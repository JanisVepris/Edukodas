<?php

namespace Edukodas\Bundle\UserBundle\Security;

use Edukodas\Bundle\UserBundle\Entity\OwnedEntityInterface;
use Edukodas\Bundle\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class OwnerVoter extends Voter
{
    const EDIT = 'edit';

    const DELETE = 'delete';

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::EDIT, self::DELETE))) {
            return false;
        }

        if (!$subject instanceof OwnedEntityInterface) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof OwnedEntityInterface) {
            return false;
        }

        /** @var OwnedEntityInterface $entity */
        $entity = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($entity, $user);
            case self::DELETE:
                return $this->canDelete($entity, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param OwnedEntityInterface $entity
     * @param User $user
     * @return bool
     */
    private function canEdit(OwnedEntityInterface $entity, User $user)
    {
        return $user === $entity->getOwner();
    }

    /**
     * @param OwnedEntityInterface $entity
     * @param User $user
     * @return bool
     */
    private function canDelete(OwnedEntityInterface $entity, User $user)
    {
        return $user === $entity->getOwner();
    }
}

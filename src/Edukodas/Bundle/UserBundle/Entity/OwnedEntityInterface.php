<?php

namespace Edukodas\Bundle\UserBundle\Entity;

interface OwnedEntityInterface
{
    /**
     * @return User
     */
    public function getOwner();
}

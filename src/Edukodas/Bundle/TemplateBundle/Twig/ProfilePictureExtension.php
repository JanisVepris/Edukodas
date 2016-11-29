<?php

namespace Edukodas\Bundle\TemplateBundle\Twig;

use Edukodas\Bundle\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\File\File;

class ProfilePictureExtension extends \Twig_Extension
{
    /**
     * @var string
     */
    private $profilePicDir;

    /**
     * ProfilePictureExtension constructor.
     *
     * @param string $profilePicDir
     */
    public function __construct(string $profilePicDir)
    {
        $this->profilePicDir = $profilePicDir;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('profile_pic', [$this, 'profilePic']),
        ];
    }

    /**
     * @param User $user
     *
     * @return null|string
     */
    public function profilePic(User $user)
    {
        if (!$user->hasPicture()) {
            return null;
        }

        $picture = $user->getPicture();

        if ($picture instanceof File) {
            return $this->profilePicDir . '/' . $picture->getFilename();
        }

        return $this->profilePicDir . '/' . $picture;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'profile_picture_extension';
    }
}

<?php

namespace Edukodas\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EdukodasUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}

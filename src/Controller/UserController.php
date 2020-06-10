<?php

namespace App\Controller;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends EasyAdminController
{
    protected function persistUserEntity( User $user)
    {
        $encodedPassword = $this->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($encodedPassword);

        parent::persistEntity($user);
    }

    protected function updateUserEntity(User $user)
    {
        $encodedPassword = $this->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($encodedPassword);

        parent::updateEntity($user);
    }

    private function encodePassword(User $user, $password)
    {
        $passwordEncoderFactory = new EncoderFactory([
            User::class => new MessageDigestPasswordEncoder('sha512', true, 5000)
        ]);

        $encoder = $passwordEncoderFactory->getEncoder($user);

        return $encoder->encodePassword($password, $user->getSalt());
    }
}





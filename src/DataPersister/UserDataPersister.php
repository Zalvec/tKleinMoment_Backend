<?php


namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDataPersister implements DataPersisterInterface
{
    // Setting the password of a new user who registers through the API endpoint, not through easyadmin
    private $entityManager;
    private $userPasswordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $userPasswordEncoder){
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    // Check if the data given is an instance of a user
    public function supports($data): bool
    {
        return $data instanceof User;
    }

    /**
     * On creation of the user, the given plain password will be encrypted and set a the password
     * Thereafter the credentials are cleared and the data is saved to the database, thus making a new user with encrypted password
     * @param User $data
     */
    public function persist($data)
    {
        if ($data->getPlainPassword()){
            $data->setPassword(
                $this->userPasswordEncoder->encodePassword($data, $data->getPlainPassword())
            );

            $data->eraseCredentials();
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
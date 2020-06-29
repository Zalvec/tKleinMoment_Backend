<?php


namespace App\Tests\Functional;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class DownloadLogResoueceTest extends ApiTestCase
{

    public function testCreateDownloadLog(){
        $client = self::createClient();

        // Test to make an post when not logged in
        $client->request('POST', '/api/download_logs', [
            'json' => [],
        ]);
        $this->assertResponseStatusCodeSame(401);

//        // Create a user and log him in
//        $user = new User();
//        $user->setEmail('user@example.com')
//            ->setFirstName('jef')
//            ->setLastname('Marcks')
//            ->setPassword('$2y$13$OlpwfPllX4IV35BO6ifcvObzHcJst3veTxMtMCLtvC04aZA/Nqpo.');
//
//        $em = self::$container->get(EntityManagerInterface::class);
//        $em->persist($user);
//        $em->flush();
//
//        $client->request('POST', '/login', [
//            'headers' => ['Content-Type' => 'application/json'],
//            'json' => [
//                'email' => 'user@example.com',
//                'password' => 'foo'
//            ]
//        ]);
//        $this->assertResponseStatusCodeSame(204);
    }
}
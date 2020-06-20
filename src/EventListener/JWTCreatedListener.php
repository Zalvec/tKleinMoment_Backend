<?php


namespace App\EventListener;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreatedListener
{
    // Creation of the JWT and modifying it's payload
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    /**
     * @param JWTCreatedEvent $event
     * @return void
     * Adding payload to the JWT when it is created
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();
        $userName = $event->getUser()->getUsername();

        /**
         * @var User $user
         * Retrieving the user from the database by it's email
         */
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $userName]);

        /**
         * Adding different information to the JWT
         */
        $payload = $event->getData();
        $payload['id'] = $user->getId();
        $payload['name'] = $user->getName();
        $payload['ip'] = $request->getClientIp();
        $payload['cosplay'] = $user->getCosplayName();
        $payload['membershipDuration'] = $user->getCreatedAtAgo();

        /**
         * Payload is set for JWT
         */
        $event->setData($payload);

//        $header        = $event->getHeader();
//        $header['cty'] = 'JWT';
//
//        $event->setHeader($header);
    }
}
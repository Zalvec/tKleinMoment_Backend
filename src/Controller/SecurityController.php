<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\AdminAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/admin/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
//         if ($this->getUser()) {
//             return $this->redirectToRoute('easyadmin');
//         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/admin/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

//    /**
//     * @Route("/admin/register", name="app_register")
//     */
//    public function register(Request $request,
//                             UserPasswordEncoderInterface $passwordEncoder,
//                             GuardAuthenticatorHandler $guardHandler,
//                             AdminAuthenticator $fromAuthenticator){
//        // TODO - use Symfony forms & valitadion
//        if ($request->isMethod('POST')) {
//            $user = new User();
//            $user->setEmail($request->request->get('email'));
//            $user->setFirstName('Mystery');
//            $user->setPassword($passwordEncoder->encodePassword(
//                $user,
//                $request->request->get('password')
//            ));
//
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($user);
//            $em->flush();
//
//            return $guardHandler->authenticateUserAndHandleSuccess(
//                $user,
//                $request,
//                $fromAuthenticator,
//                'main'
//            );
//        }
//        return $this->render('security/register.html.twig');
//    }
}

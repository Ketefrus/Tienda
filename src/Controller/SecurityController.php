<?php 

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route(
     *     "/login",
     *     name="app_login",
     *     methods={"GET","POST"}
     * )
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route(
     *     "/login-check",
     *     name="app_check_login",
     *     methods={"POST"}
     * )
     */
    function checkLogin() {

    }

    /**
     * @Route(
     *     "/logout",
     *     name="app_logout"
     * )
     */
    function logout() {

    }
}
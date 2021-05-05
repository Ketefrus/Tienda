<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use App\BLL\UserBLL;

use App\Service\FileUploader;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class UsuarioController extends AbstractController
{
    /**
     * @Route("/perfil", 
     * name="user_perfil", methods={"GET","POST"})
     */
    public function index(TokenStorageInterface $tokenStorage): Response 
    {
        $usuario = $tokenStorage->getToken()->getUser();

        return $this->render('users/index.html.twig', [
            'usuario' => $usuario,
        ]);
    }
    /**
     * @Route("/perfil-pass",
     * name="user_editar",
     * methods={"POST"})
     */
    public function cambiaPass(UserPasswordEncoderInterface $encoder, Request $request, TokenStorageInterface $tokenStorage) :Response
    {
        $nuevoPassword =
        $request->request->get('_password');
        
        $usuario = $tokenStorage->getToken()->getUser();
        $usuario->setPassword($encoder->encodePassword($usuario, $nuevoPassword));


        $this->getDoctrine()
        ->getManager()
        ->flush();

        return $this->redirectToRoute('juego_index');
    }
}

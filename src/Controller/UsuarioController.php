<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use App\BLL\UserBLL;
use App\Form\RegistrationFormType;

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
  public function index(
    TokenStorageInterface $tokenStorage
  ): Response {
    $usuario = $tokenStorage->getToken()->getUser();
    return $this->render('users/index.html.twig', [
      'usuario' => $usuario,
    ]);
  }
  /**
   * @Route("/admin/usuario/{id}",
   * name="admin_usuario",
   * methods={"GET"})
   */
  public function verUsuario(Usuario $usuario): Response
  {
    return $this->render('users/index.html.twig', [
      'usuario' => $usuario,
    ]);
  }
  /**
   * @Route("/perfil-pass",
   * name="user_editar",
   * methods={"POST"})
   */
  public function cambiaPass(
    UserPasswordEncoderInterface $encoder,
    Request $request,
    TokenStorageInterface $tokenStorage
  ): Response {
    $nuevoPassword = $request->request->get('_password');

    $usuario = $tokenStorage->getToken()->getUser();
    $usuario->setPassword($encoder->encodePassword($usuario, $nuevoPassword));

    $this->getDoctrine()
      ->getManager()
      ->flush();

    return $this->redirectToRoute('juego_index');
  }
  /**
   * @Route("admin/user_admin{id}",
   * name="user_admin",
   * methods={"POST", "GET"})
   */
  public function hacerAdmin(Usuario $usuario, UsuarioRepository $usuarioRepository)
  {
    $usuario = $usuarioRepository->find($usuario);

    $usuario->setRole('ROLE_ADMIN');

    $this->getDoctrine()
    ->getManager()
    ->flush();

    return $this->redirectToRoute('admin_usuario', ['id' => $usuario->getId()]);
  }
  /**
   * @Route("admin/listado",
   * name="user_listado",
   * methods={"GET"})
   */
  public function listar(UsuarioRepository $usuarioRepository)
  {
    $usuarios = $usuarioRepository->findAll();

    return $this->render('users/userList.html.twig', [
      'usuarios' => $usuarios,
    ]);
  }

  /**
   * @Route("/register", name="user_register")
   */
  public function register(
    FileUploader $fileUploader,
    Request $request,
    UserPasswordEncoderInterface $passwordEncoder
  ): Response {
    $user = new Usuario();
    $form = $this->createForm(RegistrationFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // encode the plain password
      $user->setPassword(
        $passwordEncoder->encodePassword(
          $user,
          $form->get('plainPassword')->getData()
        )
      );
      $imagen = $form->get('imagen')->getData();
      if ($imagen) {
        $imagenFileName = $fileUploader->upload($imagen);
        $user->setImagen($imagenFileName);
      }
      $user->setRole('ROLE_USER');
      $user->setActivo(false);
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($user);
      $entityManager->flush();

      // $this->addFlash('success', 'Usuario registrado correctamente, revise su correo electrónico.');

      return $this->redirectToRoute('app_login');
    }

    return $this->render('security/registration.html.twig', [
      'registrationForm' => $form->createView(),
    ]);
  }
}

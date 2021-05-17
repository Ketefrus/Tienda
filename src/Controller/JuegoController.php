<?php

namespace App\Controller;

use App\Entity\Juego;
use App\Form\JuegoType;
use App\Repository\JuegoRepository;
use App\Service\FileUploader;
use App\Repository\CategoriaRepository;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * @Route("/")
 */
class JuegoController extends AbstractController
{
  /** @var CategoriaRepository */
  protected $categoriaRepository;
  /** @var JuegoRepository */
  protected $juegoRepository;
  /** @var TokenStorageInterface */
  protected $tokenStorage;
  const ELEMENTOS_POR_PAGINA = 5;
  function __construct(
    CategoriaRepository $categoriaRepository,
    JuegoRepository $juegoRepository,
    TokenStorageInterface $tokenStorage
  ) {
    $this->categoriaRepository = $categoriaRepository;
    $this->juegoRepository = $juegoRepository;
    $this->tokenStorage = $tokenStorage;
  }
  /**
   * @Route("/{pagina}",
   * defaults={"pagina": 1 },
   * requirements={"pagina"="\d+"},
   * name="juego_index", methods={"GET","POST"})
   */
  public function index(int $pagina, Request $request): Response
  {
    $categorias = $this->categoriaRepository->findAll();
    $busqueda = '';
    $busqueda =
      $request->getMethod() === 'POST' ? $request->request->get('search') : '';

    $categoria = '';
    $categoria =
      $request->getMethod() === 'POST'
        ? $request->request->get('categoria')
        : '';

    $juegos = $this->juegoRepository->findJuegos(
      $busqueda,
      self::ELEMENTOS_POR_PAGINA,
      $pagina,
      '',
      $categoria
    );

    return $this->render('juego/index.html.twig', [
      'juegos' => $juegos,
      'categorias' => $categorias,
      'pagina' => $pagina,
    ]);
  }

  /**
   * @Route("cliente/mis_juegos/{pagina}",
   * defaults={"pagina": 1 },
   * requirements={"pagina"="\d+"},
   * name="juegos_cliente", methods={"GET","POST"})
   */
  public function misJuegos(int $pagina, Request $request): Response
  {
    $categorias = $this->categoriaRepository->findAll();
    $usuario = $this->tokenStorage->getToken()->getUser();
    $categoria = '';
    $categoria =
      $request->getMethod() === 'POST'
        ? $request->request->get('categoria')
        : '';
    $busqueda =
      $request->getMethod() === 'POST' ? $request->request->get('search') : '';

    $juegos = $this->juegoRepository->findJuegos(
      $busqueda,
      self::ELEMENTOS_POR_PAGINA,
      $pagina,
      $usuario->getId(),
      $categoria
    );

    return $this->render('juego/index.html.twig', [
      'juegos' => $juegos,
      'pagina' => $pagina,
      'categorias' => $categorias
    ]);
  }

  /**
   * @Route("juego/comprar/{id}", name="juego_comprar",methods={"POST"})
   */
  public function buy(Request $request, Juego $juego): Response
  {
    try {
      $usuario = $this->tokenStorage->getToken()->getUser();

      $juego->setComprador($usuario);

      $this->getDoctrine()
        ->getManager()
        ->flush();
    } catch (\Throwable $th) {
      //throw $th;
    }
    return $this->redirectToRoute('juego_show', ['id' => $juego->getId()]);
  }
  /**
   * @Route("juego/new", name="juego_new", methods={"GET","POST"})
   */
  public function new(
    Request $request,
    TokenStorageInterface $tokenStorage,
    FileUploader $fileUploader
  ): Response {
    $error = '';
    try {
      $juego = new Juego();
      $form = $this->createForm(JuegoType::class, $juego);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $imagen = $form->get('imagen')->getData();

        if ($imagen) {
          $juego->setImagenFile($imagen);
          // $newFilename = $fileUploader->upload($imagen);
          // $juego->setImagen($newFilename);
        }

        $usuario = $tokenStorage->getToken()->getUser();
        $juego->setPropietario($usuario);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($juego);
        $entityManager->flush();

        return $this->redirectToRoute('juego_index');
      }
    } catch (FileException $e) {
      $error = $e->getMessage();
    }
    return $this->render('juego/new.html.twig', [
      'juego' => $juego,
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route("juego/{id}", name="juego_show", methods={"GET"})
   */
  public function show(Juego $juego): Response
  {
    return $this->render('juego/show.html.twig', [
      'juego' => $juego,
    ]);
  }

  /**
   * @Route("juego/{id}/edit", name="juego_edit", methods={"GET","POST"})
   */
  public function edit(
    Request $request,
    Juego $juego,
    FileUploader $fileUploader
  ): Response {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $form = $this->createForm(JuegoType::class, $juego);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $juego = $form->getData();
      $imagen = $form['imagen']->getData();
      // TODO: Incluir UpdateAt en la Entidad (día 29 enero, min 38:28)
      if ($imagen) {
        $newFilename = $fileUploader->upload($imagen);
        $juego->setImagen($newFilename);
      }

      $this->getDoctrine()
        ->getManager()
        ->flush();

      return $this->redirectToRoute('juego_index');
    }

    return $this->render('juego/edit.html.twig', [
      'juego' => $juego,
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route("juego/{id}", name="juego_delete", methods={"POST"})
   */
  public function delete(Request $request, Juego $juego): Response
  {
    // $this->denyAccessUnlessGranted('ROLE_ADMIN');

    if (
      $this->isCsrfTokenValid(
        'delete' . $juego->getId(),
        $request->request->get('_token')
      )
    ) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($juego);
      $entityManager->flush();
    }

    return $this->redirectToRoute('juego_index');
  }
}

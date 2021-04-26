<?php

namespace App\Controller;

use App\Entity\Juego;
use App\Form\JuegoType;
use App\Repository\JuegoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;


/**
 * @Route("/")
 */
class JuegoController extends AbstractController
{
    /**
     * @Route("/", name="juego_index", methods={"GET","POST"})
     */
    public function index(JuegoRepository $juegoRepository, Request $request): Response
    {
        $busqueda = $request->getMethod() === 'POST' ?
            $request->request->get('search') : '';

        $juegos = $juegoRepository->findJuegos($busqueda);

        return $this->render('juego/index.html.twig', [
            'juegos' => $juegos,
        ]);
    }

    /**
     * @Route("juego/new", name="juego_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $juego = new Juego();
        $form = $this->createForm(JuegoType::class, $juego);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagen = $form->get('imagen')->getData();
            if ($imagen) {
                $imagenFileName = $fileUploader->upload($imagen);
                $juego->setImagen($imagenFileName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($juego);
            $entityManager->flush();

            return $this->redirectToRoute('juego_index');
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
    public function edit(Request $request, Juego $juego): Response
    {
        $form = $this->createForm(JuegoType::class, $juego);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

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
        if ($this->isCsrfTokenValid('delete'.$juego->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($juego);
            $entityManager->flush();
        }

        return $this->redirectToRoute('juego_index');
    }
}

<?php

namespace App\Controller\REST;

use App\BLL\JuegoBLL;
use App\Entity\Juego;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JuegoRestController extends BaseApiController
{
    /**
     * @Route("/juegos.{_format}",
     *     name="get_juegos",
     *     defaults={"_format": "json"},
     *     requirements={"_format": "json"},
     *     methods={"GET"}
     * )
     * @Route("/juegos/ordenados/{order}", name="get_juegos_ordenados")
     */
    public function getAll(Request $request, JuegoBLL $juegoBLL, string $order='id')
    {
        $categoria = $request->query->get('categoria');
        $nombre = $request->query->get('nombre');
        $descripcion = $request->query->get('descripcion');


        $juegos = $juegoBLL->getJuegosFiltrados($order, $categoria, $descripcion, $nombre);

        return $this->getResponse($juegos);
    }

    /**
     * @Route("/juegos/{id}.{_format}",
     *     name="get_juego",
     *     requirements={"id": "\d+", "_format": "json"},
     *     defaults={"_format": "json"},
     *     methods={"GET"}
     * )
     */
    public function getOne(Juego $juego, JuegoBLL $juegoBLL)
    {
        return $this->getResponse($juegoBLL->toArray($juego));
    }

    /**
     * @Route("/juegos.{_format}", name="juegos",
     *      defaults={"_format": "json"},
     *      requirements={"_format": "json"},
     *     methods={"POST"}
     * )
     */
    public function post(Request $request, JuegoBLL $juegoBLL)
    {
        $data = $this->getContent($request);
        $juego = $juegoBLL->nuevo($data);
        return $this->getResponse(
            $juego, Response:: HTTP_CREATED
        );
    }

   /**
     * @Route("/juegos/{id}.{_format}",
     *     name="update_juego",
     *     requirements={"id": "\d+", "_format": "json"},
     *     defaults={"_format": "json"},
     *     methods={"PUT"}
     * )
     */
    public function update(Request $request, Juego $juego, JuegoBLL $juegoBLL)
    {
        $data = $this->getContent($request);

        $juego = $juegoBLL->update($juego, $data);

        return $this->getResponse($juego);
    }

    /**
     * @Route("/juegos/{id}.{_format}",
     *     name="delete_juego",
     *     requirements={"id": "\d+", "_format": "json"},
     *     defaults={"_format": "json"},
     *     methods={"DELETE"}
     * )
     */
    public function delete(Juego $juego, JuegoBLL $juegoBLL)
    {
        $juegoBLL->delete($juego);
        return $this->getResponse(null, Response:: HTTP_NO_CONTENT );
    }
}
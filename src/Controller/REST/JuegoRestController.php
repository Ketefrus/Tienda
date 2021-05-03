<?php

namespace App\Controller\REST;

use App\BLL\JuegoBLL;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JuegoRestController extends BaseApiController
{
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
        $contacto = $juegoBLL->nuevo($data);
        return $this->getResponse(
            $contacto, Response:: HTTP_CREATED
        );
    }
}
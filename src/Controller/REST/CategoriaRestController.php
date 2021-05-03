<?php

namespace App\Controller\REST;

use App\BLL\CategoriaBLL;
use App\BLL\JuegoBLL;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriaRestController extends BaseApiController
{
    /**
     * @Route("/categorias.{_format}", name="post_categorias",
     *      defaults={"_format": "json"},
     *      requirements={"_format": "json"},
     *     methods={"POST"}
     * )
     */
    public function post(Request $request, CategoriaBLL $categoriaBLL)
    {
        $data = $this->getContent($request);
        $ciudad = $categoriaBLL->nuevo($data);
        return $this->getResponse(
            $ciudad, Response:: HTTP_CREATED
        );
    }

}
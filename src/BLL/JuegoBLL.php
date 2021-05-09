<?php

namespace App\BLL;

use App\Entity\Categoria;
use App\Entity\Juego;
use Exception;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
class JuegoBLL extends BaseBLL
{
    private function guardaImagen($request, $juego, $data) {
        $arr_img = explode (',', $data['imagen']);
        if ( count ($arr_img) < 2)
            throw new BadRequestHttpException('formato de imagen incorrecto');

        $imgFoto = base64_decode ($arr_img[1]);
        if (!is_null($imgFoto))
        {
            $fileName = $data['nombreImg'] . '-'. time() . '.jpg';
            $juego->setImagen($fileName);
            $ifp = fopen ($this->imgDirectory . '/' . $fileName, "wb");
            if ($ifp)
            {
                $ok = fwrite ($ifp, $imgFoto);

                fclose ($ifp);

                if ($ok)
                    return $this->guardaValidando($juego);
            }
        }

        throw new \Exception('No se ha podido cargar la imagen del contacto');
    }

    public function actualiza(Request $request, Juego $juego, array $data)
    {
        $categoria = $this->em->getRepository(Categoria::class)->find($data['categoria']);

        $juego = new Juego();
        $juego->setNombre($data['nombre']);
        $juego->setPrecio($data['precio']);
        $juego->setCategoria($categoria);
        $juego->setDescripcion($data['descripcion']);
        $juego->setPropietario($this->getUser());

        return $this->guardaImagen($request, $juego, $data);
    }

    public function nuevo(Request $request, array $data) 
    {
        $juego = new Juego();
        return $this->actualiza($request, $juego, $data);
    }

    public function getJuegosFiltrados(
        string $order, string $categoria=null, string $descripcion=null, string $nombre=null) : array
    {
        $juegos = $this->em->getRepository(Juego:: class )->getJuegosFiltrados(
            $order, $categoria, $descripcion, $nombre);

        return $this->entitiesToArray($juegos);
    }

    public function update(Request $request, Juego $juego, array $data)
    {
        return $this->actualiza($request, $juego, $data);
    }
    public function toArray($juego)
    {
        if ( is_null ($juego))
            return null;
        if (!($juego instanceof Juego))
            throw new Exception("La entidad no es un Juego");

        return [
            'id' => $juego->getId(),
            'nombre' => $juego->getNombre(),
            'categoria' => $juego->getCategoria()->getNombre(),
            'precio' => $juego->getPrecio(),
            'descripcion' => $juego->getDescripcion(),
            'propietario' => $juego->getPropietario(),
        ];
    }
}
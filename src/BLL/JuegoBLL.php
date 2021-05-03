<?php

namespace App\BLL;

use App\Entity\Categoria;
use App\Entity\Juego;
use Exception;

class JuegoBLL extends BaseBLL
{
    public function nuevo(array $data)
    {
        $categoria = $this->em->getRepository(Categoria::class)->find($data['categoria']);

        $juego = new Juego();
        $juego->setNombre($data['nombre']);
        $juego->setPrecio($data['precio']);
        $juego->setCategoria($categoria);
        $juego->setDescripcion($data['descripcion']);
        return $this->guardaValidando($juego);
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
            'descripcion' => $juego->getDescripcion()
        ];
    }
}
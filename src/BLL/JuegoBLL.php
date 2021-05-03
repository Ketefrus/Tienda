<?php

namespace App\BLL;

use App\Entity\Categoria;
use App\Entity\Juego;
use Doctrine\Common\Collections\Collection;
use Exception;

class JuegoBLL extends BaseBLL
{
    public function actualiza(Juego $juego, array $data)
    {
        $categoria = $this->em->getRepository(Categoria::class)->find($data['categoria']);

        $juego = new Juego();
        $juego->setNombre($data['nombre']);
        $juego->setPrecio($data['precio']);
        $juego->setCategoria($categoria);
        $juego->setDescripcion($data['descripcion']);

        return $this->guardaValidando($juego);
    }

    public function nuevo(array $data) 
    {
        $juego = new Juego();
        return $this->actualiza($juego, $data);
    }

    public function getJuegosFiltrados(
        string $order, string $categoria=null, string $descripcion=null, string $nombre=null) : array
    {
        $juegos = $this->em->getRepository(Juego:: class )->getJuegosFiltrados(
            $order, $categoria, $descripcion, $nombre);

        return $this->entitiesToArray($juegos);
    }

    public function update(Juego $juego, array $data)
    {
        return $this->actualiza($juego, $data);
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
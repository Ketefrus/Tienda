<?php

namespace App\BLL;

use App\Entity\Categoria;
use Exception;

class CategoriaBLL extends BaseBLL
{
    public function nuevo(array $data)
    {
        $categoria = new Categoria();
        $categoria->setNombre($data['nombre']);

        return $this->guardaValidando($categoria);
    }

    public function toArray($categoria)
    {
        if ( is_null ($categoria))
            return null;
        if (!($categoria instanceof Categoria))
            throw new Exception("La entidad no es una Categoria");

        return [
            'id' => $categoria->getId(),
            'nombre' => $categoria->getNombre()
        ];
    }
}
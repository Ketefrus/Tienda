<?php

namespace App\BLL;

use App\Entity\Usuario;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserBLL extends BaseBLL
{
    /** @var UserPasswordEncoderInterface $encoder
     */
    private $encoder;

    public function setEncoder(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function getAll() : array
    {
        $usuarios = $this->em->getRepository(Usuario:: class )->findAll();

        return $this->entitiesToArray($usuarios);
    }

    public function nuevo(array $data)
    {
        $user = new Usuario();
        $user->setNombre($data['nombre']);
        $user->setPassword($this->encoder->encodePassword($user, $data['password']));
        $user->setEmail($data['email']);
        $user->setRole($data['role']);
        $user->setActivo(true);

        return $this->guardaValidando($user);
    }

    public function profile()
    {
        $user = $this->getUser();

        return $this->toArray($user);
    }

    public function cambiaPassword(string $nuevoPassword)
    {
        $user = $this->getUser();
        $user->setPassword($this->encoder->encodePassword($user, $nuevoPassword));
        return $this->guardaValidando($user);
    }

    public function toArray(Usuario $user)
    {
        if ( is_null ($user))
            return null;

        return [
            'id' => $user->getId(),
            'nombre' => $user->getNombre(),
            'email' => $user->getEmail(),
            'role' => $user->getRole()
        ];
    }
}
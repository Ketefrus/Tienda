<?php

namespace App\BLL;

use App\Entity\Usuario;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class BaseBLL
{
    /** @var EntityManagerInterface */
    protected $em;
    /** @var ValidatorInterface */
    private $validator;
    /** @var TokenStorageInterface */
    protected $tokenStorage;

    function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->tokenStorage = $tokenStorage;
    }

    private function validate($entity)
    {
        $errors = $this->validator->validate($entity);
        if (count($errors) > 0)
        {
            $strError = '';
            foreach($errors as $error)
            {
                if (!empty($strError))
                    $strError .= '\n';
                $strError .= $error->getMessage();
            }

            throw new BadRequestHttpException($strError);
        }
    }

    protected function guardaValidando($entity)
    {
        $this->validate($entity);

        $this->em->persist($entity);
        $this->em->flush();

        return $this->toArray($entity);
    }

    public function entitiesToArray(array $entities) : ?array
    {
        if ( is_null ($entities))
            return null;
        $arr = [];
        foreach ($entities as $entity)
            $arr[] = $this->toArray($entity);
        return $arr;
    }

    public function delete($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    protected function getUser() : ? UserInterface
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}
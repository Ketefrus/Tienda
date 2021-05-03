<?php

namespace App\BLL;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseBLL
{
    /** @var EntityManagerInterface */
    protected $em;
    /** @var ValidatorInterface */
    private $validator;

    function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->validator = $validator;
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
}
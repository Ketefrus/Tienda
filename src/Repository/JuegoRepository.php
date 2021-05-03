<?php

namespace App\Repository;

use App\Entity\Juego;
use Doctrine\Common\Collections\Collection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Juego|null find($id, $lockMode = null, $lockVersion = null)
 * @method Juego|null findOneBy(array $criteria, array $orderBy = null)
 * @method Juego[]    findAll()
 * @method Juego[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JuegoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Juego::class);
    }

    public function findJuegos(string $busqueda)
    {
        $qb = $this->createQueryBuilder('j');
        
        $qb->addSelect('categoria');
        $qb->innerJoin('j.categoria', 'categoria');

        if (!empty($busqueda)) {
            $qb
                ->where(
                    $qb->expr()->like('j.nombre', ':busqueda')
                    // )->andWhere(
                    //     $qb->expr()->like('j.descripcion', ":busqueda")
                )
                ->setParameter('busqueda', '%' . $busqueda . '%');
        }
        $qb->orderBy('j.nombre', 'ASC');

        return $qb->getQuery()->execute();
    }

    public function getJuegosFiltrados(
        string $order, string $categoria=null, string $descripcion=null,  string $nombre=null) : array
    {
        $qb = $this->createQueryBuilder('juego');
        $qb->innerJoin('juego.categoria', 'categoria');

        if (isset($categoria))
        {
            $qb->andWhere($qb->expr()->like('categoria.nombre', ':categoria'))
            ->setParameter('categoria', "%$categoria%");
        }

        if (isset($nombre))
        {
            $qb->andWhere($qb->expr()->like('juego.nombre', ':nombre'))
            ->setParameter('nombre', "%$nombre%");
        }
    
        if (isset($descripcion))
        {
            $qb->andWhere($qb->expr()->like('juego.descripcion', ':descripcion'))
            ->setParameter('descripcion', "%$descripcion%");
        }
        $qb->orderBy("juego.$order", 'ASC');
        
        return $qb->getQuery()->getResult();
    }
    // +    public function findContactos(string $busqueda) {
    //     +        $qb = $this->createQueryBuilder('c');
    //     +        $qb->where(
    //     +            $qb->expr()->notLike('c.nombre', ":busqueda")
    //     +        )->andWhere(
    //     +            $qb->expr()->like('c.telefono', ":busqueda")
    //     +        )->setParameter('busqueda', '%'.$busqueda.'%');
    //     +        $qb->orderBy('c.nombre', 'ASC');
    //     +
    //     +        return $qb->getQuery()->execute();
    //     +    }
    // /**
    //  * @return Juego[] Returns an array of Juego objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Juego
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

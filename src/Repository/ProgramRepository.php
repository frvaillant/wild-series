<?php

namespace App\Repository;

use App\Entity\Program;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Program|null find($id, $lockMode = null, $lockVersion = null)
 * @method Program|null findOneBy(array $criteria, array $orderBy = null)
 * @method Program[]    findAll()
 * @method Program[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Program::class);
    }

    /**
     * @param string $title
     * @return int Returns id Of last program inserted
     */

    public function getHomeProgramId(): Program
    {
        $homeProgram = $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
        return $homeProgram[0];

    }

    public function getAllProgramsForHome()
    {
        $programs = $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->innerJoin('p.category', 'c' )
            ->addSelect('c')
            ->innerJoin('p.actors', 'a' )
            ->addSelect('a')
            ->innerJoin('p.seasons', 's' )
            ->addSelect('s')
            ->getQuery()
            ->getResult();
        return $programs;

    }



    // /**
    //  * @return Program[] Returns an array of Program objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Program
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

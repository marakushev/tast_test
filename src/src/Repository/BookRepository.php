<?php


namespace App\Repository;


use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class BookRepository
 * @package App\Repository
 */
class BookRepository extends ServiceEntityRepository
{
    /**
     * BookRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @param string $name
     * @return array
     */
    public function search(string $name)
    {
        return $this->createQueryBuilder('o')
            ->where('o.name LIKE :name')
            ->setParameter('name', $name.'%')
            ->getQuery()
            ->getResult();
    }
}
<?php

namespace App\Repository;

use App\Entity\BookRenting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookRenting>
 *
 * @method BookRenting|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookRenting|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookRenting[]    findAll()
 * @method BookRenting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRentingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookRenting::class);
    }

    public function add(BookRenting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BookRenting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //Get all books that are rented for more than a month 
    public function returnOldBookRenting()
    {
        $current_date = new \DateTime();

        //Get books where limit_date < to the current date
        $qb = $this->createQueryBuilder('cc')
             ->where('cc.limit_date < :current_date')
             ->setParameter('current_date', $current_date);

         $oldBookRentings = $qb->getQuery()->getResult();
         $entityManager = $this->getEntityManager();

        //Set the renting_end (return) all the old books
        foreach ($oldBookRentings as $oldBookRenting) {
            $oldBookRenting->setRentingEnd(new \DateTime());
            $entityManager->persist($oldBookRenting);
        }
        $entityManager->flush();
    }
}

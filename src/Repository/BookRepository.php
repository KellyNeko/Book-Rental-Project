<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    private $bookRenting;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function add(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //Get all the books rented by the user
    public function findAllUserBooks(User $user): array
    {
        //Find books where there's no renting_end (books are rented)
        $qb = $this->createQueryBuilder('b')
            ->innerJoin('b.bookRentings', 'r')
            ->where('r.renting_end is NULL AND r.user = :user')
            ->setParameter('user', $user)
            ->orderBy('r.id', 'DESC');

        $query = $qb->getQuery();

        return $query->execute();
    }

    //Get all not rented books
    public function findAllFree(): array
    {
        //Find books where there's a renting_end (books are not rented)
        $qb = $this->createQueryBuilder('b')
            ->leftJoin('b.bookRentings', 'r')
            ->where('r.renting_end is not NULL or r is NULL');

        $query = $qb->getQuery();

        return $query->execute();
    }
}

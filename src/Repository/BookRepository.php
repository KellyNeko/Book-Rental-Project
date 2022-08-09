<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\User;
use App\Entity\Author;
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

    //Get the books with the choosen author, reference, or category
    public function findCategories(): array
    {
    }

    //Get the books with the choosen author, reference, or category
    public function findByQuery(string $findQuery): array
    {
        $qb = $this->createQueryBuilder('b')
            ->innerJoin('b.author', 'a')
            ->innerJoin('b.bookCategories', 'bc')
            ->innerJoin('bc.category', 'c')
        ;
        $qb    
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->orX(
                        $qb->expr()->like('a.last_name', ':findQuery'),
                        $qb->expr()->like('a.first_name', ':findQuery')
                    )
                )
            )
            ->orWhere(
                $qb->expr()->andX(
                    $qb->expr()->orX(
                        $qb->expr()->like('b.reference', ':findQuery')
                    )
                )
            )
            ->orWhere(
                $qb->expr()->andX(
                    $qb->expr()->orX(
                        $qb->expr()->like('c.label', ':findQuery')
                    )
                )
            )
            ->setParameter('findQuery', '%' . $findQuery . '%')
            ->orderBy('b.id', 'ASC');
        ;

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function findByQueryAndUser(string $findQuery, User $user): array
    {
        $qb = $this->createQueryBuilder('b')
            ->innerJoin('b.author', 'a')
            ->innerJoin('b.bookCategories', 'bc')
            ->innerJoin('bc.category', 'c')
            ->innerJoin('b.bookRentings', 'r')
        ;
        $qb    
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->orX(
                        $qb->expr()->like('a.last_name', ':findQuery'),
                        $qb->expr()->like('a.first_name', ':findQuery')
                    )
                )
            )
            ->andWhere('r.renting_end is NULL AND r.user = :user')
            ->orWhere(
                $qb->expr()->andX(
                    $qb->expr()->orX(
                        $qb->expr()->like('b.reference', ':findQuery')
                    )
                )
            )
            ->andWhere('r.renting_end is NULL AND r.user = :user')
            ->orWhere(
                $qb->expr()->andX(
                    $qb->expr()->orX(
                        $qb->expr()->like('c.label', ':findQuery')
                    )
                )
            )
            ->andWhere('r.renting_end is NULL AND r.user = :user')
            ->setParameter('findQuery', '%' . $findQuery . '%')
            ->setParameter('user', $user)
            ->orderBy('r.id', 'DESC')
        ;

        $query = $qb->getQuery();

        return $query->execute();
    }
}

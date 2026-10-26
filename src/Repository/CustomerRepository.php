<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<Customer> */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    /** @return list<array{name: string, email: string, ordersCount: int|string}> */
    public function findWithOrdersCount(): array
    {
        return $this->createQueryBuilder('customer')
            ->select('customer.name AS name, customer.email AS email, COUNT(bookOrder.id) AS ordersCount')
            ->leftJoin('customer.orders', 'bookOrder')
            ->groupBy('customer.id')
            ->orderBy('customer.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /** @return list<array{name: string, totalSpent: int|string}> */
    public function findTopThreeByTotalSpent(): array
    {
        return $this->createQueryBuilder('customer')
            ->select('customer.name AS name, COALESCE(SUM(book.price), 0) AS totalSpent')
            ->join('customer.orders', 'bookOrder')
            ->join('bookOrder.books', 'book')
            ->groupBy('customer.id')
            ->orderBy('totalSpent', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }
}

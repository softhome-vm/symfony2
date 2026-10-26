<?php

namespace App\Repository;

use App\Entity\BookOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<BookOrder> */
class BookOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookOrder::class);
    }

    /** @return list<array{id: int, customerName: string, total: int|string}> */
    public function findWithTotals(): array
    {
        return $this->createQueryBuilder('bookOrder')
            ->select('bookOrder.id AS id, customer.name AS customerName, COALESCE(SUM(book.price), 0) AS total')
            ->join('bookOrder.customer', 'customer')
            ->leftJoin('bookOrder.books', 'book')
            ->groupBy('bookOrder.id')
            ->orderBy('bookOrder.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAverageTotal(): float
    {
        $orderTotals = $this->createQueryBuilder('bookOrder')
            ->select('bookOrder.id AS id, COALESCE(SUM(book.price), 0) AS total')
            ->leftJoin('bookOrder.books', 'book')
            ->groupBy('bookOrder.id')
            ->getQuery()
            ->getResult();

        if ($orderTotals === []) {
            return 0.0;
        }

        $total = array_sum(array_map(static fn (array $order): float => (float) $order['total'], $orderTotals));

        return $total / count($orderTotals);
    }
}

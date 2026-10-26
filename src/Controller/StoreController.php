<?php

namespace App\Controller;

use App\Repository\BookOrderRepository;
use App\Repository\BookRepository;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StoreController extends AbstractController
{
    #[Route('/store', name: 'store_index', methods: ['GET'])]
    public function index(
        CustomerRepository $customerRepository,
        BookOrderRepository $bookOrderRepository,
        BookRepository $bookRepository,
    ): Response {
        return $this->render('store/index.html.twig', [
            'customers' => $customerRepository->findWithOrdersCount(),
            'orders' => $bookOrderRepository->findWithTotals(),
            'topCustomers' => $customerRepository->findTopThreeByTotalSpent(),
            'averageOrderTotal' => $bookOrderRepository->findAverageTotal(),
            'mostExpensiveBook' => $bookRepository->findMostExpensive(),
        ]);
    }
}

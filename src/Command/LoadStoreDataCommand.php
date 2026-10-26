<?php

namespace App\Command;

use App\Entity\Book;
use App\Entity\BookOrder;
use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:load-store-data', description: 'Loads test data for the book store.')]
class LoadStoreDataCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($this->entityManager->getRepository(Customer::class)->count() > 0) {
            $output->writeln('Тестовые данные уже загружены.');

            return Command::SUCCESS;
        }

        $anna = new Customer('Анна', 'anna@example.com');
        $boris = new Customer('Борис', 'boris@example.com');
        $vera = new Customer('Вера', 'vera@example.com');
        $gleb = new Customer('Глеб', 'gleb@example.com');

        $dune = new Book('Дюна', 900);
        $foundation = new Book('Основание', 750);
        $master = new Book('Мастер и Маргарита', 600);
        $war = new Book('Война и мир', 1200);

        $orders = [
            (new BookOrder($anna))->addBook($dune)->addBook($foundation),
            (new BookOrder($anna))->addBook($war),
            (new BookOrder($boris))->addBook($master),
            (new BookOrder($vera))->addBook($dune)->addBook($war),
        ];

        foreach ([$anna, $boris, $vera, $gleb, $dune, $foundation, $master, $war, ...$orders] as $entity) {
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();
        $output->writeln('Тестовые данные загружены.');

        return Command::SUCCESS;
    }
}

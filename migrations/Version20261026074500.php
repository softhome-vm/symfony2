<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261026074500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates customers, books, orders, and the order-to-book relation.';
    }

    public function up(Schema $schema): void
    {
        $customers = $schema->createTable('customer');
        $customers->addColumn('id', 'integer', ['autoincrement' => true]);
        $customers->addColumn('name', 'string', ['length' => 255]);
        $customers->addColumn('email', 'string', ['length' => 255]);
        $customers->setPrimaryKey(['id']);

        $books = $schema->createTable('book');
        $books->addColumn('id', 'integer', ['autoincrement' => true]);
        $books->addColumn('title', 'string', ['length' => 255]);
        $books->addColumn('price', 'integer');
        $books->setPrimaryKey(['id']);

        $orders = $schema->createTable('book_orders');
        $orders->addColumn('id', 'integer', ['autoincrement' => true]);
        $orders->addColumn('customer_id', 'integer');
        $orders->setPrimaryKey(['id']);
        $orders->addIndex(['customer_id'], 'IDX_CCF8F6639395C3F3');
        $orders->addForeignKeyConstraint('customer', ['customer_id'], ['id']);

        $orderBooks = $schema->createTable('book_order_book');
        $orderBooks->addColumn('book_order_id', 'integer');
        $orderBooks->addColumn('book_id', 'integer');
        $orderBooks->setPrimaryKey(['book_order_id', 'book_id']);
        $orderBooks->addIndex(['book_order_id'], 'IDX_19B9D39594607B61');
        $orderBooks->addIndex(['book_id'], 'IDX_19B9D39516A2B381');
        $orderBooks->addForeignKeyConstraint('book_orders', ['book_order_id'], ['id'], ['onDelete' => 'CASCADE']);
        $orderBooks->addForeignKeyConstraint('book', ['book_id'], ['id'], ['onDelete' => 'CASCADE']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('book_order_book');
        $schema->dropTable('book_orders');
        $schema->dropTable('book');
        $schema->dropTable('customer');
    }
}

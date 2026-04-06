<?php
declare(strict_types=1);

interface ITransactionStorage
{
    /**
     * Adds a transaction to storage.
     *
     * @param Transaction $transaction Transaction object to add.
     * @return void
     */
    public function addTransaction(Transaction $transaction): void;

    /**
     * Removes a transaction by its ID.
     *
     * @param int $id Transaction ID to remove.
     * @return void
     */
    public function removeTransactionById(int $id): void;

    /**
     * Returns all stored transactions.
     *
     * @return Transaction[] Array of transactions.
     */
    public function getAllTransactions(): array;

    /**
     * Finds a transaction by its ID.
     *
     * @param int $id Transaction ID.
     * @return Transaction|null Found transaction or null if not found.
     */
    public function findById(int $id): ?Transaction;
}
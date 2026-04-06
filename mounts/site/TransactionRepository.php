<?php
declare(strict_types=1);

require_once "TransactionStorageInterface.php";
class TransactionRepository implements ITransactionStorage
{
    /**
     * @var Transaction[] List of stored transactions.
     */
    private array $transactions = [];

    /**
     * Adds a transaction to the repository.
     *
     * @param Transaction $transaction Transaction to add.
     * @return void
     */
    public function addTransaction(Transaction $transaction): void
    {
        $this->transactions[] = $transaction;
    }

    /**
     * Removes a transaction by its ID.
     *
     * @param int $id Transaction ID.
     * @return void
     */
    public function removeTransactionById(int $id): void
    {
        for ($i = 0; $i < count($this->transactions); $i++) {
            if ($this->transactions[$i]->getTransactionID() === $id) {
                array_splice($this->transactions, $i, 1);
                return;
            }
        }
    }

    /**
     * Returns all transactions.
     *
     * @return Transaction[] Array of transactions.
     */
    public function getAllTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * Finds a transaction by ID.
     *
     * @param int $id Transaction ID.
     * @return Transaction|null Found transaction or null.
     */
    public function findById(int $id): ?Transaction
    {
        foreach ($this->transactions as $transaction) {
            if ($transaction->getTransactionID() === $id) {
                return $transaction;
            }
        }

        return null;
    }
}
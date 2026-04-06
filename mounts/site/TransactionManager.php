<?php
declare(strict_types=1);

class TransactionManager
{
    /**
     * @param ITransactionStorage $repository Transaction storage.
     */
    public function __construct(private ITransactionStorage $repository)
    {
    }

    /**
     * Calculates the total amount of all transactions.
     *
     * @return float Total amount.
     */
    public function calculateTotalAmount(): float
    {
        $totalAmount = 0.0;

        foreach ($this->repository->getAllTransactions() as $transaction) {
            $totalAmount += $transaction->getTransactionAmount();
        }

        return $totalAmount;
    }

    /**
     * Calculates total amount within a date range.
     *
     * @param string $startDate Start date.
     * @param string $endDate End date.
     * @return float Total amount within the range.
     */
    public function calculateTotalAmountByDateRange(string $startDate, string $endDate): float
    {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $total = 0.0;

        foreach ($this->repository->getAllTransactions() as $transaction) {
            $transactionDate = $transaction->getTransactionDate();

            if ($transactionDate >= $start && $transactionDate <= $end) {
                $total += $transaction->getTransactionAmount();
            }
        }

        return $total;
    }

    /**
     * Counts transactions by merchant.
     *
     * @param string $merchant Merchant name.
     * @return int Number of transactions.
     */
    public function countTransactionsByMerchant(string $merchant): int
    {
        $count = 0;

        foreach ($this->repository->getAllTransactions() as $transaction) {
            if ($transaction->getTransactionMerchant() === $merchant) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Sorts transactions by date (ascending).
     *
     * @return Transaction[] Sorted transactions.
     */
    public function sortTransactionsByDate(): array
    {
        $transactions = $this->repository->getAllTransactions();

        usort($transactions, function (Transaction $a, Transaction $b): int {
            return $a->getTransactionDate() <=> $b->getTransactionDate();
        });

        return $transactions;
    }

    /**
     * Sorts transactions by amount (descending).
     *
     * @return Transaction[] Sorted transactions.
     */
    public function sortTransactionsByAmountDesc(): array
    {
        $transactions = $this->repository->getAllTransactions();

        usort($transactions, function (Transaction $a, Transaction $b): int {
            return $b->getTransactionAmount() <=> $a->getTransactionAmount();
        });

        return $transactions;
    }
}
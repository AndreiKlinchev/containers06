<?php
declare(strict_types=1);

class Transaction
{
    private int $id;
    private DateTime $date;
    private float $amount;
    private string $description;
    private string $merchant;

    /**
     * Creates a new transaction instance.
     *
     * @param int $id Unique transaction identifier.
     * @param string $date Transaction date in a format supported by DateTime.
     * @param float $amount Transaction amount.
     * @param string $description Description of the transaction.
     * @param string $merchant Merchant name.
     */
    public function __construct(
        int $id,
        string $date,
        float $amount,
        string $description,
        string $merchant
    ) {
        $this->id = $id;
        $this->date = new DateTime($date);
        $this->amount = $amount;
        $this->description = $description;
        $this->merchant = $merchant;
    }

    /**
     * Returns the number of days passed since the transaction date.
     *
     * @return int Number of days since the transaction.
     */
    public function getDaysSinceTransaction(): int
    {
        return (int)$this->date->diff(new DateTime())->days;
    }

    /**
     * Returns the transaction ID.
     *
     * @return int Transaction ID.
     */
    public function getTransactionID(): int
    {
        return $this->id;
    }

    /**
     * Returns the transaction date.
     *
     * @return DateTime Transaction date object.
     */
    public function getTransactionDate(): DateTime
    {
        return $this->date;
    }

    /**
     * Returns the transaction amount.
     *
     * @return float Transaction amount.
     */
    public function getTransactionAmount(): float
    {
        return $this->amount;
    }

    /**
     * Returns the transaction description.
     *
     * @return string Description of the transaction.
     */
    public function getTransactionDescription(): string
    {
        return $this->description;
    }

    /**
     * Returns the merchant name.
     *
     * @return string Merchant name.
     */
    public function getTransactionMerchant(): string
    {
        return $this->merchant;
    }
}
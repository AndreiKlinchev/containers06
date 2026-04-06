<?php
declare(strict_types=1);

final class TransactionTableRenderer
{
    /**
     * Generates an HTML table from a list of transactions.
     *
     * @param Transaction[] $transactions Array of transactions.
     * @return string HTML table.
     */
    public function render(array $transactions): string
    {
        $htmlTable = "
        <table border='1'>
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Date</td>
                    <td>Amount</td>
                    <td>Description</td>
                    <td>Merchant</td>
                    <td>Days since</td>
                </tr>
            </thead>
            <tbody>";

        foreach ($transactions as $el) {
            $htmlTable .= "<tr>";
            $htmlTable .= "<td>{$el->getTransactionID()}</td>";
            $htmlTable .= "<td>{$el->getTransactionDate()->format('Y-m-d')}</td>";
            $htmlTable .= "<td>{$el->getTransactionAmount()}</td>";
            $htmlTable .= "<td>{$el->getTransactionDescription()}</td>";
            $htmlTable .= "<td>{$el->getTransactionMerchant()}</td>";
            $htmlTable .= "<td>{$el->getDaysSinceTransaction()}</td>";
            $htmlTable .= "</tr>";
        }

        $htmlTable .= "</tbody></table>";

        return $htmlTable;
    }
}
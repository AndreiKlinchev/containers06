<?php
include "Transaction.php";
include "TransactionManager.php";
include "TransactionRepository.php";
include "TransactionTableRenderer.php";

$transactionData = [
    [
        "id" => 1,
        "date" =>"2019-01-01",
        "amount" => 100.00,
        "description" => "Payment for groceries",
        "merchant" => "SuperMart",
    ],
    [
        "id" => 2,
        "date" =>"2020-02-15",
        "amount" => 75.50,
        "description" => "Dinner with friends",
        "merchant" => "Local Restaurant",
    ],
    [
        "id" => 3,
        "date" =>"2020-03-10",
        "amount" => 45.20,
        "description" => "Gas refill",
        "merchant" => "Fuel Station",
    ],
    [
        "id" => 4,
        "date" =>"2020-04-05",
        "amount" => 120.00,
        "description" => "Clothing purchase",
        "merchant" => "Fashion Store",
    ],
    [
        "id" => 5,
        "date" =>"2020-05-12",
        "amount" => 15.99,
        "description" => "Music subscription",
        "merchant" => "Streamify",
    ],
    [
        "id" => 6,
        "date" =>"2020-06-18",
        "amount" => 220.75,
        "description" => "Electronics purchase",
        "merchant" => "TechWorld",
    ],
    [
        "id" => 7,
        "date" =>"2020-07-03",
        "amount" => 60.00,
        "description" => "Gym membership",
        "merchant" => "FitClub",
    ],
    [
        "id" => 8,
        "date" =>"2020-08-21",
        "amount" => 30.40,
        "description" => "Book purchase",
        "merchant" => "BookStore",
    ],
    [
        "id" => 9,
        "date" =>"2020-09-14",
        "amount" => 12.50,
        "description" => "Coffee and snacks",
        "merchant" => "Cafe Corner",
    ],
    [
        "id" => 10,
        "date" =>"2020-10-02",
        "amount" => 89.99,
        "description" => "Online course",
        "merchant" => "EduPlatform",
    ],
    [
        "id" => 11,
        "date" =>"2020-11-11",
        "amount" => 150.00,
        "description" => "Furniture payment",
        "merchant" => "HomeStore",
    ],
    [
        "id" => 12,
        "date" =>"2020-12-24",
        "amount" => 200.00,
        "description" => "Christmas gifts",
        "merchant" => "GiftShop",
    ],
    [
        "id" => 13,
        "date" =>"2021-01-09",
        "amount" => 18.75,
        "description" => "Movie tickets",
        "merchant" => "CinemaCity",
    ],
    [
        "id" => 14,
        "date" =>"2021-02-14",
        "amount" => 95.30,
        "description" => "Valentine dinner",
        "merchant" => "Romantic Restaurant",
    ],
    [
        "id" => 15,
        "date" =>"2021-03-08",
        "amount" => 40.00,
        "description" => "Flower purchase",
        "merchant" => "Flower Shop",
    ],
    [
        "id" => 16,
        "date" => "2021-04-01",
        "amount" => 55.60,
        "description" => "Taxi rides",
        "merchant" => "CityTaxi",
    ],
    [
        "id" => 17,
        "date" => "2021-05-20",
        "amount" => 130.00,
        "description" => "New headphones",
        "merchant" => "AudioStore",
    ],
];

$transactionRepository = new TransactionRepository();

foreach($transactionData as $transaction){
    $transactionRepository->addTransaction(new Transaction($transaction["id"],$transaction["date"],
    $transaction["amount"],$transaction["description"],$transaction["merchant"]));
}

$transactionManager = new TransactionManager($transactionRepository);
$renderer = new TransactionTableRenderer();

$foundTransaction = $transactionRepository->findById(5);

$totalAmount = $transactionManager->calculateTotalAmount();
$totalByDateRange = $transactionManager->calculateTotalAmountByDateRange("2020-01-01", "2020-12-31");
$merchantCount = $transactionManager->countTransactionsByMerchant("GiftShop");

$sortedByDate = $transactionManager->sortTransactionsByDate();
$sortedByAmountDesc = $transactionManager->sortTransactionsByAmountDesc();

$transactionRepository->removeTransactionById(3);
$allTransactionsAfterDelete = $transactionRepository->getAllTransactions();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction System Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.5;
        }

        h1, h2 {
            margin-top: 30px;
        }

        table {
            border-collapse: collapse;
            margin-top: 15px;
            width: 100%;
        }

        td, th {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .block {
            margin-bottom: 25px;
            padding: 12px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

<h1>Transaction System Test</h1>

<div class="block">
    <h2>1. Search transaction by ID</h2>
    <?php if ($foundTransaction !== null): ?>
        <p><strong>Transaction found:</strong></p>
        <p>ID: <?= $foundTransaction->getTransactionID(); ?></p>
        <p>Date: <?= $foundTransaction->getTransactionDate()->format('Y-m-d'); ?></p>
        <p>Amount: <?= $foundTransaction->getTransactionAmount(); ?></p>
        <p>Description: <?= htmlspecialchars($foundTransaction->getTransactionDescription()); ?></p>
        <p>Merchant: <?= htmlspecialchars($foundTransaction->getTransactionMerchant()); ?></p>
        <p>Days since transaction: <?= $foundTransaction->getDaysSinceTransaction(); ?></p>
    <?php else: ?>
        <p>Transaction not found.</p>
    <?php endif; ?>
</div>

<div class="block">
    <h2>2. Total amount of all transactions</h2>
    <p><strong><?= $totalAmount; ?></strong></p>
</div>

<div class="block">
    <h2>3. Total amount by date range (2020-01-01 to 2020-12-31)</h2>
    <p><strong><?= $totalByDateRange; ?></strong></p>
</div>

<div class="block">
    <h2>4. Count transactions by merchant: GiftShop</h2>
    <p><strong><?= $merchantCount; ?></strong></p>
</div>

<div class="block">
    <h2>5. Transactions sorted by date</h2>
    <?= $renderer->render($sortedByDate); ?>
</div>

<div class="block">
    <h2>6. Transactions sorted by amount (descending)</h2>
    <?= $renderer->render($sortedByAmountDesc); ?>
</div>

<div class="block">
    <h2>7. All transactions after deleting transaction with ID = 3</h2>
    <?= $renderer->render($allTransactionsAfterDelete); ?>
</div>

</body>
</html>



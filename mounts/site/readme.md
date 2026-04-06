# Лабораторная работа №5

## Цель работы

Освоить основы объектно-ориентированного программирования в PHP на практике.
Научиться создавать классы, использовать инкапсуляцию, разделять ответственность между классами, а также применять интерфейсы для построения гибкой архитектуры.

## Постановка задачи

В рамках лабораторной работы необходимо было разработать приложение для управления банковскими транзакциями.

Программа должна уметь:

- хранить транзакции;
- добавлять и удалять их;
- выполнять поиск;
- сортировать данные;
- выполнять вычисления;
- выводить результат в виде HTML-таблицы.

## Ход работы

### 1. Включение строгой типизации

В начале каждого файла была подключена строгая типизация:

```php
declare(strict_types=1);
```

Это позволяет избежать неявных преобразований типов и делает код более безопасным.

### 2. Реализация класса `Transaction`

Был создан класс `Transaction`, который описывает одну банковскую транзакцию.

Класс содержит:

- `id` — идентификатор;
- `date` — дата (через `DateTime`);
- `amount` — сумма;
- `description` — описание;
- `merchant` — получатель.

Реализован метод:

```php
getDaysSinceTransaction(): int
```

Он вычисляет количество дней с момента транзакции.

Все поля сделаны `private`, доступ к ним осуществляется через getter-методы.

#### Кода класса `Transaction`

```php
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
```

### 3. Реализация `TransactionRepository`

Создан класс `TransactionRepository`, который отвечает за хранение данных.

Функционал:

- добавление транзакции;
- удаление по ID;
- получение всех транзакций;
- поиск по ID.

Транзакции хранятся в приватном массиве, доступ к которому возможен только через методы класса.

#### Код класса `TransactionRepository`

```php
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
```

### 4. Реализация `TransactionManager`

Создан класс `TransactionManager`, который реализует бизнес-логику.

Он не хранит данные, а работает через репозиторий.

Реализованы методы:

- подсчёт общей суммы транзакций;
- подсчёт суммы за период;
- подсчёт количества по получателю;
- сортировка по дате;
- сортировка по сумме.

#### Код класса `TransactionManager`

```php

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
```

### 5. Реализация `TransactionTableRenderer`

Создан отдельный класс для вывода данных.

Метод:

```php
render(array $transactions): string
```

Формирует HTML-таблицу со столбцами:

- ID;
- дата;
- сумма;
- описание;
- получатель;
- количество дней.

Класс объявлен как `final`.

#### Код класса `TransactionTableRenderer`

```php
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
```

### 6. Создание тестовых данных

Было создано более 10 объектов `Transaction` с разными значениями:

- даты;
- суммы;
- описания;
- получатели.

Все транзакции добавлены в репозиторий.

#### Код созданных данных

```php
$transactionData = [
    [
        "id" => 1,
        "date" =>"2019-01-01",
        "amount" => 100.00,
        "description" => "Payment for groceries",
        "merchant" => "SuperMart",
    ],
    [..]
    ...

];

$transactionRepository = new TransactionRepository();

foreach($transactionData as $transaction){
    $transactionRepository->addTransaction(new Transaction($transaction["id"],$transaction["date"],
    $transaction["amount"],$transaction["description"],$transaction["merchant"]));
}
```

### 7. Реализация интерфейса

Создан интерфейс:

```php
ITransactionStorage
```

Он содержит методы:

- addTransaction
- removeTransactionById
- getAllTransactions
- findById

#### Код интерфейса

```php
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
```

Класс `TransactionRepository` реализует этот интерфейс.

`TransactionManager` работает через интерфейс, а не конкретный класс, что делает архитектуру гибкой.

### 8. Документирование кода

Весь код был задокументирован с использованием PHPDoc:

- указаны параметры (`@param`);
- указаны возвращаемые значения (`@return`);
- добавлено описание методов.

## Результат работы

В результате была реализована система управления транзакциями, которая:

- хранит данные;
- выполняет операции с ними;
- разделяет ответственность между классами;
- использует интерфейсы;
- выводит данные в HTML.

## Ответы на контрольные вопросы

### 1. Зачем нужна строгая типизация?

Она предотвращает ошибки, связанные с автоматическим приведением типов, и делает код более предсказуемым.

### 2. Что такое класс?

Класс — это шаблон для создания объектов.
Он содержит:

- свойства;
- методы, обрабатывающие свойства и передоваемые в них (методы) данные.

### 3. Что такое полиморфизм?

Полиморфизм - это возможность использовать один интерфейс для разных реализаций.

В PHP он реализуется через:

- интерфейсы;
- наследование;
- переопределение методов.

### 4. Интерфейс vs абстрактный класс

Интерфейс:

- содержит только сигнатуры методов.
- не может быть реализован.

Абстрактный класс:

- может быть реализован.
- может иметь свойства.

### 5. Зачем нужны интерфейсы?

Интерфейсы позволяют:

- менять реализацию без изменения кода;
- уменьшить связанность компонентов.

В данной работе:
`TransactionManager` не зависит от конкретного класса `TransactionRepository`, а работает через интерфейс.

## Вывод

В ходе работы были изучены основные принципы ООП в PHP:

- инкапсуляция;
- разделение ответственности;
- использование интерфейсов.

Была реализована полноценная система работы с транзакциями, что помогло лучше понять архитектуру приложений.

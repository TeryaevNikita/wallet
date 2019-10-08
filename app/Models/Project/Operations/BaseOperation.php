<?php


namespace App\Models\Project\Operations;


abstract class BaseOperation
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $walletId;

    /**
     * @var int
     */
    public $walletToId;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var string
     */
    public $currency;

    /**
     * @return array
     */
    abstract function getTransactions(): array;
}

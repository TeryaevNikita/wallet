<?php


namespace App\Models\Project;


class Wallet
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var string
     */
    public $currency;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Wallet
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param int $amount
     * @return Wallet
     */
    public function setAmount(int $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param string $currency
     * @return Wallet
     */
    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }


}

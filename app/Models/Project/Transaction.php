<?php

namespace App\Models\Project;

use App\Contracts\IRateService;
use App\Contracts\IWalletService;
use App\Exceptions\ApiException;

class Transaction
{
    /**
     * @var int
     */
    private $walletId;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var int
     */
    private $operationId;

    /**
     * @param int $operationId
     * @return Transaction
     */
    public function setOperationId(int $operationId): self
    {
        $this->operationId = $operationId;
        return $this;
    }

    /**
     * @param int $walletId
     * @param int $amount
     * @param string $currency
     * @return Transaction
     * @throws ApiException
     */
    public function setAmount(int $walletId, int $amount, string $currency): self
    {
        $this->walletId = $walletId;
        $walletCur = app(IWalletService::class)->getCurrency($walletId);

        if ($currency != $walletCur) {
            $rates = app(IRateService::class)->getRates([$walletCur, $currency]);

            if (!isset($rates[$currency])) {
                throw new ApiException('There is no rate of ' . $currency . ' to USD for today');
            }

            if (!isset($rates[$walletCur])) {
                throw new ApiException('There is no rate of ' . $walletCur . ' to USD for today');
            }

            $amount = $amount * floatval($rates[$walletCur]) / floatval($rates[$currency]);
        }

        $this->currency = $walletCur;
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return int
     */
    public function getOperationId(): int
    {
        return $this->operationId;
    }

    /**
     * @return int
     */
    public function getWalletId(): int
    {
        return $this->walletId;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }
}

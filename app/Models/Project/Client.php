<?php

namespace App\Models\Project;

use App\Contracts\IWalletService;
use App\Exceptions\ApiException;
use App\Models\Project\Dto\NewWallet;

class Client
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param int $amount
     * @param string $currency
     * @return Wallet
     * @throws ApiException
     */
    public function addWallet(int $amount, string $currency): Wallet
    {
        $walletData = new NewWallet([
            'amount' => 0,
            'currency' => $currency,
        ]);

        $walletData->setClient($this);

        $wallet = app(IWalletService::class)->create($walletData);

        if (!$wallet instanceof Wallet) {
            throw new ApiException("Cant find wallet");
        }

        return $wallet;
    }

    /**
     * @param int $wallet_id
     * @return bool
     */
    public function hasWallet(int $wallet_id): bool
    {
        return app(IWalletService::class)->HasClientWallet($this->id, $wallet_id);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

}

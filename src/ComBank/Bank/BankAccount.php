<?php

namespace ComBank\Bank;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:25 PM
 */

use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\Exceptions\BankAccountException;
use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Support\Traits\AmountValidationTrait;

class BankAccount implements BankAccountInterface
{
    use AmountValidationTrait;
    private float $balance;
    private string $status = self::STATUS_OPEN;

    private string $email = "";   
     private OverdraftInterface $overdraft;

    public function __construct(float $newBalance, string $email)
    {
        $this->balance = $newBalance;
        $this->overdraft = new NoOverdraft();
        $this->setEmail($email);
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function closeAccount(): void
    {
        if ($this->status !== self::STATUS_OPEN) {
            throw new BankAccountException('Account is already closed.');
        }

        $this->status = self::STATUS_CLOSED;
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function reopenAccount(): void
    {
        if ($this->status !== self::STATUS_CLOSED) {
            throw new BankAccountException('Account is already open.');
        }

        $this->status = self::STATUS_OPEN;
    }

    public function transaction(BankTransactionInterface $bankTransactionInterface): void
    {
        if ($this->status !== self::STATUS_OPEN) {
            throw new BankAccountException("Account is closed. Cannot perform transactions.");
        }
        try {
            $newBalance = $bankTransactionInterface->applyTransaction($this);
            $this->setBalance($newBalance);
        } catch (InvalidOverdraftFundsException $e) {
            throw new FailedTransactionException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function setBalance(float $newBalance): void
    {
        $this->balance = $newBalance;
    }

    public function getOverdraft(): OverdraftInterface
    {
        return $this->overdraft;
    }

    public function applyOverdraft(OverdraftInterface $overdraft): void
    {
        $this->overdraft = $overdraft;
    }

    public function getEmail(): string{
        return $this->email;
    }

    public function setEmail(string $email): void{
        $this->validateEmail($email);
        $this->email = $email;
    }
}

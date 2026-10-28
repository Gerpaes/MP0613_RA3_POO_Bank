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
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class BankAccount implements BankAccountInterface
{
    private float $balance;
    private bool $status = true;

    private OverdraftInterface $overdraft;

    public function __construct(float $newBalance )
    {
        $this->balance = $newBalance;
        $this->overdraft = new NoOverdraft();
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function closeAccount(): void
{
    if (!$this->status) {
        throw new BankAccountException('Account is already closed.');
    }

    $this->status = false;
}

    public function isOpen(): bool
    {
        return $this->status;
    }

    public function reopenAccount(): void{
        
       
    if ($this->status) {
        throw new BankAccountException('Account is already open.');
    }

    $this->status = true;
}

    public function transaction(BankTransactionInterface $bankTransactionInterface): void{
        if(!$this->status){
            throw new BankAccountException("Account is closed. Cannot perform transactions.");

        }
        try {
            $newBalance = $bankTransactionInterface->applyTransaction($this);
            $this->setBalance($newBalance);
        } catch (ZeroAmountException $e) {
            throw new FailedTransactionException($e->getMessage(), $e->getCode(), $e);
        } catch (InvalidArgsException $e) {
            throw new FailedTransactionException($e->getMessage(), $e->getCode(), $e);
        } catch (BankAccountException $e) {
            throw new FailedTransactionException($e->getMessage(), $e->getCode(), $e);
        } catch (FailedTransactionException $e) {
            throw new FailedTransactionException($e->getMessage(), $e->getCode(), $e);
        }
        catch (InvalidOverdraftFundsException $e) {
            throw new FailedTransactionException($e->getMessage(), $e->getCode(), $e);
        } catch (\Exception $e) {
            throw new FailedTransactionException('An unexpected error occurred during the transaction.', $e->getCode(), $e);
    }
    }

    public function setBalance(float $newBalance):void{
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
}

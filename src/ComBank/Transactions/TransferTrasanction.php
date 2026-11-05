<?php

namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 11:30 AM
 */

use ComBank\Bank\BankAccount;
use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\BankAccountException;
use ComBank\Transactions\Contracts\BankTransactionInterface;


class TransferTrasanction extends BaseTransaction implements BankTransactionInterface
{

    private BankAccount $destinationAccount;

    // The interest is calculated from the balance, so no amount is needed.
    public function __construct(float $amount, BankAccountInterface $destinationAccount)
    {
        parent::__construct($amount);
        $this->destinationAccount = $destinationAccount;
    }

    public function applyTransaction(BankAccountInterface $bankAccount): float
    {


        if (!$bankAccount->isOpen() ) {
            $mesage = "Account Source is close";
            throw new BankAccountException($mesage);
        } elseif (!$this->destinationAccount->isOpen()) {
            $mesage = "Account Destination is close";
            throw new BankAccountException($mesage);
        }
        else {
                     
        if ($bankAccount->getBalance() < $this->amount) {
           $mesage = "Transfer rejected when the source account has insufficient available funds";
            throw new BankAccountException($mesage);
        }else{         
             $bankAccount->transaction(new WithdrawTransaction($this->amount));
            $this->destinationAccount->transaction(new DepositTransaction($this->amount)); 

            return  $bankAccount->getBalance();
            
            }
                        
    
        }
        
    }

    public function getTransactionInfo(): string
    {
        return 'TRANSFER_TRANSACTION';
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setDestinationAccount(BankAccountInterface $destinationAccount)
    {

        $this->destinationAccount = $destinationAccount;
    }

    public function getDestinationAccount(): BankAccountInterface
    {

        return $this->destinationAccount;
    }
}

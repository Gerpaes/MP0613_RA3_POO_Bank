<?php namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:22 PM
 */

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Support\Traits\AmountValidationTrait;

class WithdrawTransaction implements BankTransactionInterface

{
    use AmountValidationTrait;
    private float $amount;

    public function __construct(float $amount)
    {
        $this->validateAmount($amount);
        $this->amount = $amount;
    }

    public function applyTransaction(BankAccountInterface $bankAccount){

    
        
        $newBalance = $bankAccount->getBalance() - $this->amount;

        if (!$bankAccount->getOverdraft()->isGrantOverdraftFounds($newBalance)) {
            throw new InvalidOverdraftFundsException('Fondos insuficientes');
        }
       

        return $newBalance;
        
    }

    public function getAmount(): float
    {
        return $this->amount;
       
    }

    public function getTransactionInfo(): string
    {
        return "Withdraw Transaction: Amount - " . $this->amount;

    }
   
}

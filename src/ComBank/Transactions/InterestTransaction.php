<?php

namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 11:30 AM
 */

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class InterestTransaction extends BaseTransaction implements BankTransactionInterface
{
    // The interest is calculated from the balance, so no amount is needed.
    public function __construct()
    {
        $this->amount = 0.0;
    }

    public function applyTransaction(BankAccountInterface $bankAccount): float
    {
        $balance = $bankAccount->getBalance();
        $rate    = $bankAccount->getOverdraft()->getInterestRate();

        return $balance + ($balance * $rate / 100);
    }

    public function getTransactionInfo(): string
    {
        return 'INTEREST_TRANSACTION';
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}

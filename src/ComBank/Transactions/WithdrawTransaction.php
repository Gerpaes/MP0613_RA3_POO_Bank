<?php

namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:22 PM
 */

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class WithdrawTransaction extends BaseTransaction implements BankTransactionInterface

{
    public function applyTransaction(BankAccountInterface $bankAccount): float
    {
        $newBalance = $bankAccount->getBalance() - $this->amount;
        $overdraft = $bankAccount->getOverdraft();

        if (!$overdraft->isGrantOverdraftFunds($newBalance)) {
            $message = $overdraft->getOverdraftFunds() === 0.0
                ? 'Insufficient balance to complete the withdrawal.'
                : 'Withdrawal exceeds overdraft limit.';

            throw new InvalidOverdraftFundsException($message);
        }

        return $newBalance;
    }

    public function getTransactionInfo(): string
    {
        return 'WITHDRAW_TRANSACTION';
    }
}

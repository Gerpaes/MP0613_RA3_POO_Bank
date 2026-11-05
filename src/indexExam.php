<?php

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:24 PM
 */

use ComBank\Bank\BankAccount;
use ComBank\OverdraftStrategy\SilverOverdraft;
use ComBank\Transactions\DepositTransaction;
use ComBank\Transactions\WithdrawTransaction;
use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Transactions\InterestTransaction;
use ComBank\Transactions\TransferTrasanction;

require_once 'bootstrap.php';


//---[Bank account 1]---/
// create a new account1 with balance 400
$bankAccount1 = new BankAccount(400, "john.smith@example.com");
//---[Bank account 2]---/
$bankAccount2 = new BankAccount(200, "john.smith@example.com");
$bankAccount2->applyOverdraft(new SilverOverdraft());
$bankAccount2->closeAccount();

//transfer 100
try {
    pl('Doing transaction transder (-500) Source Account Balance ' . formatBalance($bankAccount1->getBalance()));
    pl('Doing transaction transder (+500) Destination Account Balance ' . formatBalance($bankAccount2->getBalance()));

    $transfer = new TransferTrasanction(500, $bankAccount2);
    $bankAccount1->transaction($transfer);

    pl('Source Balance After Transaction: ' . formatBalance($bankAccount1->getBalance()));
    pl('Destination Balance After Transaction: ' . formatBalance($bankAccount2->getBalance()));
} catch (BankAccountException $e) {
    pl('Error transaction: ' . $e->getMessage());
}


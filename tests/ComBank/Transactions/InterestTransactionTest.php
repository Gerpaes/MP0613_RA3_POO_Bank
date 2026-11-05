<?php

use ComBank\Bank\BankAccount;
use ComBank\OverdraftStrategy\GoldOverdraft;
use ComBank\OverdraftStrategy\SilverOverdraft;
use ComBank\Transactions\InterestTransaction;
use PHPUnit\Framework\TestCase;

class InterestTransactionTest extends TestCase
{
    public function testInterestWithNoOverdraft()
    {
        $bankAccount = new BankAccount(100, "john.smith@example.com");
        $bankAccount->transaction(new InterestTransaction());
        $this->assertEquals(101, $bankAccount->getBalance());
    }

    public function testInterestWithSilverOverdraft()
    {
        $bankAccount = new BankAccount(200, "john.smith@example.com");
        $bankAccount->applyOverdraft(new SilverOverdraft());
        $bankAccount->transaction(new InterestTransaction());
        $this->assertEquals(210, $bankAccount->getBalance());
    }

    public function testInterestWithGoldOverdraft()
    {
        $bankAccount = new BankAccount(100, "john.smith@example.com");
        $bankAccount->applyOverdraft(new GoldOverdraft());
        $bankAccount->transaction(new InterestTransaction());
        $this->assertEquals(110, $bankAccount->getBalance());
    }
}

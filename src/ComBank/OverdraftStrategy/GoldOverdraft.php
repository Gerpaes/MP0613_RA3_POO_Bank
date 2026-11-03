<?php

namespace ComBank\OverdraftStrategy;

use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use Override;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:39 PM
 */

/**
 * @description: Grant 100.00 overdraft funds.
 * */
class GoldOverdraft implements OverdraftInterface
{
    public function isGrantOverdraftFunds(float $newBalance): bool
    {
        return $newBalance >= -$this->getOverdraftFunds();
    }

    public function getOverdraftFunds(): float
    {
        return 500.00;
    }


    public function getInterestRate(): int
    {
        return 0.10;
    }
}

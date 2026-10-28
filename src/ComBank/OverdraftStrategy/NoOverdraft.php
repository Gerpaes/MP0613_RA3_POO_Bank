<?php 
namespace ComBank\OverdraftStrategy;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 12:27 PM
 */

class NoOverdraft implements OverdraftInterface

{

    public function isGrantOverdraftFunds(float $newBalance): bool
    {
        return $newBalance >= 0;
    }

    public function getOverdraftFunds(): float
    {
        return 0.0;
    }   
   
}

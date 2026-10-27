<?php namespace ComBank\OverdraftStrategy;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:39 PM
 */

/**
 * @description: Grant 100.00 overdraft funds.
 * */
class SilverOverdraft implements OverdraftInterface
{


  public function isGrantOverdraftFounds( float $newBalance): bool
  {
      return $newBalance >= -$this->getOverdraftFounds();
  }

  public function getOverdraftFounds(): float
  {
      return 100.00;
  }
    
}

<?php
namespace Kumon\KeizibanLib\Errorcheck;

use PHPUnit\Framework\TestCase;

use Kumon\KeizibanLib\System\Encode;
use Kumon\KeizibanLib\System\DbManager;

use Kumon\KeizibanLib\Errorcheck\ChangenameError;
use Kumon\KeizibanLib\Errorcheck\PassException;
use Kumon\KeizibanLib\Errorcheck\ChangenameException;

class ChangenameErrorTest extends Testcase {

  public function setUp() {
    $db = DbManager::getDbforTest();
  }

  



}
?>

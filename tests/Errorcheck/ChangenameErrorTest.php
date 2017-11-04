<?php
namespace Kumon\KeizibanLib\Errorcheck;

use PHPUnit\Framework\TestCase;

use Kumon\KeizibanLib\System\Encode;
use Kumon\KeizibanLib\System\DbManager;

use Kumon\KeizibanLib\Errorcheck\ChangenameError;
use Kumon\KeizibanLib\Errorcheck\PassException;
use Kumon\KeizibanLib\Errorcheck\ChangenameException;

class ChangenameErrorTest extends Testcase {
  /*
  public function setUp() {
    $db = DbManager::getDbforTest();
  }
  */

  /**
   * @test
   */
  public function PassExceptionTest() {
    $db = DbManager::getDbforTest();
    $this->expectException(PassException::class);
    $maxlength = 15;

    //現在のパスワードとして不正な値を入れた時
    $id = "test";
    $getpass = "unkoooon";
    try {
      ChangenameError::checkerror($id, $db, $getpass, $maxlength, $newname);
      $this->fail();
    } catch(PassException $e) {
      $this->assertEquals("現在のパスワードが間違っています。戻ってやり直してください", $e->getMessage());
    }
  }



}
?>

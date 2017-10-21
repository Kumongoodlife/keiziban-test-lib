<?php
namespace Kumon\KeizibanLib;

use Kumon\KeizibanLib\Encode;

//use \Exception;
use \LogicException;
use \RuntimeException;
use \PDO;

//class MakeNewpageException extends Exception {}

class MakeThreadPreconditionNotSatisfiedException extends LogicException { }
class ThreadNameNotGivenException extends MakeThreadPreconditionNotSatisfiedException { }
class ThreadNameDuplicationException extends MakeThreadPreconditionNotSatisfiedException { }

class MakeThreadRuntimeException extends RuntimeException { }


  class Makenewpagecheck {
    public static function checkerror($newpagename, $db) {
      //入力内容を確認
      if (empty($newpagename)) {  // $_POST['..']
        throw new ThreadNameNotGivenException('新規スレッド名を入力してください！戻ってやり直してください！');
      }

      //重複がないか確認
      $query = "SELECT pagename FROM pages WHERE pagename = \"$newpagename\"";
      $result = $db->query($query);
      if (!empty($col = $result->fetch(PDO::FETCH_ASSOC))) {
        throw new ThreadNameDuplicationException('この名前のスレッドはすでに存在しています！戻ってやり直してください！');
      }
    }

    public static function makenewpage($newpagename, $db) {
      $stt = $db->prepare("INSERT INTO pages(pagename) VALUES (:newpagename)");
      $stt->bindValue(":newpagename", $newpagename);
      $result1 = $stt->execute();

      $dirpath1 = "./uploads/{$newpagename}";
      $result3 = mkdir($dirpath1, 0777, true);

      if ($result1 && $result3) {
        print "<p>スレッドを作成しました。戻ってスレッドに移動してください。</p>";
        print "<p>新規スレッド名:".Encode::e($newpagename)."</p>";
        print "<a href = \"mainpage.php\">戻る</a>";
      } else {
        // 失敗したなら、後処理。ディレクトリ削除やDB。
        throw new MakeThreadRuntimeException('不明なエラーにより失敗しました！戻ってやり直してください！');
        if (!is_dir($dirpath1)) {
          mkdir($dirpath1);
        }
      }

    }
  }

?>

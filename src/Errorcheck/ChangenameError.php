<?php
namespace Kumon\KeizibanLib\Errorcheck;

use Kumon\KeizibanLib\System\Encode;

use \PDO;
use \Exception;

  class PassException extends Exception {}
  class ChangenameException extends Exception {}

  class ChangenameError {
    public static function checkerror($id, $db, $getpass, $maxlength, $newname) {
      $query = "SELECT pass, id FROM member WHERE id = {$id}";
      $result = $db->query($query);
      while ($col = $result->fetch(PDO::FETCH_ASSOC)) {
        if (!password_verify($getpass, $col['pass'])) {
          throw new PassException("現在のパスワードが間違っています。戻ってやり直してください");
        }
      }
      if (mb_strlen($newname) > $maxlength) {
        throw new ChangenameException("新しい表示名は{$maxlength}文字以下にしてください。戻ってやり直してください");
      }
      if (empty($newname)) {
        throw new ChangenameException("新しい表示名を入力してください。戻ってやり直してください");
      }
    }

    public static function changename($db, $newname, $id) {
      $stt = $db->prepare("UPDATE member SET name = :newname WHERE id=:id");
      $stt->bindValue(":newname", $newname);
      $stt->bindValue(":id", $id);
      $result = $stt->execute();
      if ($result) {
        session_regenerate_id(TRUE);
        $_SESSION['name'] = $newname;//セッション情報を更新
        print "<p>表示名を変更しました。</p>";
        print "<p>新しい表示名:".Encode::e($newname)."</p>";
        print "<a href = \"mainpage.php\">戻る<a>";
      } else {
        throw new ChangenameException("不明なエラーにより失敗しました。戻ってやり直してください");
      }
    }
  }
?>

<?php
namespace Kumon\KeizibanLib;

use Kumon\KeizibanLib\Encode;

use \Exception;
use \PDO;

class RegisterException extends Exception {}

  class Registerpagecheck {
    public static function check($newid, $newname, $pass1, $pass2, $db, $maxlength) {
      if (mb_strlen($newid) > $maxlength || mb_strlen($newname) > $maxlength || mb_strlen($pass1) > $maxlength) {     // $maxlengthは定数として、即値を避けるべき
        throw new RegisterException('文字数オーバーです！戻ってやり直してください。');
      } elseif(mb_strlen($newid) < 1 || mb_strlen($newname) < 1 || mb_strlen($pass1) < 1) {
        throw new RegisterException('必須項目は全て入力してください！戻ってやり直してください。');
      }elseif($pass1 != $pass2) {
        throw new RegisterException('再度入力したパスワードが間違っています。戻ってやり直してください。');
      }else{
        $query = "SELECT userid FROM member WHERE userid = \"$newid\"";
        $result = $db->query($query);
        if (!empty($col = $result->fetch(\PDO::FETCH_ASSOC))) {
          throw new RegisterException('このユーザーidはすでに使用されています！戻ってやり直してください。');
        }
      }
    }

    public static function register($db, $newid, $newname, $pass1) {
      //ハッシュに変換
      $pass1 = password_hash($pass1, PASSWORD_DEFAULT);

      $stt = $db->prepare("INSERT INTO member(userid, name, pass) VALUES (:newid, :newname, :pass1)");
      $stt->bindValue(":newid", $newid);
      $stt->bindValue(":newname", $newname);
      $stt->bindValue(":pass1", $pass1);
      $result = $stt->execute();
      if ($result) {
        print "登録に成功しました。戻ってログインしてください。<br>";
        print "<p>登録ユーザーid:".Encode::e($newid)."</p>";
        print "<p>登録表示名:".Encode::e($newname)."</p>";
        print "<a href = \"loginpage.php\">戻る</a>";
      } else {
        throw new RegisterException('不明なエラーにより失敗しました。戻ってやり直してください。');
      }
    }
  }
?>

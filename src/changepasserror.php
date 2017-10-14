<?php
use \Exception;
use \PDO;

  class PassException extends Exception {}
  class ChangepassException extends Exception {}

  class ChangepassError {
    public static function checkerror($id, $db, $oldpass, $maxlength, $newpass1, $newpass2) {
      $query = "SELECT pass, id FROM member WHERE id = {$id}";
      $result = $db->query($query);
      while ($col = $result->fetch(PDO::FETCH_ASSOC)) {
        if (!password_verify($oldpass, $col['pass'])) {
          throw new PassException("現在のパスワードが間違っています。戻ってやり直してください");
        }
      }
      if (mb_strlen($newpass1) > $maxlength) {
        throw new ChangepassException("新しいパスワードは{$maxlength}文字以下にしてください。戻ってやり直してください");
      }
      if (empty($newpass1)) {
        throw new ChangepassException("新しいパスワードを入力してください。戻ってやり直してください");
      }
      if ($newpass1 != $newpass2) {
        throw new ChangepassException("新しいパスワードが一致していません。戻ってやり直してください");
      }
    }

    public static function changepass($db, $newpass1, $id) {
      $newpass1 = password_hash($newpass1, PASSWORD_DEFAULT);

      $stt = $db->prepare("UPDATE member SET pass = :newpass WHERE id=:id");
      $stt->bindValue(":newpass", $newpass1);
      $stt->bindValue(":id", $id);
      $result = $stt->execute();
      if($result) {
        print "<p>パスワードを変更しました。</p>";
        print "<a href = \"mainpage.php\">戻る<a>";
      } else {
        throw new ChangepassException("不明なエラーにより失敗しました。戻ってやり直してください");
      }
    }
  }
?>

<?php
use Akizuki\ACTG\CSRFToken;

  class Loginpageprint {
    public static function loginform($hiddenform) {
      print "<form method = \"post\" action=\"mainpage.php\">
              <p>ユーザーID:<input type = \"text\" name=\"userid\">パスワード:<input type=\"password\" name=\"pass\"></p>
              <p><input type = \"submit\" name = \"login\" value=\"ログイン\"></p>";
      //$token->printhiddentoken();
      print $hiddenform;
      print "</form>";
    }

    public static function registerform($hiddenform) {
      print "<form method = \"post\" action = \"registerpage.php\">
              <p>新規ユーザーID(必須、15文字まで):<input type = \"text\" name = \"newid\"></p>
              <p>新規表示名(必須、15文字まで):<input type = \"text\" name = \"newname\"></p>
              <p>新規パスワード(必須、15文字まで):<input type = \"password\" name = \"pass1\"></p>
              <p>再度パスワードを入力(必須):<input type = \"password\" name = \"pass2\"></p>
              <p><input type = \"submit\" name = \"regi\" value=\"新規登録\"></p>";
      //$token->printhiddentoken();
      print $hiddenform;
      print "</form>";
    }

    public static function pagemenu($db) {
      $query = "SELECT pageid, pagename FROM pages";
      $result = $db->query($query);
      print "<ls>";
      while ($col = $result->fetch(\PDO::FETCH_ASSOC)) {
        print "<form method = \"post\" action = \"pageview.php?pageid={$col['pageid']}\">";
        print "<input type = \"hidden\" name = \"pageid\" value = \"{$col['pageid']}\">";
        print "{$col['pagename']}: <input type = \"submit\" name=\"view\" value = \"閲覧\"><br>";
        print "</form>";
      }
      print "</ls>";
    }
  }

?>

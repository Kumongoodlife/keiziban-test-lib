<?php
namespace Kumon\KeizibanLib\Printer;


use \PDO;

  class Mainpageprint {
    public static function pagemenu($db) {
      $query = "SELECT pageid, pagename FROM pages";
      $result = $db->query($query);
      print "<ls>";
      while ($col = $result->fetch(PDO::FETCH_ASSOC)) {
        print "<form method = \"post\" action = \"pageview.php?pageid={$col['pageid']}\">";
        print "<input type = \"hidden\" name = \"pageid\" value = \"{$col['pageid']}\">";
        print "{$col['pagename']}: <input type = \"submit\" name=\"view\" value = \"閲覧\"><br>";
        print "</form>";
      }
      print "</ls>";
    }

    public static function changepass($hiddenform) {
      print "<form method = \"post\" action = \"changepass.php\">
              <h2>パスワード変更</h2>
              <p>現在のパスワード:<input type = \"password\" name = \"oldpass\"></p>
              <p>新規パスワード:<input type = \"password\" name = \"newpass1\"></p>
              <p>再入力:<input type = \"password\" name = \"newpass2\"></p>
              <p><input type = \"submit\" name = \"changepass\" value = \"パスワード変更\"></p>";
      //$token->printhiddentoken();
      print $hiddenform;
      print "</form>";
    }

    public static function changename($hiddenform) {
      print "<form method = \"post\" action = \"changename.php\">
              <h2>表示名変更</h2>
              <p>新しい表示名:<input type = \"text\" name = \"newname\"></p>
              <p>パスワード:<input type = \"password\" name = \"pass\"></p>
              <p><input type = \"submit\" name = \"changename\" value = \"表示名変更\"></p>";
      //$token->printhiddentoken();
      print $hiddenform;
      print "</form>";
    }
    public static function logout() {
      print "<form method = \"post\" action = \"logout.php\">
              <h2>ログアウトする</h2>
              <p><input type = \"submit\" name = \"logout\" value = \"ログアウト\"></p>
              </form>";
    }

    public static function makenewpage($hiddenform) {
      print "<form method = \"post\" action = \"makenewpage.php\">
              <h2>新規スレッドを作る</h2>
              <p>新規スレッド名:<input type = \"text\" name = \"newpagename\"></p>
              <p><input type = \"submit\" name = \"pagemake\" value = \"新規スレッド作成\"></p>";
      //$token->printhiddentoken();
      print $hiddenform;
      print "</form>";
    }
  }
?>

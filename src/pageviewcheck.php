<?php
namespace Kumon\KeizibanLib;

use \PDO;
use \Exception;

  class PageviewException extends Exception {}
  class SessionException extends Exception {}

  class Pageviewcheck {
    public $pageid = 0;
    public $pagename = "";

    public function __construct($pageid) {
      $this->pageid = $pageid;
    }

    public function ispageexist($db) {
      $query = "SELECT pagename, pageid FROM pages WHERE pageid = {$this->pageid}";
      $result = $db->query($query);
      if (!empty($col = $result->fetch(PDO::FETCH_ASSOC))) {
        $this->pagename = $col['pagename'];
      } else {
        throw new PageviewException("<!DOCTYPE html>
                                    <html lang=\"ja\">
                                    <head>
                                    <meta charaset=\"utf-8\">
                                    <title>Error!!</title>
                                    <link rel = \"stylesheet\" href = \"keiziban.css\">
                                    </head>
                                    <div class = \"errMsg\">このスレッドは存在しません！戻ってやり直してください。</div><br>
                                    <a href=\"mainpage.php\">戻る</a><br>
                                    </body></html>");
      }
    }

    public static function sessioncheck() {
      if (empty($_SESSION['userid']) || empty($_SESSION['name']) || empty($_SESSION['id'])) {
        throw new SessionException("<div class = \"errMsg\">ログインしていません！書き込むにはログインしてください。</div><br>
                                  <a href=\"loginpage.php\">ログインページ</a><br>");
        return array ("", "", "");
      } else {
        return array ($_SESSION['userid'], $_SESSION['name'], $_SESSION['id']);
      }
    }

    public function printform($userid, $name, $id, $hiddenform) {
      print "<p><div class = \"username\">".e($name)."(ユーザーid: ".e($userid).")としてログインしています。</div></p>
            <a href=\"mainpage.php\">戻る</a><br>
            <h2>書き込みする</h2>
            <form enctype = \"multipart/form-data\" method = \"post\" action = \"post.php\">
            <p>投稿内容:</p>
            <p><textarea name = \"free\"></textarea></p>
            <p>画像(jpg, png)を添付:</p>
            <input type = \"file\" name = \"userfile\"><br>
            <input type = \"hidden\" name = \"pageid\" value = {$this->pageid}>
            <input type = \"hidden\" name = \"pagename\" value = {$this->pagename}>";
      print $hiddenform;
      print "<p><input type = \"submit\" name = \"newpost\" value = \"投稿\"></p>
            </form>";
    }

    public function showposts($db) {
      $query = "SELECT * FROM posts WHERE pageid = \"$this->pageid\"";
      $result = $db->query($query);
      $count = 0;
      while ($col = $result->fetch(\PDO::FETCH_ASSOC)) {
        $count++;
        print "<p><div class = \"username\">[No.{$count}] 投稿者:".e($col['postuser'])."(".date("Y年n月j日 H時i分s秒", $col['posttime']).")</div></p>";
        print e($col['news']);
        if ($col['file']) {
          print "<br><a href=\"{$col['file']}\">添付ファイル</a>";
        }
        print "<hr>";
      }
    }
  }

?>

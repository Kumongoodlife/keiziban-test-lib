<?php
namespace Kumon\KeizibanLib\Errorcheck;


use \PDO;
use \Exception;

  class LoginErrorException extends Exception {}
  class SessionErrorException extends Exception {}

  class LoginError {
    public static function checkerror($userid, $pass, $db) {
      //ユーザーidを検索する
      $query = "SELECT userid, pass, id, name FROM member WHERE userid = \"$userid\"";
      $result = $db->query($query);
      //そもそも入力されたuseridが存在するかどうかの確認とパスワードの確認
      if (!empty($col = $result->fetch(PDO::FETCH_ASSOC))) {
        if (password_verify($pass, $col['pass'])) {
          $name = $col['name'];
          $id = $col['id'];
          return array ($name, $id);
        } else {
          throw new LoginErrorException('ログインに失敗しました！');
        }
      } else {
        throw new LoginErrorException('ログインに失敗しました！');
      }
    }

    public static function sessioncheck() {
      if (empty($_SESSION['userid']) || empty($_SESSION['name']) || empty($_SESSION['id'])) {
        throw new  SessionErrorException("不正アクセス！");
      }
    }
  }

?>

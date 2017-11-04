<?php
namespace Kumon\KeizibanLib\System;

use \Exception;

class CreateTablesforTestException extends Exception {}

  class DbManager {
    //ログインシステム用のデータベースに接続する
    public static function getDb() {
      $dsn  = 'mysql:host = dockerlampglus_mysql_1; charset = utf8';
      $user = 'dbuser';
      $pass = 'secret';

      //データベースへの接続を確立
      try {
        $db = new \PDO($dsn, $user, $pass);
        //データベースに接続
        $query = "USE keiziban";
        $result = $db->exec($query);

        return $db;
      } catch(PDOException $e) {
        print "データベース接続確立エラー: {$e->getMessage()}<br>";
        exit;
      }
    }

    public static function getDbforTest() {

      $dsn  = 'mysql:host = dockerlampglus_mysql_1; charset = utf8';
      $user = 'keiziban_test_user';
      $pass = 'secret';

      try {
        $db = new \PDO($dsn, $user, $pass);
        //データベースに接続
        $query = "USE keiziban_test";
        $result = $db->exec($query);

        return $db;
      } catch(PDOException $e) {
        print "データベース接続確立エラー: {$e->getMessage()}<br>";
        exit;
      }
    }

    public static function CreateTablesforTest($db) {
      $query = "CREATE TABLE member(id int auto_increment primary key, userid text, name text, pass text)";
      $result = $db->exec($query);
      if(!$tableresult = $db->query("SHOW TABLES LIKE \"member\"")->fetch(\PDO::FETCH_ASSOC)) {
        throw new CreateTablesforTestException("テーブルの作成に失敗しました！");
      }

      $query = "CREATE TABLE pages(pageid int auto_increment primary key, pagename text)";
      $result = $db->exec($query);
      if(!$tableresult = $db->query("SHOW TABLES LIKE \"pages\"")->fetch(\PDO::FETCH_ASSOC)) {
        throw new CreateTablesforTestException("テーブルの作成に失敗しました！");
      }

      $query = "CREATE TABLE posts(postid int auto_increment primary key, news text, postuser text, posttime int, pageid int, file text)";
      $result = $db->exec($query);
      if(!$tableresult = $db->query("SHOW TABLES LIKE \"posts\"")->fetch(\PDO::FETCH_ASSOC)) {
        throw new CreateTablesforTestException("テーブルの作成に失敗しました！");
      }

      $query = "CREATE TABLE files(fileid int auto_increment primary key, filename text, pageid int)";
      $result = $db->exec($query);
      if(!$tableresult = $db->query("SHOW TABLES LIKE \"files\"")->fetch(\PDO::FETCH_ASSOC)) {
        throw new CreateTablesforTestException("テーブルの作成に失敗しました！");
      }

      $stt = $db->prepare("INSERT INTO member(userid, name, pass) VALUES(:userid, :name, :pass)");
      $stt->bindValue(":userid", "test");
      $stt->bindValue(":name", "テストユーザー");
      $stt->bindValue(":pass", password_hash("secret", PASSWORD_DEFAULT));
      $result = $stt->execute();

      $stt = $db->prepare("INSERT INTO pages(pagename) VALUES (:pagename)");
      $stt->bindValue(":pagename", "テストスレッド");
      $result = $stt->execute();

      print "Success!";
    }
  }


?>

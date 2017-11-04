<?php
namespace Kumon\KeizibanLib\System;

use \Exception;

class CreateTablesforTestException extends Exception {}

  class DbManager {
    //ログインシステム用のデータベースに接続する
    public static function getDb() {
      $dsn  = 'mysql:host = 172.18.0.1; charset = utf8';
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

      $dsn  = 'mysql:host = 172.18.0.1; charset = utf8';
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
      if(!$result) {
        throw new CreateTablesforTestException("テーブルの作成に失敗しました！");
      }

      $query = "CREATE TABLE pages(pageid int auto_increment primary key, pagename text)";
      $result = $db->exec($query);
      if(!$result) {
        throw new CreateTablesforTestException("テーブルの作成に失敗しました！");
      }

      $query = "CREATE TABLE posts(postid int auto_increment primary key, news text, postuser text, posttime int, pageid int, file text)";
      $result = $db->exec($query);
      if(!$result) {
        throw new CreateTablesforTestException("テーブルの作成に失敗しました！");
      }

      $query = "CREATE TABLE files(fileid auto_increment primary key, filename text, pageid int)";
      $result = $db->exec($query);
      if(!$result) {
        throw new CreateTablesforTestException("テーブルの作成に失敗しました！");
      }

      print "Success!";
    }
  }


?>

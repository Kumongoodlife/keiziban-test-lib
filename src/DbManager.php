<?php
namespace Kumon\KeizibanLib;


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
  }


?>

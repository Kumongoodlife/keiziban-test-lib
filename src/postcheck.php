<?php
namespace Kumon\KeizibanLib;

use \PDO;
use \Exception;

  class FreeException extends Exception {}
  class FileException extends Exception {}

  class Postcheck {
    public $uploadfile = "";
    public $name = "";
    public $id = "";
    public $free = "";
    public $pageid = 0;
    public $pagename = "";
    public $fileflag = 0;

    public function __construct($name, $id, $free, $pageid, $pagename) {
      $this->name = $name;
      $this->id = $id;
      $this->free = $free;
      $this->pageid = $pageid;
      $this->pagename = $pagename;
    }

    public function checkandupload($size, $filedata, $perm, $originalname, $db, $tmpname, $uploaddir) {
      if (empty($this->free)) {
        throw new FreeException("投稿内容を入力してください！戻ってやり直してください。");
      }

      if ($size > 0.0) {
        $flag = 0;
        $ext = strtolower($filedata['extension']);
        if (!in_array($ext, $perm)) {
          $flag = 1;
          throw new FileException("<div class = \"errMsg\">添付ファイルが画像ファイルではありません！</div>");
        } elseif (!@getimagesize($tmpname)) {
          $flag = 1;
          throw new FileException("<div class = \"errMsg\">添付ファイルの内容が画像ファイルではありません！</div>");
        }
        if ($flag == 0) {
          $filename = sha1(uniqid(mt_rand(), true)) . '.' . $ext;
          //念のためにbasenameを使う(あらぬパスにならないように)
          $this->uploadfile = $uploaddir."/{$_POST['pagename']}/" . basename($filename);

          $this->fileflag = 0;
          if (move_uploaded_file($tmpname, $this->uploadfile)) {
            print "<p>ファイルがアップロードできました。<br>";
            print "ファイル名: ".e($originalname)."</p>";
            $stt = $db->prepare("INSERT INTO files(filename, pageid) VALUES (:filename, :pageid)");
            $stt->bindValue(":filename", $filename);
            $stt->bindValue(":pageid", $this->pageid);
            $result = $stt->execute();
            $this->fileflag = 1;
          } else {
            throw new FileException("<div class = \"errMsg\">ファイルのアップロードに失敗しました！</div>");
          }
        }
      }
    }

    public function post($db) {
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      try {
        //トランザクションを開始する
        $db->beginTransaction();

        $stt = $db->prepare("INSERT INTO posts(news, postuser, posttime, pageid, file) VALUES (:news, :postuser, :posttime, :pageid, :file)");
        $stt->bindValue(":news", $this->free);
        $stt->bindValue(":postuser", $this->name);
        $stt->bindValue(":posttime", \time());
        $stt->bindValue(":pageid", $this->pageid);
        if ($this->fileflag == 0) {
          $stt->bindValue(":file", "");
        } else {
          $stt->bindValue(":file", $this->uploadfile);
        }
        $result = $stt->execute();

        $db->commit();
      } catch(PDOException $e) {
        $db->rollBack();
        print "データベースでエラーが発生しました! : {$e->getMessage}";
        //ファイルも削除しておく
        if ($this->fileflag == 1) {
          unlink($filename);
        }
      }

      if ($result) {
        print "<p>投稿できました。</p>";
        print "<p>投稿内容:".e($this->free)."</p>";
        print "<a href = \"pageview.php?pageid=$this->pageid\">戻る</a>";
      }
    }
  }


?>

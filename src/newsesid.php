<?php
namespace Kumon\KeizibanLib;

  class Newsesid {
    //だいたい10%でセッションidを再発行する
      public static function newsesid() {
        $rand = mt_rand(0, 99);
        if ($rand < 10) {
          session_regenerate_id(TRUE);
        }
      }
  }

?>

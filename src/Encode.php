<?php
  class charset_const {
    public const charset = 'UTF-8';
  }
  function e(string $str) {
    return htmlspecialchars( $str, ENT_QUOTES | ENT_HTML5, charset_const::charset );
  }
?>

<?php
use Kumon\KeizibanLib\Test;

class CommonDatabaseTest extends PHPUnit_Extensions_Database_TestCase
{
    static public $pdo = null;
    public $connection = null;

    // テストのDB接続情報
    public function getConnection()
    {
        if ( $this->connection == null ) {
            if ( self::$pdo == null ) {
                self::$pdo = new PDO($GLOBALS['DB_DSN'],
                                     $GLOBALS['DB_USER'],
                                     $GLOBALS['DB_PASSWORD']);
                self::$pdo->query('SET NAMES UTF8');
            }
            $this->connection = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->connection;
    }

    // テストがDBに接続してデータを初期化する処理
    public function getDataSet()
    {
        $compositeDs = new PHPUnit_Extensions_Database_DataSet_CompositeDataSet([]);

        // fixture配下の.xmlファイルをすべて読み込んでデータセットしてる
        $dir = dirname(__FILE__) . '/fixture';
        $fh  = opendir($dir);

        while ($file = readdir($fh)) {
            if ( preg_match('/^\./', $file) ) {
                continue;
            }
            if ( preg_match('/\.xml$/', $file) ) {
                $ds = $this->createMySQLXMLDataSet("$dir/$file");
                $compositeDs->addDataSet($ds);
            }
        }

        return $compositeDs;
    }
}
?>

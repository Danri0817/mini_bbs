<?php
try
{
    $db = new PDO('mysql:dbname=bbs;host=127.0.0.1;charset=utf8','root', 'Dankg0817');
} catch (PDOException $e) {
    print('DB接続エラー:' . $e->getMessage());
}
?>

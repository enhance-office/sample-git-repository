<?php
// defineの値は環境によって変えてください。
define('DB_HOST','localhost');
define('DB_NAME','t_reserve');
define('DB_USER','root');
define('DB_PASSWORD','password');

try {
  /// DB接続を試みる
  $db  = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_USER);
  $msg = "MySQL への接続確認が取れました。";
} catch (PDOException $e) {
  $isConnect = false;
  $msg       = "MySQL への接続に失敗しました。<br>(" . $e->getMessage() . ")";
}
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>MySQL接続確認</title>
  </head>
  <body>
    <h1>MySQL接続確認</h1>
    <p><?php echo $msg; ?></p>
  </body>
</html>
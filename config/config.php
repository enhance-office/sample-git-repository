<?php
error_reporting(E_ALL &~E_WARNING);

ini_set('date.timezone','Asia/Tokyo');

define('DB_HOST','localhost');
define('DB_NAME','t_reserve');
define('DB_USER','root');
define('DB_PASSWORD','password');

//予約者メール送信用の定数
define('ADMIN_EMAIL','info@trickys.jp');

define('SHOP_ID',1);
define('MAX_RESERVABLE_DATA',10);
define('MAX_RESERVABLE_NUM',10);

mb_language('japanese');
mb_internal_encoding('UTF-8');

?>
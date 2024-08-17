<?php

$page_title ='予約システムログアウト｜トリッキーズ学芸大学';

session_start();

// セッション変数をすべて解除
$_SESSION = array();

// セッションクッキーが設定されている場合、それも削除
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// セッションを破棄
session_destroy();


?>

<!doctype html>
<html lang="ja">
  <head>
  <?php include(dirname(__FILE__).'/../templates/headtag.php');?>
  </head>
  <body>

<header>
    <h1>トリッキーズ学芸大学</h1>
</header>

<h2>予約システムログアウト</h2>

<section class="og_box">

    <div class="logout_box">
<p>ログアウトしました。</p>
<p><a href="login.php">ログインページへ</a></p>
    </div>

</section>

<?php include(dirname(__FILE__).'/../templates/script.php');?>

  </body>
</html>
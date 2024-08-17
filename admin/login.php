<?php
require_once(dirname(__FILE__).'/../functions.php');

try{
  $page_title ='予約システムログイン｜トリッキーズ';
  
  session_start();
  $err = array();

  //DBに接続
  $pdo = connectDb();

  if(isset($_SESSION['USER'])){
    //ログイン済みの場合は予約一覧画面へ
    header('Location: /admin/reserve_list.php');
    unset($pdo);
    exit;
  }

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //POST処理時
    check_token();

    //入力値を取得
    $login_id = $_POST['login_id'];
    $login_password = $_POST['login_password'];

    //バリデーションチェック
    if(!$login_id){
      $err['login_id'] = 'IDを入力してください。';
    }

    if(!$login_password){
      $err['login_password'] = 'パスワードを入力してください。';
    }

    if(empty($err)){
      $sql = "SELECT * FROM shop WHERE login_id = :login_id LIMIT 1";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':login_id',$login_id,PDO::PARAM_STR);
      $stmt->execute();
      $user = $stmt->fetch();

      if($user && password_verify($login_password, $user['login_password'])){
        //ログイン処理
        $_SESSION['USER'] = $user;

        //HOME画面へ推移
        header('Location: /admin/reserve_list.php');
        unset($pdo);
        exit;
      }else{
        $err['common'] = '認証に失敗しました。';
      }
    }
  }else{
    //画面初回アクセス時
    set_token();
    $login_id = '';
    $login_password = '';
  }
}catch(Exception $e){
  header('Location: /error.php');
  unset($pdo);
  exit;
}
unset($pdo);
?>


<!doctype html>
<html lang="ja">
  <head>
  <?php include(dirname(__FILE__).'/../templates/headtag.php');?>
  </head>
  <body>

<header>
    <h1>トリッキーズ</h1>
</header>

<h2>予約システムログイン</h2>

<section class="og_box">
<form method="post">

    <?php if(isset($err['common'])): ?>
    <div class="alert alert-danger" role="alert"><?= $err['common'] ?></div>
    <?php endif; ?>

    <div class="mb-3">
        <input type="text" class="form-control mb-2 <?php if(isset($err['login_id']))echo 'is-invalid' ?>" id="login_id" name="login_id" placeholder="ID" value="<?= $login_id ?>">
        <div class="invalid-feedback"><?= $err['login_id'] ?></div>
    </div>

    <div class="mb-3">
        <input type="password" class="form-control mb-2 <?php if(isset($err['login_password']))echo 'is-invalid' ?>" id="login_password" name="login_password" placeholder="PASSWORD">
        <div class="invalid-feedback"><?= $err['login_password'] ?></div>
    </div>

    <div class="d-grid gap-2">
        <button class="btn btn-primary" type="submit">ログイン</button>
    </div>
    <input type="hidden" name="CSRF_TOKEN" value="<?=$_SESSION['CSRF_TOKEN']?>">
</form>
</section>

<?php include(dirname(__FILE__).'/../templates/script.php');?>

  </body>
</html>
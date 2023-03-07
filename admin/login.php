<?php
require_once(dirname(__FILE__).'/../functions.php');

try{
  session_start();

  //DBに接続
$pdo = new PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST.';',DB_USER,DB_USER);
$pdo->query('SET NAMES utf8;');

  if(isset($_SESSION['USER'])){
    //ログイン済みの場合は予約一覧画面へ　↓何故かパスが教材とは違う/reserve/付けなければ動作しない
    header('Location: /reserve/admin/reserve_list.php');
    exit;
  }

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //POST処理時

    //入力値を取得
    $login_id = $_POST['login_id'];
    $login_password = $_POST['login_password'];

    //バリデーションチェック
    $err = array();

    if(!$login_id){
      $err['login_id'] = 'IDを入力してください。';
    }

    if(!$login_password){
      $err['login_password'] = 'パスワードを入力してください。';
    }

    if(empty($err)){
      $sql = "SELECT * FROM shop WHERE login_id = :login_id AND login_password = :login_password LIMIT 1";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':login_id',$login_id,PDO::PARAM_STR);
      $stmt->bindValue(':login_password',$login_password,PDO::PARAM_STR);
      $stmt->execute();
      $user = $stmt->fetch();

      if($user){
        //ログイン処理
        $_SESSION['USER'] = $user;

        //HOME画面へ推移　↓何故かパスが教材とは違う/reserve/付けなければ動作しない
        header('Location: /reserve/admin/reserve_list.php');
        exit;
      }else{
        $err['common'] = '認証に失敗しました。';
      }
    }
  }else{
    //画面初回アクセス時
    $login_id = '';
    $login_password = '';
  }
}catch(Exception $e){
  header('Location: /error.php');
  exit;
}
?>


<!doctype html>
<html lang="ja">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="../css/style.css" media="all" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <title>予約システムログイン｜トリッキーズ</title>
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

</form>
</section>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>
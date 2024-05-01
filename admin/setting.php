<?php
require_once(dirname(__FILE__).'/../functions.php');

try{
$page_title ='設定｜トリッキーズ';

session_start();
$err = array();
$complete_meseage = '';

if(!isset($_SESSION['USER'])){
  //ログインしていない場合はログイン画面へ↓何故かパスが教材とは違う/reserve/付けなければ動作しない
  header('Location: /reserve/admin/login.php');
  unset($pdo);
  exit;
}

//DBに接続
$pdo = connectDb();

//ショップデータを取得
$shop = getShop();

$reservable_date_array = array();
for($i = 0; $i <= MAX_RESERVABLE_DATA; $i++){
  $reservable_date_array[$i] = $i .'日前';
}

$time_array = array();
for($i = 0; $i <= 23; $i++){
  $time_array[sprintf('%02d',$i) .':00'] = sprintf('%02d',$i) .':00';
}

$max_reserve_num_array =array();
for($i = 1; $i <= MAX_RESERVABLE_NUM; $i++){
  $max_reserve_num_array[$i] = $i .'人';
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
      //入力値を取得
      $reservable_date = $_POST['reservable_date'];
      $start_time = $_POST['start_time'];
      $end_time = $_POST['end_time'];
      $max_reserve_num = $_POST['max_reserve_num'];

      //バリデーションチェック
      if(is_null($reservable_date)){
        $err['reservable_date'] = '予約可能日を入力してください。';
      }else if(!array_key_exists($reservable_date,$reservable_date_array)){
        $err['reservable_date'] = '予約可能日を正しく入力してください。';
      }
      
      if(is_null($start_time)){
        $err['start_time'] = '開始営業時間を入力してください。';
      }else if(!array_key_exists($start_time,$time_array)){
        $err['start_time'] = '開始営業時間を正しく入力してください。';
      }

      if(is_null($end_time)){
        $err['end_time'] = '終了営業時間を入力してください。';
      }else if(!array_key_exists($end_time,$time_array)){
        $err['end_time'] = '終了営業時間を正しく入力してください。';
      }

      if(is_null($max_reserve_num)){
        $err['max_reserve_num'] = '1時間あたりの予約上限人数を入力してください。';
      }else if(!array_key_exists($max_reserve_num,$max_reserve_num_array)){
        $err['max_reserve_num'] = '1時間あたりの予約上限人数を正しく入力してください。';
      }

  
      if(empty($err)){
        $sql = "UPDATE shop SET reservable_date = :reservable_date, start_time = :start_time, end_time = :end_time, max_reserve_num = :max_reserve_num WHERE id = :id LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':reservable_date',$reservable_date, PDO::PARAM_INT);
        $stmt->bindValue(':start_time',$start_time, PDO::PARAM_STR);
        $stmt->bindValue(':end_time',$end_time, PDO::PARAM_STR);
        $stmt->bindValue(':max_reserve_num',$max_reserve_num, PDO::PARAM_INT);
        $stmt->bindValue(':id',$shop['id'], PDO::PARAM_INT);
        $stmt->execute();

        $complete_meseage = '登録が完了しました。';

      }
}else{
  $reservable_date = $shop['reservable_date'];
  $start_time = format_time($shop['start_time']);
  $end_time = format_time($shop['end_time']);
  $max_reserve_num = $shop['max_reserve_num'];
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
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link href="/reserve/css/style.css" media="all" rel="stylesheet">
  </head>

  <body>

  <header class="navbar">
  <div class="container-fluid">
    <h1 class="navbar-brand">トリッキーズ</h1>
    <div class="d-flex">
    <a href="reserve_list.php" class="btn btn-outline-success mx-2" type="submit"><i class="bi bi-list-ul"></i></a> 
    <a href="setting.php" class="btn btn-outline-success" type="submit"><i class="bi bi-gear-fill"></i></a> 
    </div>
  </div>
</header>

<h2>設定</h2>

<form class="og_box" method="post">

<?php if($complete_meseage): ?>
    <div class="alert alert-success" role="alert"><?= $complete_meseage ?></div>
    <?php endif; ?>

<div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">予約可能日</label>
        <?php
        $class = 'form-select';
        if(isset($err['reservable_date'])){
          $class .= ' is-invalid';
        }
        ?>
        <?= arrayToSelect('reservable_date',$reservable_date_array,$reservable_date,$class)?>
        <div class="invalid-feedback"><?= $err['reservable_date'] ?></div>
</div>

<div class="mb-3">
<label for="exampleFormControlInput1" class="form-label">営業時間</label>
<div class="row">
  <div class="col-5">
        <?php
        $class = 'form-select';
        if(isset($err['start_time'])){
          $class .= ' is-invalid';
        }
        ?>
  <?= arrayToSelect('start_time',$time_array,$start_time,$class)?>
  <div class="invalid-feedback"><?= $err['start_time'] ?></div>
  </div>
  <div class="col-2 text-center pt-2">〜</div>
  <div class="col-5">
        <?php
        $class = 'form-select';
        if(isset($err['end_time'])){
          $class .= ' is-invalid';
        }
        ?>
  <?= arrayToSelect('end_time',$time_array,$end_time,$class)?>
  <div class="invalid-feedback"><?= $err['end_time'] ?></div>
  </div>
</div>
</div>

<div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">予約上限人数</label>
        <?php
        $class = 'form-select';
        if(isset($err['max_reserve_num'])){
          $class .= ' is-invalid';
        }
        ?>
        <?= arrayToSelect('max_reserve_num',$max_reserve_num_array,$max_reserve_num,$class)?>
        <div class="invalid-feedback"><?= $err['max_reserve_num'] ?></div>
</div>

    <div class="d-grid gap-2">
        <button class="btn btn-primary" type="submit">登録</button>
    </div>


</form>

<?php include(dirname(__FILE__).'/../templates/script.php');?>

  </body>
</html>
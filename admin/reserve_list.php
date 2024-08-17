<?php
require_once(dirname(__FILE__).'/../functions.php');

try{
$page_title ='ご予約リスト｜トリッキーズ学芸大学';
  
session_start();
if(!isset($_SESSION['USER'])){
  //ログインしていない場合はログイン画面へ
  header('Location: /admin/login.php');
  unset($pdo);
  exit;
}

//DBに接続
$pdo = connectDb();

$year = @$_GET['year'];
$month = @$_GET['month'];

if(!$year){
  $year=date('Y');
}

if(!$month){
  $month=date('m');
}

//対象年月の予約データを取得
$stmt = $pdo->prepare("SELECT * FROM reserve WHERE DATE_FORMAT(reserve_date,'%Y%m')=:yyyymm ORDER BY reserve_date,reserve_time");
$stmt->bindValue(':yyyymm',$year.$month,PDO::PARAM_STR);
$stmt->execute();
$reserve_list = $stmt->fetchAll();

//デバッグ
//var_dump($reserve_list);
//exit;

//年プルダウンの構築（1年前から3年後まで）
$year_array=array();
$current_year=date('Y');
for($i=($current_year - 1);$i <= ($current_year +3); $i++){
  $year_array[$i]=$i.'年';
}

//月プルダウンの構築（1月から12月）
$month_array=array();
for($i=1;$i <=12;$i++){
  $month_array[sprintf('%02d',$i)]=$i.'月';
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

<header class="navbar">
  <div class="container-fluid">
    <h1 class="navbar-brand">トリッキーズ学芸大学</h1>
    <div class="d-flex">
    <a href="reserve_list.php" class="btn btn-outline-success mx-2" type="submit"><i class="bi bi-list-ul"></i></a> 
    <a href="setting.php" class="btn btn-outline-success" type="submit"><i class="bi bi-gear-fill"></i></a> 
    </div>
  </div>
</header>

<h2>ご予約リスト</h2>

<section class="og_box">

<form id="filter-form" method="get">
<div class="row mx-3 mb-2">
  <div class="col">
    <?= arrayToSelect('year',$year_array,$year)?>
  </div>
  <div class="col mx-3 mb-2">
    <?= arrayToSelect('month',$month_array,$month)?>
  </div>
</div>
</form>


<?php if(!$reserve_list):?>
  <div class="alert alert-warning" role="alert">予約データがありません。</div>
<?php else:?>

<table class="table">
  <tbody>
  <?php foreach($reserve_list as $reserve):?>
    <tr>
      <th class="px-2"><?= format_date(h($reserve['reserve_date']))?></th>
      <th class="px-2"><?= format_time(h($reserve['reserve_time']))?></th>
      <td class="px-2">
        <?= h($reserve['name'])?><br>
        <?= h($reserve['reserve_num'])?>名<br>
        <?= h($reserve['email'])?><br>
        <?= h($reserve['tel'])?><br>
        <?= mb_strimwidth(h($reserve['comment']),0,90,'...')?>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<?php endif;?>

</section>

<?php include(dirname(__FILE__).'/../templates/script.php');?>

     <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

    <script>
      $('.form-select').change(function(){
        $('#filter-form').submit()
      })
    </script>

  </body>
</html>
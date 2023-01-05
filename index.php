<?php
require_once 'functions.php';

//DBに接続
$pdo = new PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST.';',DB_USER,DB_USER);
$pdo->query('SET NAMES utf8;');

//ショップデータを取得
$stmt = $pdo->prepare('SELECT * FROM shop WHERE id=:id');
$stmt->bindValue(':id', 1, PDO::PARAM_INT);
$stmt->execute();
$shop=$stmt->fetch();


//予約日選択配列例
$reserve_date_array=array();
for($i=0;$i<=$shop['reservable_date'];$i++){
    //対象日を取得
    $target_date=strtotime("+{$i}day");

    //配列に設定
    $reserve_date_array[date('ymd',$target_date)]=date('n/j',$target_date);
}

//予約時間選択配列例
//TODO:24時以降の扱いたい
$reserve_time_array = array();
for($i=date('G',strtotime($shop['start_time'])); $i<=date('G',strtotime($shop['end_time'])); $i++){
    $reserve_time_array[sprintf('%02d',$i).':00']=sprintf('%02d',$i).':00';
}

//予約時間選択配列例
$reserve_num_array = array();
for($i=1;$i<=$shop['max_reserve_num'];$i++){
    //配列に設定
    $reserve_num_array[$i]=$i;
}

//配列チェック用バーダンプ
// var_dump($reserve_num_array);
// exit;

//配列チェック用エコー
// echo date('G',strtotime($shop['start_time']));
// echo "<br>";
// echo date('G',strtotime($shop['end_time']));
// exit;


session_start();

//エラーメッセージ格納用変数
$err = array();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //POSTパラメータから各種入力値を受け取る
    $reserve_date = $_POST['reserve_date'];
    $reserve_time = $_POST['reserve_time'];
    $reserve_num = $_POST['reserve_num'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $comment = $_POST['comment'];
 
    //各種入力値のバリエーション
    if(!$reserve_date){
        $err['reserve_date'] = '予約日を入力してください。';
    }
    //TODO:予約日はプルダウン設定値を決定後にバリエーション実装

    if(!$reserve_time){
        $err['reserve_time'] = '予約時間を入力してください。';
    }
    //TODO:予約時間はプルダウン設定値を決定後にバリエーション実装

    if(!$reserve_num){
        $err['reserve_num'] = '予約人数を入力してください。';
    }else if(!preg_match('/^[0-9]+$/',$reserve_num)){
        $err['reserve_num'] = '人数を正しく入力してください。';  
    }

    if(!$name){
        $err['name'] = '名前を入力してください。';
    }else if(mb_strlen($name,'utf-8')>20){
        $err['name'] = '名前は20文字以内で入力してください。';      
    }

    if(!$email){
        $err['email'] = 'メールアドレスを入力してください。';
    }else if(mb_strlen($email,'utf-8')>100){
        $err['email'] = 'メールアドレスは100文字以内で入力してください。';      
    }else if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $err['email'] = 'メールアドレスが不正です。'; 
    }

    if(!$tel){
        $err['tel'] = '電話番号を入力してください。';
    }else if(mb_strlen($tel,'utf-8')>20){
        $err['tel'] = '電話番号は20文字以内で入力してください。';      
    }else if(!preg_match('/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$/',$tel)){
        $err['tel'] = '電話番号を正しく入力してください。';  
    }

    if(mb_strlen($comment,'utf-8')>2000){
        $err['comment'] = '備考欄はは2000文字以内で入力してください。';      
    }

    //エラーが無ければ次の処理へ進む
    if(empty($err)){

        //各種入力値をセッションに変数に保存する
        $_SESSION['RESERVE']['reserve_date']=$reserve_date;
        $_SESSION['RESERVE']['reserve_time']=$reserve_time;
        $_SESSION['RESERVE']['reserve_num']=$reserve_num;
        $_SESSION['RESERVE']['name']=$name;
        $_SESSION['RESERVE']['email']=$email;
        $_SESSION['RESERVE']['tel']=$tel;
        $_SESSION['RESERVE']['comment']=$comment;

        //予約確認画面へ遷移
        header('Location: /reserve/confirm.php');
        exit;
    }

}else{
    //セッションに入力情報がある場合は取得する
    if(isset($_SESSION['RESERVE'])){
        $reserve_date = $_SESSION['RESERVE']['reserve_date'];
        $reserve_time = $_SESSION['RESERVE']['reserve_time'];
        $reserve_num = $_SESSION['RESERVE']['reserve_num'];
        $name = $_SESSION['RESERVE']['name'];
        $email = $_SESSION['RESERVE']['email'];
        $tel = $_SESSION['RESERVE']['tel'];
        $comment = $_SESSION['RESERVE']['comment'];
    }else{
        //セッションに入力情報がない場合は初期化する
        $reserve_date = '';
        $reserve_time = '';
        $reserve_num = '';
        $name = '';
        $email = '';
        $tel = '';
        $comment = '';
    }
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
    <link href="css/style.css" media="all" rel="stylesheet">

    <title>ご来店予約｜トリッキーズ</title>
  </head>
  <body>

<header>
    <h1>トリッキーズ</h1>
</header>

<h2>ご来店予約</h2>


<section class="og_box">
<form method="post">

    <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">予約日選択</label>
        <?= arrayToSelect('reserve_date',$reserve_date_array,$reserve_date)?>
        <div class="invalid-feedback"><?= $err['reserve_date'] ?></div>
    </div>

    <div class="mb-3">
        <label for="exampleFormControlTextarea1" class="form-label">予約時間選択</label>
        <?= arrayToSelect('reserve_time',$reserve_time_array,$reserve_time)?>
        <div class="invalid-feedback"><?= $err['reserve_time'] ?></div>
    </div>

    <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">予約人数</label>
        <?= arrayToSelect('reserve_num',$reserve_num_array,$reserve_num)?>
        <div class="invalid-feedback"><?= $err['reserve_num'] ?></div>
    </div>

    <div class="mb-3">
        <label for="exampleFormControlTextarea1" class="form-label">予約者情報入力</label>
        <input type="text" class="form-control <?php if(isset($err['name']))echo 'is-invalid' ?>" name="name" placeholder="名前" value="<?= $name ?>">
        <div class="invalid-feedback"><?= $err['name'] ?></div>
    </div>
    <div class="mb-3">
        <input type="text" class="form-control <?php if(isset($err['email']))echo 'is-invalid' ?>" name="email" placeholder="メールアドレス" value="<?= $email ?>">
        <div class="invalid-feedback"><?= $err['email'] ?></div>
        </div>
    <div class="mb-3">
        <input type="text" class="form-control <?php if(isset($err['tel']))echo 'is-invalid' ?>" name="tel" placeholder="電話番号" value="<?= $tel ?>">
        <div class="invalid-feedback"><?= $err['tel'] ?></div>
    </div>

    <div class="mb-3">
        <label for="exampleFormControlTextarea1" class="form-label">備考欄</label>
        <textarea class="form-control  <?php if(isset($err['comment']))echo 'is-invalid' ?>" id="exampleFormControlTextarea1" rows="3" name="comment" placeholder="備考"><?= $comment ?></textarea>
        <div class="invalid-feedback"><?= $err['comment'] ?></div>
    </div>

    <div class="d-grid gap-2">
        <button class="btn btn-primary" type="submit">内容確認</button>
        <button class="btn btn-light" type="button">戻る</button>
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



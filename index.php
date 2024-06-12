<?php
require_once(dirname(__FILE__).'/functions.php');

try{
$page_title ='ご来店予約｜トリッキーズ';

session_start();
$err = array();

//DBに接続
$pdo = connectDb();

//ショップデータを取得
$shop = getShop();

//予約日選択配列
$reserve_date_array=array();
for($i=0;$i<=$shop['reservable_date'];$i++){
    //対象日を取得
    $target_date=strtotime("+{$i}day");

    //配列に設定
    $reserve_date_array[date('Ymd',$target_date)]=date('n/j',$target_date);
}

//予約時間選択配列
//TODO:24時以降を扱いたい
$reserve_time_array = array();
for($i=date('G',strtotime($shop['start_time'])); $i<=date('G',strtotime($shop['end_time'])); $i++){
    $reserve_time_array[sprintf('%02d',$i).':00']=sprintf('%02d',$i).':00';
}

//予約時間選択配列
$reserve_num_array = array();
for($i=1;$i<=$shop['max_reserve_num'];$i++){
    //配列に設定
    $reserve_num_array[$i]=$i;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    check_token();
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
    }else if(!array_key_exists($reserve_date,$reserve_date_array)){
        $err['reserve_date'] = '予約日を正しく入力してください。';
    }

    if(!$reserve_time){
        $err['reserve_time'] = '予約時間を入力してください。';
    }else if(!array_key_exists($reserve_time,$reserve_time_array)){
        $err['reserve_time'] = '予約時間を正しく入力してください。';
    }

    if(!$reserve_num){
        $err['reserve_num'] = '予約人数を入力してください。';
    }else if(!preg_match('/^[0-9]+$/',$reserve_num)){
        $err['reserve_num'] = '人数を正しく入力してください。'; 
    }else if(!array_key_exists($reserve_num,$reserve_num_array)){
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
        $err['tel'] = '電話番号を正しく入力してください。※ハイフン含む形式でご記入ください。例:000-0000-0000';  
    }

    if(mb_strlen($comment,'utf-8')>2000){
        $err['comment'] = '備考欄はは2000文字以内で入力してください。';      
    }

    //エラーが無ければ次の処理へ進む
    if(empty($err)){
        //DBのreserveテーブルからその日時の「予約成立済み人数」を取得
        $stmt = $pdo->prepare("SELECT SUM(reserve_num) FROM reserve WHERE DATE_FORMAT(reserve_date,'%Y%m%d')=:reserve_date AND DATE_FORMAT(reserve_time,'%H:%i')=:reserve_time GROUP BY reserve_date,reserve_time LIMIT 1");
        $stmt->bindValue(':reserve_date',$reserve_date,PDO::PARAM_STR);
        $stmt->bindValue(':reserve_time',$reserve_time,PDO::PARAM_STR);
        $stmt->execute();
        $reserve_count = $stmt->fetchColumn();

        //1時間あたりの予約上限チェック
        if($reserve_count && ($reserve_count+$reserve_num)>$shop['max_reserve_num']){
            $err['common']='この日時は既に予約が埋まっておりますので別の日時をご指定ください。';
        }

        //エラーがなければ次の処理に進む
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
        unset($pdo);
        exit;
        }
    }


}else{
    set_token();
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
}catch(Exception $e){
    header('Location: /reserve/error.php');
    exit;
}
unset($pdo);
?>


<!doctype html>
<html lang="ja">
  <head>
  <?php include(dirname(__FILE__).'/templates/headtag.php');?>
  </head>
  <body>

<header>
    <h1>トリッキーズ</h1>
</header>

<h2>ご来店予約</h2>


<section class="og_box">
<form method="post">

<?php if(isset($err['common'])): ?>
<div class="alert alert-danger" role="alert"><?= $err['common'] ?></div>
<?php endif; ?>

    <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">予約日選択</label>
        <?php
        $class = 'form-select';
        if(isset($err['reserve_date'])){
          $class .= ' is-invalid';
        }
        ?>
        <?= arrayToSelect('reserve_date',$reserve_date_array,$reserve_date,$class)?>
        <div class="invalid-feedback"><?= $err['reserve_date'] ?></div>
    </div>

    <div class="mb-3">
        <label for="exampleFormControlTextarea1" class="form-label">予約時間選択</label>
        <?php
        $class = 'form-select';
        if(isset($err['reserve_time'])){
          $class .= ' is-invalid';
        }
        ?>
        <?= arrayToSelect('reserve_time',$reserve_time_array,$reserve_time,$class)?>
        <div class="invalid-feedback"><?= $err['reserve_time'] ?></div>
    </div>

    <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">予約人数</label>
        <?php
        $class = 'form-select';
        if(isset($err['reserve_num'])){
          $class .= ' is-invalid';
        }
        ?>
        <?= arrayToSelect('reserve_num',$reserve_num_array,$reserve_num,$class)?>
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

    <input type="hidden" name="CSRF_TOKEN" value="<?=$_SESSION['CSRF_TOKEN']?>">
</form>
</section>

<?php include(dirname(__FILE__).'/templates/script.php');?>

  </body>
</html>



<?php

require_once(dirname(__FILE__).'/../config/config.php');

//DBに接続
function connectDb()
{
$pdo = new PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST.';',DB_USER,DB_USER);
$pdo->query('SET NAMES utf8;');
return $pdo;
}

//ショップデータを取得
function getShop()
{
global $pdo;
$stmt = $pdo->prepare('SELECT * FROM shop WHERE id=:id');
$stmt->bindValue(':id', SHOP_ID, PDO::PARAM_INT);
$stmt->execute();
return $stmt->fetch();
}

//プルダウン箇所は比較処理が必要な為、共通関数を制作

//引数で与えられた配列を元にプルダウンリストを自動生成
function arrayToSelect($inputName, $srcArray, $selectedIndex= "", $class = "form-select")
{
    $temphtml ="<select class=\"{$class}\" name=\"{$inputName}\">" .PHP_EOL;
    
    foreach($srcArray as $key => $val){
        //キーと選択値を比較して一致したらselectedを付与
        if($key == $selectedIndex){
            $selectedText =" selected";
        }else{
            $selectedText ="";
        }
        $temphtml .="<option value=\"{$key}\"{$selectedText}>{$val}</option>" .PHP_EOL;
    }
    $temphtml .="</select>" .PHP_EOL;

    return $temphtml;
}

//引数で与えられた日付を表示形式に変換
function format_date($yyyymmdd){
    $week =array('日','月','火','水','木','金','土');
    return date('n/j('.$week[date('w',strtotime($yyyymmdd))].')', strtotime($yyyymmdd));
}

//引数で与えられた時間を表示形式「00:00」に変換
function format_time($time){
    return substr($time, 0, -3);
}
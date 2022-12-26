<?php
//プルダウン箇所は比較処理が必要な為、共通関数を制作

//配列を受け取ってプルダウンリストを自動生成
function arrayToSelect($inputName, $srcArray, $selectedIndex= "")
{
    $temphtml ="<select class=\"form-select\" name=\"{$inputName}\">" .PHP_EOL;
    
    foreach($srcArray as $key => $val){
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
<?php 
$page_title ='エラー発生｜トリッキーズ学芸大学';
?>

<!doctype html>
<html lang="ja">
  <head>
  <?php include(dirname(__FILE__).'/templates/headtag.php');?>
  </head>
  <body>

<header>
    <h1>トリッキーズ学芸大学</h1>
</header>

<h2>エラー発生</h2>

<section class="og_box">

    <div class="og_box_in">
        <i class="bi bi-exclamation-circle"></i>
        <p>エラーが発生しました。お手数ですが、最初の画面からやり直してください。</p>
    </div>

    <div class="d-grid gap-2 mt-3">
        <a href="index.php" class="btn btn-primary">トップに戻る</a>
    </div>

</section>

<?php include(dirname(__FILE__).'/templates/script.php');?>

  </body>
</html>
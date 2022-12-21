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
<form method="post" action="confirm.php">

    <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">予約日選択</label>
        <select class="form-select" aria-label="Default select example">
        <option selected>日付</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="exampleFormControlTextarea1" class="form-label">予約時間選択</label>
        <select class="form-select" aria-label="Default select example">
        <option selected>時間</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">予約人数</label>
        <select class="form-select" aria-label="Default select example">
        <option selected>1人</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="exampleFormControlTextarea1" class="form-label">予約者情報入力</label>
        <input type="email" class="form-control mb-2" id="exampleFormControlInput1" placeholder="名前">
        <input type="email" class="form-control mb-2" id="exampleFormControlInput1" placeholder="メールアドレス">
        <input type="email" class="form-control mb-2" id="exampleFormControlInput1" placeholder="電話番号">
    </div>

    <div class="mb-3">
        <label for="exampleFormControlTextarea1" class="form-label">備考欄</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="備考"></textarea>
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
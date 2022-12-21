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
    <title>設定｜トリッキーズ</title>
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

<section class="og_box">

<div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">予約可能日</label>
        <select class="form-select" aria-label="Default select example">
        <option selected>1日前</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
        </select>
</div>

<div class="mb-3">
<label for="exampleFormControlInput1" class="form-label">営業時間</label>
<div class="row">
  <div class="col-5">
        <select class="form-select" aria-label="Default select example">
        <option selected>00:00</option>
        <option value="1">2023</option>
        </select>
  </div>
  <div class="col-2 text-center pt-2">〜</div>
  <div class="col-5">
        <select class="form-select" aria-label="Default select example">
        <option selected>24:00</option>
        <option value="1">2月</option>
        </select>
  </div>
</div>
</div>

<div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">予約上限人数</label>
        <select class="form-select" aria-label="Default select example">
        <option selected>1人</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
        </select>
</div>

    <div class="d-grid gap-2">
        <button class="btn btn-primary" type="submit">登録</button>
    </div>


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
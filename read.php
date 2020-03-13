<?php

require_once 'header.php';
require_once 'db.php';
$db = new Db();
$db->table_name="books";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $book = $db->get_one(['id' => $id]);
}

?>
<!doctype html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Книги</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <h1>Книга "<?php echo $book['name']; ?>"</h1>
    </div>
    <div class="row">
        <?php echo $book['text']; ?>
    </div>
    <div class="row">
        <button onclick="location.href='index.php'" style="margin:0 auto">На главную</button>
    </div>
</div>
</body>

<script>
    function sendData() {

    }
</script>

<style>
    h1 {
        margin-left: auto;
        margin-right: auto;
    }
    button {
        margin-left: auto;
        margin-right: auto;
    }

</style>
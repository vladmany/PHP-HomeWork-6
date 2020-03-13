<?php

require_once 'header.php';
require_once 'db.php';
$db = new Db();
$db->table_name="books";
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
        <h1>Добавление новой книги</h1>
    </div>
    <div class="row">
        <form method="post" onsubmit="sendData();return false;" id="formNews">
            <div class="col-12">
                <span>Название книги:</span>
                <input name="name" type="text">
                <div class="invalid-feedback"></div>
            </div>
            <div class="col-12">
                <span>Описание книги:</span>
                <textarea name="description"></textarea>
                <div class="invalid-feedback"></div>
            </div>
            <div class="col-12">
                <span>Автор книги:</span>
                <input name="author" type="text">
                <div class="invalid-feedback"></div>
            </div>
            <div class="col-12">
                <span>Текст книги:</span>
                <textarea name="text"></textarea>
                <div class="invalid-feedback"></div>
            </div>
            <div class="col-12">
                <button onclick="location.href='index.php'">Отмена</button>
                <input type="submit" value="Добавить">
                <input type="text" class="actionType" name="action" style="display: none" value="add">
            </div>
        </form>
    </div>
</div>
</body>

<script>
    function sendData() {
        let form = '#formNews';
        let dataForm = $('form').serialize();
        $('*', form).removeClass('error');
        $('.invalid-feedback').empty();
        $.ajax({
            url: 'server.php', //куда отправить данные
            type: 'POST',
            dataType: 'json',
            data: dataForm, // данные для отправки
            success: function(responce){//метод который выполняется когда
                //пришел ответ от сервера
                if (responce['state'] == 'success')
                {
                    location.href='index.php'
                }
                else {
                    for(key in responce)
                    {
                        $(`[name="${key}"]`, form).addClass('error');
                        $(`[name="${key}"]`, form).siblings('.invalid-feedback')
                            .html( responce[key]
                                .join("<br>") )
                            .show();
                    }
                }
            }
        })
    }
</script>

<style>
    .error {
        border: 1px solid red;
    }
    .invalid-feedback {
        margin: 0;
    }
    h1 {
        margin-left: auto;
        margin-right: auto;
    }
    [name='name'], [name='description'], [name="author"], [name='text'] {
        width: 1200px;
    }
    form > div {
        margin-top: 15px;
    }
    form > div:last-child {
        margin-top: 5px;
        display: flex;
        justify-content: center;
        margin-bottom: 15px;

    }
    form > div:last-child > button {
        margin-right: 15px;
    }
    [name='description'] {
        height: 100px;
        resize: none;
    }
    [name='text'] {
        height: 450px;
        resize: none;
    }
</style>

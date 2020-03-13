<?php

session_start();

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
        <div class="row main-row">
            <?php
                if (isset($_SESSION['user']))
                {
                    echo '<button class="addBtn" onclick="location.href=\'add.php\'"><span>+</span></button>';
                }
                else
                {
                    echo '<span class="help-message">Добавление и редактирование книг доступно только <a href="">авторизованным</a> пользователям.</span>';
                }
            ?>

            <div class="books-wrapper">
                <?php
                    $books = $db->get_all('id desc');
                  if (count($books) == 0) {
                      echo '<span class="empty">Список книг пуст.</span>';
                  }
                  else {
                      foreach ($books as $book)
                      {
                          echo '<div class="element">'.
                                  '<h5 class="name">' . $book['name'] . '</h5>'.
                                  '<span class="description"> Описание: ' . $book['description'] . '</span>'.
                                  '<span class="author"> Автор: ' . $book['author'] . '</span>'.
                                    '<span class="create_at"> Дата публикации: ' . $book['create_at'] . '</span>'.
                                  '<div>'.
                                  '<span class="posted_by"> Автор публикации: ' . $book['posted_by'] . '</span>'.
                                      '<div style="display: flex">';
                                        if (isset($_SESSION['user']))
                                            if (($book['posted_by'] == $_SESSION['user']['login']) or ($_SESSION['user']['login']=='admin'))
                                                echo '<form method="post" action="edit.php" >'.
                                                        '<input type="text" name="id" style="display: none" value=' . $book['id'] . '>'.
                                                        '<input type="submit" value="Изменить">'.
                                                    '</form>';
                                      echo '<form method="post" action="read.php" >'.
                                            '<input type="text" name="id" style="display: none" value=' . $book['id'] . '>'.
                                            '<input type="submit" value="Читать">'.
                                        '</form>'.
                                      '</div>'.
                                  '</div>'.
                              '</div>';
                      }
                  }

                ?>
            </div>
        </div>
    </div>
</body>

<script>
    function bookEdit(id) {
        var ob = {
            'id':id
        };
        $.ajax({
            url: 'index.php', //куда отправить данные
            type: 'POST',
            data:'param='+JSON.stringify(ob), // данные для отправки
            success: function(responce){//метод который выполняется когда
                //пришел ответ от сервера
                location.href='edit.php'
            }
        })
    }
    function bookRead(id) {
        var ob = {
            'id':id
        };
        $.ajax({
            url: 'index.php', //куда отправить данные
            type: 'POST',
            data:'param='+JSON.stringify(ob), // данные для отправки
            success: function(responce){//метод который выполняется когда
                //пришел ответ от сервера
                location.href='read.php'
            }
        })
    }

    $('a',$('.help-message','.main-row')).click(function () {
        signIn();
        return false
    })
</script>

<style>
    .empty {
        padding-left: 10px;
    }
    .addBtn {
        width: 50px;
        height: 50px;
        background-color: rgba(256, 256, 256, 1);
        border-radius: 50px;
        border-color: gray;
        margin: 10px;
        cursor: pointer;
    }
    .help-message {
        margin-top: 20px;
        font-style: italic;
    }

    .help-message > a {
        color: cadetblue;
        font-style: oblique;
    }

    .addBtn:active, .addBtn:focus {
        outline: none;
    }
    .addBtn span {
        font-size: 40px;
        line-height: 0;
        padding-bottom: 5px;
    }
    .editBtn {
        /*display: block;*/
    }
    .books-wrapper {
        display: block;
        width: 100%;
        border: 1px solid #000;
    }
    .element {
        border-bottom: 1px solid gray;
        padding-left: 5px;
    }

    .element > div {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }
    span {
        display: block;
    }

</style>

<?php
session_start();
?>
<!doctype html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <link rel="stylesheet" href="css/animate.css">
</head>
<body>
<header>
    <div class="main-bg" style="display: none" onclick="cancelRegister(); cancelAuth()">
    </div>
    <div class="auth animated fadeInDown" style="display: none">
        <span><strong>Вход</strong></span>
        <hr>
        <form id="authForm" method="post" onsubmit="sendAuth();return false" class="d-flex flex-column">
            <div class="login-field d-flex flex-column">
                <label for="login"><strong>Логин или Email</strong></label>
                <input type="text" id="login" name="login">
                <div class="invalid-feedback"></div>
            </div>
            <div class="password-field d-flex flex-column">
                <label for="password"><strong>Пароль</strong></label>
                <input type="password" id="password" name="password">
                <div class="invalid-feedback"></div>
            </div>
            <input type="text" class="actionType" name="action" style="display: none" value="auth">
            <input type="submit" value="Войти" class="btnAuth">
            <button class="cancelAuth" onclick="cancelAuth();return false">Отмена</button>
            <span class="help-message">Нет аккаунта? - <a href="">Зарегистрируйтесь.</a></span>
        </form>
    </div>
    <div class="register animated fadeInDown" style="display: none">
        <span><strong>Регистрация</strong></span>
        <hr>
        <form id="registerForm" method="post" onsubmit="sendRegister();return false" class="d-flex flex-column">
            <div class="email-field d-flex flex-column">
                <label for="email"><strong>Email</strong></label>
                <input type="email" id="email" name="email" >
                <div class="invalid-feedback"></div>
            </div>
            <div class="login-field d-flex flex-column">
                <label for="login"><strong>Логин</strong></label>
                <input type="text" id="login" name="login">
                <div class="invalid-feedback"></div>
            </div>
            <div class="password-field d-flex flex-column">
                <label for="password"><strong>Пароль</strong></label>
                <input type="password" id="password" name="password">
                <div class="invalid-feedback"></div>
            </div>
            <div class="rpassword-field d-flex flex-column">
                <label for="rpassword"><strong>Повторите пароль</strong></label>
                <input type="password" id="rpassword" name="rpassword">
                <div class="invalid-feedback"></div>
            </div>
            <input type="text" class="actionType" name="action" style="display: none" value="register">
            <input type="submit" value="Зарегистрироваться" class="btnRegister">
            <button class="cancelRegister" onclick="cancelRegister();return false">Отмена</button>
            <span class="help-message">Уже есть аккаунт? - <a href="">Войдите.</a></span>
        </form>
    </div>
    <div class="fluid-container header-wrapper d-flex justify-content-between">
        <div class="row d-flex align-self-center ml-1 home-link">
            <img src="img/home.svg" alt="Home Page" class="home-img">
            <span>На главную</span>
        </div>
        <div class="row d-flex align-self-center mr-0">
            <?php
            if (!isset($_SESSION['user'])) {
                echo '<div class="col-2 d-flex flex-row" >
                <button class="btnSignUp" onclick="signUp()">Зарегистрироваться</button>
                <button class="btnSignIn" onclick="signIn()">Войти</button>
            </div>';
            }
            else
            {
                echo '<span class="userLogin">'.$_SESSION['user']['login'].'</span>'.
                '<img src="img/logout.svg" id="logOutBtn" alt="Выйти">';
            }
            ?>
        </div>
    </div>

</header>
</body>
</html>

<script>
    $('body>div:first-child').hide();
    $('.cbalink').hide();
    function sendRegister() {
        let form = '#registerForm';
        let dataForm = $(form).serialize();
        console.log(dataForm)
        $('*', form).removeClass('error');
        $('.invalid-feedback').empty();
        $.ajax({
            url: 'server.php', //куда отправить данные
            type: 'POST',
            dataType: 'json',
            data: dataForm, // данные для отправки
            success: function(responce){//метод который выполняется когда
                //пришел ответ от сервера
                console.log(responce);
                if (responce['state'] == 'success')
                {
                    $('#email', '#registerForm').val("");
                    $('#login', '#registerForm').val("");
                    $('#password', '#registerForm').val("");
                    $('#rpassword', '#registerForm').val("");
                    $('.main-bg').hide();
                    $('.register').hide();
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
    function sendAuth() {
        let form = '#authForm';
        let dataForm = $(form).serialize();
        console.log(dataForm)
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


    function signUp() {
        $('*', '#registerForm').removeClass('error');
        $('.invalid-feedback').empty();
        $('.register').removeClass('animated fadeOutUp');
        $('.register').addClass('animated fadeInDown');
        $('.main-bg').show();
        $('.register').show();
    }
    function signIn() {
        $('*', '#authForm').removeClass('error');
        $('.invalid-feedback').empty();
        $('.auth').removeClass('animated fadeOutUp');
        $('.auth').addClass('animated fadeInDown');
        $('.main-bg').show();
        $('.auth').show();
    }
    function cancelRegister() {
        $('#email', '#registerForm').val("");
        $('#login', '#registerForm').val("");
        $('#password', '#registerForm').val("");
        $('#rpassword', '#registerForm').val("");
        $('.register').removeClass('animated fadeInDown');
        $('.register').addClass('animated fadeOutUp');
        setTimeout(function () {
            $('.register').hide();
            $('.main-bg').hide();
        }, 600);

        return false
    }
    function cancelAuth() {
        $('#login', '#authForm').val("");
        $('#password', '#authForm').val("");
        $('.auth').removeClass('animated fadeInDown');
        $('.auth').addClass('animated fadeOutUp');
        setTimeout(function () {
            $('.auth').hide();
            $('.main-bg').hide();
        }, 600);

        return false
    }
    $('#logOutBtn').click(function () {
        $.ajax({
            url: 'server.php', //куда отправить данные
            type: 'POST',
            dataType: 'json',
            data: 'action=logout', // данные для отправки
            success: function(responce){//метод который выполняется когда
                //пришел ответ от сервера
                console.log(responce);
                if (responce['state'] == 'success')
                {
                    location.href='index.php'
                }
            }
        })
    })
    $('a',$('.help-message','#authForm')).click(function () {
        cancelAuth();
        setTimeout(function () {
            signUp();
        }, 600);
        return false
    })
    $('a',$('.help-message','#registerForm')).click(function () {
        cancelRegister();
        setTimeout(function () {
            signIn();
        }, 600);
        return false
    })
    $('.home-link').click(function () {
        location.href='index.php'
    })

</script>

<style>
    .help-message {
        margin-top: 20px;
        font-style: italic;
        text-align: center;
    }

    .help-message > a {
        color: cadetblue;
        font-style: oblique;
    }

    .auth {
        padding-bottom: 30px;
        z-index: 2;
        width: 400px;
        /*height: 450px;*/
        position: absolute;
        top: 50%;
        left: 50%;
        margin: -125px 0 0 -125px;
        background: white;
        border: 1px solid black;
        text-align: center;

        -webkit-touch-callout: none; /* iOS Safari */
        -webkit-user-select: none;   /* Chrome/Safari/Opera */
        -khtml-user-select: none;    /* Konqueror */
        -moz-user-select: none;      /* Firefox */
        -ms-user-select: none;       /* Internet Explorer/Edge */
        user-select: none;           /* Non-prefixed version, currently
                                  not supported by any browser */

    }

    #authForm {
        text-align: start;
    }

    #authForm label, #authForm input, #authForm button {
        margin-left: 15px;
        margin-right: 15px;
    }

    .auth > span {
        font-size: 20px;
    }

    .register {
        padding-bottom: 30px;
        z-index: 2;
        width: 400px;
        /*height: 450px;*/
        position: absolute;
        top: 50%;
        left: 50%;
        margin: -125px 0 0 -125px;
        background: white;
        border: 1px solid black;
        text-align: center;

        -webkit-touch-callout: none; /* iOS Safari */
        -webkit-user-select: none;   /* Chrome/Safari/Opera */
        -khtml-user-select: none;    /* Konqueror */
        -moz-user-select: none;      /* Firefox */
        -ms-user-select: none;       /* Internet Explorer/Edge */
        user-select: none;           /* Non-prefixed version, currently
                                  not supported by any browser */

    }

    .error {
        border: 1px solid red;
    }

    #registerForm {
        text-align: start;
    }

    #registerForm label, #registerForm input, #registerForm button, .invalid-feedback {
        margin-left: 15px;
        margin-right: 15px;
    }

    .register > span {
        font-size: 20px;
    }

    hr {
        background-color: black;
    }

    .login-field {
        margin-top: 15px;
    }

    .password-field {
        margin-top: 15px;
    }

    .rpassword-field {
        margin-top: 15px;
    }
    .btnRegister {
        margin-top: 30px
    }

    .btnAuth {
        margin-top: 30px
    }

    .cancelRegister, .cancelAuth {
        margin-top: 15px;
    }

    .main-bg {
        background: gray;
        opacity: 0.5;
        z-index: 1;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    .header-wrapper {
        background-color: #aaaaaa;
        min-height: 50px;
        border: 1px solid gray;
    }
    div>button {
        margin-right: 7px;
    }

    #logOutBtn {
        margin-left: 5px;
        margin-right: 5px;
        width: 25px;
        height: 25px;
        cursor: pointer;
    }
    .home-img {
        margin-left: 5px;
        margin-right: 5px;
        width: 25px;
        height: 25px;
    }
    .home-link {
        cursor: pointer;
    }
</style>
		

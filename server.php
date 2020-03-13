<?php
session_start();

require_once "request.class.php";

$requestClass = new Request();

require_once 'db.php';
$db = new Db();
$db->table_name="books";

$users = new Db();
$users->table_name="users";

if( $requestClass->isPost() )
{
    if ($requestClass->getField('action') === 'auth') {
        $requestClass->required('login');
        $requestClass->required('password');
        $requestClass->isUserExists('login', 'password');



        if (count($requestClass->getErrors()) == 0){
            echo json_encode(['state' => 'success',
                'direction' => $_SERVER['HTTP_REFERER']]);
        }
        else {
            echo json_encode($requestClass->getErrors());
        }
    }

    if ($requestClass->getField('action') === 'register') {
        $requestClass->required('email');
        $requestClass->isEmail('email');

        $requestClass->required('login');
        $requestClass->isBadSymbolsExist('login');
        $requestClass->min('login',4);
        $requestClass->max('login', 20);
        $requestClass->isUniqueLogin('login');


        $requestClass->required('password');
        $requestClass->min('password',6);
        $requestClass->max('password', 30);

        $requestClass->isMatchPass('password','rpassword');


        if (count($requestClass->getErrors()) == 0){
            $users->insert([
                'login' => $requestClass->getField('login'),
                'email' => $requestClass->getField('email'),
                'password' => password_hash($requestClass->getField('password'),PASSWORD_DEFAULT),
                'email_confirm' => 0
            ]);
            echo json_encode(['state' => 'success',
                'direction' => $_SERVER['HTTP_REFERER']]);

        }
        else {
            echo json_encode($requestClass->getErrors());
        }
    }

    if ($requestClass->getField('action') === 'logout') {
        unset($_SESSION['user']);
        echo json_encode(['state' => 'success',
            'direction' => $_SERVER['HTTP_REFERER']]);
    }

    if ($requestClass->getField('action') === 'add') {

        $requestClass->required('name');
        $requestClass->required('description');
        $requestClass->required('author');
        $requestClass->required('text');

        if (count($requestClass->getErrors()) == 0){
            $db->insert([
                'name' => $requestClass->getField('name'),
                'description' => $requestClass->getField('description'),
                'text' => $requestClass->getField('text'),
                'author' => $requestClass->getField('author'),
                'posted_by' => $_SESSION['user']['login']
            ]);
            echo json_encode(['state' => 'success']);
        }
        else {
            echo json_encode($requestClass->getErrors());
        }
    } else if ($requestClass->getField('action') === 'edit') {

        $requestClass->required('name');
        $requestClass->required('description');
        $requestClass->required('author');
        $requestClass->required('text');

        if (count($requestClass->getErrors()) == 0){
            $db->update([
                'id' => $requestClass->getField('id'),
                'name' => $requestClass->getField('name'),
                'description' => $requestClass->getField('description'),
                'text' => $requestClass->getField('text'),
                'author' => $requestClass->getField('author')
            ]);
            echo json_encode(['state' => 'success']);
        }
        else {
            echo json_encode($requestClass->getErrors());
        }
    } else if ($requestClass->getField('action') === 'delete') {
        $db->delete([
            'id' => $requestClass->getField('id')
        ]);
        echo json_encode(['state' => 'success']);
    }

}

?>
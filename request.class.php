<?php
require_once 'db.php';
session_start();

class Request
{
    private $errors = [];

    private $users;

    function __construct()
    {
        $this->users = new Db();
        $this->users->table_name="users";
    }

    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    public function clear($str)
    {
        return strip_tags( trim($str) );
    }

    public function getField($inputName)
    {
        $value = isset($_POST[$inputName]) ? $_POST[$inputName] : '';

        return $this->clear($value);
    }

    public function required($inputName)
    {
        $value = $this->getField($inputName);
        if(empty($value))
        {
            $this->errors[$inputName][] = 'Поле обязательно к заполнению';
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * проверяет длину строки из поля на минимальное значения
     * @param $inputName
     * @param $min
     */
    public function min($inputName, $min)
    {
        $value = $this->getField($inputName);
        if(((mb_strlen($value)) < $min) and mb_strlen($value) > 0 )
        {
            $this->errors[$inputName][] = 'Минимальное количество символов '.$min ;
        }
    }


    /**
     * проверяет длину строки из поля на максимальное значения
     * @param $inputName
     * @param $max
     */
    public function max($inputName, $max)
    {
        $value = $this->getField($inputName);
        if(((mb_strlen($value)) > $max) and mb_strlen($value) > 0)
        {
            $this->errors[$inputName][] = 'Максимальное количество символов '.$max ;
        }
    }

    /**
     * проверка значения на максимальность
     * метод проверяет является ли введенное значение email
     * @param $inputName - имя поля
     */
    public function isEmail($inputName)
    {
        $value = $this->getField($inputName);
        if (!filter_var($value, FILTER_VALIDATE_EMAIL) and (mb_strlen($value) > 0)) {
            $this->errors[$inputName][] = 'Введённое значение не является email-ом' ;
        }
    }

    /**
     * проверка значения на максимальность
     * @param $inputName
     * @param $maxValue
     */
    public function maxValue($inputName, $maxValue)
    {
        $value = $this->getField($inputName);
        if(((int)$value) > $maxValue )
        {
            $this->errors[$inputName][] = 'Максимальное значение поля '.$maxValue ;
        }
    }

    /**
     * проверка значения на минимальность
     * @param $inputName
     * @param $minValue
     */
    public function minValue($inputName, $minValue)
    {
        $value = $this->getField($inputName);
        if(((int)$value) < $minValue )
        {
            $this->errors[$inputName][] = 'Минимальное значение поля '.$minValue ;
        }
    }


    public function isBadSymbolsExist($inputName) {
        $f = fopen('server.log','a+');
        $badSymbols = '!"#$%&\'()\*+,-./:;<=>?@[\]^`{|}~';
        $value = $this->getField($inputName);
        $badSymbolsDetected = '';
        for ( $i=0; $i < strlen($badSymbols); $i++ )
        {
            $badSymbolsDetected .= (mb_strpos($value, $badSymbols[$i])) !== false ? $badSymbols[$i] : '';
        }

        if (strlen($badSymbolsDetected) > 0)
        {
            $this->errors[$inputName][] = 'Используются запрещенные символы - "'.$badSymbolsDetected.'"';
        }
    }

    public function isMatchPass($inputName1,$inputName2) {
        $value1 = $this->getField($inputName1);
        $value2 = $this->getField($inputName2);
        if (($value1 !== $value2) and (mb_strlen($value1) > 0) and (count($this->errors[$inputName1]) == 0)) {
            $this->errors[$inputName2][] = 'Пароли не совпадают';
        }
    }

    public function isUniqueLogin($inputName) {
        if (count($this->errors[$inputName]) == 0) {
            $user = $this->users->get_one(['login' => $this->getField($inputName)]);
            if ($user['login'] == $this->getField($inputName))
            $this->errors[$inputName][] = 'Пользователь с таким логином уже зарегистрирован';
        }
    }

    public function isUserExists($loginField, $passwordField) {
        if (count($this->errors[$loginField]) == 0) {
            $user = $this->users->get_one(['login' => $this->getField($loginField)]);
            if ($user['login'] != $this->getField($loginField))
                $this->errors[$loginField][] = 'Пользователь с таким логином не найден';
            else {
                if (!password_verify($this->getField($passwordField),$user['password']) and (count($this->errors[$passwordField]) == 0)) {
                    $this->errors[$passwordField][] = 'Неверный пароль';
                }
                else {
                    $_SESSION['user'] = $user;
                }
            }
        }
    }
}
?>
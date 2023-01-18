<?php

namespace models;

use core\Model;
use core\Utils;

class Users extends Model
{
    public function Validate($formRow)
    {
        $errors = [];
        $user = $this->GetUserByLogin($formRow['login']);
        if (!empty($user)) {
            $errors[] = "Користувач із вказканим логіном вже зареєстрований";
        }

        if (empty($formRow['login'])) {
            $errors[] = "Поле 'Логін' не може бути порожнім ";
        }
        if (empty($formRow['password'])) {
            $errors[] = "Поле 'Пароль' не може бути порожнім ";
        }
        if ($formRow['password'] != $formRow['password2']) {
            $errors[] = "Паролі не співпадають";
        }
        if (empty($formRow['firstname'])) {
            $errors[] = "Поле 'Ім'я' не може бути порожнім ";
        }
        if (empty($formRow['lastname'])) {
            $errors[] = "Поле 'Прізвище' не може бути порожнім ";
        }
        if (count($errors) > 0) return $errors;
        else return true;
    }


    public function isUserAuthenticated()
    {
        return isset($_SESSION['user']);
    }

    public function getCurrentUser()
    {
        if ($this->isUserAuthenticated()) {
            return $_SESSION['user'];
        } else return null;
    }

    public function AddUser($userRow)
    {
        $validateRes = $this->Validate($userRow);
        if (is_array($validateRes)){
            return [
                'error'=>true,
                'messages'=>$validateRes
            ];
        }
        $fields = ['login', 'password', 'firstname', 'lastname','photo'];
        $userRowFiltered = Utils::ArrayFilter($userRow, $fields);
        $userRowFiltered['password'] = md5($userRowFiltered['password']);
        $userRowFiltered['photo'] = "...";
        $id = \core\Core::getInstance()->getDB()->insert('users', $userRowFiltered);
        return [
            'error'=>false,
            'id'=>$id
        ];
    }

    public function AuthUser($login, $password)
    {
        $password = md5($password);
        $users = \core\Core::getInstance()->getDB()->select('users', "*", ['login' => $login, 'password' => $password]);
        if (count($users) > 0) {
            return $users[0];
        } else return false;
    }

    public function GetUserByLogin($login)
    {
        $rows = \core\Core::getInstance()->getDB()->select('users', '*', ['login' => $login]);
        if (count($rows) > 0) {
            return $rows[0];
        } else return null;
    }

    public function GetUserById($id)
    {
        $rows = \core\Core::getInstance()->getDB()->select('users', '*', ['id' => $id]);
        if (count($rows) > 0) {
            return $rows[0];
        } else return null;
    }

    public function GetAllUsers()
    {
        if ($this->getCurrentUser()['access'] == 1)
            $users = \core\Core::getInstance()->getDB()->select('users', '*', "login != 'admin' AND access != '1'");
        else $users = \core\Core::getInstance()->getDB()->select('users', '*', null);
        if (count($users) > 0) {
            return $users;
        } else return null;
    }

    public function DeleteUser($login)
    {
        $user = $this->GetUserByLogin($login);
        if (empty($user))
            return false;
        \core\Core::getInstance()->getDB()->delete('users', ['id' => $user['id']]);
        return true;
    }



    public function ChangeAccess($login, $option)
    {
        $user = $this->GetUserByLogin($login);
        if (empty($user))
            return false;
        if ($user['access'] == $option) {
            return false;
        }
        if ($option == 1)
            $access = 1;
        else $access = 0;
        \core\Core::getInstance()->getDB()->update('users', ['access' => $access], ['login' => $login]);
        return true;
    }

    public function checkOnAdmin()
    {
        if (!empty($this->getCurrentUser())&&($this->getCurrentUser()['access'] == 1 || $this->getCurrentUser()['access'] == 2)) {
            return true;
        }
        return false;
    }
}
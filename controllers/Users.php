<?php

namespace controllers;

use core\Controller;

class Users extends Controller
{
    protected $usersModel;
    protected $myProfileModel;

    function __construct()
    {
        $this->usersModel = new \models\Users();
        $this->myProfileModel = new \models\MyProfile();
    }

    public function actionLogout()
    {
        unset($_SESSION['user']);
        $title = 'Вихід із аккаунту';
        return $this->renderMessage('ok', "Ви вийшли з Вашого аккаунту", null, [
            'PageTitle' => $title,
            'MainTitle' => $title
        ]);
    }

    public function actionShowUsers()
    {
        $titleForbidden = "Доступ заборонено";
        if (!isset($_SESSION['user']) || !$this->usersModel->checkOnAdmin()) {
            return $this->render('forbidden', null, [
                'PageTitle' => $titleForbidden,
                'MainTitle' => $titleForbidden
            ]);
        }
        $title = 'Користувачі сайту';
        $users = $this->usersModel->GetAllUsers();
        if (empty($users)) {
            $users = [];
        }
        return $this->render('showusers', ['users' => $users], [
            'PageTitle' => $title,
            'MainTitle' => $title
        ]);
    }

    public function actionLogin()
    {
        $_POST = array_map('trim', $_POST);
        $title = 'Вхід в аккаунт';
        if (isset($_SESSION['user'])) {
            return $this->renderMessage('ok', "Ви вже увійшли на сайт", null, [
                'PageTitle' => $title,
                'MainTitle' => $title
            ]);
        }
        if ($this->isPost()) {
            $user = $this->usersModel->AuthUser($_POST['login'], $_POST['password']);
            if (!empty($user)) {
                $_SESSION['user'] = $user;
                return $this->renderMessage('ok', "Ви успішно увійшли на сайт", null, [
                    'PageTitle' => $title,
                    'MainTitle' => $title
                ]);
            } else {
                return $this->render('login', null, [
                    'PageTitle' => $title,
                    'MainTitle' => $title,
                    'MessageText' => 'Неправильний логін або пароль',
                    'MessageClass' => 'danger'
                ]);
            }
        } else {
            $params = [
                'PageTitle' => $title,
                'MainTitle' => $title
            ];
            return $this->render('login', null, $params);
        }
    }

    public function actionRegister()
    {
        $_POST = array_map('trim', $_POST);
        if ($this->isPost()) {
            $result = $this->usersModel->AddUser($_POST);
            if ($result['error'] === false) {
                $allowed_types = ['image/png', 'image/jpeg'];
                if (is_file($_FILES['file_ava']['tmp_name']) && in_array($_FILES['file_ava']['type'], $allowed_types)) {
                    switch ($_FILES['file_ava']['type']) {
                        case 'image/png':
                            $extension = 'png';
                            break;
                        default:
                            $extension = 'jpg';
                    }
                    $name = $result['id'] . '_' . uniqid() . '.' . $extension;
                    move_uploaded_file($_FILES['file_ava']['tmp_name'], 'files/users/' . $name);
                    $user = $this->usersModel->GetUserById($result['id']);
                    $this->myProfileModel->ChangePhoto($user, $name);
                }

                return $this->renderMessage('ok', "Користувач успішно зареєстрований", null, [
                    'PageTitle' => 'Реєстрація на сайті',
                    'MainTitle' => 'Реєстрація на сайті'
                ]);
            }else {
                    $message = implode('<br>', $result['messages']);
                    return $this->render('register', null, [
                        'PageTitle' => 'Реєстрація на сайті',
                        'MainTitle' => 'Реєстрація на сайті',
                        'MessageText' => $message,
                        'MessageClass' => 'danger'
                    ]);
                }
            } else {
                $params = [
                    'PageTitle' => 'Реєстрація на сайті',
                    'MainTitle' => 'Реєстрація на сайті'
                ];
                return $this->render('register', null, $params);
            }
        }



    public function actionDelete()
    {
        $title = "Видалення користувача";
        $login = $_GET['login'];
        if ((isset($_GET['confirm']) && $_GET['confirm'] == 'yes') && $this->usersModel->checkOnAdmin()) {
            if ($this->usersModel->DeleteUser($login)) {
                header("Location: /users/showusers");
            } else {
                return $this->renderMessage('danger', "Помилка видалення користувача", null, [
                    'PageTitle' => $title,
                    'MainTitle' => $title
                ]);
            }
        }
    }

    public function actionChangeAccess()
    {
        $title = "Зміна доступу користувача";
        $login = $_GET['login'];
        if (isset($_GET['option']) && $this->usersModel->checkOnAdmin()) {
            $option = $_GET['option'];
            if ($this->usersModel->ChangeAccess($login, $option)) {
                header("Location: /users/showusers");
            } else {
                return $this->renderMessage('danger', "Помилка зміни доступу користувача", null, [
                    'PageTitle' => $title,
                    'MainTitle' => $title
                ]);
            }
        }
    }
}
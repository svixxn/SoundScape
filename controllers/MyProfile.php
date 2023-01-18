<?php

namespace controllers;

use core\Controller;

class MyProfile extends Controller
{
    protected $user;
    protected $goodsModel;
    protected $userModel;
    protected $myprofileModel;

    public function __construct()
    {
        $this->userModel = new \models\Users();
        $this->goodsModel = new \models\Goods();
        $this->user = $this->userModel->getCurrentUser();
        $this->myprofileModel = new \models\MyProfile();
    }

    /**
     * Відображення початкової сторінки модуля
     */
    public function actionIndex()
    {
        if(!empty($this->user)){
            $title = $this->user['login'];
        return $this->render('index', ['user' => $this->user], [
            'PageTitle' => $title,
            'MainTitle' => $title
        ]);
        }
        $title = "Профіль";
        return $this->renderMessage('error', "Доступ заборонено. Авторизуйтеся або створіть аккаунт для перегляду свого профіля.", null, [
            'PageTitle' => $title,
            'MainTitle' => $title
        ]);
    }

    public function actionEdit()
    {
        $title = 'Редагування користувача';
        $id = $this->user['id'];
        if ($this->isPost()) {
            $result = $this->myprofileModel->UpdateUser($_POST, $this->user);
            if ($result === true) {
                $allowed_types = ['image/png', 'image/jpeg'];
                if (is_file($_FILES['file_ava']['tmp_name']) && in_array($_FILES['file_ava']['type'], $allowed_types)) {
                    switch ($_FILES['file_ava']['type']) {
                        case 'image/png':
                            $extension = 'png';
                            break;
                        default:
                            $extension = 'jpg';
                    }
                    $name = $id . '_' . uniqid() . '.' . $extension;
                    move_uploaded_file($_FILES['file_ava']['tmp_name'], 'files/users/' . $name);
                    $this->myprofileModel->ChangePhoto($id, $name);
                }

                return $this->renderMessage('ok', "Користувача успішно відредаговано.Перезайдіть в аккаунт щоб зберегти зміни.", null, [
                    'PageTitle' => $title,
                    'MainTitle' => $title
                ]);
            } else {
                $message = implode('<br>', $result);
                return $this->render('edit', ['model' => $this->user], [
                    'PageTitle' => $title,
                    'MainTitle' => $title,
                    'MessageText' => $message,
                    'MessageClass' => 'danger'
                ]);
            }
        } else {
            $params = [
                'PageTitle' => $title,
                'MainTitle' => $title
            ];
            return $this->render('edit', ['model' => $this->user], $params);
        }
    }

    public function actionDelete(){
        $title = "Видалення користувача";
        $login = $this->user['login'];
                if ($this->userModel->DeleteUser($login)) {
                    header("Location: /users/logout");
                } else {
                    return $this->renderMessage('error', "Помилка видалення користувача", null, [
                        'PageTitle' => $title,
                        'MainTitle' => $title
                    ]);
                }
    }


    public function actionCart()
    {
        $title = "Корзина";
        if(!empty($this->user)){
        $cart = $this->myprofileModel->GetCart($this->user['id']);
        $Goods = $this->myprofileModel->GetGoodsByCart($cart);
        $cartQuan = $this->myprofileModel->GetCartQuantity($this->user['id']);
        if(!empty($Goods))
        return $this->render('cart',['Goods'=>$Goods, 'cartGoods'=>$cart,'cartQuan'=>$cartQuan], [
            'PageTitle' => $title,
            'MainTitle' => $title
        ]);

         else return $this->renderMessage('info', "Ваша корзина пустує. Швидше перейдіть до каталогу товарів та додайте щось у корзину!", null, [
            'PageTitle' => $title,
            'MainTitle' => $title
        ]);
        }
        return $this->renderMessage('error', "Доступ заборонено. Авторизуйтеся або створіть аккаунт для перегляду своєї корзини.", null, [
            'PageTitle' => $title,
            'MainTitle' => $title
        ]);
    }

    public function actionAddToCart()
    {
        $id_goods = $_GET['id'];
        $title = "Додавання товару до корзини";
        if (empty($this->user))
            return $this->renderMessage('info', "Додавати товари до корзини можуть тільки авторизовані користувачі. Авторизуйтеся або створіть новий аккаунт.", null, [
                'PageTitle' => $title,
                'MainTitle' => $title
            ]);
        $result = $this->myprofileModel->AddToCart($this->user['id'], $id_goods);
        if($result===true)
        return $this->renderMessage('ok', "Товар успішно додано у корзину", null, [
            'PageTitle' => $title,
            'MainTitle' => $title
        ]);
        else return $this->renderMessage('info', "У корзину вже було додано даний товар.", null, [
            'PageTitle' => $title,
            'MainTitle' => $title
        ]);

    }

    public function actionDeleteFromCart()
    {
        $title = "Видалення товару";
        $id = $_GET['id'];
        if ((isset($_GET['confirm']) && $_GET['confirm'] == 'yes')) {
            if ($this->myprofileModel->DeleteFromCart($id,$this->user['id'])) {
                header("Location: /myprofile/cart");
            } else {
                return $this->renderMessage('danger', "Помилка видалення товару", null, [
                    'PageTitle' => $title,
                    'MainTitle' => $title
                ]);
            }
        }
    }

    public function actionConfirm(){
        $title = "Оформлення замовлення";
        if(!empty($this->userModel->getCurrentUser()))
        return $this->render('confirm',null,  [
            'PageTitle' => $title,
            'MainTitle' => $title
        ]);
        return $this->renderMessage('error', "Доступ заборонено. Авторизуйтеся або створіть аккаунт для замовлення товарів.", null, [
            'PageTitle' => $title,
            'MainTitle' => $title
        ]);
    }

    public function actionResult(){
        if(!empty($this->userModel->getCurrentUser())){
            $title = "Замовлення оформлено!";
        return $this->renderMessage('ok', "Дякуємо за співпрацю! Ми зв'яжемось з вами найближчим часом для підтвердження замовлення.", null, [
            'PageTitle' => $title,
            'MainTitle' => $title
        ]);
        }
        $title = "Оформлення замовлення";
        return $this->renderMessage('error', "Доступ заборонено. Авторизуйтеся або створіть аккаунт для замовлення товарів.", null, [
            'PageTitle' => $title,
            'MainTitle' => $title
        ]);
    }
}
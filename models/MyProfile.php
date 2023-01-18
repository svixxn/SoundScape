<?php

namespace models;

use core\Utils;
use core\Model;
use Imagick;


class MyProfile extends Model
{
    public function UpdateUser($row,$user){
        if(is_array($this->Validate($row,$user))){
            return $this->Validate($row,$user);
        }
        $fields = ['login','password','firstname','lastname','photo'];
        $rowFiltered = Utils::ArrayFilter($row, $fields);
        $rowFiltered['password'] = md5($rowFiltered['password']);
        \core\Core::getInstance()->getDB()->update('users',$rowFiltered,['id'=>$user['id']]);
        return true;
    }

    public function Validate($formRow)
    {
        $errors = [];
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

    public function ChangePhoto($user, $file){
        $folder = 'files/users/';
        $file_path = pathinfo($folder.$file);
        $file_big = $file_path['filename'].'_b';
        $file_small =$file_path['filename'].'_s';
        if(is_file($folder.$user['photo'].'_b.jpg')&&is_file($folder.$file)){
            unlink($folder.$user['photo'].'_b.jpg');
        }

        if(is_file($folder.$user['photo'].'_s.jpg')&&is_file($folder.$file)){
            unlink($folder.$user['photo'].'_s.jpg');
        }
        $user['photo'] = $file_path['filename'];
        $im_b = new Imagick();
        $im_b->readImage($_SERVER['DOCUMENT_ROOT'].'/'.$folder.$file);
        $im_b->cropthumbnailImage(500,500,true);
        $im_b->writeImage($_SERVER['DOCUMENT_ROOT'].'/'.$folder.'/'.$file_big.'.jpg');

        $im_s = new Imagick();
        $im_s->readImage($_SERVER['DOCUMENT_ROOT'].'/'.$folder.$file);
        $im_s->cropthumbnailImage(30,30,true);
        $im_s->writeImage($_SERVER['DOCUMENT_ROOT'].'/'.$folder.'/'.$file_small.'.jpg');
        unlink($folder.$file);
        $fields = ['login','password','firstname','lastname','photo'];
        $rowFiltered = Utils::ArrayFilter($user, $fields);
        \core\Core::getInstance()->getDB()->update('users',$rowFiltered,['id'=>$user['id']]);
    }

    public function AddToCart($id_user,$id_goods){
        $cart_goods = \core\Core::getInstance()->getDB()->select('cart', "*", ['id_user' => $id_user, 'id_goods' => $id_goods]);
        if (count($cart_goods) > 0) {
            return false;
        } else{
            $date = date('Y-m-d H:i:s');
            \core\Core::getInstance()->getDB()->insert('cart',['id_goods'=>$id_goods,'id_user'=>$id_user,'quantity'=>1, 'datetime'=>$date]);
            return true;
        }
    }

    public function GetCart($user_id){
        return \core\Core::getInstance()->getDB()->select('cart', "*", ['id_user' => $user_id],['datetime'=>'DESC']);
    }
    public function GetCartQuantity($user_id){
        $cart = $this->GetCart($user_id);
        if(!empty($cart))
        return count($cart);
        else return 0;
    }
    public function GetGoodsByCart($goods_id){
        $goods = [];
        foreach ($goods_id as $value){
            $goods[] = \core\Core::getInstance()->getDB()->select('goods', "*",  ['id'=>$value['id_goods']]);
        }
        if(!empty($goods)) return $goods;
        else return false;
    }

    public function DeleteFromCart($id,$id_user){
        $goods =  \core\Core::getInstance()->getDB()->select('cart', "*", ['id_user' => $id_user, 'id_goods' => $id]);
        if(!empty($goods)){
            \core\Core::getInstance()->getDB()->delete('cart',['id_goods'=>$id, 'id_user'=>$id_user]);
            return true;
        }
        else return false;
    }
}
<?php

namespace models;

use core\Utils;
use Imagick;

class Goods extends \core\Model
{
    /**
     * @throws \ImagickException
     */
    public function ChangePhoto($id, $file){
        $folder = 'files/goods/';
        $file_path = pathinfo($folder.$file);
        $file_big = $file_path['filename'].'_b';
        $file_middle = $file_path['filename'].'_m';
        $file_small =$file_path['filename'].'_s';

        $goods = $this->GetGoodsById($id);
        if(is_file($folder.$goods['photo'].'_b.jpg')&&is_file($folder.$file)){
            unlink($folder.$goods['photo'].'_b.jpg');
        }
        if(is_file($folder.$goods['photo'].'_m.jpg')&&is_file($folder.$file)){
            unlink($folder.$goods['photo'].'_m.jpg');
        }
        if(is_file($folder.$goods['photo'].'_s.jpg')&&is_file($folder.$file)){
            unlink($folder.$goods['photo'].'_s.jpg');
        }
        $goods['photo'] = $file_path['filename'];
        $im_b = new Imagick();
        $im_b->readImage($_SERVER['DOCUMENT_ROOT'].'/'.$folder.$file);
        $im_b->cropthumbnailImage(1280,1024,true);
        $im_b->writeImage($_SERVER['DOCUMENT_ROOT'].'/'.$folder.'/'.$file_big.'.jpg');
        $im_m = new Imagick();
        $im_m->readImage($_SERVER['DOCUMENT_ROOT'].'/'.$folder.$file);
        $im_m->cropthumbnailImage(500,500,true);
        $im_m->writeImage($_SERVER['DOCUMENT_ROOT'].'/'.$folder.'/'.$file_middle.'.jpg');
        $im_s = new Imagick();
        $im_s->readImage($_SERVER['DOCUMENT_ROOT'].'/'.$folder.$file);
        $im_s->cropthumbnailImage(180,180,true);
        $im_s->writeImage($_SERVER['DOCUMENT_ROOT'].'/'.$folder.'/'.$file_small.'.jpg');
        unlink($folder.$file);
        $this->UpdateGoods($goods,$id);
    }
    public function AddGoods($row): array
    {
        $userModel = new \models\Users();
        $user = $userModel->getCurrentUser();
        if($user == null){
            return [
                'error' => true,
                'messages'=>['Користувач не аутентифікований']
            ];
        }
        $validateRes = $this->Validate($row);
        if(is_array($validateRes)){
            return [
                'error' => true,
                'messages'=>$validateRes
            ];
        }

        $fields = ['name','text', 'cost','category','photo'];
        $rowFiltered = Utils::ArrayFilter($row, $fields);
        $rowFiltered['datetime'] = date('Y-m-d H:i:s');
        $rowFiltered['photo'] = "...";
        $id = \core\Core::getInstance()->getDB()->insert('goods',$rowFiltered);
        return  [
            'error' => false,
            'id' => $id
        ];
    }
    public function GetGoods($count=null,$offset = null){
        return \core\Core::getInstance()->getDB()->select('goods', '*',null,['datetime'=>'DESC'],$count,$offset);
    }

    public function GetGoodsById($id){
        $goods = \core\Core::getInstance()->getDB()->select('goods', '*',['id'=>$id]);
        if(!empty($goods))
            return $goods[0];
        else return null;
    }

    public function GetGoodsByCategory($count=null,$category=null,$offset=null){
        $goods = \core\Core::getInstance()->getDB()->select('goods', '*',['category'=>$category],['datetime'=>'DESC'],$count,$offset);
        if(!empty($goods))
            return $goods;
        else return null;
    }

    public function GetGoodsByPrice($count=null,$startPrice=null,$endPrice=null,$offset=null){
        $goods = \core\Core::getInstance()->getDB()->select('goods', '*',"cost between $startPrice and $endPrice" ,['datetime'=>'DESC'],$count,$offset);
        if(!empty($goods))
            return $goods;
        else return null;
    }

    public function GetGoodsBySearch($count=null,$value=null,$offset=null){
        $goods = \core\Core::getInstance()->getDB()->select('goods', '*',"name like '%$value%'", ['datetime'=>'DESC'],$count,$offset);
        if(!empty($goods))
            return $goods;
        else return null;
    }
    public function Validate($row){
        $errors = [];
        if(empty($row['name'])){
            $errors[] = "Поле 'Назва товару' не може бути порожнім ";
        }
        if(empty($row['text'])){
            $errors[] = "Поле 'Опис товару' не може бути порожнім ";
        }
        if(empty($row['cost'])){
            $errors[] = "Поле 'Ціна товару' не може бути порожнім ";
        }
        if(empty($row['category'])){
            $errors[] = "Поле 'Категорія товару' не може бути порожнім ";
        }
        if(count($errors)>0) return $errors;
        else return true;
    }
    public function UpdateGoods($row,$id){
        $userModel = new \models\Users();
        $user = $userModel->getCurrentUser();
        if($user == null)
            return false;
        $validateRes = $this->Validate($row);
        if(is_array($validateRes))
            return $validateRes;
        $fields = ['name','text', 'cost','category','photo'];
        $rowFiltered = Utils::ArrayFilter($row, $fields);
        \core\Core::getInstance()->getDB()->update('goods',$rowFiltered,['id'=>$id]);
        return true;
    }
    public function DeleteGoods($id){
        $goods = $this->GetGoodsById($id);
        $userModel = new \models\Users();
        $user = $userModel->getCurrentUser();
        if(empty($goods)||!$userModel->checkOnAdmin())
            return false;
        \core\Core::getInstance()->getDB()->delete('goods',['id'=>$id]);
        return true;
    }
}
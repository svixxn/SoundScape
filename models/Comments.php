<?php

namespace models;

use core\Model;

class Comments extends Model
{
    public function AddComment($row,$user,$goodsId){
        $validateRes = $this->Validate($row);
        if($validateRes!==true){
            return [
                'error' => true,
                'message'=>$validateRes
            ];
        }
        $date = date('Y-m-d H:i:s');
        $rowFiltered = $row;
        $rowFiltered['datetime'] = $date;
        $rowFiltered['id_user'] = $user['id'];
        $rowFiltered['id_goods'] = $goodsId;
        \core\Core::getInstance()->getDB()->insert('comments',$rowFiltered);
        return ['error'=>false];

    }

    public function GetComments($goodsId){
        $comments = \core\Core::getInstance()->getDB()->select('comments', '*',['id_goods'=>$goodsId]);
        if(!empty($comments))
            return $comments;
        else return null;
    }

    public function Validate($row){
        if(empty($row['text'])){
            $error = "Поле 'Текст коментаря' не може бути порожнім ";
        }
        if(!empty($error)) return $error;
        else return true;
    }
    public function GetCommentById($id){
        $comment = \core\Core::getInstance()->getDB()->select('comments', '*',['id'=>$id]);
        if(!empty($comment))
            return $comment[0];
        else return null;
    }

    public function GetAllComments(){
        return \core\Core::getInstance()->getDB()->select('comments', '*',null);
    }


    public function Delete($id){
        $comment = $this->GetCommentById($id);
        if (empty($comment))
            return false;
        \core\Core::getInstance()->getDB()->delete('comments', ['id' => $id]);
        return true;
    }
}
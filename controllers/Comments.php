<?php

namespace controllers;

use core\Controller;

class Comments extends Controller
{
    protected $user;
    protected $goodsModel;
    protected $userModel;
    protected $commentsModel;

    public function __construct()
    {
        $this->userModel = new \models\Users();
        $this->goodsModel = new \models\Goods();
        $this->commentsModel = new \models\Comments();
        $this->user = $this->userModel->getCurrentUser();
    }

    public function actionAdd()
    {
        $idGoods = $_GET['goodsId'];
        $title = 'Додавання коментаря';
        if (empty($this->user))
            return $this->renderMessage('error', "Додавати коментарі можуть тільки авторизовані користувачі", null, [
                'PageTitle' => $title,
                'MainTitle' => $title
            ]);
        if ($this->isPost()) {
            $result = $this->commentsModel->AddComment($_POST, $this->user, $idGoods);
            if ($result['error'] === false) {
                header("Location: /goods/view?id=$idGoods");
            } else {
                return $this->renderMessage('error', $result['message'], null, [
                    'PageTitle' => $title,
                    'MainTitle' => $title
                ]);
            }
        }
    }

    public function actionDelete()
    {
        $title = "Видалення коментаря";
        $id = $_GET['id'];
        $idGoods = $_GET['goodsId'];
        $comment = $this->commentsModel->GetCommentById($id);
        if ($comment['id_user'] !== $this->user['id']&&!$this->userModel->checkOnAdmin()) {
            return $this->renderMessage('error', "Помилка видалення коментаря", null, [
                'PageTitle' => $title,
                'MainTitle' => $title
            ]);
        } else {
            if ($this->commentsModel->Delete($id))
                header("Location: /goods/view?id=$idGoods");
            else {
                return $this->renderMessage('error', "Помилка видалення коментаря", null, [
                    'PageTitle' => $title,
                    'MainTitle' => $title
                ]);
            }
        }
    }
}
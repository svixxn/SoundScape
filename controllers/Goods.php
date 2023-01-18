<?php

namespace controllers;

use core\Controller;

class Goods extends Controller
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

    /**
     * Відображення початкової сторінки модуля
     */
    public function actionIndex()
    {
        global $Config;
        $title = "Товари";
        $page = $_GET['page'] ?? null;
        if ($page !== null)
            $offset = ($page - 1) * $Config['GoodsCount'];
        else $offset = null;
        $goodsCount = count($this->goodsModel->GetGoods());
        $lastGoods = $this->goodsModel->GetGoods($Config['GoodsCount'], $offset);
        $pages = $goodsCount / $Config['GoodsCount'];
        if(is_float($pages)) $pages = ceil($pages);
        $activeClassNext = ($page+1<=$pages)? null:"disabled";
        $activeClassPrev = ($page-1<=0)? "disabled":null;
        if (!empty($lastGoods))
            return $this->render('index', ['lastGoods' => $lastGoods,'pages'=>$pages, 'activeClassNext'=>$activeClassNext, 'activeClassPrev'=>$activeClassPrev], [
                'PageTitle' => $title,
                'MainTitle' => $title
            ]);
        else return $this->renderMessage('error', "Товарів не знайдено.", null, [
            'PageTitle' => $title,
            'MainTitle' => $title
        ]);
    }

    /**
     * Перегляд товару
     */
    public function actionView()
    {
        $id = $_GET['id'];
        $goods = $this->goodsModel->GetGoodsById($id);
        $title = $goods['name'];
        $comments = $this->commentsModel->GetComments($id);
        if(empty($comments))
            return $this->render('view', ['model' => $goods,'comments'=>$comments], [
                'PageTitle' => $title,
                'MainTitle' => $title
            ]);
        return $this->render('view', ['model' => $goods,'comments'=>$comments], [
            'PageTitle' => $title,
            'MainTitle' => $title
        ]);
    }


    /**
     * Сортування товарів
     */
    public function actionSort()
    {
        global $Config;

        if (isset($_GET['category'])) {
            $category = $_GET['category'];
            switch ($category) {
                case 'keys':
                    $title = "Клавішні";
                    break;
                case 'winds':
                    $title = "Духові";
                    break;
                case 'percussion':
                    $title = "Ударні";
                    break;
                case 'strings':
                    $title = "Струнні";
                    break;
                default:
                    $title = "Не знайдено категорії";
                    break;
            }
            $page = $_GET['page'] ?? null;
            if ($page !== null)
                $offset = ($page - 1) * $Config['GoodsCount'];
            else $offset = null;
            $goodsCount = count($this->goodsModel->GetGoodsByCategory(null,$category));
            $goods = $this->goodsModel->GetGoodsByCategory($Config['GoodsCount'],$category,$offset);
            $pages = $goodsCount / $Config['GoodsCount'];
            if(is_float($pages)) $pages = ceil($pages);
            $activeClassNext = ($page+1<=$pages)? null:"disabled";
            $activeClassPrev = ($page-1<=0)? "disabled":null;
        }

        if (isset($_GET['startPrice']) && isset($_GET['endPrice'])) {
            $startPrice = $_GET['startPrice'];
            $endPrice = $_GET['endPrice'];
            $title = "Товари від $startPrice до $endPrice гривень";
            $page = $_GET['page'] ?? null;
            if ($page !== null)
                $offset = ($page - 1) * $Config['GoodsCount'];
            else $offset = null;
            $goodsCount = is_array($this->goodsModel->GetGoodsByPrice(null, $startPrice, $endPrice))? count($this->goodsModel->GetGoodsByPrice(null, $startPrice, $endPrice)):null;
            $goods = $this->goodsModel->GetGoodsByPrice($Config['GoodsCount'], $startPrice, $endPrice,$offset);
            $pages = $goodsCount / $Config['GoodsCount'];
            if(is_float($pages)) $pages = ceil($pages);
            $activeClassNext = ($page+1<=$pages)? null:"disabled";
            $activeClassPrev = ($page-1<=0)? "disabled":null;
        }

        if(isset($_GET['search'])){
            $value = $_GET['search'];
            $title = "Товари за запитом '$value'";
            if(empty($value)) $goods = null;
            else $goods = $this->goodsModel->GetGoodsBySearch(null,$value);
            $pages = 1;
            $activeClassNext = "disabled";
            $activeClassPrev = "disabled";
        }

        if (!empty($goods))
            return $this->render('index', ['lastGoods' => $goods,'pages'=>$pages, 'activeClassNext'=>$activeClassNext, 'activeClassPrev'=>$activeClassPrev], [
                'PageTitle' => $title,
                'MainTitle' => $title
            ]);
        return $this->renderMessage('error', "Товарів не знайдено.", null, [
            'PageTitle' => $title,
            'MainTitle' => $title
        ]);
    }

    /**
     * Додавання товару
     */
    public function actionAdd()
    {
        $titleForbidden = 'Доступ заборонено';
        if (empty($this->user) && !$this->userModel->checkOnAdmin())
            return $this->render('forbidden', null, [
                'PageTitle' => $titleForbidden,
                'MainTitle' => $titleForbidden
            ]);
        $title = 'Додавання товару';

        if ($this->isPost()) {
            $result = $this->goodsModel->AddGoods($_POST);
            if ($result['error'] === false) {
                $allowed_types = ['image/png', 'image/jpeg'];
                if (is_file($_FILES['file']['tmp_name']) && in_array($_FILES['file']['type'], $allowed_types)) {
                    switch ($_FILES['file']['type']) {
                        case 'image/png':
                            $extension = 'png';
                            break;
                        default:
                            $extension = 'jpg';
                    }
                    $name = $result['id'] . '_' . uniqid() . '.' . $extension;
                    move_uploaded_file($_FILES['file']['tmp_name'], 'files/goods/' . $name);
                    $this->goodsModel->ChangePhoto($result['id'], $name);
                }

                return $this->renderMessage('ok', "Товар успішно додано", null, [
                    'PageTitle' => $title,
                    'MainTitle' => $title
                ]);
            } else {
                $message = implode('<br>', $result['messages']);
                return $this->render('form', ['model' => $_POST], [
                    'PageTitle' => $title,
                    'MainTitle' => $title,
                    'MessageText' => $message,
                    'MessageClass' => 'danger'
                ]);
            }
        } else {
            return $this->render('form', ['model' => $_POST], [
                'PageTitle' => $title,
                'MainTitle' => $title
            ]);
        }

    }

    /**
     * Редагування товару
     */
    public function actionEdit()
    {
        $id = $_GET['id'];
        $goods = $this->goodsModel->GetGoodsById($id);
        $titleForbidden = 'Доступ заборонено';
        if (!$this->userModel->checkOnAdmin())
            return $this->render('forbidden', null, [
                'PageTitle' => $titleForbidden,
                'MainTitle' => $titleForbidden
            ]);
        $title = 'Редагування товару';
        if ($this->isPost()) {
            $result = $this->goodsModel->UpdateGoods($_POST, $id);
            if ($result === true) {
                $allowed_types = ['image/png', 'image/jpeg'];
                if (is_file($_FILES['file']['tmp_name']) && in_array($_FILES['file']['type'], $allowed_types)) {
                    switch ($_FILES['file']['type']) {
                        case 'image/png':
                            $extension = 'png';
                            break;
                        default:
                            $extension = 'jpg';
                    }
                    $name = $id . '_' . uniqid() . '.' . $extension;
                    move_uploaded_file($_FILES['file']['tmp_name'], 'files/goods/' . $name);
                    $this->goodsModel->ChangePhoto($id, $name);
                }
                return $this->renderMessage('ok', "Товар успішно збережено", null, [
                    'PageTitle' => $title,
                    'MainTitle' => $title
                ]);
            } else {
                $message = implode('<br>', $result);
                return $this->render('form', ['model' => $goods], [
                    'PageTitle' => $title,
                    'MainTitle' => $title,
                    'MessageText' => $message,
                    'MessageClass' => 'danger'
                ]);
            }
        } else {
            return $this->render('form', ['model' => $goods], [
                'PageTitle' => $title,
                'MainTitle' => $title
            ]);
        }
    }

    /**
     * Видалення товару
     */
    public function actionDelete()
    {
        $title = "Видалення новини";
        $id = $_GET['id'];
        if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
            if ($this->goodsModel->DeleteGoods($id)) {
                header("Location: /goods/");
            } else {
                return $this->renderMessage('danger', "Помилка видалення новини", null, [
                    'PageTitle' => $title,
                    'MainTitle' => $title
                ]);
            }
        }
        $goods = $this->goodsModel->GetGoodsById($id);
        return $this->render('delete', ['model' => $goods], [
            'PageTitle' => $title,
            'MainTitle' => $title
        ]);
    }
}
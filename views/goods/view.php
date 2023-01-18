<?php
$userModel = new \models\Users();
$user = $userModel->getCurrentUser();
function getUserById($id)
{
    $userModel = new \models\Users();
    return $userModel->GetUserById($id);
}

?>

<div class="container">
    <section id="main-sec" class="mb-4">
        <div class="row">
            <div class="col-md-6">
                <?php if (isset($model['photo']) && is_file('files/goods/' . $model['photo'] . '_m.jpg')) : ?>
                    <img src="/files/goods/<?= $model['photo'] ?>_m.jpg" alt=""
                         class="bd-placeholder-img rounded float-start m-3 img-fluid"/>
                <?php endif; ?>
            </div>
            <div class="col-md-6 border rounded p-4">
                <?php if ($userModel->checkOnAdmin()): ?>
                    <div class="button-group mb-3">
                        <a href="/goods/edit?id=<?= $model['id'] ?>" class="btn btn-primary">
                            <i class="fa-solid fa-pen-to-square"></i>
                            Редагувати
                        </a>
                        <button class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#delete<?= $model['id'] ?>">
                            <i class="fa-solid fa-trash"></i>
                            Видалити
                        </button>
                    </div>
                <?php endif; ?>

                <div class="modal fade" id="delete<?= $model['id'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content text-dark">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5">Видалення товару <?= $model['name'] ?></h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Ви дійсно хочете видалити товар <strong><?= $model['name'] ?></strong>?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
                                <a href="/goods/delete?id=<?= $model['id'] ?>&confirm=yes"
                                   class="btn btn-danger">Видалити</a>
                            </div>
                        </div>
                    </div>
                </div>

                <h2 class="display-2"><?= $model['cost'] ?> грн</h2>
                <a href="/myprofile/addtocart?id=<?= $model['id'] ?>" class="btn btn-success btn-lg mt-3"
                   style="width:20rem;">
                    <i class="fa-sharp fa-solid fa-cart-plus"></i>
                    Купити
                </a>
                <h3 class="mt-3">Доступна оплата карткою</h3>
                <p class="lead">Гарантія 12 місяців</p>
                <p>Приймаємо:</p>
                <img src="https://techzone.com.ua/themes/basic/images/mc.png" alt="" class="me-2">
                <img src="https://techzone.com.ua/themes/basic/images/visa.png" alt="" class="me-2">
                <img src="https://techzone.com.ua/themes/basic/images/googlepay.svg" alt="" class="me-2">
                <img src="https://techzone.com.ua/themes/basic/images/applepay.svg" alt="">
                <div class="d-flex justify-content-between mt-3">
                    <div class="d-flex flex-column align-items-center">
                        <i class="fa-solid fa-phone fa-2x"></i>
                        <p>+380 96 554 49 24</p>
                    </div>
                    <div class="d-flex flex-column align-items-center">
                        <i class="fa-solid fa-truck fa-2x"></i>
                        <p>Доставка по Житомиру - 30 грн<br>Доставка по Україні - 80 грн</p>
                    </div>
                    <div class="d-flex flex-column align-items-center">
                        <i class="fa-solid fa-wallet fa-2x"></i>
                        <p>Готівковий розрахунок<br>Безготівковий розрахунок</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section id="description" class="mb-4">
        <h3 class="my-2 display-4">Опис та характеристики товару</h3>
        <div class="row mt-3">
            <div class="col-md-12 border rounded p-4">
                <?= $model['text'] ?>
            </div>
        </div>
    </section>

    <section id="comments" class="mb-4">
        <h3 class="my-2 display-4">Коментарі</h3>
        <div class="row mt-3">
            <div class="col-md-12 border p-2">
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseOne" aria-expanded="false"
                                    aria-controls="flush-collapseOne">
                                <i class="fa-solid fa-comment me-1"></i>
                                Додати коментар
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse mt-2"
                             aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                            <form action="/comments/add?goodsId=<?= $model['id'] ?>" method="post">
                                <div class="mb-3">
                                    <label for="text" class="form-label">Текст коментаря</label>
                                    <textarea name="text" class="form-control editor" id="text"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Коментувати</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <?php if (is_array($comments)): ?>
                <?php foreach ($comments as $comment) : ?>
                    <div class="col-md-6 border my-2">
                        <div class="card my-2 bg-dark text-light">
                            <div class="card-header">
                                <img src="/files/users/<?= getUserById($comment['id_user'])['photo'] ?>_s.jpg"
                                     class="rounded-circle" alt="">
                                <?= getUserById($comment['id_user'])['login'] ?>
                                <?php if(!empty($user)&&$comment['id_user']===$user['id']||$userModel->checkOnAdmin()):?>
                                <span>
                                    <a href="/comments/delete?id=<?=$comment['id']?>&goodsId=<?= $model['id'] ?>" class="btn btn-danger mx-2">
                                    Видалити
                                    </a>
                                </span>
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="card-body">
                            <?= $comment['text'] ?>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">Додано: <?= $comment['datetime'] ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="mb-2 alert alert-info">
                    Коментарів немає. Будьте першими хто прокоментує даний товар!
                </div>
            <?php endif; ?>

        </div>
    </section>


</div>



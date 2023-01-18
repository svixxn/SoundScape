<?php
$i = 0;
?>
<div class="container">
    <div class="row">
        <div class="col-md-7">
            <?php foreach ($Goods as $goods) : ?>
                <div class="card mb-3">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <img class="card-img-top m-3" src="/files/goods/<?= $goods[0]['photo'] ?>_m.jpg" alt="">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h4 class="card-title mb-3"><?= $goods[0]['name'] ?></h4>
                                <div class="d-flex justify-content-between">
                                    <span class="p-2 lead">
                                        <strong><span class="sum"><?= $goods[0]['cost'] ?></span> грн</strong>
                                    </span>
                                    <div class="d-flex px-3">
                                        <input type="button" class="btn btn-primary increm<?=$i?>" value="+">
                                        <input type="text" class="form-control mx-2 quanInput<?=$i?>"
                                               value="<?= $cartGoods[$i]['quantity'] ?>">
                                        <input type="button" class="btn btn-primary decrem<?=$i?>" value="-">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#del<?=$goods[0]['id']?>">
                                    <i class="fa-solid fa-trash"></i>
                                    Видалити із корзини
                                </button>
                                <a href="/myprofile/confirm" class="btn btn-outline-success btn-lg mt-3 btn-block">
                                    <i class="fa-sharp fa-solid fa-cart-plus"></i>
                                    Оформити замовлення
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">Додано: <?= $cartGoods[$i]['datetime'] ?></small>
                    </div>
                </div>
                <div class="modal fade" id="del<?=$goods[0]['id']?>" tabindex="-1"  aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content text-dark">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" >Видалення товару <?=$goods[0]['name']?></h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Ви дійсно хочете видалити товар <strong><?=$goods[0]['name']?></strong> із корзини?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
                                <a href="/myprofile/deletefromcart?id=<?= $goods[0]['id']?>&confirm=yes" class="btn btn-danger">Видалити</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $i++ ?>
            <?php endforeach; ?>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Деталі</h4>
                    <h6 class="card-subtitle text-muted">Кількість товарів, ціна доставки, загальна ціна<hr></h6>

                    <div class="row mt-2">
                        <div class="col-md-6">Усього товарів:</div>
                        <div class="col-md-6"><?=$cartQuan?></div>
                    </div>
                    <hr>
                    <div class="row mt-2">
                        <div class="col-md-6">Загальна ціна за товари:</div>
                        <div class="col-md-6" id="generalPrice"></div>
                    </div>
                    <hr>
                    <div class="row mt-2">
                        <div class="col-md-6">Загальна ціна за доставку:</div>
                        <div class="col-md-6">150 грн</div>
                    </div>
                    <hr>
                    <div class="row mt-2">
                        <div class="col-md-6"><strong>Загальна ціна:</strong></div>
                        <div class="col-md-6"><strong id="generalPriceAll"></strong></div>
                    </div>
                    <a href="/myprofile/confirm" class="btn btn-outline-success btn-lg mt-3 btn-block">
                        <i class="fa-sharp fa-solid fa-cart-plus"></i>
                        Оформити замовлення
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
<script src="/script2.js"></script>

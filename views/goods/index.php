<?php
$userModel = new \models\Users();
$user = $userModel->getCurrentUser();
$fl = 0;

?>
<div class="container">
    <nav id="sidebarMenu" class="collapse d-xl-block sidebar collapse bg-white">
        <div class="container-fluid">
            <div class="list-group list-group-flush mx-3">
                <?php if ($userModel->checkOnAdmin()): ?>
                    <a href="/goods/add" class="btn btn-primary list-group-item active mb-2">Додати товар</a>
                <?php endif; ?>

                <a href="#" class="list-group-item py-2" aria-expanded="false" data-bs-toggle="collapse"
                   data-bs-target="#category-collapse">
                    <i class="fa-solid fa-folder-open me-3"></i><span>Категорії</span>
                </a>
                <div class="collapse" id="category-collapse">
                    <ul class="list-unstyled list-group">
                        <li>
                            <a href="/goods/sort?category=keys&page=1"
                               class="list-group-item link-unstyled mx-3 mt-2">
                                <i class="fa-solid fa-keyboard"></i>
                                Клавішні
                            </a>
                        </li>
                        <li>
                            <a href="/goods/sort?category=strings&page=1"
                               class="list-group-item link-unstyled mx-3">
                                <i class="fa-solid fa-guitar"></i>
                                Струнні
                            </a>
                        </li>
                        <li>
                            <a href="/goods/sort?category=winds&page=1" class="list-group-item link-unstyled mx-3">
                                <i class="fa-solid fa-wind"></i>
                                Духові
                            </a>
                        </li>
                        <li>
                            <a href="/goods/sort?category=percussion&page=1" class="list-group-item link-unstyled mx-3 mb-3">
                                <i class="fa-solid fa-drum"></i>
                                Ударні
                            </a>
                        </li>
                    </ul>
                </div>
                <a href="#" class="list-group-item py-2 " aria-expanded="false"
                   data-bs-toggle="collapse" data-bs-target="#price-collapse">
                    <i class="fa-solid fa-money-bill-wave me-3"></i><span>Сортувати за ціною</span>
                </a>
                <div class="collapse" id="price-collapse">
                    <div id="priceSlider" class="mt-3"></div>
                    <div class="d-inline-flex my-3 align-items-center justify-content-between">
                        <label for="minPrice">Від:</label>
                        <input type="text" id="minPrice" placeholder="1000" class="form-control mx-2">
                        <label for="maxPrice">До:</label>
                        <input type="text" id="maxPrice" placeholder="100000" class="form-control mx-2">
                    </div>
                    <a href="/goods/sort?page=1&" class="btn btn-primary" onclick="setLink()" id="priceSortLink"
                       style="width: 100%">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Пошук
                    </a>
                </div>

            </div>
        </div>
    </nav>

    <div class="row">
        <?php foreach ($lastGoods

        as $goods) : ?>
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card card-product">
                <a href="/goods/view?id=<?= $goods['id'] ?>" class="link-unstyled">
                    <img class="card-img-top" src="/files/goods/<?= $goods['photo'] ?>_m.jpg" alt="">
                    <div class="card-body">
                        <h4 class="card-title mb-3"><?= $goods['name'] ?></h4>
                </a>
                <span class="d-flex justify-content-between">
                        <span class="p-2 lead">
                            <strong><?= $goods['cost'] ?> грн</strong>
                        </span>
                        <span class="pt-1">
                            <a href="/myprofile/addtocart?id=<?= $goods['id'] ?>" class="btn btn-success text-center">
                                <i class="fa-sharp fa-solid fa-cart-plus"></i>
                                Купити
                            </a>
                        </span>
                    </span>
                <a href="/goods/view?id=<?= $goods['id'] ?>" class="btn btn-primary btn-block my-2" style="width:100%">
                    <i class="fa-solid fa-circle-info"></i>
                    Детальніше
                </a>
            </div>
            <div class="card-footer">
                <small class="text-muted">Додано: <?= $goods['datetime'] ?></small>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>


<nav aria-label="Page navigation example">
    <ul class="pagination">
        <li class="page-item">
            <?php if (!isset($_GET['category']) && !isset($_GET['startPrice'])  && !isset($_GET['search'])): ?>
            <a class="page-link <?= $activeClassPrev ?>" href="/goods?page=<?= $_GET['page'] - 1 ?>" aria-label="Previous">
                <?php else : if (!isset($_GET['startPrice']) && !isset($_GET['search'])) : ?>
                <a class="page-link <?= $activeClassPrev ?>"
                   href="/goods/sort?category=<?= $_GET['category'] ?>&page=<?= $_GET['page'] - 1 ?>"
                   aria-label="Previous">
                    <?php else : if(!isset($_GET['search'])) : ?>
                    <a class="page-link <?= $activeClassPrev ?>" onclick="setLinks(<?= $_GET['page'] - 1 ?>)"
                       aria-label="Previous">
                        <?php else : ?>
                        <a class="page-link <?= $activeClassPrev ?>"
                           href="/goods/sort?search=<?= $_GET['search'] ?>&page=<?= $_GET['page'] - 1 ?>"
                           aria-label="Previous">
                        <?php endif;endif; endif; ?>
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
        </li>


        <?php for ($i = 1; $i <= $pages; $i++) : ?>
            <?php if (!isset($_GET['category']) && !isset($_GET['startPrice']) && !isset($_GET['search'])): ?>
                <li class="page-item"><a class="page-link" href="/goods?page=<?= $i ?>"><?= $i ?></a></li>
            <?php else : if (!isset($_GET['startPrice']) && !isset($_GET['search'])) : ?>
                <li class="page-item"><a class="page-link" href="/goods/sort?category=<?= $_GET['category'] ?>&page=<?= $i ?>"><?= $i ?></a></li>
            <?php else : if(!isset($_GET['search'])) : ?>
                <li class="page-item"><a class="page-link page-link-cost" onclick="setLinks(<?= $i ?>)"><?= $i ?></a></li>
            <?php else : ?>
            <li class="page-item"><a class="page-link" href="/goods/sort?search=<?= $_GET['search'] ?>&page=<?= $i ?>"><?= $i ?></a></li>
            <?php endif; endif; endif; ?>
        <?php endfor; ?>


        <li class="page-item">
            <?php if (!isset($_GET['category']) && !isset($_GET['startPrice']) && !isset($_GET['search'])): ?>
            <a class="page-link <?= $activeClassNext ?>" href="/goods?page=<?= $_GET['page'] + 1 ?>" aria-label="Next">
                <?php else : if (!isset($_GET['startPrice']) && !isset($_GET['search'])) : ?>
                <a class="page-link <?= $activeClassNext ?>"
                   href="/goods/sort?category=<?= $_GET['category'] ?>&page=<?= $_GET['page'] + 1 ?>"
                   aria-label="Next">
                    <?php else : if(!isset($_GET['search'])) : ?>
                    <a class="page-link <?= $activeClassNext ?>" onclick="setLinks(<?= $_GET['page'] + 1 ?>)"
                       aria-label="Next">
                        <?php else : ?>
                        <a class="page-link <?= $activeClassNext ?>"
                           href="/goods/sort?search=<?= $_GET['search'] ?>&page=<?= $_GET['page'] + 1 ?>"
                           aria-label="Next">
                            <?php endif;endif; endif; ?>
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
        </li>
    </ul>
</nav>


</div>











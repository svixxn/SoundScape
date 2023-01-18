<?php
$userModel = new \models\Users();
$profileModel = new \models\MyProfile();
$user = $userModel->getCurrentUser();
if(!empty($user))
$cartQuantity = $profileModel->GetCartQuantity($user['id']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../style.css">
    <script src="https://kit.fontawesome.com/065cbe29ab.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="/noUiSlider-15.6.1/noUiSlider-15.6.1%20(1)/noUiSlider-15.6.1/dist/nouislider.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css" />
    <link rel="icon" href="/files/dop%20files/logo.png">
    <title><?= $MainTitle ?></title>
</head>
<body>
<nav class="navbar navbar-dark navbar-expand-lg bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="/files/dop%20files/logo.png" width="50" height="50" alt="" class="nav-logo rounded-circle">
            <h3 class="d-inline align-middle">SoundScape</h3>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link nav-main" href="/">На головну</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-main" href="/goods?page=1">Товари</a>
                </li>
                <?php if($userModel->isUserAuthenticated()&&$userModel->checkOnAdmin()):?>
                <li class="nav-item">
                    <a class="nav-link nav-main" href="../users/ShowUsers">Користувачі</a>
                </li>
                <?php endif;?>
            </ul>
            <form class="d-flex" method="get" action="/goods/sort?">
                <input class="form-control me-2" type="search" placeholder="Пошук" aria-label="Search" name="search">
                <input class="form-control me-2" type="text" name="page" value="1" style="display: none">

                <button class="btn btn-outline-info" type="submit">Знайти</button>
            </form>
                <?php if (!$userModel->isUserAuthenticated()): ?>
                    <a href="/users/register" class="mx-2 btn btn-primary">Реєстрація</a>
                    <a href="/users/login" class="btn btn-primary">Увійти</a>
                <?php else: ?>
                    <div class="dropdown">
                        <a class="dropdown-toggle link-unstyled navbar-text mx-2 text-white" href="#" role="button" data-bs-toggle="dropdown"
                           aria-expanded="false">
                            <img src="/files/users/<?= $user['photo'] ?>_s.jpg" class="rounded-circle" alt="">
                            <?=$user['login']?>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="/myprofile">
                                    <i class="fa-solid fa-user"></i>
                                    Профіль
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/myprofile/cart">
                                    <span>
                                        <i class="fa-solid fa-cart-shopping"></i>
                                        <sup id="cartQuantity"><?=$cartQuantity?></sup>
                                    </span>
                                    Корзина
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="/users/logout">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    Вийти
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>
        </div>
    </div>
</nav>
<div class="wrapper">
<div class="container">
    <?php if (!empty($PageTitle)): ?>
    <h1 class="mt-5"><?= $PageTitle ?></h1>
    <?php endif; ?>
    <?php if (!empty($MessageText)): ?>
        <div class="alert alert-<?= $MessageClass ?>" role="alert">
            <?= $MessageText ?>
        </div>
    <?php endif; ?>
</div>
    <?= $PageContent ?>
</div>


<footer id="main-footer" class="text-center bg-dark p-4 text-white mt-4">
    <div class="container">
        <section class="mb-4">
            <!-- Facebook -->
            <a class="btn btn-outline-light btn-floating m-1" href="https://www.facebook.com/profile.php?id=100010784380466" role="button"
            ><i class="fab fa-facebook-f"></i
                ></a>

            <!-- Twitter -->
            <a class="btn btn-outline-light btn-floating m-1" href="https://twitter.com/KijBakovec" role="button"
            ><i class="fab fa-twitter"></i
                ></a>

            <!-- Instagram -->
            <a class="btn btn-outline-light btn-floating m-1" href="https://www.instagram.com/svixxn/" role="button"
            ><i class="fab fa-instagram"></i
                ></a>

            <!-- GitLab -->
            <a class="btn btn-outline-light btn-floating m-1" href="https://gitlab.com/kn211_bmo" role="button"
            ><i class="fab fa-gitlab"></i
                ></a>

            <!-- Github -->
            <a class="btn btn-outline-light btn-floating m-1" href="https://github.com/svixxn" role="button"
            ><i class="fab fa-github"></i
                ></a>
        </section>
        <!-- Section: Social media -->
    </div>
    <!-- Grid container -->
        <div class="row">
            <div class="col">
                <p>&copy;
                    2022 - <span id="year"></span> Copyright: SoundScape</p>
            </div>
        </div>
    </div>
</footer>




<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="/noUiSlider-15.6.1/noUiSlider-15.6.1%20(1)/noUiSlider-15.6.1/dist/nouislider.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/35.3.2/classic/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="/script.js"></script>
</body>
</html>

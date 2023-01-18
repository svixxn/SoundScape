<?php
$userModel = new \models\Users();
$commentsModel = new \models\Comments();
$goodsModel = new \models\Goods();

$countUser = count($userModel->GetAllUsers());
$countGoods = count($goodsModel->GetGoods());
$countComments = count($commentsModel->GetAllComments());
?>

<div class="container mt-5">
    <table class="table table-bordered table-dark">
       <thead>
        <tr>
            <td>Id</td>
            <td>Login</td>
            <td>Lastname</td>
            <td>Firstname</td>
            <td>Access</td>
            <td>Edit access/delete</td>
        </tr>
       </thead>
        <tbody>
       <?php foreach ($users as $user) : ?>
           <tr>
               <td class="pt-2"><?=$user['id']?></td>
               <td><?=$user['login']?></td>
               <td><?=$user['lastname']?></td>
               <td><?=$user['firstname']?></td>
               <td><?=$user['access']?></td>
               <td>
                   <button type="button" class="btn btn-danger mx-2" data-bs-toggle="modal" data-bs-target="#delete<?=$user['id']?>">
                       Видалити
                   </button>
                   <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#change<?=$user['id']?>">
                       Змінити доступ
                   </button>
               </td>
           </tr>
           <div class="modal fade" id="delete<?=$user['id']?>" tabindex="-1"  aria-hidden="true">
               <div class="modal-dialog">
                   <div class="modal-content text-dark">
                       <div class="modal-header">
                           <h1 class="modal-title fs-5" >Видалення користувача <?=$user['login']?></h1>
                           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                       </div>
                       <div class="modal-body">
                           <p>Ви дійсно хочете видалити користувача <strong><?=$user['login']?></strong>?</p>
                       </div>
                       <div class="modal-footer">
                           <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
                           <a href="/users/delete?login=<?= $user['login']?>&confirm=yes" class="btn btn-danger">Видалити</a>
                       </div>
                   </div>
               </div>
           </div>

           <div class="modal fade" id="change<?=$user['id']?>" tabindex="-1"  aria-hidden="true">
               <div class="modal-dialog">
                   <div class="modal-content text-dark">
                       <div class="modal-header">
                           <h1 class="modal-title fs-5" >Зміна доступу користувача <?=$user['login']?></h1>
                           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                       </div>
                       <div class="modal-body">
                           <p>Оберіть доступ для користувача <strong><?=$user['login']?></strong></p>
                       </div>
                       <div class="modal-footer">
                           <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
                           <a href="/users/ChangeAccess?login=<?= $user['login']?>&option=1" class="btn btn-danger">1(модератор)</a>
                           <a href="/users/ChangeAccess?login=<?= $user['login']?>&option=0" class="btn btn-danger">0(звичайний кор.)</a>
                       </div>
                   </div>
               </div>
           </div>
       <?php endforeach;?>
        </tbody>
    </table>
    <div class="d-flex flex-column mt-4">
        <div><h4><i class="fa-solid fa-users"></i> Усього користувачів - <?=$countUser?></h4></div>
        <div><h4><i class="fa-solid fa-comments"></i> Усього коментарів - <?=$countComments?></h4></div>
        <div><h4><i class="fa-solid fa-boxes-stacked"></i> Усього товарів - <?=$countGoods?></h4></div>
    </div>
</div>




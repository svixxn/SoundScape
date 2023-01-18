<div class="container">
    <div class="row">
        <div class="col-md-6">
            <?php if (isset($user['photo']) && is_file('files/users/' . $user['photo'] . '_b.jpg')) : ?>
                <img src="/files/users/<?= $user['photo'] ?>_b.jpg" alt=""
                     class="bd-placeholder-img rounded float-start m-3 img-fluid"/>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <table class="table table-bordered table-striped  mt-3">
              <tbody>
              <tr>
                  <td>Id</td>
                  <td><?=$user['id']?></td>
              </tr>
              <tr>
                  <td>Логін</td>
                  <td><?=$user['login']?></td>
              </tr>
              <tr>
                  <td>Прізвище</td>
                  <td><?=$user['lastname']?></td>
              </tr>
              <tr>
                  <td>Ім'я</td>
                  <td><?=$user['firstname']?></td>
              </tr>
              </tbody>
            </table>
            <a href="/myprofile/edit" class="btn btn-primary btn-block">
                <i class="fa-solid fa-pen-to-square"></i>
                Редагувати профіль
            </a>
            <button type="button" class="btn btn-danger my-3 btn-block" data-bs-toggle="modal" data-bs-target="#delete<?=$user['id']?>">
                <i class="fa-solid fa-trash"></i>
                Видалити аккаунт
            </button>
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
                            <a href="/myprofile/delete" class="btn btn-danger">Видалити</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

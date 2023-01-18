<div class="container">
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Логін</label>
            <input type="text" name="login" value="<?= $model['login'] ?? null ?>" class="form-control" id="exampleInputEmail1">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Пароль</label>
            <input type="password" name="password"  class="form-control" id="exampleInputPassword1">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword2" class="form-label">Повторіть пароль</label>
            <input type="password" name="password2" class="form-control" id="exampleInputPassword2">
        </div>
        <div class="mb-3">
            <label for="lastname" class="form-label">Прізвище</label>
            <input type="text" name="lastname" value="<?=$model['lastname'] ?? null?>" class="form-control" id="lastname">
        </div>
        <div class="mb-3">
            <label for="firstname" class="form-label">Ім'я</label>
            <input type="text" name="firstname" value="<?=$model['firstname'] ?? null?>" class="form-control" id="firstname">
        </div>
        <div class="mb-3">
            <label for="file_ava" class="form-label">Фотографія профілю</label>
            <input type="file" name="file_ava" accept="image/jpeg, image/png" class="form-control" id="file_ava">
        </div>
        <button type="submit" class="btn btn-primary">Зберегти</button>
    </form>
</div>

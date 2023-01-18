<div class="container d-flex justify-content-center">
<div class="card p-4" style="width: 60rem;">
        <img src="/files/music-notes-26350450.jpg.png" alt="" class="card-image-top">
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Логін</label>
                    <input type="text" name="login" value="<?= $_POST['login'] ?? null ?>" class="form-control" id="exampleInputEmail1">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Пароль</label>
                    <input type="password" name="password"  class="form-control" id="exampleInputPassword1">
                </div>
                <button type="submit" class="btn btn-outline-primary mt-3" style="width: 100%;">Увійти</button>
            </form>
        </div>
</div>
</div>


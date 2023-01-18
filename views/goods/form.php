<div class="container">
<form action="" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="name" class="form-label">Назва товару</label>
        <input type="text" name="name" value="<?= $model['name'] ?? null ?>" class="form-control" id="name">
    </div>
    <div class="mb-3">
        <label for="text" class="form-label">Опис товару</label>
        <textarea name="text"  class="form-control editor" id="text"><?= $model['text'] ?? null ?></textarea>
    </div>

    <label for="cost" class="form-label">Ціна товару</label>
    <div class="mb-3 input-group">
        <input type="text" name="cost" class="form-control" id="cost" value="<?= $model['cost'] ?? null ?>">
        <span class="input-group-text">(грн)</span>
    </div>

    <div class="mb-3">
        <label for="select" class="form-label">Категорія товару</label>
    <select id="select" class="form-select" name="category">
        <option value="strings">Струнні</option>
        <option value="keys" selected>Клавішні</option>
        <option value="winds">Духові</option>
        <option value="percussion">Ударні</option>
    </select>
    </div>

    <div class="mb-3">
        <label for="file" class="form-label">Фотографія до товару</label>
        <input type="file" name="file" accept="image/jpeg, image/png" class="form-control" id="file" >
    </div>
    <div class="mb-3">
        <?php if (isset($model['photo'])&&is_file('files/goods/'.$model['photo'].'_b.jpg')) :?>
            <img src="/files/news/<?=$model['photo']?>_b.jpg" alt="" />
        <?php endif;?>
    </div>

    <button type="submit" class="btn btn-primary">Зберегти</button>
</form>
</div>

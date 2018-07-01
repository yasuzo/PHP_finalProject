<?php foreach($messages as $message): ?>
    <div class="alert alert-danger">
        <?= safe($message) ?>
    </div>
<?php endforeach; ?>

<form action="?controller=update-news&newsId=<?= $id ?>" method="post">
    <label for="title">Naslov</label><br>
    <input type="text" name="title" value="<?= safe($title); ?>"><br><br>
    <label for="content">Sadrzaj</label><br>
    <textarea name="content" cols="30" rows="10"><?= safe($content); ?></textarea>
    <input type="submit" value="Promijeni">
    <hr>
    <input type="submit" value="Izbrisi" name="delete">
</form>
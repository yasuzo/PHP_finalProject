<?php if($isAdmin): ?>
    <?php foreach($messages as $message): ?>
        <div class="alert alert-danger">
            <?= safe($message) ?>
        </div>
    <?php endforeach; ?>
    <form action="index.php" method="post">
        <label for="title">Naslov</label><br>
        <input type="text" name="title"><br><br>
        <label for="content">Sadrzaj</label><br>
        <textarea name="content" cols="30" rows="10"></textarea>
        <input type="submit" value="Dodaj">
    </form>
    <hr>
<?php endif; ?>

<h4>Zadnje objave</h4>
<div class="container">
    <?php foreach($news as $val): ?>
        <h6><?= safe($val['title']); ?> <small>@ <?= safe($val['date_time']); ?></small></h6>
        <hr>
        <p class="text-secondary"><?= safe($val['content']); ?></p><br>
    <?php endforeach; ?>
</div>

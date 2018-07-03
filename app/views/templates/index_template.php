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
        <h6><?= safe($val['title']); ?> 
            <?php if($isAdmin): ?>
                <small><a href="?controller=update-news&newsId=<?= $val['id'];?>">Uredi</a></small>
            <?php endif; ?>
        </h6>
        <sub>@ <?= safe($val['date_time']); ?> by <a href="#"><?= safe(($val['username'] ?? 'unknown')); ?></a></sub>
        <hr>
        <p class="text-secondary"><?= safe($val['content']); ?></p><br>
    <?php endforeach; ?>
</div>

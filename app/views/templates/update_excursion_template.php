<?php foreach($messages as $message): ?>
    <div class="alert alert-danger">
        <?= safe($message) ?>
    </div>
<?php endforeach; ?>

<form action="?controller=update-excursion&excursionId=<?= $id; ?>" method="post">
    <label for="title">Naslov</label><br>
    <input type="text" name="title" value="<?= safe($title); ?>"><br><br>
    <label for="date">Datum (yyyy-mm-dd ili dd.mm.yyyy)</label><br>
    <input type="text" name="date" value="<?= safe($date); ?>"><br>
    <label for="time">Vrijeme (HH:mm:ss)</label><br>
    <input type="text" name="time" value="<?= safe($time); ?>"><br>
    <label for="destination">Odrediste</label><br>
    <input type="text" name="destination" value="<?= safe($destination); ?>"><br>
    <label for="startingPoint">Polaziste</label><br>
    <input type="text" name="startingPoint" value="<?= safe($startingPoint); ?>"><br>
    <label for="price">Cijena</label><br>
    <input type="number" step="0.01" name="price" value="<?= safe($price); ?>"><br>
    <label for="description">Opis</label><br>
    <textarea name="description" cols="30" rows="10"><?= safe($description); ?></textarea>
    <input type="submit" value="Promijeni">
</form>

<hr>

<form action="?controller=update-excursion&excursionId=<?= $id ?>" method="post">
    <input type="submit" value="Izbrisi" name="delete">
</form>
<br>
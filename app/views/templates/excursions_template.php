<?php if($isAdmin): ?>
    <?php foreach($messages as $message): ?>
        <div class="alert alert-danger">
            <?= safe($message) ?>
        </div>
    <?php endforeach; ?>
    <form action="?controller=excursions" method="post">
        <label for="title">Naslov</label><br>
        <input type="text" name="title"><br><br>
        <label for="date">Datum (yyyy-mm-dd ili dd.mm.yyyy)</label><br>
        <input type="text" name="date"><br>
        <label for="time">Vrijeme (HH:mm:ss)</label><br>
        <input type="text" name="time"><br>
        <label for="destination">Odrediste</label><br>
        <input type="text" name="destination"><br>
        <label for="startingPoint">Polaziste</label><br>
        <input type="text" name="startingPoint"><br>
        <label for="price">Cijena</label><br>
        <input type="number" step="0.01" name="price"><br>
        <label for="description">Opis</label><br>
        <textarea name="description" cols="30" rows="10"></textarea>
        <input type="submit" value="Dodaj">
    </form>
    <hr>
<?php endif; ?>

<h4>Najnoviji izleti</h4>
<div class="container">
    <?php foreach($excursions as $val): ?>
        <hr><hr>
        <h4><?= safe($val['title']); ?> 
            <?php if($isAdmin): ?>
                <small><a href="?controller=update-excursion&excursionId=<?= $val['id'];?>">Uredi</a></small>
            <?php endif; ?>
        </h4>
        <sub>Polazak @ <?= safe($val['date_time']); ?></sub>
        <hr>
        <table>
        <thead>
        <tr>
            <th>Polaziste  &nbsp;</th>
            <th>Odrediste  &nbsp;</th>
            <th>Cijena &nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?= safe($val['startingPoint']); ?>  </td>
            <td><?= safe($val['destination']); ?>  </td>
            <td><?= $val['price']/100; ?>kn  </td>
        </tr>
        </tbody>
        </table>
        <p class="text-secondary"><?= safe($val['description']); ?></p><br>
    <?php endforeach; ?>
</div>

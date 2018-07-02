<h4>Korisnici</h4>
<div class="container">
    <?php foreach($users as $val): ?>
        <h6><?= safe($val['firstName'] . ' ' . $val['lastName']); ?></h6>
        <small>Username: <a href="#"><?= safe($val['username']); ?></a></small><br>
        <small>User Type: <a href="#"><?= safe($val['permission']); ?></a></small>
        <?php if($isAdmin || $isSuperAdmin): ?>
            <form action="?controller=members&userId=<?= safe($val['id']); ?>" method="post">
                <input type="submit" value="Izbrisi" name="delete">
                <input type="submit" value="Postavi za admina" name="promoteToAdmin">
                <?php if($isSuperAdmin): ?>
                    <input type="submit" value="Postavi za super admina" name="promoteToSuperadmin">
                <?php endif; ?>
            </form>
        <?php endif; ?>
        <hr>
    <?php endforeach; ?>
</div>

<?php foreach($messages as $message): ?>
    <div class="alert alert-danger">
        <?= safe($message) ?>
    </div>
<?php endforeach; ?>

<form action="?controller=settings&change=data" method="post">
    <label for="firstName">Ime</label>
    <input type="text" name="firstName" value="<?= safe($firstName) ?>"><br>
    <label for="firstName">Prezime</label>
    <input type="text" name="lastName" value="<?= safe($lastName) ?>"><br>
    <label for="username">Ime</label>
    <input type="text" name="username" value="<?= safe($username) ?>">
    <input type="submit" value="Promijeni">
</form>
<hr>
<form action="?controller=settings&change=password" method="post">
<label for="firstName">Stara lozinka</label>
    <input type="password" name="oldPassword"><br><br>
    <label for="firstName">Nova lozinka</label>
    <input type="password" name="newPassword1"><br>
    <label for="username">Ponovljena nova lozinka</label>
    <input type="password" name="newPassword2">
    <input type="submit" value="Promijeni">
</form>

<br>
<hr>

<form action="?controller=settings" method="post">
    <input type="submit" value="Izbrisi racun" name="delete">
</form>
<div class="text-center center-content container">
    <?php foreach($messages as $message): ?>
        <div class="alert alert-danger">
            <?= safe($message) ?>
        </div>
    <?php endforeach; ?>

    <form class="form-signin" method="POST" action="?controller=login">
        <h1 class="h3 mb-3 font-weight-normal">Prijava</h1>
        <label for="inputEmail" class="sr-only">Korsnicko ime</label>
        <input type="username"class="form-control" placeholder="Korisnicko ime" required="true" autofocus="on" autocomplete="off" 
            oninvalid="this.setCustomValidity('Polje ne smije biti prazno!')" oninput="setCustomValidity('')" name="username"><br>
        <label for="inputPassword" class="sr-only">Lozinka</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" required="true" autocomplete="off"
            oninvalid="this.setCustomValidity('Polje ne smije biti prazno!')" oninput="setCustomValidity('')" name="password"><br>
        <div class="checkbox mb-1">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div>
        
        <div class="mb-3"><a href="?controller=register">Niste uclanjeni? Uclanite se!</a></div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Prijava</button>
        <p class="mt-5 mb-3 text-muted">© 2017-2018</p>
    </form>
</div>
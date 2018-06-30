<div class="text-center center-content container">
    <?php foreach($messages as $message): ?>
        <div class="alert alert-danger">
            <?= safe($message) ?>
        </div>
    <?php endforeach; ?>

    <form class="form-signin" method="POST" action="?controller=register">
        <h1 class="h3 mb-3 font-weight-normal">Registracija</h1>

        <label for="firstName" class="sr-only">Ime</label>
        <input type="text"class="form-control" placeholder="Ime" required="true" autofocus="on" autocomplete="off" 
            oninvalid="this.setCustomValidity('Polje ne smije biti prazno!')" oninput="setCustomValidity('')" name="firstName"><br>
            <label for="lastName" class="sr-only">Prezime</label>
        <input type="text"class="form-control" placeholder="Prezime" required="true" autofocus="on" autocomplete="off" 
            oninvalid="this.setCustomValidity('Polje ne smije biti prazno!')" oninput="setCustomValidity('')" name="lastName"><br>

        <label for="inputEmail" class="sr-only">Korsnicko ime</label>
        <input type="text"class="form-control" placeholder="Korisnicko ime" required="true" autofocus="on" autocomplete="off" 
            oninvalid="this.setCustomValidity('Polje ne smije biti prazno!')" oninput="setCustomValidity('')" name="username"><br>
        <label for="inputPassword" class="sr-only">Lozinka</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Lozinka" required="true" autocomplete="off"
            oninvalid="this.setCustomValidity('Polje ne smije biti prazno!')" oninput="setCustomValidity('')" name="pass1"><br>
        <label for="inputPassword" class="sr-only">Ponovljena lozinka</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Ponovljena lozinka" required="true" autocomplete="off"
            oninvalid="this.setCustomValidity('Polje ne smije biti prazno!')" oninput="setCustomValidity('')" name="pass2"><br>
        <div class="checkbox mb-1">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div>
        
        <div class="mb-3"><a href="?controller=login">Vec ste uclanjeni? Prijavite se!</a></div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Registracija</button>
        <p class="mt-5 mb-3 text-muted">© 2017-2018</p>
    </form>
</div>
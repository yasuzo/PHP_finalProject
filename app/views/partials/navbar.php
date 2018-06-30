<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="?controller=index">PD Kalnik</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button> 

    <div class="collapse navbar-collapse order-1" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="?controller=index">Pocetna</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?controller=excursions">Izleti</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?controller=members">Clanovi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?controller=about">O nama</a>
            </li>
            <hr>
            <?php if($title !== 'Prijava' && $title !== 'Registracija'): ?>
                <?php if($authenticated === false): ?>
                <li class="nav-item float-right">
                    <a class="nav-link btn btn-success text-light" href="?controller=login">Prijava/Registracija</a>
                </li>
                <?php else: ?>
                <li class="nav-item float-right">
                    <a class="nav-link" href="?controller=settings">Postavke</a>
                </li>
                <form class="form-inline my-2 my-lg-0" action="?controller=login&odjavi-me" method="post">
                    <button class="btn btn-success my-2 my-sm-0 text-light" type="submit">Odjava</button>
                </form>
                <?php endif;?>
            <?php endif; ?>
        </ul>
    </div>

</nav>
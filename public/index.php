<?php

define('ROOT', __DIR__.'/..');

// AUTOLOAD
require_once ROOT.'/app/autoload.php';

require_once ROOT.'/app/baza.php';

require_once ROOT."/app/libraries/helper_functions.php";
require_once ROOT."/app/libraries/validation_helpers.php";

use Services\{Session, Templating, UserRepository, NewsRepository, Firewall};

use Controllers\{
    LoginController,
    ExcursionsController,
    MembersController,
    AboutController,
    ProfileController,
    SettingsController,
    IndexController,
    Error404Controller,
    RegisterController
};

use Http\Responses\HTMLResponse;
use Http\Request;

$userRepository = new UserRepository($db);
$newsRepository = new NewsRepository($db);
$templatingEngine = new Templating(ROOT.'/app/views/');
$session = new Session();
$firewall = new Firewall($session, $userRepository);
$request = new Request(
    $_SERVER['REQUEST_METHOD'], 
    $_SERVER['HTTP_REFERER'] ?? null, 
    $_GET, 
    $_POST, 
    $_FILES
);

switch($_GET['controller'] ?? 'index'){
    case 'login':
        $controller = new LoginController($templatingEngine, $session, $userRepository);
        break;
    case 'register':
        $controller = new RegisterController($templatingEngine, $session, $userRepository);
        break;
    case 'excursions':
        $controller = new ExcursionsController($templatingEngine, $session);
        break;
    case 'members':
        $controller = new MembersController($templatingEngine, $session);
        break;
    case 'about':
        $controller = new AboutController($templatingEngine, $session);
        break;
    case 'profile':
        $controller = new ProfileController($templatingEngine, $session);
        break;
    case 'settings':
        $controller = new SettingsController($templatingEngine, $session, $userRepository);
        break;
    case 'index':
        $controller = new IndexController($templatingEngine, $session, $newsRepository, $firewall);
        break;
    default:
        http_response_code(404);
        $controller = new Error404Controller($templatingEngine, $session);
}

try{
    $respose = $controller->handle($request);
    $respose->send();
}catch(Throwable $e){
    http_response_code(500);
    $response = new HTMLResponse('Greska! '.$e->getMessage());
    $response->send();
}


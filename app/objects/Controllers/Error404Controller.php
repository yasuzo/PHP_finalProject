<?php

namespace Controllers;

use Http\Responses\{HTMLResponse, Response};
use Services\{Session, Templating};
use Http\Request;

class Error404Controller implements Controller{
    private $templatingEngine;
    private $session;

    public function __construct(Templating $engine, Session $session){
        $this->templatingEngine = $engine;
        $this->session = $session;
    }

    public function handle(Request $request): Response{
        $content = $this->templatingEngine->render('layouts/mainLayout.php', [
            'title' => '404',
            'authenticated' => $this->session->isAuthenticated(),
            'body' => $this->templatingEngine->render('templates/404_template.php', [])
        ]);

        return new HTMLResponse($content);
    }
}
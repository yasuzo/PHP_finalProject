<?php

namespace Controllers;

use Http\Responses\{HTMLResponse, Response};
use Services\{Templating, Session};
use Http\Request;

class IndexController implements Controller{
    private $templatingEngine;
    private $session;

    public function __construct(Templating $engine, Session $session){
        $this->templatingEngine = $engine;
        $this->session = $session;
    }

    public function handle(Request $request): Response{
        $content = $this->templatingEngine->render(
            'layouts/mainLayout.php', 
            [ 
                'title' => 'Index',
                'authenticated' => $this->session->isAuthenticated(),
                'body' => $this->templatingEngine->render('templates/index_template.php', [])
            ]
        );

        return new HTMLResponse($content);
    }
}
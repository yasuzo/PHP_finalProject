<?php

namespace Controllers;

use Http\Responses\{HTMLResponse, Response, RedirectResponse};
use Services\{Templating, Session, NewsRepository, Firewall};
use Http\Request;
use Models\News;

class IndexController implements Controller{
    private $templatingEngine;
    private $session;
    private $newsRepository;
    private $firewall;

    public function __construct(Templating $engine, Session $session, NewsRepository $newsRepository, Firewall $firewall){
        $this->templatingEngine = $engine;
        $this->session = $session;
        $this->newsRepository = $newsRepository;
        $this->firewall = $firewall;
    }

    public function handle(Request $request): Response{
        switch($request->method()){
            case 'POST':
                $response = $this->handlePost($request);
                break;
            default:
                $response = $this->handleGet($request);
        }
        return $response;
    }

    private function handleGet(Request $request): Response{
        $content = $this->templatingEngine->render(
            'layouts/mainLayout.php', 
            [ 
                'title' => 'Index',
                'authenticated' => $this->session->isAuthenticated(),
                'body' => $this->templatingEngine->render('templates/index_template.php', [
                    'messages' => $messages ?? [],
                    'isAdmin' => $this->firewall->hasAuthorizationLevel('admin'),
                    'news' => $this->newsRepository->findNews()
                ])
            ]
        );

        return new HTMLResponse($content);
    }

    private function handlePost(Request $request): Response{
        if($this->firewall->hasAuthorizationLevel('admin') === false){
            return new RedirectResponse('?controller=index');
        }

        $post = $request->post();

        $title = $post['title'] ?? '';
        $content = $post['content'] ?? '';

        if(\passed_value_is_array($title, $content)){
            $messages[] = 'Greska - Poslan je array!';
        }else if(\is_empty($title, $content)){
            $messages[] = 'Polje naslova i sadrzaja ne smije biti prazno!';
        }else{
            $this->newsRepository->persist(new News($title, $content, $this->session->getLoggedUserId()));
            return new RedirectResponse('index.php');
        }

        $content = $this->templatingEngine->render(
            'layouts/mainLayout.php', 
            [ 
                'title' => 'Index',
                'authenticated' => $this->session->isAuthenticated(),
                'body' => $this->templatingEngine->render('templates/index_template.php', [
                    'messages' => $messages ?? [],
                    'isAdmin' => $this->firewall->hasAuthorizationLevel('admin'),
                    'news' => $this->newsRepository->findNews()
                ])
            ]
        );

        return new HTMLResponse($content);
    }
}
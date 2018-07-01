<?php

namespace Controllers;
use Services\{NewsRepository, Templating, Firewall, Session};
use Http\Request;
use Http\Responses\{Response, RedirectResponse, HTMLResponse};
use Models\News;

class UpdateNewsController implements Controller{
    private $newsRepository;
    private $templatingEngine;
    private $firewall;
    private $session;

    public function __construct(Templating $templatingEngine, Session $session, NewsRepository $newsRepository, Firewall $firewall){
        $this->firewall = $firewall;
        $this->newsRepository = $newsRepository;
        $this->session = $session;
        $this->templatingEngine = $templatingEngine;
    }

    public function handle(Request $request): Response{
        if(($this->firewall->hasAuthorizationLevel('admin') || $this->firewall->hasAuthorizationLevel('superadmin')) === false){
            return new RedirectResponse('?controller=404');
        }

        $newsId = $request->get()['newsId'] ?? '';

        if(passed_value_is_array($newsId)){
            return new RedirectResponse('index.php');
        }

        $news = $this->newsRepository->findById($newsId);

        if($news === null){
            return new RedirectResponse('index.php');
        }

        switch($request->method()){
            case 'POST': 
                $response = $this->handlePost($request, $news);
                break;
            default:
                $response = $this->handleGet($request, $news);
        }

        return $response;
    }

    private function renderTemplate(News $news, array $messages = []): Response{
        $content = $this->templatingEngine->render(
            'layouts/mainLayout.php', 
            [ 
                'title' => 'Update news',
                'authenticated' => $this->session->isAuthenticated(),
                'body' => $this->templatingEngine->render('templates/update_news_template.php', [
                    'messages' => $messages ?? [],
                    'id' => $news->id(),
                    'title' => $news->title(),
                    'content' => $news->content()
                ])
            ]
        );
        return new HTMLResponse($content);
    }

    private function handleGet(Request $request, News $news): Response{
        return $this->renderTemplate($news);
    }

    private function handlePost(Request $request, News $news): Response{
        $post = $request->post();

        // DELETE NEWS
        if(isset($post['delete'])){
            $this->newsRepository->deleteById($news->id());
            return new RedirectResponse('index.php');
        }


        $title = $post['title'] ?? '';
        $content = $post['content'] ?? '';

        if(\passed_value_is_array($title, $content)){
            $messages[] = 'Greska - Poslan je array!';
        }else if(\is_empty($title, $content)){
            $messages[] = 'Polja ne smiju biti prazna!';
        }else{
            $news->changeTitle($title);
            $news->changeContent($content);
            $this->newsRepository->updateNews($news);
            return new RedirectResponse('index.php');
        }
        return $this->renderTemplate($news, $messages);
    }
}
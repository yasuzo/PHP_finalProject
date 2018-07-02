<?php

namespace Controllers;

use Services\{Firewall, Session, ExcursionRepository, Templating, Normalizer};
use Models\Excursion;
use Http\Request;
use Http\Responses\{Response, HTMLResponse, RedirectResponse};

class ExcursionsController implements Controller{
    private $templatingEngine;
    private $session;
    private $excursionRepository;
    private $firewall;
    private $normalizer;

    public function __construct(Templating $engine, Session $session, ExcursionRepository $excursionRepository, Firewall $firewall, Normalizer $normalizer){
        $this->templatingEngine = $engine;
        $this->session = $session;
        $this->excursionRepository = $excursionRepository;
        $this->firewall = $firewall;
        $this->normalizer = $normalizer;
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

    private function renderTemplate(array $messages = []): Response{
        $content = $this->templatingEngine->render(
            'layouts/mainLayout.php', 
            [ 
                'title' => 'Excursions',
                'authenticated' => $this->session->isAuthenticated(),
                'body' => $this->templatingEngine->render('templates/excursions_template.php', [
                    'messages' => $messages,
                    'isAdmin' => $this->firewall->hasAuthorizationLevel('admin') || $this->firewall->hasAuthorizationLevel('superadmin'),
                    'excursions' => $this->excursionRepository->findAll()
                ])
            ]
        );

        return new HTMLResponse($content);
    }

    private function handleGet(Request $request): Response{
        return $this->renderTemplate();
    }

    private function handlePost(Request $request): Response{
        if(($this->firewall->hasAuthorizationLevel('admin') || $this->firewall->hasAuthorizationLevel('superadmin')) === false){
            return new RedirectResponse('?controller=404');
        }

        $post = $request->post();

        $title = $post['title'] ?? '';
        $description = $post['description'] ?? '';
        $destination = $post['destination'] ?? '';
        $date = $post['date'] ?? '';
        $time = $post['time'] ?? '';
        $price = $post['price'] ?? '';
        $startingPoint = $post['startingPoint'] ?? '';


        if(\passed_value_is_array($title, $description, $destination, $time, $date, $price, $startingPoint)){
            $messages[] = 'Greska - Poslan je array!';
        }else if(\is_empty($title, $description, $destination, $time, $date, $price, $startingPoint)){
            $messages[] = 'Sva polja moraju biti popunjena!';
        }else {
            $this->normalizer->date_to_YMD($date);
            $date_time = $date . ' ' . $time;
            if(\valid_date_time($date_time) === false){
                $messages[] = 'Datum i/ili vrijeme nisu valjani!';
            }else if(\is_string_number($price) === false){
                $messages[] = 'Cijena nije ispravna!';
            }else{
                $this->normalizer->normalize($description);


                $this->excursionRepository->persist(
                    new Excursion(
                        $title,
                        $description,
                        $startingPoint,
                        $destination,
                        $date_time,
                        (int)($price * 100)
                    )
                );

                return new RedirectResponse('?controller=excursions');
            }
        }

        return $this->renderTemplate($messages ?? []);
    }
}
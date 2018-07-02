<?php

namespace Controllers;

use Services\{Templating, Session, ExcursionRepository, Firewall, Normalizer};
use Http\Request;
use Http\Responses\{Response, HTMLResponse, RedirectResponse};
use Models\Excursion;

class UpdateExcursionController implements Controller{
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
        if(($this->firewall->hasAuthorizationLevel('admin') || $this->firewall->hasAuthorizationLevel('superadmin')) === false){
            return new RedirectResponse('?controller=404');
        }

        $excursionId = $request->get()['excursionId'] ?? '';

        if(passed_value_is_array($excursionId)){
            return new RedirectResponse('?controller=excursions');
        }

        $excursion = $this->excursionRepository->findById($excursionId);

        if($excursion === null){
            return new RedirectResponse('index.php');
        }

        switch($request->method()){
            case 'POST':
                $response = $this->handlePost($request, $excursion);
                break;
            default:
                $response = $this->handleGet($request, $excursion);
        }

        return $response;
    }

    private function renderTemplate(Excursion $exc, $messages = []): Response{
        list($date, $time) = explode(' ', $exc->dateTime(), 2);
        $content = $this->templatingEngine->render(
            'layouts/mainLayout.php', 
            [ 
                'title' => 'Update excursion',
                'authenticated' => $this->session->isAuthenticated(),
                'body' => $this->templatingEngine->render('templates/update_excursion_template.php', [
                    'messages' => $messages ?? [],
                    'id' => $exc->id(),
                    'title' => $exc->title(),
                    'description' => $exc->description(), 
                    'startingPoint' => $exc->startingPoint(), 
                    'destination' => $exc->destination(), 
                    'date' => $date,
                    'time' => $time, 
                    'price' => $exc->price() / 100
                ])
            ]
        );
        return new HTMLResponse($content);
    }

    private function handleGet(Request $request, Excursion $excursion): Response{
        return $this->renderTemplate($excursion);
    }

    private function handlePost(Request $request, Excursion $excursion): Response{
        
        $post = $request->post();

        if(isset($post['delete'])){
            $this->excursionRepository->deleteById($excursion->id());
            return new RedirectResponse('?controller=excursions');
        }

        $title = $post['title'] ?? '';
        $description = $post['description'] ?? '';
        $destination = $post['destination'] ?? '';
        $date = $post['date'] ?? '';
        $time = $post['time'] ?? '';
        $price = $post['price'] ?? '';
        $startingPoint = $post['startingPoint'] ?? '';

        \process_passed_parameters($errors, 
            $title,
            $description,
            $destination,
            $date,
            $time,
            $price,
            $startingPoint
        );

        if(empty($errors) === false){
            $messages = array_merge($errors);
        }else{
            $this->normalizer->date_to_YMD($date);
            $date_time = $date . ' ' . $time;
            if(\valid_date_time($date_time) === false){
                $messages[] = 'Datum i/ili vrijeme nisu valjani!';
            }else if(\is_string_number($price) === false){
                $messages[] = 'Cijena nije ispravna!';
            }else{
                $this->normalizer->normalize($description);

                $excursion->changeTitle($title);
                $excursion->changeDescription($description);
                $excursion->changeDestination($destination);
                $excursion->changeDateTime($date_time);
                $excursion->changePrice($price);
                $excursion->changeStartingPoint($startingPoint);

                $this->excursionRepository->update($excursion);

                return new RedirectResponse('?controller=update-excursion&excursionId=' . $excursion->id());
            }

            return $this->renderTemplate($excursion, $messages);
        }
    }
}
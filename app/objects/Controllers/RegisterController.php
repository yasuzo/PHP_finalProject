<?php

namespace Controllers;

use Services\{Templating, Session, UserRepository};
use Http\Responses\{HTMLResponse, RedirectResponse, Response};
use Models\User;
use Http\Request;

class RegisterController implements Controller{
    private $userRepository;
    private $templatingEngine;
    private $session;

    public function __construct(Templating $engine, Session $session, UserRepository $repository){
        $this->templatingEngine = $engine;
        $this->userRepository = $repository;
        $this->session = $session;
    }

    public function handle(Request $request): Response{
        $messages = [];
        $post = $request->post();

        if($this->session->isAuthenticated()){
            return new RedirectResponse('index.php');
        }
        if($request->method() === 'POST'){
            $username = $post['username'] ?? '';
            $firstName = $post['firstName'] ?? '';
            $lastName = $post['lastName'] ?? '';
            $pass1 = $post['pass1'] ?? '';
            $pass2 = $post['pass2'] ?? '';

            $errors = [];

            try{
                if(passed_value_is_array($username, $pass1, $pass2, $firstName, $lastName)){
                    throw new RuntimeException('Greska - Poslan je array!');
                }
                validate_name($firstName, $lastName, $errors);
                validate_username($username, $errors);
                validate_passwords($pass1, $pass2, $errors);
                username_taken($username, $this->userRepository, $errors);
                if(empty($errors) === false){
                    foreach($errors as $val){
                        $messages[] = $val;
                    }
                }else{
                    $pass1 = password_hash($pass1, PASSWORD_BCRYPT);
                    $this->userRepository->persist(new User($firstName, $lastName, $username, $pass1));
                    $user = $this->userRepository->findByUsername($username);
                    $this->session->setSessionProperty('user', $user->id());
                    return new RedirectResponse('index.php');
                }
            }catch(Exception $e){
                $messages[] = $e->getMessage();
            }
        }

        $content = $this->templatingEngine->render(
            'layouts/mainLayout.php', 
            [ 
                'title' => 'Registracija',
                'authenticated' => $this->session->isAuthenticated(),
                'body' => $this->templatingEngine->render(
                    'templates/register_template.php', 
                    [ 
                        'messages' => $messages,
                    ]
                )
            ]
        );

        return new HTMLResponse($content);
    }
}

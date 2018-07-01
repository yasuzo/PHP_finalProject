<?php

namespace Controllers;

use Http\Request;
use Services\{Templating, Session, UserRepository};
use Http\Responses\{HTMLResponse, RedirectResponse, Response};
use Models\User;

class SettingsController implements Controller{
    private $templatingEngine;
    private $session;
    private $userRepository;


    public function __construct(Templating $engine, Session $session, UserRepository $userRepository){
        $this->templatingEngine = $engine;
        $this->session = $session;
        $this->userRepository = $userRepository;
    }

    public function handle(Request $request): Response{
        if($this->session->isAuthenticated() === false){
            return new RedirectResponse('?controller=login');
        }

        $user = $this->userRepository->findById($this->session->getSessionProperty('user'));

        switch($request->method()){
            case 'POST':
                $response = $this->handlePost($request, $user);
                break;
            default:
                $response = $this->handleGet($request, $user);
        }
        return $response;
    }

    private function handleGet(Request $request, User $user): Response{
        return new HTMLResponse(
            $this->templatingEngine->render(
                'layouts/mainLayout.php', 
                [ 
                    'title' => 'Settings',
                    'authenticated' => $this->session->isAuthenticated(),
                    'body' => $this->templatingEngine->render('templates/settings_template.php', [
                        'messages' => [],
                        'firstName' => $user->firstName(),
                        'lastName'  => $user->lastName(),
                        'username'  => $user->username()
                    ])
                ]
            )
        );
    }

    private function handlePost(Request $request, User $user): Response{
        $post = $request->post();
        $get = $request->get();

        $messages = [];

        // DELETE ACCOUNT
        if(isset($post['delete'])){
            $this->userRepository->deleteById($user->id());
            $this->session->logout();
            return new RedirectResponse('index.php');
        }

        $change = $get['change'] ?? '';

        $errors = [];

        if($change === 'data'){
            $firstName = $post['firstName'] ?? '';
            $lastName = $post['lastName'] ?? '';
            $username = $post['username'] ?? '';

            if(passed_value_is_array($firstName, $lastName, $username)){
                $messages[] = 'Greska - Poslan je array!';
            }else{
                validate_name($firstName, $lastName, $errors);
                validate_username($username, $errors);
                if($user->username() !== $username){
                    username_taken($username, $this->userRepository, $errors);
                }

                if(empty($errors) === false){
                    $messages = array_merge($messages, $errors);
                }else{
                    $user->changeFirstName($firstName);
                    $user->changeLastName($lastName);
                    $user->changeUsername($username);
                    $this->userRepository->updateUser($user);
                    $messages[] = 'Podaci uspjesno promijenjeni!';
                }
            }
        }else if($change === 'password'){
            $oldPassword = $post['oldPassword'] ?? '';
            $newPassword1 = $post['newPassword1'] ?? '';
            $newPassword2 = $post['newPassword2'] ?? '';

            if(passed_value_is_array($oldPassword, $newPassword1, $newPassword2)){
                $messages[] = 'Greska - Poslan je array!';
            }else{
                if(password_verify($oldPassword, $user->password())){
                    validate_passwords($newPassword1, $newPassword2, $errors);

                    if(empty($errors) === false){
                        $messages = array_merge($messages, $errors);
                    }else{
                        $user->changePassword(password_hash($newPassword1, PASSWORD_BCRYPT));
                        $this->userRepository->updateUser($user);
                        $messages[] = 'Lozinka je uspjesno promijenjena!';
                    }
                }else{
                    $messages[] = 'Stara lozinka nije dobra!';
                }
            }
        }

        return new HTMLResponse(
            $this->templatingEngine->render(
                'layouts/mainLayout.php', 
                [ 
                    'title' => 'Settings',
                    'authenticated' => $this->session->isAuthenticated(),
                    'body' => $this->templatingEngine->render('templates/settings_template.php', [
                        'messages' => $messages,
                        'firstName' => $user->firstName(),
                        'lastName'  => $user->lastName(),
                        'username'  => $user->username()
                    ])
                ]
            )
        );
    }
}
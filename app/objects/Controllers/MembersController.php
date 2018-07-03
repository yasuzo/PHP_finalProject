<?php

namespace Controllers;

use Services\{Firewall, Session, UserRepository, Templating};
use Http\Request;
use Http\Responses\{Response, HTMLResponse, RedirectResponse};

use Models\User;

class MembersController implements Controller{
    private $templatingEngine;
    private $firewall;
    private $session;
    private $userRepository;

    public function __construct(Templating $engine, Session $session, UserRepository $userRepository, Firewall $firewall){
        $this->templatingEngine = $engine;
        $this->session = $session;
        $this->userRepository = $userRepository;
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


    private function renderTemplate(): Response{
        $content = $this->templatingEngine->render(
            'layouts/mainLayout.php', 
            [ 
                'title' => 'Index',
                'authenticated' => $this->session->isAuthenticated(),
                'body' => $this->templatingEngine->render('templates/members_template.php', [
                    'messages' => $messages ?? [],
                    'isSuperAdmin' => $this->firewall->hasAuthorizationLevel('superadmin'),
                    'isAdmin' => $this->firewall->hasAuthorizationLevel('admin'),
                    'users' => $this->userRepository->findAllButOne($this->session->getLoggedUserId() ?? '')
                ])
            ]
        );

        return new HTMLResponse($content);
    }

    private function handleGet(Request $request): Response{
        return $this->renderTemplate();
    }

    private function handlePost(Request $request): Response{
        $userId = $request->get()['userId'] ?? '';
        if(passed_value_is_array($userId)){
            return new RedirectResponse('?controller=members');
        }

        $user = $this->userRepository->findById($userId);
        
        if($user === null){
            return new RedirectResponse('?controller=members');
        }

        if($this->firewall->isAuthorizationHigher($this->firewall->getAuthorizationLevel(), $user->permission()) === false){
            return new RedirectResponse('?controller=members');
        }

        $post = $request->post();

        if(isset($post['delete'])){
            $this->userRepository->deleteById($userId);
        }

        if(isset($post['promoteToAdmin'])){
            $this->userRepository->updatePermission($userId, 'admin');
        }

        if(isset($post['promoteToSuperadmin']) && $this->firewall->hasAuthorizationLevel('superadmin')){
            $this->userRepository->updatePermission($userId, 'superadmin');
        }

        return new RedirectResponse('?controller=members');
    }
}
<?php

namespace Services;

use Models\User;

class Firewall{
    private $session;

    public function __construct(Session $session, UserRepository $userRepository){
        $this->session = $session;
        $this->userRepository = $userRepository;
    }

    public function hasAuthorizationLevel(string $level): bool{
        $user = $this->session->getSessionProperty('user');
        if($user == null){
            return false;
        }
        $user = $this->userRepository->findByUsername($user);
        return $user->permission() === $level;
    }

}
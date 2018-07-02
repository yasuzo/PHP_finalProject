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
        $user = $this->userRepository->findById($user);
        return $user->permission() === $level;
    }

    public function isAuthorizationHigher(string $level, string $levelToCompareTo): bool{
        if($level === 'regular' || ($level === $levelToCompareTo) || ($level === 'admin' && $levelToCompareTo === 'superadmin')){
            return false;
        }
        return true;
    }

    public function getAuthorizationLevel(): string{
        $user = $this->session->getSessionProperty('user');
        if($user == null){
            return false;
        }
        $user = $this->userRepository->findById($user);
        return $user->permission();
    }

}
<?php

namespace Services;

class Session{
    public function __construct(){
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
    }

    public function isAuthenticated(): bool{
        return isset($_SESSION['user']);
    }

    public function authenticate(string $key): void{
        $_SESSION['user'] = $key;
    }

    public function logout(): void{
        unset($_SESSION['user']);
    }

    public function destroySessionProperty(string $key): void{
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
        }
    }

    public function getSessionProperty(string $key){
        return $_SESSION[$key] ?? null;
    }

    public function setSessionProperty(string $key, $value): void{
        $_SESSION[$key] = $value;
    }
}
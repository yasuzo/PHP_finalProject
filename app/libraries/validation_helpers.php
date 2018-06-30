<?php

declare(strict_types=1);

require_once 'helper_functions.php';

use Services\UserRepository;

define('MIN_LEN_USER', 1);
define('MAX_LEN_USER', 24);


function validate_name(string $firstName, $lastName, array &$errors){
    $regex = "/^[a-zA-Z\p{L}]{1,25}$/u";
    if((bool)preg_match($regex, $firstName) === false || (bool)preg_match($regex, $lastName) === false){
        array_push($errors, "Ime i prezime smije sadrzavati samo slova i mora biti dugacko od 1 do 25 slova!");
        return false;
    }
    return true;
}

function validate_username(string $username, array &$errors): bool{
    $regex = "/^(?=[\w\-]*[a-zA-Z\p{L}])[\w\-]{".MIN_LEN_USER.",".MAX_LEN_USER."}$/u";
    if((bool)preg_match($regex, $username) === false){
        array_push($errors, "Username nije valjan!");
        return false;
    }
    return true;
}

function validate_passwords(string $pass1, string $pass2, array &$errors): bool{
    if(mb_strlen($pass1) < 12){
        array_push($errors, "Password nije valjan!");
        return false;
    }else if($pass1 !== $pass2){
        array_push($errors, "Passwordi nisu isti!");
        return false;
    }
    return true;
}

function username_taken(string $username, UserRepository $userRepository, array &$errors): bool{
    if($userRepository->findByUsername($username) !== null){
        array_push($errors, "Username vec postoji!");
        return true;
    }
    return false;
}
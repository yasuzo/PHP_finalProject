<?php

declare(strict_types=1);

namespace Models;

class User{
    private $id;
    private $firstName;
    private $lastName;
    private $username;
    private $password;
    private $permission;

    public function __construct(string $firstName, string $lastName, string $user, string $pass, string $permission = 'regular', $id = null){
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $user;
        $this->password = $pass;
        $this->permission = $permission;
    }

    public function username(): string{
        return $this->username;
    }

    public function changeUsername(string $username): void{
        $this->username = $username;
    }

    public function password(): string{
        return $this->password;
    }

    public function changePassword(string $password): void{
        $this->password = $password;
    }

    public function permission(): string{
        return $this->permission;
    }

    public function changePermission(string $permission): void{
        $this->permission = $permission;
    }

    public function firstName(): string{
        return $this->firstName;
    }

    public function changeFirstName(string $name): void{
        $this->firstName = $name;
    }

    public function lastName(): string{
        return $this->lastName;
    }

    public function changeLastName(string $name): void{
        $this->lastName = $name;
    }

    public function id(){
        return $this->id;
    }
}
<?php

namespace Services;

use Models\User;

class UserRepository{
    private $baza;

    public function __construct(\PDO $db){
        $this->baza = $db;
    }

    public function findByUsername(string $username): ?User{
        $query = <<<SQL
        select id, firstName, lastName, username, pass, permission
        from users
        where username=:user;
SQL;
        $query = $this->baza->prepare($query);
        $query->execute([':user' => $username]);
        if(($user = $query->fetch()) === false){
            return null;
        }
        $user = new User($user['firstName'], $user['lastName'], $user['username'], $user['pass'], $user['permission'], $user['id']);
        return $user;
    }

    public function findById($id): ?User{
        $query = <<<SQL
        select id, firstName, lastName, username, pass, permission
        from users
        where id=:id;
SQL;
        $query = $this->baza->prepare($query);
        $query->execute([':id' => $id]);
        if(($user = $query->fetch()) === false){
            return null;
        }
        $user = new User($user['firstName'], $user['lastName'], $user['username'], $user['pass'], $user['permission'], $user['id']);
        return $user;
    }

    public function findAll(): Array{
        $query = <<<SQL
        select id, firstName, lastName, username, pass, permission
        from users
SQL;
        $query = $this->baza->query($query);
        return $query->fetchAll() ?: [];
    }

    public function persist(User $user): void{
        $query = <<<SQL
        insert into users
        (firstName, lastName, username, pass, permission) values
        (:firstName, :lastName, :user, :pass, :permission);
SQL;
        $query = $this->baza->prepare($query);
        $query->execute([':firstName' => $user->firstName(), ':lastName' => $user->lastName(), ':user' => $user->username(), ':pass' => $user->password(), ':permission' => $user->permission()]);
    }

    // Needs to be removed from this class
    public function credentialsOK(string $username, string $password): bool{
        if(($user = $this->findByUsername($username)) === null){
            return false;
        }
        return password_verify($password, $user->password());
    }

    public function deleteById($id){
        $query = <<<SQL
        delete from users
        where id=:id;
SQL;

        $query = $this->baza->prepare($query);
        $query->execute([':id' => $id]);
    }

    public function updateUser(User $user){
        $query = <<<SQL
        update users
        set firstName=:firstName, lastName=:lastName, username=:username, pass=:pass, permission=:permission
        where id=:id;
SQL;
        $query = $this->baza->prepare($query);
        $query->execute(
            [
                ':firstName' => $user->firstName(), 
                ':lastName' => $user->lastName(), 
                ':username' => $user->username(), 
                ':pass' => $user->password(),
                ':permission' => $user->permission(),
                ':id' => $user->id()
            ]
        );
    }

    public function updatePermission($id, string $permission): void{
        $query = <<<SQL
        update users
        set permission=:permission
        where id=:id;
SQL;
        $query = $this->baza->prepare($query);
        $query->execute(
            [
                ':permission' => $permission,
                ':id' => $id
            ]
        );
    }

    public function findAllButOne($id): array{
        $query = <<<SQL
        select id, firstName, lastName, username, pass, permission
        from users
        where id!=:id;
SQL;
        $query = $this->baza->prepare($query);
        $query->execute([':id' => $id]);
        return $query->fetchAll() ?: [];
    }
}
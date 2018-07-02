<?php

namespace Services;

use Models\Excursion;

class ExcursionRepository{
    private $baza;

    public function __construct(\PDO $db){
        $this->baza = $db;
    }

    public function findById($id): ?Excursion{
        $query = <<<SQL
        select id, title, description, startingPoint, destination, DATE_FORMAT(date_time, "%d.%m.%Y %H:%i") date_time, price
        from excursions
        where id=:id;
SQL;
        $query = $this->baza->prepare($query);
        $query->execute([':id' => $id]);
        $query = $query->fetch();

        if($query === false){
            return null;
        }

        $excursion = new Excursion(
            $query['title'], 
            $query['description'], 
            $query['startingPoint'], 
            $query['destination'], 
            $query['date_time'], 
            $query['price'],
            $query['id']
        );
        return $excursion;
    }

    public function deleteById($id): void{
        $query = <<<SQL
        delete from excursions
        where id=:id;
SQL;

        $query = $this->baza->prepare($query);
        $query->execute([':id' => $id]);
    }

    public function persist(Excursion $exc): void{
        $query = <<<SQL
        insert into excursions
        (title, description, startingPoint, destination, date_time, price) values
        (:title, :description, :startingPoint, :destination, :date_time, :price);
SQL;
        $query = $this->baza->prepare($query);
        $query->execute([
            ':title' => $exc->title(),
            ':description' => $exc->description(), 
            ':startingPoint' => $exc->startingPoint(), 
            ':destination' => $exc->destination(), 
            ':date_time' => $exc->dateTime(), 
            ':price' => $exc->price()
        ]);
    }

    public function update(Excursion $exc): void{
        $query = <<< SQL
        update excursions
        set title=:title, description=:description, startingPoint=:startingPoint, destination=:destination, date_time=:date_time, price=:price
        where id=:id;
SQL;

        $query = $this->baza->prepare($query);
        $query->execute([
            ':id' => $exc->id(),
            ':title' => $exc->title(),
            ':description' => $exc->description(), 
            ':startingPoint' => $exc->startingPoint(), 
            ':destination' => $exc->destination(), 
            ':date_time' => $exc->dateTime(), 
            ':price' => $exc->price()
        ]);
    }

    public function findAll(): array{
        $query = <<<SQL
        select id, title, description, startingPoint, destination, DATE_FORMAT(date_time, "%d.%m.%Y %H:%i") date_time, price
        from excursions
        order by excursions.date_time desc
SQL;
        $query = $this->baza->query($query);
        return $query->fetchAll() ?: [];
    }
}
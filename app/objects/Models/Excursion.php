<?php

namespace Models;

class Excursion{
    private $id;
    private $title;
    private $description;
    private $destination;
    private $date_time;
    private $price;
    private $startingPoint;

    public function __construct(string $title, string $description, string $startingPoint, string $destination, $date_time, int $price, $id = null){
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->destination = $destination;
        $this->date_time = $date_time;
        $this->price = $price;
        $this->startingPoint = $startingPoint;
    }

    public function startingPoint(): string{
        return $this->startingPoint;
    }

    public function changeStartingPoint(string $startingPoint): void{
        $this->startingPoint = $startingPoint;
    }

    public function id(): int{
        return $this->id;
    }

    public function title(): string{
        return $this->title;
    }

    public function changeTitle(string $title): void{
        $this->title = $title;
    }

    public function description(): string{
        return $this->description;
    }

    public function changeDescription(string $description): void{
        $this->description = $description;
    }

    public function destination(): string{
        return $this->destination;
    }

    public function changeDestination(string $destination): void{
        $this->destination = $destination;
    }

    public function dateTime(){
        return $this->date_time;
    }

    public function changeDateTime($date_time): void{
        $this->date_time = $date_time;
    }

    public function price(): int{
        return $this->price;
    }

    public function changePrice(float $price){
        $this->price = (int)($price * 100);
    }


}
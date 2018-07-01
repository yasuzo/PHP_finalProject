<?php

namespace Models;

class News{
    private $id;
    private $title;
    private $content;
    private $creator;
    private $time;

    public function __construct(string $title, string $content, int $creator, $time = null, int $id=null){
        $this->title = $title;
        $this->content = $content;
        $this->creator = $creator;
        $this->time = $time ?? date('Y-m-d H:i:s');
        $this->id = $id;
    }

    public function title(): string{
        return $this->title;
    }

    public function changeTitle(string $title): void{
        $this->title = $title;
    }

    public function content(): string{
        return $this->content;
    }

    public function changeContent(string $content): void{
        $this->content = $content;
    }

    public function creator(): string{
        return $this->creator;
    }

    public function time(){
        return $this->time;
    }

    public function id(){
        return $this->id;
    }
}
<?php

namespace Models;

class News{
    private $title;
    private $content;
    private $creator;
    private $time;

    public function __construct(string $title, string $content, int $creator, $time = null){
        $this->title = $title;
        $this->content = $content;
        $this->creator = $creator;
        $this->time = $time ?? date('Y-m-d H:i:s');
    }

    public function title(): string{
        return $this->title;
    }

    public function content(): string{
        return $this->content;
    }

    public function creator(): string{
        return $this->creator;
    }

    public function time(){
        return $this->time;
    }
}
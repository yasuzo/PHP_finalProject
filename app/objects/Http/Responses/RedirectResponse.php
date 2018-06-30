<?php

namespace Http\Responses;

class RedirectResponse implements Response {
    private $location;

    public function __construct(string $location){
        $this->location = $location;
    }

    public function send(): void {
        header('Location: ' . $this->location);
        die();
    }
}
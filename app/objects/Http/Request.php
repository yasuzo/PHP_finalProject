<?php

namespace Http;

class Request{
    private $method;
    private $httpReferer;
    private $get;
    private $post;
    private $files;

    public function __construct(string $method, ?string $httpReferer, array $get, array $post, array $files){
        $this->method = $method;
        $this->httpReferer = $httpReferer;
        $this->get = $get;
        $this->post = $post;
        $this->files = $files;
    }

    public function method(): string{
        return $this->method;
    }

    public function httpReferer(): ?string{
        return $this->httpReferer;
    }

    public function get(): array{
        return $this->get;
    }

    public function post(): array{
        return $this->post;
    }

    public function files(): array{
        return $this->files;
    }
}
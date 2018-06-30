<?php

declare(strict_types=1);

namespace Services;

class Templating{
    private $templatesRoot;

    public function __construct(string $root){
        $this->templatesRoot = $root;
    }

    public function render(string $file, array $args = null): string {
        ob_start();
        
        extract($args);

        require_once $this->templatesRoot.$file;

        return ob_get_clean();
    }
}
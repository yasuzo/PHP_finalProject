<?php

namespace Exceptions;

class FileTooLargeException extends RuntimeException{

    public function __construct(string $message, int $code = 0, Exception $previous = NULL){
        parent::__construct($message, $code, $previous);
    }
}
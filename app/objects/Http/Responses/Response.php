<?php

namespace Http\Responses;

interface Response{
    public function send(): void;
}
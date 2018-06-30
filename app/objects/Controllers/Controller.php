<?php

namespace Controllers;

use Http\Request;
use Http\Responses\Response;

interface Controller{
    public function handle(Request $request): Response;
}
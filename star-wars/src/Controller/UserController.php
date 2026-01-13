<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class UserController extends AbstractController{

    public function create(): Response {
        return new Response("");
    }
    public function read(): Response {
        return new Response("");
    }
    public function update(): Response {
        return new Response("");
    }
    public function delete(): Response {
        return new Response("");
    }
}
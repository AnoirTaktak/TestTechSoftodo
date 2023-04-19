<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class LoginPageControlleur extends AbstractController {

  
    #[Route(path:'/',methods:['GET'])]
    function login () : Response {

        return $this->render('Login.html');
   }
   
}

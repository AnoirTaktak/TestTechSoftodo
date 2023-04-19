<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageControlleur extends AbstractController {

   
    function index () : Response {
        return $this->render('HomePage.html');
   }
   
}

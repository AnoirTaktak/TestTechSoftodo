<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserControlleur extends AbstractController
{
    private $manager;

    private $user;

    public function __construct(EntityManagerInterface $manager, UserRepository $user)
    {
         $this->manager=$manager;
         $this->user=$user;
    }
    
    
    //Create user
    #[Route('/userCreate', name: 'user_create', methods:'POST')]
    public function userCreate(Request $request): Response
    {

       $data=json_decode($request->getContent(),true);


       $username=$data['username'];

       $password=$data['password'];

       $role=$data['role'];
       //verify if the username exist

       $username_exist=$this->user->findOneBy(['username'=>$username]);

       if(!empty($username_exist))
       {
          return new JsonResponse
          (
              [
                'status'=>false,
                'message'=>'this username is already exist'
              ]

              );
       }

       else
       {
          $user= new User();

          $user->setUsername($username)
               ->setPassword($password)->setRole($role);

         $this->manager->persist($user);
   
         $this->manager->flush();

         return new JsonResponse
         (
             [
               'status'=>true,
               'message'=>'User Created'
             ]

             );
       }



        
    }

    /*
    //the bundle do the work here
     //Auth user
    #[Route('/', name: 'Auth_user', methods:'GET')]
    public function auth(Request $request): Response
    {
      //$data=json_decode($request->getContent(),true);
        $username = $request->query->get("username"," ");
        $password= $request->query->get("password"," ");
        $users=$this->user->findOneBy(['username'=>$username]);
        if (empty($users)) {
            return new JsonResponse
          (
              [
                'status'=>false,
                'message'=>'this username dosent exist'
              ]

              );
        }elseif(!empty($users) and sha1($password)!=$users->getPassword()){
            return new JsonResponse
          (
              [
                'status'=>false,
                'message'=>'wrong password'
              ]

              );
        }else{
            return new JsonResponse
          (
              [
                'status'=>true,
                'message'=>'hello'
              ]
            );
        }
        
    }*/
}
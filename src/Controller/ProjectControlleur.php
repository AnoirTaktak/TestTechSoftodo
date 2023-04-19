<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectControlleur extends AbstractController
{
    private $manager;

    private $project;

    public function __construct(EntityManagerInterface $manager, ProjectRepository $project)
    {
         $this->manager=$manager;
         $this->project=$project;
    }

    //CRUD START HERE
    
    //Create Project
    #[Route('/CreateProject', name: 'create_Project', methods:'POST')]
    public function CreateProject(Request $request): Response
    {

       $data=json_decode($request->getContent(),true);


       $title=$data['title'];
       $image=$data['image'];
       $filename=$data['filename'];
       $notasks=$data['notasks'];
       $description=$data['description'];
       $status=$data['status'];

          $project= new Project();

          $project->setTitle($title)
               ->setFilename($filename)->setImage($image)
               ->setNotasks($notasks)->setDescription($description)->setStatus($status);

         $this->manager->persist($project);
   
         $this->manager->flush();

         return new JsonResponse
         (
             [
               'status'=>true,
               'message'=>'New Project Added'
             ]
             );
        
    }

     //No filtre
    #[Route('/getAllProjects', name: 'getallProjects', methods:'GET')]
    public function getAllProjects(): Response
    {
        $Project=$this->project->findAll();

        return $this->json($Project,200);
    }

    
     //Filtre 
     #[Route('/getProjectsByName', name: 'get_Projects_By_Name', methods:'GET')]
     public function getProjectsByName(Request $request): Response
     {  
        $title = $request->query->get('title');
        $status = $request->query->get('status');
        //$filename = $request->query->get('filename');
    
        
        
        
    
        if (!empty($title)) {
            $projects = $this->project->findBy(["title"=>$title]);
        }
    
        if (!empty($status)) {
            
            $projects = $this->project->findBy(["status"=>$status]);
        }
        
        //error 
        //if (!empty($filename)) {
        //    $projects = $this->project->findBy(["filename"=>$filename]);
        //}
    
        
        
        
    
        return $this->json($projects);
     }

   // Update Project
#[Route('/UpdateProject/{title}', name: 'update_Project', methods: ['PUT'])]
public function UpdateProject(Request $request, ProjectRepository $projectRepository, $title): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    $project = $projectRepository->findOneBy(["title"=>$title]);

    if (!$project) {
        return new JsonResponse([
            'status' => false,
            'message' => 'Project not found'
        ]);
    }

    $title = $data['title'] ?? $project->getTitle();
    $image = $data['image'] ?? $project->getImage();
    $filename = $data['filename'] ?? $project->getFilename();
    $notasks = $data['notasks'] ?? $project->getNotasks();
    $description = $data['description'] ?? $project->getDescription();
    $status = $data['status'] ?? $project->getStatus();

    $project->setTitle($title)
            ->setImage($image)
            ->setFilename($filename)
            ->setNotasks($notasks)
            ->setDescription($description)
            ->setStatus($status);

    $this->manager->flush();

    return new JsonResponse([
        'status' => true,
        'message' => 'Project updated successfully'
    ]);
}

#[Route('/DeleteProject/{title}', name: 'delete_Project', methods: ['DELETE'])]
public function DeleteProject(EntityManagerInterface $entityManager, $title): JsonResponse
{
    // Retrieve the project to delete from the database
    $project = $entityManager->getRepository(Project::class)->findOneBy(["title"=>$title]);

    if (!$project) {
        return new JsonResponse([
            'status' => false,
            'message' => 'Project not found'
        ], Response::HTTP_NOT_FOUND);
    }

    // Remove the project from the database
    $entityManager->remove($project);
    $entityManager->flush();

    return new JsonResponse([
        'status' => true,
        'message' => 'Project deleted successfully'
    ]);
}


}
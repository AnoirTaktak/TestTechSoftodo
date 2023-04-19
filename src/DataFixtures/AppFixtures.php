<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{

    
    public function load(ObjectManager $manager): void
    {
        $statuses = ['in progress', 'done', 'blocked'];
        $randomStatus = $statuses[array_rand($statuses)];
        for ($i=1; $i < 10 ; $i++) { 
            $project = new Project();
            $project->setDescription('this is a good project')->
            setFilename('url')->
            setImage('https://th.bing.com/th/id/OIP.hcOMhEhQzt5j703SdfHlHQAAAA?pid=ImgDet&rs=1')->
            setNotasks(' '.$i)->
            setStatus($randomStatus)->setTitle('project '.$i);
            $manager->persist($project);
        }

        $user = new User();
        $user->setUsername('admin')->
        setPassword(sha1('admin') )->
        setRole('admin');
        $manager->persist($user);

        $user1 = new User();
        $user1->setUsername('normal')->
        setPassword(sha1('normal') )->
        setRole('normal');
        $manager->persist($user1);

        $manager->flush();
    }
}

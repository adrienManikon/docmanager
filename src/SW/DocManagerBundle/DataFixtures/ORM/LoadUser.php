<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SW\DocManagerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SW\DocManagerBundle\Entity\User;

/**
 * Description of LoadUser
 *
 * @author adrien.manikon
 */
class LoadUser implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $users = array(
        array(
            'firstname' => 'Adrien',
            'lastname' => 'Manikon',
            'initial' => 'AM'
        ),
    );
    
    foreach ($users as $userData) {
        
        $user = new User();
        $user->setFirstname($userData['firstname']);
        $user->setLastname($userData['lastname']);
        $user->setInitial($userData['initial']);
        
        $manager->persist($user);
        
    }    
                
    $manager->flush();
  }       
    
}

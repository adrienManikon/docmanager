<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SW\DocManagerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SW\DocManagerBundle\Entity\Category;
use SW\DocManagerBundle\Entity\User;

/**
 * Description of SearchController
 *
 * @author adrien.manikon
 */
class SearchController extends AbstractController {
    
    public function SearchAction(Request $request) {
        
        if (!$this->isMethodPost($request)) {
            
            $repoCat = $this->getRepository('SWDocManagerBundle:Category');
            $repoUser = $this->getRepository('SWDocManagerBundle:User');
            
            $mainCategories = $repoCat->findByMain(true);
            $subsubcategories = $repoCat->getSubSubCategories();
            $subCategories = $repoCat->findBy(array(
                'main' => false,
                'parent' => null
            ));
            $users = $repoUser->findAll();
            
            $noneCategory = new Category();
            $noneCategory->setName("Keine");
            $noneCategory->setParent($noneCategory);
            
            $noneUser = new User();
            $noneUser->setLastname("Keine");
                        
            array_unshift($mainCategories, $noneCategory);
            array_unshift($subsubcategories, $noneCategory);
            array_unshift($users, $noneUser);
            
            return $this->render('SWDocManagerBundle:Search:search.html.twig', array(
                'maincategories' => $mainCategories,
                'subcategories' => $subCategories,
                'subsubcategories' => $subsubcategories,
                'users' => $users
            ));
            
        }
        
    }
}

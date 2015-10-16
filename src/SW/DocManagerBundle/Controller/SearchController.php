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
use Symfony\Component\HttpFoundation\JsonResponse;
use SW\DocManagerBundle\Entity\DocumentRepository;

/**
 * Description of SearchController
 *
 * @author adrien.manikon
 */
class SearchController extends AbstractController {
    
    public function SearchAction(Request $request) {
        
        if (!$this->isMethodPost($request)) {
                        
            return $this->searchPage();
            
        } else {
            
            return $this->getResults($request);            
            
        }
    }
    
    private function searchPage() {
        
        $repoCat = $this->getRepository('SWDocManagerBundle:Category');
        $repoUser = $this->getRepository('SWDocManagerBundle:User');

        $mainCategories = $repoCat->findByMain(true);
        $subsubcategories = $repoCat->getSubSubCategories();
        $subCategories = $repoCat->findBy(array(
            'main' => false,
            'parent' => null
        ));
        $users = $repoUser->findAll();

        return $this->render('SWDocManagerBundle:Search:search.html.twig',
               $this->prepareDataForRender($mainCategories, $subCategories, $subsubcategories, $users));        
    }

    private function getResults(Request $request) {
        
        $filters = $this->getFilters($request);
        $page = $request->request->get('page') != null ? $request->request->get('page') : 1;
       
        if ($this->hasFilters($filters)) {
            $repoDocument = $this->getRepository("SWDocManagerBundle:Document");
            $results = $repoDocument->search($filters['nameCode'],
                    $filters['dateStart'],
                    $filters['dateEnd'],
                    $filters['code'],
                    $filters['initial'],
                    DocumentRepository::nbFirstResult($page));
        } else {
            $results = array('count' => 0, 'documents' => array());
        }

        $documentsJson = $this->encodeJson($results['documents']);
        $nbPage = DocumentRepository::getNbPages($results['count']);

        return new JsonResponse(array(
            'documents' => json_decode($documentsJson),
            'pages' => $nbPage,
            'pageCurrent' => $page
    ));        
    }

    private function buildCode($codes) {
        
        $code = '';
        foreach ($codes as $codeLetter) {
            
            if ($codeLetter != null)
                $code .= $codeLetter;
            
        }
        
        return $code;
    }
    
    private function getFilters(Request $request) {
        
        return array(            
            "nameCode" => $request->request->get("request"),
            "dateStart" => $request->request->get("dateStart"),
            "dateEnd" => $request->request->get("dateEnd"),
            "code" => $this->buildCode(array($request->request->get("category"),
                $request->request->get("subcategory1"),
                $request->request->get("subcategory2"),
                $request->request->get("subcategory3"))),
            "initial" => $request->request->get("creator"),            
        );
        
    }
    
    private function hasFilters($filters) {
                
        foreach ($filters as $filter) {
            
            if ($filter != null)
                return true;
            
        }
        
        return false;
        
        
    }
    
    private function prepareDataForRender($mainCategories, $subCategories, $subsubcategories, $users) {
        
        $noneCategory = new Category();
        $noneCategory->setName("Keine");
        $noneCategory->setParent($noneCategory);

        $noneUser = new User();
        $noneUser->setLastname("Keine");
        
        array_unshift($mainCategories, $noneCategory);
        array_unshift($subsubcategories, $noneCategory);
        array_unshift($users, $noneUser);
        
        return array(
            'maincategories' => $this->buildCategoriesForView($mainCategories, "", "checked"),
            'subcategories' => $subCategories,
            'subsubcategories' => $this->buildCategoriesForView($subsubcategories, "default-select", "selected"),
            'users' => $users
        );
        
    }
    
    private function buildCategoriesForView($categories, $class, $attribut) {
        
        foreach ($categories as $category) {
            
            if ($category->getId() != null) {
                
                if ($category->getParent() != null) {
                    $category->setClasse($category->getParent()->getName());
                    $category->setAttribut("");
                } else {
                    $category->setClasse("");
                }
                $category->setAttribut("");
            } else {
                $category->setClasse($class);
                $category->setAttribut($attribut);                
            }
            
        }
        
        return $categories;
        
    }
}

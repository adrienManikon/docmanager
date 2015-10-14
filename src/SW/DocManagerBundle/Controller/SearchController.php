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
            
        } else {
            
            $nameCode = $request->request->get("request");
            $dateStart = $request->request->get("dateStart");
            $dateEnd = $request->request->get("dateEnd");
            $code = $this->buildCode(array($request->request->get("category"),
                    $request->request->get("subcategory1"),
                    $request->request->get("subcategory2"),
                    $request->request->get("subcategory3")));
            $initial = $request->request->get("creator");
            $page = $request->request->get('page') != null ? $request->request->get('page') : 1;
            
            $repoDocument = $this->getRepository("SWDocManagerBundle:Document");
            
            $results = $repoDocument->search($nameCode,
                    $dateStart,
                    $dateEnd,
                    $code,
                    $initial,
                    DocumentRepository::nbFirstResult($page));

            $documentsJson = $this->encodeJson($results['documents']);
            $nbPage = DocumentRepository::getNbPages($results['count']);
            
            return new JsonResponse(array(
                'documents' => json_decode($documentsJson),
                'pages' => $nbPage,
                'pageCurrent' => $page
        ));
            
        }
    }
    
    private function buildCode($codes) {
        
        $code = '';
        foreach ($codes as $codeLetter) {
            
            if ($codeLetter != null)
                $code .= $codeLetter;
            
        }
        
        return $code;
    }
}

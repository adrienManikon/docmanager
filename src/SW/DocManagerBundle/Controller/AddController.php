<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SW\DocManagerBundle\Controller;

use SW\DocManagerBundle\Entity\Document;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Description of AddController
 *
 * @author adrien.manikon
 */
class AddController extends Controller
{
    
    /**
     *
     * @var Request 
     */
    private $request;
    
    public function categoryListAction(Request $request)
    {        
        $this->request = $request;
        
        if ($this->isMethodPost()) {   
            $category = $request->request->get("category");
            $subcategory1 = $request->request->get("select1");
            $subcategory2 = $request->request->get("select2");
            $subcategory3 = $request->request->get("select3");
            return $this->redirect($this->generateUrl('sw_doc_manager_upload_view', array(
                'category' => $category,
                'subcategory1' => $subcategory1,
                'subcategory2' => $subcategory2,
                'subcategory3' => $subcategory3,
            )));
        }
        
        return $this->render('SWDocManagerBundle:Add:categorylist.html.twig');
    }
    
    public function uploadViewAction($category, $subcategory1, $subcategory2, $subcategory3)
    {        
        return $this->render('SWDocManagerBundle:Add:upload.html.twig', array(
            'category' => $category,
            'subcategory1' => "madera",
            'subcategory2' => "Wämeschutz",
            'subcategory3' => "technische",
        ));
    }
    
    public function recapAction()
    {
        return $this->render('SWDocManagerBundle:Add:recap.html.twig');
    } 
    
    /**
     * if request is method POST
     * 
     * @return boolean
     */
    public function isMethodPost() {
        return $this->request->isMethod("POST");
    }
}

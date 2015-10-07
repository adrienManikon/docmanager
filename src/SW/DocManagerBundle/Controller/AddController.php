<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SW\DocManagerBundle\Controller;

use SW\DocManagerBundle\Entity\Document;
use SW\DocManagerBundle\Entity\UploadSession;
use SW\DocManagerBundle\Form\UploadSessionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use DateTime;


/**
 * Description of AddController
 *
 * @author adrien.manikon
 */
class AddController extends Controller
{
        
    private $uploadSession;
    
    private $category;
    
    private $subcategory1;
    
    private $subcategory2;
    
    private $subcategory3;
    /**
     *
     * @var Request 
     */
    private $request;
    
    public function categoryListAction(Request $request)
    {        
        $this->request = $request;
        
        if ($this->isMethodPost()) {
            
            $this->category = $request->request->get("category");
            $this->subcategory1 = $request->request->get("select1");
            $this->subcategory2 = $request->request->get("select2");
            $this->subcategory3 = $request->request->get("select3");
            return $this->uploadViewAction($request);
            
        }
        
        return $this->render('SWDocManagerBundle:Add:categorylist.html.twig');
    }
    
    public function uploadViewAction(Request $request)
    {       
        
        $uploadSession = new UploadSession();
        $document = new Document();
        $document->setCode("DEEC2A");
        $document->setDate(new DateTime("2012-07-08"));
        /* TEST */
        $document->setName("test.pdf");
        $document->setInitials("AM");        
        $uploadSession->getDocuments()->add($document);
        
        $form = $this->createForm(new UploadSessionType(), $uploadSession);
        
        if ($form->handleRequest($request)->isValid()) {
            
            $this->uploadSession = $uploadSession;
            return $this->recapAction();
            
        }
        
        return $this->render('SWDocManagerBundle:Add:upload.html.twig', array(
            'category' => $this->category,
            'subcategory1' => "madera",
            'subcategory2' => "Wämeschutz",
            'subcategory3' => "technische",
            'form' => $form->createView(),
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

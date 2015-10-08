<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SW\DocManagerBundle\Controller;

use SW\DocManagerBundle\Entity\Document;
use SW\DocManagerBundle\Form\CategoryType;
use SW\DocManagerBundle\Entity\UploadSession;
use SW\DocManagerBundle\Form\UploadSessionType;
use SW\DocManagerBundle\Form\DocumentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \Doctrine\Common\Collections\ArrayCollection;
use DateTime;


/**
 * Description of AddController
 *
 * @author adrien.manikon
 */
class AddController extends Controller
{
        
    private $uploadSession;        
    private $form;
    
    /**
     *
     * @var Request 
     */
    private $request;
    
    public function categoryListAction($status = null, Request $request)
    {        
        $this->request = $request;
        
        $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('SWDocManagerBundle:Category');
        
        if ($this->isMethodPost()) {
            $this->uploadSession = new UploadSession();
            
            if ($request->request->get("category") != null 
                    && $request->request->get("select1") != null
                    && $request->request->get("select2") != null
                    && $request->request->get("select3") != null)
            {
                $category = $repository->find($request->request->get("category"));
                $subcategory1 = $repository->find($request->request->get("select1"));
                $subcategory2 = $repository->find($request->request->get("select2"));
                $subcategory3 = $repository->find($request->request->get("select3"));


                $this->uploadSession->setCategory($category);
                $this->uploadSession->setSubcategory1($subcategory1);
                $this->uploadSession->setSubcategory2($subcategory2);
                $this->uploadSession->setSubcategory3($subcategory3);
            }
            
            return $this->uploadViewAction($request);
            
        }
       
        $mainCategories = $repository->findByMain(true);
        $subsubcategories = $repository->getSubSubCategories();
        $subCategories = $repository->findBy(array(
            'main' => false,
            'parent' => null
        ));
        
        return $this->render('SWDocManagerBundle:Add:categorylist.html.twig', array(
            'status' => $status,
            'maincategories' => $mainCategories,
            'subcategories' => $subCategories,
            'subsubcategories' => $subsubcategories,
                ));
    }
    
    public function buildSubCategories($subsubcategories) {
        
        $subCategories = array();
        
        foreach ($subsubcategories as $subsubcategory) {
            
            $subCategories[] = $subsubcategory->getParent();
            
        }
        
        return $subCategories;
        
    }
    
    public function uploadViewAction(Request $request)
    {       
                
        $document = new Document();
        $document->setCode($this->uploadSession->getCode());
        $document->setDate(new DateTime("2012-07-08"));
        
        /* TEST */
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('SWDocManagerBundle:User');
        
        $user = $repository->findByLastname('Manikon')[0];
        
        $document->setName("test.pdf");
        $document->setInitials($user->getInitial());
        $document->setCreator($user);
        
        $this->uploadSession->getDocuments()->add($document);
        
        $form = $this->createForm(new UploadSessionType(), $this->uploadSession);
        
        if ($form->handleRequest($request)->isValid()) {
            
            $this->form = $form;
            $this->uploadDocuments(true, $this->uploadSession);           
            
            return $this->recapAction($request);
            
        }
        
        return $this->render('SWDocManagerBundle:Add:upload.html.twig', array(
            'form' => $form->createView(),
        ));
    }
            
    public function recapAction(Request $request)
    {        
       
        $form = $this->get('form.factory')->createBuilder('form', $this->uploadSession)
            ->setAction($this->generateUrl('sw_doc_manager_publish'))
            ->add('documents', 'collection', array(
                'type' => new DocumentType(),
                'allow_add' => true,
                ))
            ->add('category', new CategoryType())
            ->add('subcategory1', new CategoryType())
            ->add('subcategory2', new CategoryType())
            ->add('subcategory3', new CategoryType())
            ->add('Veröffentlichen', 'submit');
        
        return $this->render('SWDocManagerBundle:Add:recap.html.twig', array(
            'form' => $form->getForm()->createView(),
        ));
    } 
    
    public function publishAction(Request $request)
    {
        $this->request = $request;
        
        if ($this->isMethodPost()) {
            
            $uploadSession = new UploadSession();
            
            $form = $this->get('form.factory')->createBuilder('form', $uploadSession)
                ->setAction($this->generateUrl('sw_doc_manager_publish'))
                ->add('documents', 'collection', array(
                    'type' => new DocumentType(),
                    'allow_add' => true,
                    ))
                ->add('category', new CategoryType())
                ->add('subcategory1', new CategoryType())
                ->add('subcategory2', new CategoryType())
                ->add('subcategory3', new CategoryType())
                ->add('Veröffentlichen', 'submit')
                ->getForm();
            
            $form->handleRequest($request);
            
            if ($uploadSession != null) {
                $this->uploadDocuments(false, $uploadSession);                                
                $parameter = array('status' => 'success');
            } else {      
                $parameter = array('status' => 'failed');
            }
            
            return $this->redirect($this->generateUrl('sw_doc_manager_add', $parameter));            
        }
    }
    
    /**
     * if request is method POST
     * 
     * @return boolean
     */
    public function isMethodPost() {
        return $this->request->isMethod("POST");
    }
    
    public function uploadDocuments($temporary, UploadSession $uploadSession) {
        
        if (!$temporary)
            $em = $this->getDoctrine()->getManager();
        
        foreach ($uploadSession->getDocuments() as $document) {
            
            $document->setCategory($uploadSession->getCategory());
            $document->getSubCategories()->add($uploadSession->getSubcategory1());
            $document->getSubCategories()->add($uploadSession->getSubcategory2());
            $document->getSubCategories()->add($uploadSession->getSubcategory3());
            $document->setCode($uploadSession->getCode());
            
            $document->upload($temporary); 
            if (!$temporary)
                $em->persist($document);
        }
        if (!$temporary)
            $em->flush();
        
    }
}

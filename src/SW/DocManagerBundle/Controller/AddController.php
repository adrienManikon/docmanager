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
use SW\DocManagerBundle\Form\DocumentType;
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
    
    public function categoryListAction($status = null, Request $request)
    {                
        $repository = $this->getRepository('SWDocManagerBundle:Category');
        
        if ($this->isMethodPost($request) && $request->request->get("category") != null 
                    && $request->request->get("select1") != null
                    && $request->request->get("select2") != null
                    && $request->request->get("select3") != null) {

            $document = new Document();
            $subCategories = array();
            
            $category = $repository->find($request->request->get("category"));
            $subCategories[] = $repository->find($request->request->get("select1"));
            $subCategories[] = $repository->find($request->request->get("select2"));
            $subCategories[] = $repository->find($request->request->get("select3"));
            
            $document->setDate(new DateTime("2012-07-08"));
            $document->setCategory($category);
            $document->generateCode($subCategories);
            $document->setSubCategories($this->array_unique_categories($subCategories));
            $document->setDisabled(false);
            
            $this->saveObject($document);

            return $this->redirect($this->generateUrl('sw_doc_manager_upload', array(
                'id' => $document->getId()
            )));
            
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
    
    public function uploadViewAction(Request $request, $id)
    {       
        $repositoryDocument = $this->getRepository("SWDocManagerBundle:Document");
        
        $document = $repositoryDocument->find($id);
                
        /* TEST */
        $repositoryUser = $this->getRepository('SWDocManagerBundle:User');
        
        $user = $repositoryUser->findByLastname('Manikon')[0];
        $document->setName("test.pdf");
        $document->setInitials($user->getInitial());
        $document->setCreator($user);
         
        $uploadSession = new UploadSession();
        $uploadSession->getDocuments()->add($document);
        $uploadSession->setDocumentRef($document);
        
        $form = $this->createForm(new UploadSessionType(), $uploadSession);
        
        if ($form->handleRequest($request)->isValid()) {
            
            $this->uploadDocuments(true, $uploadSession);
            
            $this->saveObject($uploadSession);
            
            return $this->redirect($this->generateUrl('sw_doc_manager_recap', array(
                'id' => $uploadSession->getId()
            )));
            
        }
        
        return $this->render('SWDocManagerBundle:Add:upload.html.twig', array(
            'form' => $form->createView(),
        ));
    }
            
    public function recapAction(Request $request, $id)
    {   
        $repository = $this->getRepository("SWDocManagerBundle:UploadSession");
        $uploadSession = $repository->find($id);
        
        $formPublish = $this->getFormPublish($uploadSession);
        $formPublish->handleRequest($request);
        
        if ($formPublish->isValid()) {
            $this->uploadDocuments(false, $uploadSession);                                
            $parameter = array('status' => 'success');
            
            return $this->redirect($this->generateUrl('sw_doc_manager_add', $parameter));
        }
                                
        return $this->render('SWDocManagerBundle:Add:recap.html.twig', array(
            'form' => $formPublish->createView(),
        ));
    } 
    
    private function getFormPublish(UploadSession $uploadSession) {
        return $this->get('form.factory')->createBuilder('form', $uploadSession)
            ->add('documents', 'collection', array(
                'type' => new DocumentType(false),
                'allow_add' => true,
                ))
            ->add('Veröffentlichen', 'submit')
            ->getForm();
    }
    
    /**
     * if request is method POST
     * 
     * @return boolean
     */
    public function isMethodPost(Request $request) {
        return $request->isMethod("POST");
    }
    
    public function uploadDocuments($temporary, UploadSession $uploadSession) {
        
        $em = $this->getDoctrine()->getManager();
        $documentRef = $uploadSession->getDocumentRef();
        
        foreach ($uploadSession->getDocuments() as $document) {
            
            $document->setCode($documentRef->getCode());
            $document->setCategory($documentRef->getCategory());
            $document->setSubCategories($documentRef->getSubCategories());
            $document->setDisabled(!$temporary);
            
            $document->upload($temporary);
            
            $em->persist($document);
            
        }
        
        $em->flush();
        
    }
    
    private function saveObject($object)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($object);
        $em->flush();
    }
    
    private function getRepository($classe) {
        return $this
            ->getDoctrine()
            ->getManager()
            ->getRepository($classe);
    }
    
    public function array_unique_categories($array) {
        
        $ids = array();
        $arrayUnique = array();
        
        foreach ($array as $category) {
            
            if (!in_array($category->getId(), $ids)) {
                $arrayUnique[] = $category;
                $ids[] = $category->getId();
            }
        }
        
        return $arrayUnique;
        
    }
}

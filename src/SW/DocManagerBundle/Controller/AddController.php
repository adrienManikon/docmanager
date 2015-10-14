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
use Symfony\Component\HttpFoundation\Request;
use DateTime;

/**
 * Description of AddController
 *
 * @author adrien.manikon
 */
class AddController extends AbstractController
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
            
            $document->setDate(new DateTime('NOW'));
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
    
    public function uploadViewAction(Request $request, Document $document)
    {       
        $repositoryUploadSession = $this->getRepository("SWDocManagerBundle:UploadSession");
                        
        /* TEST */
        $repositoryUser = $this->getRepository('SWDocManagerBundle:User');
        
        $user = $repositoryUser->findByLastname('Manikon')[0];
        $document->setInitials($user->getInitial());
        $document->setCreator($user);
        $document->setNameAlreadyUsed(false);
             
        $uploadSession = $repositoryUploadSession->findOneByDocumentRef($document);
        if ($uploadSession == null) {
            $uploadSession = new UploadSession();
            $uploadSession->getDocuments()->add($document);
            $uploadSession->setDocumentRef($document);
        }
        
        $alreadyExists = false;
        $form = $this->createForm(new UploadSessionType(), $uploadSession);
        
        if ($form->handleRequest($request)->isValid()) {
            
            if (!$uploadSession->hasExistedNames()) {
                $uploadSession = $this->checkAvailabityNames($uploadSession);
                //We keep files in cache
                $this->uploadDocuments(true, $uploadSession);
                $uploadSession->updateDocuments();                               
            } else //User overrides
                $uploadSession->setExistedNames(false);
                        
            $this->saveObject($uploadSession); 
            
            if (!$uploadSession->hasExistedNames()) {
                return $this->redirect($this->generateUrl('sw_doc_manager_recap', array(
                    'id' => $uploadSession->getId()
                )));
            } else {
                $form = $this->createForm(new UploadSessionType(), $uploadSession);
                $alreadyExists = true;
            }            
        }
        
        return $this->render('SWDocManagerBundle:Add:upload.html.twig', array(
            'form' => $form->createView(),
            'alreadyExists' => $alreadyExists
        ));
    }
            
    public function recapAction(Request $request, UploadSession $uploadSession)
    {   
        
        $formPublish = $this->getFormPublish($uploadSession);
        $formPublish->handleRequest($request);
        
        if ($formPublish->isValid()) {
            $this->uploadDocuments(false, $uploadSession);
            
            return $this->redirect($this->generateUrl('sw_doc_manager_add', array('status' => 'success')));
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
        
    public function uploadDocuments($temporary, UploadSession $uploadSession) {
        
        $em = $this->getDoctrine()->getManager();
        $documentRef = $uploadSession->getDocumentRef();
        foreach ($uploadSession->getDocuments() as $document) {
            
            $document->setCode($documentRef->getCode());
            $document->setCategory($documentRef->getCategory());
            $document->setSubCategories($documentRef->getSubCategories());
            $document->setCreator($documentRef->getCreator());
            $document->setDisabled(!$temporary);
            
            $document->upload($temporary);
            
            if (!$temporary) {
                $this->removeDocumentbyName($document->getName());
                $document->setUploadSession(null);
            }
            
            $em->persist($document);
            
        }
        
        if (!$temporary) {
            $em->remove($uploadSession);
        }
        
        $em->flush();
        
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
    
    public function checkAvailabityNames(UploadSession $uploadSession) {
        
        $documents = $uploadSession->getDocuments();
        $repDocument = $this->getRepository("SWDocManagerBundle:Document");
        $alreadyUsed = false;
        
        foreach ($documents as $document) {
            $documentExist = $repDocument->getOneByName($document->getName()) != null;
            $alreadyUsed = !$alreadyUsed ? $documentExist : $alreadyUsed;
            $document->setNameAlreadyUsed($documentExist);
        }
        
        $uploadSession->setExistedNames($alreadyUsed);
        $uploadSession->setDocuments($documents);
        
        return $uploadSession;
        
    }
    
    protected function deleteCache($user) {
        
        //$repoUploadSession = $this->getRepository("SWDocManagerBundle:UploadSession");
        
        //$uploads = $repoUploadSession->findBy
        
    }
}

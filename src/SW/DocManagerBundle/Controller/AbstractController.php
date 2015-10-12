<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SW\DocManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use DateTime;

/**
 * Description of AbstractController
 *
 * @author adrien.manikon
 */
class AbstractController extends Controller {
    
    public function dateToString(DateTime $date) {
        $dateService = $this->container->get('sw_doc_manager.date_service');
        return $dateService->dateToString($date);
    }
    
    public function stringToDate($date) {
        $dateService = $this->container->get('sw_doc_manager.date_service');
        return $dateService->stringToDate($date);
    }
    
    protected function saveObject($object)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($object);
        $em->flush();
    }
    
    protected function getRepository($classe) {
        return $this
            ->getDoctrine()
            ->getManager()
            ->getRepository($classe);
    }
    
    /**
     * if request is method POST
     * 
     * @return boolean
     */
    public function isMethodPost(Request $request) {
        return $request->isMethod("POST");
    }
    
    public function replaceDocument($oldDocument, $document) {
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($oldDocument);
        $em->persist($document);
        $em->flush();
        
    }
    
   public function removeDocumentbyName($name) {
       
       $repoUpload = $this->getRepository("SWDocManagerBundle:UploadSession");             
        $repo = $this->getRepository("SWDocManagerBundle:Document");
        $document = $repo->findOneByName($name);
        
        if ($document != null) {/*
            $uploadSession = $repoUpload->findOneByDocumentRef($document);
            $uploadSessions = $repoUpload->findByDocumentName($document);            
            $em = $this->getDoctrine()->getManager();
            if ($uploadSession != null)
                $em->remove($uploadSession);
            if ($uploadSessions != null)
                foreach ($uploadSessions as $uploadSession) {
                $em->remove($uploadSession);
                }
            $em->flush();  
            $em->remove($document);*/
            $em = $this->getDoctrine()->getManager();
            $document->setDisabled(true);
            $em->persist($document);
            $em->flush();
        }
        
    }
}
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SW\DocManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SW\DocManagerBundle\Entity\Document;
use Symfony\Component\HttpFoundation\File\File;
use DateTime;

/**
 * Description of AbstractController
 *
 * @author adrien.manikon
 */
class AbstractController extends Controller {
    
    protected function dateToString(DateTime $date) {
        $dateService = $this->container->get('sw_doc_manager.date_service');
        return $dateService->dateToString($date);
    }
    
    protected function stringToDate($date) {
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
    protected function isMethodPost(Request $request) {
        return $request->isMethod("POST");
    }
    
    protected function replaceDocument($oldDocument, $document) {
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($oldDocument);
        $em->persist($document);
        $em->flush();
        
    }
    
   protected function removeDocumentbyName($name) {
       
        $repoUpload = $this->getRepository("SWDocManagerBundle:UploadSession");
        $repo = $this->getRepository("SWDocManagerBundle:Document");
        $document = $repo->getOneByName($name);        
        
        if ($document != null) {
            
            $uploadSession = $repoUpload->findOneByDocumentRef($document);            
            $em = $this->getDoctrine()->getManager();
            if ($uploadSession != null) {
                
                foreach ($uploadSession->getDocuments() as $documentUp) {
                    $documentUp->setUploadSession(null);
                    $em->persist($documentUp);
                }
                
                $em->remove($uploadSession);
            }
            
            
            if ($document->getUploadSession() != null) {
                
                $upSession = $document->getUploadSession();
                foreach ($upSession->getDocuments() as $documentUp) {
                    $documentUp->setUploadSession(null);
                    $em->persist($documentUp);
                }
                $em->remove($upSession);
            }
            
            $em->remove($document);
            $em->flush();
        }
        
    }
    
   protected function encodeJson($documents) {
        
        $array = array();
        
        foreach ($documents as $document) {
            
            $array[] = array(
                "id" => $document->getId(),
                "name" => $document->getName(),
                "format" => $document->getFormat(),
                "date" => $this->dateToString($document->getDate()),
                "code" => $document->getCode(),
                "creator" => $document->getCreator()->getInitial(),
                "thumbs" => $this->getThumbs($document->getFormat())
            );
            
        }
        return json_encode($array);
        
    }
    
    protected function getThumbs($format) {
        
        if ($format == "pdf") {
            return "mif-file-pdf";
        }
        
        if ($format == "zip") {
            return "mif-file-archive";
        }
        
        if ($format == "doc" || $format == "xdoc") {
            return "mif-file-word";
        }
        
        if ($format == "xls") {
            return "mif-file-excel";
        }        
        
        if ($format == "jpeg" || $format == "jpg") {
            return "mif-file-image";
        }
        
        return "mif-file-pdf";
        
    }
    
    protected function upload($temporary, Document $document) {
        
        if ($temporary) {
            
            $file = $document->getFile();
                        
            if (null === $document->getFile()) {
                return;
            }
            
            
            //$this->creator = $this->file->getClientOriginalName();
            $file = $file->move($this->getUploadTempDir(), $file->getFilename());
            
            $document->setFile($file);
            $document->setPath($this->getUploadTempDir() . '/' . $file->getFilename());
            $document->setAlt($document->getCode());
            
        } else {
            
            $file = new File($document->getPath());
            
            if (null === $file)
                return;
            
            $file = $file->move($this->getUploadRootDir(), $document->getName());
            $document->setPath($this->getUploadRootDir() . '/' . $file->getFilename());
        }
        
        return $document;
        
    }
    
    protected function renameFile(Document $document, $newname)
    {
        
        $file = new File($document->getPath());

        if (null === $file)
            return;
        
        $file->move($this->getUploadRootDir(), $newname);     
        $document->setName($newname);
        $document->setPath($this->getUploadRootDir() . '/' . $newname);
        
        return $document;
        
    }
    
    protected function getUploadDir()
    {
        return 'uploads/document';
    }
    
    protected function getUploadTempDir()
    {
        return 'uploads/temp';
    }
    
    protected function getUploadRootDir()
    {
        return $this->get('kernel')->getRootDir() .'/../web/'. $this->getUploadDir();
    }
    
    protected function getUploadRootTempDir()
    {
        return $this->get('kernel')->getRootDir() .'/../web/' . $this->getUploadTempDir();
    }
}

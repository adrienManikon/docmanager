<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SW\DocManagerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Description of EditController
 *
 * @author adrien.manikon
 */
class EditController extends AbstractController {

    public function editAction(Request $request, $force) {
        
        $status = false;
        $message = "Something went wrong.";
        $forceRequest = $force === "true";
        
        if ($this->isMethodPost($request)) {
            
            $message = "Invalid data.";
            
            if ($this->validRequest($request)) {
                
                $repDocument = $this->getRepository("SWDocManagerBundle:Document");
                
                $document = $repDocument->findOneBy(array(
                    "id" => $request->request->get("id"),
                    "code" => $request->request->get("code"),
                    "initials" => $request->request->get("creator"),
                ));
                
                $message = "This document does not exist.";
                
                if ($document != null && $request->request->get("name") != '') {

                    $name = $request->request->get("name");
                    
                    return $this->editDocument($name, $document, $forceRequest);
                    
                }
                
            }
            
            
        }
        
        return $this->returnJsonResponse($status, $message, false);
        
    }
    
    private function editDocument($name, $document, $forceRequest) {
        
        $repDocument = $this->getRepository("SWDocManagerBundle:Document");
        $documentSameName = $repDocument->findOneByName($name);
        $status = true;
        $message = "";
        $alreadyExists = false;

        if (!$forceRequest && $documentSameName != null) {

            if (!$forceRequest) {
                $status = false;
                $alreadyExists = true;
                $message = "This document already exists.";
            }else {
                $document = $this->renameDocument($document, $name);
                $this->replaceDocument($documentSameName, $document);
            }

        } else {

            $document = $this->renameDocument($document, $name);
            $this->saveObject($document);
        }
        
        return $this->returnJsonResponse($status, $message, $alreadyExists);
        
    }
    
    private function validRequest(Request $request) {
        return ($request->request->get("id") != null
                    && $request->request->get("code") != null
                    && $request->request->get("name") != null
                    && $request->request->get("date") != null
                    && $request->request->get("creator") != null);
    }
    
    private function renameDocument($document, $name) {
        $document = $this->renameFile($document, $name);
        $document->setName($name);
        
        return $document;
    }

    private function returnJsonResponse($status, $message, $alreadyExists) {
        
        return new JsonResponse(array(
            'status' => $status,
            'message' => $message,
            'alreadyExists' => $alreadyExists
        ));
        
    }
}

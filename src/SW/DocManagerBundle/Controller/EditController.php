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
        $alreadyExists = false;
        $forceRequest = $force === "true";
        
        if ($this->isMethodPost($request)) {
            
            $message = "Invalid data.";
            
            if ($request->request->get("id") != null
                    && $request->request->get("code") != null
                    && $request->request->get("name") != null
                    && $request->request->get("date") != null
                    && $request->request->get("creator") != null) {
                
                $repDocument = $this->getRepository("SWDocManagerBundle:Document");
                
                $document = $repDocument->findOneBy(array(
                    "id" => $request->request->get("id"),
                    "code" => $request->request->get("code"),
                    "initials" => $request->request->get("creator"),
                ));
                
                $message = "This document does not exist.";
                
                if ($document != null && $request->request->get("name") != '') {

                    $name = $request->request->get("name");
                    $documentSameName = $repDocument->findOneByName($name);
                    
                    if (!$forceRequest && $documentSameName != null) {
                        
                        if (!$forceRequest) {
                            
                            $status = false;
                            $alreadyExists = true;
                            $message = "This document already exists.";
                            
                        }else {
                                                        
                            $document->renameFile($name);
                            $document->setName($name);
                            $this->replaceDocument($documentSameName, $document);
                            
                            $status = true;
                            $message = "";
                            
                        }
                        
                    } else {
                    
                        $document->renameFile($name);
                        $document->setName($name);                                       
                        $this->saveObject($document);

                        $status = true;
                        $message = "";
                    }
                }
                
            }
            
            
        }
        
        return $this->returnJsonResponse($status, $message, $alreadyExists);
        
    }

    public function returnJsonResponse($status, $message, $alreadyExists) {
        
        return new JsonResponse(array(
            'status' => $status,
            'message' => $message,
            'alreadyExists' => $alreadyExists
        ));
        
    }
}

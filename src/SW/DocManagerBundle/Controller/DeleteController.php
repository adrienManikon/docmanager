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
 * Description of DeleteController
 *
 * @author adrien.manikon
 */
class DeleteController extends AbstractController {
    
    public function deleteAction(Request $request)
    {
        
        if ($this->isMethodPost($request)) {
            
            $id = $request->request->get("id");
            
            if ($id != null) {
                
                $repDoc = $this->getRepository("SWDocManagerBundle:Document");
                $document = $repDoc->find($id);
                
                if ($document != null) {
                    
                    $em = $this->getDoctrine()->getManager();
                    $uploadSession = $this->getRepository("SWDocManagerBundle:UploadSession")
                            ->findOneByDocumentRef($document);

                    if ($uploadSession != null) {
                        $em->remove($uploadSession);
                        $em->flush();
                    }
                    
                    $em->remove($document);
                    
                    $em->flush();
                    
                    $status = true;
                    $message = "";
                    
                } else {
                    $status = false;
                    $message = "Document not found.";
                }
            } else {
                $status = false;
                $message = "Error when sending request.";
            }
        } else {
            $status = false;
            $message = "Bad request.";
        }
        
        return new JsonResponse(array(
            'status' => $status,
            'message' => $message
        ));
    }
}

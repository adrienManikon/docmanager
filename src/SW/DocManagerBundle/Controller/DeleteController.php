<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SW\DocManagerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use SW\DocManagerBundle\Entity\Document;

/**
 * Description of DeleteController
 *
 * @author adrien.manikon
 */
class DeleteController extends AbstractController {
    
    public function deleteAction(Request $request)
    {
        $response = array('status' => false, 'message' => '');
        if ($this->isMethodPost($request)) {
            
            $id = $request->request->get("id");
            
            if ($id != null) {
                
                $repDoc = $this->getRepository("SWDocManagerBundle:Document");
                $document = $repDoc->find($id);
                
                if ($document != null) {
                    
                    $response = $this->requestDeleteDocument($id);
                    
                } else {
                    $response['message'] = "Document not found.";
                }
            } else {
                $response['message'] = "Error when sending request.";
            }
        } else {
            $response['message'] = "Bad request.";
        }
        
        return new JsonResponse($response);
    }
    
    private function requestDeleteDocument($id) {  
        
        $status = false;
        $message = "";
        
        if ($id != null) {

            $repDoc = $this->getRepository("SWDocManagerBundle:Document");
            $document = $repDoc->find($id);

            if ($document != null) {

                $this->deleteDocument($document);
                $status = true;

            } else {
                $message = "Document not found.";
            }
        } else {
            $message = "Error when sending request.";
        }
        
        return array(
            'status' => $status,
            'message' => $message
        );
    }
    
    private function deleteDocument(Document $document) {
        
        $em = $this->getDoctrine()->getManager();
        $uploadSession = $this->getRepository("SWDocManagerBundle:UploadSession")
                ->findOneByDocumentRef($document);

        if ($uploadSession != null) {
            $em->remove($uploadSession);
            $em->flush();
        }

        $em->remove($document);

        $em->flush();        
        
    }
}

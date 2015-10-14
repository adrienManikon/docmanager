<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SW\DocManagerBundle\Controller;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Description of ViewController
 *
 * @author adrien.manikon
 */
class ViewController extends AbstractController {
    
    public function RenderAction(\SW\DocManagerBundle\Entity\Document $document) {
                
        $path = $document->getFilePath();
        
        $response = new BinaryFileResponse($path);
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $document->getName());
                                
        return $response;
    }
    
    /**                                                                                   
    * @Method({"GET", "POST"})
    */
    public function listAction(Request $request)    
    {
        if ($request->isXMLHttpRequest()) {
            
            $idcategories = $request->request->get("idcategories");
            $code = "";
            
            $repository = $this->getRepository('SWDocManagerBundle:Category');
            
            foreach ( $idcategories as $idcategory) {
                
                if ($idcategory > -1) {
                    
                    $code .= $repository->getCode($idcategory);
                    
                }
                
            }
            
            $repDoc = $this->getRepository('SWDocManagerBundle:Document');
            
            $documents = $repDoc->getByCode($code);
            $documentsJson = $this->encodeJson($documents);
            return new JsonResponse(array(
                'code' => $code,
                'documents' => json_decode($documentsJson)
                    ));
    }

        return new Response('This is not ajax!', 400);
    }    
}

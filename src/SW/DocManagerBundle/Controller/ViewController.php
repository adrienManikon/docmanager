<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SW\DocManagerBundle\Controller;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Description of ViewController
 *
 * @author adrien.manikon
 */
class ViewController extends AbstractController {
    
    public function RenderAction($id) {
        
        $repDocument = $this->getRepository('SWDocManagerBundle:Document');
        
        $document = $repDocument->find($id);
        $path = $document->getFilePath();
        
        $response = new BinaryFileResponse($path);
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $document->getName());
                                
        return $response;
    }
}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SW\DocManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Description of AddController
 *
 * @author adrien.manikon
 */
class AddController extends Controller
{
    public function categoryListAction()
    {
        return $this->render('SWDocManagerBundle:Add:categorylist.html.twig');
    }
    
    public function uploadViewAction()
    {
        return $this->render('SWDocManagerBundle:Add:upload.html.twig');
    }
    
    public function recapAction()
    {
        return $this->render('SWDocManagerBundle:Add:recap.html.twig');
    }
}

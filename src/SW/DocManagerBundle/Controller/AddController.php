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
    public function indexAction()
    {
        return $this->render('SWDocManagerBundle:Add:index.html.twig');
    }
}

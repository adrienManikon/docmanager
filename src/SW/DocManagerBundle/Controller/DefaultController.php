<?php

namespace SW\DocManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SWDocManagerBundle:Default:index.html.twig');
    }
    }

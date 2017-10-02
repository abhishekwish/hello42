<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace PointToPoint\View\Helper;
use Zend\View\Helper\AbstractHelper;
 
class CommonHelper extends AbstractHelper {
 
    public function __invoke() {
        return "Custom View Helper called";
        //$serviceLocator = $this->getServiceLocator();
    }
    
    public function getServiceLocator()
    {
        //return $this->serviceLocator;
        
    }
    
}


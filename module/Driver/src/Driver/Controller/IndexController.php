<?php

namespace Driver\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Json\Json;
use Driver\Form\DriverRegistrationForm;


class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();   
        
    }
    
    public function driverRegistrationAction()
    {
        //$form = new DriverRegistrationForm();
        
        //return new ViewModel(array('form'=>$form)); 
        
        return new ViewModel();
        
        
    }
    
}

<?php

namespace Driver\Form;
 
use Zend\Form\Form;
 
class DriverRegistrationForm extends Form{
    
    public function __construct($name=null)
    {
        parent::__construct('registraiton');
 
        // Setting post method for this form
        $this->setAttribute("method", "post");
  
        
        $this->add(array(
            "name"=>"localEmail",
            
            "attributes"=>array(
                "type"=>"text",
                "id" => "localEmail",
                'placeholder' => 'Email',
                'class' => 'form-control'            
                             
            ),
            "options"=>array(
                "label"=>""
                
            )
        ));
                 
             
        
        // Adding Submit button to the form
                
        $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Submit',
                 'id' => 'submitbutton',
                 'class' => 'btn btn-primary btn-lg',
             ),
         ));
        
        
    }
}


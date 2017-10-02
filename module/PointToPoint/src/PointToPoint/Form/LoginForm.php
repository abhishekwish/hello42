<?php
/*
 * This is for front end login form
 */

namespace PointToPoint\Form;
 
use Zend\Form\Form;
 
class LoginForm extends Form{
    
    public function __construct($name=null)
    {
        parent::__construct('login');
 
        // Setting post method for this form
        $this->setAttribute("method", "post");
 
        $this->add(array(
            "name"=>"login_user",
            
            "attributes"=>array(
                "type"=>"text",
                "id" => "login_user",
                'placeholder' => 'Email or Mobile',
                'class' => 'form-control',
                'required' => 'required',
                
                
            ),
            "options"=>array(
                "label"=>"Email or Mobile"
                
            )
        ));
        
        $this->add(array(
            "name"=>"login_pass",
            
            "attributes"=>array(
                "type"=>"password",
                "id" => "login_pass",
                'placeholder' => 'Password',
                'class' => 'form-control',
                'required' => 'required',
                
                
            ),
            "options"=>array(
                "label"=>"Email or Mobile"
                
            )
        )); 
        
        
        
        // Adding Submit button to the form
                
        $this->add(array(
             'name' => 'login',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Login',
                 'id' => 'login',
                 'class' => 'btn btn-primary',
             ),
         ));
        
        
    }
}


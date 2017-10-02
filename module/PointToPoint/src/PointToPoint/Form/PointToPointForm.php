<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace PointToPoint\Form;
 
use Zend\Form\Form;
 
class PointToPointForm extends Form{
    
    public function __construct($name=null)
    {
        parent::__construct('pointtopoint');
 
        // Setting post method for this form
        $this->setAttribute("method", "post");
 
        // Adding Hidden element to the form for ID
        $this->add(array(
            "name"=>"pointPickupArea_value",
            "attributes"=>array(
            "type"=>"hidden",
            "id" => "pointPickupArea_value" 
            )
        ));
 
        // Adding a text element to the form for Name
        $this->add(array(
            "name"=>"localName",
            
            "attributes"=>array(
                "type"=>"text",
                "id" => "localName",
                'placeholder' => 'Full Name',
                'class' => 'form-control',
                'required' => 'required',
                
                
            ),
            "options"=>array(
                "label"=>"Name"
                
            )
        ));
        
        // Adding a text element to the form for Name
        $this->add(array(
            "name"=>"localMobile",
            
            "attributes"=>array(
                "type"=>"text",
                "id" => "localMobile",
                'placeholder' => 'Mobile No1.',
                'class' => 'form-control'            
                
                
            ),
            "options"=>array(
                "label"=>""
                
            )
        ));
        
        $this->add(array(
            "name"=>"localMobile_alt",
            
            "attributes"=>array(
                "type"=>"text",
                "id" => "localMobile_alt",
                'placeholder' => 'Mobile No2.',
                'class' => 'form-control'            
                
                
            ),
            "options"=>array(
                "label"=>""
                
            )
        ));
        
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
                 
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'pointCabIn',
            'required' => true,
            'attributes' =>  array(
                'id' => 'pointCabIn',
                'class' => 'form-control'
                ),
            'options' => array(
                'label' => '',
                'options' => array(),
                ),
            ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'pointNationality',
            'attributes' =>  array(
                'id' => 'pointNationality',
                'class' => 'form-control'
                ),
            'options' => array(
                'label' => '',
                'options' => array(
                    'Indian' => 'Indian',
                    'Non-Indian' => 'Non-Indian',
                    ),
                ),
            ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'pointAdults',
            'attributes' =>  array(
                'id' => 'pointAdults',
                'class' => 'form-control'
                ),
            'options' => array(
                'label' => '',
                'options' => array(),
                ),
            ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'pointChilds',
            'attributes' =>  array(
                'id' => 'pointChilds',
                'class' => 'form-control'
                ),
            'options' => array(
                'label' => '',
                'options' => array(),
                ),
            ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'pointLuggages',
            'attributes' =>  array(
                'id' => 'pointLuggages',
                'class' => 'form-control'
                ),
            'options' => array(
                'label' => '',
                'options' => array(),
                ),
            ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'pointCars',
            'attributes' =>  array(
                'id' => 'pointCars',
                'class' => 'form-control'
                ),
            'options' => array(
                'label' => '',
                'options' => array(),
                ),
            ));
        
        $this->add(array(
            "name"=>"term",
            
            "attributes"=>array(
                "type"=>"text",
                "id" => "pointPickupArea",
                'placeholder' => 'Choose Location',
                'class' => 'form-control'            
                             
            ),
            "options"=>array(
                "label"=>""
                
            )
        ));
        
        $this->add(array(
            "name"=>"pointDropArea",
            
            "attributes"=>array(
                "type"=>"text",
                "id" => "pointDropArea",
                'placeholder' => 'Choose Location',
                'class' => 'form-control'            
                             
            ),
            "options"=>array(
                "label"=>""
                
            )
        ));
        
        $this->add(array(
            "name"=>"pointDropArea_value",
            "attributes"=>array(
            "type"=>"hidden",
            "id" => "pointDropArea_value" 
            )
        ));
        
        $this->add(array(
            "name"=>"pointAddress",
            
            "attributes"=>array(
                "type"=>"textarea",
                "id" => "pointAddress",
                'placeholder' => 'City, Airport, Point of Interest or U.S. Zip Code',
                'class' => 'form-control autocomplete'            
                             
            ),
            "options"=>array(
                "label"=>""
                
            )
        ));
        
        $this->add(array(
        'name' => 'pickup',
        'type' => 'Radio',
        'options' => array(
        'label' => 'Type',
        'label_attributes' => array(
            'class' => 'col-sm-6 padding0',
        ),
         'column-size' => 'col-md-3',
        'value_options' => array(
            'Pick Now' => 'Pick Now (With in 30 min)',
            'Book Later' => 'Book Later (After 1 Hr.)',
        ),
        ),
            'attributes' => array(
                'value' => 'Pick Now',
                'id' => 'pickup_later',
            )
        ));
        
        $this->add(array(
            "name"=>"pointLaterDate",
            
            "attributes"=>array(
                "type"=>"text",
                "id" => "datetimepicker",
                'placeholder' => 'Date',
                'class' => ''            
                             
            ),
            "options"=>array(
                "label"=>""
                
            )
        ));
        
       $this->add([
           'name' => 'agreeterms',
           'type' => 'Checkbox',
           "attributes"=>array(
                "id" => "agreeterms",
                'placeholder' => '',
                'class' => ''            
                             
            ),
           'options' => [
               'label' => '',
               'checked_value' => '1',
               'unchecked_value' => '0',
               'use_hidden_element' => true,
               ],
           ]);
        
        
        // Adding Submit button to the form
                
        $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Search Car',
                 'id' => 'submitbutton',
                 'class' => 'btn btn-primary btn-lg',
             ),
         ));
        
        
    }
}


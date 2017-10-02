<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace  Outstation\Form; 

use Zend\Form\Form;

class OutstationForm extends Form{
    
    
     public function __construct($name=null)
    {
        parent::__construct('outstation');
        
        $this->setAttribute("method", "post");
          
        $this->add(array(
        'name' => 'booking_type',
        'type' => 'Radio',
        'options' => array(
        'label' => 'Type',
        'label_attributes' => array(
            'class' => 'col-sm-6 padding0',
        ),
         'column-size' => 'col-md-3',
        'value_options' =>
             [
                    [
                        'value' => 'Roundtrip',
                        'label' => 'Roundtrip',
                        'selected' => true,

                    ],
                    [
                        'value' => 'Oneway',
                        'label' => 'Oneway',
                        'selected' => false,

                    ],
                    [
                        'value' => 'Multicity',
                        'label' => 'Multicity',
                        'selected' => false,

                    ]
            ],     
        ),
            'attributes' => array(               
               'id' => 'Roundtrip',
               'value'=>'Roundtrip'
            
            )
            
        ));
        
        $this->add(array(
            "name"=>"outstationFrom",
            
            "attributes"=>array(
                "type"=>"text",
                "id" => "outstationFrom",
                'placeholder' => '',
                'class' => 'form-control autocomplete'            
                             
            ),
            "options"=>array(
                "label"=>""
                
            )
        ));
        
        
         $this->add(array(
            "name"=>"outstationTo",
             
            "attributes"=>array(
                "type"=>"text",
                "id" => "outstationTo",
                'placeholder' => '',
                'class' => 'form-control autocomplete'            
                             
            ),
            "options"=>array(
                "label"=>""
                
            )
        ));
         
         
          $this->add(array(
            "name"=>"outstattionDate",
            
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

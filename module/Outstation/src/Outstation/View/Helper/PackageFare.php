<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Outstation\View\Helper;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorInterface as ServiceLocator;
 
class PackageFare extends AbstractHelper {
     protected $serviceLocator;
 
  public function __construct(ServiceLocator $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
          
    }
    
    public function __invoke($base_comb_id,$subpackage_id)
    {
        $fare_details = array();
         $base_comb_id; $subpackage_id; 
         if($subpackage_id==1){
              $distancefareobj  = $this->serviceLocator->get('DistanceFareTable');
              $getDistanceFare =  $distancefareobj->getDistanceFare($base_comb_id);
              foreach($getDistanceFare as $results){
                  $fare_details = $results;
              }
              return $fare_details;
         }
         if($subpackage_id==2){
             
         }
         if($subpackage_id==3){
              $distancehourfareobj  = $this->serviceLocator->get('DistanceHourFareTable');
              $distancehourfareobj->getDistanceHourFare($base_comb_id);             
               foreach($distancehourfareobj as $results){
                  $fare_details = $results;
              }
              return $fare_details;
         }
         if($subpackage_id==4){
             
         }
      
       //echo '<pre>';print_r($distancehourfareobj);die();
       
    }
    
   
    
}


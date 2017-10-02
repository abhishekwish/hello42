<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace PointToPoint\View\Helper;
use Zend\View\Helper\AbstractHelper;
 
class CommonHelper extends AbstractHelper {
 
    public function __invoke()
    {
        return "Custom View Helper called";
        //$serviceLocator = $this->getServiceLocator();
    }
    
    public function getServiceLocator()
    {
        
        return $this->serviceLocator;
        
    }
    public function getResultByPackage($fareDetailsOfPackage)
    { 
                
        $localFare = array();
        
        if ($fareDetailsOfPackage['0']['sub_package_id'] == 1) 
        {
            
            $pack_hour = $fareDetailsOfPackage['0']['package_info_distance'];
            
            $fare = str_replace(array('[', ']'), '', $pack_hour);
            $fare1 = explode(',', $fare);
            // 4- 40 km
            $Efdaa = str_replace('"', '', $fare1[0]);
            $fda = explode('_', $Efdaa);

            // 8- 80 KM
            $sdaa = str_replace('"', '', $fare1[1]);
            $sda = explode('_', $sdaa);

            // 12- 120 KM
            $tdaa = str_replace('"', '', $fare1[2]);
            $tda = explode('_', $tdaa);

            // 16- 160 KM
            $slaa = str_replace('"', '', $fare1[3]);
            $lda = explode('_', $slaa);


            if ($fareDetailsOfPackage['0']['cab_type'] == 1)
            {
                // Open For Distance fare (Economy)
                $fareDetailsOfPackage['0']['rate_per_hour_dh'] = "0";
                $localFare['min_charge'] = $fareDetailsOfPackage['0']['minimum_charge'];
                $localFare['car'] = array('c1' => $fda[0], 'c2' => $fda[2], 'c3' => $fareDetailsOfPackage['0']['per_km_charge'], 'c4' => $fareDetailsOfPackage['0']['rate_per_hour_dh']);
                
            }
            elseif ($fareDetailsOfPackage['0']['cab_type'] == 2) 
            {
                // Open For Distance fare (Sedan)
                                
                $fareDetailsOfPackage['0']['rate_per_hour_dh'] = "0";
                $localFare['min_charge'] = $fareDetailsOfPackage['0']['minimum_charge'];
                $localFare['car'] = array('c1' => $fda[0], 'c2' => $fda[2], 'c3' => $fareDetailsOfPackage['0']['Per_Km_Charge'], 'c4' => $fareDetailsOfPackage['0']['rate_per_hour_dh']);
                
            } 
            elseif ($fareDetailsOfPackage['0']['cab_type'] == 3)
            {
                // Open For Distance fare (Prime)
                $fareDetailsOfPackage['0']['rate_per_hour_dh'] = "0";
                $localFare['min_charge'] = $fareDetailsOfPackage['0']['minimum_charge'];
                $localFare['car'] = array('c1' => $fda[0], 'c2' => $fda[2], 'c3' => $fareDetailsOfPackage['0']['per_km_charge'], 'c4' => $fareDetailsOfPackage['0']['rate_per_hour_dh']);
            }
        }
        elseif ($fareDetailsOfPackage['0']['sub_package_id'] == 2) 
        {
            $pack_hour = $fareDetailsOfPackage['0']['package_info_hourly'];

            $fare = str_replace(array('[', ']'), '', $pack_hour);
            $fare1 = explode(',', $fare);
            // 4- 40 km
            $Efdaa = str_replace('"', '', $fare1[0]);
            $fda = explode('_', $Efdaa);

            // 8- 80 KM
            $sdaa = str_replace('"', '', $fare1[1]);
            $sda = explode('_', $sdaa);

            // 12- 120 KM
            $tdaa = str_replace('"', '', $fare1[2]);
            $tda = explode('_', $tdaa);

            // 16- 160 KM
            $slaa = str_replace('"', '', $fare1[3]);
            $lda = explode('_', $slaa);

            if ($fareDetailsOfPackage['0']['cab_type'] == 1) 
            {
                // Open For Hourly fare (Economy)
                $fareDetailsOfPackage['0']['per_km_charge'] = "0";
                $localFare['min_charge'] = 0;
                $localFare['car'] = array('c1' => $fda[0], 'c2' => $fda[2], 'c3' => $fareDetailsOfPackage['0']['per_km_charge'], 'c4' => $fareDetailsOfPackage['0']['trip_charge_per_minute']);
                
            } 
            elseif ($fareDetailsOfPackage['0']['cab_type'] == 2) 
            {
                // Open For Hourly fare (Sedan)
                $fareDetailsOfPackage['0']['per_km_charge'] = "0";
                $localFare['min_charge'] = 0;
                $localFare['car'] = array('c1' => $fda[0], 'c2' => $fda[2], 'c3' => $fareDetailsOfPackage['0']['per_km_charge'], 'c4' => $fareDetailsOfPackage['0']['trip_charge_per_minute']);
                
            } 
            elseif ($fareDetailsOfPackage['0']['cab_type'] == 3)
            {
                // Open For Distance fare (Prime)
                $fareDetailsOfPackage['0']['per_km_charge'] = "0";
                $localFare['min_charge'] = 0;
                $localFare['car'] = array('c1' => $fda[0], 'c2' => $fda[2], 'c3' => $fareDetailsOfPackage['0']['per_km_charge'], 'c4' => $fareDetailsOfPackage['0']['trip_charge_per_minute']);
            }
        }
        elseif ($fareDetailsOfPackage['0']['sub_package_id'] == 3) 
        {
            $pack_hour = $fareDetailsOfPackage['0']['package_info_distance_hourly'];
            $fare = str_replace(array('[', ']'), '', $pack_hour);
            $fare1 = explode(',', $fare);
            // 4- 40 km
            $Efdaa = str_replace('"', '', $fare1[0]);
            $fda = explode('_', $Efdaa);

            // 8- 80 KM
            $sdaa = str_replace('"', '', $fare1[1]);
            $sda = explode('_', $sdaa);

            // 12- 120 KM
            $tdaa = str_replace('"', '', $fare1[2]);
            $tda = explode('_', $tdaa);

            // 16- 160 KM
            $slaa = str_replace('"', '', $fare1[3]);
            $lda = explode('_', $slaa);

            if ($fareDetailsOfPackage['0']['cab_type'] == 1)
            {
                $localFare['min_charge'] = $fareDetailsOfPackage['0']['minimum_fare_dh'];
                $localFare['car'] = array('c1' => $fda[0], 'c2' => $fda[3], 'c3' => $fareDetailsOfPackage['0']['rate_per_km_dh'], 'c4' => $fareDetailsOfPackage['0']['rate_per_hour_dh']);
                
            } 
            elseif ($fareDetailsOfPackage['0']['cab_type'] == 2) 
            {
                $localFare['min_charge'] = $fareDetailsOfPackage['0']['minimum_fare_dh'];
                $localFare['car'] = array('c1' => $fda[0], 'c2' => $fda[3], 'c3' => $fareDetailsOfPackage['0']['rate_per_km_dh'], 'c4' => $fareDetailsOfPackage['0']['rate_per_hour_dh']);
                
            }
            elseif ($fareDetailsOfPackage['0']['cab_type'] == 3)
            {
                $localFare['min_charge'] = $fareDetailsOfPackage['0']['minimum_fare_dh'];
                $localFare['car'] = array('c1' => $fda[0], 'c2' => $fda[3], 'c3' => $fareDetailsOfPackage['0']['rate_per_km_dh'], 'c4' => $fareDetailsOfPackage['0']['rate_per_hour_dh']);
            }
        }
        elseif ($fareDetailsOfPackage['0']['sub_package_id'] == 4)
        {
            
            $pack_hour = $fareDetailsOfPackage['0']['package_info_distance_waiting'];
            $fare = str_replace(array('[', ']'), '', $pack_hour);
            $fare1 = explode(',', $fare);
            // 4- 40 km
            $Efdaa = str_replace('"', '', $fare1[0]);
            $fda = explode('_', $Efdaa);

            // 8- 80 KM
            $sdaa = str_replace('"', '', $fare1[1]);
            $sda = explode('_', $sdaa);

            // 12- 120 KM
            $tdaa = str_replace('"', '', $fare1[2]);
            $tda = explode('_', $tdaa);

            // 16- 160 KM
            $slaa = str_replace('"', '', $fare1[3]);
            $lda = explode('_', $slaa);

            if ($fareDetailsOfPackage['0']['cab_type'] == 1)
            {
                // Open For Distance waiting fare(Economy) 
                $fareDetailsOfPackage['0']['trip_charge_per_minute'] = "0";
                $localFare['min_charge'] = $fareDetailsOfPackage['0']['minimum_fare_dw']; 
                $localFare['car'] = array('c1' => $fda[0], 'c2' => $fda[2], 'c3' => $fareDetailsOfPackage['0']['rate_per_km_dw'], 'c4' => $fareDetailsOfPackage['0']['trip_charge_per_minute']);
                
            } 
            elseif ($fareDetailsOfPackage['0']['cab_type'] == 2)
            {
                // Open For Distance waiting fare(Sedan)
                $fareDetailsOfPackage['0']['trip_charge_per_minute'] = "0";
                $localFare['min_charge'] = $fareDetailsOfPackage['0']['minimum_fare_dw'];
                $localFare['car'] = array('c1' => $fda[0], 'c2' => $fda[2], 'c3' => $fareDetailsOfPackage['0']['rate_per_km_dw'], 'c4' => $fareDetailsOfPackage['0']['trip_charge_per_minute']);
            } 
            elseif ($fareDetailsOfPackage['0']['cab_type'] == 3)
            {
                // Open For Distance waiting fare (Prime)
                $fareDetailsOfPackage['0']['trip_charge_per_minute'] = "0";
                $localFare['min_charge'] = $fareDetailsOfPackage['0']['minimum_fare_dw'];
                $localFare['car'] = array('c1' => $fda[0], 'c2' => $fda[2], 'c3' => $fareDetailsOfPackage['0']['rate_per_km_dw'], 'c4' => $fareDetailsOfPackage['0']['trip_charge_per_minute']);
                
            }
        }
        return $localFare;
    }
    
    public function getRoundingOff($roundingTypeId, $value)
    {        
        /**
         * Nearest is 1, Upward is 2, and Downward is 3
         */
        if($roundingTypeId==1)
        {
            //Nearest
            return round($value);  
            
        }else if($roundingTypeId==2)
        {
            //Upward
            return ceil($value); 
            
        }else if($roundingTypeId==3)
        {
            //Downward
            return floor($value); 
            
        }        
        
    }
    
}


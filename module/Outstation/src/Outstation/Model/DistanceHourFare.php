<?php
namespace Outstation\Model;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class DistanceHourFare
{
    
     public  $dist_hr_fare_id;
     public  $base_comb_id;
     public  $minimum_charge;
     public  $minimum_distance;
     public  $minimum_hrs;
     public  $per_km_charge;
     public  $per_hr_charge;
     public  $created_date;
     public  $modified_date;
     public  $created_by;
     public  $modified_by;
     public  $status;
     public  $ip;
     
    
   
     
      public function exchangeArray($data)
    {
        $this->dist_hr_fare_idPrimary     = (isset($data['dist_hr_fare_idPrimary']))     ? $data['dist_hr_fare_idPrimary']     : null;
        $this->base_comb_id = (isset($data['base_comb_id'])) ? $data['base_comb_id'] : null;
        $this->minimum_charge  = (isset($data['minimum_charge']))  ? $data['minimum_charge']  : null;
        $this->minimum_distance  = (isset($data['minimum_distance']))  ? $data['minimum_distance']  : null;
        $this->minimum_hrs  = (isset($data['minimum_hrs']))  ? $data['minimum_hrs']  : null;
        $this->per_km_charge  = (isset($data['per_km_charge']))  ? $data['per_km_charge']  : null;
        $this->per_hr_charge  = (isset($data['per_hr_charge']))  ? $data['per_hr_charge']  : null;    
        $this->created_date  = (isset($data['created_date']))  ? $data['created_date']  : null;
        $this->modified_date  = (isset($data['modified_date']))  ? $data['modified_date']  : null;
        $this->created_by  = (isset($data['created_by']))  ? $data['created_by']  : null;
        $this->modified_by  = (isset($data['modified_by']))  ? $data['modified_by']  : null;
        $this->status  = (isset($data['modified_by']))  ? $data['status']  : null;
        $this->ip  = (isset($data['ip']))  ? $data['ip']  : null;
    }
    
    
     public function getArrayCopy()
	{
		return get_object_vars($this);
	}

}

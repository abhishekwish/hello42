<?php
namespace Outstation\Model;

class DistanceFare 
{
    
    public  $distance_fare_id;
    public  $base_comb_id;
    public  $minimum_charge;
    public  $minimum_distance;
    public  $per_km_charge;
    public  $distance_up_to;
    public  $rate_upto_distance;
    public  $created_date;
    public  $modified_date;
    public  $created_by;
    public  $modified_by;
    public  $status;
    public  $ip;
    
    public function exchangeArray($data)
    {
        $this->distance_fare_id     = (isset($data['distance_fare_id']))     ? $data['distance_fare_id']     : null;
        $this->base_comb_id = (isset($data['base_comb_id'])) ? $data['base_comb_id'] : null;
        $this->minimum_charge  = (isset($data['minimum_charge']))  ? $data['minimum_charge']  : null;
        $this->minimum_distance  = (isset($data['minimum_distance']))  ? $data['minimum_distance']  : null;
        $this->per_km_charge  = (isset($data['per_km_charge']))  ? $data['per_km_charge']  : null;
        $this->distance_up_to  = (isset($data['distance_up_to']))  ? $data['distance_up_to']  : null;
        $this->rate_upto_distance  = (isset($data['rate_upto_distance']))  ? $data['rate_upto_distance']  : null;
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
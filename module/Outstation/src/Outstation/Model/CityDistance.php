<?php
namespace Outstation\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class CityDistance implements InputFilterAwareInterface
{
    public $id;
    public $source_city;
    public $destination_city;
    public $distance_km;

    
     public function exchangeArray($data)
    {
        $this->id     = (isset($data['id']))     ? $data['id']     : null;
        $this->source_city = (isset($data['source_city'])) ? $data['source_city'] : null;
        $this->destination_city  = (isset($data['destination_city']))  ? $data['destination_city']  : null;
        $this->distance_km  = (isset($data['distance_km']))  ? $data['distance_km']  : null;
    }

    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function getInputFilter() {
        
    }

     public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

}
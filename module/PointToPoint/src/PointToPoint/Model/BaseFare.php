<?php

namespace PointToPoint\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class BaseFare implements InputFilterAwareInterface
{
    public $id;
    public $city_id;
    public $booking_type_id;
        
    
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id']))     ? $data['id']     : null;
        $this->city_id = (isset($data['city_id'])) ? $data['city_id'] : null;
        $this->booking_type_id  = (isset($data['booking_type_id']))  ? $data['booking_type_id']  : null;
    }

     // Add the following method:
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
     

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
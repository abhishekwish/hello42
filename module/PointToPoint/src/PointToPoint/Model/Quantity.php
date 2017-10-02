<?php

namespace PointToPoint\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Quantity implements InputFilterAwareInterface
{
    public $id;
    public $quantity_value;
    public $status;
        
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id']))     ? $data['id']     : null;
        $this->quantity_value = (isset($data['quantity_value'])) ? $data['quantity_value'] : null;
        $this->status  = (isset($data['status']))  ? $data['status']  : null;
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
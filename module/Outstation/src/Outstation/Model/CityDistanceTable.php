<?php
/**
 * Mohd Emadullah, Cities table
 */
namespace Outstation\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Expression;

class CityDistanceTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function getCityDistanceInKm($sourcecity, $distancecity)
    {
     
            /*$adapter = $this->tableGateway->getAdapter();
             $sql     = new Sql($adapter);
             $select = $sql->select();
            $select->where(array("source_city" => $sourcecity));
            $select->where(array("destination_city" =>$distancecity));     
            
           // echo $select->getSqlString();die;
            $statement = $sql->getSqlStringForSqlObject($select);
            $result   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
            return $result->toArray(); */
        
            $select =  $this->tableGateway->getSql()->select(); //$this->tableGateway->select(array('city_name'));
            $select->columns(array('*'));
            $select->where("source_city LIKE '$sourcecity%'");
            $select->where("destination_city LIKE '$distancecity%'");
            $resultSet = $this->tableGateway->selectWith($select);        
            return $resultSet =  $resultSet->current();            
            //echo '<pre>';print_r($resultSet);die();
        
    }
    
}

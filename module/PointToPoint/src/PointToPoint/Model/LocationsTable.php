<?php
/**
 * Mohd Emadullah, Cities table
 */
namespace PointToPoint\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Expression;

class LocationsTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getCityNameDelhiNcr($ncr)
    {
        $ncr  = (int) $ncr;
        $rowset = $this->tableGateway->select(array('ncr' => $ncr));
        $row = $rowset->toArray();
        if (!$row) {
            throw new \Exception("Could not find row $ncr");
        }
        return $row;
              
    }
    
    public function location($city,$term) 
    {
        $adapter = $this->tableGateway->getAdapter();
        if(!empty($city))
        {
            
            $sql = new Sql($adapter);
            $select = $sql->select();
            $select->from('tbl_cities')
               ->columns(array('city_name'))
               ->where->equalTo('id',$city);
              

        $statement = $sql->getSqlStringForSqlObject($select);
        $result   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        $cityName = $result->current();
        $cityName = $cityName['city_name'];
                    
        }
        $sql     = new Sql($adapter);
        $select = $sql->select();
        $select->from('tbl_locations')
               ->columns(array('*'))
               ->where->like('area', $term.'%')
               ->where->equalTo('country','India');
        if(!empty($cityName)){
            $select->where->equalTo('city',$cityName);                    

        }
        $select->limit(10);
        $statement = $sql->getSqlStringForSqlObject($select);
        $result   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        return $result->toArray();        
        
    }    
    
    public function fetchJoin()
    {
        $select = new \Zend\Db\Sql\Select ;
        $select->from('province');
        $select->columns(array('province'));
        $select->join('village', "village.id_province = province.province.id", array('village'), 'left');
         
        echo $select->getSqlString();
        $resultSet = $this->tableGateway->selectWith($select);
       
        return $resultSet;
    } 

    
}
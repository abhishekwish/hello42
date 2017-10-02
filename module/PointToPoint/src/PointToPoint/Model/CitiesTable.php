<?php
/**
 * Mohd Emadullah, Cities table
 */
namespace PointToPoint\Model;

use Zend\Db\TableGateway\TableGateway;

class CitiesTable
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

    public function getCityNameDelhiNcr($moduleType)
    {
        
        $ncr  = (int) $ncr;
        $rowset = $this->tableGateway->select(array('is_active'=>1,$moduleType=>1));
        $row = $rowset->toArray();
        if (!$row) {
            throw new \Exception("Could not find row $ncr");
        }
        return $row;       
       
    }
    
    public function getCityIdByCityName($cityName, $moduleType)
    {
        
        $rowset = $this->tableGateway->select(array('city_name'=> $cityName, 'is_active'=>1,$moduleType=>1));
        $row = $rowset->toArray();
        if (!$row) {
            throw new \Exception("Could not find rowr");
        }
        return $row;        
        
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
    
    /** Author  : Mohit 
     * @Comment : GET CITY NAME FOR AUTO COMPLETE.
     */
    public function getCityNameFromName($cityname){
        if($cityname!=''){
            $select =  $this->tableGateway->getSql()->select();//$this->tableGateway->select(array('city_name'));
            $select->columns(array('city_name'));
            $select->where("city_name LIKE '$cityname%'");
            $select->where('is_active=1');
              //echo $select->getSqlString();die;
            $resultSet = $this->tableGateway->selectWith($select);
           return  $resultSet = $resultSet->toArray();
        }
    }
    
}
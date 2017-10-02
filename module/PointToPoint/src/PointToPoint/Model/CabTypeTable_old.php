<?php
/**
 * Mohd Emadullah, CabType table
 */
namespace PointToPoint\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Expression;

class CabTypeTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function getCabType($city_id, $booking_type_id)
    {  
        $adapter = $this->tableGateway->getAdapter();
        $sql     = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('cbt'=>'tbl_cab_type'))
               ->columns(array('*'));
       $select->join(array('cbmm'=>'tbl_cab_model_master'), "cbt.id = cbmm.cab_type_id", array('*'), 'left');
       $select->join(array('bf'=>'tbl_base_fare'), "bf.cab_type = cbmm.cab_type_id", array('booking_type_id','city_id'), 'left');
       $select->where(array('bf.city_id'=> $city_id));
       $select->where(array('bf.booking_type_id'=> $booking_type_id));
       $select->group('cbt.cab_type');
       $select->order('cbmm.cab_type_id ASC');
       $statement = $sql->getSqlStringForSqlObject($select);
        $result   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        return $result->toArray(); 
        
    } 

    
}
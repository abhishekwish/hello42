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
              
       $select->join(array('bc'=>'tbl_base_combination'), "bc.cab_type_id = cbmm.cab_type_id", array('company_id','vendor_id','booking_type_id','city_id'), 'left');
       $select->join(array('bf'=>'tbl_base_fare'),'bc.base_comb_id=bf.base_comb_id',array('*'),'left');
       $select->join(array('sp'=>'tbl_sub_package'),'bf.subpackage_id=sp.id',array('sub_package_name'),'left');
       $select->where(array('bc.city_id'=> $city_id));
       $select->where(array('bc.booking_type_id'=> $booking_type_id));
       $select->where(array('bc.company_id'=> 0));
       $select->where(array('bc.vendor_id'=> 0));
       $select->group('cbt.cab_type');
       $select->order('cbmm.cab_type_id ASC');
      
       $statement = $sql->getSqlStringForSqlObject($select);
        $result   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        return $result->toArray(); 
        
    }
    public function getPackages($city_id, $booking_type_id)
    {  
        $adapter = $this->tableGateway->getAdapter();
        $sql     = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('lpkg'=>'tbl_local_package'))
               ->columns(array('*'));
       $select->join(array('mpkg'=>'tbl_master_package'), "lpkg.sub_pkg_id = mpkg.sub_package_id", array('master_package','master_package_ref','package_id','sub_package_id','state_id'), 'left');
       $select->where(array('mpkg.package_id'=> $booking_type_id));
       $select->where(array('lpkg.master_pkg_id'=> $booking_type_id));
       $select->where(array('lpkg.city_id'=> $city_id));
       $select->where(array('lpkg.cab_type'=> 1));
       $select->where(array('lpkg.status'=> 1));
       
       $statement = $sql->getSqlStringForSqlObject($select);
        $result   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        return $result->toArray(); 
       
    }
    
    public function getPackageDetailsByID($localPackageId)
    {  
        $adapter = $this->tableGateway->getAdapter();
        $sql     = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('lpkg'=>'tbl_local_package'))
               ->columns(array('*'));
        $select->where(array('lpkg.status'=> 1));
        $select->where(array('lpkg.id'=> $localPackageId));
       
       $statement = $sql->getSqlStringForSqlObject($select);
        $result   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        return $result->toArray(); 
       
    }
   
}
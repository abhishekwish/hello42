<?php
/**
 * Mohd Emadullah, Cities table
 */
namespace PointToPoint\Model;

use Zend\Db\TableGateway\TableGateway;

class QuantityTable
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

    public function getQuantity($status)
    {
        $status  = (int) $status;
        $rowset = $this->tableGateway->select(array('status' => $status));
        $row = $rowset->toArray();
        if (!$row) {
            throw new \Exception("Could not find row $ncr");
        }
        return $row;
              
    }
    /*
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
    
    */

    
}
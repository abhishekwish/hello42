<?php
/**
 * Mohd Emadullah, User table
 */
namespace PointToPoint\Model;

use Zend\Db\TableGateway\TableGateway;

class UserTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function getLoginUser()
    {
        
        $ncr  = (int) $ncr;
        $rowset = $this->tableGateway->select(array('is_active'=>1,$moduleType=>1));
        $row = $rowset->toArray();
        if (!$row) {
            throw new \Exception("Could not find row $ncr");
        }
        return $row;      
       
    }
}
<?php
namespace Outstation\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Expression;

class DistanceWatingFareTable extends TableGateway
{
    
     protected $tableGateway;
    protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
    
    public function getDistanceWatingFare($base_comb_id)
    {	
        $select = $this->tableGateway->getSql()->select();
        $select->where('base_comb_id="'.$base_comb_id.'"');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet->toArray();		
    }
    
}

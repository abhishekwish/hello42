<?php

/**
 * Description of Module
 *
 * @author Arian Khosravi <arian@bigemployee.com>, <@ArianKhosravi>
 */
//  module/StickyNotes/Module.php

namespace Outstation;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\Adapter\DbTable as DbAuthAdapter;
use Zend\Authentication\AuthenticationService;

use Outstation\Model\Cities;
use Outstation\Model\CitiesTable;

use Outstation\Model\CityDistance;
use Outstation\Model\CityDistanceTable;

use Outstation\Model\DistanceFare;
use Outstation\Model\DistanceFareTable;

use Outstation\Model\DistanceHourFare;
use Outstation\Model\DistanceHouFareTable;

use PointToPoint\Model\CabType;
use PointToPoint\Model\CabTypeTable;
class Module {

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getViewHelperConfig()
  {
      return array(
          'factories' => array(
              'PackageFare' => function ($serviceManager) {
                  // Get the service locator 
                  $serviceLocator = $serviceManager->getServiceLocator();
                  // pass it to your helper 
                  return new \Outstation\View\Helper\PackageFare($serviceLocator);
              }
          )
      );
  }
 
    public function getServiceConfig() {
        return array(
            'factories' => array(
                'CitiesTable' =>  function($sm) {
                    $tableGateway = $sm->get('CitiesTableGateway');
                    $table = new CitiesTable($tableGateway);
                    return $table;
                },
                'CitiesTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Cities());
                    return new TableGateway('tbl_cities', $dbAdapter, null, $resultSetPrototype);
                },
                 'CabTypeTable' =>  function($sm) {
                    $tableGateway = $sm->get('CabTypeTableGateway');
                    $table = new CabTypeTable($tableGateway);
                    return $table;
                },
                'CabTypeTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Cabtype());
                    return new TableGateway('tbl_cab_type', $dbAdapter, null, $resultSetPrototype);
                },
                        
                'CityDistanceTable' =>  function($sm) {
                    $tableGateway = $sm->get('CityDistanceTableGateway');
                    $table = new CityDistanceTable($tableGateway);
                    return $table;
                },
                'CityDistanceTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new CityDistance());
                    return new TableGateway('tbl_city_distance_list', $dbAdapter, null, $resultSetPrototype);
                },
                        
                'DistanceFareTable' =>  function($sm) {
                    $tableGateway = $sm->get('DistanceFareTableGateway');
                    $table = new DistanceFareTable($tableGateway);
                    return $table;
                },
                 'DistanceFareTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new DistanceFare());
                    return new TableGateway('tbl_distance_fare', $dbAdapter, null, $resultSetPrototype);
                },
                        
                 'DistanceHourFareTable' =>  function($sm) {
                    $tableGateway = $sm->get('DistanceHourFareTableGateway');
                    $table = new DistanceHouFareTable($tableGateway);
                    return $table;
                },  
                 'DistanceHourFareTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new DistanceHourFare());
                    return new TableGateway('tbl_distance_hour_fare', $dbAdapter, null, $resultSetPrototype);
                },       
                        
                        
            ),
        );
    }

}

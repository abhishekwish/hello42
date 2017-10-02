<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PointToPoint;

use PointToPoint\Model\Locations;
use PointToPoint\Model\LocationsTable;
use PointToPoint\Model\Cities;
use PointToPoint\Model\CitiesTable;
use PointToPoint\Model\CabType;
use PointToPoint\Model\CabTypeTable;
use PointToPoint\Model\BaseFare;
use PointToPoint\Model\BaseFareTable;
use PointToPoint\Model\QuantityTable;
use PointToPoint\Model\Quantity;
use PointToPoint\Model\User;
use PointToPoint\Model\UserTable;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\Adapter\DbTable as DbAuthAdapter;
use Zend\Authentication\AuthenticationService;

class Module
{
    
   /* public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }*/
    
    public function onBootstrap(MvcEvent $e) {

        $sm = $e->getApplication()->getServiceManager();

        $router = $sm->get('router');
        $request = $sm->get('request');
        $matchedRoute = $router->match($request);

        $params = $matchedRoute->getParams();

        $controller = $params['controller'];
        $action = $params['action'];
        $module_array = explode('\\', $controller);
        $module = array_pop($module_array);

        $route = $matchedRoute->getMatchedRouteName();

        $e->getViewModel()->setVariables(
            array(
                'CURRENT_MODULE_NAME' => $module,
                'CURRENT_CONTROLLER_NAME' => $controller,
                'CURRENT_ACTION_NAME' => $action,
                'CURRENT_ROUTE_NAME' => $route,
            )
        );
        
                
    }
    
    public function getServiceConfig()
    {
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
                        'QuantityTable' =>  function($sm) {
                    $tableGateway = $sm->get('QuantityTableGateway');
                    $table = new QuantityTable($tableGateway);
                    return $table;
                },
                'QuantityTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Quantity());
                    return new TableGateway('tbl_quantity', $dbAdapter, null, $resultSetPrototype);
                },
                        'LocationsTable' =>  function($sm) {
                    $tableGateway = $sm->get('LocationsTableGateway');
                    $table = new LocationsTable($tableGateway);
                    return $table;
                },
                'LocationsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Locations());
                    return new TableGateway('tbl_locations', $dbAdapter, null, $resultSetPrototype);
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
                        'BaseFareTable' =>  function($sm) {
                    $tableGateway = $sm->get('BaseFareTableGateway');
                    $table = new BaseFareTable($tableGateway);
                    return $table;
                },
                'BaseFareTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new basefare());
                    return new TableGateway('tbl_base_fare', $dbAdapter, null, $resultSetPrototype);
                },
                  
                'UserTable' =>  function($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new BaseFareTable($tableGateway);
                    return $table;
                },
                'UserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new user());
                    return new TableGateway('tbl_user', $dbAdapter, null, $resultSetPrototype);
                },       
                        
                        'AuthService' => function($sm) {
                    //My assumption, you've alredy set dbAdapter
                    //and has users table with columns : user_name and pass_word
                    //that password hashed with md5
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $dbTableAuthAdapter  = new DbAuthAdapter($dbAdapter, 
              'tbl_user','login_name','password', 'MD5(?) and is_active=1');
             
            $authService = new AuthenticationService();
            $authService->setAdapter($dbTableAuthAdapter);
            //$authService->setStorage($sm->get('SanAuth\Model\MyAuthStorage'));
              
            return $authService;
        },
                        
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getViewHelperConfig() {
        return array(
            'invokables' => array(
                'CommonHelper' => 'PointToPoint\View\Helper\CommonHelper',
            )
        );
    }
}

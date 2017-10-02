<?php

namespace Driver;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{    
      
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
                'CommonHelper' => 'Driver\View\Helper\CommonHelper',
            )
        );
    }
}

<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PointToPoint;

return array(
    'router' => array(
     'routes' => array(
         'default' => array(
             'type' => 'segment',
             'options' => array(
                 'route'    => '/[:controller[/:action]]',
                 'defaults' => array(
                     '__NAMESPACE__' => 'PointToPoint\Controller',
                     'controller'    => 'Index',
                     'action'        => 'index',
                 ),
                 'constraints' => [
                     'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                     'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                 ]
             ),
         )
     )
 ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'PointToPoint\Controller\Index' => Controller\IndexController::class,
            'PointToPoint\Controller\Auth' => Controller\AuthController::class
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'CommonPlugin' => 'PointToPoint\Controller\Plugin\CommonPlugin',
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'CommonHelper' => 'PointToPoint\View\Helper\CommonHelper',
            'NotificationsHelper' => 'PointTopoint\View\Helper\NotificationsHelper',
        )
    ),
    
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);

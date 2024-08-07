<?php

declare(strict_types=1);

namespace Application;

namespace Application;

use Application\Model\User;
use Application\Model\UserTable;
use Application\Service\JwtService;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Application\Controller\UserController;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;




class Module implements ConfigProviderInterface
{
    public function getConfig() : array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    // Add this method:
    public function getControllerConfig()
    {
        return [
            'factories' => [
                UserController::class => function($container) {
                    return new UserController($container->get(UserTable::class), $container->get(JwtService::class));
                },
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                JwtService::class => function ($container) {
                    return new JwtService();
                },
                UserTable::class => function($container) {
                    $tableGateway = $container->get(UserTableGateway::class);
                    return new Model\UserTable($tableGateway);
                },
                UserTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                },
                
            ],
        ];
    }    
    
}
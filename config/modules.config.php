<?php

/**
 * List of enabled modules for this application.
 *
 * This should be an array of module namespaces used in the application.
 */
namespace Application;

use Laminas\Mvc\MvcEvent;


return [
    'Laminas\Db',
    'Laminas\Router',
    'Laminas\Validator',
    // 'Mezzio\Cors',
    'Application',
];

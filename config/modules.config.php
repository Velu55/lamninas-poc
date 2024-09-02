<?php

/**
 * List of enabled modules for this application.
 *
 * This should be an array of module namespaces used in the application.
 */
namespace Application;

use Laminas\Mvc\MvcEvent;


return [
    'Laminas\Mvc\Plugin\FilePrg',
    'Laminas\Mvc\Plugin\FlashMessenger',
    'Laminas\Mvc\Plugin\Identity',
    'Laminas\Mvc\Plugin\Prg',
    'Laminas\Session',
    'Laminas\Form',
    'Laminas\Hydrator',
    'Laminas\InputFilter',
    'Laminas\Filter',
    'Laminas\Db',
    'Laminas\Router',
    'Laminas\Validator',
    // 'Mezzio\Cors',
    'Application',
];

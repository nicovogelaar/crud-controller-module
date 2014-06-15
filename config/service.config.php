<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace CrudController;

return array(
    'invokables' => array(
        'CrudController\Repository\CrudRepository' => 'CrudController\Repository\CrudRepository',
        'CrudController\Listener\CrudControllerListener' => 'CrudController\Listener\CrudControllerListener',
    ),
);
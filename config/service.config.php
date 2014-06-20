<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace Nicovogelaar\CrudController;

return array(
    'invokables' => array(
        'Nicovogelaar\CrudController\Repository\CrudRepository' => 'Nicovogelaar\CrudController\Repository\CrudRepository',
        'Nicovogelaar\CrudController\Listener\CrudControllerListener' => 'Nicovogelaar\CrudController\Listener\CrudControllerListener',
    ),
);
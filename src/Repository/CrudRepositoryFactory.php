<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace Nicovogelaar\CrudController\Repository;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

/**
 * @author Nico Vogelaar <nico@nicovogelaar.nl>
 */
class CrudRepositoryFactory implements FactoryInterface
{
    /**
     * Create service
     * 
     * @param ServiceLocatorInterface $serviceLocator Service locator
     * 
     * @return CrudControllerRepository
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $repository = new CrudRepository();

        if ($repository instanceof ObjectManagerAwareInterface) {
            $objectManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
            $repository->setObjectManager($objectManager);
        }

        return $repository;
    }
}
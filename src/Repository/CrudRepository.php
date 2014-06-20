<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace Nicovogelaar\CrudController\Repository;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

/**
 * @author Nico Vogelaar <nico@nicovogelaar.nl>
 */
class CrudRepository implements
    CrudRepositoryInterface,
    ObjectManagerAwareInterface
{
    /**
     * Entity class
     * 
     * @var string
     */
    protected $entityClass;

    /**
     * Object manager
     * 
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * Create resource
     * 
     * @param mixed $object Object
     * 
     * @return void
     */
    public function create($object)
    {
        $this->objectManager->persist($object);
        $this->objectManager->flush();
    }

    /**
     * Update resource
     * 
     * @param mixed $object Object
     * 
     * @return void
     */
    public function update($object)
    {
        $this->objectManager->flush();
    }

    /**
     * Fetch resource
     * 
     * @param mixed $id Id
     * 
     * @return mixed
     */
    public function fetch($id)
    {
        return $this->objectManager
            ->getRepository($this->entityClass)
            ->find($id);
    }

    /**
     * Delete resource
     * 
     * @param mixed $id Id
     * 
     * @return void
     */
    public function delete($id)
    {
        if ($object = $this->fetch($id)) {
            $this->objectManager->remove($object);
            $this->objectManager->flush();
        }
    }

    /**
     * Get entity class
     * 
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * Set entity class
     * 
     * @param string $entityClass Entity class
     * 
     * @return void
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
    }

    /**
     * Set the object manager
     *
     * @param ObjectManager $objectManager Object manager
     * 
     * @return void
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Get the object manager
     *
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }
}
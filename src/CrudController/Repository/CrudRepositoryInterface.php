<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace CrudController\Repository;

/**
 * @author Nico Vogelaar <nico@nicovogelaar.nl>
 */
interface CrudRepositoryInterface
{
    /**
     * Create resource
     * 
     * @param mixed $object Object
     * 
     * @return void
     */
    public function create($object);

    /**
     * Update resource
     * 
     * @param mixed $object Object
     * 
     * @return void
     */
    public function update($object);

    /**
     * Fetch resource
     * 
     * @param mixed $id Id
     * 
     * @return mixed
     */
    public function fetch($id);

    /**
     * Delete resource
     * 
     * @param mixed $id Id
     * 
     * @return void
     */
    public function delete($id);

    /**
     * Get entity class
     * 
     * @return string
     */
    public function getEntityClass();

    /**
     * Set entity class
     * 
     * @param string $entityClass Entity class
     * 
     * @return void
     */
    public function setEntityClass($entityClass);
}
<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace Nicovogelaar\CrudController\Listener;

use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Stdlib\CallbackHandler;

/**
 * @author Nico Vogelaar <nico@nicovogelaar.nl>
 */
class CrudControllerListener implements ListenerAggregateInterface
{
    /**
     * Listeners
     * 
     * @var CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * {@inheritdoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents = $events->getSharedManager();

        $this->listeners[] = $sharedEvents->attach(
            'Nicovogelaar\CrudController\Mvc\Controller\AbstractCrudController',
            'save',
            array($this, 'save'),
            10
        );

        $this->listeners[] = $sharedEvents->attach(
            'Nicovogelaar\CrudController\Mvc\Controller\AbstractCrudController',
            'delete',
            array($this, 'delete'),
            10
        );
    }

    /**
     * {@inheritdoc}
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * Saves object
     * 
     * @param Event $event Event
     * 
     * @return void
     */
    public function save(Event $event)
    {
        $form = $event->getParam('form');
        $repository = $event->getParam('repository');

        $object = $form->getData();

        if ('' == $object->getId()) {
            $repository->create($object);
        } else {
            $repository->update($object);
        }
    }

    /**
     * Deletes object
     * 
     * @param Event $event Event
     * 
     * @return void
     */
    public function delete(Event $event)
    {
        $object = $event->getParam('object');
        $repository = $event->getParam('repository');

        $repository->delete($object->getId());
    }
}
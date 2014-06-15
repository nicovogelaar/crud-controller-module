<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace CrudController\Mvc\Controller;

use Zend\Form\Form;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use CrudController\Exception;
use CrudController\Repository\CrudRepositoryInterface;

/**
 * @author Nico Vogelaar <nico@nicovogelaar.nl>
 */
abstract class AbstractCrudController extends AbstractActionController
{
    /**
     * Crud repository
     * 
     * @var CrudRepositoryInterface
     */
    protected $repository;

    /**
     * Form class
     * 
     * @var Form
     */
    protected $form;

    /**
     * Paginator
     * 
     * @var mixed
     */
    protected $paginator;

    /**
     * Templates (list, add, edit)
     * 
     * @var array
     */
    protected $templates;

    /**
     * Routes
     * 
     * @var array
     */
    protected $routes;

    /**
     * Event identifier
     * 
     * @var string
     */
    protected $eventIdentifier = __CLASS__;

    /**
     * Constructor
     * 
     * @param CrudRepositoryInterface $repository Crud repository
     * @param mixed                   $form       Form
     * @param mixed                   $paginator  Paginator
     * @param array                   $templates  Templates (list, add, edit)
     * @param array                   $routes     Routes (list, add, edit, delete)
     */
    public function __construct(CrudRepositoryInterface $repository, $form,
        $paginator, array $templates, array $routes
    ) {
        $this->repository = $repository;
        $this->form = $form;
        $this->paginator = $paginator;
        $this->templates = $templates;
        $this->routes = $routes;
    }

    /**
     * List
     * 
     * @return ViewModel
     */
    public function listAction()
    {
        if ($this->paginator && method_exists($this->paginator, 'setData')) {
            $this->paginator->setData($this->params()->fromQuery());
        }

        $viewModel = new ViewModel();
        $viewModel->setTemplate($this->templates['list']);
        $viewModel->setVariables(
            array(
                'paginator' => $this->paginator,
                'templates' => $this->templates,
                'routes' => $this->routes
            )
        );

        return $viewModel;
    }

    /**
     * New
     * 
     * @return ViewModel
     */
    public function newAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            if ($response = $this->processForm()) {
                return $response;
            }
        }

        $viewModel = new ViewModel();
        $viewModel->setTemplate($this->templates['new']);
        $viewModel->setVariables(
            array(
                'form' => $this->form,
                'templates' => $this->templates,
                'routes' => $this->routes
            )
        );

        return $viewModel;
    }

    /**
     * Edit
     * 
     * @return ViewModel
     */
    public function editAction()
    {
        $object = $this->getObject();

        $this->form->bind($object);

        $request = $this->getRequest();

        if ($request->isPost()) {
            if ($response = $this->processForm()) {
                return $response;
            }
        }

        $viewModel = new ViewModel();
        $viewModel->setTemplate($this->templates['edit']);
        $viewModel->setVariables(
            array(
                'form' => $this->form,
                'object' => $object,
                'templates' => $this->templates,
                'routes' => $this->routes
            )
        );

        return $viewModel;
    }

    /**
     * Process form
     * 
     * @return Response|null
     */
    public function processForm()
    {
        $request = $this->getRequest();

        $this->form->setData($request->getPost());

        $fm = $this->flashMessenger();

        if (!$this->form->isValid()) {
            $fm->addErrorMessage('The form was not valid. ' . var_export($this->form->getMessages(), true), 'error');

            return;
        }

        try {
            $object = $this->saveObject();
        } catch (\Exception $exception) {
        }

        if (isset($exception)) {
            $fm->addErrorMessage('The object was not saved. ' . $exception->getMessage());

            return;
        } else {
            $fm->addSuccessMessage('The object has been successfully saved!');
        }

        return $this->redirectTo($object);
    }

    /**
     * Save object
     * 
     * @return object
     */
    public function saveObject()
    {
        $object = $this->getObject();

        $params = array(
            'controller' => $this,
            'form' => $this->form,
            'object' => $object,
            'repository' => $this->repository
        );

        $this->getEventManager()->trigger('save', $this, $params);

        return $object;
    }

    /**
     * Redirect to the edit page
     * 
     * @param object $object Object
     * 
     * @return Response
     */
    public function redirectTo($object)
    {
        $route = $this->routes['edit'];
        $params = array('id' => $object->getId());

        return $this->redirect()->toRoute($route, $params);
    }

    /**
     * Delete
     * 
     * @return ViewModel
     */
    public function deleteAction()
    {
        $object = $this->getObject();

        $params = array(
            'controller' => $this,
            'form' => $this->form,
            'object' => $object,
            'repository' => $this->repository
        );

        $fm = $this->flashMessenger();

        try {
            $this->getEventManager()->trigger('delete', $this, $params);

            $fm->addSuccessMessage('The object was deleted.');
        } catch (\Exception $e) {
            $fm->addErrorMessage('Cannot delete object.', 'error');
        }

        $route = $this->routes['list'];
        $params = $this->params()->fromRoute();

        return $this->redirect()->toRoute($route, $params);
    }

    /**
     * Get object
     * 
     * @return object
     * 
     * @throws Exception
     */
    public function getObject()
    {
        $id = $this->getObjectId();

        if ($id > 0) {
            $object = $this->repository->fetch($id);
        }

        if (!isset($object) || !$object) {
            throw new Exception('Object with id "' . $id . '" not found');
        }

        return $object;
    }

    /**
     * Get object Id
     * 
     * @return integer
     */
    public function getObjectId()
    {
        return $this->params('id');
    }
}
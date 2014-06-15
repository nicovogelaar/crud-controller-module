<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace CrudController\ControllerFactory;
 
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractPluginManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

/**
 * @author Nico Vogelaar <nico@nicovogelaar.nl>
 */
class AbstractCrudControllerFactory implements AbstractFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if (!$serviceLocator instanceof AbstractPluginManager) {
            throw new \BadMethodCallException('This abstract factory is meant to be used only with a plugin manager');
        }

        $parentLocator = $serviceLocator->getServiceLocator();
        $config = $parentLocator->get('config');

        return isset($config['crud_controllers'][$requestedName]);
    }
 
    /**
     * {@inheritDoc}
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if (!$this->canCreateServiceWithName($serviceLocator, $name, $requestedName)) {
            throw new \BadMethodCallException('This abstract factory can\'t create service "' . $requestedName . '"');
        }

        $parentLocator = $serviceLocator->getServiceLocator();

        $config = $parentLocator->get('config');

        $config = $config['crud_controllers'][$requestedName];

        if (isset($config['controller_class'])) {
            $controllerClass = $config['controller_class'];
        } else {
            $controllerClass = $requestedName;
        }

        if (!class_exists($controllerClass)) {
            if ('Controller' !== substr($requestedName, -10)) {
                $controllerClass .= 'Controller';
            }
        }

        $fm = $parentLocator->get('FormElementManager');

        $entityClass = isset($config['entity_class']) ?
            $config['entity_class'] : null;
        $form = isset($config['form_class']) ?
            $fm->get($config['form_class']) : null;
        $paginator = isset($config['paginator_class']) ?
          $parentLocator->get($config['paginator_class']) : null;
        $templates = $this->getTemplates($config);
        $routes = $this->getRoutes($config);

        $repository = $parentLocator->get('CrudController\Repository\CrudRepository');
        $repository->setEntityClass($entityClass);

        if ($repository instanceof ObjectManagerAwareInterface
          && null === $repository->getObjectManager()
        ) {
          $repository->setObjectManager($parentLocator->get('Doctrine\ORM\EntityManager'));
        }

        return new $controllerClass($repository, $templates, $routes, $form, $paginator);
    }

    /**
     * Get templates
     * 
     * @param array $config Crud controller config
     * 
     * @return array
     */
    protected function getTemplates(array $config)
    {
        $templates = array();

        if (isset($config['template_prefix'])) {
            $prefix = $config['template_prefix'];
            $templates['prefix'] = $prefix;
            foreach (array('list', 'new', 'edit') as $name) {
                $templates[$name] = $prefix . '/' . $name;
            }
        }

        if (isset($config['templates'])) {
            $templates = array_merge($templates, $config['templates']);
        }

        return $templates;
    }

    /**
     * Get routes
     * 
     * @param array $config Crud controller config
     * 
     * @return array
     */
    protected function getRoutes(array $config)
    {
        $routes = array();

        if (isset($config['route_prefix'])) {
            $prefix = $config['route_prefix'];
            $routes['prefix'] = $prefix;
            foreach (array('list', 'new', 'edit', 'delete') as $name) {
                $routes[$name] = $prefix . '/' . $name;
            }
        }

        if (isset($config['routes'])) {
            $routes = array_merge($routes, $config['routes']);
        }

        return $routes;
    }
}
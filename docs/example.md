# CRUD controller example

## module.config.php
```php
<?php
return array(
    // ...
    'crud_controllers' => array(
        // Example 1 (short)
        'Admin\Controller\Foo' => array(
            'entity_class' => 'Example\Entity\Foo',
            'form_class' => 'Admin\Form\FooForm',
            'paginator_class' => 'Admin\Paginator\FooPaginator',
            'template_prefix' => 'admin/foo',
            'route_prefix' => 'zfcadmin/foo',
        ),
        // Example 2 (short with custom list route)
        'Admin\Controller\Bar' => array(
            'entity_class' => 'Example\Entity\Bar',
            'form_class' => 'Admin\Form\BarForm',
            'template_prefix' => 'admin/bar',
            'route_prefix' => 'zfcadmin/foo/edit/bar',
            'routes' => array(
                'list' => 'zfcadmin/foo/edit',
            ),
        ),
        // Example 3 (extended)
        'Admin\Controller\Baz' => array(
            'entity_class' => 'Example\Entity\Baz',
            'form_class' => 'Admin\Form\BazForm',
            'paginator_class' => 'Admin\Paginator\BazPaginator',
            'templates' => array(
                'list' => 'admin/baz/list',
                'new' => 'admin/baz/new',
                'edit' => 'admin/baz/edit',
            )
            'routes' => array(
                'list' => 'zfcadmin/baz/list',
                'new' => 'zfcadmin/baz/new',
                'edit' => 'zfcadmin/baz/edit',
                'delete' => 'zfcadmin/baz/delete',
            ),
        ),
    ),
    // ...
    'router' => array(
        'routes' => array(
            // The use of ZfcAdmin is not required
            'zfcadmin' => array(
                'child_routes' => array(
                    // ...
                    'foo' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/foo',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Foo',
                                'action' => 'list',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'list' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/list',
                                    'defaults' => array(
                                        'action' => 'list',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                            'new' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/new',
                                    'defaults' => array(
                                        'action' => 'new',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                            'edit' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/edit/:id',
                                    'defaults' => array(
                                        'action' => 'edit',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                            'delete' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/delete/:id',
                                    'defaults' => array(
                                        'action' => 'delete',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                        ),
                    ),
                ),
            ),
            // ...
        ),
    ),
    // ...
);
```

## Controller

The Crud repository, form, paginator, routes and templates will be injected into the controller and are accessible as protected variables.

```php
<?php
namespace Admin\Controller;

use CrudController\Mvc\Controller\AbstractCrudController;

class FooController extends AbstractCrudController
{
}
```
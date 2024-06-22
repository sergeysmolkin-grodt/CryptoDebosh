<?php

namespace App\Presentation\Controller\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;

final class UserAdmin extends AbstractAdmin
{
    public $baseRouteName = 'admin_user';
    public $baseRoutePattern = 'admin/user';

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        // Define your routes here
        $collection->add('list');
        $collection->add('create');
        $collection->add('edit');
        $collection->add('delete');
    }


    protected function configureFormFields(FormMapper $form): void
    {
        // Add form fields here
        $form->add('name', 'text');
        $form->add('email', 'text');
        $form->add('balance', 'number');
        // Add more fields as necessary
    }

    protected function configureListFields(ListMapper $list): void
    {
        // Add list fields here
        $list->addIdentifier('id');
        $list->add('name');
        $list->add('email');
        $list->add('balance');
        // Add more fields as necessary
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        // Add show fields here
        $show->add('id');
        $show->add('name');
        $show->add('email');
        $show->add('balance');
        // Add more fields as necessary
    }
}

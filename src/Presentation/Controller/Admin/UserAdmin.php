<?php

namespace App\Presentation\Controller\Admin;

use App\Domain\Entities\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;

//TODO replace Admin folder to Web folder
final class UserAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'user';

    protected $baseRoutePattern = 'user';

    protected $classnameLabel = 'User';

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('name')
            ->add('email')
            ->add('balance')
            ->add('address')
            ->add('roles');
    }
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
        $form->add('name', 'text')
            ->add('email', 'email')
            ->add('balance', 'number')
            ->add('address', 'text')
            ->add('roles', 'choice', [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'User' => 'ROLE_USER',
                ],
                'multiple' => true,
                'expanded' => true,
            ]);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('id')
            ->add('name')
            ->add('email')
            ->add('balance')
            ->add('address')
            ->add('roles')
            ->add('createdAt');
    }


    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('id')
            ->add('name')
            ->add('email')
            ->add('balance')
            ->add('address')
            ->add('roles')
            ->add('createdAt');
    }
}

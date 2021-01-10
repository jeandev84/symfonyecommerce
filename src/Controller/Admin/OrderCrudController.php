<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }


    public function configureActions(Actions $actions): Actions
    {
        // index nom de la route et detail est le nom de l' action
        return $actions
                 ->add('index', 'detail');
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        /* DateField::new('createdAt') */
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt', 'Passe le'),
            TextField::new('user.fullname', 'Utilisateur'),
            MoneyField::new('total', 'Total du produit')->setCurrency('EUR'),
            TextField::new('carrierName', 'Transporteur'),
            MoneyField::new('carrierPrice', 'Frais de port')->setCurrency('EUR'),
            BooleanField::new('isPaid', 'Payee'),
            // hideOnIndex() pour masquer l'affichage des commandes a la page index
            ArrayField::new('orderDetails', 'Produit achetes')->hideOnIndex()
        ];
    }
}

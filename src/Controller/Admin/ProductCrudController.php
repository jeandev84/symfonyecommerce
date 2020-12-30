<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
         // On indique les types d'inputs qu'on veut afficher et leur format

        return [
           TextField::new('name'),
           // le slug sera generer en fonction du nom du produit
           SlugField::new('slug')->setTargetFieldName('name'),
           // ImageField::new('illustration')->setBasePath('uploads/'),
           ImageField::new('illustration')->setUploadDir('public/uploads/')->setFormTypeOptions(['data_class' => null]),
           TextField::new('subtitle'),
           TextareaField::new('description'),
           MoneyField::new('price')->setCurrency('EUR'),
           AssociationField::new('category')
        ];
    }
}

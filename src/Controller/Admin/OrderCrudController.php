<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\Validator\Constraints\Choice;

class OrderCrudController extends AbstractCrudController
{

    /**
     * @var EntityManagerInterface
    */
    private $em;


    /**
     * @var CrudUrlGenerator
    */
    private $crudUrlGenerator;


    /**
     * OrderCrudController constructor.
     * @param EntityManagerInterface $em
     * @param CrudUrlGenerator $crudUrlGenerator
    */
    public function __construct(EntityManagerInterface $em, CrudUrlGenerator $crudUrlGenerator)
    {
        $this->em = $em;
        $this->crudUrlGenerator = $crudUrlGenerator;
    }


    public static function getEntityFqcn(): string
    {
        return Order::class;
    }


    /**
     * Permet d'ajouter des actions (ajouter, supprimer, modifier)
     *
     * @param Actions $actions
     * @return Actions
    */
    public function configureActions(Actions $actions): Actions
    {
        $updatePreparation = Action::new(
            'updatePreparation',
            'Preparation en cours'
        )->linkToCrudAction('updatePreparation'); // name of method in this controller

        // index nom de la route et detail est le nom de l' action
        return $actions
                 ->add('detail', $updatePreparation)
                 ->add('index', 'detail')
            ;
    }


    /**
     * @param AdminContext $context
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
    */
    public function updatePreparation(AdminContext $context)
    {

         // get instance of Order
         $order = $context->getEntity()->getInstance(); /* dd($order); */

         // set state to 2.
         $order->setState(2);

         // save to the database
         $this->em->flush();

         // add message flash
         $this->addFlash('notice',
             "<span style='color: green;'>
                          <strong>
                             La commande ". $order->getReference() ."
                             a bien <u>en cours de preparation</u>
                          </strong>
                       </span>"
         );

         // generate URL
         $url = $this->crudUrlGenerator->build()
                                       ->setController(
                                           OrderCrudController::class
                                       )->setAction('index') // ACTION::INDEX
                                       ->generateUrl();

         return $this->redirect($url);


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
            ChoiceField::new('state')->setChoices([
                'Non payee' => 0,
                'Payee'     => 1,
                'Preparation en cours' => 2,
                'Livraison en cours' => 3
            ]),
            // hideOnIndex() pour masquer l'affichage des commandes a la page index
            ArrayField::new('orderDetails', 'Produit achetes')->hideOnIndex()
        ];
    }
}

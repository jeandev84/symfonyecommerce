<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class AccountAddressController
 * @package App\Controller
*/
class AccountAddressController extends AbstractController
{

    /**
     * @Route("/compte/adresses", name="account_address")
    */
    public function index(): Response
    {
        /*
         dd($this->getUser());
         dd($this->getUser()->getAddresses());
         twig: app.user.addresses
        */
        return $this->render('account/address.html.twig');
    }


    /**
     * @Route("/compte/ajouter-une-adresse", name="account_address_add")
     */
    public function add(): Response
    {
        return $this->render('account/address_add.html.twig');
    }

}

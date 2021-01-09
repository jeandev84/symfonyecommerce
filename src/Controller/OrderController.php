<?php

namespace App\Controller;

use App\Form\OrderType;
use App\Service\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/commande", name="order")
     * @param Cart $cart
     * @param Request $request
     * @return Response
    */
    public function index(Cart $cart, Request $request): Response
    {
        // Pour recuperer les values et non une persistence collection
        /* dd($this->getUser()->getAddresses()->getValues()); */

        // Si l'utilisateur connecter n'a pas d'adresses
        if(! $this->getUser()->getAddresses()->getValues())
        {
             // alors on le redirige dans la page ou il peut ajouter ses adresses
             return $this->redirectToRoute('account_address_add');
        }

        $form = $this->createForm(OrderType::class, null, [
            // options pour passer l'utilisateur en cours
            // ces options seront recuperer dans options de :
            // OrderType::buildForm(FormBuilderInterface $builder, array $options)
            'user' => $this->getUser()
        ]);


        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
             dd($form->getData());
        }

        return $this->render('order/index.html.twig', [
            'order_form' => $form->createView(),
            'cart' => $cart->getFullItems()
        ]);
    }
}

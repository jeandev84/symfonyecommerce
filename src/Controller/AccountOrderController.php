<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountOrderController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;


    /**
     * RegisterController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @Route("/compte/mes-commandes", name="account_order")
    */
    public function index(): Response
    {
        // On recuperes toutes les commandes qui on ete payee
        $orders = $this->em->getRepository(Order::class)
                           ->findSuccessOrders($this->getUser());

        /* dd($orders); */

        return $this->render('account/order.html.twig', [
            'orders' => $orders
        ]);
    }



    /**
     * @Route("/compte/mes-commandes/{reference}", name="account_order_show")
     */
    public function show($reference): Response
    {
        // On recuperes toutes les commandes qui on ete payee
        $order = $this->em->getRepository(Order::class)
                          ->findOneByReference($reference);


        if(! $order || $order->getUser() != $this->getUser())
        {
             return $this->redirectToRoute('account_order');
        }

        return $this->render('account/order_show.html.twig', [
            'order' => $order
        ]);
    }
}

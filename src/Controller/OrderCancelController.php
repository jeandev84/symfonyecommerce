<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class OrderCancelController
 * @package App\Controller
*/
class OrderCancelController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;


    /**
     * OrderValidateController constructor.
     * @param EntityManagerInterface $em
    */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @Route("/commande/erreur/{stripeSessionId}", name="order_cancel")
     * @param $stripeSessionId
     * @return Response
    */
    public function index($stripeSessionId): Response
    {
        $order = $this->em->getRepository(Order::class)
                          ->findOneByStripeSessionId($stripeSessionId);


        if(! $order || $order->getUser() != $this->getUser())
        {
            return $this->redirectToRoute('home');
        }


        // Envoyer un email a notre utilisateur pour lui indiquer l'echec de paiement

        return $this->render('order_cancel/index.html.twig', [
            'order' => $order
        ]);
    }
}

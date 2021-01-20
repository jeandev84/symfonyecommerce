<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\Cart;
use App\Service\Mail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class OrderSuccessController
 *
 * @package App\Controller
 */
class OrderSuccessController extends AbstractController
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
     * @Route("/commande/merci/{stripeSessionId}", name="order_validate")
     * @param Cart $cart
     * @param $stripeSessionId
     * @return Response
    */
    public function index(Cart $cart, $stripeSessionId): Response
    {
        $order = $this->em->getRepository(Order::class)
                          ->findOneByStripeSessionId($stripeSessionId);


        if(! $order || $order->getUser() != $this->getUser())
        {
            return $this->redirectToRoute('home');
        }

        // si la commande n'a pas ete payee
        if($order->getState() == 0)
        {
            // Vider la session cart
            $cart->remove();

            // Modifier le status isPaid de notre commande (Order)
            $order->setState(1); // 1 : commande payee

            $this->em->flush();

            // Envoyer un email a notre client pour lui confirmer sa commande
            $mail = new Mail();

            $content =  "Bonjour ". $order->getUser()->getFirstname() ."<br>";
            $content .= "Merci pour votre commande.";
            $mail->send(
                $order->getUser()->getEmail(),
                $order->getUser()->getFirstname(),
                'Votre commande la Boutique Francaise est bien validee.',
                $content
            );
        }

        // Afficher les quelques informations de la commande de l' utilisateur

        /* dd($order); */

        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}

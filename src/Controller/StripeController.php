<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Service\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class StripeController
 * @package App\Controller
*/
class StripeController extends AbstractController
{

    /**
     * @Route("/commande/create-session/{reference}", name="stripe_create_session")
     * @param EntityManagerInterface $em
     * @param Cart $cart
     * @param string $reference
     * @return Response
     * @throws \Stripe\Exception\ApiErrorException
    */
    public function index(EntityManagerInterface $em, Cart $cart, $reference): Response
    {
        // Stripe line items
        $productForStripe = [];

        // domain en mode dev
        $YOUR_DOMAIN = 'http://localhost:8000';

        // domain en mode prod (URL de mon vrai site)
        // $YOUR_DOMAIN = 'https://myshop.com';


        // Find order by reference
        /** @var Order $order */
        $order = $em->getRepository(Order::class)
                    ->findOneByReference($reference);

        if(! $order)
        {
            return new JsonResponse(['error' => 'order']);
        }

        /* dd($order->getOrderDetails()->getValues()); */

        // Building stripe line items
        foreach ($order->getOrderDetails()->getValues() as $product)
        {
            $productObject = $em->getRepository(Product::class)
                                ->findOneByName($product->getProduct());

            // stripe items build
            $productForStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN."/uploads/". $productObject->getIllustration()],
                    ],
                ],
                'quantity' => $product->getQuantity(),
            ];
        }

        // add livraison items (transport)
        $productForStripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice(),
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN],
                ],
            ],
            'quantity' => 1,
       ];

       /* dd($productForStripe); */


        Stripe::setApiKey('sk_test_D9sGXxNpdGTAFO5J0FI20GZe');

        $checkoutSession = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [$productForStripe],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);


        $order->setStripeSessionId($checkoutSession->id);

        $em->flush();

        $response = new JsonResponse([
            'id' => $checkoutSession->id
        ]);


        return $response;
    }



//    public function indexTest(EntityManagerInterface $em, Cart $cart, $reference): Response
//    {
//        // Stripe line items
//        $productForStripe = [];
//
//        // domain en mode dev
//        $YOUR_DOMAIN = 'http://localhost:8000';
//
//        // domain en mode prod (URL de mon vrai site)
//        // $YOUR_DOMAIN = 'https://myshop.com';
//
//
//        // Find order by reference
//        $order = $em->getRepository(Order::class)
//            ->findOneByReference($reference);
//
//        if(! $order)
//        {
//            return new JsonResponse(['error' => 'order']);
//        }
//
//        /* dd($order->getOrderDetails()->getValues()); */
//
//        // Building stripe line items
//        foreach ($cart->getFullItems() as $item)
//        {
//            /** @var Product $product */
//            $product = $item['product'];
//
//            /** @var integer $quantity */
//            $quantity = $item['quantity'];
//
//            // stripe items build
//            $productForStripe[] = [
//                'price_data' => [
//                    'currency' => 'eur',
//                    'unit_amount' => $product->getPrice(),
//                    'product_data' => [
//                        'name' => $product->getName(),
//                        'images' => [$YOUR_DOMAIN."/uploads/". $product->getIllustration()],
//                    ],
//                ],
//                'quantity' => $quantity,
//            ];
//        }
//
//        Stripe::setApiKey('sk_test_D9sGXxNpdGTAFO5J0FI20GZe');
//
//        $checkoutSession = Session::create([
//            'payment_method_types' => ['card'],
//            'line_items' => [$productForStripe],
//            'mode' => 'payment',
//            'success_url' => $YOUR_DOMAIN . '/success.html',
//            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
//        ]);
//
//        $response = new JsonResponse([
//            'id' => $checkoutSession->id
//        ]);
//
//
//        return $response;
//    }
}

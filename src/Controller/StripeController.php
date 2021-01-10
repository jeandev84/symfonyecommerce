<?php

namespace App\Controller;

use App\Entity\OrderDetails;
use App\Entity\Product;
use App\Service\Cart;
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
     * @Route("/commande/create-session", name="stripe_create_session")
     * @param Cart $cart
     * @return Response
     * @throws \Stripe\Exception\ApiErrorException
    */
    public function index(Cart $cart): Response
    {
        // Stripe line items
        $productForStripe = [];

        // domain en mode dev
        $YOUR_DOMAIN = 'http://localhost:8000';

        // domain en mode prod (URL de mon vrai site)
        // $YOUR_DOMAIN = 'https://myshop.com';

        // Building stripe line items
        foreach ($cart->getFullItems() as $item)
        {
            /** @var Product $product */
            $product = $item['product'];

            /** @var integer $quantity */
            $quantity = $item['quantity'];

            // stripe items build
            $productForStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getName(),
                        'images' => [$YOUR_DOMAIN."/uploads/". $product->getIllustration()],
                    ],
                ],
                'quantity' => $quantity,
            ];
        }

        Stripe::setApiKey('sk_test_D9sGXxNpdGTAFO5J0FI20GZe');

        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [$productForStripe],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);

        $response = new JsonResponse([
            'id' => $checkoutSession->id
        ]);


        return $response;
    }
}

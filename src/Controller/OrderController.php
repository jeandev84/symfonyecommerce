<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Carrier;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Entity\Product;
use App\Form\OrderType;
use App\Service\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class OrderController
 * @package App\Controller
 *
 * https://stripe.com/docs/checkout/integration-builder
*/
class OrderController extends AbstractController
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

        return $this->render('order/index.html.twig', [
            'order_form' => $form->createView(),
            'cart' => $cart->getFullItems()
        ]);
    }


    /**
     * @Route("/commande/recapitulatif", name="order_recap", methods={"POST"})
     * @param Cart $cart
     * @param Request $request
     * @return Response
    */
    public function add(Cart $cart, Request $request): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            // options pour passer l'utilisateur en cours
            // ces options seront recuperer dans options de :
            // OrderType::buildForm(FormBuilderInterface $builder, array $options)
            'user' => $this->getUser()
        ]);


        $form->handleRequest($request);

        // $deliveryContent = '';
        // $carrier = null;

        if($form->isSubmitted() && $form->isValid())
        {
            $date = new \DateTime();

            /** @var Carrier $carrier */
            $carrier = $form->get('carriers')->getData(); /* dd($carrier); */


            /** @var Address $delivery */
            $delivery = $form->get('addresses')->getData();  /* dd($delivery); */
            $deliveryContent = $delivery->getFirstname() . ' '. $delivery->getLastname();
            $deliveryContent .= '<br/>'. $delivery->getPhone();


            // on ajoute la compagnie si elle est renseignee
            if($company = $delivery->getCompany())
            {
                $deliveryContent .= '<br/>'. $company;
            }

            $deliveryContent .= '<br/>'. $delivery->getAddress();
            $deliveryContent .= '<br/>'. $delivery->getPostal() .' '. $delivery->getCity();
            $deliveryContent .= '<br/>'. $delivery->getCountry();
            $deliveryContent .= '<br/>'. $delivery->getAddress();

            /* dd($deliveryContent); */


            /* dd($form->getData()); */
            // Enregistrer ma commande Order()
            $order = new Order();

            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carrier->getName());
            $order->setCarrierPrice($carrier->getPrice());
            $order->setDelivery($deliveryContent);
            $order->setIsPaid(0); // 0 or false
            $this->em->persist($order);

            // Enregistrer mes produits OrderDetails()
            foreach ($cart->getFullItems() as $item)
            {
                 /** @var Product $product */
                 $product = $item['product'];

                 /** @var integer $quantity */
                 $quantity = $item['quantity'];

                 $orderDetails = new OrderDetails();
                 $orderDetails->setMyOrder($order);
                 $orderDetails->setProduct($product->getName());
                 $orderDetails->setQuantity($quantity);
                 $orderDetails->setPrice($product->getPrice());
                 $orderDetails->setTotal($product->getPrice() * $quantity);

                 $this->em->persist($orderDetails);
            }

            return $this->render('order/add.html.twig', [
                'cart' => $cart->getFullItems(),
                'carrier' => $carrier,
                'delivery' => $deliveryContent
            ]);
        }

        return $this->redirectToRoute('cart');
    }
}

<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class HomeController
 * @package App\Controller
*/
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @return Response
    */
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }


    /**
     * @param SessionInterface $session
     * @return Response
    */
    public function cartDemo(SessionInterface $session): Response
    {
        // Ajout a mon panier
        $session->set('cart', [
            [
                'id' => 522,
                'quantity' => 12
            ]
        ]);

        $session->remove('cart');


        // Obtention de mon panier
        $cart = $session->get('cart');


        dd($cart);
        return $this->render('home/index.html.twig');
    }
}

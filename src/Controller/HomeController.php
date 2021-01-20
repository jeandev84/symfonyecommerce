<?php
namespace App\Controller;

use App\Entity\Header;
use App\Entity\Product;
use App\Service\Mail;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var EntityManagerInterface
    */
    private $em;



    /**
     * HomeController constructor.
     * @param EntityManagerInterface $entityManager
    */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @Route("/", name="home")
     * @return Response
    */
    public function index(): Response
    {
        ini_set('upload_max_filesize', '20M');
        ini_set('max_file_uploads', '20M');

        // Mettre les produits a la une
        $products = $this->em->getRepository(Product::class)
                             ->findByIsBest(1);

        $headers = $this->em->getRepository(Header::class)
                            ->findAll();

        return $this->render('home/index.html.twig', [
            'products' => $products,
            'headers'  => $headers
        ]);
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


    /**
     * Send Mail test
    */
    public function sendMailDemo()
    {
        $mail = new Mail();
        $mail->send(
            'jeanyao@ymail.com',
            'John Doe',
            'Mon premier mail',
            "Bonjour John, j'espere que tu vas bien"
        );
    }
}

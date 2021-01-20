<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use App\Service\Search;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class ProductController
 * @package App\Controller
*/
class ProductController extends AbstractController
{

    /**
     * @var EntityManagerInterface
   */
    private $entityManager;


    /**
     * ProductController constructor.
     * @param EntityManagerInterface $entityManager
    */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/nos-produits", name="products")
     * @param Request $request
     * @param ProductRepository $productRepository
     * @return Response
    */
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        /*
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        */

        // Liste de tous mes produits
        $products = $productRepository->findAll();

        $search = new Search();

        // Formulaire de recherche
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
             /* $search = $form->getData(); dd($search); */
             $products = $productRepository->findWithSearch($search);

        } /* else {
            // Liste de tous mes produits
            $products = $productRepository->findAll();

        }*/


        return $this->render('product/index.html.twig', [
            'products' => $products,
            'search_form' => $form->createView()
        ]);
    }


    /**
     * @Route("/produit/{slug}", name="product")
     * @param $slug
     * @return Response
     */
    public function show($slug): Response
    {
        $product = $this->entityManager
                        ->getRepository(Product::class)
                        ->findOneBySlug($slug);


        // Afficher les produits a la une
        $products = $this->entityManager->getRepository(Product::class)
                         ->findByIsBest(1);

        // Si product n'a pas ete trouve
        // alors on redirige vers la liste des products
        if(! $product)
        {
            return $this->redirectToRoute('products');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'products' => $products
        ]);
    }


//    /*
//     * @Route("/produit/{slug}", name="product")
//     * @param Product $product
//     * @return Response
//    */
//    public function showDemo(Product $product): Response
//    {
//        return $this->render('product/index.html.twig', [
//            'product' => $product
//        ]);
//    }
//    */
}

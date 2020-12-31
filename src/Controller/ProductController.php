<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @param ProductRepository $productRepository
     * @return Response
    */
    public function index(ProductRepository $productRepository): Response
    {
        /*
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        */

        $products = $productRepository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products
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

        // Si product n'a pas ete trouve
        // alors on redirige vers la liste des products
        if(! $product)
        {
            return $this->redirectToRoute('products');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }


//    /*
//     * @Route("/produit/{slug}", name="product")
//     * @param Product $product
//     * @return Response
//    */
//    public function show(Product $product): Response
//    {
//        return $this->render('product/index.html.twig', [
//            'product' => $product
//        ]);
//    }
//    */
}

<?php
namespace App\Service;


use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class Cart
 *
 * Class representant notre panier
 * @package App\Service
*/
class Cart
{


     /**
      * @var EntityManagerInterface
     */
     private $em;



     /**
      * @var SessionInterface
     */
     private $session;


    /**
     * Cart constructor.
     * @param EntityManagerInterface $em
     * @param SessionInterface $session
     */
     public function __construct(EntityManagerInterface $em, SessionInterface $session)
     {
          $this->em = $em;
          $this->session = $session;
     }


     /**
      * Ajout de produit a mon panier
      * @param $id
     */
     public function add($id)
     {
          // stocker le panier
          $cart = $this->session->get('cart', []);

          // si on a un produit deja insere, on ajoute en quantite
          if(! empty($cart[$id]))
          {
              $cart[$id]++;

          }else{

              $cart[$id] = 1;
          }

          /*
          $this->session->set('cart', [
              [
                  'id' => $id,
                  'quantity' => 1
              ]
          ]);
          */

          $this->session->set('cart', $cart);
     }



    /**
     * Affiche mon panier
    */
    public function get()
    {
       return $this->session->get('cart');
    }


    /**
     * Supprimer tous les produits de mon panier
    */
    public function remove()
    {
        return $this->session->remove('cart');
    }


    /**
     * Remove item from cart
     *
     * @param $id
     * @return mixed
    */
    public function delete($id)
    {
        $cart = $this->session->get('cart', []);

        unset($cart[$id]);

        return $this->session->set('cart', $cart);
    }



    /**
     * @param $id
     * @return mixed
    */
    public function decrease($id)
    {
        $cart = $this->session->get('cart', []);

        // verifier si la quantite de mon produit n'est pas egale a 1
        if($cart[$id] > 1)
        {
            // retirer une quantite (-1)
            $cart[$id]--;

        }else{

            // supprimer mon produit
            unset($cart[$id]);
        }

        return $this->session->set('cart', $cart);
    }


    /**
     * Get cart items
    */
    public function getFullItems(): array
    {
        $cartComplete = [];

        if($items = $this->get())
        {
            foreach ($items as $id => $quantity)
            {
                $productObject = $this->em->getRepository(Product::class)->findOneById($id);

                if(! $productObject)
                {
                     $this->delete($id);
                     continue;
                }

                $cartComplete[] = [
                    'product' => $productObject,
                    'quantity' => $quantity
                ];
            }
        }

        return $cartComplete;
    }
}
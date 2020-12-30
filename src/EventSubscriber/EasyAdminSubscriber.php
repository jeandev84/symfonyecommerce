<?php
namespace App\EventSubscriber;


use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelInterface;


/**
 * Class EasyAdminSubscriber
 *
 * @package App\EventSubscriber
*/
class EasyAdminSubscriber implements EventSubscriberInterface
{


    /**
     * @var KernelInterface
    */
    private $appKernel;


    /**
     * EasyAdminSubscriber constructor.
     * @param KernelInterface $appKernel
    */
    public function __construct(KernelInterface $appKernel)
    {
        $this->appKernel = $appKernel;
    }


    public static function getSubscribedEvents()
    {
        return [
           // evenement avant que l'entite soit creer
           // On dit avant qu'une entite produit soit creer ou persistee en base,
           // je veux que tu fasse appelle a setIllustration
           BeforeEntityPersistedEvent::class => ['setIllustration'],
        ];
    }


    public function setIllustration(BeforeEntityPersistedEvent $event)
    {
          $entity = $event->getEntityInstance();

          // dd($entity);

          // $tmpName = $entity->getIllustration();

          /* dd($_FILES['Product']['name']['illustration']['file']); */
          $extension = pathinfo($_FILES['Product']['name']['illustration']['file'], PATHINFO_EXTENSION);
          $filename = uniqid();
          $tmpName = $_FILES['Product']['tmp_name']['illustration']['file'];

          /*   dump($tmpName); dd($extension); */

          $project_dir = $this->appKernel->getProjectDir();

          move_uploaded_file($tmpName, $project_dir . '/public/uploads/'. $filename .'.'. $extension);

          /* dump($entity); */

         $entity->setIllustration($filename. '.'. $extension);
    }
}
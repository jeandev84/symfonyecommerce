<?php
namespace App\Form;


use App\Entity\Category;
use App\Service\Search;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



/**
 * Class SearchType
 * @package App\Form
*/
class SearchType extends AbstractType
{

    /**
     * Build Form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
          $builder->add('string', TextType::class, [
              'label' => false,
              'required' => false,
              'attr'  => [
                  'placeholder' => 'Votre recherche ...',
                  'class' => 'form-control-sm'
              ]
          ])
          ->add('categories', EntityType::class, [
             'label' => false,
             'required' => false,
             'class' => Category::class,
             'multiple' => true,
             'expanded' => true
          ])
          ->add('submit', SubmitType::class, [
              'label' => 'Filtrer',
              'attr'  => [
                  'class' => 'btn-block btn-info'
              ]
          ]);
    }


    /**
     * Resolve options
     *
     * @param OptionsResolver $resolver
    */
    public function configureOptions(OptionsResolver $resolver)
    {
         $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'csrf_protection' => false
         ]);
    }


    /**
     * Give clean URL
     *
     * @return string
    */
    public function getBlockPrefix()
    {
        return '';
    }
}
<?php

namespace SW\DocManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType
{
    private $withfile;
    
    public function __construct($withfile) {
        $this->withfile = $withfile;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', 'text', array(
                'required'=> false
            ))
            ->add('path', 'hidden', array(
                'required'=> false
            ))
            ->add('nameAlreadyUsed', 'hidden', array(
                'required'=> false
            ))
            ->add('name', 'text', array(
                'attr'=> array('placeholder'=>'Dateiname')
            ))
            ->add($builder->create('date', 'text', array(
                'attr'=> array('placeholder'=>'Initialen')
            ))
                ->addViewTransformer(new DateTimeToStringTransformer(null, null, 'd.m.Y')))                
            ->add('initials', 'text', array(
                'required'=> false,
                'attr'=> array('readonly'=>'readonly')
            ))
            ->add('creator', new UserType(), array(
                'attr'=> array('readonly'=>'readonly')
            ))
            ->add('category', new CategoryType(), array(
                'attr'=> array('readonly'=>'readonly')))
            ->add('subCategories', 'collection', array(
                'attr'=> array('readonly'=>'readonly'),
                'type' => new CategoryType(),
                'allow_add' => true,
                ))
        ;
        
        if ($this->withfile) {            
            $builder->add('file', 'file', array(
                'attr' => array('class' => 'button full-size bg-gray fg-white')));
        }
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SW\DocManagerBundle\Entity\Document'
        ));
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'sw_docmanagerbundle_document';
    }
}

<?php

namespace bizbink\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * TagType
 *
 * @author Matthew Vanderende <matthew@vanderende.ca>
 */
class TagType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', NULL, array(
                    'required' => true,
                    'label' => false,
                    'translation_domain' => 'blog'));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'entity_type' => 'text',
            'data_class' => 'bizbink\BlogBundle\Entity\Tag'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'blogbundle_tag';
    }

}

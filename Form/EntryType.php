<?php

namespace bizbink\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * EntryType
 *
 * @author Matthew Vanderende <matthew@vanderende.ca>
 */
class EntryType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title', 'text', array(
                    'required' => true,
                    'label' => 'entry.form.title',
                    'translation_domain' => 'blog'))
                ->add('content', 'ckeditor', array(
                    'required' => true,
                    'label' => 'entry.form.content',
                    'translation_domain' => 'blog'))
                ->add('category', 'entity', array(
                    'required' => false,
                    'class' => 'BlogBundle:Category',
                    'choice_label' => 'name',
                    'label' => 'entry.form.category',
                    'translation_domain' => 'blog'))
                ->add('tags', 'collection', array(
                    'required' => false,
                    'type' => new TagType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'label' => 'entry.form.tags',
                    'translation_domain' => 'blog'))
                ->add('datetime', 'datetime', array(
                    'required' => true,
                    'label' => 'entry.form.datetime',
                    'translation_domain' => 'blog'))
                ->add('save', 'submit', array(
                    'label' => 'entry.form.submit',
                    'translation_domain' => 'blog'));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'bizbink\BlogBundle\Entity\Entry'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'blogbundle_entry';
    }

}

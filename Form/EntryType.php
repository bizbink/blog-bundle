<?php

namespace bizbink\BlogBundle\Form;

use bizbink\BlogBundle\Form\TagType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                ->add('title', TextType::class, array(
                    'required' => true,
                    'label' => 'entry.form.title',
                    'translation_domain' => 'blog'))
                ->add('content', CKEditorType::class, array(
                    'required' => true,
                    'label' => 'entry.form.content',
                    'translation_domain' => 'blog'))
                ->add('category', EntityType::class, array(
                    'required' => false,
                    'class' => 'BlogBundle:Category',
                    'choice_label' => 'name',
                    'label' => 'entry.form.category',
                    'translation_domain' => 'blog'))
                ->add('tags', CollectionType::class, array(
                    'required' => false,
                    'entry_type' => TagType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'label' => 'entry.form.tags',
                    'translation_domain' => 'blog'))
                ->add('datetime', DateTimeType::class, array(
                    'required' => true,
                    'label' => 'entry.form.datetime',
                    'translation_domain' => 'blog'))
                ->add('save', SubmitType::class, array(
                    'label' => 'entry.form.submit',
                    'translation_domain' => 'blog'));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'bizbink\BlogBundle\Entity\Entry'
        ));
    }

}

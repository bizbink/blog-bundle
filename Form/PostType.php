<?php

namespace bizbink\BlogBundle\Form;

use bizbink\BlogBundle\Entity\Category;
use bizbink\BlogBundle\Entity\Post;
use bizbink\BlogBundle\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\CrudOperationBindable;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => new NotBlank()
            ])
            ->add('slug', TextType::class, [
                'constraints' => new NotBlank()
            ])
            ->add('content', TextareaType::class, [
                'constraints' => new NotBlank()
            ])
            ->add('tagsText', TextType::class, [
                'label' => 'Tags',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Comma (;) separated')
            ])
            ->add('category', EntityType::class, [
                'placeholder' => 'Uncategorized',
                'required' => false,
                'class' => Category::class,
                'choice_label' => 'name',])
            ->add('submit', SubmitType::class, [
                'label' => $options['submit_label'],]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            $post = $event->getData();

            if ($post instanceof Post) {
                $tags = $post->getTags();
                foreach ($tags as $key => $element) {
                    $tagText = $element->getName();
                    if ($key !== array_key_last($tags->toArray())) {
                        $tagText .= ", ";
                    }
                    $post->appendTagsText($tagText);
                }
            }
        });

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            $post = $event->getData();
            $form = $event->getForm();

            if (!$post || null !== $post->getId()) {
                $form
                    ->add('delete', SubmitType::class, [
                        'label' => 'Delete'])
                    ->add('publish', SubmitType::class, [
                        'label' => $options['publish_label'],]);
            }
        });

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($options) {
            $post = $event->getData();
            $form = $event->getForm();
            if ($post instanceof Post) {
                $formTags = new ArrayCollection();
                foreach (explode(',', $form->get('tagsText')->getData()) as $tag) {
                    $formTags->add(new Tag(trim($tag)));
                }

                $originalTags = new ArrayCollection();
                foreach ($post->getTags() as $tag) {
                    $originalTags->add($tag);
                }

                foreach ($originalTags as $originalTag) {
                    if (!$formTags->contains($originalTag)) {
                        $post->removeTag($originalTag);
                    }
                }

                foreach ($formTags as $formTag) {
                    if (!$originalTags->contains($formTag)) {
                        $post->addTag($formTag);
                    }
                }
            }
        });

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($options) {
            $post = $event->getData();
            if ($post instanceof Post) {
                $tagRepository = $options['entity_manager']->getRepository(Tag::class);
                foreach ($post->getTags() as $tag) {
                    if ($tagFound = $tagRepository->findOneBy(['name' => $tag->getName()])) {
                        if ($tagFound instanceof Tag) {
                            $post->removeTag($tag);
                            $tag = $tagFound;
                            $post->addTag($tag);
                        }
                    } else {
                        $tag->setSlug(str_replace(' ', '-', strtolower($tag->getName())));
                    }
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Post::class,
            'submit_label' => 'Submit',
            'publish_label' => 'Publish',
            'entity_manager' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'blogbundle_post';
    }


}

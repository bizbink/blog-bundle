<?php


namespace bizbink\BlogBundle\EventSubscriber;


use bizbink\BlogBundle\Event\PageViewEvent;
use bizbink\BlogBundle\Event\PostViewEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ViewSubscriber implements EventSubscriberInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            PostViewEvent::class => [
                ['incrementPostView', 0]
            ],
            PageViewEvent::class => [
                ['pageView', 0]
            ],
        ];
    }

    public function incrementPostView(PostViewEvent $event)
    {
        $post = $event->getPost();
        $post->setViews($post->getViews() + 1);
        $this->em->persist($event->getPost());
        $this->em->flush();
    }

    public function pageView(PageViewEvent $event)
    {
        // TODO: More analytics
    }
}
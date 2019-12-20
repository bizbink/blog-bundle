<?php


namespace bizbink\BlogBundle\EventSubscriber;


use bizbink\BlogBundle\Event\PageEvent;
use bizbink\BlogBundle\Event\PostEvent;
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
            PostEvent::VIEW => [
                ['incrementPostView', 0]
            ],
            PageEvent::VIEW => [
                ['pageView', 0]
            ],
        ];
    }

    public function incrementPostView(PostEvent $event)
    {
        $post = $event->getPost();
        $post->setViews($post->getViews() + 1);
        $this->em->persist($event->getPost());
        $this->em->flush();
    }
    public function pageView(PageEvent $event) {
        // TODO: More analytics
    }
}
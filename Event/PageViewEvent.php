<?php


namespace bizbink\BlogBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class PageViewEvent extends Event
{
    const VIEW = 'blog.page.view';

    private $page;

    public function __construct(int $page)
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }
}
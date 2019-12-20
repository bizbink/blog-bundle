<?php

namespace bizbink\BlogBundle\Twig;

use Parsedown;
use Twig\Extension\RuntimeExtensionInterface;

class BlogRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
    }

    public function markdownToHTML($text)
    {
        $parsedown = new Parsedown();
        return $parsedown->parse($text);
    }
}
<?php

namespace bizbink\BlogBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class BlogExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('markdown', [BlogRuntime::class, 'markdownToHTML'], ['is_safe'=> ['html']]),
        ];
    }
}
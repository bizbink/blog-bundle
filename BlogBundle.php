<?php

/* 
 * Copyright (C) Matthew Vanderende - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace bizbink\BlogBundle;

use bizbink\BlogBundle\DependencyInjection\bizbinkBlogExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * BlogBundle
 *
 * @author Matthew Vanderende <matthew@vanderende.ca>
 */
class BlogBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new bizbinkBlogExtension();
    }
}

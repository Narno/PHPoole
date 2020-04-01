<?php
/*
 * Copyright (c) Arnaud Ligny <arnaud@ligny.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cecil\Step;

use Cecil\Util;
use MatthiasMullie\Minify;

/**
 * Post process CSS files.
 */
class PostProcessCss extends AbstractPostProcess
{
    /**
     * {@inheritdoc}
     */
    public function init($options)
    {
        $this->type = 'css';
        parent::init($options);
    }

    /**
     * {@inheritdoc}
     */
    public function setProcessor()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function processFile(\Symfony\Component\Finder\SplFileInfo $file)
    {

            $minifier = new Minify\CSS($file->getPathname());
            $minified = $minifier->minify();
            \Cecil\Util::getFS()->dumpFile($file->getPathname(), $minified);

    }
}

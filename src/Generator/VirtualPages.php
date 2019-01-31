<?php
/*
 * Copyright (c) Arnaud Ligny <arnaud@ligny.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cecil\Generator;

use Cecil\Collection\Page\Collection as PagesCollection;
use Cecil\Collection\Page\Page;
use Cecil\Page\Type;

/**
 * Class VirtualPages.
 */
class VirtualPages extends AbstractGenerator implements GeneratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function generate(PagesCollection $pagesCollection, \Closure $messageCallback)
    {
        $generatedPages = new PagesCollection();

        $virtualpages = $this->config->get('site.virtualpages');
        foreach ($virtualpages as $file => $frontmatter) {
            if ($frontmatter == 'disabled') {
                continue;
            }
            $pageId = Page::slugify(sprintf('%s', $file));
            $page = (new Page())
                ->setId($pageId)
                ->setPathname($pageId)
                ->setType(Type::PAGE);
            $page->setVariables($frontmatter);
            if (!empty($frontmatter['layout'])) {
                $page->setLayout($frontmatter['layout']);
            }
            if (!empty($frontmatter['permalink'])) {
                $page->setPermalink($frontmatter['permalink']);
            }
            $generatedPages->add($page);
        }

        return $generatedPages;
    }
}

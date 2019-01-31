<?php
/*
 * This file is part of the Cecil/Cecil package.
 *
 * Copyright (c) Arnaud Ligny <arnaud@ligny.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Default config
return [
    'site' => [
        'title'        => 'My Webiste',
        'baseline'     => 'An amazing static website!',
        'baseurl'      => 'http://localhost:8000/',
        'canonicalurl' => false,
        'description'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        'taxonomies'   => [
            'tags'       => 'tag',
            'categories' => 'category',
        ],
        'paginate' => [
            'max'  => 5,
            'path' => 'page',
        ],
        'date' => [
            'format'   => 'j F Y',
            'timezone' => 'Europe/Paris',
        ],
        'virtualpages' => [
            'robots' => [
                'title'  => 'Robots.txt',
                'layout' => 'robots.txt',
                'url'    => 'robots.txt',
            ],
            'sitemap' => [
                'title'      => 'XML sitemap',
                'layout'     => 'sitemap.xml',
                'url'        => 'sitemap.xml',
                'changefreq' => 'monthly',
                'priority'   => '0.5',
            ],
            '404' => [
                'title'  => '404 page',
                'layout' => '404.html',
                'url'    => '404.html',
            ],
        ],
        'output' => [
            'dir'      => '_site',
            'formats'  => [
                'html' => [
                    'mediatype' => 'text/html',
                    'filename'  => 'index.html',
                ],
                'rss' => [
                    'mediatype' => 'application/rss+xml',
                    'filename'  => 'rss.xml',
                ],
                'json' => [
                    'mediatype' => 'application/json',
                    'filename'  => 'index.json',
                ],
            ],
            'pagetypeformats' => [
                'page'     => ['html', 'json'],
                'homepage' => ['html', 'rss', 'json'],
                'section'  => ['html', 'rss', 'json'],
                'taxonomy' => ['html', 'rss'],
                'terms'    => ['html'],
            ],
        ],
    ],
    'content' => [
        'dir' => 'content',
        'ext' => ['md', 'markdown', 'mdown', 'mkdn', 'mkd', 'text', 'txt'],
    ],
    'frontmatter' => [
        'format' => 'yaml',
    ],
    'body' => [
        'format' => 'md',
    ],
    'static' => [
        'dir' => 'static',
    ],
    'layouts' => [
        'dir'      => 'layouts',
        'internal' => [
            'dir' => 'res/layouts',
        ],
    ],
    'themes' => [
        'dir' => 'themes',
    ],
    'generators' => [
        10 => 'Cecil\Generator\Section',
        20 => 'Cecil\Generator\Taxonomy',
        30 => 'Cecil\Generator\Homepage',
        40 => 'Cecil\Generator\Pagination',
        50 => 'Cecil\Generator\Alias',
        35 => 'Cecil\Generator\ExternalBody',
        36 => 'Cecil\Generator\VirtualPages',
        60 => 'Cecil\Generator\Redirect',
    ],
];

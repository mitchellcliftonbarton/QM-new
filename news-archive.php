<?php
global $params;
global $paged;

if (!isset($paged) || !$paged){
  $paged = 1;
} else {
  $paged = $GLOBALS['params']['paged'];
}

$context = Timber::context();
$context['site_theme'] = 'green-theme';
$context['archive_slug'] = '/news/';

/*
----------------
SUBLINKS DEFINITIONS
----------------
*/

$sublinks = [
  'title' => 'News',
  'title_link' => '/news',
  'subtitle' => 'Archive',
  'type' => 'static'
];

$context['sublinks'] = $sublinks;


/*
----------------
PAGE DATA
----------------
*/

$args = array(
  'post_type' => 'post',
  'posts_per_page' => 20,
  'paged' => $paged
);

$context['posts'] = new Timber\PostQuery($args);
$context['paged'] = $paged;

Timber::render( 'news-archive.twig', $context );

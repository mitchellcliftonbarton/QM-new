<?php
global $params;
global $paged;

if (!isset($paged) || !$paged){
  $paged = 1;
} else {
  $paged = $GLOBALS['params']['paged'];
}

$context = Timber::context();
$context['site_theme'] = 'pink-theme';
$context['archive_slug'] = '/programs/';

$sort_val = false;
if (isset($_GET['sort'])) {
  $sort_val = $_GET['sort'];
} else {
  $sort_val = 'date'; // this is the default
}

$context['sort_val'] = $sort_val;

/*
----------------
SUBLINKS DEFINITIONS
----------------
*/

$sublinks = [
  'title' => 'Past Programs Archive',
  'type' => 'sort',
  'items' => [
    [
      'title' => 'Date',
      'link' => '/programs/archive',
      'active' => $sort_val == 'date'
    ],
    [
      'title' => 'Name',
      'link' => '/programs/archive?sort=name',
      'active' => $sort_val == 'name'
    ]
  ]
];

$context['sublinks'] = $sublinks;


/*
----------------
PAGE DATA
----------------
*/

$sortby = null;
$order = 'DESC';

if ($sort_val == 'date') {
  $sortby = 'date';
} else if ($sort_val == 'name') {
  $sortby = 'title';
  $order = 'ASC';
} else {
  $sortby = 'date';
}

$args = array(
  'post_type' => 'program',
  'posts_per_page' => 20,
  'orderby' => $sortby,
  'order' => $order,
  'paged' => $paged
);

$context['posts'] = new Timber\PostQuery($args);
$context['paged'] = $paged;

Timber::render( 'programs-archive.twig', $context );

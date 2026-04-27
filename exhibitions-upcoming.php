<?php
global $params;
global $paged;

if (!isset($paged) || !$paged){
  $paged = 1;
} else {
  $paged = $GLOBALS['params']['paged'];
}

$context = Timber::context();
$context['site_theme'] = 'forest-green-theme';

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
  'title' => 'Upcoming Exhibitions and Projects',
  'title_link' => '/exhibitions/upcoming',
  'type' => 'sort',
  'items' => [
    [
      'title' => 'Date',
      'link' => '/exhibitions/upcoming',
      'active' => $sort_val == 'date'
    ],
    [
      'title' => 'Exhibition',
      'link' => '/exhibitions/upcoming?sort=exhibition', // aka title,
      'active' => $sort_val == 'exhibition'
    ]
  ]
];

$context['sublinks'] = $sublinks;


/*
----------------
PAGE DATA
----------------
*/

$today = date('Ymd');
$sortby = null;
$order = 'DESC';
$meta_key = 'starting_date_new';

if ($sort_val == 'date') {
  $sortby = 'meta_value';
} else if ($sort_val == 'exhibition') {
  $sortby = 'title';
  $order = 'ASC';
} else {
  $sortby = 'meta_value';
}

$args = array(
  'post_type' => 'exhibition',
  'posts_per_page' => 20,
  'meta_key' => $meta_key,
  'orderby' => $sortby,
  'order' => $order,
  'paged' => $paged,
  'meta_query' => array(
    array(
      'key' => 'starting_date_new',
      'value' => $today,
      'compare' => '>='
    ),
    array(
      array(
        'key' => 'exhibition_type',
        'value' => 'On Long Term View',
        'compare' => '!='
      )
    ),
    array(
      'relation' => 'OR',
      array(
        'key' => 'is_project',
        'value' => '0'
      ),
      array(
        'key' => 'is_project',
        'compare' => 'NOT EXISTS', 
      )
    )
  )
);

$posts = new Timber\PostQuery($args);
$context['posts'] = $posts;
$context['paged'] = $paged;

Timber::render( 'exhibitions-past.twig', $context );

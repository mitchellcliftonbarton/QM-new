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
$context['button_theme'] = 'neon';

$sort_val = 'date'; // default
$filter_val = 'all'; // default

if (isset($_GET['sort'])) {
  $sort_val = $_GET['sort'];
}

if (isset($_GET['filter'])) {
  $filter_val = $_GET['filter'];
}

$context['sort_val'] = $sort_val;
$context['filter_val'] = $filter_val;

/*
----------------
SUBLINKS DEFINITIONS
----------------
*/

$sublinks = [
  'title' => 'Calendar',
  'title_link' => '/calendar',
  'subtitle' => $GLOBALS['params']['year'],
  'type' => 'sort',
  'items' => [
    [
      'title' => 'Date',
      'link' => '/calendar/year/' . $GLOBALS['params']['year'] . '?sort=date&' . ($filter_val ? 'filter=' . $filter_val : 'filter=all'),
      'active' => $sort_val == 'date'
    ],
    [
      'title' => 'Title',
      'link' => '/calendar/year/' . $GLOBALS['params']['year'] . '?sort=title&' . ($filter_val ? 'filter=' . $filter_val : 'filter=all'),
      'active' => $sort_val == 'title'
    ]
  ],
  'filters' => [
    [
      'title' => 'All',
      'link' => '/calendar/year/' . $GLOBALS['params']['year'] . '?' . ($sort_val ? 'sort=' . $sort_val : 'sort=date') . '&filter=all',
      'active' => $filter_val == 'all'
    ],
    [
      'title' => 'Exhibition',
      'link' => '/calendar/year/' . $GLOBALS['params']['year'] . '?' . ($sort_val ? 'sort=' . $sort_val : 'sort=date') . '&filter=exhibition',
      'active' => $filter_val == 'exhibition'
    ],
    [
      'title' => 'Event',
      'link' => '/calendar/year/' . $GLOBALS['params']['year'] . '?' . ($sort_val ? 'sort=' . $sort_val : 'sort=date') . '&filter=event',
      'active' => $filter_val == 'event'
    ]
  ]
];

$context['sublinks'] = $sublinks;

/*
----------------
PAGE DATA
----------------
*/

$firstDayOfYear = date('Ymd', strtotime($GLOBALS['params']['year'] . '-01-01'));
$lastDayOfYear = date('Ymd', strtotime($GLOBALS['params']['year'] . '-12-31 11:59:59 PM'));

// $today = date('Ymd');
$sortby = null;
$filterby = null;

if ($sort_val == 'date') {
  $sortby = 'meta_value';
} else if ($sort_val == 'title') {
  $sortby = 'title';
} else {
  $sortby = 'meta_value';
}

if ($filter_val == 'all') {
  $filterby = array('exhibition', 'event');
} else if ($filter_val == 'exhibition') {
  $filterby = 'exhibition';
} else if ($filter_val == 'event') {
  $filterby = 'event';
} else {
  $filterby = array('exhibition', 'event');
}

// echo $firstDayOfYear;

remove_all_filters('posts_orderby'); // ADDED

$args = array(
  'post_type' => $filterby,
  'posts_per_page' => 20,
  'meta_key' => 'starting_date_new',
  'orderby' => $sortby,
  'order' => 'ASC',
  'paged' => $paged,
  'meta_query' => array(
    array(
      'key' => 'starting_date_new',
      'compare' => 'EXISTS'
    ),
    array(
      'key' => 'starting_date_new',
      'value' => $firstDayOfYear,
      'compare' => '>='
    ),
    array(
      'key' => 'starting_date_new',
      'value' => $lastDayOfYear,
      'compare' => '<='
    )
  )
);

$posts = new Timber\PostQuery($args);

$context['posts'] = $posts;
$context['year'] = $GLOBALS['params']['year'];
$context['paged'] = $paged;

Timber::render( 'calendar-year.twig', $context );

<?php

require('create-calendar.php');

$context = Timber::context();
$context['site_theme'] = 'green-theme';
$dateString = $GLOBALS['params']['day'];

$sort_val = false;
$filter_val = false;

if (isset($_GET['sort'])) {
  $sort_val = $_GET['sort'];
}

if (isset($_GET['filter'])) {
  $filter_val = $_GET['filter'];
}

$context['sort_val'] = $sort_val;
$context['filter_val'] = $filter_val;
$context['is_day'] = true;

/*
----------------
SUBLINKS DEFINITIONS
----------------
*/

$sublinks = [
  'title' => 'Calendar',
  'title_link' => '/calendar',
  'type' => 'sort',
  'items' => [
    [
      'title' => 'Date',
      'link' => '/calendar/date/' . $dateString . '?sort=date&' . ($filter_val ? 'filter=' . $filter_val : 'filter=all'),
      'active' => $sort_val == 'date'
    ],
    [
      'title' => 'Title',
      'link' => '/calendar/date/' . $dateString . '?sort=title&' . ($filter_val ? 'filter=' . $filter_val : 'filter=all'),
      'active' => $sort_val == 'title'
    ],
  ],
  'filters' => [
    [
      'title' => 'All',
      'link' => '/calendar/date/' . $dateString . '?' . ($sort_val ? 'sort=' . $sort_val : 'sort=date') . '&filter=all',
      'active' => $filter_val == 'all'
    ],
    [
      'title' => 'Exhibition',
      'link' => '/calendar/date/' . $dateString . '?' . ($sort_val ? 'sort=' . $sort_val : 'sort=date') . '&filter=exhibition',
      'active' => $filter_val == 'exhibition'
    ],
    [
      'title' => 'Event',
      'link' => '/calendar/date/' . $dateString . '?' . ($sort_val ? 'sort=' . $sort_val : 'sort=date') . '&filter=event',
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
$today = date('Ymd', strtotime($dateString));
$now = strtotime('now');
$sortby = null;
$filterby = null;

if ($sort_val == 'date') {
  $sortby = 'meta_value';
} else if ($sort_val == 'title') {
  $sortby = 'title';
} else {
  $sortby = 'meta_value';
}

$exhibitions = Timber::get_posts( array(
  'post_type' => 'exhibition',
  'posts_per_page' => -1,
  'meta_key' => 'starting_date_new',
  'orderby' => $sortby,
  'order' => 'ASC',
  'meta_query' => array(
    array(
      'key' => 'starting_date_new',
      'value' => $today,
      'compare' => '<='
    ),
    array(
      'key' => 'ending_date_new',
      'value' => $today,
      'compare' => '>='
    )
  )
));

$events = Timber::get_posts( array(
  'post_type' => 'event',
  'posts_per_page' => -1,
  'meta_key' => 'starting_date_new',
  'orderby' => $sortby,
  'order' => 'ASC',
  'meta_query' => array(
    array(
      'key' => 'starting_date_new',
      'value' => $today,
      'compare' => '='
    )
  )
));

$all = null;

if ($filter_val == 'all') {
  $all = array_merge($events, $exhibitions);
} else if ($filter_val == 'exhibition') {
  $all = $exhibitions;
} else if ($filter_val == 'event') {
  $all = $events;
} else {
  $all = array_merge($events, $exhibitions);
}

function title_compare($item1, $item2) {
  $title1 = $item1->title;
  $title2 = $item2->title;

  // echo $title1;
  // echo '-----';
  // echo $title2;
  // echo '<br><br>';

  return strcmp($title1, $title2);
}

function date_compare($item1, $item2) {
  $date1 = null;
  $date2 = null;
  
  $item1Start = get_field('starting_date_new', $item1->ID);
  $item2Start = get_field('starting_date_new', $item2->ID);

  $item1End = get_field('ending_date_new', $item1->ID);
  $item2End = get_field('ending_date_new', $item2->ID);

  if ($item1End) {
    $date1 = strtotime($item1End);
  } else {
    $date1 = strtotime($item1Start);
  }

  if ($item2End) {
    $date2 = strtotime($item2End);
  } else {
    $date2 = strtotime($item2Start);
  }

  // echo $date1 . '---' . $date2;
  // echo '<br><br>';

  return $date1 - $date2;
}

if ($sort_val == 'date') {
  usort($all, 'date_compare');
} else if ($sort_val == 'title') {
  usort($all, 'title_compare');
} else {
  usort($all, 'date_compare');
}

$context['all_upcoming'] = $all;

/*
----------------
CALENDAR STUFF
----------------
*/
$data = createCalendar($today);

$context['data'] = $data;

Timber::render( 'page-calendar.twig', $context );

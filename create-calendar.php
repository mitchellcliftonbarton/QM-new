<?php

function createCalendar ($today) {
$all_items = Timber::get_posts( array(
  'post_type' => array('exhibition', 'event'),
  'posts_per_page' => -1,
  'meta_key' => 'starting_date_new',
  'orderby' => 'meta_value',
  'order' => 'DESC'
));

$years = [];
$month_titles = [
  'January',
  'February',
  'March',
  'April',
  'May',
  'June',
  'July',
  'August',
  'September',
  'October',
  'November',
  'December'
];

$today_year = date('Y', strtotime($today));
$today_month = date('n', strtotime($today));
$today_month_word = date('F', strtotime($today));
$today_day = date('j', strtotime($today));

foreach ($all_items as $item) {
  $date = get_field('starting_date_new', $item);
  $year = date('Y', strtotime($date));
  $exists = false;

  $isinarray = in_array($year, array_column($years, 'year'));
  
  if ($isinarray) {
    $exists = true;
  }

  if (!$exists) {
    $months = [];

    foreach (range(1, 12) as $monthNum) {
      $dateObj   = DateTime::createFromFormat('!m', $monthNum);
      $monthName = $dateObj->format('F'); // March
      $firstDay = strtotime($year.'-'.$monthNum.'-01');

      $days = date('t', $firstDay); // 1-31
      $starting_day = date('w', $firstDay); // 0-6

      $month = [
        'month' => $monthName,
        'days' => $days,
        'starting_day' => $starting_day,
        'month_num' => $monthNum,
        'active' => $monthNum == $today_month and $year == $today_year
      ];

      array_push($months, $month);
    }

    $years[] = [
      'year' => $year,
      'months' => $months
    ];
  }
}

return [
    'years' => $years,
    'month_titles' => $month_titles,
    'today_date_unformatted' => strtotime($today),
    'today_date' => date('m.d.y', strtotime($today)),
    'today_date_formatted' => date('Y-m-d', strtotime($today)),
    'current_month' => $today_month_word,
    'current_year' => $today_year,
    'current_day' => $today_day
];
}
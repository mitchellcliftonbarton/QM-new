<?php

function getOpenTimes () {
  // date_default_timezone_set("America/New_York");
  $day = date('l');
  $today_date = strtotime(date('Y-m-d'));
  $open_time = null;
  $close_time = null;
  $hoursObj = get_field('hours', 'option');
  $now = strtotime('now');
  $holidays = $hoursObj['holidays'];
  $is_open = false;

  foreach ($holidays as $holiday) {
		$start = strtotime($holiday['start_date']);
		$end = strtotime($holiday['end_date']);
		$holiday_is_closed = $holiday['closed'] == 1;

    $start_hour = strtotime($holiday['opening_hours']);
    $end_hour = strtotime($holiday['closing_hours']);

    if ($today_date >= $start and $today_date <= $end) {
      // if ($holiday_is_closed) {
      //   return false;
      // } else if ($now >= $start_hour and $now <= $end_hour) {
      //   return true;
      // } else {
      //   return false;
      // }
      
      // If its a holiday and its closed, return false, if not, return start and end hours for holiday which will then be handled by JS
      if ($holiday_is_closed) {
        return [
          'open_time' => 'false',
          'close_time' => 'false'
        ];
      } else {
        return [
          'open_time' => date('F j, Y') . ', ' . $holiday['opening_hours'],
          'close_time' => date('F j, Y') . ', ' . $holiday['closing_hours']
        ];
      }
    }
	}

  switch ($day) {
    case 'Monday':
      // $open_time = strtotime($hoursObj['monday_hours']['open']);
      // $close_time = strtotime($hoursObj['monday_hours']['close']);

      $open_time = $hoursObj['monday_hours']['open'] ? date('F j, Y') . ', ' . $hoursObj['monday_hours']['open'] : 'false';
      $close_time = $hoursObj['monday_hours']['close'] ? date('F j, Y') . ', ' . $hoursObj['monday_hours']['close'] : 'false';

      break;
    case 'Tuesday':
      // $open_time = strtotime($hoursObj['tuesday_hours']['open']);
      // $close_time = strtotime($hoursObj['tuesday_hours']['close']);

      $open_time = $hoursObj['tuesday_hours']['open'] ? date('F j, Y') . ', ' . $hoursObj['tuesday_hours']['open'] : 'false';
      $close_time = $hoursObj['tuesday_hours']['close'] ? date('F j, Y') . ', ' . $hoursObj['tuesday_hours']['close'] : 'false';

      break;
    case 'Wednesday':
      // $open_time = strtotime($hoursObj['wednesday_hours']['open']);
      // $close_time = strtotime($hoursObj['wednesday_hours']['close']);

      $open_time = $hoursObj['wednesday_hours']['open'] ? date('F j, Y') . ', ' . $hoursObj['wednesday_hours']['open'] : 'false';
      $close_time = $hoursObj['wednesday_hours']['close'] ? date('F j, Y') . ', ' . $hoursObj['wednesday_hours']['close'] : 'false';

      break;
    case 'Thursday':
      // $open_time = strtotime($hoursObj['thursday_hours']['open']);
      // $close_time = strtotime($hoursObj['thursday_hours']['close']);

      $open_time = $hoursObj['thursday_hours']['open'] ? date('F j, Y') . ', ' . $hoursObj['thursday_hours']['open'] : 'false';
      $close_time = $hoursObj['thursday_hours']['close'] ? date('F j, Y') . ', ' . $hoursObj['thursday_hours']['close'] : 'false';

      break;
    case 'Friday':
      // $open_time = strtotime($hoursObj['friday_hours']['open']);
      // $close_time = strtotime($hoursObj['friday_hours']['close']);

      $open_time = $hoursObj['friday_hours']['open'] ? date('F j, Y') . ', ' . $hoursObj['friday_hours']['open'] : 'false';
      $close_time = $hoursObj['friday_hours']['close'] ? date('F j, Y') . ', ' . $hoursObj['friday_hours']['close'] : 'false';

      break;
    case 'Saturday':
      // $open_time = strtotime($hoursObj['saturday_hours']['open']);
      // $close_time = strtotime($hoursObj['saturday_hours']['close']);

      $open_time = $hoursObj['saturday_hours']['open'] ? date('F j, Y') . ', ' . $hoursObj['saturday_hours']['open'] : 'false';
      $close_time = $hoursObj['saturday_hours']['close'] ? date('F j, Y') . ', ' . $hoursObj['saturday_hours']['close'] : 'false';

      break;
    case 'Sunday':
      // $open_time = strtotime($hoursObj['sunday_hours']['open']);
      // $close_time = strtotime($hoursObj['sunday_hours']['close']);

      $open_time = $hoursObj['sunday_hours']['open'] ? date('F j, Y') . ', ' . $hoursObj['sunday_hours']['open'] : 'false';
      $close_time = $hoursObj['sunday_hours']['close'] ? date('F j, Y') . ', ' . $hoursObj['sunday_hours']['close'] : 'false';

      break;
    default:
      echo "Couldnt get hours";
  }

  // if ($now >= $open_time and $now <= $close_time) {
  //   $is_open = true;
  // }

  // echo $open_time;
  // echo "<br>";
  // echo $close_time;
  // echo "<br>";

  return [
    'open_time' => $open_time,
    'close_time' => $close_time
    // 'is_open' => $is_open
  ];
}

function checkOpenStatus ($date) {
	$day_date = strtotime($date);
	$is_open = 'open';
	$hoursObj = get_field('hours', 'options');
	$holidays = $hoursObj['holidays'];
	$day_word = date('l', $day_date);
	$day_word_lower = strtolower($day_word);
	$default_open_status = true;

	foreach ($holidays as $holiday) {
		$start = strtotime($holiday['start_date']);
		$end = strtotime($holiday['end_date']);
		$holiday_is_closed = $holiday['closed'] == 1;

		if ($day_date >= $start and $day_date <= $end and $holiday_is_closed) {
			$is_open = 'closed';
		}
	}

	$current_day_open = $hoursObj[$day_word_lower . '_hours']['open'];
	$current_day_close = $hoursObj[$day_word_lower . '_hours']['close'];

	if ($current_day_open == null and $current_day_close == null) {
		$is_open = 'closed';
	}
	
	return $is_open;
}
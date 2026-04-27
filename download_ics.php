<?php

include 'ICS.php';

function download_ics ($data) {
	$dateDay = null;
	$timeNow = null;
	$combinedDate = null;
	$date = false;
	$end_date = false;
	$post = Timber::get_post($data['id']);
  $dates = get_field('calendar_event_dates', $post);
  $address = get_field('address', 'options');
	$starting_date = null;
	$ending_date = null;
	$starting_date_test = null;
	$ending_date_test = null;

	if ($post->post_type == 'event') { // IF EVENT
		date_default_timezone_set("America/New_York"); // WONT WORK WITHOUT THIS, BUT KEEP AN EYE ON ACF FIELDS
		
		// starting_date_new --- 20220908
		// starting_time_new --- 11_00 am

		// build starting date
		$start_date_field = strtotime(get_field('starting_date_new', $post));
		$start_string = date('F j, Y', $start_date_field);

		$start_time_field = get_field('starting_time_new', $post);
		$start_string = $start_string . ' ' . $start_time_field;

		$starting_date = date('F j, Y g:i a', strtotime($start_string));

		// build ending date
		$ending_date = null;
		$end_string = null;

		$end_date_field = strtotime(get_field('ending_date_new', $post));
		$end_time_field = get_field('ending_time_new', $post);

		if (isset($end_date_field)) { // if ending date exists, then put that as ending date. If not, put two hours from starting date.
			$end_string = date('F j, Y', $end_date_field);
			$end_string = $end_string . ' ' . $end_time_field;

			$ending_date = date('F j, Y g:i a', strtotime($end_string));
		} else {
			$ending_date = date('F j, Y g:i a', strtotime($start_string . ' +2 hour'));
		}
	} else if ($post->post_type == 'exhibition') { // IF EXHIBITION
		if ($data['date']) { // IF ON CALENDAR PAGE
			$dateDay = date('F j, Y', strtotime($data['date']));
			$timeNow = date('g:i a');
			$combinedDate = $dateDay . ' ' . $timeNow;

			$date = date('F j, Y g:i a', strtotime($combinedDate . ' +1 hour'));
			$end_date = date('F j, Y g:i a', strtotime($combinedDate . ' +3 hours'));
		} else { // IF ON NORMAL EXHIBITION PAGE
			$event_dates = get_field('calendar_event_dates', $post);
			$start_field = $event_dates['starting_date'];
			$end_field = $event_dates['ending_date'];

			if (isset($start_field) && isset($end_field)) {
				date_default_timezone_set("America/New_York");
				$starting_date = $start_field;
				$ending_date = $end_field;
			} else {
				$starting_date = 'now + 1 hour';
				$ending_date = 'now + 3 hours';
			}
		}
	}

	$event = array(
	  'id' => $data['id'],
	  'title' => $data['date'] ? html_entity_decode($post->title) : html_entity_decode($post->title) . ': ' . $starting_date . ' - ' . $ending_date . ' EST',
	  'slug' => $post->slug,
		'description' => $data['date'] ? $date : $starting_date . ' - ' . $ending_date,
	  'datestart' => $data['date'] ? $date : $starting_date,
	  'dateend' => $data['date'] ? $end_date : $ending_date,
	  'address' => str_replace(array("\r", "\n"), ' ', $address['address_text']),
		'link' => $post->link
	);

	$formattedDate = date('m.d.y', $date);

	header('Content-Type: text/calendar; charset=utf-8');
	header('Content-Disposition: attachment; filename=' . $post->post_type . '-' . $formattedDate . '-' . 'queens-museum-' . $event['slug'] . '.ics');

	$ics = new ICS(array(
	  'location' => $event['address'],
	  'dtstart' => $event['datestart'],
	  'dtend' => $event['dateend'],
		'description' => $event['description'],
	  'summary' => $event['title'],
	  'url' => $event['link']
	));

	$icsFile = $ics->to_string();
	echo $icsFile;
	wp_die();
}
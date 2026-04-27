<?php

function createRoutes () {
  // EXHIBITIONS
  Routes::map('exhibitions/upcoming', function($params) {
    Routes::load('exhibitions-upcoming.php', $params, null, 200);
  });

  Routes::map('exhibitions/upcoming/page/:paged', function($params) {
    Routes::load('exhibitions-upcoming.php', $params, null, 200);
  });

  Routes::map('exhibitions/current', function($params) {
    Routes::load('exhibitions-current.php', $params, null, 200);
  });

  Routes::map('exhibitions/current/page/:paged', function($params) {
    Routes::load('exhibitions-current.php', $params, null, 200);
  });

  Routes::map('exhibitions/past', function($params) {
    Routes::load('exhibitions-past.php', $params, null, 200);
  });

  Routes::map('exhibitions/past/page/:paged', function($params) {
    Routes::load('exhibitions-past.php', $params, null, 200);
  });

  Routes::map('exhibitions/year/:year', function($params) {
    Routes::load('exhibitions-year.php', $params, null, 200);
  });

  Routes::map('exhibitions/year/:year/page/:paged', function($params) {
    Routes::load('exhibitions-year.php', $params, null, 200);
  });

  // BOARD AND STAFF
  Routes::map('board-staff', function($params) {
    Routes::load('board-staff.php', $params, null, 200);
  });

  Routes::map('board-staff/:category', function($params) {
    Routes::load('board-staff-category.php', $params, null, 200);
  });

  Routes::map('board-staff/:category/page/:paged', function($params) {
    Routes::load('board-staff-category.php', $params, null, 200);
  });

  // PROGRAMS
  Routes::map('programs/archive', function($params) {
    Routes::load('programs-archive.php', $params, null, 200);
  });

  Routes::map('programs/archive/page/:paged', function($params) {
    Routes::load('programs-archive.php', $params, null, 200);
  });

  Routes::map('programs/category/:category', function($params) {
    Routes::load('program-category.php', $params, null, 200);
  });

  Routes::map('programs/category/:category/page/:paged', function($params) {
    Routes::load('program-category.php', $params, null, 200);
  });

  // CALENDAR
  Routes::map('calendar/date/:day', function($params) {
    Routes::load('calendar-day.php', $params, null, 200);
  });

  Routes::map('events/:category', function($params) {
    Routes::load('event-category.php', $params, null, 200);
  });

  Routes::map('events/:category/page/:paged', function($params) {
    Routes::load('event-category.php', $params, null, 200);
  });

  Routes::map('calendar/year/:year', function($params) {
    Routes::load('calendar-year.php', $params, null, 200);
  });

  Routes::map('calendar/year/:year/page/:paged', function($params) {
    Routes::load('calendar-year.php', $params, null, 200);
  });

  // NEWS
  Routes::map('news/archive', function($params) {
    Routes::load('news-archive.php', $params, null, 200);
  });

  Routes::map('news/archive/page/:paged', function($params) {
    Routes::load('news-archive.php', $params, null, 200);
  });

  Routes::map('news/category/:category', function($params) {
    Routes::load('news-category.php', $params, null, 200);
  });

  Routes::map('news/category/:category/page/:paged', function($params) {
    Routes::load('news-category.php', $params, null, 200);
  });

  // Videos
  Routes::map('video-series/category/:category', function($params) {
    Routes::load('video-category.php', $params, null, 200);
  });

  Routes::map('video-series/category/:category/page/:paged', function($params) {
    Routes::load('video-category.php', $params, null, 200);
  });
}
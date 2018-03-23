<?php

  function university_files() {
    // random name, files location, does it rely on anything else, version number (whatever), load at bottom(true) or top
    // microtime() to beat caching
    wp_enqueue_script('main-university-argument', get_theme_file_uri('/js/scripts-bundled.js'), NULL, microtime(), true);
    wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'/* , NULL, microtime() */);
    wp_enqueue_style('custom-google-fonts','https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('university_main_styles', get_stylesheet_uri());
  }
  // instuctions to wordpress
  // first argument tell wp what type of instruction
  // 2nd argument is a function to run
  add_action('wp_enqueue_scripts', 'university_files');

  function university_features() {
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    register_nav_menu('footerLocationOne', 'Footer Location One');
    register_nav_menu('footerLocationTwo', 'Footer Location Two');
    add_theme_support('title-tag');
  }
  
  // after_setup_theme is a hook
  add_action('after_setup_theme', 'university_features');
  
  
  

  // don't have to close php tags - useful when adding to end of file often
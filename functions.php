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
    // if we use a wp menu
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    register_nav_menu('footerLocationOne', 'Footer Location One');
    register_nav_menu('footerLocationTwo', 'Footer Location Two');
    
    add_theme_support('title-tag');
    // featured images
    add_theme_support('post-thumbnails');
    // any name, width, height, crop(or not)
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
  }
  
  // after_setup_theme is a hook
  add_action('after_setup_theme', 'university_features');
  
  function university_adjust_queries($query) {
    if (!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
      $query->set('meta_key', 'event_date');
      $query->set('orderby', 'meta_value_num');
      $query->set('order', 'ASC');
      $query->set('meta_query', array(
        array(
          'key' => 'event_date',
          'compare' => '>=',
          'value' => date('Ymd'),
          'type' => 'numeric'
        )
      ));
    };

    if(!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
      $query->set('orderby', 'title');
      $query->set('order', 'ASC');
      $query->set('posts_per_page', -1);
    };
  }
  
  add_action('pre_get_posts', 'university_adjust_queries');
  

  // don't have to close php tags - useful when adding to end of file often
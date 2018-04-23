<?php

  require get_theme_file_path('/inc/search-route.php');
  require get_theme_file_path('/inc/like-route.php');

  function university_custom_rest() {
    register_rest_field('post', 'authorName', array(
      'get_callback' => function() {return get_the_author();}
    ));

    register_rest_field('note', 'userNoteCount', array(
      'get_callback' => function() {return count_user_posts(get_current_user_id(), 'note');}
    ));
  }

  add_action('rest_api_init', 'university_custom_rest');

  // for printing variable values with formatting
  function vd($var) {
    echo "<pre>";
    print_r($var);
    echo "</pre>";
  }

  // recyclable page banner
  function pageBanner($args = NULL) {
    if (!$args['title']) {
      $args['title'] = get_the_title();
    }
    if (!$args['subtitle']) {
      $args['subtitle'] = get_field('page_banner_subtitle');
    }
    if (!$args['photo']) {
      if(get_field('page_banner_background_image')) {
        $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
      } else {
        $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
      }
    }
    ?>
    <div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url('<?php echo $args['photo']; ?>');"></div>
        <div class="page-banner__content container container--narrow">
          <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
            <div class="page-banner__intro">
              <p><?php echo $args['subtitle']; ?></p>
        </div>
      </div>  
    </div>

  <?php
  }

  function university_files() {
    // random name, files location, does it rely on anything else, version number (whatever), load at bottom(true) or top
    // microtime() to beat caching
    wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyDQytVIrz561xfg8x3rZl79LYx16zTpWKc', NULL, '1.0', true);
    wp_enqueue_script('main-university-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, microtime(), true);
    wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'/* , NULL, microtime() */);
    wp_enqueue_style('custom-google-fonts','https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('university_main_styles', get_stylesheet_uri());
    wp_localize_script('main-university-js', 'universityData', array(
      'root_url' => get_site_url(),
      'nonce' => wp_create_nonce('wp_rest')
    ));
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
    add_image_size('pageBanner', 1500, 350, true);
  }
  
  // after_setup_theme is a hook
  add_action('after_setup_theme', 'university_features');
  
  function university_adjust_queries($query) {

    if(!is_admin() && is_post_type_archive('campus') && $query->is_main_query()) {
      $query->set('posts_per_page', -1);
    };
  
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

  function universityMapKey($api) {
    $api['key'] = 'AIzaSyDQytVIrz561xfg8x3rZl79LYx16zTpWKc';
    return $api;
  }
  
  add_filter('acf/fields/google_map/api', 'universityMapKey');

  // redirect subscriber accounts out of admin and onto homepage
  add_action('admin_init', 'redirectSubsToFrontend');
  
  function redirectSubsToFrontend() {
    $ourCurrentUser = wp_get_current_user();
    if (count($ourCurrentUser->roles) === 1 && $ourCurrentUser->roles[0] === 'subscriber') {
      wp_redirect(site_url('/'));
      // tell's php to stop once redirected
      exit;
    }
  }

  // remove admin bar for subs
  add_action('wp_loaded', 'noSubsAdminBar');
  
  function noSubsAdminBar() {
    $ourCurrentUser = wp_get_current_user();
    if (count($ourCurrentUser->roles) === 1 && $ourCurrentUser->roles[0] === 'subscriber') {
      show_admin_bar(false);
    }
  }

  // customize login screen
  add_filter('login_headerurl', 'ourHeaderUrl');

  function ourHeaderUrl() {
    return esc_url(site_url('/'));
  }

  // load css on login screen
  add_action('login_enqueue_scripts', 'ourLoginCSS');

  function ourLoginCSS() {
    wp_enqueue_style('university_main_styles', get_stylesheet_uri());
    wp_enqueue_style('custom-google-fonts','https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  }
  
  add_filter('login_headertitle', 'ourLoginTitle');

  // change title/hover info
  function ourLoginTitle() {
    return get_bloginfo('name');
  }

  // force note posts to be private
  add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

  function makeNotePrivate($data, $postarr) {
    // remove any basic html from post
    if ($data['post_type'] === 'note') {
      if (count_user_posts(get_current_user_id(), 'note') > 4 && !$postarr['ID']) {
        exit('You have reached your note limit.');
      }

      $data['post_content'] = sanitize_textarea_field($data['post_content']);
      $data['post_title'] = sanitize_text_field($data['post_title']);
    }
    
    // make private
    if ($data['post_type'] === 'note' && $data['post_status'] !== 'trash') {
      $data['post_status'] = 'private';
    }

    return $data;
  }


  // don't have to close php tags - useful when adding to end of file often
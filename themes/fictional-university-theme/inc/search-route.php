<?php

add_action('rest_api_init', 'universityRegistersearch');

function universityRegistersearch() {
  register_rest_route('university/v1', 'search', array(
    'methods' => WP_REST_SERVER::READABLE,
    'callback' => 'universitySearchResults'
  ));
}

function universitySearchResults($data) {
  $mainQuery = new WP_Query(array(
    'post_type' => array('post', 'page', 'professor', 'program', 'campus', 'event'),
    // data is passed automatically - 'term' is ?term= - can be anythign
    's' => sanitize_text_field($data['term'])
  ));

  $results = array(
    'generalInfo' => array(),
    'professors' => array(),
    'programs' => array(),
    'events' => array(),
    'campuses' => array()
  );

  while($mainQuery->have_posts()) {
    $mainQuery->the_post();

    switch(get_post_type()) {
      case 'professor': 
        array_push($results['professors'], array(
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
        ));
        break;

      case 'program':
        array_push($results['programs'], array(
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
        ));
        break;

      case 'event':
        array_push($results['events'], array(
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
        ));
        break;

      case 'campus':
        array_push($results['generalInfo'], array(
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
        ));
        break;

      default: 
        array_push($results['generalInfo'], array(
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
          'postType' => get_post_type(),
          'authorName' => get_the_author()
        ));  
    }
  }
  
  return $results;
}
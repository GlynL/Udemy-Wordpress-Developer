<?php

add_action('rest_api_init', 'universityRegistersearch');

// registers api route & specifices callback function
function universityRegistersearch() {
  register_rest_route('university/v1', 'search', array(
    'methods' => WP_REST_SERVER::READABLE,
    'callback' => 'universitySearchResults'
  ));
}

// callback function for api call
function universitySearchResults($data) {
  
  // query database with the data from api route (data will be the search term)
  $mainQuery = new WP_Query(array(
    'post_type' => array('post', 'page', 'professor', 'program', 'campus', 'event'),
    // data is passed automatically - 'term' is ?term= - can be anythign
    's' => sanitize_text_field($data['term'])
  ));

  // results will be what we return - WP will convert from associative array to json
  $results = array(
    'generalInfo' => array(),
    'professors' => array(),
    'programs' => array(),
    'events' => array(),
    'campuses' => array()
  );

  // loop over the main query and insert the data we want into $results ass array
  while($mainQuery->have_posts()) {
    $mainQuery->the_post();

    switch(get_post_type()) {
      case 'professor': 
        array_push($results['professors'], array(
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
          'image' => get_the_post_thumbnail_url(0, 'professorLandscape'),
        ));
        break;

      case 'program':
        // finding related campuses for programs
        $relatedCampuses = get_field('related_campus');
        if ($relatedCampuses) {
          foreach($relatedCampuses as $campus) {
            array_push($results['campuses'], array(
              'title' => get_the_title($campus),
              'permalink' => get_the_permalink($campus)
            ));
          }
        }

        array_push($results['programs'], array(
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
          'id' => get_the_id()
        ));
        break;

      case 'event':
        $eventDate = new DateTime(get_field('event_date'));
        $description = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 10, '...');

        array_push($results['events'], array(
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
          'month' => $eventDate->format('M'),
          'day' => $eventDate->format('d'),
          'description' => $description
        ));
        break;

      case 'campus':
        array_push($results['campuses'], array(
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
  

  if ($results['programs']) {
    // creates query for multiple programs (e.g. math basic, math advanced)
    $programsMetaQuery = array('relation' => 'OR');
    foreach($results['programs'] as $item) {
      array_push($programsMetaQuery, array(
        // checks the post below when looping over all posts to check related_programs field for a matching id
        'key' => 'related_programs',
        'compare' => 'LIKE',
        'value' => '"' . $item['id'] . '"'
      ));
    }
  
    // our custom query for finding related results
    $programRelationshipQuery = new WP_Query(array(
      'post_type' => array('professor', 'event'),
      // how we serach by custom field
      'meta_query' => $programsMetaQuery
    ));
  
    // loop over our custom query and add into $results
    while($programRelationshipQuery->have_posts()) {
      $programRelationshipQuery->the_post();

      if (get_post_type() === 'event') {
        $eventDate = new DateTime(get_field('event_date'));
        $description = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 10, '...');

        array_push($results['events'], array(
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
          'month' => $eventDate->format('M'),
          'day' => $eventDate->format('d'),
          'description' => $description
        ));
      }
  
      if (get_post_type() === 'professor') {
        array_push($results['professors'], array(
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
          'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
        ));
      }
    }
  
    // remove duplicates - if matches both queries
      // array_unique leaves us with indexed results
      // array_values removes these numbers
    $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
    $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
    $results['campuses'] = array_values(array_unique($results['campuses'], SORT_REGULAR));

  }
  
  
  return $results;
}
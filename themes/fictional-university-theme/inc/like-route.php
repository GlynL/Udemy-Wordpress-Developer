<?php

add_action('rest_api_init', 'universityLikeRoutes');

function universityLikeRoutes() {
  register_rest_route('university/v1', 'manageLike', array(
    'methods' => 'POST',
    'callback' => 'createLike'
  ));

  register_rest_route('university/v1', 'manageLike', array(
    'methods' => 'DELETE',
    'callback' => 'deleteLike'
  ));
}

function createLike($data) {
  // check if user is logged in
  if (is_user_logged_in()) {
    $professor = sanitize_text_field($data['professorId']);

    $existQuery = new WP_Query(array(
      // if not logged in => 0 -- same as not having check
      'author' => get_current_user_id(),
      'post_type' => 'like',
      'meta_query' => array(
        array(
          'key' => 'liked_professor_id',
          'compare' => '=',
          'value' => $professor
        )
      )
    ));

    if ($existQuery->found_posts === 0 && get_post_type($professor) === 'professor') {
      // programmatically insert new post
      // returns id# by default
      return wp_insert_post(array(
        'post_type' => 'like',
        'post_status' => 'publish',
        // don't have to include title
        'post_title' => 'test',
        'post_content' => 'hello world 123',
        // custom fields
        'meta_input' => array(
          'liked_professor_id' => $professor
        )
      ));
    } else {
      exit('invalid professor id');
    }

   
  } else {
    exit('you must be logged in to do this');
  }
}

function deleteLike($data) {
  $likeId = sanitize_text_field($data['like']);
  if (get_current_user_id() === (int)get_post_field('post_author', $likeId) && 
    get_post_type($likeId) === 'like') {
    // true - skip trash
    wp_delete_post($likeId, true);
    return 'adklfalkdfjkdakdflalkal';
  } else {
    exit("You don't have permission to do that");
  }
}
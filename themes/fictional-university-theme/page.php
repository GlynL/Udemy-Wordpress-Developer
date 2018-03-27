<?php
  get_header();

  while(have_posts()) {
    the_post();
    
    pageBanner();
    ?>


  <div class="container container--narrow page-section">

    <?php
      // get id of parent page from id of current page
      // returns 0 if no parent
      $theParent = wp_get_post_parent_id(get_the_ID());
      if ($theParent) { ?>
        <div class="metabox metabox--position-up metabox--with-home-link">
          <p><a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent); ?></a> <span class="metabox__main"><?php the_title() ?></span></p>
        </div>
      <?php
      }
    ?>
    
    <?php 
      // similar to wp_list_pages but returns not echoes
      $testArray = get_pages(array(
        'child_of' => get_the_ID()
      ));

      if($theParent || $testArray) { ?>
    
      <div class="page-links">
        <!-- an argument of '0' in get the title returns current page -->
        <h2 class="page-links__title"><a href="<?php echo get_the_permalink($theParent) ?>"><?php echo get_the_title($theParent); ?></a></h2>
        <ul class="min-list">
          <?php 
            $findChildrenOf = ($theParent) ? $theParent : get_the_ID();
            // arguments are an associative array (basically javascript object)
            wp_list_pages(array(
              'title_li' => NULL,
              'child_of' => $findChildrenOf,
              // set menu_order in wp-admin
              'sort_column' => 'menu_order'
            ));
          ?>
        </ul>
      </div>
    <?php } ?>

    <div class="generic-content">
      <?php the_content() ?>
    </div>

  </div>

  <?php
  }

  get_footer();
?>
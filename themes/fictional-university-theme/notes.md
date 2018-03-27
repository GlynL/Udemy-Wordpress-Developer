## looping on a single page
In most other programming situations your instinct is correct; if we only have one item there's no need to loop. However, this is the "standard" way of doing things in WordPress because we want to keep our template files flexible, and also because WordPress does lots of things for us behind the scenes when we call the_post();  and it prefers that we work within a loop.

* single.php - template for singular posts

* page.php - template for pages

* post/page id's - found in wp-admin on page

* the_title() - current page
* get_the_title(id)
* get_permalink(id)

* if WP function begins with 'get' it won't echo anything
* if WP funciton begins with 'the' it will echo

* codex.wordpress.org
* developer.wordpress.org

* front-page.php - for home page


# Blog Archives (archive.php)
* categories and authors (of blog posts)
* url ends up /category/example or /author/john

* index.php is meant to be a generic fallback - last resort

* the_archive_title() - takes care of all different titles for blog type pages
* if you want more control use if statements


# Custom Queries
* normal(default) wordpress query 
* create new query - $variable = new WP_Query();
* WP_Query is a class - supply array of args
* wp_reset_postdata(); - call after using custom query

# general notes
* get_post_type() === 'post' <!-- check if the page is a blog (post) -->
* wp has posts & pages - page is really just a post type of page

* workflow - gulp watch
* css - style.css in css folder which imports all the seperate styles <!-- then compiles into style.css (main folder) to be used -->

# Custom Post Types
* default is post & page <!-- pages are a actually a post type -->
* adding events - first functions.php (not best place as changing themes will ruin functionality) <!-- plugin is better! -->
* must-use plugins - inside wp-content folder (mu-plugins) <!-- can't deactivate -->

* rebuild permalinks after adding new custom post types <!-- settings/permalinks/save -->
* working '/event' page <!-- has_archive inside the mu_plugin -->
* events instead of event <!-- rewrite slug mu_plugin -->
* custom archive page for event <!-- archive-event.php -->

# misc
* can do excerpts for your posts in admin
* have to edit the custom post type to allow excerpts

# Custom Fields
* built-in is not a good user experience
* good use for plugin <!-- ACF or CMB2` -->

* using php class DateTime() to give us the formatting we want from event_date acf

# Custom Queries - ordering/sorting
* using meta_query to sort from custom field

# Manipulating Default URL Based Queries
* custom queries are the right choice when what you want to do isn't related to default behaviour of url <!-- home page event dates not events page -->
* add action to functions.php

# Past Events Page
* custom html for a page - page-slug <!-- page-past-events -->
* out of box -pagination links only work with default queries wordpress makes itself tied to current url

# Creating Relationships between Content
* ACF - field w/ field type 'relationship'

# displaying relationships (frontend)
* to check what a variable contains <!-- print_r($variable) -->

* ACF gives us get_field() function
* can loop over related programs found with get_field

* linking programs to events - no custom field - can query database (custom query)
* need to concatonate quotations around get_the_ID() due to database serializing data and you will get additional false positivies <!-- e.g. match 12 as well as 120 -->

# Professors Post Type
* if running multiple wp custom queries need to use wp_reset_postdata() inbetween

# Featured Image (Post Thumbnail)
* not enabled by default
* add_theme_support() in functions.php
* for custom post types we need to do more 
  * add thumbnail to muplugin
* images saved in wp-content -> uploads
* will automatically produce images of different sizes
* can add your own sizes that are needed in functions.php - add_image_size()
* won't add sizes to images already uploaded
 * plugin to do this - regenerate thumbnails
 
# Custom Image Sizes (frontend use)
* add argument (name we gave image) to post thumbnail - the_post_thumbnail('professorThumbnail')
* cropping wp default will crop to center
  * can set an array in functions.php image size - tells how to crop <!-- but not all images we want the same -->
  * manual image crop plugin <!-- not well updated now - good for course -->
  *  Advanced Custom Fields: Image Crop Add-On <!-- try this one --> 

# Page Banner Dynamic - subtitle/bg image
* can't use featured image - already used it on the portrait photo for professors
* custom fields - subtitle & image fields <!-- show if post type is post or post type is not post -- always shows -->
* ACF image - get_field() returns an array - need to access the url

* print_r() <!-- to find out more about something -->
* function for printing variable <!-- from comments -->
 function vd($var) {
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}

# Create our own function - reusable coe
* pass associative array as $args
* use if statements to set defaults if args aren't provided
* set default $args to NULL - if not provided will still have a value

# Reducing Duplication - get_template_part()
* get_template_part() accepts two arguments
  * path of file, second optional argument adds '-argument'
  * e.g. could use get_post_type() as 2nd arg

* functions vs template
  * no necessary right or wrong
  * if you want to pass arguments use function otherwise use template

# Campus Post Type
* ACF field with type of google map
* get API key from Google & create add_filter function in functions.php
  * function adds the api key to acf data


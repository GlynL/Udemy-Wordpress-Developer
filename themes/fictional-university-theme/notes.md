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
  * tell how many pages we need

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

# Create our own function - reusable code
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

# Live Search
* javascript written in modules, imported to scripts.js & bundled then enqueued in functions.php

# Open & Close Search Overlay
* updating with keydown is fired too quickly for browser - keyup gives time to register input


# WP REST API (AJAX)

# Load WP Content with JS
* siteurl/wp-json/wp/v2/posts - replace posts w/ pages etc
  * add /posts-per-page, id, etc..
  * ?search=

* fetch - course uses jQuery 
  * use arrow functions to keep 'this'

# HTML Based on JSON Data
* WP REST API - can use wordpress data/content outside of PHP
* CRUD - create/read/update/delete 
* wordpress.org - REST API info


# Conditional Logic Within Template Literal
* output js data into html source of webpage <!-- can then access in js -->
  * wp_localize_script(1, 2, 3);
    1. main js file (one we want to make flexible)
    2. function name (doesn't amtter)
    3. associative array of data we want

# Synchronous vs Asynchronous -workign with multiple post types (1)
* making search work for more than posts (pages/events/etc)
  * make multiple requests and join

# REST API: Add New Custom Field
* functions.php - add_action(1, 2)
  1. wp event you want to hook onto <!-- rest_api_init -->
  2. function of your creation
* register_rest_field(1, 2, 3)
  1. post type you want to customize
  2. name of new field - whatever you want
  3. array describing how you manage field

# REST API: Add New Custom Route (URL)
* default - can't access custom post type
* in our custom plugin add show_in_rest to post_type
* api doesn't know to look in custom fields for search term
* reasons for custom route
  1. custom search logic
  2. respond with less JSON data (load faster)
  3. send 1 request instead of 6 in js
  4. perfect exercise for sharpening PHP skills

* writing in new file - inc folder - search-route.php & require inside functions.php <!-- saves bloating functions.php -->

* register_rest_route(1, 2, 3)
  1. url for api - wp indicates core wp - don't use & make unique to avoid plugin conflicts
  2. route - ending part of url 
  3. array describing what should happen when url visited
    - methods - e.g. GET <!-- almost always work, extra mile use WP_REST_SERVER::READABLE -->
    - callback - function which is json data to be displayed

# Create Your Own Raw JSON Data
* return php (associative array) & WP will convert into JSON
* use WP_QUERY & WP loop over results

# WP_Query & Keyword Searching
* 's' argument in WP_Query for searching
* dynamic url w/ search term
  * data is automatically passed to your callback function (wp_query)
  * can access $data['watever'] <!-- watever is the ?watever=value -->
  * sanitize input <!-- sanitize_text_field() -->

# Working with Multiple Post Types
* change callback function 'post_type' to an array of post types we want
* results pass into associative array of arrays - one for each post type - use if/switch to check post type and push into correct array

# 3 Column Layout for Search Overlay - Using our new custom API

# Custom Layout & JSON based on Post Type

# Search Logic That's Aware of Relationships (1)
* professors w/ related programs - programs are saved in db under id # <!-- biology is id #87 -->
* add query to api

# Search Logic That's Aware of Relationships (2)
* making query dynamic
  * add 'id' to programs json - filter for matching id in professors related programs query
  * query relation 'OR' - for multiple fitlers (like)
* only search title not content field for programs
  * could adjust wp sql - don't like to do unless 100% need to
  * make acf exactly like body content field which wp doesn't queyr by default <!-- adjust our single-program.php from the_content() -->
  * hide the default wp content field - delete 'content' from custom post type (mu-plugin)

# Completing our Search Overlay
* updating related query to add event, campus post types
* related campus is in programs post not in campus <!-- differnet to event/professor -->
* in event/professor if we search math the related field is in the event and not the math program. Whereas, the math program contains the related campus field. <!-- keeps the post type with less being related to -->


# Traditional WP Searching
* works even w/out JS
* add /?s=term to end of url
* create file search.php for styling
* create a search form - new page when search icon clicked
* form on searchpage which submits to a new url
  * esc_url(site_url()) <!-- gives added protection -->
  * action='urlyouwant'
* prevent <a> redirect when js enabled - return false in openOverlay function (serach.js) 


# Tradition WP Searching (2)
* search.php <!-- for search results -->
* get_search_query() <!-- gives value searched for -->
  * wp by default won't run malicious code placed inside search <!-- can override with false as argument -->
  * if you want to output it in a string of html place inside esc_html()
* get_search_form() <!-- searchform.php file in base -->
* we haven't done related fields w/ traditional search <!-- xtra section at end -->


<!-- =====================
========= USERS ==========
====================== -->
<!-- USER ROLES & PERMISSIONs -->

# User Roles & Permissions
* create new user in dashboard
* role is important <!-- might not want to make someone administrator unless tech savvy as they can mess up site -->
* editor can change all post-types
* create new user roles to suit what you need <!-- 'members' plugin - gives roles & add new roles to db -->
* custom post types treated as normal 'posts' - default inherit those role permissions
* add capability_type in post-types
* members plugin lets you assign multiple roles to a user

# Open Registation
* db settings -> general -> membership
* signup - site-url/wp-signup.php
* is_user_logged_in()
* wp_logout_url() <!-- logs user out -->
* get_avatar(get_current_user_id(), image size)
* wp directs to admin page on login by default
  * check user role in functions.php
* remove admin bar for subscribers in functions.php

# Open Registration (2) - login screen branding
* wp_login_url()
* wp_registration_url()
* login page icon takes to wp.org by default
  * customize in functions.php
  add_filter(1,2)
  1. value/object you want to customize/filter/change <!-- login_headerurl -->
  2. function you want to use instead
* change image by changing background image of element
  * wp won't load your css by default
  * functions.php add_action
* change title/hover info in functions.php <!-- add_filter -->


<!--
 USER GENERATED CONTENT
 -->
# 'My Notes' Feature (CRUD)
* new page - provide button in header if logged in
* if not logged in - redirect <!-- if they manually type in page -->
* custom post type - notes
* esc_attr() makes it safe to use db values in html

# 'My Notes' Front-end (1)
# 'My Notes' Front-end (2)
* send a DELETE request to the api url
* ajax request MyNotes.js
* create nonce in functions.php university_files
* beforeSend ajax request - xhr stuff

# 'My Notes' Front-end (3)
* give note in html a data-id of the note id
* add this id to delete url
* remove element on success

* edit note is a PUT request
* make fields read only - readonly attribute in html
* select note li group in js - remove readonly & add a stylign class

# Edit/Update Notes with REST API
* changing edit between edit and cancel - editable/readonly
  * jquery.data() - no real js sub - stores data for you
* updateNote - mostly same as deleting - type is POST instead
* with updating WP requires specifc name - title, content

# Creating New Notes - w/ REST API
* add a section in html for creating new note
* similar js to updateNote
* remove id from url & wp will make a new note
* new note ends up as a draft in WP by default - set 'status': 'publish'

# Creating New Notes (2)
* make new note update real data straight away to page
* when posting to wp rest api it responds with relevant data

* click handlers aren't added to this new note 
* change listener to parent with a specific class<!-- $('#my-notes').on('click', '.delete-note', this.deleteNote); -->

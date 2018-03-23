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
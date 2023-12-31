<?php

// query vars: lecture 206 (see our-blocks/page.php for implementation)
function universityQueryVars($vars) {
    $vars[] = 'skyColor';
    $vars[] = 'grassColor';
    return $vars;
}
add_filter('query_vars', 'universityQueryVars');



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

add_action('rest_api_init', 'university_custom_rest'); //the university_custom_rest customizes the fields that the rest api returns (see in /inc/search-route.php and Search.js)


function pageBanner($args = NULL) { //$args argument is OPTIONAL instead of required
    //'''evaluates which image to use in the page banner and inserts html code for it. this function is called in every page template boelow the header
    
    //php code for this function
    if (!isset($args['title'])) {
        $args['title'] = get_the_title();
    }

    if (!isset($args['subtitle'])) {
        $args['subtitle'] = get_field('page_banner_subtitle');
    } 

    if (!isset($args['photo'])) {
        if (get_field('page_banner_background_image') AND !is_archive() AND !is_home()) {
            $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
        } else {
            $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        }
    }

    ?>

    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php 
        echo $args['photo'];
        ?>"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
            <div class="page-banner__intro">
                <p><?php
                echo $args['subtitle']; ?></p>
            </div>
        </div>
    </div>

    <?php
}

// function university_files() {
//     wp_enqueue_style('university_main_styles', get_stylesheet_uri()); // adding styles from style.css

function university_files()
{
    wp_enqueue_style('leaflet-map-css', 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css'); //Leaflet Map CSS file
    wp_enqueue_script('leaflet-map-js', 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.js', NULL, '1.0', true); // Leaflet Map JS
    wp_enqueue_script('main-university-js', get_theme_file_uri('build/index.js'), array('jquery'), '1.0', true); // adding the main js file; it takes 3 more arguments besides the file uri: dependecies, theme version, and a booloan which stands for if we want to load the file right after loading the head of the page.
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i'); // adding styles from google fonts CDN link (without the https: part)
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); // adding styles from fontawesome CDN link (without the https: part)
    wp_enqueue_style('university_main_styles', get_theme_file_uri('build/style-index.css')); // adding styles from /build/style-index.css
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('build/index.css')); // adding styles from /build/style-index.css
    
    wp_localize_script('main-university-js', 'universityData', array(
        'root_url' => get_site_url(), //the root url
        'nonce' => wp_create_nonce('wp_rest'), //generates a nonce to perform CRUD requests and authorize them on the server via header
    ));
        //main-university-js: it is the js script where we want to output this information, so that it can be used (in this case, it is the main index.js that will live inside of the build folder)
        //universityData definition:  the name of the variable to be output
        // the 3rd argument is the data to be output in main-university-js; in this case, what we need is the actual site url
}

add_action('wp_enqueue_scripts', 'university_files'); // run university_files function before loading the head in html

function university_features()
{
    register_nav_menu('headerMenuLocation', 'Header Menu Location'); // register menu location so that in the CMS the user can create and edit menus (see index.php, the exact location is set there)
    register_nav_menu('footerLocationOne', 'Footer Location 1');
    register_nav_menu('footerLocationTwo', 'Footer Location 2');
    add_theme_support('title-tag'); //enables dynamic titles on the page's tab
    add_theme_support('post-thumbnails'); //enables featured image for posts -> this innitially will only work for blog posts -> to enable it for other post-types, add 'thumbnail' as as item of 'supports' array in register_post_type in mu-plugins/university-post-types
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);

    //banner Block styles:
    add_theme_support('editor-styles');
    add_editor_style(array('https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i', 'build/style-index.css', 'build/index.css'));


}

add_action('after_setup_theme', 'university_features');

function university_adjust_queries($query)
{
    if (!is_admin() and is_post_type_archive('campus') and $query->is_main_query()) {
        $query->set('posts_per_page', -1);
    }

    if (!is_admin() and is_post_type_archive('program') and $query->is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }

    if (!is_admin() and is_post_type_archive('event') and $query->is_main_query()) {
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
            )
        ));
    }
}

add_action('pre_get_posts', 'university_adjust_queries'); //sort events in ascending order of their event_date field + not show past events

//Redirect subscriber accounts out of admin and onto homepage
add_action('admin_init', 'redirectSubsToFrontend');

function redirectSubsToFrontend() {
    $ourCurrentUser = wp_get_current_user();

    if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
        wp_redirect(site_url('/'));
        exit;
    }
}

//Hides the WordPress official navbar from subscribers:
add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar() {
    $ourCurrentUser = wp_get_current_user();

    if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
        show_admin_bar(false);
    }
}

//Customize Login Screen:
add_filter('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl() {
    return esc_url(site_url('/'));
}

add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS() {
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i'); // adding styles from google fonts CDN link (without the https: part)
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); // adding styles from fontawesome CDN link (without the https: part)
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css')); // adding styles from /build/style-index.css
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css')); // adding styles from /build/style-index.css
}

// Remove 'Powered by WordPress' in login page:
add_filter('login_headertitle', 'ourLoginTitle');

function ourLoginTitle() {
    return get_bloginfo('name');
}

// Force note posts to be private and sanitize post content agains malicious attacks (XSS):
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

function makeNotePrivate($data, $postarr) {
    if ($data['post_type'] == 'note') {
        if (count_user_posts(get_current_user_id(), 'note') > 50 AND !$postarr['ID']) {
            die('You have reached your note limit.');
        }
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
        $data['post_title'] = sanitize_text_field($data['post_title']);
    }

    if ($data['post_type'] == 'note' AND $data['post_status'] != 'trash') {
        $data['post_status'] = 'private';
    }
    return $data;
}

//tell All In One Migration Plugin to ignore node_modules folder when building our export files, if we're using this plugin for that:
// add_filter('ai1wm_exclude_content_from_export', 'ignoreCertainFiles');

// function ignoreCertainFiles($exclude_filters) {
//     $exclude_filters[] = 'themes/uint-university-theme/node_modules';
//     return $exclude_filters;
// }
add_filter('ai1wm_exclude_themes_from_export', function ($exclude_filters) {
    $exclude_filters[] = 'uint-university-theme/node_modules';
    return $exclude_filters;
  });

//see uint-university-theme/mu-plugins for other functions, eg university-post-types.php that defines custom post types (they are there so that the user can change the theme and keep having access to them in the CMS)


  //registering blocks for uint-block-theme

  //refactored below (created a class to be able to )
//   function bannerBlock() {
//     wp_register_script('bannerBlockScript', get_stylesheet_directory_uri(). '/build/banner.js', array('wp-blocks', 'wp-editor'));
//     register_block_type('ourblocktheme/banner', array(
//         'editor_script' => 'bannerBlockScript'
//     ));
//   }

//   add_action('init', 'bannerBlock');

//never render blocks from php:
// class JSXBlock {
//     function __construct($name) {
//       $this->name = $name;
//       add_action('init', [$this, 'onInit']);
//     }
  
//     function onInit() {
//       wp_register_script($this->name, get_stylesheet_directory_uri() . "/build/{$this->name}.js", array('wp-blocks', 'wp-editor'));
//       register_block_type("ourblocktheme/{$this->name}", array(
//         'editor_script' => $this->name
//       ));
//     }
//   }
  
//   new JSXBlock('banner');
//   new JSXBlock('genericheading');
//   new JSXBlock('genericbutton');

//These blocks won't display as What You See Is What You Get in the ditor CMS; instead, they will render a simple placeholder; that is because they won't receive any inner customization like sizes, fonts or inner blocks:
class PlaceholderBlock {
    function __construct($name) {
      $this->name = $name;
      add_action('init', [$this, 'onInit']);
    }
  
    function ourRenderCallback($attributes, $content) {
      ob_start();
      require get_theme_file_path("/our-blocks/{$this->name}.php");
      return ob_get_clean();
    }
  
    function onInit() {
      wp_register_script($this->name, get_stylesheet_directory_uri() . "/our-blocks/{$this->name}.js", array('wp-blocks', 'wp-editor'));

      $ourArgs = array(
        'editor_script' => $this->name,
        'render_callback' => [$this, 'ourRenderCallback']
      );
  
      register_block_type("ourblocktheme/{$this->name}", $ourArgs);
    }
  }

  new PlaceholderBlock('eventsandblogs');
  new PlaceholderBlock('header');
  new PlaceholderBlock('footer');
  new PlaceholderBlock('singlepost');
  new PlaceholderBlock('page');
  new PlaceholderBlock('blogindex');
  new PlaceholderBlock('programarchive');
  new PlaceholderBlock('singleprogram');
  new PlaceholderBlock('singleprofessor');
  new PlaceholderBlock('mynotes');
  new PlaceholderBlock('campusarchive');
  new PlaceholderBlock('eventarchive');
  new PlaceholderBlock("archive");
  new PlaceholderBlock('pastevents');
  new PlaceholderBlock('searchresults');
  new PlaceholderBlock('search');
  new PlaceholderBlock("singlecampus");
  new PlaceholderBlock("singleevent");

//render blocks from php if the second argument is true. the 3rd argument is for displaying a fallback image in the editor while no image is selected
class JSXBlock {
    function __construct($name, $renderCallback = null, $data = null) {
      $this->name = $name;
      $this->data = $data;
      $this->renderCallback = $renderCallback;
      add_action('init', [$this, 'onInit']);
    }
  
    function ourRenderCallback($attributes, $content) {
        // print_r($content);
      ob_start();
      require get_theme_file_path("/our-blocks/{$this->name}.php");
      return ob_get_clean();
    }
  
    function onInit() {
      wp_register_script($this->name, get_stylesheet_directory_uri() . "/build/{$this->name}.js", array('wp-blocks', 'wp-editor'));

      if ($this->data) {
        wp_localize_script($this->name, $this->name, $this->data);
      } //injects the fallback image url into the source code
      
      $ourArgs = array(
        'editor_script' => $this->name
      );
  
      if ($this->renderCallback) {
        $ourArgs['render_callback'] = [$this, 'ourRenderCallback'];
      }
  
      register_block_type("ourblocktheme/{$this->name}", $ourArgs);
    }
  }
  
  new JSXBlock('banner', true, ['fallbackimage' => get_theme_file_uri('/images/library-hero.jpg')]);
  new JSXBlock('genericheading');
  new JSXBlock('genericbutton');
  new JSXBlock('slideshow', true);
  new JSXBlock('slide', true, ['themeimagepath' => get_theme_file_uri('/images/')]); //providing a relative path to the images' URLs so that the theme works even if the user don't install it in the root theme folder; see index.html in /templates


// //   restrict which blocks the user can add in the template editor (doesn't affect the block editor for any post type)
// function myallowedblocks($allowed_block_types, $editor_context) {
//     // if user is on a professor post editor, only allow these blocks to be used:
//     if ($editor_context->post->post_type == 'professor') {
//         return array('ourblocktheme/header', 'ourblocktheme/footer', 'core/list');
//     }
    
//     // if user is on a page/post editor:
//     if (!empty($editor_context->post)) {
//         return $allowed_block_types;
//     }
//     // if user is on the Full Site Editor, only allow these blocks to be used:
//     return array('core/paragraph', 'ourblocktheme/header', 'ourblocktheme/footer');

// }

// add_filter('allowed_block_types_all', 'myallowedblocks', 10, 2);

  
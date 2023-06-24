<div class="full-width-split group">
    <div class="full-width-split__one">
        <div class="full-width-split__inner">
            <h2 class="headline headline--small-plus t-center">Upcoming Events</h2>

            <!-- Custom query for Events: -->
            <?php
            $today = date('Ymd');
            $homepageEvents = new WP_Query(array(
                'posts_per_page' => 2,
                'post_type' => 'event',
                'meta_key' => 'event_date',
                'orderby' => 'meta_value_num', //define the 'meta_key' to the name of the custom-field, and define 'orderby' to 'meta_value_num' in order to display the events by their event_date date.
                'type' => 'DATE',
                'order' => 'ASC', //default is DEScending
                'meta_query' => array(
                    array(
                        'key' => 'event_date',
                        'compare' => '>=',
                        'value' => $today, //only show posts where event_date is >= today
                        'type' => 'numeric'
                    )
                )
            ));
            while ($homepageEvents->have_posts()) {
                $homepageEvents->the_post();
                get_template_part('template-parts/content', get_post_type());
                //will load 'template-parts/content-event'
             }
            wp_reset_postdata();
            ?>
            <p class="t-center no-margin"><a href="<?php echo get_post_type_archive_link('event'); ?>" class="btn btn--blue">View All Events</a></p>
        </div>
    </div>

    <div class="full-width-split__two">
        <div class="full-width-split__inner">
            <h2 class="headline headline--small-plus t-center">From Our Blogs</h2>
            <!-- Custom query: -->
            <?php
            $homepagePosts = new WP_Query(array(
                'posts_per_page' => 2, // only shows 2 posts
                // 'post_type' => 'page', // shows pages intead of the default posts 
                // 'category_name' => 'news', // shows only posts under that category
            )); // instantiate a new WP_Query object passing the 'posts_per_page' property set to 2

            while ($homepagePosts->have_posts()) {
                $homepagePosts->the_post(); ?>
                <div class="event-summary">
                    <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink(); ?>">
                        <span class="event-summary__month"><?php the_time('M'); ?></span>
                        <span class="event-summary__day"><?php the_time('d'); ?></span>
                    </a>
                    <div class="event-summary__content">
                        <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                        <p><?php if (has_excerpt()) {
                                echo  get_the_excerpt();
                            } else {
                                echo wp_trim_words(get_the_content(), 18);
                            } ?> <a href="<?php the_permalink(); ?>" class="nu gray">Read more</a></p>
                    </div>
                </div>
            <?php }
            wp_reset_postdata();
            ?>

            <p class="t-center no-margin"><a href="<?php echo site_url('/blog'); ?>" class="btn btn--yellow">View All Blog Posts</a></p>
        </div>
    </div>
</div>
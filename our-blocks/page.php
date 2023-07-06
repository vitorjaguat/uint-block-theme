<?php

while (have_posts()) {
    the_post(); 
    pageBanner();
    ?>

    <div class="container container--narrow page-section">
        <?php
        $theParent = wp_get_post_parent_id(get_the_ID()); //checks if a page has a parent

        if ($theParent) { ?>
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p>
                    <a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent); ?></a> <span class="metabox__main"><?php the_title(); ?></span>
                </p>
            </div>
        <?php }
        ?>

        <?php
        $testArray = get_pages(array(
            'child_of' => get_the_ID()
        )); // checks if a page has children

        if ($theParent or $testArray) { ?>
            <div class="page-links">
                <h2 class="page-links__title"><a href="<?php echo get_permalink($theParent); ?>"><?php echo get_the_title($theParent) ?></a></h2>
                <ul class="min-list">
                    <?php
                    if ($theParent) {
                        $findChildrenOf = $theParent; //the variable $theParent is defined above as $theParent = wp_get_post_parent_id(get_the_ID());
                    } else {
                        $findChildrenOf = get_the_ID();
                    }
                    wp_list_pages(array(
                        'title_li' => NULL, //takes off the title 'PAGES' (useless)
                        'child_of' => $findChildrenOf, //shows children OR parent (see $findChildrenOf definition above)
                        'sort_column' => 'menu_order', //shows list in the order defined in the CMS 
                    ));
                    ?>
                    <!-- <li class="current_page_item"><a href="#">Our History</a></li>
          <li><a href="#">Our Goals</a></li> -->
                </ul>
            </div>
        <?php } ?>

        <div class="generic-content">
            <?php the_content(); 
            
            // query vars implementation: (see functions.php right in the beginning; lecture 206)

            $skyColorValue = sanitize_text_field(get_query_var('skyColor'));
            $grassColorValue = sanitize_text_field(get_query_var('grassColor'));

            if ($skyColorValue == 'blue' AND $grassColorValue == 'green') {
                echo '<p>The sky is blue today. The grass is green. Life is good.</p>';
            }
            
            ?>
            <form method="GET">
                <input name='skyColor' placeholder='Sky color'>
                <input name='grassColor' placeholder='Grass color'>
                <button>Submit</button>
            </form>
        </div>
    </div>

<?php }

?>
<?php get_header(); /* Template Name: Developer template */

global $post; ?>

<section class="content">
    <div class="wrapper">
        <h1 class="page-title"><?php the_title(); ?></h1>
        <div class="wysiwyg">
            <?php
            if (have_posts()) : while (have_posts()) : the_post();
                the_content();
            endwhile; endif; ?>
        </div>
<!--        --><?php //get_template_part('components/global', 'flexible'); ?>
    </div>
</section>
<?php get_footer(); ?>

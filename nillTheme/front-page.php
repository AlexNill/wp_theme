<?php get_header(); ?>
<section class="content">
    <div class="wrapper">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <?php the_content(); ?>
        <?php endwhile; endif; ?>
    </div>
</section>
<?php get_footer(); ?>
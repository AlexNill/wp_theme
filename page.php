<?php get_header(); ?>
<section class="content">
    <div class="wrapper">
        <h1 class="page-title"><?php the_title(); ?></h1>
        <?php if (get_post()->post_content !== '') { ?>
            <div class="wysiwyg">
                <?php if (have_posts()): while (have_posts()): the_post();
                    the_content();
                endwhile;endif;
                ?>
            </div>
        <?php } ?>
    </div>
</section>
<?php get_footer(); ?>


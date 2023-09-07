<?php get_header();
if (is_home()) {
    $queryname = get_the_title(BLOG_ID);
} else {
    $queryname = 'Archive of ' . get_the_archive_title();
} ?>
?>
<section class="content">
    <div class="wrapper">
        <h1><?php echo get_the_title(BLOG_ID); ?></h1>
        <?php if ( have_posts() ) { ?>
            <div class="box">
                <?php while ( have_posts() ) { the_post(); ?>
                    <div class="post">
                        <?php if (has_post_thumbnail()) { ?>
                            <a class="thumb" href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail(); ?>
                            </a>
                        <?php } ?>
                        <div class="info">
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <time datetime="<?php echo get_the_date('Y-m-d'); ?>"><?php echo get_the_date(get_option('date_format_custom')); ?></time>
                            <p><?php echo wp_trim_words(strip_shortcodes(get_the_content()), 20, "..."); ?></p>
                            <a class="read" href="<?php the_permalink(); ?>">Read more ></a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <div class="main-pagination">
            <?php echo paginate_links(); ?>
        </div>
    </div>
</section>
<?php get_footer(); ?>
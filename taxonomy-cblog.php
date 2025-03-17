<?php get_header(); ?>
	<div class="container">
        <div class="app-p">
            <div class="section blog">
                <div class="title-section">
                    <?php echo __( 'Categoría', 'appyn' ); ?>: <?php echo single_tag_title("", false); ?>
                </div>
                <div class="ct_description"><?php echo @tag_description(); ?></div>
                <?php
                if( have_posts() ):
                ?> 
                <div class="bloques">
                    <?php
                    while( have_posts() ) : the_post();
                        get_template_part( 'template-parts/loop/blog' );
                    endwhile;
                    ?>
                </div>
                <?php paginador();
                else: 
                    echo '<div class="no-entries"><p>'.__( 'No hay entradas', 'appyn' ).'</p></div>';
                endif; ?>
            </div>
        </div>
        <?php 
        if( appyn_options( 'blog_sidebar' ) ) {
            get_sidebar( 'blog' ); 
        } else { 
            get_sidebar();
        } ?>
    </div>
<?php get_footer(); ?>
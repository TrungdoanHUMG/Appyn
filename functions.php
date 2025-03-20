<?php

if( ! defined( 'ABSPATH' ) ) die ( '✋' );

define( 'VERSIONPX', '2.0.15' );
define( 'THEMEPX', 'appyn' );
define( 'PX_AMP_QUERY_VAR', apply_filters( 'amp_query_var', 'amp' ) );
define( 'API_URL', 'https://api.themespixel.net' );
define( 'MAX_DOWNLOAD_FILESIZE', 52428800 );
define( 'ALLOW_UNFILTERED_UPLOADS', true );
define( 'WP_TEMP_DIR', WP_CONTENT_DIR. '/uploads/temp' );

add_action( 'after_setup_theme', 'px_theme_setup' );

function px_theme_setup() {
		
	add_theme_support( 'nav-menus' );

	add_theme_support( 'post-thumbnails' );

	add_image_size( 'miniatura', 75, 75, true );

	add_image_size( 'medio', 128, 128, true );

	add_theme_support( 'title-tag' );

	add_theme_support( 'woocommerce' );

	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption',  ) );

	load_theme_textdomain( 'appyn', get_template_directory() . '/languages' );
	
	// create pages
	
	global $wpdb;
		
	$v = $wpdb->get_var( $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s LIMIT 1" , '_wp_page_template', 'template-most-viewed-apps.php' ) );

	if( empty($v) ) {
		$postarr = array(
			'post_title' => __( 'Apps más vistas', 'appyn' ),
			'post_type' => 'page',
			'post_status' => 'publish',
			'page_template'  => 'template-most-viewed-apps.php',
		);
		$insert = wp_insert_post($postarr);
		update_post_meta( $insert, '_wp_page_template', 'template-most-viewed-apps.php' );
		update_post_meta( $insert, 'appyn_hidden_sidebar', 1 );
	}
	
	$v = $wpdb->get_var( $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s LIMIT 1" , '_wp_page_template', 'template-most-rated-apps.php' ) );

	if( empty($v) ) {
		$postarr = array(
			'post_title' => __( 'Apps más calificadas', 'appyn' ),
			'post_type' => 'page',
			'post_status' => 'publish',
			'page_template'  => 'template-most-rated-apps.php',
		);
		$insert = wp_insert_post($postarr);
		update_post_meta( $insert, '_wp_page_template', 'template-most-rated-apps.php' );
		update_post_meta( $insert, 'appyn_hidden_sidebar', 1 );
	}
}

add_action( 'init', 'px_theme_menus' );

function px_theme_menus() {
    $locations = array(
        'menu' => __( 'Menu', 'appyn' ),
        'menu-mobile' => __( 'Menu mobile', 'appyn' ),
		'menu-footer' => __( 'Menu footer', 'appyn' ),
		'menu-fixed-bottom' => __( 'Menu fixed bottom (Mobile)', 'appyn' ),
    );
    register_nav_menus( $locations );
}


function all_options() {
	$url = get_stylesheet_directory_uri();
	$options = array(
		'logo' => $url.'/images/logo.png',
		'favicon' => $url.'/images/favicon.ico', 
		'titulo_principal' => __( 'Theme Appyn para aplicaciones Android', 'appyn' ), 
		'descripcion_principal' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer fermentum erat ut massa venenatis, vitae ultrices sem dictum. Aliquam leo ipsum, bibendum nec dolor et.', 
		'image_header1' => $url.'/images/plantasvszombies2.png',
		'image_header2' => $url.'/images/lords-mobile.png',
		'image_header3' => $url.'/images/pokemon-go.png',
		'image_header4' => $url.'/images/roblox.png',
		'image_header5' => $url.'/images/free-fire.png',
		'social_single_color' => 'default',
		'social_facebook' => '#',
		'social_twitter' => '#',
		'social_instagram' => '#',
		'social_youtube' => '#',
		'social_pinterest' => '#',
		'social_telegram' => '#',
		'footer_texto' => '© '.date('Y').' - '.__( 'Derechos reservados', 'appyn' ).' - <a href="https://themespixel.net/en/theme/appyn/" target="_blank" rel="nofollow noopener">Appyn Theme</a>',
		'home_limite' => 12,
		'categories_home_limite' => 6,
		'blog_posts_limite' => 10,
		'mas_calificadas_limite' => 5,
		'blog_posts_home_limite' => 4,	
		'comments' => 'wp',
		'color_theme' => 'claro',
		'readmore_single' => 0,
		'color_theme_principal' => '#1bbc9b',
		'color_download_button' => '#1bbc9b',
		'color_new_ribbon' => '#d22222',
		'color_update_ribbon' => '#19b934',
		'color_stars' => '#f9bd00',
		'color_tag_mod' => '#20a400',
		'download_links' => 0,
		'social_single_color' => 'default',
		'appyn_amp' => 0,
		'redirect_timer' => 5,
		'download_timer' => 5,
		'edcgp_sapk_server' => 1,
		'edcgp_rating' => 1,
		'apps_info_download_apk' => sprintf( 
			"<p><strong>%s</strong></p><p>%s</p><p>%s</p><p>%s</p>", 
			__( '¿Cómo instalar [Title] APK?', 'appyn' ), 
			__( '1. Toca el archivo [Title] APK descargado.', 'appyn' ), 
			__( '2. Toca instalar.', 'appyn' ), 
			__( '3. Sigue los pasos que aparece en pantalla.', 'appyn' )
		),
		'apps_info_download_zip' => sprintf( 
			"<p><strong>%s</strong></p><p>%s</p><p>%s</p><p>%s</p><p>%s</p><p>%s</p>", 
			__( '¿Cómo instalar [Title]?', 'appyn' ), 
			__( '1. Descargar el archivo ZIP.', 'appyn' ), 
			sprintf( __( '2. Instale la aplicación %s', 'appyn' ), '<a href="https://play.google.com/store/apps/details?id=com.aefyr.sai" target="_blank">Split APKs Installer</a>'), 
			__( '3. Abra la aplicación y pulse en "Instalar APKs".', 'appyn' ),
			__( '4. Busque la carpeta donde se encuentra el ZIP descargado y selecciónelo.', 'appyn' ),
			__( '5. Sigue los pasos que aparece en pantalla.', 'appyn' )			
		),
		'general_text_edit' => array(
			'amc' => __( 'Aplicaciones más calificadas', 'appyn' ),
			'uadnw' => __( 'Últimas aplicaciones de nuestra web', 'appyn' ),
			'bua' => __( 'Buscar una aplicación', 'appyn' ),
			'bda' => __( 'Descargar APK', 'appyn' ),
		),
		'home_posts_orden' => 0,
		'edcgp_extracted_images' => 5,
		'apps_per_row_pc' => 6,
		'apps_per_row_movil' => 2,
		'mod_apps_data_gpl' => array( 
			'short_description',
			'requirements',
			'rating',
			'downloads',
			'category',
			'developer',
			'whats_new',
			'video',
			'screenshots',
		),
	);

	return $options;
}

add_action( 'after_switch_theme', 'px_default_options' );

function px_default_options(){

	$options = all_options();

	foreach( $options as $key => $value ){
		$getoption = get_option( 'appyn_'.$key );
		if( empty($getoption) ) {
			update_option( 'appyn_'.$key, $value );
		}
	}

	wp_redirect( admin_url('admin.php?page=appyn_panel') );
}

add_action( 'init', 'default_info_download_apk_zip' );

function default_info_download_apk_zip() {

	update_option( 'appyn_apps_default_info_download_apk', sprintf( 
		"<p><strong>%s</strong></p><p>%s</p><p>%s</p><p>%s</p>", 
		__( '¿Cómo instalar [Title] APK?', 'appyn' ), 
		__( '1. Toca el archivo [Title] APK descargado.', 'appyn' ), 
		__( '2. Toca instalar.', 'appyn' ), 
		__( '3. Sigue los pasos que aparece en pantalla.', 'appyn' )
	) );

	update_option( 'appyn_apps_default_info_download_zip', sprintf( 
		"<p><strong>%s</strong></p><p>%s</p><p>%s</p><p>%s</p><p>%s</p><p>%s</p>", 
		__( '¿Cómo instalar [Title]?', 'appyn' ), 
		__( '1. Descargar el archivo ZIP.', 'appyn' ), 
		sprintf( __( '2. Instale la aplicación %s', 'appyn' ), '<a href="https://play.google.com/store/apps/details?id=com.aefyr.sai" target="_blank">Split APKs Installer</a>'), 
		__( '3. Abra la aplicación y pulse en "Instalar APKs".', 'appyn' ),
		__( '4. Busque la carpeta donde se encuentra el ZIP descargado y selecciónelo.', 'appyn' ),
		__( '5. Sigue los pasos que aparece en pantalla.', 'appyn' )			
	) );
}

add_action( 'init', 'blog_register' ); 

function blog_register(){
	$labels = array(
		'name' => 'Blog',
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array('slug' => 'blog'),
		'has_archive' => true,
		'show_in_rest' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'supports' => array('title','editor','thumbnail','comments'),
	); 

	register_post_type('blog', $args );
}

add_action( 'init', 'dev_taxonomy_register' );

function dev_taxonomy_register() {

	register_taxonomy(
		'dev',
    	'post',
    	array(
      		'label' => __( 'Developer', 'appyn' ),
      		'sort' => true,
      		'args' => array( 'orderby' => 'term_order' ),
			'show_in_rest' => true,
      		'rewrite' => array( 'slug' => 'dev' ),
	  		'labels' => array( 'menu_name' => __( 'Developer', 'appyn' ) )
    	)
  	);

	register_taxonomy(
		'cblog',
		'blog',
		array(
			'label' => __( 'Categorías', 'appyn' ),
			'sort' => true,
			'args' => array( 'orderby' => 'term_order' ),
			'show_in_rest' => true,
			'rewrite' => array( 'slug'  => 'cblog' ),
			'labels' => array( 'menu_name' => __( 'Categorías', 'appyn' ) )
		)
	);

	register_taxonomy(
		'tblog',
		'blog',
    	array(
      		'label' => __( 'Etiquetas', 'appyn' ),
      		'sort' => true,
      		'args' => array( 'orderby' => 'term_order' ),
			'show_in_rest' => true,
			'rewrite' => array( 'slug'  => 'tblog' ),
	  		'labels' => array( 'menu_name' => __( 'Etiquetas', 'appyn' ) )
		)
  	);
}

add_action( 'init', 'add_px_rewrite_rule' );

function add_px_rewrite_rule() {	
    add_rewrite_rule( '^([^/]*)/versions/?$', 'index.php?name=$matches[1]&section=versions', 'top' );

	if( appyn_options( 'download_links_permalinks' ) == 1 ) {
		add_rewrite_rule( '^([^/]*)/download/?$', 'index.php?name=$matches[1]&download=links', 'top' );
		add_rewrite_rule( '^([^/]*)/download/([0-9]+)/?$', 'index.php?name=$matches[1]&download=links&opt=$matches[2]', 'top' );
	}
}

add_filter( 'query_vars', 'add_px_rewrite_var');

function add_px_rewrite_var( $vars ) {
    $vars[] = 'section';
	$vars[] = "download";
	$vars[] = "download_link";
	$vars[] = "opt";
    return $vars;
}

add_action( 'wp', 'px_404_versiones' );

function px_404_versiones() {
	global $post;
	if( get_query_var( 'section' ) == __( 'versiones', 'appyn' ) && $post->post_parent ) {
		global $wp_query;
		$wp_query->set_404();
		status_header(404);
	}
}

add_action( 'wp', 'px_wp_post_views');

function px_wp_post_views(){
	global $post;
	if( !is_single() ) {
		return;
	}
	setPostViews( $post->ID );
}

add_action( 'wp', 'new_rating_db' );

function new_rating_db() {
	global $wpdb;
	$table_name = $wpdb->prefix."ap_rating";
	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) != $table_name ) return;

	$results = $wpdb->get_results( "SELECT *, COUNT(rating_count) users, SUM(rating_count) total_rating FROM $table_name GROUP BY post_id", 'OBJECT' );
	if( $results ) {
			$wpdb->query( "DROP TABLE IF EXISTS $table_name");	
		foreach( $results as $r ) {	
			update_post_meta( $r->post_id, 'new_rating_users', $r->users );
			update_post_meta( $r->post_id, 'new_rating_count', $r->total_rating );
			update_post_meta( $r->post_id, 'new_rating_average', number_format(($r->total_rating / $r->users), 1, ".", "") );	
		}
	}
}

add_action( 'wp', 'new_views' );

function new_views() {
	global $wpdb;
	$table_name = $wpdb->prefix."views";
	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) != $table_name ) return;

	$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."views_temp");
	
	$results = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."views" );

	if( !$results ) return;

	foreach( $results as $r ) {
		update_post_meta( $r->post_id, 'px_views', $r->total );
	}
	$wpdb->query( "DROP TABLE $table_name" );
}

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('template_redirect', 'rest_output_link_header', 11, 0);
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');

add_filter( 'posts_where', 'wpse18703_posts_where', 10, 2 );

function wpse18703_posts_where($where, $wp_query){
    global $wpdb;
    if ( $wpse18703_title = $wp_query->get( 'wpse18703_title' ) ) {
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( $wpse18703_title ) ) . '%\'';
    }
    return $where;
}

add_action( 'add_meta_boxes', 'datos_meta_boxes' );  

function datos_meta_boxes(){ 
    add_meta_box(
        'datos_informacion', 
        __( 'Application Information', 'appyn' ), 
        'callback_informacion', 
        'post', 
        'normal'
    );  
    
    add_meta_box(
        'datos_video',  
        __( 'Application Video', 'appyn' ), 
        'callback_video', 
        'post', 
        'normal'
    );  
    
    add_meta_box(
        'datos_imagenes', 
        __( 'Application Images', 'appyn' ), 
        'callback_images', 
        'post', 
        'normal'
    ); 
     
    add_meta_box(
        'datos_download', 
        __( 'Application Download Links', 'appyn' ), 
        'datos_download', 
        'post', 
        'normal'
    );  
    
    add_meta_box(
        'custom_boxes', 
        __( 'Custom Boxes', 'appyn' ), 
        'custom_boxes', 
        'post', 
        'normal'
    );  
    
    add_meta_box(
        'permanent_custom_boxes', 
        __( 'Permanent Custom Boxes', 'appyn' ), 
        'permanent_custom_boxes', 
        'post', 
        'normal'
    );  
    
    add_meta_box(
        'box_ads_control', 
        __( 'Ads Control', 'appyn' ), 
        'callback_box_ads_control', 
        array('post', 'page', 'blog'), 
        'normal'
    );   
    

}



function callback_box_ads_control( $post ){

	$selected_ad = appyn_gpm( $post->ID, 'appyn_selected_ad' );
	echo '<p><label><input type="radio" name="appyn_selected_ad" value="ad1" ' . checked( 'ad1', $selected_ad, false ) . '> ' . __( 'Ads Name 1', 'appyn' ) . '</label></p>';
    echo '<p><label><input type="radio" name="appyn_selected_ad" value="ad2" ' . checked( 'ad2', $selected_ad, false ) . '> ' . __( 'Ads Name 2', 'appyn' ) . '</label></p>';
	echo '<p><label><input type="radio" name="appyn_selected_ad" value="ad3" ' . checked( 'ad3', $selected_ad, false ) . '> ' . __( 'Disable Ads', 'appyn' ) . '</label></p>';
}


function permanent_custom_boxes( $post ){
	$pcb = get_option( 'permanent_custom_boxes' );

	echo '<div id="permanent-boxes-content">';

	if( !empty($pcb) && is_array($pcb) ) {
		$i = 0;
		foreach($pcb as $box_key => $box_value) : $i++;
			if( !empty( $box_value['title'] ) || !empty( $box_value['content'] ) ) { ?>
			<div class="boxes-a">
				<h4><?php echo sprintf( __( 'Permanent box %s', 'appyn' ), '#'.$i ); ?></h4>
				<p><input type="text" id="permanent_custom_boxes-title" class="widefat" name="permanent_custom_boxes[<?php echo $box_key; ?>][title]" value="<?php echo $box_value['title']; ?>" placeholder="<?php echo __( 'Title for the box', 'appyn' ); ?>"></p>

				<p><?php wp_editor_fix($box_value['content'], 'permanent_custom_boxes-'.$box_key, array('textarea_name' => 'permanent_custom_boxes['.$box_key.'][content]', 'textarea_rows' => 5)); ?>
				</p>
				<p><a href="javascript:void(0)" class="delete-boxes button"><?php echo __( 'Delete box', 'appyn' ); ?></a></p>
			</div>
<?php } endforeach; 
	}
	echo '</div>';
	echo '<a href="javascript:void(0)" id="add-permanent-boxes" class="button">+ '.__( 'Add box', 'appyn' ).'</a>'; ?>
<?php
}

function custom_boxes( $post ){
    $custom_boxes = get_post_meta($post->ID, 'custom_boxes', true);
    echo '<div id="boxes-content">';
    if(!empty($custom_boxes)) {
        foreach($custom_boxes as $box_key => $box_value) : 
            if( !empty( $box_value['title'] ) || !empty( $box_value['content'] ) ) { ?>
                <div class="boxes-a">
                    <p>
                        <input 
                            type="text" 
                            id="custom_boxes-title" 
                            class="widefat" 
                            name="custom_boxes[<?php echo $box_key; ?>][title]" 
                            value="<?php echo esc_attr($box_value['title']); ?>" 
                            placeholder="<?php echo __( 'Title for the box', 'appyn' ); ?>"
                        >
                    </p>

                    <p>
                        <?php 
                        wp_editor_fix(
                            $box_value['content'], 
                            'custom_boxes-'.$box_key, 
                            array(
                                'textarea_name' => 'custom_boxes['.$box_key.'][content]', 
                                'textarea_rows' => 5
                            )
                        ); 
                        ?>
                    </p>
                    <p>
                        <a href="javascript:void(0)" class="delete-boxes button">
                            <?php echo __( 'Delete box', 'appyn' ); ?>
                        </a>
                    </p>
                </div>
        <?php } endforeach; 
    }
    echo '</div>';
    echo '<a href="javascript:void(0)" id="add-boxes" class="button">+ '.__( 'Add box', 'appyn' ).'</a>';
}


function px_label_help( $t, $a = false ) {
	return '<div class="px-label-info"><span class="dashicons dashicons-editor-help"></span><div class="pxli-content"'.(($a) ? ' style="width:auto"' : '').'>'.$t.'</div></div>';
}

function callback_informacion( $post ){
    ?>
    <div><?php echo __( 'Application Status', 'appyn' ); ?>:
        <?php echo px_label_help( __('With this option, a banner will appear on each application indicating whether it has been updated or is a new application. The active option will have a duration of 2 weeks based on the post\'s publication date. For example, if you mark "Updated" and the post\'s creation date is today, the banner will only appear for 2 weeks.', 'appyn' )); ?>
        <select name="datos_informacion[app_status]" id="app_status">
            <?php px_filter_app_status(); ?>
        </select>
    </div>

    <p><?php echo __( 'Application Type', 'appyn' ); ?>:
        <select name="app_type" id="app_type">
            <option value="0"><?php echo __( 'Normal', 'appyn' ); ?></option>
            <option value="1"<?php selected( appyn_gpm( $post->ID, 'app_type' ), 1 ); ?>>MOD</option>
        </select>
    </p>

    <p><?php echo __( 'Short Description', 'appyn' ); ?>:<br>
        <textarea class="widefat" name="datos_informacion[descripcion]" id="descripcion"><?php echo get_datos_info('descripcion'); ?></textarea>
    </p>

    <p><?php echo __( 'Version', 'appyn' ); ?>:<br>
        <input type="text" class="widefat" name="datos_informacion[version]" id="version" value="<?php echo strip_tags(get_datos_info('version')); ?>">
    </p>

    <p><?php echo __( 'Size', 'appyn' ); ?>:<br>
        <input type="text" class="widefat" name="datos_informacion[tamano]" id="tamano" value="<?php echo strip_tags(get_datos_info('tamano')); ?>">
    </p>

    <p><?php echo __( 'Release Date', 'appyn' ); ?>:<br>
        <input type="text" class="widefat" name="datos_informacion[released_on]" id="released_on" value="<?php echo strip_tags(get_datos_info('released_on')); ?>"><input type="hidden" class="widefat" name="datos_informacion[released_on]" id="released_on" value="<?php echo strip_tags(get_datos_info('released_on')); ?>">
    </p>

    <p><?php echo __( 'Last Update', 'appyn' ); ?>:<br>
        <input type="text" class="widefat" name="datos_informacion[fecha_actualizacion]" id="fecha_actualizacion" value="<?php echo strip_tags(get_datos_info('fecha_actualizacion')); ?>"><input type="hidden" class="widefat" name="datos_informacion[last_update]" id="last_update" value="<?php echo strip_tags(get_datos_info('last_update')); ?>">
    </p>

    <p><?php echo __( 'Requirements', 'appyn' ); ?>:<br>
        <input type="text" class="widefat" name="datos_informacion[requirements]" id="requirements" value="<?php echo strip_tags(get_datos_info('requirements')); ?>">
    </p>

    <p><?php echo __( 'Get it at', 'appyn' ); ?>:<br>
        <input type="text" class="widefat" name="datos_informacion[consiguelo]" id="consiguelo" value="<?php echo get_datos_info('consiguelo'); ?>">
    </p>

    <?php   
        $new_rating_average = ( get_post_meta( $post->ID, 'new_rating_average', true ) ) ? get_post_meta( $post->ID, 'new_rating_average', true ) : 0;
        $new_rating_users = ( get_post_meta( $post->ID, 'new_rating_users', true ) ) ? get_post_meta( $post->ID, 'new_rating_users', true ) : 0;
        ?>
    <p><?php echo __( 'Rating', 'appyn' ); ?> (<?php echo __( 'Number of Votes', 'appyn' ); ?>):<br>
        <input type="number" min="0" class="widefat" name="new_rating_users" id="new_rating_users" value="<?php echo @$new_rating_users; ?>">
    </p>

    <p><?php echo __( 'Rating', 'appyn' ); ?> (<?php echo __( 'Average', 'appyn' ); ?>):<br>
        <input type="number" min="0" step="0.1" class="widefat" name="new_rating_average" id="new_rating_average" value="<?php echo @$new_rating_average; ?>" placeholder="4.5">
    </p>

    <p><?php echo __( 'Downloads', 'appyn' ); ?>:<br>
        <input type="text" class="widefat" name="datos_informacion[downloads]" id="downloads" value="<?php echo get_datos_info( 'downloads' ); ?>">
    </p>
    <p><?php echo __( 'Application Type (Category)', 'appyn' ); ?>:
        <select name="datos_informacion[categoria_app]">
        <?php
        $catsapp = px_cats_app();
        foreach( $catsapp as $key => $cat ) {
            echo '<option value="'.$key.'"'.selected( get_datos_info('categoria_app'), $key, false ).'>'.$cat.'</option>';
        } ?></select>
    </p>

    <p><?php echo __( 'Operating System', 'appyn' ); ?>:
        <label><input type="radio" name="datos_informacion[os]" value="ANDROID" <?php checked( get_datos_info('os'), 'ANDROID' ); ?> <?php echo (!isset( $datos_informacion['os'] ) ? 'checked' : ''); ?>> Android</label>&nbsp;
        <label><input type="radio" name="datos_informacion[os]" value="iOS" <?php checked( get_datos_info('os'), 'iOS' ); ?>> iOS</label>&nbsp;
        <label><input type="radio" name="datos_informacion[os]" value="MAC" <?php checked( get_datos_info('os'), 'MAC' ); ?>> Mac</label>&nbsp;
        <label><input type="radio" name="datos_informacion[os]" value="WINDOWS" <?php checked( get_datos_info('os'), 'WINDOWS' ); ?>> Windows</label>&nbsp;
        <label><input type="radio" name="datos_informacion[os]" value="LINUX" <?php checked( get_datos_info('os'), 'LINUX' ); ?>> Linux</label>
    </p>

    <p><label><input type="radio" name="datos_informacion[offer][price]" value="gratis" <?php echo ( (empty(get_datos_info('offer', 'price') || get_datos_info('offer', 'price') == "gratis") ) ? ' checked' : ''); ?>> <?php echo __( 'Free', 'appyn' ); ?></label> &nbsp;
        <label><input type="radio" name="datos_informacion[offer][price]" value="pago" <?php checked( get_datos_info('offer', 'price'), 'pago' ); ?>> <?php echo __( 'Paid', 'appyn' ); ?></label>
        <label><input type="text" name="datos_informacion[offer][amount]" value="<?php echo get_datos_info('offer', 'amount'); ?>" placeholder="1.00" style="width: 50px;"></label>
        <label><select name="datos_informacion[offer][currency]">
        <?php 
        $currencys = array( 'USD', 'EUR', 'AED', 'AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD', 'AWG', 'AZN', 'BAM', 'BBD', 'BDT', 'BGN', 'BHD', 'BIF', 'BMD', 'BND', 'BOB', 'BRL', 'BSD', 'BTN', 'BWP', 'BYN', 'BZD', 'CAD', 'CDF', 'CHF', 'CLP', 'CNY', 'COP', 'CRC', 'CUP', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD', 'EGP', 'ERN', 'ETB', 'FJD', 'FKP', 'GBP', 'GEL', 'GGP', 'GHS', 'GIP', 'GMD', 'GNF', 'GTQ', 'GYD', 'HKD', 'HNL', 'HRK', 'HTG', 'HUF', 'IDR', 'ILS', 'IMP', 'INR', 'IQD', 'IRR', 'ISK', 'JEP', 'JMD', 'JOD', 'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KPW', 'KRW', 'KWD', 'KYD', 'KZT', 'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'LYD', 'MAD', 'MDL', 'MGA', 'MKD', 'MMK', 'MNT', 'MOP', 'MRU', 'MUR', 'MVR', 'MWK', 'MXN', 'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'OMR', 'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SDG', 'SEK', 'SGD', 'SHP', 'SLL', 'SOS', 'SRD', 'SSP', 'STN', 'SYP', 'SZL', 'THB', 'TJS', 'TMT', 'TND', 'TOP', 'TRY', 'TTD', 'TWD', 'TZS', 'UAH', 'UGX', 'UYU', 'UZS', 'VES', 'VND', 'VUV', 'WST', 'XAF', 'XCD', 'XDR', 'XOF', 'XPF', 'YER', 'ZAR', 'ZMW' );
    
        foreach( $currencys as $cur ) {
            echo '<option value="'.$cur.'"'.selected( get_datos_info('offer', 'currency'), $cur, true ).'>'.$cur.'</option>';
        } 
        ?>
            </select>
    </p>

    <p><?php echo __( 'News', 'appyn' ); ?>:<br>
        <?php wp_editor_fix( get_datos_info('novedades'), 'novedades', array('textarea_name' => 'datos_informacion[novedades]', 'textarea_rows' => 5)); ?>
    </p>

    <?php
}


function callback_video( $post ){
	$datos_video = get_post_meta($post->ID, 'datos_video', true);
	?>
	<p>ID YouTube:<br>
		<input type="text" class="widefat" id="id_video" name="datos_video[id]" placeholder="TkErUvyVlhA" value="<?php echo ( isset($datos_video['id']) ) ? $datos_video['id'] : ''; ?>">
	</p>
	<?php
}

function callback_images( $post ){
    $datos_imagenes = get_post_meta($post->ID, 'datos_imagenes', true);
    $datos_imagenes = !empty($datos_imagenes) ? $datos_imagenes : array();
    $c = 4;
    $input_upload = '<input class="upload_image_button button" type="button" value="'.__( 'Upload', 'appyn' ).'" style="width:auto; vertical-align:middle; font-family:inherit">';
    ?>
    <script>
    jq1 = jQuery.noConflict();
    jq1(function($) {
        var count = <?php echo $c; ?>;
        $(document).on('click', '.removeimage', function() {
            $(this).parents('p').remove();
            count--;
        });
        $(".addImg").on('click', function() {
            $(".ElementImagenes").append('<p><input type="text" name="datos_imagenes[' + count +
                ']" value="" class="regular-text upload"><?php echo @$input_upload; ?><a href="javascript:void(0)" class="removeimage">X</a></p>'
                );
            count++;
        });
    });
    </script>
    <div class="ElementImagenes">
    <div class="download"></div>
    <?php 
    if (!is_array($datos_imagenes)) {
        // Nếu $datos_imagenes là chuỗi JSON, cố gắng giải mã nó
        $decoded = json_decode($datos_imagenes, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $datos_imagenes = $decoded;
        } else {
            // Nếu không thể giải mã, gán giá trị mặc định là mảng rỗng
            $datos_imagenes = [];
            // Bạn cũng có thể log lỗi hoặc xử lý theo cách khác
            error_log('Không thể giải mã $datos_imagenes thành mảng.');
        }
    }

    // Khởi tạo biến $n
    $n = 0;

    // Đảm bảo rằng $datos_imagenes là mảng và có thể đếm được
    if (is_countable($datos_imagenes) && count($datos_imagenes) > 10) {

        foreach($datos_imagenes as $elemento) {
            echo '<p><input type="text" name="datos_imagenes[' . esc_attr($n) . ']" value="' . ( !empty($datos_imagenes[$n]) ? esc_attr($datos_imagenes[$n]) : '' ) . '" id="imagenes' . esc_attr($n) . '" class="regular-text upload">' . $input_upload . '</p>';
            $n++; 
        }

    } else { 

        for($i = 0; $i < 10; $i++) {
            echo '<p><input type="text" name="datos_imagenes[' . esc_attr($i) . ']" value="' . ( !empty($datos_imagenes[$i]) ? esc_attr($datos_imagenes[$i]) : '' ) . '" id="imagenes' . esc_attr($i) . '" class="regular-text upload">' . $input_upload . '</p>';
        }  

    } 
    echo '</div>
    <p class="addImg button"><b>+ '.__( 'Add images', 'appyn' ).'</b></p>';

    wp_nonce_field( plugin_basename( __FILE__ ), 'dynamicMeta_noncename' );
}


function datos_download( $post ){
    $post = $post;
    $datos_download = get_datos_download();
    ?>
    <div class="download-direct">
        <ul class="dd-options">
            <?php if(empty($datos_download['option'])) { ?>
                <li data-option="1" class="button active"><label><?php echo __( 'Download Links', 'appyn' ); ?><input type="radio" name="datos_download[option]" value="links" style="display:none;"></label></li>
                <li data-option="2" class="button"><label><?php echo __( 'Direct Link / Redirect', 'appyn' ); ?><input type="radio" name="datos_download[option]" value="direct-link" style="display:none;"></label>
                </li>
                <li data-option="3" class="button"><label><?php echo __( 'Direct Download', 'appyn' ); ?><input type="radio" name="datos_download[option]" value="direct-download" style="display:none;"></label></li>
            <?php } else { ?>
                <li data-option="1" class="button<?php echo (!$datos_download['option'] || $datos_download['option'] == "links") ? ' active': ''; ?>"><label><?php echo __( 'Download Links', 'appyn' ); ?><input type="radio" name="datos_download[option]" value="links" style="display:none;"></label></li>
                <li data-option="2" class="button<?php echo ($datos_download['option'] == "direct-link") ? ' active': ''; ?>"><label><?php echo __( 'Direct Link / Redirect', 'appyn' ); ?><input type="radio" name="datos_download[option]" value="direct-link" style="display:none;"></label></li>
                <li data-option="3" class="button<?php echo ($datos_download['option'] == "direct-download") ? ' active': ''; ?>"> <label><?php echo __( 'Direct Download', 'appyn' ); ?><input type="radio" name="datos_download[option]" value="direct-download" style="display:none;"></label></li>
            <?php } ?>
        </ul>
    </div>
    <?php   
    $ddf = (isset($datos_download['type'])) ? $datos_download['type'] : 'apk';
    ?>
    <?php echo __( 'File Type', 'appyn' ); ?>
    <?php echo px_label_help( sprintf( __( 'Select the type of file the user will download. Thanks to this, you can display steps based on the chosen option. <a href="%s">View/Edit the steps</a>', 'appyn' ), admin_url('admin.php?page=appyn_panel#general') ) ); ?>

    <p>
        <input type="radio" name="datos_download[type]" value="apk" <?php echo checked($ddf, 'apk'); ?>>APK &nbsp;
        <input type="radio" name="datos_download[type]" value="apk_obb" <?php echo checked($ddf, 'apk_obb'); ?>>APK + OBB &nbsp;
        <input type="radio" name="datos_download[type]" value="zip" <?php echo checked($ddf, 'zip'); ?>>ZIP
    </p>
    <?php if(empty($datos_download['option'])) { ?>
    <div class="dd-content" data-option="1" style="display:block;">
    <?php } elseif(!$datos_download['option']) { ?>
    <div class="dd-content" data-option="1" style="display:block;">
    <?php } else { ?>
    <div class="dd-content" data-option="1" <?php echo ($datos_download['option'] == "links") ? ' style="display:block;"': ' style="display:none";'; ?>>
    <?php } ?>

        <p><em><?php echo __( 'To remove a field, just leave it empty', 'appyn' ); ?>.</em><br>
            <em><?php echo __("Links 'nofollow' by default", 'appyn'); ?>.</em>
        </p>
        <div class="ElementLinks">
            <table style="width:100%;">
                <thead>
                    <tr>
                        <th></th>
                        <th style="width:60%;"><?php echo __( 'Link', 'appyn' ); ?></th>
                        <th><?php echo __( 'Text', 'appyn' ); ?></th>
                        <th><?php echo __( 'Attribute', 'appyn' ); ?></th>
                        <th style="width:30px;"></th>
                    </tr>
                </thead>
                <tbody id="tbodylinks">
                    <?php
					$px_app_id = get_post_meta( $post->ID, 'px_app_id', true );
                    $list_count = !empty($datos_download['links_options']) ? count($datos_download['links_options']) : 1;

                    for( $i=0; $i<$list_count; $i++ ) {

                        $link = isset($datos_download['links_options'][$i]['link']) ? $datos_download['links_options'][$i]['link'] : 'https://modgara.com/downloads/ggplay/'.$px_app_id.'.apk';

                        if( appyn_options( 'shortlink_disabled' ) ) {
                            $link = isset($datos_download['links_options'][$i]['link_original']) ? $datos_download['links_options'][$i]['link_original'] : $link;
                        }

                        $text = isset($datos_download['links_options'][$i]['texto']) ? $datos_download['links_options'][$i]['texto'] : 'Download';

                        $follow = isset($datos_download['links_options'][$i]['follow']) ? $datos_download['links_options'][$i]['follow'] : '';
                    ?>
                        <tr>
                            <td><span class="dashicons dashicons-move"></span></td>
                            <td><input type="text" name="datos_download[<?php echo $i; ?>][link]" value="<?php echo $link; ?>" class="widefat">
                                <input type="hidden" name="datos_download[<?php echo $i; ?>][link_original]" value="<?php echo ( isset($datos_download['links_options'][$i]['link_original'] ) ? $datos_download['links_options'][$i]['link_original'] : ''); ?>">
                                <input type="hidden" name="datos_download[<?php echo $i; ?>][shortlink]" value="<?php echo ( isset($datos_download['links_options'][$i]['shortlink'] ) ? $datos_download['links_options'][$i]['shortlink'] : ''); ?>">
                            </td>
                            <td><input type="text" name="datos_download[<?php echo $i; ?>][texto]" value="<?php echo $text; ?>" class="widefat"></td>
                            <td><label><input type="checkbox" value="1" name="datos_download[<?php echo $i; ?>][follow]" <?php checked($follow, '1'); ?>> Follow</label></td>
                        </tr>
                        <?php
                    } ?>
                </tbody>
            </table>
        </div>
        <p class="addLink button"><b>+ <?php echo __( 'Add link', 'appyn' ); ?></b></p>
        <p><a href="https://themespixel.net/en/docs/appyn/posts/#doc4" target="_blank"><?php echo __( 'See in the documentation', 'appyn' ); ?></a></p>
    </div>
    <?php
    $dd = get_datos_download();
    $d_option = (isset($dd['option'])) ? $dd['option'] : null;
    ?>
    <div class="dd-content" data-option="2" <?php echo ( $d_option == "direct-link" ) ? 'style="display:block;"': ''; ?>>
        <p><?php echo __( 'Direct Link / Redirect', 'appyn' ); ?><br>
            <input type="text" placeholder="Link" class="widefat" name="datos_download[direct-link]" value="<?php echo ( isset($datos_download['direct-link']) ) ? $datos_download['direct-link'] : ''; ?>">
        </p>
    </div>
    <div class="dd-content" data-option="3" <?php echo ($d_option == "direct-download") ? 'style="display:block;"': ''; ?>>
        <p><?php echo __( 'Direct Download', 'appyn' ); ?><br>
            <input type="text" placeholder="File link" name="datos_download[direct-download]" value="<?php echo ( isset($datos_download['direct-download']) ) ? $datos_download['direct-download'] : ''; ?>" class="upload" style="width:500px;"><input class="upload_image_button button" type="button" value="<?php echo __( 'Upload', 'appyn' ); ?>" style="width: auto;vertical-align: middle;font-family: inherit;">
        </p>
    </div>
    <?php
}


function replace_r_n_n( $var ) {
	$var = str_replace("\r\n", "\n", $var );
	return $var;
}

add_action( 'save_post', 'px_quote_meta_save' );

function px_quote_meta_save( $id ) {
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	
	if( isset($_POST['dynamicMeta_noncename']) )
		if( ! wp_verify_nonce( $_POST['dynamicMeta_noncename'], plugin_basename( __FILE__ ))) return;

	if( ! current_user_can( 'edit_post', $id ) ) return;
	
	if(isset($_POST['datos_informacion'])) {
		update_post_meta( $id, "datos_informacion", array_map( 'replace_r_n_n', $_POST['datos_informacion'] ) );

		if( !empty($_POST['datos_informacion']['consiguelo']) ) {
						
			$re = '/(?<=[?&]id=)[^&]+/m';
			preg_match_all($re, $_POST['datos_informacion']['consiguelo'], $matches, PREG_SET_ORDER, 0);
			$app_id = ( isset($matches[0][0]) ) ? $matches[0][0] : '';
			update_post_meta( $id, 'px_app_id', $app_id );
		}
	}

	if(isset($_POST['datos_video']))
		update_post_meta( $id, "datos_video", $_POST['datos_video'] );

	if(isset($_POST['datos_imagenes']))
		update_post_meta( $id, "datos_imagenes", $_POST['datos_imagenes'] );

	if(isset($_POST['datos_download'])) {

		$datos_download_d = get_datos_download();

		$datos_download = array_merge($_POST['datos_download']);

		foreach( $datos_download as $key => $dw ) {

			if( isset($dw['link']) && isset($datos_download_d['links_options'][$key]['link']) ) {

				if( $dw['link'] != $datos_download_d['links_options'][$key]['link'] ) {

					$datos_download[$key]['link_original'] = $dw['link'];
					
					$pdls4 = get_option( 'posts_download_links_status_404', array('') );
					$arr = array_values(array_diff($pdls4 ,[$id]));
					update_option( 'posts_download_links_status_404', $arr );

				}
			}
		}

		update_post_meta( $id, "datos_download", $datos_download );
	}

	if(isset($_POST['custom_boxes'])) {
		update_post_meta( $id, "custom_boxes", $_POST['custom_boxes'] );
	} else {
		delete_post_meta( $id, "custom_boxes" );
	}

	if( isset($_POST['permanent_custom_boxes']) ) {
		$pcb = $_POST['permanent_custom_boxes'];
		array_multisort($pcb);
		update_option( "permanent_custom_boxes", stripslashes_deep($pcb) );
		$oc = get_option( 'appyn_orden_cajas', null );
		if( $oc ) {
			$add = array();
			foreach( $pcb as $k => $p ) {
				if( !array_key_exists('permanent_custom_box_'.$k, $oc ) ) 
					$oc['permanent_custom_box_'.$k] = stripslashes_deep($pcb)[$k]['title'];
			}
			update_option( 'appyn_orden_cajas', $oc );
		}
	}
	
	if( isset($_POST['new_rating_users']) || isset($_POST['new_rating_average']) ) {
		update_post_meta( $id, "new_rating_users", @$_POST['new_rating_users'] );
		update_post_meta( $id, "new_rating_average", @$_POST['new_rating_average'] );
		$nru = (empty($_POST['new_rating_users']) ? 0 : $_POST['new_rating_users'] );
		$nra = (empty($_POST['new_rating_average']) ? 0 : $_POST['new_rating_average'] );
		update_post_meta( $id, "new_rating_count", ceil($nru * $nra));
	}

	delete_post_meta( $id, "app_type" );
	if( isset($_POST['app_type']) ) {
		update_post_meta( $id, "app_type", $_POST['app_type'] );
	}
	

	 // Kiểm tra và lưu lựa chọn quảng cáo
	 delete_post_meta( $id, "appyn_selected_ad" );
	if( isset($_POST['appyn_selected_ad']) ) {
		update_post_meta( $id, "appyn_selected_ad", $_POST['appyn_selected_ad'] );
	}
	delete_post_meta( $id, "appyn_hidden_sidebar" );
	if( isset($_POST['appyn_hidden_sidebar']) ) {
		update_post_meta( $id, "appyn_hidden_sidebar", $_POST['appyn_hidden_sidebar'] );
	}
	
	delete_post_meta( $id, "appyn_hidden_post_meta" );
	if( isset($_POST['appyn_hidden_post_meta']) ) {
		update_post_meta( $id, "appyn_hidden_post_meta", $_POST['appyn_hidden_post_meta'] );
	}
	
	delete_post_meta( $id, "appyn_hidden_social_buttons" );
	if( isset($_POST['appyn_hidden_social_buttons']) ) {
		update_post_meta( $id, "appyn_hidden_social_buttons", $_POST['appyn_hidden_social_buttons'] );
	}
	
}

add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

function custom_excerpt_length($length) {
	return 5;	
}

add_filter( 'excerpt_more', 'new_excerpt_more' );

function new_excerpt_more( $more ) {
	return '... <div class="readmore"><a href="'.get_permalink( get_the_ID() ).'" title="'.__( 'Seguir leyendo', 'appyn' ).'">'.__( 'Seguir leyendo', 'appyn' ).'</a></div>';
}

add_filter( 'image_size_names_choose', 'px_image_sizes' );

function px_image_sizes( $sizes ) {
    $addsizes = array(
		"minimo" => "Mínimo",
		"medio" => "Medio"
    );
    $newsizes = array_merge($sizes, $addsizes);
    return $newsizes;
}

add_filter( 'comment_text', 'div_comment_content' );

function div_comment_content( $comment_text ) {
	$comment_text = '<div class="comment-content">'.wpautop($comment_text).'</div>';
	return $comment_text;
}
require_once( TEMPLATEPATH . '-child/includes/flatsome.php' );
require_once( TEMPLATEPATH . '-child/includes/template-functions.php' );
require_once( TEMPLATEPATH . '-child/includes/template-actions.php' );
require_once( TEMPLATEPATH . '-child/includes/template-tags.php' );
require_once( TEMPLATEPATH . '-child/includes/admin.php' );
require_once( TEMPLATEPATH . '-child/includes/ajax.php' );
require_once( TEMPLATEPATH . '-child/includes/widget-ultimos-posts.php' );
require_once( TEMPLATEPATH . '-child/includes/widget-mejor-calificados.php' );
require_once( TEMPLATEPATH . '-child/includes/widget-mas-vistos.php' );
require_once( TEMPLATEPATH . '-child/includes/widget-facebook.php' );
require_once( TEMPLATEPATH . '-child/includes/widget-twitter.php' );
require_once( TEMPLATEPATH . '-child/includes/widget-youtube.php' );
require_once( TEMPLATEPATH . '-child/includes/widget-ultimos-posts-blog.php' );
require_once( TEMPLATEPATH . '-child/includes/widget-mas-vistos-blog.php' );
require_once( TEMPLATEPATH . '-child/includes/widget-mas-calificados.php' );
require_once( TEMPLATEPATH . '-child/includes/widget-categories.php' );
require_once( TEMPLATEPATH . '-child/includes/widget-fixed.php' );
require_once( TEMPLATEPATH . '-child/includes/class-list-table-atul.php' );
require_once( TEMPLATEPATH . '-child/includes/class-list-table-modapps.php' );
require_once( TEMPLATEPATH . '-child/includes/class-list-table-latestapps.php' );
require_once( TEMPLATEPATH . '-child/includes/class-upload-apk.php' );
require_once( TEMPLATEPATH . '-child/includes/class-google-drive.php' );
require_once( TEMPLATEPATH . '-child/includes/class-dropbox.php' );
require_once( TEMPLATEPATH . '-child/includes/class-ftp.php' );
require_once( TEMPLATEPATH . '-child/includes/class-1fichier.php' );
require_once( TEMPLATEPATH . '-child/includes/class-onedrive.php' );
require_once( TEMPLATEPATH . '-child/includes/class-shortlinks.php' );
require_once( TEMPLATEPATH . '-child/includes/crawdata.php' );
require_once( TEMPLATEPATH . '-child/admin/class-eps.php' );

add_action( 'wp_head', 'add_my_favicon' );
add_action( 'admin_head', 'add_my_favicon' ); 

function add_my_favicon() {
	global $post;
	$favicon = get_option( 'appyn_favicon' );
	$favicon = ( !empty($favicon) ) ? $favicon: get_stylesheet_directory_uri().'/images/favicon.ico';
	echo '<link rel="icon" href="'.$favicon.'">';
}

add_action( 'wp_head', 'add_head', 1 );

function add_head() {
	global $post;
	if(  wp_is_mobile() ) { 
		$styles = str_replace("url(images/", "url(".get_bloginfo('template_directory')."/images/", 
		file_get_contents( TEMPLATEPATH . '/style.min.css') );
		echo '<style>'.$styles.'</style>';
	} 	
	$color_theme_principal = str_replace('#', '',get_option( 'appyn_color_theme_principal' ));	
	if($color_theme_principal){
		echo '<meta name="theme-color" content="#'.$color_theme_principal.'">';
	} else {
		echo '<meta name="theme-color" content="#1d222d">';	
	}

	echo "<script>
	function setCookie(cname, cvalue, exdays) {
		var d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		var expires = \"expires=\"+ d.toUTCString();
		document.cookie = cname + \"=\" + cvalue + \";\" + expires + \";path=/\";
	}</script>";
	$script_loadfont = "
	<link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>
	<script>
	const loadFont = (url) => {
		var xhr = new XMLHttpRequest();
		xhr.open('GET', url, true);
		xhr.onreadystatechange = () => {
		  if (xhr.readyState == 4 && xhr.status == 200) {
			const head = document.getElementsByTagName('head')[0];
			const style = document.createElement('style');
			style.appendChild(document.createTextNode(xhr.responseText));
			head.appendChild(style);
		  }
		};
		xhr.send();
	};
	loadFont('".get_stylesheet_directory_uri()."/assets/css/font-awesome-6.4.2.min.css');
	</script>
	";
	echo ( ! httuachl() ) ? str_replace(array("\n", "\t"), "", str_replace("  ", " ", $script_loadfont)) : '';
	$header_codigos = stripslashes(get_option('appyn_header_codigos'));
	echo $header_codigos;
	
	px_data_structure();
}

function px_css_dark_theme() {
	$css = "body, .wrapper-inside {
		color: #d8d2d2;
		background: #1d222d;
  	}
	table thead th,
	#versiones table tbody > tr:nth-child(odd) td,
	.pxtd .table tr:nth-child(odd) td {
		background: rgba(255,255,255,0.05);
	}
	table tbody td,
	table tfoot td,
	table tfoot th,
	#versiones table tbody tr td {
		background: rgba(255,255,255,0.02);
	}
	#versiones table tbody tr td {
		background: transparent;
	}
  	#header, #header menu .menu .sub-menu, #header menu .menu .sub-menu li a, #footer, #px-bottom-menu {
		background: #13161d;
  	}
	.box-rating .rating {
		background-color: #7c7f84;
	}
	table td, table th, 
	.box .box-title::before, 
	.section.blog .bloques li, .section.blog .title-section, .box .comments-title, #versiones table thead tr th, #slideimages .item img, .pxtd .table td, .loading, .g-recaptcha::before {
    	border-color: #232834;
  	}
	html .section .bav2>a::before {
		background: rgb(31 34 39 / 30%);
	}
	html .section .bav a,
	.section a.more:hover,
	.section .bloque-blog, 
	.widget .widget-content ul li:hover a::before,
	.w75.bloque-imagen.bi_ll, 
	.pxtd .entry.bx-info-install,
	.fp_box a {
		background-color: #282d3a;
  	}		  
	table tr:nth-child(even), table tbody th {
		background: #252a37;
	}
	#versiones table thead tr th {
		background: #1f2430;
	}
	html .section .bav a,
	html .gsc-control-cse,
	.bloque-blog {
	  	box-shadow:2px 2px 2px 0px #1a1c1f;
  	}
  	.rating-loading {
	  	background-color:rgba(0,0,0,0.5);
  	}
  	a, a:hover, 
	.botones_sociales.v2 a i, 
  	.app-s #download.box ul li a,
  	#comments ol.comment-list .comment .comment-body > p,
  	.box .box-title, 
  	.app-p .box h1.box-title, 
	.app-p .box h2 #reply-title,
	.bav .title,
	h1.main-box-title, 
  	#subheader.np #searchBox ul li a,
	.section.blog .bloques li a.title, 
	.section .bloque-blog a.title, 
	.single-product .product, 
	.single-product .product a, 
	.single-product .product a:hover,
	.app-s .bx-download, .bxt, 
	#breadcrumbs a:hover, 
	#main-site .error404 h1, 
	#main-site .error404 h2, 
	.app-s .da-s b, 
	.widget-title h2, 
	.dl-verified, 
	#comments ol.comment-list .trackback, 
	#comments ol.comment-list .pingback, 
	.widget a, 
	.pxtd h3 {
		color: #FFF;
	}#666666
	.px-carousel-nav .px-prev i, 
	.px-carousel-nav .px-next i {
		color: #8a8a8a;
	}
  	.entry, .section.blog .bloques li .excerpt, 
	.app-s .entry,
  	.app-s .box .box-content {
	  	color:#d4d4d4;
  	}
  	#comments ol.comment-list .comment .comment-body .reply a {
	  	color:#1bbc9b;  
  	}
  	.app-s .box, #subheader.np, 
	.section.blog, .app-p .box, 
	.single-product .product {
	  	background: #282d3a;
	  	box-shadow: 2px 2px 2px 0px #1a1c1f;
  	}
	.app-s .entry.bx-info-install {
		background: #303542;
	}
  	.botones_sociales.v2 a, #comments textarea, 
	#comments input[type=text], 
	#comments input[type=email], 
	#comments input[type=url], #comments textarea, 
	.botones_sociales a {
	  	background: #1d222d;
	  	color: #FFF;
  	}
  	.pagination .page-numbers, 
	.pagination .current, 
	.section.blog .pagination .current, 
	.section.blog .pagination .page-numbers {
	  	color:#FFF;
	  	background: #282d3a;
  	}
	.wb .title, 
	#comments ol.comment-list .comment .comment-body .comment-content p {
	  	color:#bfbfbf;
	}
  	
	#subheader.np #searchBox ul,
	.widget {
	  	background:#1d222d;
	  	box-shadow:none;
  	}
  	.section .title-section, 
	.widget .widget-title, 
	.widget_block h2, 
	.widget .widget-content ul li a,
	#slideimages .px-prev i, 
	#slideimages .px-next i, 
	.app-s .entry.bx-info-install {
	  	border-color: #282d3a;
  	}
	#breadcrumbs,
	#breadcrumbs a,
	.wb .developer, 
	.wb .app-date, 
	.wb .version {
	  	color: #6d6d6d;  
  	}
  	main .error404  {
	  	color:#FFF;
  	}
  	main .error404 h1 {
	  	text-shadow: 10px 10px 8px rgba(0,0,0,0.4);
  	}
  	.entry blockquote {
	  	border-color: #4c5160;
  	}
  	.ratingBoxMovil .box-rating.movil {
      	background: #252935;
	}
	.link-report {
		color: #bfbfbf;
	}
	.link-report:hover, .px-carousel-nav .px-prev i:hover, .px-carousel-nav .px-next i:hover {
		color: #FFF;
	}
	#box-report > div {
		color: #4c4c4c;
	}
	.wp-caption-text {
		background: rgba(0,0,0,0.1);
		color: #d8d2d2;
	}
	.app-s .entry.limit::before {
		background: -moz-linear-gradient(top,  rgba(0,0,0,0), rgba(40,45,58,1));
		background: -webkit-linear-gradient(top,  rgba(0,0,0,0),rgba(40,45,58,1));
		background: linear-gradient(to bottom,  rgba(40,45,58,0),rgba(40,45,58,1));
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\"#00ffffff\", endColorstr=\"#282d3a\",GradientType=0 );
	}
	.buttond:not(.danv):hover:not(.t):hover, .buttond.danv:hover, .buttond.t:hover, .app-s .s2 .meta-cats a:hover, .app-s .readmore:hover, .tags a:hover, #comments input[type=submit]:hover, .section a.more:hover, .sb_submit[type=submit]:hover, .widget.widget_tag_cloud a:hover, .bloque-imagen.bi_ll, #backtotop:hover, .botones_sociales.color span:hover, .botones_sociales.color a:hover, .widget .search-form input[type=submit]:hover, .widget .wp-block-search .wp-block-search__button:hover, #dl-telegram:hover, #dasl:not([disabled]):hover {
		background: #41495d;
	}
	.sdl-bar {
		background: rgba(0,0,0,0.2);
	}
	/*Woocommerce*/
	.select2-container {
		color:#000;
	}
	.woocommerce div.product .woocommerce-tabs ul.tabs::before,
	.woocommerce div.product .woocommerce-tabs ul.tabs li,
	.woocommerce div.product .woocommerce-tabs ul.tabs li.active,
	.woocommerce #reviews #comments ol.commentlist li .comment-text, 
	#add_payment_method #payment ul.payment_methods, 
	.woocommerce-cart #payment ul.payment_methods, 
	.woocommerce-checkout #payment ul.payment_methods,
	#add_payment_method .cart-collaterals .cart_totals tr td, 
	#add_payment_method .cart-collaterals .cart_totals tr th, 
	.woocommerce-cart .cart-collaterals .cart_totals tr td, 
	.woocommerce-cart .cart-collaterals .cart_totals tr th, 
	.woocommerce-checkout .cart-collaterals .cart_totals tr td, 
	.woocommerce-checkout .cart-collaterals .cart_totals tr th {
		border-color: rgba(255,255,255,0.1);
	}
	#subheader.np #searchBox ul li a:hover,
	.woocommerce div.product .woocommerce-tabs ul.tabs li,
	.woocommerce div.product .woocommerce-tabs ul.tabs li.active,
	#add_payment_method #payment, 
	.woocommerce-cart #payment, 
	.woocommerce-checkout #payment,
	fieldset {
		background: rgba(255,255,255,0.1);
	}
	.woocommerce div.product .woocommerce-tabs ul.tabs li.active, fieldset legend {
		background: #282d3a;
	}
	.woocommerce div.product .woocommerce-tabs ul.tabs li a {
		color: rgba(255,255,255,0.3);
	}
	/**/
  	@media screen and (max-width:500px){
	  	.botones_sociales li {
			border:none;
	  	}
	  	.app-s .box-data-app {
		  	background:#1d222d;		  
	  	}
	  	.app-s .da-s {
		  	border-bottom-color:#282d3a;  
	  	}
	}";

	if( is_rtl() ) {
		$css .= '
		html .gsc-control-cse,
		.app-s .box, #subheader.np, 
		.section.blog, .app-p .box,
		.bloque-blog {
			box-shadow: -2px 2px 2px 0px #1a1c1f;
		}';
	}
	
	$css = str_replace(array("\n", "\r", "\t", "  "), "", $css);
	return $css;
}

function is_dark_theme_active() {

	if( isset($_COOKIE['px_light_dark_option']) ) {

		return ($_COOKIE['px_light_dark_option'] == 1) ? true : false;

	} else {

		$color_theme = str_replace('#', '', get_option( 'appyn_color_theme' ));
		
		return ($color_theme == "oscuro") ? true : false;
	}
}

add_action( 'wp_head', 'add_color_theme', 99 );

function add_color_theme() {
	global $post;
	
	if( !is_amp_px() ) {
		if( is_dark_theme_active() ) {
			echo '<style id="css-dark-theme">'.px_css_dark_theme().'</style>';
		} else {
			echo '<style id="css-dark-theme" media="max-width:1px">'.px_css_dark_theme().'</style>';
		}
	}
	
	if( is_dark_theme_active() && is_amp_px() ) {
		echo px_css_dark_theme();
	}
	
	if( !is_amp_px() ) {
		echo '<style>';
	}
	$css = '';
	$sidebar_ubicacion = get_option( 'appyn_sidebar_ubicacion' );
	if( $sidebar_ubicacion == "izquierda" ){
		$css .= '
		#main-site .container {
			flex-direction: row-reverse;
		}
		#sidebar {
			margin-left: initial;
			margin-right: 20px;
		}
		html[dir=rtl] #sidebar {
			margin-left: 20px;
			margin-right: initial;
		}
		';
	}
	$color_theme_principal = str_replace('#', '', appyn_options( 'color_theme_principal' ));
	if( $color_theme_principal ) {
		$css .= '
		html a,
		html #header nav .menu > li.menu-item-has-children > .sub-menu::before,
		html .section .bloque-blog a.title:hover,
		html .section.blog .bloques li a.title:hover,
		html .app-s .box .entry a,
		html .app-s .box .box-content a,
		html .app-p .box .entry a,
		html .app-s .rating-average b, 
		html .app-s .da-s a,
		html .rlat .bav1 a:hover .title,
		html .ratingBoxMovil .rating-average b,
		html #comments ol.comment-list .comment .comment-body .reply a,
		html #wp-calendar td a,
		html .trackback a, 
		html .pingback a,
		html .pxtd h3 i,
		html .spinvt .snt {
			color: #'.$color_theme_principal.';
		}';
		$css .= '
		html #header nav ul li.current-menu-item a,
		html #header nav .menu > li > a::before, 
		html #header nav .menu > li.beforeactive > a::before,
		html #menu-mobile ul li a:hover,
		html body.nav_res #header nav ul.menu.active li a:hover, 
		html body.nav_res #header nav ul.menu.active li a:hover i::before,
		html .sb_submit[type=submit],
		html #subheader.np #searchBox form button,
		html #subheader .np .social li a,
		html .section .bav1 a::after,
		html .pagination .page-numbers.current,
		html .pagination a.page-numbers:hover,
		html .section.blog .pagination .page-numbers.current,
		html .section.blog .pagination a.page-numbers:hover,
		html .section.blog .bloques li .excerpt .readmore a,
		html .section a.more,
		html .buttond,
		html .app-s .s2 .meta-cats a,
		html .app-s .readmore,
		html .tags a,
		html .box .box-title::after, 
		html .box .comments-title::after,
		html #slideimages .si-prev i, 
		html #slideimages .si-next i,
		html #comments input[type=submit],
		html .widget.widget_tag_cloud a,
		html .widget .search-form input[type=submit],
		html .widget .wp-block-search .wp-block-search__button,
		html main .error404 form button,
		html .ratingBoxMovil button,
		html #slideimages .px-prev i, 
		html #slideimages .px-next i,
		html .section.blog .pagination .current,
		html .section.blog .pagination a:hover,
		html #box-report input[type=submit], 
		#main-site .error404 form button,
		html .bld_,
		html #backtotop,
		html #dasl:not([disabled]),
		html .sdl-bar div {
			background: #'.$color_theme_principal.';
		}
		html ::-webkit-scrollbar-thumb {
			background: #'.$color_theme_principal.';
		}';
		$css .= '
		html #header,
		html #header nav .menu > li.menu-item-has-children > .sub-menu::before,
		html #subheader,
		html .section .bav a,
		html #footer,
		html #button_light_dark i, 
		html .bloque-blog,
		html .spinvt .snv,
		html #px-bottom-menu,
		html #px-bottom-menu ul li a:hover,
		html #px-bottom-menu .current-menu-item a,
		html #px-bottom-menu .current_page_item a {
			border-color: #'.$color_theme_principal.';
		}
		html .loading {
			border-top-color: #'.$color_theme_principal.';
		} 
		html .spinvt .snv {
			border-right-color: transparent;
		}
		html .loading, html .g-recaptcha::before {
			border-top-color: #'.$color_theme_principal.';
		}
		';
	}

	$css .= '
	html .downloadAPK {
		background-color: '.appyn_options( 'color_download_button', '#1bbc9b' ).';
	}
	.bloque-status.bs-new {
		background-color: '.appyn_options( 'color_new_ribbon', '#1bbc9b' ).';
	}
	.bloque-status.bs-update {
		background-color: '.appyn_options( 'color_update_ribbon', '#19b934' ).';
	}
	.rating .stars {
		background-color: '.appyn_options( 'color_stars', '#f9bd00' ).';
	}
	.b-type {
		background-color: '.appyn_options( 'color_tag_mod', '#20a400' ).';
	}
	';

	$css = str_replace(array("\n", "\r", "\t", "  "), "", $css);
	echo $css;
	if( !is_amp_px() ) {
		echo '</style>';
	}
}

add_action( 'wp_enqueue_scripts', 'theme_scripts' );

function theme_scripts() {
	if( !wp_is_mobile() ) {
		wp_enqueue_style( 'style', get_stylesheet_directory_uri().'/style.min.css', false, VERSIONPX, 'all' ); 
	}

	if( appyn_options( 'infinite_scroll' ) )
		wp_enqueue_script( 'px-infinite-scroll', get_stylesheet_directory_uri().'/assets/js/infinite-scroll.pkgd.min.js', false, VERSIONPX, true ); 

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'px-js', get_stylesheet_directory_uri().'/assets/js/js.min.js', array('jquery'), VERSIONPX, true );
	wp_enqueue_script('wsm-admin-script', get_stylesheet_directory_uri() . 'assets/js/admin.js', array('jquery'), VERSIONPX, true);

	$readmore_single = stripslashes(get_option( 'appyn_readmore_single' ));
	$o = '';
	if( $readmore_single == 1 ) {
		$o .= 'var text_ = false;';
	} else {
		$o .= 'var text_ = true;';
	}
	$o .= '	
	var ajaxurl = "' . admin_url('admin-ajax.php') . '";
	var text_votar = "'.__( 'Votar', 'appyn' ).'";
	var text_votos = "'.__( 'Votos', 'appyn' ).'";
	var text_leer_mas = "'.__( 'Leer más', 'appyn' ).'";
	var text_leer_menos = "'.__( 'Leer menos', 'appyn' ).'";
	var text_de = "'.__( 'de', 'appyn' ).'";
	var text_reporte_gracias = "'.__( 'Gracias por enviarnos su reporte.', 'appyn' ).'";';
	
	$footer_codigos = stripslashes(get_option( 'appyn_footer_codigos' ));

	echo $footer_codigos;

	$recaptcha_site = get_option( 'appyn_recaptcha_site' );
	$recaptcha_secret = get_option( 'appyn_recaptcha_secret' );	
	if( $recaptcha_site && $recaptcha_secret ) {
		$o .= 'var recaptcha_site = "'.$recaptcha_site.'"';
	}

	wp_add_inline_script( 'px-js', $o, 'before' );
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		add_action('wp_footer', function(){
			echo '<script>window.addComment=function(s){var u,f,v,y=s.document,p={commentReplyClass:"comment-reply-link",cancelReplyId:"cancel-comment-reply-link",commentFormId:"commentform",temporaryFormId:"wp-temp-form-div",parentIdFieldId:"comment_parent",postIdFieldId:"comment_post_ID"},e=s.MutationObserver||s.WebKitMutationObserver||s.MozMutationObserver,i="querySelector"in y&&"addEventListener"in s,n=!!y.documentElement.dataset;function t(){r(),function(){if(!e)return;new e(d).observe(y.body,{childList:!0,subtree:!0})}()}function r(e){if(i&&(u=I(p.cancelReplyId),f=I(p.commentFormId),u)){u.addEventListener("touchstart",a,{passive: true}),u.addEventListener("click",a);var t=function(e){if((e.metaKey||e.ctrlKey)&&13===e.keyCode)return f.removeEventListener("keydown",t),e.preventDefault(),f.submit.click(),!1};f&&f.addEventListener("keydown",t);for(var n,r=function(e){var t,n=p.commentReplyClass;e&&e.childNodes||(e=y);t=y.getElementsByClassName?e.getElementsByClassName(n):e.querySelectorAll("."+n);return t}(e),d=0,o=r.length;d<o;d++)(n=r[d]).addEventListener("touchstart",l,{passive: true}),n.addEventListener("click",l)}}function a(e){var t=I(p.temporaryFormId);t&&v&&(I(p.parentIdFieldId).value="0",t.parentNode.replaceChild(v,t),this.style.display="none",e.preventDefault())}function l(e){var t=this,n=m(t,"belowelement"),r=m(t,"commentid"),d=m(t,"respondelement"),o=m(t,"postid");n&&r&&d&&o&&!1===s.addComment.moveForm(n,r,d,o)&&e.preventDefault()}function d(e){for(var t=e.length;t--;)if(e[t].addedNodes.length)return void r()}function m(e,t){return n?e.dataset[t]:e.getAttribute("data-"+t)}function I(e){return y.getElementById(e)}return i&&"loading"!==y.readyState?t():i&&s.addEventListener("DOMContentLoaded",t,!1),{init:r,moveForm:function(e,t,n,r){var d=I(e);v=I(n);var o,i,a,l=I(p.parentIdFieldId),m=I(p.postIdFieldId);if(d&&v&&l){!function(e){var t=p.temporaryFormId,n=I(t);if(n)return;(n=y.createElement("div")).id=t,n.style.display="none",e.parentNode.insertBefore(n,e)}(v),r&&m&&(m.value=r),l.value=t,u.style.display="",d.parentNode.insertBefore(v,d.nextSibling),u.onclick=function(){return!1};try{for(var c=0;c<f.elements.length;c++)if(o=f.elements[c],i=!1,"getComputedStyle"in s?a=s.getComputedStyle(o):y.documentElement.currentStyle&&(a=o.currentStyle),(o.offsetWidth<=0&&o.offsetHeight<=0||"hidden"===a.visibility)&&(i=!0),"hidden"!==o.type&&!o.disabled&&!i){o.focus();break}}catch(e){}return!1}}}}(window);</script>';
		});
	} 
}

function paginador( $query = false, $num = false, $args = null ) {
	
	if( !empty($query) ) {
		$wp_query = $query;
		$numposts = $query->found_posts;
		$max_page = $query->max_num_pages;
		$posts_per_page = intval($num);
	} else {
		global $wp_query;
		$max_page = $wp_query->max_num_pages;
	}
	
	if( appyn_options( 'infinite_scroll' ) == 1 && $wp_query->max_num_pages > 1 && ! is_amp_px() ) {
		echo '
		<div class="scroller-status">
			<div class="infinite-scroll-request loader-ellips">
				<div class="loading"></div>
			</div>
		</div>';
	}

	$big = 999999999;

	$pages = paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $max_page,
        'type'  => 'array',
	) );
	if( is_array( $pages ) ) {
        $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
        echo '<div class="section"><div class="pagination-wrap"><ul class="pagination">';
        foreach ( $pages as $page ) {
			echo "<li>".str_replace('page-numbers dots', 'dots', $page)."</li>";
        }
       echo '</ul></div></div>';
    }
}

add_action( 'pre_get_posts', 'function_pregetposts' );

function function_pregetposts( $query ) {
  	if ( !is_admin() && $query->is_main_query() ) {
		if( $query->is_post_type_archive('blog') ) {
			$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
			$blog_posts_limite = get_option( 'appyn_blog_posts_limite' );
			$blog_posts_limite = (empty($blog_posts_limite)) ? '10' : $blog_posts_limite;
			$query->set('post_type', 'blog');
			$query->set('posts_per_page', $blog_posts_limite);
			$query->set('paged', $paged);
		}
		if( $query->is_search() ) {
			$query->set('post_type', array('post', 'blog'));
			if( get_option( 'appyn_versiones_mostrar_buscador') == 1 ) {
				$query->set('post_parent', 0);
			} 
		}
		if( $query->is_tax('dev') ) {
			if( get_option( 'appyn_versiones_mostrar_tax_Developer') == 1 ) {
				$query->set('post_parent', 0);
			} 
		}
		if( $query->is_tax('cblog') ) {
			$query->set('post_type', 'blog');
		}
		if( $query->is_tax('tblog') ) {
			$query->set('post_type', 'blog');
		}
		if( $query->is_category() ) {
			if( get_option( 'appyn_versiones_mostrar_categorias') == 1 ) {
				$query->set('post_parent', 0);
			} 
		}
		if( $query->is_tag() ) {
			if( get_option( 'appyn_versiones_mostrar_tags') == 1 ) {
				$query->set('post_parent', 0);
			} 
		}
		if( $query->is_home() && 'posts' === get_option( 'show_on_front' ) ) {
			$home_limite = get_option( 'appyn_home_limite' );
			$home_limite = ( empty( $home_limite ) ) ? '12' : $home_limite;
			$query->set('posts_per_page', $home_limite);
			$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

			$query->set('paged', $paged);
			$home_posts_orden = get_option( 'appyn_home_posts_orden' );
			$home_posts_versiones = get_option( 'appyn_versiones_mostrar_inicio', 0 );
			if( $home_posts_orden == 'modified' ){
				$query->set('orderby', 'modified');
			}
			elseif( $home_posts_orden == 'rand' ){
				$query->set('orderby', 'rand');
			}		
			if( $home_posts_versiones == 1 ){
				$query->set('post_parent', 0);
			}
		}
		if( $query->is_home() && 'page' === get_option( 'show_on_front' ) ) {
			$query->set('post_type', 'page');
			$query->set('page_id', get_option( 'page_on_front' ));
		}
	}
	if(is_admin()){
		$query->set('orderby', '');
		$query->set('order', '');    
	}
}

add_action( 'wp_head', 'download_opts' );

function download_opts() {
	if( !is_singular('post') ) return;

	global $post;
	$adl = get_option( 'appyn_download_links' );
	$redirect_timer = get_option( 'appyn_redirect_timer' );
	$get_download = get_query_var( 'download', null );
	$datos_download = get_datos_download();

	if( !isset($datos_download['option']) ) return;

	if( $adl == 1 || $adl == 2 || $adl == 3 ) {
		$error_script = "<script>function alert_download() { alert('".__( 'No hay archivo para descargar.', 'appyn' )."'); }</script>";

		$option = $datos_download['option'];
		$get_opt = get_query_var( 'opt' );

		if( $option == "direct-link" && $get_download ) {

			if( ($adl == 2 && $get_opt == 1) || (($adl == 1 || $adl == 3) && !$get_opt) ) {
				if( isset( $datos_download['direct-link'] ) ) {
					echo '<script>
					setTimeout( function() {
						window.location.href = "'. $datos_download['direct-link'] .'";
					}, '.($redirect_timer*1000).');
					</script>';
				}
			}
		} else {
			echo $error_script;	
		}
	}
}

add_action( 'template_redirect', 'download_file' );

function download_file(){
	if( !is_single() ) return;
		global $post;
		$get_download = get_query_var( 'download', null );

	if( $get_download == "file") {
		$post_id = $post->ID;
		$datos_download = get_post_meta($post_id, 'datos_download', true);
		$url = $datos_download['direct-download'];
		header("Location: $url");
		exit();
	}
}

add_action( 'registered_post_type', 'igy2411_make_posts_hierarchical', 10, 2 );

function igy2411_make_posts_hierarchical( $post_type, $pto ){
    if ($post_type != 'post') return;
    global $wp_post_types;
    $wp_post_types['post']->hierarchical = 1;
    add_post_type_support( 'post', 'page-attributes' );
}

add_filter( 'post_thumbnail_html', 'modify_post_thumbnail_html', 99, 5 );

function modify_post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
	$appyn_lazy_loading = ( get_option('appyn_lazy_loading') ) ? get_option('appyn_lazy_loading') : NULL;
	if( $appyn_lazy_loading == 1 ) {
		$id = get_post_thumbnail_id();
		$src = wp_get_attachment_image_src($id, $size);
		$alt = get_the_title($id);
		$class = '';
		if( !empty($attr['class']) ) {
			$class = $attr['class'];
		}
		$image_blank = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAI4AAACNAQMAAABbp9DlAAAAA1BMVEX///+nxBvIAAAAGUlEQVRIx+3BMQEAAADCIPunNsU+YAAA0DsKdwABBBTMnAAAAABJRU5ErkJggg==";
		$color_theme = str_replace('#', '', get_option( 'appyn_color_theme' ));
		if($color_theme == "oscuro") {
			$image_blank = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAI4AAACNAQMAAABbp9DlAAAAA1BMVEUUHCkYkPNHAAAAGUlEQVRIx+3BMQEAAADCIPunNsU+YAAA0DsKdwABBBTMnAAAAABJRU5ErkJggg==";
		}
		$html = '<img src="'.$image_blank.'" data-src="' . $src[0] . '" alt="' . $alt . '" class="' . $class . ' lazyload">';
	}	
	return $html;
}

add_filter( 'upload_mimes', 'allow_custom_mimes' );

function allow_custom_mimes( $existing_mimes=array() ) {
	$existing_mimes['apk'] = '<code>application/vnd.android.package-archive</code>';
	return $existing_mimes;
}

add_rewrite_endpoint( PX_AMP_QUERY_VAR, EP_PERMALINK );

add_filter( 'template_include', 'amp_page_template', 99 );

function amp_page_template( $template ) {
	$section = get_query_var( 'section' );

    if( is_amp_px() ) {
		if( $section == 'versions' ) {
			$template = get_template_directory() .  '/amp/single-versions.php';
		} else {
			if ( is_home() ) {
				if ( 'page' === get_option( 'show_on_front' ) )
					$template = get_template_directory() .  '/amp/page.php';
				else
					$template = get_template_directory() .  '/amp/index.php';
			} 
			elseif ( is_singular('post') ) {
				$template = get_template_directory() .  '/amp/single.php';
			} 
		}
	} else {
		if( $section == 'versions' ) {
			$template = get_template_directory() .  '/single-versions.php';
		}
	}
    return $template;
}

add_action( 'get_header', function($name){
	return "header-amp";
} );

add_action('wp_head', function(){
	global $wp_query;
	if( is_404() || !appyn_options( 'amp' ) ) return;

	if( is_home() || is_single() || is_archive() ) {
		if( is_home() ) { 
			echo '<link rel="amphtml" href="'.get_bloginfo('url').'/?amp">';
		} elseif( is_single() ) {
			global $post;
			echo '<link rel="amphtml" href="'.add_query_arg('amp', '', get_permalink()).'">';
		} elseif( is_archive() ) {
			$obj = get_queried_object();
			if( isset($obj->rewrite['slug']) ) {
				$l = get_post_type_archive_link($obj->rewrite['slug']);
				echo '<link rel="amphtml" href="'.($l).'?amp">';
			}
		} else {
			$obj = get_queried_object();
			if( !empty($obj->term_id) ) echo '<link rel="amphtml" href="'.get_term_link($obj->term_id).'?amp">';
		}
	}
});
 
add_filter( 'post_link', 'px_filter_post_link_amp', 10, 2 );
add_filter( 'post_type_link', 'px_filter_post_link_amp', 10, 2 );

function px_filter_post_link_amp( $link, $post ){
	if( is_amp_px() ) {
    	return $link.'?amp';
	}
	return $link;
}

add_filter( 'post_thumbnail_html', 'modify_post_thumbnail_amp', 99, 5 );

function modify_post_thumbnail_amp( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
	if( is_amp_px() ) { 
		$id = get_post_thumbnail_id();
		$src = wp_get_attachment_image_src($id, $size);
		$alt = get_the_title($id);
		$class = '';
		if( !empty($attr['class']) ) {
			$class = $attr['class'];
		}

		$html = '<amp-img src="'.$src[0].'" width="128" height="128" alt="' . $alt . '" class="' . $class . '" layout="responsive"></amp-img>';
	}
	return $html;
}

add_filter( 'comment_reply_link', function($args_before_link_args_after){
	if( is_amp_px() ) {
		return false;
	} else {
		return $args_before_link_args_after;
	}
} );

add_filter( 'get_avatar_url', 'wpua_get_avatar_url', 50, 3 );

function wpua_get_avatar_url( $url, $id_or_email, $args ){
	if( class_exists('WP_User_Avatar_Functions') && $id_or_email != 'unknown@gravatar.com' ) {
		global $wpua_functions;
		$url = $wpua_functions->get_wp_user_avatar_src( $id_or_email, $args['size'] );	
	}
	return $url;
}

add_filter( 'locale_stylesheet_uri', function ($localized_stylesheet_uri) {
	if( strpos($localized_stylesheet_uri, 'rtl.css') !== false ) {
    	return add_query_arg( array('ver' => VERSIONPX), $localized_stylesheet_uri );
	}
});

add_action( 'wp_head', function(){
	echo '<style>';
	include __DIR__."/../../../wp-includes/css/dist/block-library/style.min.css";
	echo '</style>';
});

add_action( 'wp_enqueue_scripts', 'dequeue_gutenberg_theme_css', 100);

function dequeue_gutenberg_theme_css() {
    wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'classic-theme-styles' );
}

add_filter('request', 'px_change_term_request', 1, 1 );
 
function px_change_term_request( $query ){
 
	foreach( array( 'cblog', 'tblog' ) as $tax_name ) {
 
		if( isset($query['attachment']) ) :
			$include_children = true;
			$name = $query['attachment'];
		else:
			$include_children = false;
			$name = isset($query['name']) ? $query['name'] : '';
		endif;
	
		$term = get_term_by('slug', $name, $tax_name);
	
		if (isset($name) && $term && !is_wp_error($term)):
	
			if( $include_children ) {
				unset($query['attachment']);
				$parent = $term->parent;
				while( $parent ) {
					$parent_term = get_term( $parent, $tax_name);
					$name = $parent_term->slug . '/' . $name;
					$parent = $parent_term->parent;
				}
			} else {
				unset($query['name']);
			}
	
			switch( $tax_name ):
				case 'category':{
					$query['category_name'] = $name;
					break;
				}
				case 'post_tag':{
					$query['tag'] = $name;
					break;
				}
				default:{
					$query[$tax_name] = $name;
					break;
				}
			endswitch;
	
		endif;
	}
 
	return $query;
 
}

add_filter( 'term_link', 'px_term_permalink', 10, 3 );
 
function px_term_permalink( $url, $term, $taxonomy ){
 
	if( get_option( 'permalink_structure' ) != '/%postname%/' ) return $url;

	$taxonomy_name = 'cblog';
	$taxonomy_slug = 'cblog';
 
	if ( strpos($url, $taxonomy_slug) === FALSE || $taxonomy != $taxonomy_name ) return $url;
 
	$url = str_replace('/' . $taxonomy_slug, '', $url);
 
	return $url;
}
 
add_filter( 'term_link', 'px_term_permalink_tag_blog', 10, 3 );
 
function px_term_permalink_tag_blog( $url, $term, $taxonomy ){
 
	if( get_option( 'permalink_structure' ) != '/%postname%/' ) return $url;

	$taxonomy_name = 'tblog';
	$taxonomy_slug = 'tblog';
 
	if ( strpos($url, $taxonomy_slug) === FALSE || $taxonomy != $taxonomy_name ) return $url;
 
	$url = str_replace('/' . $taxonomy_slug, '', $url);
 
	return $url;
}

add_filter( 'manage_post_posts_columns', 'set_custom_edit_version_columns' );

function set_custom_edit_version_columns( $columns ) {
	
	$new = array();
	foreach($columns as $key => $title) {
		if( $key == 'title' ) {
			$new['icon'] = '';
		}
		if( $key == 'author' ) {
			$new['version'] = __( 'Versión', 'appyn' );
		}
		$new[$key] = $title;
	}
    return $new;
}

add_action( 'manage_post_posts_custom_column' , 'custom_version_column', 10, 2 );

function custom_version_column( $column, $post_id ) {
	global $wpdb;
    switch ( $column ) {
		case 'icon' :
			$icon = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
			$icon = ( $icon ) ? $icon : 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKoAAACqBAMAAADPWMmxAAAAElBMVEXu7u7////09PT7+/v39/fx8fFOJAxSAAABPklEQVRo3u3YQW6DMBCFYYLjAzxM9iRK9tA2e2h6ACP1/mcpFDlQNVTQGdpEvO8Av62RDYKIiIiIiIiIiIj+THyY4TS1usEMjlVW/7+aTLourK60ai+v+lVbAGf1ao2G164WaOTKVYtWolyN0UqVq1u03F3u9enmXHeyqkW2wBkw32Zh2pSXVffhxPcU7lYRZtizx1Mkq8ZoeO1nlkHjrF2t0HBeuYpPedS5eJXq9ss732KnUq3RycOQM41qgY4LayQKVYsgD2uU8qpBkIaz6+TVGldlWCMXVwsMN1uh5bywGmMgs+jshFWDgcRc+7LqHkNVPwxRtcBtpaS6xQgnqW4w5iyoVhjj/BJVJItUkS1STRepIl+k6n5bfX/7SfYQ3zArqaaHCY738deB1XVXzfMMLxERERERERERET24D8nRkAcrLOazAAAAAElFTkSuQmCC';
			echo '<img src="'.$icon.'" style="width:50px;">';
            break;

		case 'version' :
			$datos_informacion = get_post_meta( $post_id, 'datos_informacion', true );
			echo ( isset($datos_informacion['version']) ) ? $datos_informacion['version'] : '--';
			break;
    }
}

add_filter( 'body_class', function( $classes ) {
	$add_class = array();

	if( is_dark_theme_active() ) {
		$add_class[] = 'theme-dark';
	}
    if( appyn_options( 'sidebar_active' ) == 1 ) {
		$add_class[] = 'no-sidebar';
	}
	if( appyn_options( 'design_rounded' ) ) {
		$add_class[] = 'rounded';
	}
	if( appyn_options( 'og_sidebar' ) || (is_single() || is_page() || is_post_type_archive('blog') || is_tax('cblog')) && appyn_options( 'sidebar_active' ) == 0 ) {
		$add_class[] = 'sidg';
	}
	global $post;
	if( appyn_gpm( $post->ID, 'appyn_hidden_sidebar' ) ) {
		$add_class = array_diff($add_class, array('sidg'));
	}

	$aprmv = appyn_options( 'apps_per_row_movil', 2 );
	$add_class[] = 'aprm-'.$aprmv;

	if( appyn_options( 'width_page' ) ) {
		$add_class[] = 'full-width';
	}
	if( appyn_options( 'view_apps' ) == 1 ) {
		$add_class[] = 'vah';
	} else {
		$add_class[] = 'vav';
	}

	if( count($add_class) > 0 )
		return array_merge( $classes, $add_class );	
	else
		return $classes;
} );

if( !is_dir( WP_TEMP_DIR ) ) {
	mkdir( WP_TEMP_DIR, 0755, true );
}

function disabled_lazyload() {
	if( is_amp_px() )
		return false;
}

add_filter( 'wp_lazy_loading_enabled', 'disabled_lazyload' );

function wp_editor_fix( $content, $editor_id, $settings = array() ){      
    ob_start();
    wp_editor($content, $editor_id, $settings);
    $out = ob_get_contents();
    $js = json_encode($out);
    $id_editor_ctn  = $editor_id.'-ctn';
    ob_clean(); ?>
    <div id="<?php echo $id_editor_ctn; ?>"></div>
    <script>
    setTimeout(function() {
		var id_ctn = '#<?php echo $id_editor_ctn; ?>';
		jQuery(id_ctn).append(<?php echo $js; ?>); 
		setTimeout(function() {
			jQuery('#<?php echo $editor_id; ?>-tmce').trigger('click');
			
		}, 500);
    }, 3000);
    </script>
    <?php
    $out = ob_get_contents();
    ob_end_clean();
    echo $out;
}

add_action( 'rank_math/vars/register_extra_replacements', function(){
	rank_math_register_var_replacement(
	   'px_rms_get_version',
	   [
	   'name'        => __( 'Versión', 'appyn' ),
	   'variable'    => 'px_rms_get_version',
	   'description' => '',
	   'example'     => px_rms_callback(),
	   ],
	   'px_rms_callback'
   );
});

function single_info_title( $version ) {
	$title = get_the_title();
	$version = $version ?? '';
	$filter_rvt = apply_filters( 'px_remove_version_title', '__return_true' );

	if( $filter_rvt ) { 
		$output = '<h1 class="main-box-title">'. str_replace( $version, '', $title ) . '</h1>';

		$output .= ( ! empty ( $version ) ? '<div class="version">'.$version.'</div>' : '' );
	} else {
		$output = '<h1 class="main-box-title">'. $title . '</h1>';
	}

	return $output;

}

add_filter('wp_generate_tag_cloud', 'na_tag_cloud',10,1);

function na_tag_cloud($string){
   return preg_replace("/style=\"font-size:.+pt;\"/", '', $string);
}

add_action( 'template_redirect', function(){
	ob_start( function( $buffer ){
		$buffer = str_replace( array( ' type="text/css"', " type='text/css'" ), '', $buffer );
		$buffer = str_replace( array( ' type="text/javascript"', " type='text/javascript'" ), '', $buffer );        
		return $buffer;
	});
});

class PX_Menu_AMP extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = NULL, $id = 0) {
        global $wpdb, $wp_query;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $class_names = $value = '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $class_names = ' class="' . esc_attr( $class_names ) . '"';

		$has_children = $wpdb->get_var(
			$wpdb->prepare("
			   SELECT COUNT(*) FROM $wpdb->postmeta
			   WHERE meta_key = %s
			   AND meta_value = %d
			   ", '_menu_item_menu_item_parent', $item->ID)
		);

        $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

        $attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) .'"' : '';
        $attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) .'"' : '';
        $attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) .'"' : '';
        $item_output = $args->before;
		if( $has_children )
        	$item_output .= '<span amp-nested-submenu-open>';
		else
        	$item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		if( $has_children )
			$item_output .= '<i class="fa fa-chevron-right"></i></span>';
		else 
			$item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<div amp-nested-submenu><ul>
		<li>
		  <span amp-nested-submenu-close><i class=\"fa fa-chevron-left\"></i> ".__( 'Back', 'appyn' )."</span>
		</li>\n";
    }
}

add_filter( 'widget_update_callback', 'px_save_custom_widget_option', 10, 3 );

function px_save_custom_widget_option( $instance, $new_instance, $old_instance ) {
    $instance['fixed_widget'] = ! empty( $new_instance['fixed_widget'] ) ? 1 : 0;
    $instance['mod_apps_widget'] = ! empty( $new_instance['mod_apps_widget'] ) ? 1 : 0;
    return $instance;
}

add_filter( 'dynamic_sidebar_params', 'add_custom_class_to_widgets' );

function add_custom_class_to_widgets( $params ) {
    global $wp_registered_widgets;
    $widget_id = $params[0]['widget_id'];
    $widget_obj = $wp_registered_widgets[$widget_id];
    $widget_opt = get_option($widget_obj['callback'][0]->option_name);
    $widget_number = $widget_obj['params'][0]['number'];
    $widget_custom_option = isset($widget_opt[$widget_number]['fixed_widget']) ? $widget_opt[$widget_number]['fixed_widget'] : '';
    if ( $widget_custom_option ) {
        $params[0]['before_widget'] = str_replace( 'class="', 'class="widget_fixed_widget wfm ', $params[0]['before_widget'] );
    }
    return $params;
}

add_filter( 'locale', 'tpxm_change_default_lang' );

function tpxm_change_default_lang( $locale ) {
	if( strpos($locale, 'es') === false ) {
		return 'en_US';
	}
	return $locale;
}

add_filter( 'views_edit-post', 'px_add_mod_apps_filter_menu' );

function px_add_mod_apps_filter_menu($views) {

    if( ( is_admin() ) && ( $_GET['post_type'] == 'post' ) ) {

		$nquery = array(
			'post_type'   => 'post',
			'post_status' => 'any',
			'meta_key' => 'app_type',
			'meta_value' => 1,
		);

		$result = new WP_Query($nquery);

		$type = ( isset($_GET['type']) ) ? $_GET['type'] : '';
		$class = ($type == 'modapps') ? ' class="current"' : '';
		$mod_apps = sprintf('<a href="%s"'. $class .'>'.__( 'Apps modificadas', 'appyn' ).' <span class="count">(%d)</span></a>', admin_url('edit.php?post_type=post&type=modapps'), $result->found_posts);

		array_splice( $views, array_search('mine', array_keys($views)) + 1, 0, $mod_apps );

		$results = get_option( 'posts_download_links_status_404', array() );

		$type = ( isset($_GET['type']) ) ? $_GET['type'] : '';
		$class = ($type == 'check_download_links') ? ' class="current"' : '';
		$dlb = sprintf('<a href="%s"'. $class .' style="font-style:italic; color:#FF0000;">'.__( 'Enlaces de descarga rotos', 'appyn' ).' <span class="count">(%d)</span></a>', admin_url('edit.php?post_type=post&type=check_download_links'), count($results));

		$views[] = $dlb;

		return $views;

     }
}

add_filter( 'pre_get_posts', 'px_pgp_add_filter_menu' );

function px_pgp_add_filter_menu($query) {
	global $pagenow;

	if( 'edit.php' != $pagenow || !$query->is_admin )
	    return $query;

	$py = ( isset($_GET['post_type']) ) ? $_GET['post_type'] : '';
	$ty = ( isset($_GET['type']) ) ? $_GET['type'] : '';
	if( ( $py == 'post' && $ty == 'modapps' ) ) {
		$query->set('meta_key', 'app_type' );
		$query->set('meta_value', 1 );
	}
	if( ( $py == 'post' && $ty == 'check_download_links' ) ) {
		$query->set('post__in', get_option( 'posts_download_links_status_404', array('') ) );
		$query->set('post_type', 'publish' );
	}
	return $query;
}


function flatsome_child_remove_default_styles() {
    // Deregister Flatsome's default styles
    wp_dequeue_style('flatsome-main');
    wp_deregister_style('flatsome-main');

    // Deregister other Flatsome styles if necessary
    wp_dequeue_style('flatsome-theme-style');
    wp_deregister_style('flatsome-theme-style');
}
add_action('wp_enqueue_scripts', 'flatsome_child_remove_default_styles', 20);
function remove_flatsome_css() {
    // Lấy danh sách tất cả các styles được enqueue
    global $wp_styles;
    
    // Kiểm tra xem styles nào đến từ theme Flatsome và xóa chúng
    foreach( $wp_styles->queue as $handle ) {
        if ( strpos( $wp_styles->registered[$handle]->src, 'flatsome' ) !== false ) {
            wp_dequeue_style( $handle );
            wp_deregister_style( $handle );
        }
    }
}
add_action('wp_enqueue_scripts', 'remove_flatsome_css', 20);




function register_version_variable_for_yoast() {
    if ( class_exists( 'WPSEO_Frontend' ) ) {
        wpseo_register_var_replacement( '%%Version%%', 'get_custom_version_meta', 'advanced', 'Version from custom field' );
    }
}
add_action( 'wpseo_register_extra_replacements', 'register_version_variable_for_yoast' );

// Hàm để lấy giá trị version từ custom field
function get_custom_version_meta( $replacement, $args ) {
    global $post;

    // Lấy giá trị từ custom field 'datos_informacion'
    $datos_informacion = get_post_meta( $post->ID, 'datos_informacion', true );

    // Kiểm tra xem 'version' có tồn tại trong custom field không
    if ( is_array( $datos_informacion ) && isset( $datos_informacion['version'] ) ) {
        return "- Version ".esc_html( $datos_informacion['version'] );
    }

    return '';
}
function force_update_yoast_meta_preview() {
    if ( get_the_ID() ) { // Kiểm tra nếu có giá trị post ID
        $datos_informacion = get_post_meta( get_the_ID(), 'datos_informacion', true );

        // Kiểm tra nếu datos_informacion và version tồn tại
        if ( is_array( $datos_informacion ) && isset( $datos_informacion['version'] ) ) {
            ?>
            <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', function() {
                    // Lấy giá trị thực của version từ PHP
                    var version = '<?php echo "- Version " . esc_js( $datos_informacion['version'] ); ?>';
                    if (!version) {
                        version = '- Version 1.0'; // Giá trị mặc định nếu không có version
                    }

                    // Hàm thay thế %%Version%% trong thẻ #wpseo_meta
                    function updateYoastMetaPreview() {
                        var wpseoMeta = document.querySelector('.hFjSGk'); // Tìm thẻ với id là wpseo_meta
                        
                        if (wpseoMeta) {
                            var metaText = wpseoMeta.innerHTML; // Lấy nội dung của thẻ

                            // Kiểm tra và thay thế %%Version%%
                            if (metaText.includes('%%Version%%')) {
                                var newMetaText = metaText.replace(/%%Version%%/g, version);
                                wpseoMeta.innerHTML = newMetaText; // Cập nhật lại nội dung thẻ
                            }
                        }

						var wpseoMeta2 = document.querySelector('.iEkGYV'); // Tìm thẻ với id là wpseo_meta
                        
                        if (wpseoMeta2) {
                            var metaText = wpseoMeta2.innerHTML; // Lấy nội dung của thẻ

                            // Kiểm tra và thay thế %%Version%%
                            if (metaText.includes('%%Version%%')) {
                                var newMetaText = metaText.replace(/%%Version%%/g, version);
                                wpseoMeta2.innerHTML = newMetaText; // Cập nhật lại nội dung thẻ
                            }
                        }
                    }

                    // Sử dụng MutationObserver để theo dõi sự thay đổi của DOM
                    var targetNode = document.querySelector('body'); // Theo dõi toàn bộ body
                    if (targetNode) {
                        var observer = new MutationObserver(function(mutationsList, observer) {
                            updateYoastMetaPreview(); // Gọi hàm update mỗi khi DOM thay đổi
                        });

                        // Theo dõi các thay đổi trong cây DOM
                        observer.observe(targetNode, {
                            childList: true,
                            subtree: true
                        });

                        // Gọi hàm lần đầu để cập nhật ngay lập tức
                        updateYoastMetaPreview();
                    }
                });
            </script>
            <?php
        }
    }
}
add_action('admin_footer', 'force_update_yoast_meta_preview');


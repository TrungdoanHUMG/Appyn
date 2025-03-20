<?php

if( ! defined( 'ABSPATH' ) ) die ( '✋' );

include_once __DIR__.'/../admin/panel.php';

add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );

function load_custom_wp_admin_style() {
	wp_enqueue_style( 'style-admin', get_stylesheet_directory_uri().'/admin/assets/css/style.css', false, VERSIONPX, 'all' ); 

	wp_enqueue_style( 'style-font-awesome', get_stylesheet_directory_uri().'/assets/css/font-awesome-6.4.2.min.css', false, VERSIONPX, 'all' ); 

	wp_enqueue_style( 'wp-color-picker' );

	wp_enqueue_style( 'thickbox' );
}

add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_scripts' );

function load_custom_wp_admin_scripts() {
	wp_enqueue_script( 'jquery-ui-sortable' );

	wp_enqueue_script( 'media-upload' );

	wp_enqueue_script( 'thickbox' );

	wp_enqueue_script( 'my-upload' );

	wp_register_script( 'custom-upload', get_stylesheet_directory_uri().'/admin/assets/js/upload.js',array('jquery','media-upload','thickbox') );

	wp_enqueue_script( 'custom-upload' );

	wp_enqueue_script( 'colorpicker-custom', get_stylesheet_directory_uri().'/admin/assets/js/colorpicker.js', array( 'wp-color-picker' ), false, true );
	
	wp_enqueue_script( 'admin-js', get_stylesheet_directory_uri().'/admin/assets/js/js.js', false, VERSIONPX, true ); 
    wp_localize_script( 'admin-js', 'ajax_var', array(
        'url'    => admin_url( 'admin-ajax.php' ),
        'nonce'  => wp_create_nonce( 'admin_panel_nonce' ),
        'action' => 'px_panel_admin',
		'error_text' => __( 'Ocurrió un error', 'appyn' ),
		
    ) );
	global $post;
	$pp = isset($post->post_parent) ? $post->post_parent : null;
	$am = '';
	if( $pp != 0 ) {
		$am = "\n".__( 'Importante: Este post es una version antigua', 'appyn' );
	}
	wp_localize_script( 'admin-js', 'vars', array(
		'_img' => __( 'Imagen', 'appyn' ),
		'_title' => __( 'Título', 'appyn' ),
		'_version' => __( 'Version', 'appyn' ),
		'_import_text' => __( 'import app', 'appyn' ),
		'_confirm_update_text' => __( '¿Quiere actualizar la información de esta aplicación? Recuerda que reemplazará toda la información.', 'appyn' ).$am,
    ) );
    wp_localize_script( 'admin-js', 'importgp_nonce', array(
        'nonce'  => wp_create_nonce( 'importgp_nonce' )
    ) );
	wp_localize_script( 'admin-js', 'md', array(
        'px_limit_filesize' => MAX_DOWNLOAD_FILESIZE,
    ) );
}



add_filter( 'px_process_convert_post_old_version', 'px_process_convert_post_old_version_callback', 10, 2 );

function px_process_convert_post_old_version_callback( $post_id, $return = '' ) {
	global $wpdb;

	$post = get_post( $post_id );
 
	$current_user = wp_get_current_user();
	$new_post_author = $current_user->ID;
 
	if (isset( $post ) && $post != null) {
 
		$info = get_post_meta( $post->ID, 'datos_informacion', true );
		$cb = get_post_meta( $post->ID, 'custom_boxes', true );
		$post_title = $post->post_title;
		if( !empty( $info['version'] ) ) {
			$post_title .= ' '.$info['version'];
		}
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'publish',
			'post_title'     => $post_title,
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order,
			'post_parent'	 => $post_id,
		);

		$new_post_id = wp_insert_post( $args );
		
		$p_name = wp_unique_post_slug( sanitize_title( $post_title ), $new_post_id, 'publish', 'post', $post->post_parent );

		wp_update_post( array(
			'ID' => $new_post_id,
			'post_date' => $post->post_date,
			'post_date_gmt' => $post->post_date_gmt,
			'post_name' => $p_name,
		));
		wp_update_post( array(
			'ID' => $post->ID,
			'post_date' => the_date('', '', '', FALSE),
			'post_date_gmt' => the_date('', '', '', FALSE),
		));
	
		update_post_meta( $post->ID, "custom_boxes", $cb );
		update_post_meta( $new_post_id, "custom_boxes", $cb );
 
		$taxonomies = get_object_taxonomies($post->post_type); 
		foreach ($taxonomies as $taxonomy) {
			$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
			wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
		}
 
		$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
		if (count($post_meta_infos)!=0) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ($post_meta_infos as $meta_info) {
				$meta_key = $meta_info->meta_key;
				if( $meta_key == '_wp_old_slug' ) continue;
				$meta_value = addslashes($meta_info->meta_value);
				$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
			}
			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
			$wpdb->query($sql_query);
		}
 
		if( $return == 'redirect' ) {
			wp_redirect( admin_url( 'post.php?action=edit&post=' . $post_id ) );
			exit;
		} else {
			return $post_id;
		}
	} else {
		wp_die('Post creation failed, could not find original post: ' . $post_id);
	}
}

add_action( 'admin_action_px_convert_post_old_version', 'px_convert_post_old_version' );

function px_convert_post_old_version(){
	global $wpdb;
	
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'px_convert_post_old_version' == $_REQUEST['action'] ) ) ) {
		wp_die('No post to duplicate has been supplied!');
	}
 
	if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )
		return;
		
	$post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );

	apply_filters( 'px_process_convert_post_old_version', $post_id, 'redirect' );
}


add_action( 'admin_bar_menu', 'toolbar_admin_px', 999 );

function toolbar_admin_px( $wp_admin_bar ) {
	$post_id = ( isset($_GET['post']) ) ? $_GET['post'] : NULL;


	$results = get_option( 'posts_download_links_status_404', array() );


	$args = array(
		'id'    => 'appyn_content_import_gp',
		'title' => '<span class="ab-icon"></span><span class="ab-label">'.__( 'Import app (Google Play) ', 'appyn' ).' </span>',
		'href'  => admin_url().'admin.php?page=appyn_content_import_gp',
		'meta'  => array( 'class' => 'tbap-ipcgp'),
		'parent' => false, 
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'    => 'appyn_mod_apps',
		'title' => '<span class="ab-icon"></span><span class="ab-label">'.__( 'Apps list', 'appyn' ).' </span>',
		'href'  => admin_url().'edit.php?post_type=post',
		'meta'  => array( 'class' => 'tbap-ipcmda'),
		'parent' => false, 
	);
	$wp_admin_bar->add_node( $args );
	if (is_admin() && isset($_GET['post'])) {
        // Lấy ID bài viết hiện tại
        $post_id = intval($_GET['post']);

        // Kiểm tra meta option 'px_ggplay' của bài viết
        $px_ggplay_value = get_post_meta($post_id, 'px_ggplay', true);
        
        // Chỉ hiển thị nút nếu giá trị 'px_ggplay' là true
        if ($px_ggplay_value && $post_id) {
            // Tạo nút "Update Post" chỉ khi đang ở trong bài viết và có điều kiện 'px_ggplay'
			$args = array(
				'id'    => 'sync_post_data_action',
				'title' => '<span class="ab-icon dashicons dashicons-image-rotate"></span><span class="ab-label">'.__( 'Sync Post Data', 'appyn' ).' </span>',
				'href'  => admin_url('admin-post.php?action=update_post_action&post_id=' . $post_id),  // Lấy post_id hiện tại
				'meta'  => array('class' => 'tbap-ipcgp'),
				'parent' => false,
			);
            $wp_admin_bar->add_node($args);
        }
    }

}

add_action( 'admin_head', 'css_admin_bar' );
add_action( 'wp_head', 'css_admin_bar' );

function css_admin_bar() { 

	if( ! is_user_logged_in() ) return; 

	echo '
	<style type="text/css">
		.tbap-report .ab-icon::before,
		.tbap-update .ab-icon::before,
		.tbap-ipcgp .ab-icon::before,
		.tbap-ipaua .ab-icon::before,
		.tbap-cdl .ab-icon::before {
			content: "\f534";
			display: inline-block;
			-webkit-font-smoothing: antialiased;
			font-family: "dashicons";
			font-display: "swap";
			vertical-align: middle;
			position: relative;
			top: -3px;
		}
		#wpadminbar #wp-admin-bar-appyn_content_import_gp a {
			background: rgba(255,255,255,0.2);
		} 
		#wpadminbar #wp-admin-bar-appyn_content_import_gp,
		#wpadminbar #wp-admin-bar-appyn_updated_apps,
		#wpadminbar #wp-admin-bar-appyn_mod_apps {
			display: block;
		}
		.tbap-ipcgp .ab-icon::before,
		.tbap-ipcmda .ab-icon::before {
			content: "\f3ab";
			font-family: "Font Awesome 6 Brands";
			font-size: 17px;
			top: -2px;
		}
		.tbap-ipcmda .ab-icon::before {		
			content: "\f1c9";
			font-family: "Font Awesome 6 Free"	;
		}
		.tbap-update .ab-icon::before {
			content: "\f463";
            height: 19px;
		}
		.tbap-ipaua .ab-icon::before {
			content: "\f469";
            height: 19px;
		}
		.tbap-cdl .ab-icon::before {
			content: "\f225";
			height: 15px;
			font-size: 18px;
		}
        .tbap-update.wait .ab-icon {
            animation: infinite-spinning 2s infinite;
            -webkit-animation: infinite-spinning 2s infinite;
            -webkit-animation-timing-function: linear;
            animation-timing-function: linear;
        }
		@media (max-width:768px) {
			.tbap-ipcgp .ab-icon::before,
			.tbap-ipaua .ab-icon::before,
			.tbap-ipcmda .ab-icon::before {
				line-height: 1.33333333;
				height: 46px!important;
				text-align: center;
				width: 52px;
				font-size: 33px;
				vertical-align: inherit;
				top: 0;
			}
			.tbap-ipcgp .ab-icon::before,
			.tbap-ipcmda .ab-icon::before {
				top: -6px;
				font-size: 30px;
			}
		}
        @keyframes infinite-spinning {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
	</style>';
}

add_action( 'admin_menu', 'px_admin_menu_edit_u' );

function px_admin_menu_edit_u() {
	global $submenu;
	$submenu['edit.php'][5][2] = add_query_arg( 'post_type', 'post', $submenu['edit.php'][5][2] );
}


add_action( 'admin_init', 'admin_init_url_action' );

function admin_init_url_action() {

	$action = isset($_GET['action']) ? $_GET['action'] : NULL;
	
	if( $action == "google_drive_connect" ) {
		$gdrive = new TPX_GoogleDrive();
		if( $gdrive->getClient() ) {
			if (!get_option('appyn_gdrive_token')) {
				header("Location: ".$gdrive->getClient()->createAuthUrl());
				exit;
			}
		}
	}

	if( $action == "new_gdrive_info" ) {
		delete_option( 'appyn_gdrive_token' );
		header("Location: ". admin_url('admin.php?page=appyn_panel#servers'));
		exit;
	}

	if( $action == "dropbox_connect" ) {
		$dropbox_app_key = appyn_options( 'dropbox_app_key' );
		header("Location: https://www.dropbox.com/oauth2/authorize?client_id={$dropbox_app_key}&redirect_uri=".add_query_arg('appyn_upload', 'dropbox', get_bloginfo('url'))."&response_type=code&token_access_type=offline"); 
		exit;
	}

	if( $action == "new_dropbox_info" ) {
		delete_option( 'appyn_dropbox_result' );
		header("Location: ". admin_url('admin.php?page=appyn_panel#servers'));
		exit;
	}
	
	if( $action == "ftp_connect" ) {

		$name_ip 	= appyn_options( 'ftp_name_ip', true );
		$port 		= appyn_options( 'ftp_port', true ) ? appyn_options( 'ftp_port', true ) : 21;
		$username 	= appyn_options( 'ftp_username', true );
		$password 	= appyn_options( 'ftp_password', true );
		$directory	= appyn_options( 'ftp_directory', true ) ? trailingslashit(appyn_options( 'ftp_directory', true )) : '';
		$url		= untrailingslashit( appyn_options( 'ftp_url', true ) );

		$conn_id = @ftp_connect( $name_ip , $port, 30 ) or die( sprintf( __( 'No se pudo conectar a "%s". Verifique nuevamente', 'appyn' ), $name_ip ) ); 
		
		if( !$url ) die( __( 'Complete el campo URL', 'appyn' ) );

		if( @ftp_login( $conn_id, $username, $password ) ) {
			ftp_pasv($conn_id, true) or die( __( 'No se puede cambiar al modo pasivo', 'appyn' ) );

			$filename = 'test-file.txt';
			$contents = 'Hello World';
			$tmp = tmpfile();
			fwrite($tmp, $contents);
			rewind($tmp);
			$tmpdata = stream_get_meta_data($tmp);

			if( @ftp_put( $conn_id, $directory.$filename, $tmpdata['uri'], FTP_ASCII ) ) {
				echo '<p><b>'.__( '¡Se ha creado el archivo "test-file.txt" en su servidor!', 'appyn' ).'</b></p>';
				
				if( !$url ) {
					echo '<p>'.__( 'Es importante que coloque el campo URL ya que esa será la dirección con la que los usuarios accederán a descargar los archivos. Complete el campo y realice nuevamente el test de conexión.', 'appyn' ).'</p>';
				} else {
                    echo '<p>'.sprintf( __( 'Accede al archivo a través de este %s', 'appyn' ), '<a href="'.$url.'/'.$filename.'" target="_blank">'. __( 'enlace', 'appyn' ).'</a>').'. '. __( 'Si no puede acceder al enlace debe colocar el campo URL de manera correcta.', 'appyn' ).'</p>';
					echo '<p>'. __( 'Si accedió al enlace y apareció el texto "Hello World" entonces la conexión fue exitosa.', 'appyn' ).'</p>';
                }
			} else {
				echo __( 'No se pudo generar el archivo de prueba. Es posible que el directorio que ha colocado no exista.', 'appyn' ) . ' - ' . error_get_last()['message'];
			}
			fclose($tmp);
		} else {
			echo __( 'Datos del servidor incorrectos. Verifique nuevamente', 'appyn' );
		}
		ftp_close($conn_id);  

		exit;
	}
	
	if( $action == "onedrive_connect" ) {
		$onedrive = new TPX_OneDrive();
		header( "Location: ". $onedrive->ODConnect() );
		exit;
	}

	if( $action == "new_onedrive_info" ) {
		delete_option( 'appyn_onedrive_access_token' );
		header("Location: ". admin_url('admin.php?page=appyn_panel#servers'));
		exit;
	}
}
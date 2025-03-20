<?php

if( !defined('ABSPATH') ) die ( '✋' );

add_action('admin_menu', 'appyn_theme');

function appyn_theme() {

	add_menu_page( 'JOYDIGI', 'JOYDIGI', 'manage_options', 'appyn_panel', 'appyn_settings', get_stylesheet_directory_uri().'/admin/assets/images/ico-panel.png', 81 );
	
	add_submenu_page( 'appyn_panel', 'Panel', 'Panel', 'manage_options', 'appyn_panel' );
	
	add_submenu_page( 'appyn_panel', __( 'Import app', 'appyn' ), __( 'Import app', 'appyn' ), 'manage_options', 'appyn_content_import_gp', 'appyn_content_import_gp' );

	$capability = px_roles( appyn_options( 'mod_apps_roles' ) );
	add_submenu_page( 'appyn_panel', __( 'Import app apk', 'appyn' ), __( 'Import app apk', 'appyn' ), 'manage_options', 'appyn_content_import', 'appyn_content_import' );


	add_submenu_page( 'appyn_content_import_gp', __( 'Import app (Google Play)', 'appyn' ), __( 'Import app (Google Play)', 'appyn' ), $capability, 'appyn_content_import_gp', 'appyn_content_import_gp' );
}




function appyn_content_import_gp() {

    echo '<h3>' . __( 'Import app (Google Play)', 'appyn' ) . '</h3>';
    echo '<div class="extract-box">
            <form id="form-import">
                <input type="text" class="widefat" id="url_googleplay" name="" value="" placeholder="Google Play URL" spellcheck="false">
                <input type="submit" class="button button-primary button-large" id="importar" value="' . __( 'Import app', 'appyn' ) . '">
                <span class="spinner"></span>
            </form>
        </div>';
    echo '
    <p><em>' . __( 'By clicking "import app", a post will be created with all the imported information based on the options shown below...', 'appyn' ) . '</em></p>
    <h3>' . __( 'Options when importing app', 'appyn' ) . '</h3>
    <ul>
        <li>' . __( 'Post status', 'appyn' ) . ': <strong>' . ( (appyn_options('edcgp_post_status') == 1 ) ? __( 'Published', 'appyn' ) : __( 'Draft', 'appyn' ) ) . '</strong></li>
        <li>' . __( 'Create category if it doesn\'t exist', 'appyn' ) . ': <strong>' . ( (appyn_options('edcgp_create_category') == 1 ) ? __( 'No', 'appyn' ) : __( 'Yes', 'appyn' ) ) . '</strong></li>        
        <li>' . __( 'Create taxonomy <i>Developer</i> if it doesn\'t exist', 'appyn' ) . ': <strong>' . ( (appyn_options('edcgp_create_tax_dev') == 1 ) ? __( 'No', 'appyn' ) : __( 'Yes', 'appyn' ) ) . '</strong></li>        
        <li>' . __( 'Get APK', 'appyn' ) . ': <strong>' . ( (appyn_options('edcgp_sapk') ) ? __( 'No', 'appyn' ) : __( 'Yes', 'appyn' ) ) . '</strong></li>';
        
    if (appyn_options('edcgp_sapk') == 0) {
        echo '<li>' . __( 'Upload server', 'appyn' ) . ': <strong>' . px_option_selected_upload() . '</strong></li>';
    }
    if (appyn_options('edcgp_sapk_shortlink')) {
        echo '<li>' . __( 'Shorten link', 'appyn' ) . ': <strong>' . ucfirst(appyn_options('edcgp_sapk_shortlink')) . '</strong></li>';
    }
    echo '
        <li>' . __( 'Number of images imported', 'appyn' ) . ': <strong>' . appyn_options('edcgp_extracted_images') . '</strong></li>
        <li>' . __( 'Rating', 'appyn' ) . ': <strong>' . ( (appyn_options('edcgp_rating') ) ? __( 'Yes', 'appyn' ) : __( 'No', 'appyn' ) ) . '</strong></li>
        <li>' . __( 'Duplicate apps', 'appyn' ) . ': <strong>' . ( (appyn_options('edcgp_appd') == 1 ) ? __( 'Yes', 'appyn' ) : __( 'No', 'appyn' ) ) . '</strong></li>
    </ul>';
    echo '<p><a href="' . admin_url() . 'admin.php?page=appyn_panel#edcgp">' . __( 'Change options', 'appyn' ) . '</a></p>';
?>

<?php
}


function appyn_content_import() {

    echo '<h3>' . __( 'Import app (Gamejolt,Malavida,Softonic,Mcead,Apkpure,Apkcombo,Uptodown)', 'appyn' ) . '</h3>';
    echo '<div class="extract-box">
            <form id="form-import">
                <input type="text" class="widefat" id="url_googleplay" name="" value="" placeholder="Gamejolt,Malavida,Softonic,Mcead,Apkpure,Apkcombo,Uptodown URL" spellcheck="false">
                <input type="submit" class="button button-primary button-large" id="importar" value="' . __( 'Import app', 'appyn' ) . '">
                <span class="spinner"></span>
            </form>
        </div>';
	echo '<p><a href="' . admin_url() . 'admin.php?page=appyn_panel#edcgp">' . __( 'Change options', 'appyn' ) . '</a></p>';

?>

<?php
}




function lang_wp() {
	if( function_exists( 'icl_object_id' ) ){ //WPML
		$lang = ICL_LANGUAGE_CODE;
	} else {
		$lang = strstr(get_user_locale(), '_', true);
	}
	return $lang;
}

function appyn_settings() { ?> 
<div id="panel_theme_tpx">
    <div class="pttbox">
    	<form method="post" id="form-panel">
    		<div id="menu">
     			<ul>
      				<li style="background:#FFF; padding:10px 15px;"><b><?php echo __( 'Theme', 'appyn' ); ?>: </b>Appyn<br>
						  <b><?php echo __( 'Version', 'appyn' ); ?>: </b><?php echo VERSIONPX; ?><br>
					</li>						  
                    <li>
						<a href="#general"><i class="fa fa-cog"></i> <?php echo __( 'General Options', 'appyn' ); ?></a>
					</li>
                    <li>
						<a href="#home"><i class="fa fa-home"></i> <?php echo __( 'Home', 'appyn' ); ?></a>
					</li>
                    <li>
						<a href="#single"><i class="fa fa-file"></i> <?php echo __( 'Single', 'appyn' ); ?></a>
					</li>
                    <li>
						<a href="#edcgp"><i class="fa-brands fa-google-play"></i> <?php echo __( 'Content importer', 'appyn' ); ?></a>
					</li>
                    <li>
						<a href="#modapps"><i class="fa-solid fa-file-code"></i> <?php echo __( 'MOD apps', 'appyn' ); ?></a>
					</li>
                    <li>
						<a href="#servers"><i class="fa-solid fa-server"></i> <?php echo __( 'External servers', 'appyn' ); ?></a>
					</li>
                    <li>
						<a href="#shorteners"><i class="fas fa-link"></i> <?php echo __( 'Shorteners', 'appyn' ); ?></a>
					</li>
                    <li>
						<a href="#version_history"><i class="fa fa-history"></i> <?php echo __( 'Version history', 'appyn' ); ?></a>
					</li>
                    <li>
						<a href="#blog"><i class="fa-solid fa-blog"></i> <?php echo __( 'Blog', 'appyn' ); ?></a>
					</li>
                    <li>
						<a href="#color"><i class="fa-solid fa-palette"></i> <?php echo __( 'Colors', 'appyn' ); ?></a>
					</li>
                    <li>
						<a href="#sidebar"><i class="fa fa-list-ul"></i> <?php echo __( 'Sidebar', 'appyn' ); ?></a>
					</li>
                    <li>
						<a href="#ads1"><i class="fa-solid fa-dollar-sign"></i> <?php echo __( 'Advertisements 1', 'appyn' ); ?></a>
					</li>
					<li>
						<a href="#ads2"><i class="fa-solid fa-dollar-sign"></i> <?php echo __( 'Advertisements 2', 'appyn' ); ?></a>
					</li>
                    <li>
						<a href="#amp"><i class="fa fa-bolt"></i> <?php echo __( 'AMP', 'appyn' ); ?></a>
					</li>
                    <li>
						<a href="#footer"><i class="fa fa-terminal"></i> <?php echo __( 'Footer', 'appyn' ); ?></a>
					</li>
                    <li>
						<a href="#others"><i class="fa fa-info-circle"></i> <?php echo __( 'Info', 'appyn' ); ?></a>
					</li>
                    <li><div class="submit" style="clear:both">
                        <input type="submit" name="Submit" class="button-primary" value="<?php echo __( 'Save changes', 'appyn' ); ?>">
                        <input type="hidden" name="appyn_settings" value="save">
                        </div>
					</li>
     			</ul>
    		</div>
			
            <div class="section active" data-section="general">
				<h2><?php echo __( 'General Options', 'appyn' ); ?></h2>
				<table class="table-main">
					<tr>
						<td>
							<h3><?php echo __( 'Logo', 'appyn' ); ?></h3>
								<div class="descr"><?php echo __( 'The image must have a height limit of 60px.
', 'appyn' ); ?></div>
						</td>
						<td>
							<div class="regular-text-download df">
								<input type="text" name="logo" id="logo" value="<?php $logo = get_option( 'appyn_logo' ); echo (!empty($logo)) ? $logo : get_stylesheet_directory_uri().'/images/logo.png'; ?>" class="regular-text upload">
								<input class="upload_image_button" type="button" value="&#xf093;">
							</div>
						</td>
					</tr>

					<tr>
						<td><h3><?php echo __( 'Favicon', 'appyn' ); ?></h3></td>
						<td>
							<?php
							$favicon = appyn_options( 'favicon', true ) ? appyn_options( 'favicon', true ) : get_stylesheet_directory_uri().'/images/favicon.ico';
							?>
							<div class="regular-text-download">
								<p class="df"><input type="text" name="favicon" id="favicon" value="<?php echo $favicon; ?>" class="regular-text upload">
								<input class="upload_image_button" type="button" value="&#xf093;"></p>
							</div>
							<p><?php echo sprintf( __( 'Upload your favicons of different sizes for all devices. %s', 'appyn' ), '' ); ?></p>
						</td>
					</tr>

					<tr>
						<td><h3><?php echo __( 'Social networks', 'appyn' ); ?></h3></td>
						<td><?php $color_botones_sociales = appyn_options( 'social_single_color', 'default' ); ?>
							<table class="sub-table">
								<tr>
									<td><?php echo __( 'Color', 'appyn' ); ?></td>
									<td>
										<label><input type="radio" name="social_single_color" value="default"<?php checked($color_botones_sociales, 'default'); ?>> <?php echo __( ' Gray', 'appyn' ); ?> <?php echo __( '(Default)', 'appyn' ); ?> </label> &nbsp;
										<label><input type="radio" name="social_single_color" value="color"<?php checked($color_botones_sociales, 'color'); ?>> <?php echo __( 'Color', 'appyn' ); ?> </label>
									</td>
								</tr>
								<tr>
									<td>Facebook</td>
									<td><input type="text" name="social_facebook" value="<?php echo appyn_options( 'social_facebook', true ); ?>" class="regular-text text2"></td>
								</tr>
								<tr>
									<td>Twitter</td>
									<td><input type="text" name="social_twitter" value="<?php echo appyn_options( 'social_twitter', true ); ?>" class="regular-text text2"></td>
								</tr>
								<tr>
									<td>Instagram</td>
									<td><input type="text" name="social_instagram" value="<?php echo appyn_options( 'social_instagram', true ); ?>" class="regular-text text2"></td>
								</tr>
								<tr>
									<td>Youtube</td>
									<td><input type="text" name="social_youtube" value="<?php echo appyn_options( 'social_youtube', true ); ?>" class="regular-text text2"></td>
								</tr>
								<tr>
									<td>Pinterest</td>
									<td><input type="text" name="social_pinterest" value="<?php echo appyn_options( 'social_pinterest', true ); ?>" class="regular-text text2"></td>
								</tr>
								<tr>
									<td>Telegram</td>
									<td><input type="text" name="social_telegram" value="<?php echo appyn_options( 'social_telegram', true ); ?>" class="regular-text text2"></td>
								</tr>
							</table>
							
							
						</td>
					</tr>

					<tr>
						<td><h3><?php echo __( 'Title', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Modify some default texts', 'appyn' ); ?></div></td>
						<td>
							<?php
							$gte = appyn_options( 'general_text_edit', true );
							?>
							<p><?php echo __( 'Top rated apps', 'appyn' ); ?></p>
							<p><input type="text" name="general_text_edit[amc]" value="<?php echo ( isset($gte['amc']) ) ? $gte['amc'] : ''; ?>" class="regular-text"></p>

							<p><?php echo __( 'Latest Apps', 'appyn' ); ?></p>
							<p><input type="text" name="general_text_edit[uadnw]" value="<?php echo ( isset($gte['uadnw']) ) ? $gte['uadnw'] : ''; ?>" class="regular-text"></p>

							<p><?php echo __( 'App search', 'appyn' ); ?></p>
							<p><input type="text" name="general_text_edit[bua]" value="<?php echo ( isset($gte['bua']) ) ? $gte['bua'] : ''; ?>" class="regular-text"></p>

							<p><?php echo __( 'APK download button', 'appyn' ); ?></p>
							<p><input type="text" name="general_text_edit[bda]" value="<?php echo ( isset($gte['bda']) ) ? $gte['bda'] : ''; ?>" class="regular-text"></p>
						</td>
					</tr>

					<tr>
						<td><h3><?php echo __( 'Comments', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'You can display the comments of Wordpress and also those of Facebook.', 'appyn' ); ?></div></td>
						<td>
							<?php $comments = get_option( 'appyn_comments' ); ?>
							<p><input type="radio" name="comments" value="wp" id="comments_wp" <?php checked( $comments, 'wp', true); ?> checked> 
							<label for="comments_wp"><?php echo __( ' Wordpress comments', 'appyn' ); ?></label></p>

							<p><input type="radio" name="comments" value="fb" id="comments_fb" <?php checked( $comments, 'fb', true); ?>> 
							<label for="comments_fb"><?php echo __( ' Facebook comments', 'appyn' ); ?></label></p>

							<p><input type="radio" name="comments" value="wpfb" id="comments_wpfb" <?php checked( $comments, 'wpfb', true); ?>> 
							<label for="comments_wpfb"><?php echo __( ' Wordpress and Facebook comments', 'appyn' ); ?></label></p>

							<p><input type="radio" name="comments" value="disabled" id="comments_disabled" <?php checked( $comments, 'disabled', true); ?>> 
							<label for="comments_disabled"><?php echo __( ' Disable comments', 'appyn' ); ?></label></p>
						</td>
					</tr>

					<tr>
						<td><h3><?php echo __( 'Header codes', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Place the codes in the header, such as: Google Analytics, Webmasters, Alexa, etc.', 'appyn' ); ?></div></td>
						<td><textarea spellcheck="false" name="header_codigos" class="widefat" rows="8"><?php echo stripslashes(get_option( 'appyn_header_codigos' )); ?></textarea></td>
					</tr>

					<tr>
						<td><h3><?php echo __( 'Keys Recaptcha v3', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'In order to prevent robots from sending reports automatically, it is recommended to use recaptcha.', 'appyn' ); ?><br><a href="https://www.google.com/recaptcha/admin" target="_blank"><?php echo __( 'Get keys', 'appyn' ); ?></a></div></td>
						<td><?php
							$recaptcha_secret = get_option( 'appyn_recaptcha_secret' );
							$recaptcha_site = get_option( 'appyn_recaptcha_site' );
							?>
							<table class="sub-table">
								<tr>
									<td><?php echo __( 'Site key', 'appyn' ); ?></td>
									<td><input type="text" name="recaptcha_site" value="<?php echo $recaptcha_site; ?>" class="regular-text"></td>
								</tr>
								<tr>
									<td><?php echo __( 'Secret key', 'appyn' ); ?></td>
									<td><input type="text" name="recaptcha_secret" value="<?php echo $recaptcha_secret; ?>" class="regular-text"></td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td><h3><?php echo __( 'Keys Recaptcha v2', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Fill in the reCaptcha v2 codes.', 'appyn' ); ?><br><a href="https://www.google.com/recaptcha/admin" target="_blank"><?php echo __( 'Get keys', 'appyn' ); ?></a></div></td>
						<td><?php
							$recaptcha_v2_secret = get_option( 'appyn_recaptcha_v2_secret' );
							$recaptcha_v2_site = get_option( 'appyn_recaptcha_v2_site' );
							?>
							<table class="sub-table">
								<tr>
									<td><?php echo __( 'Site key', 'appyn' ); ?></td>
									<td><input type="text" name="recaptcha_v2_site" value="<?php echo $recaptcha_v2_site; ?>" class="regular-text"></td>
								</tr>
								<tr>
									<td><?php echo __( 'Secret key', 'appyn' ); ?></td>
									<td><input type="text" name="recaptcha_v2_secret" value="<?php echo $recaptcha_v2_secret; ?>" class="regular-text"></td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td><h3><?php echo __( 'Lazy loading', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Delay loading images', 'appyn' ); ?></div></td>
						<td>
							<?php
							$appyn_lazy_loading = appyn_options( 'lazy_loading' );
							?>
							<p><label class="switch"><input type="checkbox" name="lazy_loading" value="1" <?php checked( $appyn_lazy_loading, 1 ); ?>><span class="swisr"></span></label></p>
						</td>
					</tr>
				
					<tr>
						<td>
							<h3><?php echo __( 'Previous versions apps', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Control the appearance of apps from previous versions in different parts. Only the latest versions will appear.', 'appyn' ); ?></div>
						</td>
						<td>
							<?php $appyn_vmh = appyn_options( 'versiones_mostrar_inicio' ); ?>
							<table class="sub-table">
								<tr>
									<td style="width:165px"><?php echo __( 'Home', 'appyn' ); ?></td>
									<td>
										<label class="switch">
											<input type="checkbox" name="versiones_mostrar_inicio" value="0" <?php checked( $appyn_vmh, 0 ); ?>>
											<span class="swisr"></span>
										</label>
									</td>
								</tr>
								<?php $appyn_vmhc = appyn_options( 'versiones_mostrar_inicio_categorias' ); ?>
								<tr>
									<td><?php echo __( 'Categories (Home)', 'appyn' ); ?></td>
									<td>
										<label class="switch">
											<input type="checkbox" name="versiones_mostrar_inicio_categorias" value="0" <?php checked( $appyn_vmhc, 0 ); ?>>
											<span class="swisr"></span>
										</label>
									</td>
								</tr>
								<?php $appyn_vmhamc = appyn_options( 'versiones_mostrar_inicio_apps_mas_calificadas' ); ?>
								<tr>
									<td><?php echo __( 'Top rated apps (Home)', 'appyn' ); ?></td>
									<td>
										<label class="switch">
											<input type="checkbox" name="versiones_mostrar_inicio_apps_mas_calificadas" value="0" <?php checked( $appyn_vmhamc, 0 ); ?>>
											<span class="swisr"></span>
										</label>
									</td>
								</tr>
								<?php $appyn_vmb = appyn_options( 'versiones_mostrar_buscador' ); ?>
								<tr>
									<td><?php echo __( 'Search bar', 'appyn' ); ?></td>
									<td>
										<label class="switch">
											<input type="checkbox" name="versiones_mostrar_buscador" value="0" <?php checked( $appyn_vmb, 0 ); ?>>
											<span class="swisr"></span>
										</label>
									</td>
								</tr>
								<?php $appyn_vmtd = appyn_options( 'versiones_mostrar_tax_developer' ); ?>
								<tr>
									<td><?php echo __( 'Developer taxonomy', 'appyn' ); ?></td>
									<td>
										<label class="switch">
											<input type="checkbox" name="versiones_mostrar_tax_developer" value="0" <?php checked( $appyn_vmtd, 0 ); ?>>
											<span class="swisr"></span>
										</label>
									</td>
								</tr>
								<?php $appyn_vmc = appyn_options( 'versiones_mostrar_categorias' ); ?>
								<tr>
									<td><?php echo __( 'Categories', 'appyn' ); ?></td>
									<td>
										<label class="switch">
											<input type="checkbox" name="versiones_mostrar_categorias" value="0" <?php checked( $appyn_vmc, 0 ); ?>>
											<span class="swisr"></span>
										</label>
									</td>
								</tr>
								<?php $appyn_vmt = appyn_options( 'versiones_mostrar_tags' ); ?>
								<tr>
									<td><?php echo __( 'Tags', 'appyn' ); ?></td>
									<td>
										<label class="switch">
											<input type="checkbox" name="versiones_mostrar_tags" value="0" <?php checked( $appyn_vmt, 0 ); ?>>
											<span class="swisr"></span>
										</label>
									</td>
								</tr>
								<?php $appyn_vmw = appyn_options( 'versiones_mostrar_widgets' ); ?>
								<tr>
									<td><?php echo __( 'Widgets', 'appyn' ); ?></td>
									<td>
										<label class="switch">
											<input type="checkbox" name="versiones_mostrar_widgets" value="0" <?php checked( $appyn_vmw, 0 ); ?>>
											<span class="swisr"></span>
										</label>
									</td>
								</tr>
								<?php $appyn_mamc = appyn_options( 'versiones_mostrar_amc' ); ?>
								<tr>
									<td><?php echo __( 'Top rated apps (page)', 'appyn' ); ?></td>
									<td>
										<label class="switch">
											<input type="checkbox" name="versiones_mostrar_amc" value="0" <?php checked( $appyn_mamc, 0 ); ?>>
											<span class="swisr"></span>
										</label>
									</td>
								</tr>
								<?php $appyn_mamv = appyn_options( 'versiones_mostrar_amv' ); ?>
								<tr>
									<td><?php echo __( 'Most viewed apps (page)', 'appyn' ); ?></td>
									<td>
										<label class="switch">
											<input type="checkbox" name="versiones_mostrar_amv" value="0" <?php checked( $appyn_mamv, 0 ); ?>>
											<span class="swisr"></span>
										</label>
									</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td>
							<h3><?php echo __( 'Date of each post', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Show the date when the post was created everywhere (Home, Widgets, etc.)', 'appyn' ); ?></div>
						</td>
						<td>
							<?php $appyn_post_date = appyn_options( 'post_date' ); ?>
							<p>
								<label class="switch">
									<input type="checkbox" name="post_date" value="1" <?php checked( $appyn_post_date, 1 ); ?>>
									<span class="swisr"></span>
								</label>
							</p>

							<?php $appyn_post_date_type = appyn_options( 'post_date_type' ); ?>
							<p><?php echo __( 'Date type', 'appyn' ); ?></p>
							
							<p>
								<label>
									<input type="radio" name="post_date_type" value="0" <?php checked( $appyn_post_date_type, "0" ); ?>> 
									<?php echo __( 'Post creation date', 'appyn' ); ?>
								</label>
							</p>
							
							<p>
								<label>
									<input type="radio" name="post_date_type" value="1" <?php checked( $appyn_post_date_type, 1 ); ?>> 
									<?php echo __( 'Last updated app date', 'appyn' ); ?>
								</label>
							</p>
						</td>
					</tr>
					<tr>
						<td>
							<h3><?php echo __( 'Related apps', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Choose how to display related apps', 'appyn' ); ?>.</div>
						</td>
						<td>
							<?php $art = get_option( 'appyn_apps_related_type', array() ); ?>
							<p>
								<input type="checkbox" name="apps_related_type[]" value="cat" id="apps_related_type_cat" <?php echo ( is_array($art) && in_array('cat', $art) || empty($art) ) ? 'checked' : ''; ?>> 
								<label for="apps_related_type_cat"><?php echo __( 'By category(s)', 'appyn' ); ?> <?php echo __( '(Default)', 'appyn' ); ?></label>
							</p>

							<p>
								<input type="checkbox" name="apps_related_type[]" value="tag" id="apps_related_type_tag" <?php echo ( is_array($art) && in_array('tag', $art) ) ? 'checked' : ''; ?>> 
								<label for="apps_related_type_tag"><?php echo __( 'By tag(s)', 'appyn' ); ?></label>
							</p>

							<p>
								<input type="checkbox" name="apps_related_type[]" value="title" id="apps_related_type_title" <?php echo ( is_array($art) && in_array('title', $art) ) ? 'checked' : ''; ?>> 
								<label for="apps_related_type_title"><?php echo __( 'By similar title', 'appyn' ); ?></label>
							</p>

							<p>
								<input type="checkbox" name="apps_related_type[]" value="random" id="apps_related_type_random" <?php echo ( is_array($art) && in_array('random', $art) ) ? 'checked' : ''; ?>> 
								<label for="apps_related_type_random"><?php echo __( 'Randomly', 'appyn' ); ?></label>
							</p>
						</td>
					</tr>

				</table>
            </div>

            <div class="section" data-section="home">
				<h2><?php echo __( 'Home', 'appyn' ); ?></h2>

				<table class="table-main">			

					<tr>
						<td>
							<h3><?php echo __( 'Title', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Enter the Home title', 'appyn' ); ?>.</div>
						</td>
						<td>
							<input type="text" name="titulo_principal" value="<?php echo get_option( 'appyn_titulo_principal' ); ?>" class="widefat">
						</td>
					</tr>

					<tr>
						<td>
							<h3><?php echo __( 'Description', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Enter the Home description', 'appyn' ); ?>.</div>
						</td>
						<td>
							<textarea spellcheck="false" name="descripcion_principal" class="widefat" rows="5" spellcheck="false"><?php echo stripslashes(get_option( 'appyn_descripcion_principal' )); ?></textarea>
						</td>
					</tr>

					<tr>
						<td>
							<h3><?php echo __( 'Cover Images', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Upload up to 5 images to be displayed randomly on the Home. Preferably images larger than 1300px wide and 300px tall', 'appyn' ); ?>.</div>
						</td>
						<td>
							<table class="sub-table">
								<?php for($n=1;$n<=5;$n++) { ?>
								<tr>
									<td>
										<div class="regular-text-download df">
											<input type="text" name="image_header<?php echo $n; ?>" value="<?php echo get_option('appyn_image_header'.$n); ?>" class="regular-text">
											<input class="upload_image_button" type="button" value="&#xf093;">
										</div>
									</td>
								</tr>
								<?php } ?>
							</table>
						</td>
					</tr>

					<tr>
						<td>
							<h3><?php echo __( 'Top Rated Apps', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Slider with the most downloaded apps.', 'appyn' ); ?></div>
						</td>
						<td>
							<?php
							$mas_calificadas = get_option( 'appyn_mas_calificadas' );
							$mas_calificadas_limite = get_option( 'appyn_mas_calificadas_limite' );
							$mas_calificadas_limite = (empty($mas_calificadas_limite)) ? '5' : $mas_calificadas_limite;
							?>
							<p><label class="switch"><input type="checkbox" name="mas_calificadas" value="1" <?php checked( $mas_calificadas, 1 ); ?>><span class="swisr"></span></label></p>
							<p><input type="number" name="mas_calificadas_limite" size="2" value="<?php echo $mas_calificadas_limite; ?>" class="input_number" required> <?php echo __( 'Entries', 'appyn' ); ?></p>							
						</td>
					</tr>

					<tr>
						<td>
							<h3><?php echo __( 'Hide Posts', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Hide posts from the homepage.', 'appyn' ); ?></div>
						</td>
						<td>
							<?php $h = appyn_options( 'home_hidden_posts' ); ?>
							<p><label class="switch"><input type="checkbox" name="home_hidden_posts" value="1" <?php checked( $h, 1 ); ?>><span class="swisr"></span></label></p>
						</td>
					</tr>

					<tr>
						<td>
							<h3><?php echo __( 'Posts per Page', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Limit of posts.', 'appyn' ); ?></div>
						</td>
						<td>
							<?php
							$home_limite = get_option( 'appyn_home_limite' );
							$home_limite = (empty($home_limite)) ? '12' : $home_limite;
							echo '<input type="number" name="home_limite" size="2" value="'.$home_limite.'" class="input_number" required> '.__( 'Entries', 'appyn' );
							?>
						</td>
					</tr>

					<tr>
						<td>
							<h3><?php echo __( 'Order of Posts', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Order the posts on the Home by date, modified, or random.', 'appyn' ); ?></div>
						</td>
						<td>
							<?php $home_posts_orden = get_option( 'appyn_home_posts_orden' ); ?>
							<p><label><input type="radio" name="home_posts_orden" value="0" <?php checked( $home_posts_orden, "0" ); ?> <?php checked( $home_posts_orden, '' ); ?>> <?php echo __( 'By date', 'appyn' ); ?> <?php echo __( '(Default)', 'appyn' ); ?></label></p>
							<p><label><input type="radio" name="home_posts_orden" value="modified" <?php checked( $home_posts_orden, 'modified' ); ?>> <?php echo __( 'By modification', 'appyn' ); ?></label></p>
							<p><label><input type="radio" name="home_posts_orden" value="rand" <?php checked( $home_posts_orden, 'rand' ); ?>> <?php echo __( 'Random', 'appyn' ); ?></label></p>
						</td>
					</tr>

					<tr>
						<td>
							<h3><?php echo __( 'Home Categories', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Choose which categories and how many of them you want to appear on the Home below the latest posts', 'appyn' ); ?>.</div>
						</td>
						<td>
							<div style="overflow:auto; max-height:233px; margin-bottom:15px;">
								<?php
								$categorias_home = get_option( 'appyn_categories_home' );
								if( function_exists( 'icl_object_id' ) ){ //WPML
									$categorias_home = lang_object_ids($categorias_home,'category');
								}
								$categories = get_categories(array( 'hide_empty'=> 0));

								foreach( $categories as $cat ) {
									if( !empty($categorias_home) ){
										if( $cat->count == 0 ) continue;
										if (@in_array($cat->term_id, $categorias_home) ){
											echo '<label><input type="checkbox" name="categories_home[]" value="'.$cat->term_id.'" checked> '.$cat->name .'('.$cat->count.')</label><br>';
										} else {
											echo '<label><input type="checkbox" name="categories_home[]" value="'.$cat->term_id.'"> '.$cat->name.' ('.$cat->count.')</label><br>';
										}
									} else {
										echo '<label><input type="checkbox" name="categories_home[]" value="'.$cat->term_id.'"> '.$cat->name.' ('.$cat->count.')</label><br>';
									}
								}
								?>
							</div>
							<?php
							$categories_home_limite = get_option( 'appyn_categories_home_limite' );
							$categories_home_limite = (empty($categories_home_limite)) ? '6' : $categories_home_limite;
							echo '<p><input type="number" name="categories_home_limite" size="2" value="'.$categories_home_limite.'" class="input_number" required> ' . __( 'Entries', 'appyn' ).'</p>';
							?>
						</td>
					</tr>

					<tr>
						<td>
							<h3><?php echo __( 'Hide Blog', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Hide blog posts', 'appyn' ); ?></div>
						</td>
						<td>
							<?php $h = appyn_options( 'home_hidden_blog' ); ?>
							<p><label class="switch"><input type="checkbox" name="home_hidden_blog" value="1" <?php checked( $h, 1 ); ?>><span class="swisr"></span></label></p>
						</td>
					</tr>

					<tr>
						<td>
							<h3><?php echo __( 'Featured Posts', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Show up to 5 featured posts that only appear on the home', 'appyn' ); ?></div>
						</td>
						<td>
							<?php $hspc = appyn_options( 'home_sp_checked' ); ?>
							<p><input type="search" name="" class="widefat" id="search_posts" placeholder="<?php echo __( 'Search posts...', 'appyn' ); ?>"></p>
							<div id="sp_results"></div>
							<div id="sp_checked">
								<ul>
								<?php
								if( $hspc ) {
									foreach( $hspc as $h ) {
										echo '<li><input type="checkbox" name="home_sp_checked[]" value="'.$h.'" checked style="display:none;">'.get_the_title($h).' <a href="javascript:void(0);" class="delete">×</a></li>';
									}	
								}
								?>
								</ul>
							</div>
						</td>
					</tr>

					<tr>
						<td>
							<h3><?php echo __( 'Descriptions', 'appyn' ); ?><?php echo px_label_help( '<img src="'.get_stylesheet_directory_uri().'/admin/assets/images/home-description.png" height="250">', true ); ?></h3>
							<div class="descr"><?php echo __( 'Display content above and below the posts on the home', 'appyn' ); ?></div>
						</td>
						<td>
							<?php
							$hdt = appyn_options( 'home_description_top', true );
							$hdb = appyn_options( 'home_description_bottom', true );
							?>
							<p><?php echo __( 'At the top', 'appyn'); ?></p>
							<?php wp_editor( $hdt, 'home_description_top', array( 'media_buttons' => false, 'textarea_rows' => 8, 'quicktags' => true ) ); ?><br>
							<p><?php echo __( 'At the bottom', 'appyn'); ?></p>
							<?php wp_editor( $hdb, 'home_description_bottom', array( 'media_buttons' => false, 'textarea_rows' => 8, 'quicktags' => true ) ); ?><br>
						</td>
					</tr>
					</table>

			</div>

            <div class="section" data-section="edcgp">
				<h2><?php echo __( 'Content importer', 'appyn' ); ?></h2>

				<table class="table-main">
					<tr>
						<td><h3><?php echo __( 'Roles', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'User types that will see the content importer.', 'appyn' ); ?></div>
						</td>
						<td>
							<?php 
							$roles = appyn_options( 'edcgp_roles' );
							?>
							<select name="edcgp_roles">
								<option value="administrator"<?php selected(0, $roles); ?><?php selected('administrator', $roles); ?>><?php echo __( 'Administrator', 'appyn'); ?></option>
								<option value="editor"<?php selected('editor', $roles); ?>><?php echo __( 'Administrator and Editor', 'appyn'); ?></option>
								<option value="author"<?php selected('author', $roles); ?>><?php echo __( 'Administrator, Editor, and Author', 'appyn'); ?></option>
								<option value="contributor"<?php selected('contributor', $roles); ?>><?php echo __( 'Administrator, Editor, Author, and Contributor', 'appyn'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Post Status', 'appyn'); ?></h3></td>
						<td><?php $edcgp_post_status = appyn_options( 'edcgp_post_status' ); ?>
							
							<p><label><input type="radio" name="edcgp_post_status" value="0" <?php checked( $edcgp_post_status, 0 ); ?>> <?php echo __( 'Draft', 'appyn' ); ?></label></p>

							<p><label><input type="radio" name="edcgp_post_status" value="1" <?php checked( $edcgp_post_status, 1 ); ?>> <?php echo __( 'Published', 'appyn' ); ?></label></p>
						</td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Create Category', 'appyn'); ?></h3>
							<div class="descr"><?php echo __( 'Create the category if it does not exist', 'appyn' ); ?></div>
						</td>
						<td><?php $edcgp_create_category = appyn_options( 'edcgp_create_category' ); ?>
							<label class="switch"><input type="checkbox" name="edcgp_create_category" value="0" <?php checked( $edcgp_create_category, "0"); ?>><span class="swisr"></span></label>
						</td>
					</tr>
					<tr>
						<td><h3><?php echo __( "Create 'Developer' taxonomy", 'appyn'); ?></h3>
							<div class="descr"><?php echo __( "Create the 'Developer' taxonomy if it does not exist", 'appyn' ); ?></div>
						</td>
						<td><?php $edcgp_create_tax_dev = appyn_options( 'edcgp_create_tax_dev' ); ?>
							<label class="switch"><input type="checkbox" name="edcgp_create_tax_dev" value="0" <?php checked( $edcgp_create_tax_dev, "0"); ?>><span class="swisr"></span></label>
						</td>
					</tr>
					<tr>
						<td><h3><?php echo __( "Get APK", 'appyn'); ?></h3></td>
						<td><?php 
							$edcgp_sapk = appyn_options( 'edcgp_sapk' );
							$edcgp_sapk_server = appyn_options( 'edcgp_sapk_server' ); 
							$edcgp_sapk_slug = appyn_options( 'edcgp_sapk_slug', true ); 
							$edcgp_sapk_shortlink = appyn_options( 'edcgp_sapk_shortlink', true );
							?>						
							<label class="switch switch-show" data-sshow="edcgp_sapk_server" data-svalue="0"><input type="checkbox" name="edcgp_sapk" value="0" <?php checked( $edcgp_sapk, "0"); ?>><span class="swisr"></span></label>
						</td>
					</tr>
					<tr>
						<td><h3><?php echo __( "Upload Server", 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( "Select the server to upload the APK files.", 'appyn' ); ?></div>
						</td>
						<td><select name="edcgp_sapk_server">
								<option value="1"<?php echo selected($edcgp_sapk_server, 1); ?>><?php echo __( 'My Server', 'appyn' ); ?></option>
								<option value="2"<?php echo selected($edcgp_sapk_server, 2); ?>><?php echo __( 'Google Drive', 'appyn' ); ?></option>
								<option value="3"<?php echo selected($edcgp_sapk_server, 3); ?>><?php echo __( 'Dropbox', 'appyn' ); ?></option>
								<option value="4"<?php echo selected($edcgp_sapk_server, 4); ?>><?php echo __( 'FTP', 'appyn' ); ?></option>
								<option value="5"<?php echo selected($edcgp_sapk_server, 5); ?>><?php echo __( '1Fichier', 'appyn' ); ?></option>
								<option value="6"<?php echo selected($edcgp_sapk_server, 6); ?>><?php echo __( 'OneDrive', 'appyn' ); ?></option>
								<option value="7"<?php echo selected($edcgp_sapk_server, 7); ?>><?php echo __( 'Telegram', 'appyn' ); ?></option>
							</select></td>
					</tr>
					<tr>
						<td><h3><?php echo __( "Shortener", 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( "Select your preferred shortener for download links.", 'appyn' ); ?></div>
						</td>
						<td><select name="edcgp_sapk_shortlink">
								<option value=""><?php echo __( 'None' ); ?></option>
								<option value="ouo"<?php echo selected($edcgp_sapk_shortlink, 'ouo'); ?>>Ouo.io</option>
								<option value="shrinkearn"<?php echo selected($edcgp_sapk_shortlink, 'shrinkearn'); ?>>ShrinkEarn.com</option>
								<option value="shorte"<?php echo selected($edcgp_sapk_shortlink, 'shorte'); ?>>Shorte.st</option>
								<option value="clicksfly"<?php echo selected($edcgp_sapk_shortlink, 'clicksfly'); ?>>ClicksFly.com</option>
								<option value="oke"<?php echo selected($edcgp_sapk_shortlink, 'oke'); ?>>Oke.io</option>
							</select><br><br>
							<p><i><?php echo sprintf(__( 'Important: Enter the API Key of the selected shortener in the %s section.', 'appyn' ), '<strong><u>'.__( 'Shorteners', 'appyn' ).'</u></strong>' ); ?></i></p>
						</td>
					</tr>
					<tr>
						<td><h3><?php echo __( "Slug", 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( "Automatically adds text at the end of the file name.", 'appyn' ); ?></div>
						</td>
						<td><input type="text" name="edcgp_sapk_slug" value="<?php echo $edcgp_sapk_slug; ?>" class="widefat"><br>
							<p><em><?php echo __( 'Example', 'appyn' ); ?>: com-file-<b><?php echo ( $edcgp_sapk_slug ) ? $edcgp_sapk_slug : 'example'; ?></b>.apk</em></p>
						</td>
					</tr>
					<tr>
						<td><h3><?php echo __( "Screenshots", 'appyn'); ?></h3>
							<div class="descr"><?php echo __( 'Number of application screenshots.', 'appyn' ); ?></div>
						</td>
						<td><?php $edcgp_extracted_images = appyn_options( 'edcgp_extracted_images' ); ?>
							<input type="number" name="edcgp_extracted_images" value="<?php echo $edcgp_extracted_images; ?>" class="input_number"> <?php echo __( 'Screenshots', 'appyn'); ?></td>
					</tr>
					<tr>
						<td><h3><?php echo __( "Rating", 'appyn'); ?></h3>
							<div class="descr"><?php echo __( 'Fetch the application ratings.', 'appyn' ); ?></div>
						</td>
						<td><?php $edcgp_rating = appyn_options( 'edcgp_rating' ); ?>
							<label class="switch"><input type="checkbox" name="edcgp_rating" value="1" <?php checked( $edcgp_rating, 1 ); ?>><span class="swisr"></span></label></td>    
					</tr>
					<tr>
						<td><h3><?php echo __( "Duplicate Apps", 'appyn'); ?></h3>
							<div class="descr"><?php echo __( 'Allow importing duplicate applications', 'appyn' ); ?></div>
						</td>
						<td><?php $edcgp_appd = appyn_options( 'edcgp_appd' ); ?>
							<label class="switch"><input type="checkbox" name="edcgp_appd" value="1" <?php checked( $edcgp_appd, 1 ); ?>><span class="swisr"></span></label></td>    
					</tr>
				</table><br>

				<h2><?php echo __( 'When updating app information', 'appyn' ); ?></h2>

				<table class="table-main tmnb">
					<tr>
						<td><h3><?php echo __( "Create a new version", 'appyn'); ?></h3>
							<div class="descr"><?php echo __( 'Create a post with the new version when updating an app.', 'appyn' ); ?></div>
						</td>
						<td><?php $edcgp_up = appyn_options( 'edcgp_update_post' ); ?>
							<label class="switch"><input type="checkbox" name="edcgp_update_post" value="1" <?php checked( $edcgp_up, 1 ); ?>><span class="swisr"></span></label></td>    
					</tr>
					<tr>
						<td><h3><?php echo __( 'Disable fields', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Fields to disable when updating the app information.', 'appyn' ); ?></div>
						</td>
						<td><?php 
							$dca = get_option( 'appyn_dedcgp_descamp_actualizar', array() );
							?>
							<p><label><input type="checkbox" name="dedcgp_descamp_actualizar[]" value="app_title" <?php echo (is_array($dca) && in_array('app_title', $dca) ) ? 'checked' : ''; ?>> <span><?php echo __( 'Title', 'appyn' ); ?></span></label></p>

							<p><label><input type="checkbox" name="dedcgp_descamp_actualizar[]" value="app_description" <?php echo (is_array($dca) && in_array('app_description', $dca) ) ? 'checked' : ''; ?>> <span><?php echo __( 'Description', 'appyn' ); ?></span></label></p>

							<p><label><input type="checkbox" name="dedcgp_descamp_actualizar[]" value="app_content" <?php echo (is_array($dca) && in_array('app_content', $dca) ) ? 'checked' : ''; ?>> <span><?php echo __( 'Content', 'appyn' ); ?></span></label></p>

							<p><label><input type="checkbox" name="dedcgp_descamp_actualizar[]" value="app_ico" <?php echo (is_array($dca) && in_array('app_ico', $dca) ) ? 'checked' : ''; ?>> <span><?php echo __( 'Icon', 'appyn' ); ?></span></label></p>

							<p><label><input type="checkbox" name="dedcgp_descamp_actualizar[]" value="app_download_links" <?php echo (is_array($dca) && in_array('app_download_links', $dca) ) ? 'checked' : ''; ?>> <span><?php echo __( 'Download Links', 'appyn' ); ?></span></label></p>

							<p><label><input type="checkbox" name="dedcgp_descamp_actualizar[]" value="app_screenshots" <?php echo (is_array($dca) && in_array('app_screenshots', $dca) ) ? 'checked' : ''; ?>> <span><?php echo __( 'Screenshots', 'appyn' ); ?></span></label></p>

							<p><label><input type="checkbox" name="dedcgp_descamp_actualizar[]" value="app_video" <?php echo (is_array($dca) && in_array('app_video', $dca) ) ? 'checked' : ''; ?>> <span><?php echo __( 'Video', 'appyn' ); ?></span></label></p>
						</td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Remove old featured image', 'appyn'); ?></h3>
							<div class="descr"><?php echo __( 'Remove the old featured image from the post. This will also affect old versions.', 'appyn' ); ?></div>
						</td>
						<td><?php $eid = appyn_options( 'eidcgp_update_post' ); ?>
							<label class="switch"><input type="checkbox" name="eidcgp_update_post" value="1" <?php checked( $eid, 1 ); ?>><span class="swisr"></span></label></td>    
					</tr>
					<tr>
						<td><h3><?php echo __( "Keep Categories", 'appyn'); ?></h3>
							<div class="descr"><?php echo __( 'Preserve the same categories when a post is updated.', 'appyn' ); ?></div>
						</td>
						<td><?php $mc = appyn_options( 'edcgp_mc' ); ?>
							<label class="switch"><input type="checkbox" name="edcgp_mc" value="1" <?php checked( $mc, 1 ); ?>><span class="swisr"></span></label></td>    
					</tr>
					<tr>
						<td><h3><?php echo __( "Remove old files", 'appyn'); ?></h3>
							<div class="descr"><?php echo __( 'When updating an app, the old file will be removed.', 'appyn' ); ?></div>
						</td>
						<td><?php $eaa = appyn_options( 'edcgp_eaa' ); ?>
							<label class="switch"><input type="checkbox" name="edcgp_eaa" value="1" <?php checked( $eaa, 1 ); ?>><span class="swisr"></span></label></td>    
					</tr>
				</table><br>

			</div>
			
			<div class="section" data-section="modapps">
				<h2><?php echo __( 'MOD apps', 'appyn' ); ?></h2>
					
				<table class="table-main">
					<tr>
						<td>
							<h3><?php echo __( 'Roles', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'User types who will see the "mod apps" area', 'appyn' ); ?></div>
						</td>
						<td>
							<?php 
							$roles = appyn_options( 'mod_apps_roles' );
							?>
							<select name="mod_apps_roles">
								<option value="administrator"<?php selected(0, $roles); ?><?php selected('administrator', $roles); ?>><?php echo __( 'Administrator', 'appyn'); ?></option>
								<option value="editor"<?php selected('editor', $roles); ?>><?php echo __( 'Administrator and Editor', 'appyn'); ?></option>
								<option value="author"<?php selected('author', $roles); ?>><?php echo __( 'Administrator, Editor, and Author', 'appyn'); ?></option>
								<option value="contributor"<?php selected('contributor', $roles); ?>><?php echo __( 'Administrator, Editor, Author, and Contributor', 'appyn'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<h3><?php echo __( 'Post Status', 'appyn'); ?></h3>
							<div class="descr"><?php echo __( 'Status of the imported posts', 'appyn' ); ?></div>
						</td>
						<td>
							<?php $mod_apps_post_status = appyn_options( 'mod_apps_post_status' ); ?>
							<p><label><input type="radio" name="mod_apps_post_status" value="0" <?php checked( $mod_apps_post_status, 0 ); ?>> <?php echo __( 'Draft', 'appyn' ); ?></label></p>
							<p><label><input type="radio" name="mod_apps_post_status" value="1" <?php checked( $mod_apps_post_status, 1 ); ?>> <?php echo __( 'Published', 'appyn' ); ?></label></p>
						</td>
					</tr>
					<tr>
						<td>
							<h3><?php echo __( 'Post Content', 'appyn'); ?></h3>
							<div class="descr"><?php echo __( 'Get information about the modified app by default or take it from Google Play', 'appyn' ); ?></div>
						</td>
						<td>
							<?php $mod_apps_post_content = appyn_options( 'mod_apps_import_content' ); ?>
							<p><label><input type="radio" name="mod_apps_import_content" value="0" <?php checked( $mod_apps_post_content, 0 ); ?>> <?php echo __( 'By default', 'appyn' ); ?></label></p>
							<p><label><input type="radio" name="mod_apps_import_content" value="1" <?php checked( $mod_apps_post_content, 1 ); ?>> <?php echo __( 'From Google Play', 'appyn' ); ?></label></p>
						</td>
					</tr>
					<?php 
					$mod_apps_sapk_shortlink = appyn_options( 'mod_apps_sapk_shortlink', true );
					?>
					<tr>
						<td>
							<h3><?php echo __( "Shortener", 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( "Select your preferred shortener for download links.", 'appyn' ); ?></div>
						</td>
						<td>
							<select name="mod_apps_sapk_shortlink">
								<option value=""><?php echo __( 'None' ); ?></option>
								<option value="ouo"<?php echo selected($mod_apps_sapk_shortlink, 'ouo'); ?>>Ouo.io</option>
								<option value="shrinkearn"<?php echo selected($mod_apps_sapk_shortlink, 'shrinkearn'); ?>>ShrinkEarn.com</option>
								<option value="shorte"<?php echo selected($mod_apps_sapk_shortlink, 'shorte'); ?>>Shorte.st</option>
								<option value="clicksfly"<?php echo selected($mod_apps_sapk_shortlink, 'clicksfly'); ?>>ClicksFly.com</option>
								<option value="oke"<?php echo selected($mod_apps_sapk_shortlink, 'oke'); ?>>Oke.io</option>
							</select><br><br>
							<p><i><?php echo sprintf(__( 'Important: Place the API Key of the selected shortener in the %s section.', 'appyn' ), '<strong><u>'.__( 'Shorteners', 'appyn' ).'</u></strong>' ); ?></i></p>
						</td>
					</tr>
					<?php 
					$mod_apps_data_gpl = appyn_options( 'mod_apps_data_gpl', true );
					?>
					<tr>
						<td>
							<h3><?php echo __( "Data Obtained from Google Play", 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( "By default, the demo info of a post is taken from Google Play. Here you can disable or enable which data will be extracted from Google Play", 'appyn' ); ?></div>
						</td>
						<td>
							<?php
							$elms = array( 
								'short_description' => __( 'Short Description', 'appyn' ),
								'requirements' => __( 'Requirements', 'appyn' ),
								'rating' => __( 'Rating', 'appyn' ),
								'downloads' => __( 'Number of Downloads', 'appyn' ),
								'category' => __( 'Category', 'appyn' ),
								'developer' => __( 'Developer', 'appyn' ),
								'whats_new' => __( 'What\'s New', 'appyn' ),
								'video' => __( 'Video', 'appyn' ),
								'screenshots' => __( 'Screenshots', 'appyn' ),
							);
							foreach( $elms as $k => $el ) {
								echo '<p><label><input type="checkbox" name="mod_apps_data_gpl[]" value="'.$k.'" '. ( ( is_array($mod_apps_data_gpl) && in_array($k, $mod_apps_data_gpl) ) ? 'checked' : '') .'> '.$el.'</label></p>';
							}
							?>
						</td>
					</tr>
				</table><br>
			</div>



			<div class="section" data-section="servers">
				<h2><?php echo __( 'External servers', 'appyn' ); ?></h2>
				
				<?php
				$url = ( lang_wp() == "es" ) ? 'https://themespixel.net/api-google-drive-obtener-id-de-cliente-y-secreto-para-almacenamiento/' : 'https://themespixel.net/en/google-drive-api-get-client-id-and-secret-for-storage/';
				?>
				<table class="table-main">
					<tr>
						<td colspan="2">
							<h2 style="padding:0;"><?php echo __( 'Google Drive', 'appyn' ); ?></h2>	
							<?php echo __( 'Create a Google Drive API and enter the client ID and secret code in the following fields.', 'appyn' ); ?> <?php echo sprintf( __( 'Follow the %s to create the API.', 'appyn' ), '<a href="'.$url.'" target="_blank">'. __( 'tutorial', 'appyn' ). '</a>' ); ?>
						</td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Client ID', 'appyn' ); ?>*</h3></td>
						<td><input type="text" name="gdrive_client_id" id="gdrive_client_id" value="<?php echo appyn_options( 'gdrive_client_id', true ); ?>" class="widefat"></td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Client Secret', 'appyn' ); ?>*</h3></td>
						<td><input type="text" name="gdrive_client_secret" id="gdrive_client_secret" value="<?php echo appyn_options( 'gdrive_client_secret', true ); ?>" class="widefat"></td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Folder', 'appyn' ); ?><?php echo px_label_help( __('Enter the name of a folder that will be created automatically and all files will be uploaded there.', 'appyn' )); ?></h3></td>
						<td><input type="text" name="gdrive_folder" id="gdrive_folder" value="<?php echo appyn_options( 'gdrive_folder', true ); ?>" class="widefat"></td>
					</tr>
					<?php 
					$gdt = appyn_options( 'gdrive_token' );
					?>
					<tr>
						<td><?php 
							if( $gdt ) {
								echo '<a href="'.admin_url('admin.php?page=appyn_panel&action=new_gdrive_info#edcgp').'">'.__( 'Connect new account', 'appyn'). '</a>'.px_label_help( __('Click here only if you have added a new client ID and secret. The connect button will appear again that you need to click.', 'appyn' ) );
							}
							?>
						</td>
						<td>
							<?php
							if( $gdt && appyn_options( 'gdrive_client_secret', true )  && appyn_options( 'gdrive_client_id', true ) ) {
								echo '<strong style="color:#50b250"><i class="fa fa-check"></i> '.__( 'Connected to Google Drive', 'appyn').'</strong>';
							} else {
								echo '<p id="alert_test_gdrive" style="display:none; font-weight:bold;">'. __( 'Remember to save changes before making the connection', 'appyn' ). '</p>';
								echo '<a class="button" id="button_google_drive_connect" href="'. admin_url(). 'admin.php?page=appyn_panel&action=google_drive_connect">'.__( 'Connect to Google Drive', 'appyn' ).'</a>';
							}
							?>	
						</td>
					</tr>
				</table><br>

				<?php
				$url = ( lang_wp() == "es" ) ? 'https://themespixel.net/crear-app-de-dropbox-para-almacenamiento-de-archivos/' : 'https://themespixel.net/en/create-dropbox-app-for-file-storage/';
				?>
				<table class="table-main">
					<tr>
						<td colspan="2">
							<h2 style="padding:0;"><?php echo __( 'Dropbox', 'appyn' ); ?></h2>	
							<?php echo __( 'Create an app in Dropbox and enter the app key and app secret in the following fields.', 'appyn' ); ?> <?php echo sprintf( __( 'Follow the %s.', 'appyn' ), '<a href="'.$url.'" target="_blank">'.__( 'tutorial', 'appyn' ). '</a>' ); ?>
						</td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'App Key', 'appyn' ); ?>*</h3></td>
						<td><input type="text" name="dropbox_app_key" id="dropbox_app_key" value="<?php echo appyn_options( 'dropbox_app_key', true ); ?>" class="widefat"></td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'App Secret', 'appyn' ); ?>*</h3></td>
						<td><input type="text" name="dropbox_app_secret" id="dropbox_app_secret" value="<?php echo appyn_options( 'dropbox_app_secret', true ); ?>" class="widefat"></td>
					</tr>
					<?php 
					$dbr = appyn_options( 'dropbox_result' );
					?>
					<tr>
						<td><?php 
							if( $dbr ) {
								echo '<a href="'.admin_url('admin.php?page=appyn_panel&action=new_dropbox_info#edcgp').'">'.__( 'Connect new account', 'appyn'). '</a>'.px_label_help( __('Click here only if you have added a new app key and app secret. The connect button will appear again that you need to click.', 'appyn' ) );
							}
							?>
						</td>
						<td>
							<?php
							if( $dbr && appyn_options( 'dropbox_app_key', true )  && appyn_options( 'dropbox_app_secret', true ) ) {
								echo '<strong style="color:#50b250"><i class="fa fa-check"></i> '.__( 'Connected to Dropbox', 'appyn').'</strong>';
							} else {
								echo '<p id="alert_test_dropbox" style="display:none; font-weight:bold;">'. __( 'Remember to save changes before making the connection', 'appyn' ). '</p>';
								echo '<a class="button" id="button_dropbox_connect" href="'. admin_url(). 'admin.php?page=appyn_panel&action=dropbox_connect">'.__( 'Connect to Dropbox', 'appyn' ).'</a>';
							}
							?>	
						</td>
					</tr>
				</table><br>

				<?php
				$url = ( lang_wp() == "es" ) ? 'https://themespixel.net/crear-app-de-dropbox-para-almacenamiento-de-archivos/' : 'https://themespixel.net/en/create-dropbox-app-for-file-storage/';
				?>
				<table class="table-main">
					<tr>
						<td colspan="2">
							<h2 style="padding:0;"><?php echo __( 'FTP', 'appyn' ); ?></h2>	
							<?php echo __( 'Do you have an external server? Connect via FTP so that imported files are uploaded to your own server.', 'appyn' ); ?></td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Server Name or IP', 'appyn' ); ?>*</h3></td>
						<td><input type="text" name="ftp_name_ip" id="ftp_name_ip" value="<?php echo appyn_options( 'ftp_name_ip', true ); ?>" class="widefat"></td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Port', 'appyn' ); ?></h3></td>
						<td><input type="text" name="ftp_port" id="ftp_port" value="<?php echo appyn_options( 'ftp_port', true ); ?>" class="widefat"></td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Username', 'appyn' ); ?>*</h3></td>
						<td><input type="text" name="ftp_username" id="ftp_username" value="<?php echo appyn_options( 'ftp_username', true ); ?>" class="widefat"></td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Password', 'appyn' ); ?>*</h3></td>
						<td><input type="text" name="ftp_password" id="ftp_password" value="<?php echo appyn_options( 'ftp_password', true ); ?>" class="widefat"></td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Directory', 'appyn' ); ?>*</h3>
							<div class="descr"><?php echo __( 'Specify the exact path where files will be saved', 'appyn' ); ?></div></td>
						<td><input type="text" name="ftp_directory" id="ftp_directory" value="<?php echo appyn_options( 'ftp_directory', true ); ?>" class="widefat"><br>
						<div style="font-style:italic">public_html<br>
						/website.com/<br>
						/web/website.com/public_html/</div></td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'URL', 'appyn' ); ?>*</h3>
							<div class="descr"><?php echo __( 'Enter the address that will be used to access your files', 'appyn' ); ?></div></td>
						<td><input type="text" name="ftp_url" id="ftp_url" value="<?php echo appyn_options( 'ftp_url', true ); ?>" class="widefat" placeholder="https://website.com"></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<?php
							echo '<p id="alert_test_ftp" style="display:none; font-weight:bold;">'. __( 'Remember to save changes to test the connection', 'appyn' ). '</p>';
							echo '<a class="button" id="button_ftp_connect" href="'. admin_url(). 'admin.php?page=appyn_panel&action=ftp_connect" target="_blank">'.__( 'Test FTP Connection', 'appyn' ).'</a>';
							?>	
						</td>
					</tr>
				</table><br>

				<?php
				$url = ( lang_wp() == "es" ) ? 'https://themespixel.net/genera-una-api-key-en-1fichier/' : 'https://themespixel.net/en/generate-an-api-key-in-1fichier/';
				?>
				<table class="table-main">
					<tr>
						<td colspan="2">
							<h2 style="padding:0;"><?php echo __( '1Fichier', 'appyn' ); ?></h2>	
							<?php echo __( 'Generate an API Key in 1Fichier', 'appyn' ); ?>. <?php echo sprintf( __( 'Follow the %s.', 'appyn' ), '<a href="'.$url.'" target="_blank">'.__( 'tutorial', 'appyn' ). '</a>' ); ?>
						</td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'API Key', 'appyn' ); ?>*</h3></td>
						<td><input type="text" name="1fichier_apikey" id="1fichier_apikey" value="<?php echo appyn_options( '1fichier_apikey', true ); ?>" class="widefat"></td>
					</tr>
				</table><br>

				<?php
				$url = ( lang_wp() == "es" ) ? 'https://themespixel.net/crear-app-de-onedrive-para-almacenamiento-de-archivos/' : 'https://themespixel.net/en/create-onedrive-app-for-file-storage/';
				?>
				<table class="table-main">
					<tr>
						<td colspan="2">
							<h2 style="padding:0;"><?php echo __( 'OneDrive', 'appyn' ); ?></h2>	
							<?php echo __( 'Create an app in OneDrive', 'appyn' ); ?>. <?php echo sprintf( __( 'Follow the %s.', 'appyn' ), '<a href="'.$url.'" target="_blank">'.__( 'tutorial', 'appyn' ). '</a>' ); ?>
						</td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Client ID', 'appyn' ); ?>*</h3></td>
						<td><input type="text" name="onedrive_client_id" id="onedrive_client_id" value="<?php echo appyn_options( 'onedrive_client_id', true ); ?>" class="widefat"></td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Client Secret', 'appyn' ); ?>*</h3></td>
						<td><input type="text" name="onedrive_client_secret" id="onedrive_client_secret" value="<?php echo appyn_options( 'onedrive_client_secret', true ); ?>" class="widefat"></td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Folder', 'appyn' ); ?></h3></td>
						<td><input type="text" name="onedrive_folder" id="onedrive_folder" value="<?php echo appyn_options( 'onedrive_folder', true ); ?>" class="widefat"></td>
					</tr>
					<?php 
					$odat = appyn_options( 'onedrive_access_token' );
					?>
					<tr>
						<td><?php 
							if( $odat ) {
								echo '<a href="'.admin_url('admin.php?page=appyn_panel&action=new_onedrive_info#edcgp').'">'.__( 'Connect new account', 'appyn'). '</a>'.px_label_help( __('Click here only if you have added a new client ID and secret. The connect button will appear again that you need to click.', 'appyn' ) );
							}
							?>
						</td>
						<td>
							<?php
							if( $odat && appyn_options( 'onedrive_client_secret', true )  && appyn_options( 'onedrive_client_id', true ) ) {
								echo '<strong style="color:#50b250"><i class="fa fa-check"></i> '.__( 'Connected to OneDrive', 'appyn').'</strong>';
							} else {
								echo '<p id="alert_test_onedrive" style="display:none; font-weight:bold;">'. __( 'Remember to save changes before making the connection', 'appyn' ). '</p>';
								echo '<a class="button" id="button_onedrive_connect" href="'. admin_url(). 'admin.php?page=appyn_panel&action=onedrive_connect">'.__( 'Connect to OneDrive', 'appyn' ).'</a>';
							}
							?>	
						</td>
					</tr>
				</table><br>

				<?php
				$url = ( lang_wp() == "es" ) ? 'https://themespixel.net/telegram-bot-api-crear-un-bot-y-obtener-el-token-de-acceso/' : 'https://themespixel.net/en/telegram-bot-api-create-a-bot-and-get-the-access-token/';
				?>
				<table class="table-main">
					<tr>
						<td colspan="2">
							<h2 style="padding:0;"><?php echo __( 'Telegram', 'appyn' ); ?></h2>
							<?php echo __( 'Create your bot. Get the token and chat ID', 'appyn' ); ?>. <?php echo sprintf( __( 'Follow the %s.', 'appyn' ), '<a href="'.$url.'" target="_blank">'.__( 'tutorial', 'appyn' ). '</a>' ); ?>
						</td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Token', 'appyn' ); ?>*</h3></td>
						<td><input type="text" name="telegram_token" id="telegram_token" value="<?php echo appyn_options( 'telegram_token', true ); ?>" class="widefat"></td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Chat ID', 'appyn' ); ?>*</h3></td>
						<td><input type="text" name="telegram_chatid" id="telegram_chatid" value="<?php echo appyn_options( 'telegram_chatid', true ); ?>" class="widefat"></td>
					</tr>
				</table>
			</div>

			<div class="section" data-section="single">
				<h2><?php echo __( 'Single', 'appyn' ); ?></h2>
				<table class="table-main">
					<tr>
						<td>
							<h3><?php echo __( 'Read More', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __("Displays the full content of the post or leave it with the 'read more' button", 'appyn'); ?>.</div>
						</td>
						<td><?php $readmore_single = get_option( 'appyn_readmore_single' ); ?>
							<p><input type="radio" name="readmore_single" value="0" id="readmore_default" <?php checked( $readmore_single, 0, true); ?> checked> 
								<label for="readmore_default"><?php echo __( 'Read More (default)', 'appyn' ); ?></label></p>
							<p><input type="radio" name="readmore_single" value="1" id="readmore_all" <?php checked( $readmore_single, 1, true); ?>> 
								<label for="readmore_all"><?php echo __( 'Show All', 'appyn' ); ?></label></p></td>
					</tr>
					<?php $download_links = get_option( 'appyn_download_links' ); ?>
					<tr>
						<td>
							<h3><?php echo __( 'Download Links', 'appyn' ); ?></h3>
							<div class="descr"><a href="https://themespixel.net/en/docs/appyn/panel/#doc3" target="_blank"><?php echo __( 'How does this work?', 'appyn' ); ?></a></div>    
						</td>
						<td>
							<p><label><input type="radio" name="download_links" value="0" <?php checked( $download_links, '', true); checked( $download_links, 0, true); ?>> <?php echo __( 'Normal', 'appyn' ); ?></label></p>
							<p><label><input type="radio" name="download_links" value="1" <?php checked( $download_links, 1, true); ?>> <?php echo __( 'Internal Page', 'appyn' ); ?></label></p>
							<p><label><input type="radio" name="download_links" value="2" <?php checked( $download_links, 2, true); ?>> <?php echo __( 'Internal Page with Double Step', 'appyn' ); ?></label></p>
							<p><label><input type="radio" name="download_links" value="3" <?php checked( $download_links, 3, true); ?>> <?php echo __( 'Single Page', 'appyn' ); ?></label></p>
						</td>
					</tr>
					
					<tr>
						<td>
							<h3><?php echo __( 'Download Links', 'appyn' ); ?> (<?php echo __( 'Permalinks' ); ?>)</h3>
						</td>
						<td>
							<?php
							$dlp = appyn_options( 'download_links_permalinks' );
							?>
							<p><label><input type="radio" name="download_links_permalinks" value="0" <?php checked( $dlp, '', true); checked( $dlp, 0, true); ?>> web.com/post/?download=links <?php echo __( '(Default)', 'appyn' ); ?></label></p>
							<p><label><input type="radio" name="download_links_permalinks" value="1" <?php checked( $dlp, '', true); checked( $dlp, 1, true); ?>> web.com/post/download/</label></p>

							<p><i><?php echo sprintf( __( 'When making an option change, you must refresh the permalinks. To do this, go to %s and save the changes. Remember that the structure "%s" must be selected', 'appyn'), '<a href="'.admin_url('options-permalink.php').'">'.__( 'Permalinks' ).'</a>', '<strong>'.__( 'Post name' ).'</strong>' ); ?></i></p>
						</td>
					</tr>
					
					<tr>
						<td>
							<h3><?php echo __( 'Complete reCaptcha', 'appyn' ); ?><?php echo px_label_help( sprintf( __( 'You must complete the reCaptcha v2 codes in the required fields in %s', 'appyn' ), '<b>'.__( 'General Options', 'appyn' ).'</b>' ) ); ?></h3>
							<div class="descr"><?php echo __( 'Option that will request the user to complete the reCaptcha to view the download links.', 'appyn' ); ?></div>
						</td>
						<td>
							<?php
							$asdr = appyn_options( 'active_show_dl_recaptcha' );
							?>
							<p><label class="switch"><input type="checkbox" name="active_show_dl_recaptcha" value="1" <?php checked( $asdr, 1 ); ?>><span class="swisr"></span></label></p>
						</td>
					</tr>

					<?php $download_links_d = get_option( 'appyn_download_links_design' ); ?>
					<tr>
						<td>
							<h3><?php echo __( 'Download Links Style', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Choose the style of the download links', 'appyn' ); ?></div>    
						</td>
						<td>
							<p><label><input type="radio" name="download_links_design" value="0" <?php checked( $download_links_d, '', true); checked( $download_links_d, 0, true); ?>> <?php echo __( 'In Row', 'appyn' ); ?> <?php echo __( '(Default)', 'appyn' ); ?></label></p>
							<p><label><input type="radio" name="download_links_design" value="1" <?php checked( $download_links_d, 1, true); ?>> <?php echo __( 'Centered Row', 'appyn' ); ?></label></p>
							<p><label><input type="radio" name="download_links_design" value="2" <?php checked( $download_links_d, 2, true); ?>> <?php echo __( 'In Column', 'appyn' ); ?></label></p>
						</td>
					</tr>
					<?php 
					$download_links_vb = get_option( 'appyn_download_links_verified_by' );
					$download_links_vbp = get_option( 'appyn_download_links_verified_by_p' ); 
					?>
					<tr>
						<td>
							<h3><?php echo __( 'Verified By...', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Alternative text that will be displayed below the download links', 'appyn' ); ?></div>    
						</td>
						<td>
							<p><input type="text" name="download_links_verified_by" value="<?php echo $download_links_vb; ?>" class="widefat" placeholder="<?php echo 'Verified by Sitename Protect'; ?>"></p>
							<p><label><input type="checkbox" name="download_links_verified_by_p" value="1"<?php checked( $download_links_vbp, 1 ); ?>> <?php echo __( 'Centered', 'appyn' ); ?></label></p>
						</td>
					</tr>
					<?php 
					$dltbu = get_option( 'appyn_download_links_telegram_button_url' ); 
					$dltbt = get_option( 'appyn_download_links_telegram_button_text' ); 
					?>
					<tr>
						<td>
							<h3><?php echo __( 'Join Our Telegram Group', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Button to join Telegram that will appear below "Verified By..."', 'appyn' ); ?></div>    
						</td>
						<td>
							<p><input type="text" name="download_links_telegram_button_url" value="<?php echo $dltbu; ?>" class="widefat" placeholder="<?php echo 'https://t.me/xxxxxxxxxxx'; ?>"></p>
							<p><input type="text" name="download_links_telegram_button_text" value="<?php echo $dltbt; ?>" class="widefat" placeholder="<?php echo __( 'JOIN OUR TELEGRAM GROUP', 'appyn' ); ?>"></p>
						</td>
					</tr>
					<?php $redirect_timer = appyn_options( 'redirect_timer' ); ?>
					<tr>
						<td>
							<h3><?php echo __( 'Redirect Timer', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Timer for redirecting the download link', 'appyn' ); ?>.</div>    
						</td>
						<td>
							<p><label><input type="number" name="redirect_timer" min="0" max="999" value="<?php echo (isset($redirect_timer)) ? $redirect_timer : 5; ?>" class="input_number"> <?php echo __( 'seconds', 'appyn' ); ?></label></p>
							<p>
								<a href="https://demo.themespixel.net/appyn/lords-mobile-guerra-de-reinos-batalla-mmo-rpg/?download=links#new" target="_blank"><?php echo __( 'Example', 'appyn' ); ?></a>
							</p>
						</td>
					</tr>
					<?php $active_redirect = appyn_options( 'active_redirect' ); ?>
					<tr>
						<td>
							<h3><?php echo __( 'Redirect', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Enable or disable the redirect to the download link', 'appyn' ); ?>.</div>    
						</td>
						<td>
							<p><label class="switch"><input type="checkbox" name="active_redirect" value="1" <?php checked( $active_redirect, 1 ); ?>><span class="swisr"></span></label></p>
						</td>
					</tr>
					<?php $active_footer = appyn_options( 'active_footer' ); ?>
					<tr>
						<td>
							<h3><?php echo __( 'Display Footer', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Enable or disable the footer', 'appyn' ); ?>.</div>    
						</td>
						<td>
							<p><label class="switch"><input type="checkbox" name="active_footer" value="1" <?php checked( $active_footer, 1 ); ?>><span class="swisr"></span></label></p>
						</td>
					</tr>
				</table>
			</div>

			<div class="section" data-section="version_history">
				<h2><?php echo __( 'Version History', 'appyn' ); ?></h2>
				<table class="table-main">
					<tr>
						<td>
							<h3><?php echo __( 'Number of entries', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Choose the number of versions that will appear in the entry box.', 'appyn' ); ?>.</div>
						</td>
						<td>
							<p><input type="number" name="versiones_cantidad_post" size="2" value="<?php $cvp = get_option( 'appyn_versiones_cantidad_post' ); echo ($cvp) ? $cvp : 5; ?>" min="1" max="100" class="input_number" required> <?php echo __( 'Entries', 'appyn' ); ?></p>
						</td>
					</tr>
					<tr>
						<td>
							<h3><?php echo __( 'Boxes to remove from the old version entry', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Check the boxes you do not want to appear in the Version History entries.', 'appyn' ); ?></div>
						</td>
						<td><?php 
							$cvn = get_option( 'appyn_versiones_no_cajas', array() ); 
							foreach( $oc as $k => $o ) {
								if( array_key_exists( $k, $oc_default ) ) {
									if( $k == 'versiones' ) continue;
									echo '<p><label><input type="checkbox" name="versiones_no_cajas[]" value="'.$k.'" '. ((in_array($k, $cvn) ) ? 'checked' : '') .'> <span>'. __( $o, 'appyn' ) .'</span></label></p>';
								}
							}
							?>
						</td>
					</tr>
					<tr>
						<td>
							<h3><?php echo __( 'Show direct download link', 'appyn' ); ?> <?php echo px_label_help( __( 'This option will allow the direct download link of the old version to be displayed without the user needing to access the post to download. It will show the first download link. If it does not exist, the link will point to the post.', 'appyn' ) ); ?></h3>
							<div class="descr"><?php echo __( 'Allow direct access to the first download link', 'appyn' ); ?></div>
						</td>
						<td><?php
							$vdld = appyn_options( 'version_download_link_direct' );
							?>
							<p><label class="switch"><input type="checkbox" name="version_download_link_direct" value="1" <?php checked( $vdld, 1 ); ?>><span class="swisr"></span></label></p>
						</td>
					</tr>
					<tr>
						<td>
							<h3><?php echo __( 'Noindex for old versions', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Automatically disables old version pages for search engines.', 'appyn' ); ?></div>
						</td>
						<td>
							<?php 
							$s = appyn_options( 'version_history_noindex' ); ?>
							<p><label class="switch"><input type="checkbox" name="version_history_noindex" value="1" <?php checked( $s, 1 ); ?>><span class="swisr"></span></label></p>
						</td>
					</tr>
				</table>
			</div>


            <div class="section" data-section="sidebar">
				<h2>Sidebar</h2>
				
				<table class="table-main">
					<tr>
						<td><h3><?php echo __( 'Active', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Activate or deactivate the sidebar.', 'appyn' ); ?></div>
						</td>
						<td>
							<?php 
							$s = appyn_options( 'sidebar_active' ); ?>
							<p><label class="switch"><input type="checkbox" name="sidebar_active" value="0" <?php checked( $s, "0" ); ?>><span class="swisr"></span></label></p>
						</td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Sidebar position', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Choose the sidebar position.', 'appyn' ); ?></div>
						</td>
						<td>
							<?php $sidebar_ubicacion = appyn_options( 'sidebar_ubicacion' ); ?>
							<p><label><input type="radio" name="sidebar_ubicacion" value="right" <?php checked( $sidebar_ubicacion, "right" ); ?> <?php checked( $sidebar_ubicacion, "0" ); ?>> <?php echo __( 'Right', 'appyn' ); ?></label></p>

							<p><label><input type="radio" name="sidebar_ubicacion" value="left" <?php checked( $sidebar_ubicacion, "left" ); ?>> <?php echo __( 'Left', 'appyn' ); ?></label></p>
						</td>
					</tr>
				</table>
            </div>

			<div class="section" data-section="color">
				<h2><?php echo __( 'Colors', 'appyn' ); ?></h2>
				<table class="table-main">
					<tr>
						<td>
							<h3><?php echo __( 'Color Style', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Choose the type of tone for the theme.', 'appyn' ); ?></div>
						</td>
						<td>
							<?php $color_theme = appyn_options( 'color_theme' ); ?>					
							<p><label><input type="radio" name="color_theme" value="light" <?php checked( $color_theme, "light" ); ?> <?php checked( $color_theme, "0" ); ?>> <?php echo __( 'Light', 'appyn' ); ?></label></p>
							<p><label><input type="radio" name="color_theme" value="dark" <?php checked( $color_theme, "dark" ); ?>> <?php echo __( 'Dark', 'appyn' ); ?></label></p>
							<p><label><input type="radio" name="color_theme" value="browser" <?php checked( $color_theme, "browser" ); ?>> <?php echo __( 'Browser', 'appyn' ); ?></label></p>
						</td>
					</tr>
					<tr>
						<td>
							<h3><?php echo __( 'Allow user to choose color', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Allow the user to select the theme color.', 'appyn' ); ?></div>
						</td>
						<td>
							<?php $c = appyn_options( 'color_theme_user_select' ); ?>
							<p><label class="switch"><input type="checkbox" name="color_theme_user_select" value="1" <?php checked( $c, 1 ); ?>><span class="swisr"></span></label></p>
						</td>
					</tr>
					<tr>
						<td>
							<h3><?php echo __( 'Main Color', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Choose the main color of the theme.', 'appyn' ); ?></div>				
						</td>
						<td>
							<?php $color_theme_principal = appyn_options( 'color_theme_principal', '#1bbc9b' );
							echo '<input name="color_theme_principal" class="colorpicker" value="'.$color_theme_principal.'" data-default-color="#1bbc9b">'; ?>
						</td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Download Button Color', 'appyn' ); ?></h3></td>
						<td>
							<?php $color_download_button = appyn_options( 'color_download_button', '#1bbc9b' );
							echo '<input name="color_download_button" class="colorpicker" value="'.$color_download_button.'" data-default-color="#1bbc9b">'; ?>
						</td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'New Ribbon Color', 'appyn' ); ?></h3></td>
						<td>
							<?php $color_new_ribbon = appyn_options( 'color_new_ribbon', '#d22222' );
							echo '<input name="color_new_ribbon" class="colorpicker" value="'.$color_new_ribbon.'" data-default-color="#d22222">'; ?>
						</td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Updated Ribbon Color', 'appyn' ); ?></h3></td>
						<td>
							<?php $color_update_ribbon = appyn_options( 'color_update_ribbon', '#19b934' );
							echo '<input name="color_update_ribbon" class="colorpicker" value="'.$color_update_ribbon.'" data-default-color="#19b934">'; ?>
						</td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Star Color', 'appyn' ); ?></h3></td>
						<td>
							<?php $color_stars = appyn_options( 'color_stars', '#f9bd00' );
							echo '<input name="color_stars" class="colorpicker" value="'.$color_stars.'" data-default-color="#f9bd00">'; ?>
						</td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'MOD Tag Color', 'appyn' ); ?></h3></td>
						<td>
							<?php $color_tag_mod = appyn_options( 'color_tag_mod', '#20a400' );
							echo '<input name="color_tag_mod" class="colorpicker" value="'.$color_tag_mod.'" data-default-color="#20a400">'; ?>
						</td>
					</tr>
				</table>
			</div>

			
			
			<div class="section" data-section="blog">
				<h2>Blog</h2>
				
				<table class="table-main">
					<tr>
						<td>
							<h3><?php echo __( 'Blog Section on Home', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Specify the number of posts that should appear on the home page.', 'appyn' ); ?></div>							
						</td>
						<td><?php
							$blog_posts_home_limit = appyn_options( 'appyn_blog_posts_home_limit', 4 );
							echo '<input type="number" name="blog_posts_home_limit" size="2" value="'.$blog_posts_home_limit.'" class="input_number" required> '.__( 'Entries', 'appyn' );	
							?>
						</td>
					</tr>
					<tr>
						<td>
							<h3><?php echo __( 'Blog Page', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Specify the number of posts that should appear on the blog page', 'appyn' ); ?></div>
						</td>
						<td><?php
							$blog_posts_limit = appyn_options( 'appyn_blog_posts_limit', 10 );
							echo '<input type="number" name="blog_posts_limit" size="2" value="'.$blog_posts_limit.'" class="input_number" required> '.__( 'Entries', 'appyn' );					
							?>
						</td>
					</tr>
					<tr>
						<td>
							<h3><?php echo __( 'Blog Sidebar', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Enable this option to add widgets to the exclusive blog sidebar', 'appyn' ); ?></div>
						</td>
						<td>
							<?php
							$bsd = appyn_options( 'blog_sidebar' );
							?>
							<p><label class="switch"><input type="checkbox" name="blog_sidebar" value="1" <?php checked( $bsd, 1 ); ?>><span class="swisr"></span></label></p>
							<p><a href="<?php bloginfo('url'); ?>/wp-admin/widgets.php"><?php echo __( 'Add Widgets', 'appyn' ); ?></a> (Blog Sidebar)</p>
						</td>
					</tr>
				</table>
			</div>

			
			<div class="section" data-section="amp">
				<h2><?php echo __( 'AMP', 'appyn' ); ?></h2>
				<table class="table-main">
					<tr>
						<td><h3><?php echo __( 'AMP', 'appyn' ); ?></h3>
							<div class="descr">Accelerated Mobile Pages.<br>
							<a href="https://support.google.com/google-ads/answer/7496737" target="_blank"><?php echo __( 'More info', 'appyn' ); ?></a></div></td>
						<td>
							<?php
							$appyn_amp = appyn_options( 'amp' );
							?>
							<label class="switch"><input type="checkbox" name="amp" value="1" <?php checked( $appyn_amp, 1 ); ?>><span class="swisr"></span></label>
						</td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Google Analytics', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Put the Analytics ID', 'appyn' ); ?></div></td>
						<td><input type="text" name="analytics_amp" class="regular-text" value="<?php echo appyn_options( 'analytics_amp', true ); ?>" placeholder="UA-XXXXXXXX-XX"></td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Header codes', 'appyn' ); ?></h3>
							<div class="descr"><?php echo sprintf( __( 'Put the codes in the header (inside the %s)', 'appyn' ), '&lt;head&gt;&lt;/head&gt;' ); ?></div></td>
						<td><textarea spellcheck="false" name="header_codigos_amp" class="widefat" rows="8"><?php echo stripslashes(get_option( 'appyn_header_codigos_amp' )); ?></textarea></td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Body codes', 'appyn' ); ?></h3>
							<div class="descr"><?php echo sprintf( __( 'Put codes under the %s tags', 'appyn' ), '&lt;body&gt;' ); ?></div></td>
						<td><textarea spellcheck="false" name="body_codigos_amp" class="widefat" rows="8"><?php echo stripslashes(get_option( 'appyn_body_codigos_amp' )); ?></textarea></td>
					</tr>
						<td><h3>ADS Header</h3>
							<div class="descr"><?php echo __( 'Add the ad code for the AMP version', 'appyn' ); ?>.</div>
						</td>
						<td><textarea spellcheck="false" name="ads_header_amp" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_header_amp' )); ?></textarea></td>
					</tr>
					<tr>
						<td><h3>ADS Single Top</h3>
							<div class="descr"><?php echo __( 'Add the ad code for the single and page in the middle in the AMP version.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="ads_single_top_amp" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_single_top_amp' )); ?></textarea></td>
					</tr>
					
					<tr>
						<td><h3>ADS Single Center</h3>
							<div class="descr"><?php echo __('Add the ad code to the top of the download links on the internal page of the AMP version.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="ads_single_center_amp" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_single_center_amp' )); ?></textarea></td>
					</tr>
					
					<tr>
						<td><h3>ADS Download 1<br>
							<?php echo __( '(Internal page)', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Añade el código de anuncio para la parte superior de los enlaces de descarga en la internal page de la version AMP.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="ads_download_1_amp" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_download_1_amp' )); ?></textarea></td>
					</tr>
				</table>
			</div>

			<div class="section" data-section="ads1">
				<h2><?php echo __( 'Advertisements 1', 'appyn' ); ?></h2>
				
				<table class="table-main">
					<tr>
						<td><h3><?php echo __( 'Text above each ad (Optional)', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Place a text on top of each ad.', 'appyn' ); ?></div>
						</td>
						<td><input type="text" name="ads_text_above_1" class="regular-text" value="<?php echo stripslashes(get_option( 'appyn_ads_text_above_1' )); ?>"></td>
					</tr>
					<tr>
						<td><h3>ADS Header</h3>
							<div class="descr"><?php echo __( 'Add the ad code below the header.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="ads_header_1" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_header_1' )); ?></textarea></td>
					</tr>
					<tr>
						<td><h3>ADS Header [<?php echo __( 'Mobile', 'appyn' ); ?>]</h3>
							<div class="descr"><?php echo __( 'Add the ad code for the mobile version', 'appyn' ); ?>.</div>
						</td>
						<td><textarea spellcheck="false" name="ads_header_movil_1" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_header_movil_1' )); ?></textarea></td>
					</tr>
					<tr>
						<td><h3>ADS Single Top</h3>
							<div class="descr"><?php echo __( 'Add the ad code for the single and page.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="ads_single_top_1" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_single_top_1' )); ?></textarea></td>
					</tr>
					<tr>
						<td><h3>ADS Single Top [<?php echo __( 'Mobile', 'appyn' ); ?>]</h3>
							<div class="descr"><?php echo __( 'Add the ad code for the single and page in the mobile version.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="ads_single_top_movil_1" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_single_top_movil_1' )); ?></textarea></td>
					</tr>
					<tr>
						<td><h3>ADS Single Center</h3>
							<div class="descr"><?php echo __( 'Add the ad code for the single and page in the middle.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="ads_single_center_1" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_single_center_1' )); ?></textarea></td>
					</tr>
					<tr>
						<td><h3>ADS Single Center [<?php echo __( 'Mobile', 'appyn' ); ?>]</h3>
							<div class="descr"><?php echo __( 'Add the ad code to the top of the download links on the internal page.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="ads_single_center_movil_1" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_single_center_movil_1' )); ?></textarea></td>
					</tr>

					<tr>
						<td><h3>ADS Download 1<br>
							<?php echo __( '(Internal page)', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Add the ad code to the top of the download links on the internal page.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="ads_download_1_1" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_download_1_1' )); ?></textarea></td>
					</tr>

					<tr>
						<td><h3>ADS Download 2<br>
							<?php echo __( '(Internal page)', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Add the ad code to the bottom of the download links on the internal page.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="ads_download_2_1" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_download_2_1' )); ?></textarea></td>
					</tr>

					<tr>
						<td><h3>ADS Download 1<br>
							<?php echo __( '(Internal page)', 'appyn' ); ?> [<?php echo __( 'Mobile', 'appyn' ); ?>]</h3>
							<div class="descr"><?php echo __( 'Añade el código de anuncio para la parte superior de los enlaces de descarga en la internal page en la version mobile.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="ads_download_1_movil_1" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_download_1_movil_1' )); ?></textarea></td>
					</tr>

					<tr>
						<td><h3>ADS Download 2<br>
							<?php echo __( '(Internal page)', 'appyn' ); ?> [<?php echo __( 'Mobile', 'appyn' ); ?>]</h3>
							<div class="descr"><?php echo __( 'Add the ad code to the bottom of the download links on the internal page in the mobile version.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="ads_download_2_movil_1" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_download_2_movil_1' )); ?></textarea></td>
					</tr>
				</table>
			</div>

			<div class="section" data-section="ads2">
				<h2><?php echo __( 'Advertisements 2', 'appyn' ); ?></h2>
				
				<table class="table-main">
					<tr>
						<td><h3><?php echo __( 'Text above each ad (Optional)', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Place a text on top of each ad.', 'appyn' ); ?></div>
						</td>
						<td><input type="text" name="ads_text_above_2" class="regular-text" value="<?php echo stripslashes(get_option( 'appyn_ads_text_above_2' )); ?>"></td>
					</tr>
					<tr>
						<td><h3>ADS Header</h3>
							<div class="descr"><?php echo __( 'Add the ad code below the header.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="ads_header_2" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_header_2' )); ?></textarea></td>
					</tr>
					<tr>
						<td><h3>ADS Header [<?php echo __( 'Mobile', 'appyn' ); ?>]</h3>
							<div class="descr"><?php echo __( 'Add the ad code for the mobile version', 'appyn' ); ?>.</div>
						</td>
						<td><textarea spellcheck="false" name="ads_header_movil_2" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_header_movil_2' )); ?></textarea></td>
					</tr>
					<tr>
						<td><h3>ADS Single Top</h3>
							<div class="descr"><?php echo __( 'Add the ad code for the single and page.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="ads_single_top_2" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_single_top_2' )); ?></textarea></td>
					</tr>
					<tr>
						<td><h3>ADS Single Top [<?php echo __( 'Mobile', 'appyn' ); ?>]</h3>
							<div class="descr"><?php echo __( 'Add the ad code for the single and page in the mobile version.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="ads_single_top_movil_2" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_single_top_movil_2' )); ?></textarea></td>
					</tr>
					<tr>
						<td><h3>ADS Single Center</h3>
							<div class="descr"><?php echo __( 'Add the ad code for the single and page in the middle.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="ads_single_center_2" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_single_center_2' )); ?></textarea></td>
					</tr>
					<tr>
						<td><h3>ADS Single Center [<?php echo __( 'Mobile', 'appyn' ); ?>]</h3>
							<div class="descr"><?php echo __( 'Add the ad code to the top of the download links on the internal page.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="ads_single_center_movil_2" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_single_center_movil_2' )); ?></textarea></td>
					</tr>

					<tr>
						<td><h3>ADS Download 1<br>
							<?php echo __( '(Internal page)', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Add the ad code to the top of the download links on the internal page.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="ads_download_1_2" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_download_1_2' )); ?></textarea></td>
					</tr>

					<tr>
						<td><h3>ADS Download 2<br>
							<?php echo __( '(Internal page)', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Add the ad code to the bottom of the download links on the internal page.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="ads_download_2_2" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_download_2_2' )); ?></textarea></td>
					</tr>

					<tr>
						<td><h3>ADS Download 1<br>
							<?php echo __( '(Internal page)', 'appyn' ); ?> [<?php echo __( 'Mobile', 'appyn' ); ?>]</h3>
							<div class="descr"><?php echo __( 'Añade el código de anuncio para la parte superior de los enlaces de descarga en la internal page en la version mobile.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="ads_download_1_movil_2" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_download_1_movil_2' )); ?></textarea></td>
					</tr>

					<tr>
						<td><h3>ADS Download 2<br>
							<?php echo __( '(Internal page)', 'appyn' ); ?> [<?php echo __( 'Mobile', 'appyn' ); ?>]</h3>
							<div class="descr"><?php echo __( 'Add the ad code to the bottom of the download links on the internal page in the mobile version.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="ads_download_2_movil_2" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_ads_download_2_movil_2' )); ?></textarea></td>
					</tr>
				</table>
			</div>

			<?php
			$url = ( lang_wp() == "es" ) ? 'https://themespixel.net/shorteners-de-enlaces-para-generar-ganancias-extras/' : 'https://themespixel.net/en/link-shorteners-to-generate-extra-earnings/';
			?>
			<div class="section" data-section="shorteners">
				<h2><?php echo __( 'Shorteners', 'appyn' ); ?></h2>
				
				<table class="table-main">
					<tr>
						<td colspan="100%">
							<p><?php echo sprintf( __( 'Enter the API Key of your preferred shortener. %s', 'appyn' ), '<a href="'.$url.'" target="_blank">'.__( 'Read tutorial', 'appyn' ).'</a>' ); ?></p>
						</td>
					</tr>
					<tr>
						<td>
							<h3>Ouo.io <a href="https://ouo.io" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></h3>
							<div class="descr"></div>
						</td>
						<td>
							<input type="text" name="shortlink_ouo" class="regular-text" value="<?php echo appyn_options( 'shortlink_ouo', true ); ?>" placeholder="API Key...">
						</td>
					</tr>
					<tr>
						<td>
							<h3>ShrinkEarn.com <a href="https://shrinkearn.com" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></h3>
							<div class="descr"></div>
						</td>
						<td>
							<input type="text" name="shortlink_shrinkearn" class="regular-text" value="<?php echo appyn_options( 'shortlink_shrinkearn', true ); ?>" placeholder="API Key...">
						</td>
					</tr>
					<tr>
						<td>
							<h3>Shorte.st <a href="https://shorte.st" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></h3>
							<div class="descr"></div>
						</td>
						<td>
							<input type="text" name="shortlink_shorte" class="regular-text" value="<?php echo appyn_options( 'shortlink_shorte', true ); ?>" placeholder="API Key...">
						</td>
					</tr>
					<tr>
						<td>
							<h3>ClicksFly.com <a href="https://clicksfly.com" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></h3>
							<div class="descr"></div>
						</td>
						<td>
							<input type="text" name="shortlink_clicksfly" class="regular-text" value="<?php echo appyn_options( 'shortlink_clicksfly', true ); ?>" placeholder="API Key...">
						</td>
					</tr>
					<tr>
						<td>
							<h3>Oke.io <a href="https://oke.io" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></h3>
							<div class="descr"></div>
						</td>
						<td>
							<input type="text" name="shortlink_oke" class="regular-text" value="<?php echo appyn_options( 'shortlink_oke', true ); ?>" placeholder="API Key...">
						</td>
					</tr>
					<tr>
						<td>
							<h3><?php echo __( 'Show original download link', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Show the original download link instead of the shortened links', 'appyn' ); ?></div>
						</td>
						<td>
							<?php
							$shtlds = appyn_options( 'shortlink_disabled' );
							?>
							<p><label class="switch"><input type="checkbox" name="shortlink_disabled" value="1" <?php checked( $shtlds, 1 ); ?>><span class="swisr"></span></label></p>
						</td>
					</tr>
				</table>
			</div>

            
            <div class="section" data-section="footer">
				<h2>Footer</h2>
				
				<table class="table-main">
					<tr>
						<td><h3><?php echo __( 'Text Footer', 'appyn' ); ?></h3>
							<div class="descr"><?php echo __( 'Coloca cualquier texto en el footer. Permite HTML.', 'appyn' ); ?></div>
						</td>
						<td><textarea spellcheck="false" name="footer_texto" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_footer_texto' )); ?></textarea></td>
					</tr>
					<tr>
						<td><h3><?php echo __( 'Footer Codes', 'appyn' ); ?></h3></td>
						<td><textarea spellcheck="false" name="footer_codigos" class="widefat" rows="7"><?php echo stripslashes(get_option( 'appyn_footer_codigos' )); ?></textarea></td>
					</tr>
				</table>
    		</div>
            
            <div class="section" data-section="others">
				<h2><?php echo __( 'Info', 'appyn'); ?></h2>

				<?php 
				$auf = ini_get('allow_url_fopen'); 
				$met = ini_get('max_execution_time');
				$mit = ini_get('max_input_time'); 
				$mli = ini_get('memory_limit'); 
				$pms = ini_get('post_max_size'); 
				$umf = ini_get('upload_max_filesize'); 

				function sss($mli, $d) {
					
					if( $mli == -1 ) {
						return 999999;
					}
					if (preg_match('/^(\d+)(.)$/', $mli, $matches)) {
						if ($matches[2] == 'G') {
							$ml = $matches[1] * 1024 * 1024 * 1024;
						}
						else if ($matches[2] == 'M') {
							$ml = $matches[1] * 1024 * 1024;
						} else if ($matches[2] == 'K') {
							$ml = $matches[1] * 1024;
						}
					}

					return ($ml >= $d * 1024 * 1024);
				}
				?>
				
				<table class="table-main">
					<tr>
						<?php 
						$url = ( lang_wp() == "es" ) ? 'https://themespixel.net/aumentar-los-valores-de-configuracion-de-php/' : 'https://themespixel.net/en/increase-php-configuration-values/';
						?>
						<td colspan="100%"><?php echo __( 'These values ​​are important to be able to download or upload the heavy APK to your server or external servers.', 'appyn' ); ?> <a href="<?php echo $url; ?>" target="_blank"><?php echo __( '¿Cómo cambiar estos valores?', 'appyn' ); ?></a>
						</td>
					</tr>		
					<tr>
						<td>allow_url_fopen</td>
						<td><?php echo ($auf == 1) ? '<b style="color:#35c835;">'.__( 'Activated', 'appyn' ).'</b>' : '<b style="color:red;">'.__( 'Desactivated', 'appyn' ).'</b>'; ?></td>
					</tr>
					<tr>
						<td>max_execution_time</td>
						<td><?php echo ($met >= 300 || $met <= 0) ? '<b style="color:#35c835;">'.( ( $met <= 0 ) ? 'No limit' : $met ).'</b>' : '<b style="color:red;">'.$met.'</b> <em>/ '.__( 'Se recomienda mayor o igual a 300', 'appyn' ).'</em>'; ?></td>
					</tr>
					<tr>
						<td>max_input_time</td>
						<td><?php echo ($mit >= 300) ? '<b style="color:#35c835;">'.$mit.'</b>' : '<b style="color:red;">'.$mit.'</b> <em>/ '.__( 'Se recomienda mayor o igual a 300', 'appyn' ).'</em>'; ?></td>
					</tr>
					<tr>
						<td>memory_limit</td>
						<td><?php echo (sss($mli, 3000)) ? '<b style="color:#35c835;">'.( ( $mli <= 0 ) ? 'No limit' : $mli ).'</b>' : '<b style="color:red;">'.$mli.'</b> <em>/ '.__( 'Se recomienda mayor o igual a 4G', 'appyn' ).'</em>'; ?></td>
					</tr>
					<tr>
						<td>post_max_size</td>
						<td><?php echo (sss($pms, 3000)) ? '<b style="color:#35c835;">'.$pms.'</b>' : '<b style="color:red;">'.$pms.'</b> <em>/ '.__( 'Se recomienda mayor o igual a 4G', 'appyn' ).'</em>'; ?></td>
					</tr>
					<tr>
						<td>upload_max_filesize</td>
						<td><?php echo (sss($umf, 3000)) ? '<b style="color:#35c835;">'.$umf.'</b>' : '<b style="color:red;">'.$umf.'</b> <em>/ '.__( 'Se recomienda mayor o igual a 4G', 'appyn' ).'</em>'; ?></td>
					</tr>
				</table>
    		</div>
            
    	</form>
    </div>
</div>
<?php }

add_action( 'wp_ajax_px_panel_admin', 'px_panel_admin' ); 
add_action( 'wp_ajax_nopriv_px_panel_admin', 'px_panel_admin' );

function px_panel_admin() {
	global $wpdb;

	$nonce = sanitize_text_field( $_POST['nonce'] );

    if ( ! wp_verify_nonce( $nonce, 'admin_panel_nonce' ) ) die ( '✋');

	if( ! isset( $_POST['serializedData'] ) ) exit;

	parse_str($_POST['serializedData'], $output);

	$options = array(
		'logo',
		'favicon', 
		'titulo_principal',
		'descripcion_principal',
		'image_header1',
		'image_header2',
		'image_header3',
		'image_header4',
		'image_header5',
		'social_single_color', 
		'social_facebook', 
		'social_twitter',
		'social_instagram', 
		'social_youtube', 
		'social_pinterest',
		'social_telegram',
		'mas_calificadas',
		'mas_calificadas_limite',
		'home_limite',
		'home_posts_orden',
		'categories_home',
		'categories_home_limite',
		'comments',
		'readmore_single',
		'header_codigos',
		'header_codigos_amp',
		'analytics_amp',
		'body_codigos_amp',
		'download_links',		
		'download_links_permalinks',				
		'blog_posts_home_limite',
		'blog_posts_limite', 
		'blog_sidebar',
		'ads_text_above_1',           
		'ads_header_1',
		'ads_header_movil_1',
		'ads_header_amp_1',
		'ads_single_top_1',
		'ads_single_top_movil_1',
		'ads_single_top_amp_1',
		'ads_single_center_1',
		'ads_single_center_movil_1',	
		'ads_single_center_amp_1',	
		'ads_download_1_1',				
		'ads_download_2_1',	
		'ads_download_1_movil_1',				
		'ads_download_2_movil_1',	
		'ads_download_1_amp_1',
		'ads_download_u_1_1',
		'ads_download_u_2_1',
		'ads_download_u_3_1',
		'ads_download_u_1_movil_1',
		'ads_download_u_2_movil_1',
		'ads_download_u_3_movil_1',
		'ads_header_movil_2',
		'ads_text_above_2',           
		'ads_header_2',
		'ads_header_amp_2',
		'ads_single_top_2',
		'ads_single_top_movil_2',
		'ads_single_top_amp_2',
		'ads_single_center_2',
		'ads_single_center_movil_2',	
		'ads_single_center_amp_2',	
		'ads_download_1_2',				
		'ads_download_2_2',	
		'ads_download_1_movil_2',				
		'ads_download_2_movil_2',	
		'ads_download_1_amp_2',
		'ads_download_u_1_2',
		'ads_download_u_2_2',
		'ads_download_u_3_2',
		'ads_download_u_1_movil_2',
		'ads_download_u_2_movil_2',
		'ads_download_u_3_movil_2',
		'shortlink_shorte',
		'shortlink_shrinkearn',
		'shortlink_ouo',
		'shortlink_clicksfly',
		'shortlink_oke',
		'shortlink_disabled',
		'color_theme',
		'color_theme_user_select',
		'color_theme_principal',
		'color_download_button',
		'color_new_ribbon',
		'color_update_ribbon',
		'color_stars',
		'color_tag_mod',
		'sidebar_active',						
		'sidebar_ubicacion',			
		'footer_texto',
		'footer_codigos',
		'versiones_cantidad_post',
		'versiones_no_cajas',
		'orden_cajas',
		'orden_cajas_disabled',
		'version_download_link_direct',
		'version_history_noindex',
		'recaptcha_secret',
		'recaptcha_site',
		'recaptcha_v2_secret',
		'recaptcha_v2_site',
		'lazy_loading',
		'versiones_mostrar_inicio',
		'versiones_mostrar_inicio_categorias',
		'versiones_mostrar_inicio_apps_mas_calificadas',
		'versiones_mostrar_buscador',
		'versiones_mostrar_tax_developer',
		'versiones_mostrar_categorias',
		'versiones_mostrar_tags',
		'versiones_mostrar_widgets',
		'versiones_mostrar_amc',
		'versiones_mostrar_amv',
		'edcgp_post_status',
		'edcgp_create_category',
		'edcgp_create_tax_dev',
		'edcgp_extracted_images',
		'edcgp_sapk',
		'edcgp_sapk_server',
		'edcgp_sapk_shortlink',
		'edcgp_sapk_slug',
		'edcgp_rating',
		'edcgp_appd',
		'edcgp_lang',
		'edcgp_update_post',
		'eidcgp_update_post',
		'edcgp_mc',
		'edcgp_eaa',
		'mod_apps_roles',
		'mod_apps_post_status',
		'mod_apps_import_content',
		'mod_apps_sapk_shortlink',
		'mod_apps_data_gpl',
		'show_mod_apps_to_apps_to_update',
		'infinite_scroll',
		'dedcgp_descamp_actualizar',
		'edcgp_roles',
		'download_timer',
		'redirect_timer',
		'active_redirect',
		'design_timer',
		'pagina_interna_no_cajas',
		'single_hide_social_buttons',
		'single_show_telegram_button',
		'single_show_youtube_button',
		'style_info_app',
		'elements_info_app',
		'elements_info_app_disabled',
		'single_hide_short_description',
		'amp',
		'post_date',
		'post_date_type',
		'apps_related_type',
		'apikey',
		'home_hidden_posts',
		'home_hidden_blog',
		'home_sp_checked',
		'home_description_top',
		'home_description_bottom',
		'gdrive_client_id',
		'gdrive_client_secret',
		'gdrive_folder',
		'apps_info_download_apk',
		'apps_info_download_zip',
		'encrypt_links',
		'dropbox_app_key',
		'dropbox_app_secret',
		'request_email',
		'send_report_to_admin',
		'ftp_name_ip',
		'ftp_port',
		'ftp_username',
		'ftp_password',
		'ftp_directory',
		'ftp_url',
		'1fichier_apikey',
		'onedrive_client_id',
		'onedrive_client_secret',
		'onedrive_folder',
		'telegram_token',
		'telegram_chatid',
		'general_text_edit',
		'ribbon_update_post_modified',
		'download_links_design',
		'active_show_dl_recaptcha',
		'download_links_verified_by',
		'download_links_verified_by_p',
		'download_links_telegram_button_url',
		'download_links_telegram_button_text',
		'disabled_notif_apps_update',
		'sticky_header',
		'apps_per_row_pc',
		'apps_per_row_movil',
		'title_2_lines',
		'design_rounded',
		'automatic_results',
		'og_sidebar',
		'width_page',
		'view_apps',
		'bottom_menu',
		'search_google_active',
		'search_google_id',
	);

	foreach( $options as $key => $opt ) {

		if( ! in_array( $opt, $options) ) continue;

		if( $opt == "versiones_no_cajas" && empty( $output["versiones_no_cajas"] ) ) {
			delete_option( 'appyn_versiones_no_cajas' );
			continue;
		}
		if( $opt == "pagina_interna_no_cajas" && empty( $output["pagina_interna_no_cajas"] ) ) {
			delete_option( 'appyn_pagina_interna_no_cajas' );
			continue;
		}
		if( $opt == "categories_home" && empty( $output["categories_home"] ) ) {
			delete_option( 'appyn_categories_home' );
			continue;
		}


		
		if( $opt == "versiones_mostrar_inicio" ||
			$opt == "versiones_mostrar_inicio_categorias" ||
			$opt == "versiones_mostrar_inicio_apps_mas_calificadas" ||
			$opt == "versiones_mostrar_tax_developer" ||
			$opt == "versiones_mostrar_buscador" ||
			$opt == "versiones_mostrar_categorias" ||
			$opt == "versiones_mostrar_tags" ||
			$opt == "versiones_mostrar_widgets" || 
			$opt == "versiones_mostrar_amc" || 
			$opt == "versiones_mostrar_amv" || 
			$opt == "edcgp_create_tax_dev" ||
			$opt == "edcgp_sapk" ||
			$opt == "edcgp_create_category" ||
			$opt == "sidebar_active" ||
			$opt == "automatic_results" ||
			$opt == "sticky_header"
			) {
			if( ! isset( $output[$opt] ) ) {
				update_option( 'appyn_'.$opt, 1 );
			} else {
				update_option( 'appyn_'.$opt, stripslashes_deep($output[$opt]) );
			}
			continue;
		}

		if( !isset($output[$opt]) ) {
			delete_option( 'appyn_'.$opt );
			continue;
		}

		update_option( 'appyn_'.$opt, @stripslashes_deep($output[$opt]) );
	}

	flush_rewrite_rules();

	exit;
}

function px_screen_option() {

	$option = 'per_page';
	$args   = [
		'label'   => __( 'Número de elementos por página', 'appyn' ).': ',
		'default' => 20,
		'option'  => 'apps_to_update_per_page'
	];

	add_screen_option( $option, $args );
}

add_filter( 'set-screen-option', 'px_set_screen', 10, 3 );

function px_set_screen( $status, $option, $value ) {
	return $value;
}

function px_roles( $a ) {
	switch( $a ) {
		case 'administrator':
			$capability = 'manage_options';
			break;
		case 'editor':
			$capability = 'moderate_comments';
			break;
		case 'author':
			$capability = 'publish_posts';
			break;
		case 'contributor':
			$capability = 'read';
			break;
		default:
			$capability = 'manage_options';
			break;
	}
	return $capability;
}

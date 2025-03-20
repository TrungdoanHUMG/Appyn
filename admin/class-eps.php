<?php

if( ! defined( 'ABSPATH' ) ) die ( '✋' );

class EPS {
    
    public $post_id;
    public $url_app;
    public $nonce;
    private $bot_info;
    private $info_app;
    private $info;
    private $options;
    private $term_id;
    private $dca;
    private $output;

    public function __construct() {
        
        $this->dca = appyn_options( 'dedcgp_descamp_actualizar', true ) ? appyn_options( 'dedcgp_descamp_actualizar', true ) : array();
    }


    private function check_url_app() {

        if (empty($this->url_app) || 
        !preg_match('/https?:\/\/(www\.)?play\.google\.com/i', $this->url_app)){
            $output = array();
            $output['response'] = __( 'Lỗi: Sai đường dẫn, Vui lòng nhập URL Google Play', 'appyn' );        
            $output['error_field'] = 'consiguelo';
            die(json_encode($output));
        }
        if (empty($this->url_app)){
            $output = array();
            $output['response'] = __( 'Lỗi: Không có URL Playstore trong bài đăng này', 'appyn' );        
            $output['error_field'] = 'consiguelo';
            die(json_encode($output));
        }
    }

    private function check_if_exist_url() {

        if( ! get_http_response_code( $this->url_app ) ) {
            $output = array();
            $output['response'] = __( 'Lỗi: Có vẻ như URL không tồn tại. Vui lòng kiểm tra lại..', 'appyn' );
            $output['error_field'] = 'consiguelo';
            die(json_encode($output));
        }
    }

    private function getData($get_apk = true) {
        
        $this->options['edcgp_sapk'] = appyn_options( 'edcgp_sapk', true );
        
        $output = array();

        $parsed_url = parse_url($this->url_app);
        parse_str($parsed_url['query'], $query_params);
        $app_id = $query_params['id'] ?? null;
        $hour = intval( current_time( 'H' ) );
        if ( $hour >= 0 && $hour < 6 ) {
            // Từ 0h đến 6h sáng
            // Gọi đến API dự phòng
            $proxy_api_url = 'https://modgara.com/api/proxy/get-data/' . $app_id;
            $proxy_response = wp_remote_get( $proxy_api_url, array(
                'method'      => 'GET',
                'timeout' => 60,
            ) );

        } 
        
        $url = 'https://modgara.com/api/app_google_play/' . $app_id;

        $response = wp_remote_get( $url, array(
            'method'      => 'GET',
            'timeout' => 60,
        ) );
        
        
        if ( ! is_wp_error( $response ) ) {
            $bot = json_decode($response['body'], true);
            $this->bot_info = $bot;
            $proxy_url_apk = 'https://modgara.com/api/proxy/get-apk/' . $app_id;
            $price_string = $this->bot_info['app_price'];
            $price_number = floatval(str_replace('$', '', $price_string)); // 0.24
        
            // So sánh với 0
            if($price_number > 0){

            }
            else{
                $proxy_response_apk = wp_remote_post( $proxy_url_apk, array(
                    'method'      => 'GET',
                    'timeout'     => 60,
                ) );
            }

            if (empty($this->bot_info['app_version'])) {
                // Nếu app_version null, gọi đến API dự phòng và cập nhật bot_info
                $proxy_url = 'https://modgara.com/api/proxy/get-data/' . $app_id;
                $proxy_response = wp_remote_get($proxy_url, array(
                    'method' => 'GET',
                    'timeout' => 60,
                ));
                if (!is_wp_error($proxy_response)) {
                    $url = 'https://modgara.com/api/app_google_play/' . $app_id;
                    $response = wp_remote_post( $url, array(
                        'method'      => 'GET',
                        'timeout'     => 60,
                    ) );
                    $bot = json_decode($response['body'], true);
                } else {
                    $output['response'] = $proxy_response->get_error_message();
                    die(json_encode($output));
                }
            }
            if (empty($this->bot_info['app_version'])) {
                // Nếu app_version null, gọi đến API dự phòng và cập nhật bot_info
                $proxy_url = 'https://modgara.com/api/proxy/get-data/' . $app_id;
                $proxy_response = wp_remote_get($proxy_url, array(
                    'method' => 'GET',
                    'timeout' => 60,
                ));
                if (!is_wp_error($proxy_response)) {
                    $url = 'https://modgara.com/api/app_google_play/' . $app_id;
                    $response = wp_remote_post( $url, array(
                        'method'      => 'GET',
                        'timeout'     => 60,
                    ) );
                    $bot = json_decode($response['body'], true);
                } else {
                    $output['response'] = $proxy_response->get_error_message();
                    die(json_encode($output));
                }
            }

            if (empty($this->bot_info['app_size'])) {
                $url = 'https://modgara.com/api/app_google_play/' . $app_id;
                $response = wp_remote_get( $url, array(
                    'method'      => 'GET',
                    'timeout' => 60,
                ) );
                $bot = json_decode($response['body'], true);
                $this->bot_info = $bot;
            }

            
            return $bot;
        } else {
            $output['response'] = $response->get_error_message();
            die( json_encode($output) );
            return $bot;
        }
        
        
    }

    public function showData( $url ) {
        $this->url_app = $url;
        $bot = $this->getData(false);

        return $bot['app'];

    }

    
    private function import_process() {
   

        $bot = $this->getData();

     

        $this->bot_info = $bot;
        $parts = preg_split('/[.\n]/', $this->bot_info['app_description'], 2);


        $this->info_app = array();
        $this->info_app['nombre']                = $this->bot_info['app_title'];            // Tên ứng dụng
        $this->info_app['contenido']             = $parts[0];      // Nội dung ứng dụng
        $this->info_app['descripcion']           = $parts[0];      // Mô tả ứng dụng
        $this->info_app['fecha_actualizacion']    = $this->bot_info['app_updated_on'];      // Ngày cập nhật
        $this->info_app['released_on']           = $this->bot_info['app_released_on'];     // Ngày phát hành
        $this->info_app['last_update']           = $this->bot_info['app_updated_on'];      // Lần cập nhật cuối
        $this->info_app['version']               = $this->bot_info['app_version'];         // Phiên bản ứng dụng
        $this->info_app['requirements']        = $this->bot_info['app_requires_android'];// Yêu cầu Android
        $this->info_app['novedades']             = $this->bot_info['app_what_news'];       // Những cập nhật mới
        $this->info_app['imagecover']            = $this->bot_info['app_image'];           // Hình ảnh chính (cover)
        $this->info_app['video']                 = $this->bot_info['app_video'];                                     // Không có thông tin video trong dữ liệu cung cấp
        $this->info_app['tamano']                =$this->bot_info['app_size'];;                                     // Kích thước không được cung cấp
        $this->info_app['categoria']             = 'Games';                                // Ví dụ: thể loại "Games"
       
        $this->info_app['developer']             = $this->bot_info['app_dev'];             // Nhà phát triển
        $this->info_app['pago']                  = '';                                     // Thông tin trả phí không có trong dữ liệu
        $this->info_app['downloads']             = $this->bot_info['app_download'];                                     // Không có thông tin lượt tải
        $this->info_app['app_id']                = $this->bot_info['app_id'];    
        $this->options = array();
        $this->options['edcgp_post_status']     = appyn_options( 'edcgp_post_status' );
        $this->options['edcgp_create_category'] = appyn_options( 'edcgp_create_category' );
        $this->options['edcgp_create_tax_dev'] 	= appyn_options( 'edcgp_create_tax_dev' );
        $this->options['edcgp_extracted_images']= appyn_options( 'edcgp_extracted_images' );
        $this->options['edcgp_sapk']			= appyn_options( 'edcgp_sapk' );
        $this->options['edcgp_mc']              = appyn_options( 'edcgp_mc' );
        $this->options['edcgp_eaa']             = appyn_options( 'edcgp_eaa' );
   
    }

    public function createPost( $url_app ) {
        
        $this->url_app = trim($url_app);

        $this->check_url_app();
        
        $this->check_if_exist_url();

        $this->checkExists();

        $this->import_process();
                
        $my_post = array(
            'post_title'    => wp_strip_all_tags( $this->info_app['nombre'] ),
            'post_content'  => "",
            'post_author'   => get_current_user_id(),
        );

        if( $this->options['edcgp_post_status'] == 1 ) {
            $my_post['post_status'] = 'publish';
        } else {
            $my_post['post_status'] = 'draft';
        }


        $this->post_id = wp_insert_post( $my_post );

        if( $this->post_id ) {
            $this->output['post_id'] = $this->post_id;
            $this->info = __( 'Información importada.', 'appyn' )."\n";
            $this->output['info_text'] = '<i class="fa fa-check"></i> '.sprintf(__( 'Entry "%s" created.', 'appyn' ), $this->info_app['nombre']).' <a href="'.get_edit_post_link($this->post_id).'" target="_blank">'.__( 'See post', 'appyn' ).'</a>';
        }

        return $this->after_process( 'create' );
    }

    public function updatePost( $post_id ) {

        $this->post_id = $post_id;
                
        $this->url_app = trim(get_datos_info('consiguelo', false, $this->post_id));
        $this->check_url_app();
        
        $this->check_if_exist_url();
        
        $this->import_process();
        $post = get_post($post_id);
        $current_content = $post->post_content;

        $my_post = array(
            'ID'       		=> $this->post_id,
            'post_title'    => wp_strip_all_tags( $this->info_app['nombre'] ),
            'post_content' => $current_content, // Giữ nguyên nội dung hiện tại
            'post_author'   => get_current_user_id(),
        );
        $my_post['post_status'] = get_post_status($this->post_id);
        
        


        wp_update_post( $my_post, true );

        
        if( !is_wp_error($this->post_id) ) {
            $this->output['post_id'] = $this->post_id;
            $this->info = __( 'Información actualizada.', 'appyn' )."\n";
            $this->output['info_text'] = '<i class="fa fa-check"></i> '.sprintf(__( 'Entrada "%s" actualizada.', 'appyn' ), $this->info_app['nombre']);
        }

        return $this->after_process( 'update' );
        
    }
    
    private function after_process( $type = 'create' ) {

        update_post_meta( $this->post_id, "px_app_id", $this->info_app['app_id'] );
        update_post_meta( $this->post_id, "px_ggplay", true );

        
        if( $this->options['edcgp_create_tax_dev'] != 1 ) {
            $post_datos_informacion = str_replace(',', '', $this->info_app['developer']);
            wp_insert_term( $post_datos_informacion, 'dev' );
            $this->term_id = term_exists( $post_datos_informacion, 'dev' );
            wp_set_post_terms( $this->post_id, $post_datos_informacion, 'dev' );
        }
        
        if( $type == 'update' )
            if( ! in_array('app_ico', $this->dca) ) {
                $eidcgp = appyn_options( 'eidcgp_update_post' );
                if( $eidcgp == 1 ) {
                    $attachment_id = get_post_thumbnail_id( $this->post_id );
                    if( $attachment_id ) {
                        $attachment_id = get_post_thumbnail_id( $this->post_id );
                        wp_delete_attachment( $attachment_id, true );
                        delete_post_thumbnail( $this->post_id );
                    }
                }
                        
                if( $eidcgp == 1 ) {
                    global $post;
                    $ppt = new WP_Query( array('post_parent' => $this->post_id) );
                    if( $ppt->have_posts() ) {
                        while( $ppt->have_posts() ) { $ppt->the_post();
                            $attachment_id = get_post_thumbnail_id( $post->ID );
                            wp_delete_attachment( $attachment_id, true );
                            delete_post_thumbnail( $post->ID );
                            set_post_thumbnail( $post->ID, $attachment_id );
                        }
                    }
                }
                $attach_id = px_upload_image( $this->info_app, $this->post_id );
            }
        
        if( $type == 'create' )
            $attach_id = px_upload_image( $this->info_app, $this->post_id );

        $datos_informacion = array(
            'descripcion' 			=> $this->info_app['descripcion'],
            'version' 				=> $this->info_app['version'],
            'tamano' 				=> $this->info_app['tamano'],
            'fecha_actualizacion' 	=> $this->info_app['fecha_actualizacion'],
            'last_update'		 	=> $this->info_app['last_update'],
            'released_on' 	        => $this->info_app['released_on'],
            'requirements' 		=> $this->info_app['requirements'],
            'novedades' 			=> $this->info_app['novedades'],
            // 'app_status'	 		=> 'updated',
            'consiguelo' 			=> $this->url_app,
            'downloads'			    => $this->info_app['downloads'],
            'os'					=> 'ANDROID',
        );
    
   


        // if( $type == 'create' )
        //     $datos_informacion['app_status'] = 'new';
        $price_string = $this->bot_info['app_price']; // "$0.24"

        // Loại bỏ ký tự '$'
        $price_number = floatval(str_replace('$', '', $price_string)); // 0.24
        
        // So sánh với 0
        if($price_number > 0){
            $datos_informacion['offer']['price'] = 'pago';
            $datos_informacion['offer']['amount'] = $price_number;
        }
        $descargas = get_datos_info('downloads', false, $this->post_id);

        // Remove all '+' characters from the string
        $descargas = str_replace('+', '', $descargas);
        
        // Assign the sanitized value back to the array
        if( $type == 'update' && empty( $this->bot_info['app_download'] ) )
            $datos_informacion['downloads'] = $descargas;
        
        update_post_meta($this->post_id, "datos_informacion", $datos_informacion);

        $px_app_id = get_post_meta( $this->post_id, 'px_app_id', true );
        $datos_download = array(
            array(
                'link' => 'https://modgara.com/downloads/ggplay/'.$px_app_id.'.apk',
                'texto' => 'Download',
            ),
        );
        if($price_number > 0){

        }else{

            // Cập nhật giá trị datos_download vào custom field
            update_post_meta($this->post_id, 'datos_download', $datos_download);
        }

        if( $type == 'update' ) {
            if( ! in_array('app_video', $this->dca) )
                update_post_meta($this->post_id, "datos_video", array('id' => $this->info_app['video']));

        } else {
            update_post_meta($this->post_id, "datos_video", array('id' => $this->info_app['video']));

        }

        $app_images_array = json_decode($this->bot_info['app_images'], true);
        $n = 0;

        foreach($app_images_array as $screenshot) { 
            if( $n < $this->options['edcgp_extracted_images'] ) {
                $image_id = px_upload_image_by_url($screenshot, $this->post_id,true);
     
                $image_url = wp_get_attachment_url($image_id);
                if ($image_url) {
                    $image_urls[] = $image_url;
                }
            }
            $n++;
        }	
        if (!empty($image_urls)) {
            update_post_meta($this->post_id, 'datos_imagenes', $image_urls);
        }

        if( get_option( 'appyn_edcgp_rating' ) ) {
            
            $rating = $this->bot_info;
        
            $number_str = strtoupper(trim($rating['app_rank_number_of_vote']));
    
            // Loại bỏ từ 'REVIEWS' nếu có
            $number_str = str_replace('REVIEWS', '', $number_str);
            $number_str = trim($number_str);
            
            // Kiểm tra và chuyển đổi dựa trên ký hiệu
            if (strpos($number_str, 'K') !== false) {
                $ranking = (int)(floatval(str_replace('K', '', $number_str)) * 1000);
            } elseif (strpos($number_str, 'M') !== false) {
                $ranking = (int)(floatval(str_replace('M', '', $number_str)) * 1000000);
            } elseif (strpos($number_str, 'B') !== false) {
                $ranking = (int)(floatval(str_replace('B', '', $number_str)) * 1000000000);
            } else {
                // Nếu không có ký hiệu, chỉ chuyển đổi thành số nguyên
                $ranking = is_numeric($number_str) ? (int)$number_str : 0;
            }

            update_post_meta($this->post_id, "new_rating_users", $ranking);
            update_post_meta($this->post_id, "new_rating_count", ((isset($rating['app_rank_number_of_vote'])) ? $rating['app_rank_number_of_vote'] : ''));
            update_post_meta($this->post_id, 'new_rating_average', ((isset($rating['app_rank'])) ? $rating['app_rank'] : ''));
        }


        $this->output['response'] = $this->info;

        return json_encode($this->output);
    }


    private function checkExists() {
        global $wpdb;
        if( appyn_options('edcgp_appd') != 1 ) {
            $results = $wpdb->get_results("SELECT SQL_CALC_FOUND_ROWS  {$wpdb->prefix}posts.ID FROM {$wpdb->prefix}posts  INNER JOIN {$wpdb->prefix}postmeta ON ( {$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id ) WHERE 1=1  AND (( {$wpdb->prefix}postmeta.meta_key = 'datos_informacion' AND {$wpdb->prefix}postmeta.meta_value LIKE '%:\"{$this->url_app}\";%' )) AND {$wpdb->prefix}posts.post_type = 'post' AND (({$wpdb->prefix}posts.post_status = 'publish' OR {$wpdb->prefix}posts.post_status = 'future' OR {$wpdb->prefix}posts.post_status = 'draft')) GROUP BY {$wpdb->prefix}posts.ID ORDER BY {$wpdb->posts}.post_date DESC LIMIT 0, 10");
            
            if( count($results) != 0 )  {
                $output['response'] = sprintf(__( 'Lỗi: Ứng dụng bạn muốn nhập đã tồn tại. %s', 'appyn' ), '<a href="'.get_edit_post_link($results[0]->ID).'" target="_blank">'.__( 'See entry', 'appyn' ).'</a>');
                echo json_encode($output);
                exit;
            }
        }
    }
    private $allowed_domains = [
        'gamejolt.com',
        'malavida.com',
        'softonic.com',
        'mcead.com',
        'apkpure.com',
        'apkcombo.com',
        'uptodown.com'
    ];
    private function check_url_app_apk() {
        if ( empty( $this->url_app ) ) {
            $output = array();
            $output['response'] = __( 'Lỗi: URL không được để trống. Dừng thực thi.', 'appyn' );        
            $output['error_field'] = 'consiguelo';
            die(json_encode($output));
        }

        $contains_allowed_domain = false;

        foreach ( $this->allowed_domains as $domain ) {
            if ( strpos( $this->url_app, $domain ) !== false ) {
                $contains_allowed_domain = true;
                break; // Tìm thấy ít nhất một tên miền được phép, dừng vòng lặp
            }
        }

        if ( ! $contains_allowed_domain ) {
            $output = array();
            $output['response'] = __( 'Lỗi: URL không chứa bất kỳ tên miền được phép nào. Dừng thực thi.', 'appyn' );        
            $output['error_field'] = 'consiguelo';
            die(json_encode($output));
        }

    }
    
    private function check_if_exist_url_apk() {

        if( ! get_http_response_code( $this->url_app ) ) {
            $output = array();
            $output['response'] = __( 'Lỗi: Có vẻ như URL không tồn tại. Vui lòng kiểm tra lại.', 'appyn' );
            $output['error_field'] = 'consiguelo';
            die(json_encode($output));
        }
    }

    private function checkExistsApk() {
        global $wpdb;
        if( appyn_options('edcgp_appd') != 1 ) {
            $results = $wpdb->get_results("SELECT SQL_CALC_FOUND_ROWS  {$wpdb->prefix}posts.ID FROM {$wpdb->prefix}posts  INNER JOIN {$wpdb->prefix}postmeta ON ( {$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id ) WHERE 1=1  AND (( {$wpdb->prefix}postmeta.meta_key = 'datos_informacion' AND {$wpdb->prefix}postmeta.meta_value LIKE '%:\"{$this->url_app}\";%' )) AND {$wpdb->prefix}posts.post_type = 'post' AND (({$wpdb->prefix}posts.post_status = 'publish' OR {$wpdb->prefix}posts.post_status = 'future' OR {$wpdb->prefix}posts.post_status = 'draft')) GROUP BY {$wpdb->prefix}posts.ID ORDER BY {$wpdb->posts}.post_date DESC LIMIT 0, 10");
            
            if( count($results) != 0 )  {
                $output['response'] = sprintf(__( 'Lỗi: Ứng dụng bạn muốn nhập đã tồn tại. %s', 'appyn' ), '<a href="'.get_edit_post_link($results[0]->ID).'" target="_blank">'.__( 'See entry', 'appyn' ).'</a>');
                echo json_encode($output);
                exit;
            }
        }
    }

    private function getDataApk($get_apk = true) {
        
        $this->options['edcgp_sapk'] = appyn_options( 'edcgp_sapk', true );
        
        $output = array();


        $proxy_api_url = 'https://modgara.com/app2/add-data?url=' . $this->url_app;
        $proxy_response = wp_remote_get( $proxy_api_url, array(
            'method'      => 'GET',
            'timeout' => 60,
        ) );
        
        $proxy_api_url_apk = 'https://modgara.com/app2/scrape-download-links?url=' . $this->url_app;
        $proxy_response = wp_remote_get( $proxy_api_url_apk, array(
            'method'      => 'GET',
            'timeout'     => 180, // Increase timeout from 60 to 120 seconds or more
            ) );

        $url = 'https://modgara.com/app2/get-data?url=' . $this->url_app;

        $response = wp_remote_get( $url, array(
            'method'      => 'GET',
            'timeout' => 60,
        ) );
        
        
        if ( ! is_wp_error( $response ) ) {
            $bot = json_decode($response['body'], true);
            $this->bot_info = $bot;
            return $bot;
        } else {
            $output['response'] = $response->get_error_message();
            die( json_encode($output) );
            return $bot;
        }
        
        
    }

    private function import_process_apk() {
   

        $bot = $this->getDataApk();
     

        $this->bot_info = $bot;
        $parts = preg_split('/[.\n]/', $this->bot_info['app_description'], 2);


        $this->info_app = array();
        $this->info_app['nombre']                = $this->bot_info['app_title'];            // Tên ứng dụng
        $this->info_app['contenido']             = $this->bot_info['app_description'];       // Nội dung ứng dụng
        $this->info_app['descripcion']           = $this->bot_info['app_description'];       // Mô tả ứng dụng
        $this->info_app['fecha_actualizacion']    = $this->bot_info['app_updated_on'];      // Ngày cập nhật
        $this->info_app['released_on']           = $this->bot_info['app_released_on'];     // Ngày phát hành
        $this->info_app['last_update']           = $this->bot_info['app_updated_on'];      // Lần cập nhật cuối
        $this->info_app['version']               = $this->bot_info['app_version'];         // Phiên bản ứng dụng
        $this->info_app['requirements']        = $this->bot_info['app_requires_android'];// Yêu cầu Android
        $this->info_app['novedades']             = $this->bot_info['app_what_news'];       // Những cập nhật mới
        $this->info_app['imagecover']            = $this->bot_info['app_image'];           // Hình ảnh chính (cover)
        $this->info_app['video']                 = $this->bot_info['app_video'];                                     // Không có thông tin video trong dữ liệu cung cấp
        $this->info_app['tamano']                =$this->bot_info['app_size'];;                                     // Kích thước không được cung cấp
        $this->info_app['categoria']             = 'Games';                                // Ví dụ: thể loại "Games"
       
        $this->info_app['developer']             = $this->bot_info['app_dev'];             // Nhà phát triển
        $this->info_app['pago']                  = '';                                     // Thông tin trả phí không có trong dữ liệu
        $this->info_app['downloads']             = $this->bot_info['app_download'];                                     // Không có thông tin lượt tải
        $this->info_app['app_id']                = $this->bot_info['url'];    
        $this->options = array();
        $this->options['edcgp_post_status']     = appyn_options( 'edcgp_post_status' );
        $this->options['edcgp_create_category'] = appyn_options( 'edcgp_create_category' );
        $this->options['edcgp_create_tax_dev'] 	= appyn_options( 'edcgp_create_tax_dev' );
        $this->options['edcgp_extracted_images']= appyn_options( 'edcgp_extracted_images' );
        $this->options['edcgp_sapk']			= appyn_options( 'edcgp_sapk' );
        $this->options['edcgp_mc']              = appyn_options( 'edcgp_mc' );
        $this->options['edcgp_eaa']             = appyn_options( 'edcgp_eaa' );

    }
    private function after_process_apk( $type = 'create' ) {

        update_post_meta( $this->post_id, "px_app_id", $this->info_app['app_id'] );
        update_post_meta( $this->post_id, "px_ggplay", false );

        
        if( $this->options['edcgp_create_tax_dev'] != 1 ) {
            $post_datos_informacion = str_replace(',', '', $this->info_app['developer']);
            wp_insert_term( $post_datos_informacion, 'dev' );
            $this->term_id = term_exists( $post_datos_informacion, 'dev' );
            wp_set_post_terms( $this->post_id, $post_datos_informacion, 'dev' );
        }
        
        if( $type == 'update' )
            if( ! in_array('app_ico', $this->dca) ) {
                $eidcgp = appyn_options( 'eidcgp_update_post' );
                if( $eidcgp == 1 ) {
                    $attachment_id = get_post_thumbnail_id( $this->post_id );
                    if( $attachment_id ) {
                        $attachment_id = get_post_thumbnail_id( $this->post_id );
                        wp_delete_attachment( $attachment_id, true );
                        delete_post_thumbnail( $this->post_id );
                    }
                }
                        
                if( $eidcgp == 1 ) {
                    global $post;
                    $ppt = new WP_Query( array('post_parent' => $this->post_id) );
                    if( $ppt->have_posts() ) {
                        while( $ppt->have_posts() ) { $ppt->the_post();
                            $attachment_id = get_post_thumbnail_id( $post->ID );
                            wp_delete_attachment( $attachment_id, true );
                            delete_post_thumbnail( $post->ID );
                            set_post_thumbnail( $post->ID, $attachment_id );
                        }
                    }
                }
                $attach_id = px_upload_image( $this->info_app, $this->post_id );
            }
        
        if( $type == 'create' )
            $attach_id = px_upload_image( $this->info_app, $this->post_id );

        $datos_informacion = array(
            'descripcion' 			=> $this->info_app['descripcion'],
            'version' 				=> $this->info_app['version'],
            'tamano' 				=> $this->info_app['tamano'],
            'fecha_actualizacion' 	=> $this->info_app['fecha_actualizacion'],
            'last_update'		 	=> $this->info_app['last_update'],
            'released_on' 	        => $this->info_app['released_on'],
            'requirements' 		=> $this->info_app['requirements'],
            'novedades' 			=> $this->info_app['novedades'],
            // 'app_status'	 		=> 'updated',
            'consiguelo' 			=> $this->url_app,
            'downloads'			    => $this->info_app['downloads'],
            'os'					=> 'ANDROID',
        );
    



        // if( $type == 'create' )
        //     $datos_informacion['app_status'] = 'new';
        $price_string = $this->bot_info['app_price']; // "$0.24"

        // Loại bỏ ký tự '$'
        $price_number = floatval(str_replace('$', '', $price_string)); // 0.24
        
        // So sánh với 0
        if($price_number > 0){
            $datos_informacion['offer']['price'] = 'pago';
            $datos_informacion['offer']['amount'] = $price_number;
        }
        $descargas = get_datos_info('downloads', false, $this->post_id);

        // Remove all '+' characters from the string
        $descargas = str_replace('+', '', $descargas);
        
        // Assign the sanitized value back to the array
        if( $type == 'update' && empty( $this->bot_info['app_download'] ) )
            $datos_informacion['downloads'] = $descargas;
        
        update_post_meta($this->post_id, "datos_informacion", $datos_informacion);

        $px_app_id = get_post_meta( $this->post_id, 'px_app_id', true );
        $datos_download = array(
            array(
                'link' => 'https://modgara.com/app2/downloads?url='.$px_app_id,
                'texto' => 'Download',
            ),
        );
        update_post_meta($this->post_id, 'datos_download', $datos_download);


        if( $type == 'update' ) {
            if( ! in_array('app_video', $this->dca) )
                update_post_meta($this->post_id, "datos_video", array('id' => $this->info_app['video']));

        } else {
            update_post_meta($this->post_id, "datos_video", array('id' => $this->info_app['video']));

        }
        $app_images_array = json_decode($this->bot_info['app_images'], true);
        $n = 0;

        foreach($app_images_array as $screenshot) { 
            if( $n < $this->options['edcgp_extracted_images'] ) {
                $image_id = px_upload_image_by_url($screenshot, $this->post_id,true);
     
                $image_url = wp_get_attachment_url($image_id);
                if ($image_url) {
                    $image_urls[] = $image_url;
                }
            }
            $n++;
        }	
        if (!empty($image_urls)) {
            update_post_meta($this->post_id, 'datos_imagenes', $image_urls);
        }


        if( get_option( 'appyn_edcgp_rating' ) ) {
            
            $rating = $this->bot_info;
        
            $number_str = strtoupper(trim($rating['app_rank_number_of_vote']));
    
            // Loại bỏ từ 'REVIEWS' nếu có
            $number_str = str_replace('REVIEWS', '', $number_str);
            $number_str = trim($number_str);
            
            // Kiểm tra và chuyển đổi dựa trên ký hiệu
            if (strpos($number_str, 'K') !== false) {
                $ranking = (int)(floatval(str_replace('K', '', $number_str)) * 1000);
            } elseif (strpos($number_str, 'M') !== false) {
                $ranking = (int)(floatval(str_replace('M', '', $number_str)) * 1000000);
            } elseif (strpos($number_str, 'B') !== false) {
                $ranking = (int)(floatval(str_replace('B', '', $number_str)) * 1000000000);
            } else {
                // Nếu không có ký hiệu, chỉ chuyển đổi thành số nguyên
                $ranking = is_numeric($number_str) ? (int)$number_str : 0;
            }

            update_post_meta($this->post_id, "new_rating_users", $ranking);
            update_post_meta($this->post_id, "new_rating_count", ((isset($rating['app_rank_number_of_vote'])) ? $rating['app_rank_number_of_vote'] : ''));
            update_post_meta($this->post_id, 'new_rating_average', ((isset($rating['app_rank'])) ? $rating['app_rank'] : ''));
        }


        $this->output['response'] = $this->info;

        return json_encode($this->output);
    }
    public function createPostApk( $url_app ) {
        
        $this->url_app = trim($url_app);

        $this->check_url_app_apk();
        
        $this->check_if_exist_url_apk();

        $this->checkExistsApk();

        $this->import_process_apk();
                
        $my_post = array(
            'post_title'    => wp_strip_all_tags( $this->info_app['nombre'] ),
            'post_content'  => "",
            'post_author'   => get_current_user_id(),
        );

        if( $this->options['edcgp_post_status'] == 1 ) {
            $my_post['post_status'] = 'publish';
        } else {
            $my_post['post_status'] = 'draft';
        }


        $this->post_id = wp_insert_post( $my_post );

        if( $this->post_id ) {
            $this->output['post_id'] = $this->post_id;
            $this->info = __( 'Información importada.', 'appyn' )."\n";
            $this->output['info_text'] = '<i class="fa fa-check"></i> '.sprintf(__( 'Entry "%s" created.', 'appyn' ), $this->info_app['nombre']).' <a href="'.get_edit_post_link($this->post_id).'" target="_blank">'.__( 'See post', 'appyn' ).'</a>';
        }
        
        return $this->after_process_apk( 'create' );
    }
}
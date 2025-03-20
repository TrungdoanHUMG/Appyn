<?php

// 1. Register Custom Post Type
add_action('init', 'wsm_register_custom_post_type');
function wsm_register_custom_post_type() {
    $labels = array(
        'name'               => 'Web Scrapers',
        'singular_name'      => 'Web Scraper',
        'menu_name'          => 'Web Scrapers',
        'name_admin_bar'     => 'Web Scraper',
        'add_new'            => 'Thêm Mới',
        'add_new_item'       => 'Thêm Mới Web Scraper',
        'new_item'           => 'Web Scraper Mới',
        'edit_item'          => 'Chỉnh Sửa Web Scraper',
        'view_item'          => 'Xem Web Scraper',
        'all_items'          => 'Tất Cả Web Scrapers',
        'search_items'       => 'Tìm Kiếm Web Scrapers',
        'parent_item_colon'  => 'Web Scraper Cha:',
        'not_found'          => 'Không Tìm Thấy',
        'not_found_in_trash' => 'Không Tìm Thấy Trong Thùng Rác',
    );
	$args = array(
		'label'               => __( 'Web Scraper', 'text_domain' ),
		'description'         => __( 'Post type for web scraping data', 'text_domain' ),
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => 'appyn_panel', // Chỉ định menu chính 'appyn_panel'
		'capability_type'     => 'post',
		'hierarchical'        => false,
		'supports'            => array( 'title'),
		'has_archive'         => false,
		'rewrite'             => array( 'slug' => 'web-scraper' ),
	);

	register_post_type( 'web_scraper', $args );

}

// 2. Add Meta Boxes for CPT
add_action('add_meta_boxes', 'wsm_add_meta_boxes');
function wsm_add_meta_boxes() {
    add_meta_box(
        'wsm_meta_box',           // Meta Box ID
        'Web Scraper Settings',  // Meta Box Title
        'wsm_meta_box_callback',  // Callback function
        'web_scraper',            // Post type
        'normal',
        'high'
    );
}

// 3. Meta Box Callback with Updated Fields
function wsm_meta_box_callback($post) {
    // Use nonce for verification
    wp_nonce_field('wsm_save_meta_box_data', 'wsm_meta_box_nonce');

    // Retrieve existing data
    $url = get_post_meta($post->ID, '_wsm_url', true);
    $selectors = get_post_meta($post->ID, '_wsm_selectors', true);
    $selectors = !empty($selectors) ? $selectors : array();

    // Define selector types
    $selector_types = array(
        'post_title'        => 'Post Title',
        'image_update'      => 'Image',
        'version'           => 'Version',
        'download_count'    => 'Download Count',
        'developer'         => 'Developer',
        'requirements'      => 'Requirements',
        'short_description'     => 'Short Description',
        'list_image'       => 'List Image',
        'vote_number'   => 'Vote Number',
        'rank_vote'              => 'Rank Vote',
        'release_date'      => 'Release Date',
        'last_update'       => 'Last Update',
        'video'             => 'Video',
    );
    // Start form display
    echo '<table class="form-table"><tbody>';

    // URL Input Field
    echo '<tr aria-label="_test_url_post">
        <td>
            <label for="_test_url_post">Test Post URL</label>
            <div class="info-button"><span class="dashicons dashicons-info"></span></div>
            <div style="clear: both;"></div>
            <div class="info-text hidden">A full post URL to be used to perform the tests for post page CSS selectors.</div>
        </td>
        <td>
            <div class="input-group text">
                <div class="input-container">
                    <input type="url" id="_test_url_post" name="_wsm_url" value="' . esc_attr($url) . '" placeholder="A post URL that will be used for tests..." tabindex="0">
                </div>
            </div>
        </td>
    </tr>';

    // Loop through each selector type and create corresponding fields
    foreach ($selector_types as $type_key => $type_label) {
        echo '<tr aria-label="' . esc_attr($type_key) . '_selectors">
            <td>
                <label for="' . esc_attr($type_key) . '_selectors">' . esc_html($type_label) . ' Selectors</label>
                <div class="info-button"><span class="dashicons dashicons-info"></span></div>
                <div style="clear: both;"></div>
                <div class="info-text hidden">
                    CSS selectors cho ' . esc_html($type_label) . '. Ví dụ: <span class="highlight selector">h1</span>. Lấy văn bản của phần tử được chỉ định. Nếu bạn đưa nhiều selector, selector đầu tiên sẽ được sử dụng. Bạn có thể chọn \'text\', \'src\', hoặc \'href\' cho trường hành vi chọn lựa.
                </div>
            </td>
            <td>
                <div class="inputs" data-type="' . esc_attr($type_key) . '">';

        // Display existing selectors for this type
        if (!empty($selectors[$type_key])) {
            $index = 0;
            foreach ($selectors[$type_key] as $selector) {
                $url_value = isset($selector['url']) ? $selector['url'] : '';
                $selector_value = isset($selector['selector']) ? $selector['selector'] : '';
                $selection_behavior = isset($selector['selection_behavior']) ? $selector['selection_behavior'] : 'text';
                $target_html_tag = isset($selector['target_html_tag']) ? $selector['target_html_tag'] : '';

                echo '<div class="input-group selector-attribute addon dev-tools remove" data-key="' . $index . '">
                    <button type="button" class="button wpcc-button wcc-dev-tools" title="Visual Inspector">
                        <span class="dashicons dashicons-admin-tools"></span>
                    </button>
                    <div class="input-container">
                        <input type="text" name="_wsm_selectors[' . esc_attr($type_key) . '][' . $index . '][url]" placeholder="Url" value="' . esc_attr($url_value) . '">
                        <input type="text" name="_wsm_selectors[' . esc_attr($type_key) . '][' . $index . '][selector]" placeholder="Selector" value="' . esc_attr($selector_value) . '">
                        <input type="text" name="_wsm_selectors[' . esc_attr($type_key) . '][' . $index . '][target_html_tag]" class="target-html-tag" placeholder="Target tag..." title="Enter an HTML element tag name to restrict the selection with only elements having this tag name. E.g. img" tabindex="-1" value="' . esc_attr($target_html_tag) . '">
                        <select name="_wsm_selectors[' . esc_attr($type_key) . '][' . $index . '][selection_behavior]" title="Select the behavior of CSS selector finder" tabindex="-1">
                            <option value="text" ' . selected($selection_behavior, 'text', false) . '>Text</option>
                            <option value="src" ' . selected($selection_behavior, 'src', false) . '>Src</option>
                            <option value="href" ' . selected($selection_behavior, 'href', false) . '>Href</option>
                        </select>
                    </div>
                </div>';
                $index++;
            }
        } else {
            // If no selectors exist, display an empty input group
            echo '<div class="input-group selector-attribute addon dev-tools remove" data-key="0">
                <button type="button" class="button wpcc-button wcc-dev-tools" title="Visual Inspector">
                    <span class="dashicons dashicons-admin-tools"></span>
                </button>
                <div class="input-container">
                    <input type="text" name="_wsm_selectors[' . esc_attr($type_key) . '][0][url]" placeholder="Url" value="">
                    <input type="text" name="_wsm_selectors[' . esc_attr($type_key) . '][0][selector]" placeholder="Selector" value="">
                    <input type="text" name="_wsm_selectors[' . esc_attr($type_key) . '][0][target_html_tag]" class="target-html-tag" placeholder="Target tag..." title="Enter an HTML element tag name to restrict the selection with only elements having this tag name. E.g. img" tabindex="-1">
                    <select name="_wsm_selectors[' . esc_attr($type_key) . '][0][selection_behavior]" title="Select the behavior of CSS selector finder" tabindex="-1">
                        <option value="text">Text</option>
                        <option value="src">Src</option>
                        <option value="href">Href</option>
                    </select>
                </div>';
                if ($type_key === 'list_image'){
                    echo '    <button type="button" class="button wpcc-button wcc-remove" title="Remove">
                    <span class="dashicons dashicons-trash"></span>
                </button>';
                }
                echo'
            
            </div>';
        }

        echo '</div>
        <div style="clear: both;"></div>';

    // Add "Add New" button only for `list_image`
    if ($type_key === 'list_image') {
        echo '<div class="actions">
            <button type="button" class="button wpcc-button wcc-add-new" data-type="' . esc_attr($type_key) . '">Add New</button>
        </div>';
    }

    echo '<div id="wsm_test_result_' . esc_attr($type_key) . '"></div>
        </td>
    </tr>';
    }
    // Special handling for 'apk_file' type
    $apk_file_url = isset($selectors['apk_file_url']) ? $selectors['apk_file_url'] : '';
    echo '<tr>
        <td>
            <label for="apk_file_url">APK File URL</label>
        </td>
        <td>
            <input type="url" name="_wsm_selectors[apk_file_url]" id="apk_file_url" value="' . esc_attr($apk_file_url) . '" placeholder="Enter the APK file URL..." style="width: 100%;">
        </td>
    </tr>';
    echo '</tbody></table>';
}


function convertDownloads($number_downloads) {
    // 1. Chuẩn hóa chuỗi: loại bỏ khoảng trắng và chuyển thành chữ hoa
    $number_downloads = strtoupper(trim($number_downloads));

    // 2. Kiểm tra xem chuỗi có kết thúc bằng dấu '+' hay không
    $has_plus = false;
    if (substr($number_downloads, -1) === '+') {
        $has_plus = true;
        $number_downloads = substr($number_downloads, 0, -1);
    }

    // 3. Loại bỏ các ký tự không mong muốn (chỉ giữ lại số, dấu chấm, dấu phẩy và các ký tự K, M, B)
    $number_downloads = preg_replace('/[^\d.,KMB]/u', '', $number_downloads);

    // 4. Định nghĩa biểu thức chính quy để phân tích chuỗi
    $pattern = '/^([\d.,]+)([KMB])?$/';

    // 5. Áp dụng biểu thức chính quy và phân tích kết quả
    if (preg_match($pattern, $number_downloads, $matches)) {
        // $matches[1]: phần số
        // $matches[2]: phần chữ (K, M, B) nếu có

        $number = $matches[1];
        $suffix = isset($matches[2]) ? $matches[2] : '';

        // 6. Xác định và xử lý dấu chấm và dấu phẩy
        $has_dot = strpos($number, '.') !== false;
        $has_comma = strpos($number, ',') !== false;

        if ($has_dot && $has_comma) {
            // Nếu cả dấu chấm và dấu phẩy đều tồn tại, xác định dấu nào là dấu thập phân
            $last_dot = strrpos($number, '.');
            $last_comma = strrpos($number, ',');

            if ($last_dot > $last_comma) {
                // Dấu chấm là dấu thập phân, loại bỏ tất cả dấu phẩy
                $number = str_replace(',', '', $number);
            } else {
                // Dấu phẩy là dấu thập phân, loại bỏ tất cả dấu chấm và thay thế dấu phẩy bằng dấu chấm
                $number = str_replace('.', '', $number);
                $number = str_replace(',', '.', $number);
            }
        } elseif ($has_dot) {
            // Chỉ có dấu chấm, giả định là dấu thập phân, loại bỏ dấu phẩy
            $number = str_replace(',', '', $number);
        } elseif ($has_comma) {
            // Chỉ có dấu phẩy, giả định là dấu ngăn cách hàng nghìn, loại bỏ tất cả dấu phẩy
            $number = str_replace(',', '', $number);
        }

        // 7. Chuyển đổi phần số thành float
        $number = floatval($number);

        // 8. Xác định hệ số nhân dựa trên hậu tố
        switch ($suffix) {
            case 'K':
                $multiplier = 1000;
                break;
            case 'M':
                $multiplier = 1000000;
                break;
            case 'B':
                $multiplier = 1000000000;
                break;
            default:
                $multiplier = 1;
                break;
        }

        // 9. Tính toán số lượng tải về đầy đủ
        $downloads = $number * $multiplier;

        // 10. Định dạng số với dấu chấm làm ngăn cách hàng nghìn và không có chữ số thập phân
        $formatted_downloads = number_format($downloads, 0, ',', '.');

        // 11. Thêm dấu '+' nếu chuỗi đầu vào không có dấu '+'
        if (!$has_plus) {
            $formatted_downloads .= '+';
        }

        return $formatted_downloads;
    } else {
        // 12. Nếu không khớp với biểu thức chính quy, xử lý như một số nguyên với dấu chấm làm ngăn cách hàng nghìn
        // Loại bỏ tất cả ký tự không phải số
        $number_downloads = preg_replace('/[^\d]/', '', $number_downloads);

        // Chuyển đổi thành integer
        $downloads = intval($number_downloads);

        // Định dạng lại số với dấu chấm làm ngăn cách hàng nghìn
        $formatted_downloads = number_format($downloads, 0, ',', '.');

        // Thêm dấu '+' vì chuỗi đầu vào không có dấu '+'
        $formatted_downloads .= '+';

        return $formatted_downloads;
    }
}

function extract_vote_number_extended($vote_str) {
    // Chuyển đổi chuỗi về dạng chữ thường để dễ dàng xử lý, sử dụng mb_strtolower để hỗ trợ Unicode
    $vote_str = mb_strtolower($vote_str, 'UTF-8');

    // Sử dụng biểu thức chính quy để trích xuất số lượng và hậu từ
    // Thêm 'u' để hỗ trợ Unicode
    $pattern = '/(\d+(?:\.\d+)?)\s*([km])?\s*(reviews|đánh\s+giá)/u';

    if (preg_match($pattern, $vote_str, $matches)) {
        $number = floatval($matches[1]); // Lấy phần số (có thể có phần thập phân)

        // Kiểm tra xem có hậu từ 'k' hoặc 'm' không
        if (isset($matches[2])) {
            switch ($matches[2]) {
                case 'k':
                    $number *= 1000;
                    break;
                case 'm':
                    $number *= 1000000;
                    break;
                // Bạn có thể thêm các trường hợp khác nếu cần
            }
        }
        
        return intval($number); // Trả về số nguyên
    } else {
        // Nếu không khớp với bất kỳ mẫu nào, trả về 0 hoặc xử lý theo nhu cầu
        return 0;
    }
}


/**
 * Hàm xử lý giá trị rank_vote theo quy tắc:
 * - Nếu lớn hơn 10, chia cho 10.
 * - Nếu từ 5 đến 10, chia cho 2.
 * - Nếu nhỏ hơn 5, giữ nguyên.
 *
 * @param mixed $rank_vote Giá trị rank_vote cần xử lý.
 * @return float|int Giá trị đã được xử lý.
 */
function process_rank_vote($rank_vote) {
    // Kiểm tra xem rank_vote có phải là số hay không
    if (!is_numeric($rank_vote)) {
        // Nếu không phải số, bạn có thể xử lý theo cách khác hoặc trả về giá trị mặc định
        return 0;
    }

    // Chuyển đổi rank_vote thành kiểu số thực để xử lý chính xác
    $rank_vote = floatval($rank_vote);

    // Áp dụng quy tắc xử lý
    if ($rank_vote > 10) {
        $processed_vote = $rank_vote / 10;
    } elseif ($rank_vote >= 5) {
        $processed_vote = $rank_vote / 2;
    } else {
        $processed_vote = $rank_vote;
    }

    return $processed_vote;
}

// 4. Save Meta Box Data with Updated Fields
add_action('save_post_web_scraper', 'wsm_save_meta_box_data');
function wsm_save_meta_box_data($post_id) {
    // Verify nonce
    if (!isset($_POST['wsm_meta_box_nonce']) || !wp_verify_nonce($_POST['wsm_meta_box_nonce'], 'wsm_save_meta_box_data')) {
        return;
    }

    // Check for autosave or revision
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save URL
    if (isset($_POST['_wsm_url'])) {
        $url = sanitize_text_field($_POST['_wsm_url']);
        update_post_meta($post_id, '_wsm_url', $url);
    } else {
        // If no URL, use an empty string
        $url = '';
    }
    // Save Selectors
    $selectors_sanitized = array();
    if (isset($_POST['_wsm_selectors']) && is_array($_POST['_wsm_selectors'])) {
        $selectors = $_POST['_wsm_selectors'];

        foreach ($selectors as $type_key => $type_selectors) {
            if ($type_key === 'apk_file_url') {
                $apk_file_url = sanitize_text_field($type_selectors);
                $selectors_sanitized['apk_file_url'] = $apk_file_url;
            } else {
                if (is_array($type_selectors)) {
                    foreach ($type_selectors as $selector) {
                        if (!empty($selector['selector'])) {
                            $selectors_sanitized[$type_key][] = array(
                                'url'           => sanitize_text_field($selector['url']),
                                'selector'           => sanitize_text_field($selector['selector']),
                                'selection_behavior' => sanitize_text_field($selector['selection_behavior']),
                                'target_html_tag'    => sanitize_text_field($selector['target_html_tag']),
                            );
                        }
                    }
                }
            }
        }
    }
    update_post_meta($post_id, '_wsm_selectors', $selectors_sanitized);
    if (isset($_POST['wsm_meta_box_nonce']) || wp_verify_nonce($_POST['wsm_meta_box_nonce'], 'wsm_save_meta_box_data')) {
        // Post creation and update code goes here
        // Get existing created post ID
        $created_post_id = get_post_meta($post_id, '_wsm_created_post_id', true);

        // Get the post title from the selectors
        $post_title = '';
        if (!empty($selectors_sanitized['post_title'][0]['target_html_tag'])) {
            $post_title = sanitize_text_field($selectors_sanitized['post_title'][0]['target_html_tag']);
        }
    
        // If the post title is empty, log error and exit
        if (empty($post_title)) {
            set_transient('wsm_post_error', 'Web Scraper Plugin Error: No post title provided.', 30);
            return;
        }

        $info_app = array();

        // Check if 'image_update' exists and is not empty
        if (!empty($selectors_sanitized['image_update'][0]['target_html_tag'])) {
            if (strpos($selectors_sanitized['image_update'][0]['target_html_tag'], 'https://play-lh.googleusercontent.com/') !== false) { 
                // Tìm vị trí dấu '=' đầu tiên
                $pos = strpos($selectors_sanitized['image_update'][0]['target_html_tag'], '=');
        
                // Nếu tìm thấy dấu '=' thì cắt chuỗi tại vị trí đó và thêm `=s1000`
                if ($pos !== false) {
                    $selectors_sanitized['image_update'][0]['target_html_tag'] = substr($selectors_sanitized['image_update'][0]['target_html_tag'], 0, $pos) . '=s1000';
                } else {
                    // Nếu không có dấu '=', thêm `=s1000` vào cuối
                    $selectors_sanitized['image_update'][0]['target_html_tag'] .= '=s175';
                }
            
            }
            $info_app['imagecover'] = sanitize_text_field($selectors_sanitized['image_update'][0]['target_html_tag']);
        } else {
            set_transient('wsm_post_error', 'Web Scraper Plugin Error: No post image provided.', 30);
            return;
        }

        if (!empty($selectors_sanitized['version'][0]['target_html_tag'])) {
            $info_app['version'] = sanitize_text_field($selectors_sanitized['version'][0]['target_html_tag']);
        } else {
            set_transient('wsm_post_error', 'Web Scraper Plugin Error: No post version provided.', 30);
            return;
        }

        if (!empty($selectors_sanitized['download_count'][0]['target_html_tag'])) {
            $info_app['download_count'] = sanitize_text_field($selectors_sanitized['download_count'][0]['target_html_tag']);
        } else {
            set_transient('wsm_post_error', 'Web Scraper Plugin Error: No post download count provided.', 30);
            return;
        }
        if (!empty($selectors_sanitized['requirements'][0]['target_html_tag'])) {
            $info_app['requirements'] = sanitize_text_field($selectors_sanitized['requirements'][0]['target_html_tag']);
        }
        if (!empty($selectors_sanitized['short_description'][0]['target_html_tag'])) {
            $info_app['short_description'] = sanitize_text_field($selectors_sanitized['short_description'][0]['target_html_tag']);
        }

        if (!empty($selectors_sanitized['vote_number'][0]['target_html_tag'])) {
            $info_app['vote_number'] = sanitize_text_field($selectors_sanitized['vote_number'][0]['target_html_tag']);
        }

        if (!empty($selectors_sanitized['rank_vote'][0]['target_html_tag'])) {
            $info_app['rank_vote'] = sanitize_text_field($selectors_sanitized['rank_vote'][0]['target_html_tag']);
        }

        $info_app['nombre'] = $post_title; // Application name

        if (!empty($selectors_sanitized['list_image'])) {
            $app_images_array = array();
            foreach ($selectors_sanitized['list_image'] as $selector_data) {
                if (!empty($selector_data['target_html_tag'])) {
                    $app_images_array[] = $selector_data['target_html_tag'];
                }
            }
        }

        if (!empty($selectors_sanitized['release_date'][0]['target_html_tag'])) {
            $info_app['release_date'] = sanitize_text_field($selectors_sanitized['release_date'][0]['target_html_tag']);
        } 
    
        if (!empty($selectors_sanitized['last_update'][0]['target_html_tag'])) {
            $info_app['last_update'] = sanitize_text_field($selectors_sanitized['last_update'][0]['target_html_tag']);
        }
    
        if (!empty($selectors_sanitized['video'][0]['target_html_tag'])) {
            $info_app['video'] = esc_url_raw($selectors_sanitized['video'][0]['target_html_tag']);
        }
    
        if (!empty($selectors_sanitized['developer'][0]['target_html_tag'])) {
            $info_app['developer'] = sanitize_text_field($selectors_sanitized['developer'][0]['target_html_tag']);
        }
        else {
            set_transient('wsm_post_error', 'Web Scraper Plugin Error: No developer provided.', 30);
            return;
        }
        if (!empty($selectors_sanitized['apk_file_url'])) {
            $apk_file_url = sanitize_text_field($selectors_sanitized['apk_file_url']);
        }
        else {
            set_transient('wsm_post_error', 'Web Scraper Plugin Error: No url apk provided.', 30);
            return;
        }



       

        // Prepare the new post data
        $new_post = array(
            'post_title'   => $post_title,
            'post_content' => '', // Set this to scraped content if available
            'post_status'  => 'publish',
            'post_author'  => get_current_user_id(),
            'post_type'    => 'post', // Change to desired post type
        );

        if (empty($created_post_id)) {

            $downloads = convertDownloads($info_app['download_count']);
            $downloads = strval($downloads);
            if (substr($downloads, -1) !== '+') {
                $downloads .= '+';
            }
            $new_post_id = wp_insert_post($new_post);

            if (is_wp_error($new_post_id)) {
                var_dump('Web Scraper Plugin Error Creating Post: ' . $new_post_id->get_error_message());
            } else {
                // Save the created post ID in meta
                update_post_meta($post_id, '_wsm_created_post_id', $new_post_id);

                // Assuming $info_app contains the necessary data
                if (!empty($info_app['imagecover']) && !empty($info_app['nombre'])) {
                    px_upload_image($info_app, $new_post_id);
                } 
                
                $datos_informacion = array(
                    'version' 				=> $info_app['version'],
                    'app_status' 				=> 'new',
                    'downloads'			    => $downloads,
                    'requirements' 				=> $info_app['requirements'],
                    'fecha_actualizacion'     => $info_app['last_update'],
                    'descripcion' 				=> $info_app['short_description'],
                    'os'					=> 'ANDROID',
                    'released_on'    => $info_app['release_date'],
                    'last_update'     => $info_app['last_update'],
                );

                $ranking = extract_vote_number_extended($info_app['vote_number']);
                if (!empty($selectors_sanitized['list_image'])) {
                
                    // Now $app_images_array is an array of image URLs
                    $n = 0;
                    $max_images = 5; // Adjust as needed
                    $image_ids = array();
                
                    foreach ($app_images_array as $screenshot) {
                        if ($n < $max_images) {
                            $image_id = px_upload_image_by_url($screenshot, $new_post_id,false);
                            if ($image_id) {
                                $image_ids[] = $image_id;
                            }
                            $image_url = wp_get_attachment_url($image_id);
                            if ($image_url) {
                                $image_urls[] = $image_url;
                            }
                            
                        }
                        $n++;
                    }
                
                    // Save image IDs to post meta if needed
                    if (!empty($image_urls)) {
                        update_post_meta($new_post_id, 'datos_imagenes', $image_urls);
                    }
                }

                // 2.2 Handle Multiple 'dev' Taxonomy Terms
                if ( isset( $info_app['developer'] ) && ! empty( $info_app['developer'] ) ) {
                    // Tách các developer bằng dấu phẩy
                    $developers = explode( ',', $info_app['developer'] );
                    $developers = array_map( 'trim', $developers ); // Loại bỏ khoảng trắng thừa

                    $term_ids = array();

                    foreach ( $developers as $developer ) {
                        if ( ! empty( $developer ) ) {
                            // Loại bỏ dấu phẩy và trim tên developer
                            $developer_clean = str_replace( ',', '', $developer );
                            $developer_clean = trim( $developer_clean );

                            // Tạo term trong taxonomy 'dev'
                            $insert_term = wp_insert_term( $developer_clean, 'dev' );

                            // Kiểm tra xem term đã tồn tại hoặc được tạo thành công
                            if ( is_wp_error( $insert_term ) ) {
                                if ( $insert_term->get_error_code() == 'term_exists' ) {
                                    $term = term_exists( $developer_clean, 'dev' );
                                    if ( $term !== 0 && $term !== null ) {
                                        $term_ids[] = intval( $term['term_id'] );
                                    }
                                } else {
                                    // Nếu có lỗi khác, lưu lại thông báo lỗi và dừng quá trình
                                    set_transient( 'wsm_post_error', 'Web Scraper Plugin Error: ' . $insert_term->get_error_message(), 30 );
                                    return;
                                }
                            } else {
                                $term_ids[] = intval( $insert_term['term_id'] );
                            }
                        }
                    }

                    if ( ! empty( $term_ids ) ) {
                        // Gán các term cho post
                        wp_set_post_terms( $new_post_id, $term_ids, 'dev', false );
                    }
                }
                

                $datos_download = array(
                    array(
                        'link' => 'https://modgara.com/app2/downloads?url='.$apk_file_url,
                        'texto' => 'Download',
                    ),
                );
            
    
                update_post_meta($new_post_id, 'datos_download', $datos_download);

                update_post_meta($new_post_id, "datos_video", array('id' => $info_app['video']));
                update_post_meta($new_post_id, 'datos_informacion', $datos_informacion);
                update_post_meta($new_post_id, "new_rating_users", (int)$ranking);
                update_post_meta($new_post_id, "new_rating_count",  (int)$ranking);
                // Xử lý giá trị rank_vote
                $processed_rank_vote = isset($info_app['rank_vote']) ? process_rank_vote($info_app['rank_vote']) : '';
    
                // Cập nhật giá trị meta 'new_rating_average'
                update_post_meta($new_post_id, 'new_rating_average', $processed_rank_vote);
                  
            }
            set_transient('wsm_post_saved', $new_post_id, 30);
        } else {
            // Update existing post
            $new_post['ID'] = $created_post_id;
            $post = get_post($created_post_id);
            $current_content = $post->post_content;
            $new_post['post_content'] = $current_content;
            $new_post['post_author'] = get_current_user_id();

            $downloads = convertDownloads($info_app['download_count']);
            $downloads = strval($downloads);
            if (substr($downloads, -1) !== '+') {
                $downloads .= '+';
            }

            $new_post_id = wp_update_post($new_post);

            if (is_wp_error($new_post_id)) {
                error_log('Web Scraper Plugin Error Updating Post: ' . $new_post_id->get_error_message());
            } else {
                // Optionally, update additional scraped data as post meta
                $attachment_id = get_post_thumbnail_id( $new_post_id );
                if( $attachment_id ) {
                    $attachment_id = get_post_thumbnail_id( $new_post_id );
                    wp_delete_attachment( $attachment_id, true );
                    delete_post_thumbnail( $new_post_id );
                }
            // Assuming $info_app contains the necessary data
            if (!empty($info_app['imagecover']) && !empty($info_app['nombre'])) {
                px_upload_image($info_app, $new_post_id);
            } 

            $datos_informacion = array(
                'version' 				=> $info_app['version'],
                'descripcion' 				=> $info_app['short_description'],
                'app_status' 				=> 'updated',
                'downloads'			    => $downloads,
                'requirements' 				=> $info_app['requirements'],
                'os'					=> 'ANDROID',
                'released_on'    => $info_app['release_date'],
                'last_update'     => $info_app['last_update'],
                'fecha_actualizacion'     => $info_app['last_update'],

            );
            $ranking = extract_vote_number_extended($info_app['vote_number']);

            if (!empty($selectors_sanitized['list_image'])) {
                
                // Now $app_images_array is an array of image URLs
                $n = 0;
                $max_images = 5; // Adjust as needed
                $image_ids = array();
            
                foreach ($app_images_array as $screenshot) {
                    if ($n < $max_images) {
                        $image_id = px_upload_image_by_url($screenshot, $new_post_id,false);
                        if ($image_id) {
                            $image_ids[] = $image_id;
                        }
                        $image_url = wp_get_attachment_url($image_id);
                        if ($image_url) {
                            $image_urls[] = $image_url;
                        }
                        
                    }
                    $n++;
                }
            
                // Save image IDs to post meta if needed
                if (!empty($image_urls)) {
                    update_post_meta($new_post_id, 'datos_imagenes', $image_urls);
                }
            }
            // 2.2 Handle Multiple 'dev' Taxonomy Terms
            if ( isset( $info_app['developer'] ) && ! empty( $info_app['developer'] ) ) {
                // Tách các developer bằng dấu phẩy
                $developers = explode( ',', $info_app['developer'] );
                $developers = array_map( 'trim', $developers ); // Loại bỏ khoảng trắng thừa

                $term_ids = array();

                foreach ( $developers as $developer ) {
                    if ( ! empty( $developer ) ) {
                        // Loại bỏ dấu phẩy và trim tên developer
                        $developer_clean = str_replace( ',', '', $developer );
                        $developer_clean = trim( $developer_clean );

                        // Tạo term trong taxonomy 'dev'
                        $insert_term = wp_insert_term( $developer_clean, 'dev' );

                        // Kiểm tra xem term đã tồn tại hoặc được tạo thành công
                        if ( is_wp_error( $insert_term ) ) {
                            if ( $insert_term->get_error_code() == 'term_exists' ) {
                                $term = term_exists( $developer_clean, 'dev' );
                                if ( $term !== 0 && $term !== null ) {
                                    $term_ids[] = intval( $term['term_id'] );
                                }
                            } else {
                                // Nếu có lỗi khác, lưu lại thông báo lỗi và dừng quá trình
                                set_transient( 'wsm_post_error', 'Web Scraper Plugin Error: ' . $insert_term->get_error_message(), 30 );
                                return;
                            }
                        } else {
                            $term_ids[] = intval( $insert_term['term_id'] );
                        }
                    }
                }

                if ( ! empty( $term_ids ) ) {
                    // Gán các term cho post
                    wp_set_post_terms( $new_post_id, $term_ids, 'dev', false );
                }
            }

            $datos_download = array(
                array(
                    'link' => 'https://modgara.com/app2/downloads?url='.$apk_file_url,
                    'texto' => 'Download',
                ),
            );
        

            update_post_meta($new_post_id, 'datos_download', $datos_download);
            
            update_post_meta($new_post_id, "datos_video", array('id' => $info_app['video']));
            update_post_meta($new_post_id, 'datos_informacion', $datos_informacion);
            update_post_meta($new_post_id, "new_rating_users", (int)$ranking);
            update_post_meta($new_post_id, "new_rating_count",  (int)$ranking);
            // Xử lý giá trị rank_vote
            $processed_rank_vote = isset($info_app['rank_vote']) ? process_rank_vote($info_app['rank_vote']) : '';

            // Cập nhật giá trị meta 'new_rating_average'
            update_post_meta($new_post_id, 'new_rating_average', $processed_rank_vote);
            }
            set_transient('wsm_post_saved', $new_post_id, 30);
        }



    }



}

function processImageUrl($image_url) {
    // Xử lý URL từ miền 'img.utdstc.com'
    if (strpos($image_url, 'https://img.utdstc.com/') !== false) { 
        // Kiểm tra nếu URL không chứa dấu ':' hoặc không có gì sau dấu ':'
        // Đảm bảo không thêm ':800' nếu nó đã tồn tại hoặc có thêm phần sau dấu ':'
        if (strpos($image_url, ':') === false || substr($image_url, strrpos($image_url, ':') + 1) === '') {
            $image_url .= ':800';
        }
    }

    // Xử lý URL từ miền 'play-lh.googleusercontent.com'
    if (strpos($image_url, 'https://play-lh.googleusercontent.com/') !== false) { 
        // Tìm vị trí dấu '=' đầu tiên
        $pos = strpos($image_url, '=');

        // Nếu tìm thấy dấu '=' thì cắt chuỗi tại vị trí đó và thêm `=s1000`
        if ($pos !== false) {
            $image_url = substr($image_url, 0, $pos) . '=s1000';
        } else {
            // Nếu không có dấu '=', thêm `=s1000` vào cuối
            $image_url .= '=s1000';
        }
    
        return $image_url;
    }

    // Xử lý URL từ miền 'image.winudf.com'
    if (strpos($image_url, 'https://image.winudf.com/') !== false) { 
        // Loại bỏ các tham số truy vấn hiện tại
        $base_url = preg_replace('/\?.*$/', '', $image_url);
        
        // Thêm '?fakeurl=1' vào URL
        $image_url = $base_url . '?fakeurl=1';
    }

    return $image_url;
}
/**
 * Upload an image from a URL, resize it to have a height of 520 pixels while maintaining the aspect ratio (upscaling if necessary),
 * add a logo to the image, and attach it to a post.
 * Checks if an image with the same file name already exists and returns its ID if found.
 * If not, downloads, processes, and uploads the image.
 *
 * @param string $image_url The URL of the image to upload.
 * @param int    $post_id   The ID of the post to attach the image to.
 *
 * @return int|false The attachment ID on success, or false on failure.
 */
function px_upload_image_by_url($image_url, $post_id, $px_ggplay_value) {
    // Include WordPress file handling functions
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    
    // Đảm bảo hàm processImageUrl được định nghĩa
    if (!function_exists('processImageUrl')) {
        // Bạn cần định nghĩa hàm này hoặc đảm bảo nó được định nghĩa ở đâu đó
        function processImageUrl($url) {
            // Ví dụ xử lý URL
            return esc_url_raw($url);
        }
    }
    
    $image_url = processImageUrl($image_url); // Ensure this function is defined elsewhere
    
    // Parse the file name from the URL
    $parsed_url = parse_url($image_url);
    $file_name = basename($parsed_url['path']);

    // Ensure the file has an extension
    $pathinfo = pathinfo($file_name);
    if (empty($pathinfo['extension'])) {
        // Try to get the extension from mime type
        $temp_mime_type = wp_check_filetype($file_name);
        $extension = $temp_mime_type['ext'] ? $temp_mime_type['ext'] : 'jpg';
        $file_name .= '.' . $extension;
    }

    // Sanitize the file name for querying
    $sanitized_file_name = sanitize_file_name($file_name);

    // **Sử dụng meta_query để kiểm tra sự tồn tại của ảnh dựa trên URL nguồn**
    $existing_attachments = get_posts(array(
        'post_type'      => 'attachment',
        'meta_query'     => array(
            array(
                'key'     => '_px_image_source_url',
                'value'   => esc_url_raw($image_url),
                'compare' => '=',
            ),
        ),
        'posts_per_page' => 1,
        'post_status'    => 'inherit',
    ));

    if (!empty($existing_attachments)) {
        // Image with the same URL already exists, return the existing attachment ID
        return $existing_attachments[0]->ID;
    }

    // Download image to temporary location
    $temp_file = download_url($image_url);

    if (is_wp_error($temp_file)) {
        // If there was an error downloading the image, log the error and return false
        error_log('Error downloading image: ' . $temp_file->get_error_message());
        return false;
    }

    // Get the dimensions of the downloaded image
    $image_size = getimagesize($temp_file);

    if ($image_size === false) {
        // Not a valid image
        @unlink($temp_file);
        error_log('Invalid image file: ' . $image_url);
        return false;
    }

    $width = $image_size[0];
    $height = $image_size[1];

    // Generate a unique filename based on the URL to avoid conflicts
    $unique_prefix = md5($image_url);
    $file_name = $unique_prefix . '-' . $file_name;

    // Check the file type
    $wp_filetype = wp_check_filetype($file_name, null);

    // If the file type is not allowed, set a default type
    if (!$wp_filetype['type']) {
        $wp_filetype['type'] = 'image/jpeg';
    }

    // Prepare an array of file data suitable for wp_handle_sideload()
    $file = array(
        'name'     => $file_name,
        'type'     => $wp_filetype['type'],
        'tmp_name' => $temp_file,
        'error'    => 0,
        'size'     => filesize($temp_file),
    );

    // Set upload overrides
    $overrides = array(
        'test_form'   => false, // This is not from a form submission
        'test_size'   => true,
        'test_upload' => true,
    );

    // Handle the upload using WordPress's media sideloading function
    $results = wp_handle_sideload($file, $overrides);

    // Clean up the temporary file
    @unlink($temp_file);

    if (!empty($results['error'])) {
        // If there was an error during the upload, log the error and return false
        error_log('Error uploading image: ' . $results['error']);
        return false;
    } else {
        $image_path = $results['file'];
        if ($px_ggplay_value){
            // Logic khi $px_ggplay_value được thiết lập
            // Bạn cần điền vào đây nếu có yêu cầu cụ thể
        } else {
            // Resize the image to have a height of 520 pixels while maintaining aspect ratio (upscaling if necessary)

            // Create an instance of the image editor
            $image_editor = wp_get_image_editor($image_path);

            if (is_wp_error($image_editor)) {
                // If there was an error initializing the image editor, log the error and return false
                error_log('Error initializing image editor: ' . $image_editor->get_error_message());
                return false;
            } else {
                // Allow upscaling
                add_filter('image_resize_dimensions', 'px_allow_upscale', 10, 6);

                // Set the desired height
                $new_height = 520;

                // Calculate the new width to maintain aspect ratio
                $aspect_ratio = $width / $height;
                $new_width = intval($new_height * $aspect_ratio);

                // Resize the image
                $resize_result = $image_editor->resize($new_width, $new_height, false);

                if (is_wp_error($resize_result)) {
                    error_log('Error resizing image: ' . $resize_result->get_error_message());
                    remove_filter('image_resize_dimensions', 'px_allow_upscale', 10);
                    return false;
                }

                // Remove the upscaling filter
                remove_filter('image_resize_dimensions', 'px_allow_upscale', 10);

                // Save the resized image, overwriting the original
                $saved = $image_editor->save($image_path);

                if (is_wp_error($saved)) {
                    // If there was an error saving the image, log the error and return false
                    error_log('Error saving resized image: ' . $saved->get_error_message());
                    return false;
                }
            }
        }

        // *** Add the Logo (Watermark) ***
        // Path to the logo image (ensure this path is correct)
        $logo_path = 'https://apkmodsum.com/wp-content/uploads/2024/09/New-Project-e1725613800575.png'; // Update the path as needed

        $uploaded_image_type = wp_check_filetype($image_path);
        // Load the uploaded image
        switch ($uploaded_image_type['ext']) {
            case 'jpg':
            case 'jpeg':
                $image_resource = imagecreatefromjpeg($image_path);
                break;
            case 'png':
                $image_resource = imagecreatefrompng($image_path);
                break;
            case 'gif':
                $image_resource = imagecreatefromgif($image_path);
                break;
            default:
                error_log('Unsupported image type for watermarking: ' . $uploaded_image_type['ext']);
                $image_resource = false;
                break;
        }

        // Load the logo image
        $logo_resource = imagecreatefrompng($logo_path); // Assuming logo is a PNG with transparency

        if ($image_resource && $logo_resource) {
            // Get dimensions of both images
            $image_width = imagesx($image_resource);
            $image_height = imagesy($image_resource);

            $logo_width = imagesx($logo_resource);
            $logo_height = imagesy($logo_resource);

            // *** Tính Toán Kích Thước Mới Cho Logo ***
            // Đặt logo bằng 1/5 chiều rộng của hình ảnh nếu hình ngang, 1/2 nếu hình dọc
            $desired_logo_width = 0;
            if ($image_width > $image_height) {
                $desired_logo_width = intval($image_width / 5);
            } else {
                $desired_logo_width = intval($image_width / 2);
            }

            // Tính toán chiều cao mới để duy trì tỷ lệ khung hình của logo
            $logo_aspect_ratio = $logo_width / $logo_height;
            $desired_logo_height = intval($desired_logo_width / $logo_aspect_ratio);

            // Tạo một hình ảnh tạm thời để chứa logo đã thay đổi kích thước
            $resized_logo = imagecreatetruecolor($desired_logo_width, $desired_logo_height);

            // Đảm bảo giữ được độ trong suốt nếu logo là PNG
            imagesavealpha($resized_logo, true);
            $trans_colour = imagecolorallocatealpha($resized_logo, 0, 0, 0, 127);
            imagefill($resized_logo, 0, 0, $trans_colour);

            // Thay đổi kích thước logo
            imagecopyresampled(
                $resized_logo,    // Destination image
                $logo_resource,   // Source image
                0, 0,             // Destination x, y
                0, 0,             // Source x, y
                $desired_logo_width, $desired_logo_height, // Destination width, height
                $logo_width, $logo_height                    // Source width, height
            );

            // Lấy kích thước của logo đã thay đổi kích thước
            $final_logo_width = imagesx($resized_logo);
            $final_logo_height = imagesy($resized_logo);

            $margin = 10; // Khoảng cách từ cạnh (tùy chỉnh theo nhu cầu)
            $dest_x = intval($image_width - $final_logo_width - $margin);
            $dest_y = intval($margin);

            // Chèn logo vào hình ảnh
            imagecopy($image_resource, $resized_logo, $dest_x, $dest_y, 0, 0, $final_logo_width, $final_logo_height);

            // Save the merged image
            switch ($uploaded_image_type['ext']) {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($image_resource, $image_path, 90); // Adjust quality as needed
                    break;
                case 'png':
                    imagepng($image_resource, $image_path);
                    break;
                case 'gif':
                    imagegif($image_resource, $image_path);
                    break;
            }

            // Free up memory
            imagedestroy($image_resource);
            imagedestroy($logo_resource);
            imagedestroy($resized_logo);
        } else {
            error_log('Failed to load images for watermarking.');
        }

        // Prepare an array of post data for the attachment
        $attachment = array(
            'post_mime_type' => $results['type'],
            'post_title'     => sanitize_file_name($file_name),
            'post_content'   => '',
            'post_status'    => 'inherit',
        );

        // Insert the attachment into the WordPress media library
        $attach_id = wp_insert_attachment($attachment, $image_path, $post_id);

        if (is_wp_error($attach_id)) {
            error_log('Error inserting attachment: ' . $attach_id->get_error_message());
            return false;
        }

        // Generate metadata for the attachment and update the database record
        $attach_data = wp_generate_attachment_metadata($attach_id, $image_path);
        wp_update_attachment_metadata($attach_id, $attach_data);

        // Save the image source URL in post meta
        update_post_meta($attach_id, '_px_image_source_url', esc_url_raw($image_url));

        // Return the attachment ID
        return $attach_id;
    }
}


/**
 * Allow image upscaling.
 *
 * @param bool   $default         The default value.
 * @param int    $orig_w          Original width.
 * @param int    $orig_h          Original height.
 * @param int    $dest_w          New width.
 * @param int    $dest_h          New height.
 * @param bool   $crop            Whether to crop.
 *
 * @return array|bool New dimensions array or false.
 */
function px_allow_upscale($default, $orig_w, $orig_h, $dest_w, $dest_h, $crop) {
    if (!$crop) {
        // Non-cropped images are always scaled
        $aspect_ratio = $orig_w / $orig_h;
        $new_w = $dest_w;
        $new_h = $dest_h;

        if (!$new_w) {
            $new_w = intval($new_h * $aspect_ratio);
        }

        if (!$new_h) {
            $new_h = intval($new_w / $aspect_ratio);
        }

        return array(0, 0, 0, 0, $new_w, $new_h, $orig_w, $orig_h);
    }

    return $default;
}



add_action('admin_notices', 'wsm_admin_error_notice');
function wsm_admin_error_notice() {
    if ($error_message = get_transient('wsm_post_error')) {
        echo '<div class="notice notice-error is-dismissible">
            <p>' . esc_html($error_message) . '</p>
        </div>';
        delete_transient('wsm_post_error');
    }
}

add_action('admin_notices', 'wsm_admin_notice_code');

function wsm_admin_notice_code() {
    if ($data = get_transient('wsm_post_saved')) {
        $new_post_id = $data;
        $view_link = get_permalink($new_post_id); // Lấy liên kết xem bài viết

        echo '<div class="notice notice-success is-dismissible">
            <p>Bài viết <a href="' . esc_url($view_link) . '" target="_blank">"View Post"</a> đã được lưu thành công.</p>
        </div>';
        delete_transient('wsm_post_saved');
    }
}
// 5. Enqueue Scripts
add_action('admin_enqueue_scripts', 'wsm_enqueue_scripts');
function wsm_enqueue_scripts($hook) {
    global $post_type;
    if (($hook == 'post-new.php' || $hook == 'post.php') && $post_type == 'web_scraper') {
        // Enqueue jQuery UI Sortable
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_style('dashicons');

        // Enqueue Font Awesome
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');

        // Add inline JavaScript and CSS
        add_action('admin_footer', 'wsm_inline_js');

        // Localize script with AJAX URL and Node.js server URL
        wp_localize_script('jquery', 'wsmAjax', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nodeServerUrl' => 'https://modgara.com/app2', // Change if necessary
        ));
    }
}

// 6. Inline JavaScript and CSS with Updated Fields
function wsm_inline_js() {
    global $post_type;
    if ($post_type == 'web_scraper') {
        ?>
        <style>
            .form-table td{
                margin-bottom: 9px;
                padding: 10px 6px;
                line-height: 1.3;
                vertical-align: middle;
                width: 100px;
            }
            /* Existing CSS Styles */
            .form-table th {
                padding: 10px 0;
            }
            .input-group {
                margin-bottom: 10px;
            }
            .input-container {
                display: flex;
                gap: 15px;
                align-items: center;
                width: 100%;
            }
            .input-container input, 
            .input-container select {
                flex: 1 1 200px;
                min-width: 150px;    
                padding: 0px 8px;
                line-height: 2;
                min-height: 38px;
            }
            .button.wpcc-button {
                margin-right: 5px;
                display: flex;
                align-content: center;
                justify-content: center;
                align-items: center;
            }
            .selector-attribute {
                margin-bottom: 8px;
                clear: both;
                display: flex;
                gap: 3px;
            }
            .wcc-sort {
                cursor: move;
            }
            /* CSS for modal */
            .wsm-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                display: none;
                z-index: 9999; /* Highest level */
            }
            .wsm-modal-content {
                width: 95vw;
                height: 90vh;
                margin: auto;
                background: #fff;
                position: relative;
                top: 50%;
                transform: translateY(-50%);
                display: flex;
                flex-direction: column;
                box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            }
            .wsm-modal-header {
                padding: 10px;
                background: #f1f1f1;
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-direction: row;
                flex-wrap: wrap;
            }
            .wsm-modal-close {
                cursor: pointer;
                font-size: 24px;    
                display: contents;
            }
            .wsm-modal-body {
                flex: 1;
                position: relative;
            }
            .wsm-modal-footer {
                padding: 10px;
                background: #f1f1f1;
                text-align: right;
            }
            .wsm-modal iframe {
                width: 100%;
                height: 100%;
                border: none;
            }
            .wsm-modal-url-input {
                width: calc(100% - 220px);
                margin-right: 10px;
            }
            .wsm-mode-toggle {
                margin-left: 10px;
            }
            .info-button {
                display: inline-block;
                margin-left: 5px;
                cursor: pointer;
            }
            .info-text {
                display: none;
                font-size: 12px;
                color: #666;
            }
            .info-button:hover + .info-text {
                display: block;
                position: absolute;
                background: #fff;
                border: 1px solid #ddd;
                padding: 5px;
                z-index: 10000;
            }
            /* Additional CSS for better layout */
            .css-selector-tools,  .address-bar {
                display: flex;
                margin-bottom: 4px;
                margin-top: 4px;
                gap: 4px;
                padding: 0 5px;
            }

            .css-selector-tools .toolbar-input-container, .address-bar .toolbar-input-container {
                display: flex;
                flex-grow: 1;
            }

            .css-selector-tools .toolbar-input-container .input-container, .address-bar .toolbar-input-container .input-container {
                flex-grow: 1;
                display: flex;
                align-items: center;
            }

            .css-selector-tools .toolbar-input-container input, .address-bar .toolbar-input-container input {
                width: 100%;
            }

            @media screen and (max-width: 783px) {
                 .css-selector-tools .toolbar-input-container, .address-bar .toolbar-input-container {
                    width:calc(100% - 262px)
                }
            }

            .css-selector-tools .button-container, .address-bar .button-container {
                display: flex;
                gap: 4px;
                justify-content: end;
            }

            .css-selector-tools input {
                font-size: 18px;
                margin: 0;
            }

            .css-selector-tools button.css-selector-use {
                border: 2px solid #7CB342;
                margin-left: 5px;
                height: 40px;
            }

            .address-bar {
                background: #f2f2f2;
                border-bottom-left-radius: 2px;
                border-bottom-right-radius: 2px;
                padding: 5px 10px 2px 10px;
                border-bottom: 1px solid #e2e2e2;
            }

            .address-bar .button-container {
                padding-top: 2px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 4px;
            }

            .address-bar .toolbar-input-container {
                flex-grow: 1;
            }

            .address-bar input {
                transition: all 0.3s;
                -webkit-transition: all 0.3s;
            }

            .address-bar input.loading {
                background: #C5E1A5;
            }

            .address-bar .button-option {
                font-size: 14px;
            }

            @media screen and (max-width: 783px) {
                 .address-bar .button-option {
                    font-size:18px;
                    padding: 0;
                    margin: 10px 3px;
                }
            }

            .address-bar .button-option:hover {
                cursor: pointer;
            }

            .address-bar .button-option.disabled {
                color: #ccc;
            }

            .address-bar .button-option.disabled:hover {
                cursor: default;
            }

            .options {
                display: block;
                margin-top: 4px;
                overflow: hidden;
                padding: 0 10px;
                font-size: 11px;
            }

            .options input, .options select {
                font-size: 11px;
            }

            .options label {
                display: inline-block;
            }

            .options .dashicons {
                font-size: 14px;
                width: 14px;
                height: 14px;
            }

            .options .button-option {
                margin-top: 2px;
            }

            .options .button-option .dashicons {
                margin: 0 3px;
            }

            .options .button-option:hover {
                cursor: pointer;
            }

            .options .button-option.active {
                border-color: #ccc;
            }

            .options .left {
                float: left;
            }

            .options .right {
                float: right;
            }

            .options .target-html-tag {
                width: 100px;
            }

            .options label[for="test_button_behavior"] {
                margin-top: 2px;
            }

            .dashicons.active {
                color: #7CB342;
            }

            button.active span {
                color: #23282d;
            }

            .test-results {
                margin: 10px;
            }

            .separator {
                background: #e6e6e6;
                clear: both;
            }

            .separator.vertical {
                display: inline-block;
                padding: 0 1px 20px 0;
                margin-bottom: -7px;
                margin-left: 1px;
                margin-right: 1px;
            }

            a[role="button"]:hover {
                cursor: pointer;
            }

            .selected-elements {
                display: inline-block;
            }

            .selected-elements .clear-selections {
                margin-left: 4px;
            }

            .dev-tools-content iframe {
                width: 100%;
            }

            .dev-tools-content .sidebar {
                width: 300px;
                position: absolute;
                right: -305px;
                top: 0;
                bottom: 0;
                background: #fff;
                overflow-y: scroll;
                -webkit-box-shadow: -4px 0px 11px -3px rgba(0,0,0,0.5);
                -moz-box-shadow: -4px 0px 11px -3px rgba(0,0,0,0.5);
                box-shadow: -4px 0px 11px -3px rgba(0,0,0,0.5);
                transition: all 0.3s;
                -webkit-transition: all 0.3s;
            }

            .dev-tools-content .sidebar.opened {
                right: 0;
            }

            .dev-tools-content .sidebar .sidebar-section .section-title {
                overflow: auto;
                padding: 5px 10px;
                background: #F5F5F5;
                border-top: 1px solid #E0E0E0;
                border-bottom: 1px solid #E0E0E0;
            }

            .dev-tools-content .sidebar .sidebar-section .section-title span {
                float: left;
                font-weight: 300;
            }

            .dev-tools-content .sidebar .sidebar-section .section-title span:hover {
                cursor: pointer;
            }

            .dev-tools-content .sidebar .sidebar-section .section-title .section-controls {
                float: right;
                margin-right: 20px;
            }

            .dev-tools-content .sidebar .sidebar-section .section-title .section-controls .section-title-button {
                font-size: 16px;
                padding-top: 1px;
            }

            .dev-tools-content .sidebar .sidebar-section .section-title .section-controls .section-title-button:hover {
                cursor: pointer;
            }

            .dev-tools-content .sidebar .sidebar-section:not(.expanded) .section-content {
                display: none;
            }

            .dev-tools-content .sidebar .sidebar-section .section-content {
                padding: 5px 10px;
                overflow-y: scroll;
            }

            .dev-tools-content .sidebar .sidebar-section .section-content ul {
                margin: 0;
            }

            .dev-tools-content .sidebar .sidebar-section.used-selectors .section-content ul,
            .dev-tools-content .sidebar .sidebar-section.alternative-selectors .section-content ul,
            .dev-tools-content .sidebar .sidebar-section.history .section-content ul {
                margin-left: 20px;
            }

            .dev-tools-content .sidebar .sidebar-section.used-selectors .section-content ul li,
            .dev-tools-content .sidebar .sidebar-section.alternative-selectors .section-content ul li,
            .dev-tools-content .sidebar .sidebar-section.history .section-content ul li {
                color: #ccc;
                list-style-type: decimal;
            }

            .dev-tools-content .sidebar .sidebar-section.used-selectors .section-content ul li.active,
            .dev-tools-content .sidebar .sidebar-section.alternative-selectors .section-content ul li.active,
            .dev-tools-content .sidebar .sidebar-section.history .section-content ul li.active {
                color: #7CB342;
            }

            .dev-tools-content .sidebar .sidebar-section.used-selectors .section-content ul li .selector,
            .dev-tools-content .sidebar .sidebar-section.used-selectors .section-content ul li .url,
            .dev-tools-content .sidebar .sidebar-section.alternative-selectors .section-content ul li .selector,
            .dev-tools-content .sidebar .sidebar-section.alternative-selectors .section-content ul li .url,
            .dev-tools-content .sidebar .sidebar-section.history .section-content ul li .selector,
            .dev-tools-content .sidebar .sidebar-section.history .section-content ul li .url {
                color: #000;
            }

            .dev-tools-content .sidebar .sidebar-section.used-selectors .section-content ul li .selector:hover,
            .dev-tools-content .sidebar .sidebar-section.used-selectors .section-content ul li .url:hover,
            .dev-tools-content .sidebar .sidebar-section.alternative-selectors .section-content ul li .selector:hover,
            .dev-tools-content .sidebar .sidebar-section.alternative-selectors .section-content ul li .url:hover,
            .dev-tools-content .sidebar .sidebar-section.history .section-content ul li .selector:hover,
            .dev-tools-content .sidebar .sidebar-section.history .section-content ul li .url:hover {
                text-decoration: underline;
                cursor: pointer;
            }

            .dev-tools-content .sidebar .sidebar-section.used-selectors .section-content ul li .selector .count,
            .dev-tools-content .sidebar .sidebar-section.used-selectors .section-content ul li .url .count,
            .dev-tools-content .sidebar .sidebar-section.alternative-selectors .section-content ul li .selector .count,
            .dev-tools-content .sidebar .sidebar-section.alternative-selectors .section-content ul li .url .count,
            .dev-tools-content .sidebar .sidebar-section.history .section-content ul li .selector .count,
            .dev-tools-content .sidebar .sidebar-section.history .section-content ul li .url .count {
                padding-left: 3px;
                font-style: initial;
                color: #ff4400;
            }

            .dev-tools-content .sidebar .sidebar-section.used-selectors .section-content ul li .selector .count {
                color: #7CB342;
            }

            .dev-tools-content .sidebar .sidebar-close {
                position: absolute;
                right: 8px;
            }

            .dev-tools-content .sidebar .sidebar-close:hover {
                cursor: pointer;
            }

            .dev-tools-content .iframe-status {
                position: absolute;
                left: 0;
                bottom: 0;
                background: #f2f2f2;
                font-size: 11px;
                padding: 2px 8px 1px 4px;
                border: 1px solid #b3b3b3;
                border-top-right-radius: 4px;
            }

            .dev-tools-content>:not(iframe) .button span {
                pointer-events: none;
            }

            .wsm-overlay { 
                position: absolute; 
                top: 0; 
                left: 0; 
                width: 100%; 
                height: 100%; 
                background: rgba(255, 0, 0, 0.1); 
                pointer-events: none; 
                display: none; 
            }

            .fa-fw {
                text-align: center;
                font-size: 1.75em;
            }
        </style>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Append the Visual Inspector Modal
            $('body').append(`
                <div class="wsm-modal" id="wsm-visual-inspector-modal">
                    <div class="wsm-modal-content">
                        <div class="wsm-modal-header">
                            <div style="flex-grow: 1;display: flex;align-items: center;justify-content: space-evenly;">
                                <input type="text" id="wsm-modal-url-input" class="wsm-modal-url-input" placeholder="Enter URL..." />
                                <i class="fas fa-play-circle fa-fw button-option go" id="wsm-modal-load-url" title="Click to go to the URL"></i>
                                <button type="button" class="button wsm-mode-toggle" id="wsm-mode-selector">Pointer Mode</button>
                            </div>
                            <span class="wsm-modal-close">&times;</span>
                        </div>
                        <div class="css-selector-tools">
                            <div class="button-container">
                                <button class="button wpcc-button css-selector-use" type="button" title="Use the selector">
                                    <span class="dashicons dashicons-yes"></span>
                                </button>                
                            </div>
                            <div class="input-group text toolbar-input-container css-selector-input ">
                                <div class="input-container">
                                    <input type="text" id="_wsm_toolbar_css_selector" name="_wsm_selectors[currentIndex][selector]" value="" placeholder="CSS selector..." tabindex="-1">
                                    <!-- Add Target HTML Tag Field -->
                                        <input type="text" name="_wsm_selectors[currentIndex][target_html_tag]" class="target-html-tag" placeholder="Target tag..." title="Enter an HTML element tag name to restrict the selection with only elements having this tag name. E.g. img" tabindex="-1">
                    
                                    <!-- Add Selection Behavior Field -->
                                    <select name="_wsm_selectors[currentIndex][selection_behavior]" title="Select the behavior of CSS selector finder" tabindex="-1">
                                        <option value="text">Text</option>
                                        <option value="src">Src</option>
                                        <option value="href">Href</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="wsm-modal-body">
                            <iframe id="wsm-visual-inspector-frame"></iframe>
                        </div>
                    </div>
                </div>
            `);

            // Variable to track mode
            var wsmMode = 'pointer'; // or 'selector'

            // Function to reset mode
            function resetMode() {
                wsmMode = 'pointer';
                $('#wsm-mode-selector').text('Pointer Mode');
                $('.wcc-dev-tools-active').removeClass('wcc-dev-tools-active');
                $('.css-selector-tools-active').removeClass('css-selector-tools-active');
            }

            // Close modal event
            $('.wsm-modal-close').on('click', function() {
                $('#wsm-visual-inspector-modal').hide();
                resetMode();
            });
            $('.css-selector-use').on('click', function() {
                $('#wsm-visual-inspector-modal').hide();
                resetMode();
            });
            // Open Visual Inspector when clicking "Visual Inspector" button
            $(document).on('click', '.wcc-dev-tools', function(e) {
                e.preventDefault();
                var url = $('#_test_url_post').val();
                if (!url) {
                    // If no URL, display modal with blank page
                    $('#wsm-modal-url-input').val('');
                    $('#wsm-visual-inspector-frame').attr('src', 'about:blank');
                } else {
                    $('#wsm-modal-url-input').val(url);
                    $('#wsm-visual-inspector-frame').attr('src', wsmAjax.nodeServerUrl + '/interaction-inspector?url=' + encodeURIComponent(url));
                }
                // Show modal
                $('#wsm-visual-inspector-modal').show();
                // Add active class to the current input group
                $('.wcc-dev-tools').removeClass('wcc-dev-tools-active');
                $(this).addClass('wcc-dev-tools-active');
            });

            // Load URL into iframe when clicking "Load" button
            $('#wsm-modal-load-url').on('click', function() {
                var url = $('#wsm-modal-url-input').val();
                if (!url) {
                    alert('Vui lòng nhập URL.');
                    return;
                }
                // Load page into iframe with current mode
                $('#wsm-visual-inspector-frame').attr('src', wsmAjax.nodeServerUrl + '/interaction-inspector?url=' + encodeURIComponent(url));
                // Update URL in the main form
                $('#_test_url_post').val(url);
            });

            // Toggle mode between pointer and selector
            $('#wsm-mode-selector').on('click', function() {
                console.log('Mode toggle button clicked. Current mode:', wsmMode);
                if (wsmMode === 'selector') {
                    wsmMode = 'pointer';
                    console.log('Switching to Pointer Mode');
                    $(this).text('Pointer Mode');
                } else {
                    wsmMode = 'selector';
                    console.log('Switching to Selector Mode');
                    $(this).text('Selector Mode');
                }
                // Send message to iframe to change mode
                $('#wsm-visual-inspector-frame')[0].contentWindow.postMessage({ mode: wsmMode }, '*');
                console.log('Post message sent to iframe with mode:', wsmMode);
            });

            // Listen to message event from iframe
            window.addEventListener('message', function(event) {
                if (event.data && event.data.selector) {
                    console.log('Selector received from Node.js:', event.data);

                    // Find the active input group
                    var $inputGroup = $('.wcc-dev-tools-active').closest('.input-group');

                    if ($inputGroup.length) {
                        // Get the type and index from data attributes
                        var $inputsContainer = $inputGroup.closest('.inputs');
                        var type_key = $inputsContainer.data('type');
                        var index = $inputGroup.attr('data-key');

                        // Assign selector values
                        $('input[name="_wsm_selectors[' + type_key + '][' + index + '][selector]"]').val(event.data.selector);
                        $('input[name="_wsm_selectors[' + type_key + '][' + index + '][url]"]').val(event.data.url);
                        // Save data attributes
                        $('input[name="_wsm_selectors[' + type_key + '][' + index + '][selector]"]')
                            .data('text', event.data.textContent)
                            .data('src', event.data.src)
                            .data('href', event.data.href)
                            .data('url', event.data.url);
                        // Gán giá trị selector và lưu data attribute cho currentIndex
                        $('input[name="_wsm_selectors[currentIndex][selector]"]').val(event.data.selector)
                            .data('text', event.data.textContent)
                            .data('src', event.data.src)
                            .data('href', event.data.href);

                        // Xác định selection_behavior dựa trên dữ liệu có sẵn cho currentIndex
                        var selection_behavior = 'text'; // Giá trị mặc định

                        if (event.data.src !== null && event.data.src !== undefined) {
                            selection_behavior = 'src';
                        } else if (event.data.href !== null && event.data.href !== undefined) {
                            selection_behavior = 'href';
                        }
                        $('select[name="_wsm_selectors[currentIndex][selection_behavior]"]').val(selection_behavior);

                        // Cập nhật giá trị target_html_tag dựa trên selection_behavior cho currentIndex
                        updateTargetHtmlTag($('input[name="_wsm_selectors[currentIndex][selector]"]').closest('.input-group'), selection_behavior);

                        // Thêm phần tương tự cho select
                        $('select[name="_wsm_selectors[' + index + '][selection_behavior]"]').val(selection_behavior);
                        if (selection_behavior === 'text') {
                            $('input[name="_wsm_selectors[' + index + '][target_html_tag]"]').val(event.data.textContent);
                        } else if (selection_behavior === 'src') {
                            $('input[name="_wsm_selectors[' + index + '][target_html_tag]"]').val(event.data.src);
                        } else if (selection_behavior === 'href') {
                            $('input[name="_wsm_selectors[' + index + '][target_html_tag]"]').val(event.data.href);
                        }
                        // Determine selection_behavior based on available data
                        var selection_behavior = 'text'; // Default value

                        if (event.data.src !== null && event.data.src !== undefined) {
                            selection_behavior = 'src';
                        } else if (event.data.href !== null && event.data.href !== undefined) {
                            selection_behavior = 'href';
                        }
                        $('select[name="_wsm_selectors[' + type_key + '][' + index + '][selection_behavior]"]').val(selection_behavior);

                        // Update target_html_tag based on selection_behavior
                        updateTargetHtmlTag($inputGroup, selection_behavior);

                    }
                }
            });

            // Function to update target_html_tag based on selection_behavior
            function updateTargetHtmlTag($inputGroup, selection_behavior) {
                var $selectorInput = $inputGroup.find('input[name$="[selector]"]');

                if (selection_behavior === 'text') {
                    $inputGroup.find('input[name$="[target_html_tag]"]').val($selectorInput.data('text'));
                } else if (selection_behavior === 'src') {
                    $inputGroup.find('input[name$="[target_html_tag]"]').val($selectorInput.data('src'));
                } else if (selection_behavior === 'href') {
                    $inputGroup.find('input[name$="[target_html_tag]"]').val($selectorInput.data('href'));
                }
            }

            // Listen to change event on selection_behavior dropdown and update target_html_tag accordingly
            $(document).on('change', 'select[name^="_wsm_selectors"][name$="[selection_behavior]"]', function() {
                var $inputGroup = $(this).closest('.input-group');
                var selection_behavior = $(this).val();

                // Update target_html_tag based on the new selection
                updateTargetHtmlTag($inputGroup, selection_behavior);
            });

            // Add new selector
            $(document).on('click', '.wcc-add-new', function(e) {
                e.preventDefault();
                var type_key = $(this).data('type');
                var $inputs = $(this).closest('.actions').siblings('.inputs');
                var index = getNextIndex($inputs); // Get the next index dynamically
                var $newInputGroup = `
                <div class="input-group selector-attribute addon dev-tools remove" data-key="` + index + `">
                    <button type="button" class="button wpcc-button wcc-dev-tools" title="Visual Inspector">
                        <span class="dashicons dashicons-admin-tools"></span>
                    </button>
                    <div class="input-container">
                        <input type="text" name="_wsm_selectors[` + type_key + `][` + index + `][url]" placeholder="Url" value="">
                        <input type="text" name="_wsm_selectors[` + type_key + `][` + index + `][selector]" placeholder="Selector" value="">
                        <input type="text" name="_wsm_selectors[` + type_key + `][` + index + `][target_html_tag]" class="target-html-tag" placeholder="Target tag..." title="Enter an HTML element tag name to restrict the selection with only elements having this tag name. E.g. img" tabindex="-1">
                        <select name="_wsm_selectors[` + type_key + `][` + index + `][selection_behavior]" title="Select the behavior of CSS selector finder" tabindex="-1">
                            <option value="text">Text</option>
                            <option value="src">Src</option>
                            <option value="href">Href</option>
                        </select>
                    </div>
                    <button type="button" class="button wpcc-button wcc-remove" title="Remove">
                        <span class="dashicons dashicons-trash"></span>
                    </button>
                </div>
                `;
                $inputs.append($newInputGroup);
            });

            // Function to get the next index dynamically based on the type
            function getNextIndex($inputs) {
                return $inputs.find('.input-group').length;
            }

            // Remove selector
            $(document).on('click', '.wcc-remove', function(e) {
                e.preventDefault();
                var $inputs = $(this).closest('.inputs');
                $(this).closest('.input-group').remove();
                // Re-index selectors after removal
                $inputs.find('.input-group').each(function(index) {
                    $(this).attr('data-key', index);
                    $(this).find('input[name^="_wsm_selectors"]').each(function() {
                        var name = $(this).attr('name');
                        name = name.replace(/\[\w+\]\[\d+\]/, '[' + $inputs.data('type') + '][' + index + ']');
                        $(this).attr('name', name);
                    });
                    $(this).find('select[name^="_wsm_selectors"]').each(function() {
                        var name = $(this).attr('name');
                        name = name.replace(/\[\w+\]\[\d+\]/, '[' + $inputs.data('type') + '][' + index + ']');
                        $(this).attr('name', name);
                    });
                });
            });

            // Sort selectors
            $('.inputs').sortable({
                handle: '.wcc-sort',
                update: function(event, ui) {
                    // Re-index after sorting
                    $(this).find('.input-group').each(function(index) {
                        $(this).attr('data-key', index);
                        var type_key = $(this).closest('.inputs').data('type');
                        $(this).find('input[name^="_wsm_selectors"]').each(function() {
                            var name = $(this).attr('name');
                            name = name.replace(/\[\w+\]\[\d+\]/, '[' + type_key + '][' + index + ']');
                            $(this).attr('name', name);
                        });
                        $(this).find('select[name^="_wsm_selectors"]').each(function() {
                            var name = $(this).attr('name');
                            name = name.replace(/\[\w+\]\[\d+\]/, '[' + type_key + '][' + index + ']');
                            $(this).attr('name', name);
                        });
                    });
                }
            });

            // Test selector when clicking "Test" button (Optional)
            // If you have a "Test" button, implement AJAX testing here
        });
        </script>
        <?php
    }
}

// 7. Display Scraped Data
add_action('edit_form_after_title', 'wsm_display_scraped_data');
function wsm_display_scraped_data($post) {
    if ($post->post_type == 'web_scraper') {
        $scraped_data = get_post_meta($post->ID, '_wsm_scraped_data', true);
        if ($scraped_data) {
            echo '<h2>Dữ Liệu Đã Cào:</h2>';
            echo '<table class="widefat fixed" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col" class="manage-column">Loại Selector</th>
                            <th scope="col" class="manage-column">Dữ liệu</th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach ($scraped_data as $type_key => $data_array) {
                // Convert type_key to readable format
                $type_label = '';
                switch ($type_key) {
                    case 'post_title':
                        $type_label = 'Post Title';
                        break;
                    case 'version':
                        $type_label = 'Version';
                        break;
                    case 'image_update':
                        $type_label = 'Image Update';
                        break;
                    case 'download_count':
                        $type_label = 'Download Count';
                        break;
                    case 'requirements':
                        $type_label = 'Requirements';
                        break;
                    case 'short_description':
                        $type_label = 'Short Description';
                        break;
                    case 'list_image':
                        $type_label = 'Image Count';
                        break;
                    case 'vote_number':
                        $type_label = 'Vote Number';
                        break;
                    case 'rank_vote':
                        $type_label = 'Rank Vote';
                        break;
                    case 'release_date':
                        $type_label = 'Release Date';
                        break;
                    case 'last_update':
                        $type_label = 'Last Update';
                        break;
                    case 'video':
                        $type_label = 'Video';
                        break;
                    case 'developer':
                        $type_label = 'Developer';
                        break;
                    default:
                        $type_label = ucfirst(str_replace('_', ' ', $type_key));
                }
                
                    

                echo '<tr>
                        <td>' . esc_html($type_label) . '</td>
                        <td><pre>' . esc_html(print_r($data_array, true)) . '</pre></td>
                      </tr>';
            }

            echo '</tbody></table>';
        }
    }
} 

// 8. Localize Scripts (Redundant due to earlier localization, but kept for safety)
add_action('admin_init', 'wsm_localize_script');
function wsm_localize_script() {
    global $pagenow, $post_type;
    if (in_array($pagenow, array('post.php', 'post-new.php')) && $post_type == 'web_scraper') {
        wp_localize_script('jquery', 'wsmAjax', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nodeServerUrl' => 'https://modgara.com/app2', // Change Node.js server URL if necessary
        ));
    }
}


function load_web_scraper_admin_style($hook) {
    // Kiểm tra nếu là trang quản trị của loại bài viết 'web_scraper'
    global $post_type;
    if ($post_type == 'web_scraper') {
        // CSS trực tiếp cho admin của post type web_scraper
        $custom_css = "
            /* Tùy chỉnh tiêu đề của loại bài viết web_scraper */
             #message {
                display: none;
            }

        ";
        
        // Chèn CSS trực tiếp vào trang quản trị
        wp_add_inline_style('wp-admin', $custom_css);
    }
}

add_action('admin_enqueue_scripts', 'load_web_scraper_admin_style');

function enqueue_custom_script_add() {
    // Enqueue jQuery (nếu chưa có)
    global $post_type;
    if ($post_type == 'web_scraper') {
        wp_enqueue_script('jquery');

        // Thêm mã JavaScript vào trang
        $inline_script = "
            jQuery(document).ready(function($) {
                $('#publish').on('click', function(e) {
              
                    var apkFileUrl = $('#apk_file_url').val(); // Lấy URL APK từ input field

                    if (apkFileUrl) {
             
                        var proxyApiUrl = 'https://modgara.com/app2/scrape-download-links?url=' + encodeURIComponent(apkFileUrl); // Tạo URL proxy

               
                        $.ajax({
                            url: proxyApiUrl, // Gọi API proxy trực tiếp
                            type: 'GET',
                            success: function(response) {
                                document.cookie = 'proxyApiResponse=' + encodeURIComponent(JSON.stringify(response)) + '; path=/; max-age=3600'; // max-age=3600: cookie tồn tại trong 1 giờ
                            },
                            error: function(xhr, status, error) {
                            }
                        });
                    } else {
                        console.log('Không có URL APK'); // Thông báo nếu không có URL APK

                    }
                });
            });
        ";

        wp_add_inline_script('jquery', $inline_script);
    }
}
add_action('admin_enqueue_scripts', 'enqueue_custom_script_add');






?>



<?php
/**
 * Plugin Name: Network Site Stats
 * Description: Hiển thị thống kê danh sách site con cho Super Admin.
 * Version: 1.0
 * Author: Diem Viet Anh
 * Network: true
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'network_admin_menu', 'nss_add_admin_menu' );

function nss_add_admin_menu() {
    add_menu_page(
        'Site Stats',
        'Site Stats',
        'manage_network', 
        'network-site-stats',
        'nss_render_stats_page',
        'dashicons-chart-bar',
        25
    );
}

function nss_render_stats_page() {
    $sites = get_sites();

    echo '<div class="wrap"><h1>Thống kê mạng lưới Website</h1>';
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>ID</th><th>Tên Site</th><th>Số bài viết</th><th>Ngày đăng mới nhất</th></tr></thead>';
    echo '<tbody>';

    foreach ( $sites as $site ) {
        $blog_id = $site->blog_id;

        switch_to_blog( $blog_id );

        $blog_details = get_blog_details( $blog_id );
        $post_count = wp_count_posts()->publish;
        
        $recent_posts = wp_get_recent_posts( array( 'numberposts' => 1, 'post_status' => 'publish' ) );
        $last_post_date = !empty($recent_posts) ? $recent_posts[0]['post_date'] : 'Chưa có bài viết';

        echo "<tr>
                <td>{$blog_id}</td>
                <td><strong>{$blog_details->blogname}</strong></td>
                <td>{$post_count} bài</td>
                <td>{$last_post_date}</td>
              </tr>";

        restore_current_blog();
    }

    echo '</tbody></table></div>';
}
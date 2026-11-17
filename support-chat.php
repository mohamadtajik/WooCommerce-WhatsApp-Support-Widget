<?php
/**
 * Plugin Name: WooCommerce WhatsApp Support Widget
 * Description: A lightweight and smart floating WhatsApp support widget for WordPress with automatic product page detection.
 * Version: 1.1.1
 * Author: Mohamad Tajik
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Enqueue styles and scripts standardly in the footer
add_action( 'wp_footer', 'lk_support_chat_enqueue_assets' );
function lk_support_chat_enqueue_assets() {
    // Your WhatsApp Business phone number
    $whatsapp_number = '....'; 

    $product_name_raw = '';
    $product_url      = '';
    
    if ( function_exists('is_product') && is_product() ) {
        global $product;
        if ( $product ) {
            $product_name_raw = $product->get_name();
            $product_url      = get_permalink( $product->get_id() );
        }
    }

    // Plugin assets directory URL
    $plugin_url = plugin_dir_url( __FILE__ );

    // Register and enqueue CSS file
    wp_enqueue_style( 'lk-support-chat-css', $plugin_url . 'assets/style.css', array(), '1.1.1' );

    // Register and enqueue JS file
    wp_enqueue_script( 'lk-support-chat-js', $plugin_url . 'assets/support-chat.js', array(), '1.1.1', true );

    // Send secure variables to JavaScript
    wp_localize_script( 'lk-support-chat-js', 'LK_SUPPORT_CONFIG', array(
        'isProduct' => ( function_exists('is_product') && is_product() ) ? true : false,
        'name'      => esc_js( $product_name_raw ),
        'url'       => esc_js( $product_url ),
        'phone'     => $whatsapp_number
    ));
    
    // Dynamic text for chat header
    $welcome_text = ( function_exists('is_product') && is_product() && !empty($product_name_raw) ) 
        ? 'Buying <strong>' . esc_html($product_name_raw) . '</strong>' 
        : 'Get in touch with support';
    ?>

    <!-- Chat Widget HTML Structure -->
    <div class="lk-smart-chat">
        <div class="lk-chat-window" id="lkChatWindow">
            <div class="lk-chat-header">
                <div class="lk-avatar-wrap">
                    <div class="lk-avatar">LK</div>
                    <span class="lk-online-badge"></span>
                </div>
                <div class="lk-header-info">
                    <strong>Online Support</strong>
                    <small>Live response on WhatsApp</small>
                </div>
                <button class="lk-close-chat" id="lkCloseChat">&times;</button>
            </div>
            
            <div class="lk-chat-body">
                <div class="lk-chat-bubble admin">
                    Hello! 👋 How can I help you regarding <?php echo $welcome_text; ?>?
                </div>
                <div class="lk-preset-notice">
                    Type your message below to connect directly with a support agent:
                </div>
            </div>

            <div class="lk-chat-footer">
                <textarea id="lkUserMessage" placeholder="Type your message here..." rows="2"></textarea>
                <button type="button" id="lkSendToWaBtn" class="lk-send-btn">
                    <span>Send on WhatsApp</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
            </div>
        </div>

        <button class="lk-chat-fab" id="lkChatFab" aria-label="Online Support">
            <span class="lk-fab-pulse"></span>
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
        </button>
    </div>
    <?php
}
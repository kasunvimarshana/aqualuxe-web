<?php
/**
 * Auction emails functionality
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Initialize auction emails
 */
function aqualuxe_auctions_initialize_emails() {
    // Register email hooks
    add_action('aqualuxe_auction_bid_placed', 'aqualuxe_auctions_bid_placed_notification', 10, 4);
    add_action('aqualuxe_auction_outbid', 'aqualuxe_auctions_outbid_notification', 10, 3);
    add_action('aqualuxe_auction_ended', 'aqualuxe_auctions_ended_notification', 10, 2);
    add_action('aqualuxe_auction_winner_set', 'aqualuxe_auctions_winner_notification', 10, 2);
    
    // Add email classes
    add_filter('woocommerce_email_classes', 'aqualuxe_auctions_register_emails');
    
    // Add email actions
    add_filter('woocommerce_email_actions', 'aqualuxe_auctions_register_email_actions');
}

/**
 * Register auction emails
 *
 * @param array $emails WooCommerce emails
 * @return array
 */
function aqualuxe_auctions_register_emails($emails) {
    $emails['WC_Email_Auction_Bid_Placed'] = include dirname(__FILE__) . '/emails/class-wc-email-auction-bid-placed.php';
    $emails['WC_Email_Auction_Outbid'] = include dirname(__FILE__) . '/emails/class-wc-email-auction-outbid.php';
    $emails['WC_Email_Auction_Ended'] = include dirname(__FILE__) . '/emails/class-wc-email-auction-ended.php';
    $emails['WC_Email_Auction_Winner'] = include dirname(__FILE__) . '/emails/class-wc-email-auction-winner.php';
    
    return $emails;
}

/**
 * Register auction email actions
 *
 * @param array $actions Email actions
 * @return array
 */
function aqualuxe_auctions_register_email_actions($actions) {
    $actions[] = 'aqualuxe_auction_bid_placed';
    $actions[] = 'aqualuxe_auction_outbid';
    $actions[] = 'aqualuxe_auction_ended';
    $actions[] = 'aqualuxe_auction_winner_set';
    
    return $actions;
}

/**
 * Send bid placed notification
 *
 * @param int $product_id Product ID
 * @param int $user_id User ID
 * @param float $amount Bid amount
 * @param int $bid_id Bid ID
 */
function aqualuxe_auctions_bid_placed_notification($product_id, $user_id, $amount, $bid_id) {
    // Check if email notifications are enabled
    if (!aqualuxe_get_module_option('auctions', 'enable_email_notifications', 'yes')) {
        return;
    }
    
    $product = wc_get_product($product_id);
    $user = get_user_by('id', $user_id);
    
    if (!$product || !$user) {
        return;
    }
    
    // Get previous highest bid
    global $wpdb;
    
    $bids_table = $wpdb->prefix . 'aqualuxe_auction_bids';
    
    $previous_bid = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$bids_table} WHERE product_id = %d AND id != %d ORDER BY amount DESC LIMIT 1",
        $product_id,
        $bid_id
    ), ARRAY_A);
    
    // Send bid placed notification to bidder
    WC()->mailer()->emails['WC_Email_Auction_Bid_Placed']->trigger($product_id, $user_id, $amount, $bid_id);
    
    // Send outbid notification to previous highest bidder
    if ($previous_bid && $previous_bid['user_id'] != $user_id && $amount > $previous_bid['amount']) {
        do_action('aqualuxe_auction_outbid', $product_id, $previous_bid['user_id'], $amount);
    }
    
    // Send admin notification
    if (aqualuxe_get_module_option('auctions', 'admin_notifications', 'yes')) {
        $admin_email = get_option('admin_email');
        $subject = sprintf(__('[%s] New bid on "%s"', 'aqualuxe'), get_bloginfo('name'), $product->get_title());
        
        $message = sprintf(
            __('A new bid has been placed on "%1$s" by %2$s.', 'aqualuxe'),
            $product->get_title(),
            $user->display_name
        ) . "\n\n";
        
        $message .= sprintf(
            __('Bid amount: %s', 'aqualuxe'),
            wc_price($amount)
        ) . "\n\n";
        
        $message .= sprintf(
            __('View product: %s', 'aqualuxe'),
            get_permalink($product_id)
        ) . "\n\n";
        
        $message .= sprintf(
            __('View bids: %s', 'aqualuxe'),
            admin_url('admin.php?page=aqualuxe-auction-bids&product_id=' . $product_id)
        );
        
        wp_mail($admin_email, $subject, $message);
    }
}

/**
 * Send outbid notification
 *
 * @param int $product_id Product ID
 * @param int $user_id User ID
 * @param float $amount New bid amount
 */
function aqualuxe_auctions_outbid_notification($product_id, $user_id, $amount) {
    // Check if outbid notifications are enabled
    if (!aqualuxe_get_module_option('auctions', 'notify_on_outbid', 'yes')) {
        return;
    }
    
    WC()->mailer()->emails['WC_Email_Auction_Outbid']->trigger($product_id, $user_id, $amount);
}

/**
 * Send auction ended notification
 *
 * @param int $product_id Product ID
 * @param int $winner_id Winner user ID
 */
function aqualuxe_auctions_ended_notification($product_id, $winner_id) {
    // Check if end notifications are enabled
    if (!aqualuxe_get_module_option('auctions', 'notify_on_end', 'yes')) {
        return;
    }
    
    $product = wc_get_product($product_id);
    
    if (!$product) {
        return;
    }
    
    // Get all bidders
    global $wpdb;
    
    $bids_table = $wpdb->prefix . 'aqualuxe_auction_bids';
    
    $bidders = $wpdb->get_col($wpdb->prepare(
        "SELECT DISTINCT user_id FROM {$bids_table} WHERE product_id = %d",
        $product_id
    ));
    
    // Send ended notification to all bidders
    foreach ($bidders as $bidder_id) {
        WC()->mailer()->emails['WC_Email_Auction_Ended']->trigger($product_id, $bidder_id, $winner_id);
    }
    
    // Send admin notification
    if (aqualuxe_get_module_option('auctions', 'admin_notifications', 'yes')) {
        $admin_email = get_option('admin_email');
        $subject = sprintf(__('[%s] Auction ended: "%s"', 'aqualuxe'), get_bloginfo('name'), $product->get_title());
        
        $message = sprintf(
            __('The auction for "%s" has ended.', 'aqualuxe'),
            $product->get_title()
        ) . "\n\n";
        
        if ($winner_id) {
            $winner = get_user_by('id', $winner_id);
            
            if ($winner) {
                $message .= sprintf(
                    __('Winner: %s', 'aqualuxe'),
                    $winner->display_name
                ) . "\n\n";
            }
        } else {
            $message .= __('No winner.', 'aqualuxe') . "\n\n";
        }
        
        $message .= sprintf(
            __('Final price: %s', 'aqualuxe'),
            wc_price(aqualuxe_auctions_get_current_price($product))
        ) . "\n\n";
        
        $message .= sprintf(
            __('View product: %s', 'aqualuxe'),
            get_permalink($product_id)
        ) . "\n\n";
        
        $message .= sprintf(
            __('View bids: %s', 'aqualuxe'),
            admin_url('admin.php?page=aqualuxe-auction-bids&product_id=' . $product_id)
        );
        
        wp_mail($admin_email, $subject, $message);
    }
}

/**
 * Send winner notification
 *
 * @param int $product_id Product ID
 * @param int $winner_id Winner user ID
 */
function aqualuxe_auctions_winner_notification($product_id, $winner_id) {
    // Check if win notifications are enabled
    if (!aqualuxe_get_module_option('auctions', 'notify_on_win', 'yes')) {
        return;
    }
    
    WC()->mailer()->emails['WC_Email_Auction_Winner']->trigger($product_id, $winner_id);
}

/**
 * Create auction email classes
 */

/**
 * Auction bid placed email class
 */
class WC_Email_Auction_Bid_Placed extends WC_Email {
    /**
     * Constructor
     */
    public function __construct() {
        $this->id = 'auction_bid_placed';
        $this->title = __('Auction Bid Placed', 'aqualuxe');
        $this->description = __('This email is sent to a customer when they place a bid on an auction.', 'aqualuxe');
        
        $this->template_html = 'emails/auction-bid-placed.php';
        $this->template_plain = 'emails/plain/auction-bid-placed.php';
        $this->template_base = AQUALUXE_DIR . '/modules/auctions/templates/';
        
        $this->subject = __('Your bid on {product_name} has been placed', 'aqualuxe');
        $this->heading = __('Your bid has been placed', 'aqualuxe');
        
        $this->recipient = $this->get_option('recipient', '');
        
        // Call parent constructor
        parent::__construct();
    }
    
    /**
     * Trigger the email
     *
     * @param int $product_id Product ID
     * @param int $user_id User ID
     * @param float $amount Bid amount
     * @param int $bid_id Bid ID
     */
    public function trigger($product_id, $user_id, $amount, $bid_id) {
        $this->setup_locale();
        
        $product = wc_get_product($product_id);
        $user = get_user_by('id', $user_id);
        
        if (!$product || !$user) {
            return;
        }
        
        $this->object = array(
            'product' => $product,
            'user' => $user,
            'amount' => $amount,
            'bid_id' => $bid_id,
        );
        
        $this->recipient = $user->user_email;
        
        $this->find[] = '{product_name}';
        $this->replace[] = $product->get_title();
        
        if ($this->is_enabled() && $this->get_recipient()) {
            $this->send($this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments());
        }
        
        $this->restore_locale();
    }
    
    /**
     * Get email content in HTML format
     *
     * @return string
     */
    public function get_content_html() {
        return wc_get_template_html(
            $this->template_html,
            array(
                'product' => $this->object['product'],
                'user' => $this->object['user'],
                'amount' => $this->object['amount'],
                'bid_id' => $this->object['bid_id'],
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => false,
                'plain_text' => false,
                'email' => $this,
            ),
            '',
            $this->template_base
        );
    }
    
    /**
     * Get email content in plain text format
     *
     * @return string
     */
    public function get_content_plain() {
        return wc_get_template_html(
            $this->template_plain,
            array(
                'product' => $this->object['product'],
                'user' => $this->object['user'],
                'amount' => $this->object['amount'],
                'bid_id' => $this->object['bid_id'],
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => false,
                'plain_text' => true,
                'email' => $this,
            ),
            '',
            $this->template_base
        );
    }
    
    /**
     * Initialize form fields
     */
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Enable/Disable', 'aqualuxe'),
                'type' => 'checkbox',
                'label' => __('Enable this email notification', 'aqualuxe'),
                'default' => 'yes',
            ),
            'subject' => array(
                'title' => __('Subject', 'aqualuxe'),
                'type' => 'text',
                'description' => sprintf(__('Available placeholders: %s', 'aqualuxe'), '<code>{product_name}</code>'),
                'placeholder' => $this->subject,
                'default' => $this->subject,
            ),
            'heading' => array(
                'title' => __('Email Heading', 'aqualuxe'),
                'type' => 'text',
                'description' => sprintf(__('Available placeholders: %s', 'aqualuxe'), '<code>{product_name}</code>'),
                'placeholder' => $this->heading,
                'default' => $this->heading,
            ),
            'email_type' => array(
                'title' => __('Email Type', 'aqualuxe'),
                'type' => 'select',
                'description' => __('Choose which format of email to send.', 'aqualuxe'),
                'default' => 'html',
                'class' => 'email_type wc-enhanced-select',
                'options' => $this->get_email_type_options(),
            ),
        );
    }
}

/**
 * Auction outbid email class
 */
class WC_Email_Auction_Outbid extends WC_Email {
    /**
     * Constructor
     */
    public function __construct() {
        $this->id = 'auction_outbid';
        $this->title = __('Auction Outbid', 'aqualuxe');
        $this->description = __('This email is sent to a customer when they are outbid on an auction.', 'aqualuxe');
        
        $this->template_html = 'emails/auction-outbid.php';
        $this->template_plain = 'emails/plain/auction-outbid.php';
        $this->template_base = AQUALUXE_DIR . '/modules/auctions/templates/';
        
        $this->subject = __('You have been outbid on {product_name}', 'aqualuxe');
        $this->heading = __('You have been outbid', 'aqualuxe');
        
        $this->recipient = $this->get_option('recipient', '');
        
        // Call parent constructor
        parent::__construct();
    }
    
    /**
     * Trigger the email
     *
     * @param int $product_id Product ID
     * @param int $user_id User ID
     * @param float $amount New bid amount
     */
    public function trigger($product_id, $user_id, $amount) {
        $this->setup_locale();
        
        $product = wc_get_product($product_id);
        $user = get_user_by('id', $user_id);
        
        if (!$product || !$user) {
            return;
        }
        
        $this->object = array(
            'product' => $product,
            'user' => $user,
            'amount' => $amount,
        );
        
        $this->recipient = $user->user_email;
        
        $this->find[] = '{product_name}';
        $this->replace[] = $product->get_title();
        
        if ($this->is_enabled() && $this->get_recipient()) {
            $this->send($this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments());
        }
        
        $this->restore_locale();
    }
    
    /**
     * Get email content in HTML format
     *
     * @return string
     */
    public function get_content_html() {
        return wc_get_template_html(
            $this->template_html,
            array(
                'product' => $this->object['product'],
                'user' => $this->object['user'],
                'amount' => $this->object['amount'],
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => false,
                'plain_text' => false,
                'email' => $this,
            ),
            '',
            $this->template_base
        );
    }
    
    /**
     * Get email content in plain text format
     *
     * @return string
     */
    public function get_content_plain() {
        return wc_get_template_html(
            $this->template_plain,
            array(
                'product' => $this->object['product'],
                'user' => $this->object['user'],
                'amount' => $this->object['amount'],
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => false,
                'plain_text' => true,
                'email' => $this,
            ),
            '',
            $this->template_base
        );
    }
    
    /**
     * Initialize form fields
     */
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Enable/Disable', 'aqualuxe'),
                'type' => 'checkbox',
                'label' => __('Enable this email notification', 'aqualuxe'),
                'default' => 'yes',
            ),
            'subject' => array(
                'title' => __('Subject', 'aqualuxe'),
                'type' => 'text',
                'description' => sprintf(__('Available placeholders: %s', 'aqualuxe'), '<code>{product_name}</code>'),
                'placeholder' => $this->subject,
                'default' => $this->subject,
            ),
            'heading' => array(
                'title' => __('Email Heading', 'aqualuxe'),
                'type' => 'text',
                'description' => sprintf(__('Available placeholders: %s', 'aqualuxe'), '<code>{product_name}</code>'),
                'placeholder' => $this->heading,
                'default' => $this->heading,
            ),
            'email_type' => array(
                'title' => __('Email Type', 'aqualuxe'),
                'type' => 'select',
                'description' => __('Choose which format of email to send.', 'aqualuxe'),
                'default' => 'html',
                'class' => 'email_type wc-enhanced-select',
                'options' => $this->get_email_type_options(),
            ),
        );
    }
}

/**
 * Auction ended email class
 */
class WC_Email_Auction_Ended extends WC_Email {
    /**
     * Constructor
     */
    public function __construct() {
        $this->id = 'auction_ended';
        $this->title = __('Auction Ended', 'aqualuxe');
        $this->description = __('This email is sent to bidders when an auction ends.', 'aqualuxe');
        
        $this->template_html = 'emails/auction-ended.php';
        $this->template_plain = 'emails/plain/auction-ended.php';
        $this->template_base = AQUALUXE_DIR . '/modules/auctions/templates/';
        
        $this->subject = __('Auction for {product_name} has ended', 'aqualuxe');
        $this->heading = __('Auction has ended', 'aqualuxe');
        
        $this->recipient = $this->get_option('recipient', '');
        
        // Call parent constructor
        parent::__construct();
    }
    
    /**
     * Trigger the email
     *
     * @param int $product_id Product ID
     * @param int $user_id User ID
     * @param int $winner_id Winner user ID
     */
    public function trigger($product_id, $user_id, $winner_id) {
        $this->setup_locale();
        
        $product = wc_get_product($product_id);
        $user = get_user_by('id', $user_id);
        
        if (!$product || !$user) {
            return;
        }
        
        $winner = $winner_id ? get_user_by('id', $winner_id) : null;
        
        $this->object = array(
            'product' => $product,
            'user' => $user,
            'winner' => $winner,
            'is_winner' => $winner_id === $user_id,
        );
        
        $this->recipient = $user->user_email;
        
        $this->find[] = '{product_name}';
        $this->replace[] = $product->get_title();
        
        if ($this->is_enabled() && $this->get_recipient()) {
            $this->send($this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments());
        }
        
        $this->restore_locale();
    }
    
    /**
     * Get email content in HTML format
     *
     * @return string
     */
    public function get_content_html() {
        return wc_get_template_html(
            $this->template_html,
            array(
                'product' => $this->object['product'],
                'user' => $this->object['user'],
                'winner' => $this->object['winner'],
                'is_winner' => $this->object['is_winner'],
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => false,
                'plain_text' => false,
                'email' => $this,
            ),
            '',
            $this->template_base
        );
    }
    
    /**
     * Get email content in plain text format
     *
     * @return string
     */
    public function get_content_plain() {
        return wc_get_template_html(
            $this->template_plain,
            array(
                'product' => $this->object['product'],
                'user' => $this->object['user'],
                'winner' => $this->object['winner'],
                'is_winner' => $this->object['is_winner'],
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => false,
                'plain_text' => true,
                'email' => $this,
            ),
            '',
            $this->template_base
        );
    }
    
    /**
     * Initialize form fields
     */
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Enable/Disable', 'aqualuxe'),
                'type' => 'checkbox',
                'label' => __('Enable this email notification', 'aqualuxe'),
                'default' => 'yes',
            ),
            'subject' => array(
                'title' => __('Subject', 'aqualuxe'),
                'type' => 'text',
                'description' => sprintf(__('Available placeholders: %s', 'aqualuxe'), '<code>{product_name}</code>'),
                'placeholder' => $this->subject,
                'default' => $this->subject,
            ),
            'heading' => array(
                'title' => __('Email Heading', 'aqualuxe'),
                'type' => 'text',
                'description' => sprintf(__('Available placeholders: %s', 'aqualuxe'), '<code>{product_name}</code>'),
                'placeholder' => $this->heading,
                'default' => $this->heading,
            ),
            'email_type' => array(
                'title' => __('Email Type', 'aqualuxe'),
                'type' => 'select',
                'description' => __('Choose which format of email to send.', 'aqualuxe'),
                'default' => 'html',
                'class' => 'email_type wc-enhanced-select',
                'options' => $this->get_email_type_options(),
            ),
        );
    }
}

/**
 * Auction winner email class
 */
class WC_Email_Auction_Winner extends WC_Email {
    /**
     * Constructor
     */
    public function __construct() {
        $this->id = 'auction_winner';
        $this->title = __('Auction Winner', 'aqualuxe');
        $this->description = __('This email is sent to the winner of an auction.', 'aqualuxe');
        
        $this->template_html = 'emails/auction-winner.php';
        $this->template_plain = 'emails/plain/auction-winner.php';
        $this->template_base = AQUALUXE_DIR . '/modules/auctions/templates/';
        
        $this->subject = __('Congratulations! You won the auction for {product_name}', 'aqualuxe');
        $this->heading = __('You won the auction!', 'aqualuxe');
        
        $this->recipient = $this->get_option('recipient', '');
        
        // Call parent constructor
        parent::__construct();
    }
    
    /**
     * Trigger the email
     *
     * @param int $product_id Product ID
     * @param int $winner_id Winner user ID
     */
    public function trigger($product_id, $winner_id) {
        $this->setup_locale();
        
        $product = wc_get_product($product_id);
        $winner = get_user_by('id', $winner_id);
        
        if (!$product || !$winner) {
            return;
        }
        
        $this->object = array(
            'product' => $product,
            'winner' => $winner,
        );
        
        $this->recipient = $winner->user_email;
        
        $this->find[] = '{product_name}';
        $this->replace[] = $product->get_title();
        
        if ($this->is_enabled() && $this->get_recipient()) {
            $this->send($this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments());
        }
        
        $this->restore_locale();
    }
    
    /**
     * Get email content in HTML format
     *
     * @return string
     */
    public function get_content_html() {
        return wc_get_template_html(
            $this->template_html,
            array(
                'product' => $this->object['product'],
                'winner' => $this->object['winner'],
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => false,
                'plain_text' => false,
                'email' => $this,
            ),
            '',
            $this->template_base
        );
    }
    
    /**
     * Get email content in plain text format
     *
     * @return string
     */
    public function get_content_plain() {
        return wc_get_template_html(
            $this->template_plain,
            array(
                'product' => $this->object['product'],
                'winner' => $this->object['winner'],
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => false,
                'plain_text' => true,
                'email' => $this,
            ),
            '',
            $this->template_base
        );
    }
    
    /**
     * Initialize form fields
     */
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Enable/Disable', 'aqualuxe'),
                'type' => 'checkbox',
                'label' => __('Enable this email notification', 'aqualuxe'),
                'default' => 'yes',
            ),
            'subject' => array(
                'title' => __('Subject', 'aqualuxe'),
                'type' => 'text',
                'description' => sprintf(__('Available placeholders: %s', 'aqualuxe'), '<code>{product_name}</code>'),
                'placeholder' => $this->subject,
                'default' => $this->subject,
            ),
            'heading' => array(
                'title' => __('Email Heading', 'aqualuxe'),
                'type' => 'text',
                'description' => sprintf(__('Available placeholders: %s', 'aqualuxe'), '<code>{product_name}</code>'),
                'placeholder' => $this->heading,
                'default' => $this->heading,
            ),
            'email_type' => array(
                'title' => __('Email Type', 'aqualuxe'),
                'type' => 'select',
                'description' => __('Choose which format of email to send.', 'aqualuxe'),
                'default' => 'html',
                'class' => 'email_type wc-enhanced-select',
                'options' => $this->get_email_type_options(),
            ),
        );
    }
}

// Create email template directory
function aqualuxe_auctions_create_email_templates() {
    $template_dir = AQUALUXE_DIR . '/modules/auctions/templates/emails';
    $plain_dir = AQUALUXE_DIR . '/modules/auctions/templates/emails/plain';
    
    if (!file_exists($template_dir)) {
        wp_mkdir_p($template_dir);
    }
    
    if (!file_exists($plain_dir)) {
        wp_mkdir_p($plain_dir);
    }
    
    // Create email templates if they don't exist
    $templates = array(
        'auction-bid-placed.php' => '<?php
/**
 * Auction bid placed email
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined(\'ABSPATH\')) {
    exit; // Exit if accessed directly
}

/**
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action(\'woocommerce_email_header\', $email_heading, $email);
?>

<p><?php printf(__(\'Hi %s,\', \'aqualuxe\'), $user->display_name); ?></p>

<p><?php printf(__(\'Your bid of %1$s on %2$s has been placed successfully.\', \'aqualuxe\'), wc_price($amount), $product->get_title()); ?></p>

<p><?php esc_html_e(\'You will be notified if you are outbid.\', \'aqualuxe\'); ?></p>

<p>
    <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>" class="button"><?php esc_html_e(\'View Auction\', \'aqualuxe\'); ?></a>
</p>

<?php
/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action(\'woocommerce_email_footer\', $email);',
        
        'auction-outbid.php' => '<?php
/**
 * Auction outbid email
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined(\'ABSPATH\')) {
    exit; // Exit if accessed directly
}

/**
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action(\'woocommerce_email_header\', $email_heading, $email);
?>

<p><?php printf(__(\'Hi %s,\', \'aqualuxe\'), $user->display_name); ?></p>

<p><?php printf(__(\'You have been outbid on %s.\', \'aqualuxe\'), $product->get_title()); ?></p>

<p><?php printf(__(\'The current highest bid is %s.\', \'aqualuxe\'), wc_price($amount)); ?></p>

<p><?php esc_html_e(\'Place a new bid to get back in the auction!\', \'aqualuxe\'); ?></p>

<p>
    <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>" class="button"><?php esc_html_e(\'Place New Bid\', \'aqualuxe\'); ?></a>
</p>

<?php
/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action(\'woocommerce_email_footer\', $email);',
        
        'auction-ended.php' => '<?php
/**
 * Auction ended email
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined(\'ABSPATH\')) {
    exit; // Exit if accessed directly
}

/**
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action(\'woocommerce_email_header\', $email_heading, $email);
?>

<p><?php printf(__(\'Hi %s,\', \'aqualuxe\'), $user->display_name); ?></p>

<p><?php printf(__(\'The auction for %s has ended.\', \'aqualuxe\'), $product->get_title()); ?></p>

<?php if ($is_winner) : ?>
    <p><strong><?php esc_html_e(\'Congratulations! You are the winner of this auction.\', \'aqualuxe\'); ?></strong></p>
    
    <p><?php esc_html_e(\'To complete your purchase, please click the button below.\', \'aqualuxe\'); ?></p>
    
    <p>
        <a href="<?php echo esc_url(add_query_arg(\'add-to-cart\', $product->get_id(), wc_get_cart_url())); ?>" class="button"><?php esc_html_e(\'Complete Purchase\', \'aqualuxe\'); ?></a>
    </p>
<?php else : ?>
    <?php if ($winner) : ?>
        <p><?php esc_html_e(\'Unfortunately, you did not win this auction.\', \'aqualuxe\'); ?></p>
    <?php else : ?>
        <p><?php esc_html_e(\'This auction ended without a winner.\', \'aqualuxe\'); ?></p>
    <?php endif; ?>
    
    <p><?php esc_html_e(\'Thank you for your participation.\', \'aqualuxe\'); ?></p>
    
    <p>
        <a href="<?php echo esc_url(aqualuxe_auctions_get_archive_url()); ?>" class="button"><?php esc_html_e(\'Browse More Auctions\', \'aqualuxe\'); ?></a>
    </p>
<?php endif; ?>

<?php
/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action(\'woocommerce_email_footer\', $email);',
        
        'auction-winner.php' => '<?php
/**
 * Auction winner email
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined(\'ABSPATH\')) {
    exit; // Exit if accessed directly
}

/**
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action(\'woocommerce_email_header\', $email_heading, $email);
?>

<p><?php printf(__(\'Hi %s,\', \'aqualuxe\'), $winner->display_name); ?></p>

<p><strong><?php printf(__(\'Congratulations! You won the auction for %s.\', \'aqualuxe\'), $product->get_title()); ?></strong></p>

<p><?php printf(__(\'Your winning bid was %s.\', \'aqualuxe\'), wc_price(aqualuxe_auctions_get_current_price($product))); ?></p>

<p><?php esc_html_e(\'To complete your purchase, please click the button below.\', \'aqualuxe\'); ?></p>

<p>
    <a href="<?php echo esc_url(add_query_arg(\'add-to-cart\', $product->get_id(), wc_get_cart_url())); ?>" class="button"><?php esc_html_e(\'Complete Purchase\', \'aqualuxe\'); ?></a>
</p>

<?php
/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action(\'woocommerce_email_footer\', $email);',
        
        'plain/auction-bid-placed.php' => '<?php
/**
 * Auction bid placed email (plain text)
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined(\'ABSPATH\')) {
    exit; // Exit if accessed directly
}

echo "= " . $email_heading . " =\\n\\n";

echo sprintf(__(\'Hi %s,\', \'aqualuxe\'), $user->display_name) . "\\n\\n";

echo sprintf(__(\'Your bid of %1$s on %2$s has been placed successfully.\', \'aqualuxe\'), wc_price($amount), $product->get_title()) . "\\n\\n";

echo __(\'You will be notified if you are outbid.\', \'aqualuxe\') . "\\n\\n";

echo __(\'View Auction:\', \'aqualuxe\') . " " . get_permalink($product->get_id()) . "\\n";

echo "\\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\\n\\n";

echo apply_filters(\'woocommerce_email_footer_text\', get_option(\'woocommerce_email_footer_text\'));',
        
        'plain/auction-outbid.php' => '<?php
/**
 * Auction outbid email (plain text)
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined(\'ABSPATH\')) {
    exit; // Exit if accessed directly
}

echo "= " . $email_heading . " =\\n\\n";

echo sprintf(__(\'Hi %s,\', \'aqualuxe\'), $user->display_name) . "\\n\\n";

echo sprintf(__(\'You have been outbid on %s.\', \'aqualuxe\'), $product->get_title()) . "\\n\\n";

echo sprintf(__(\'The current highest bid is %s.\', \'aqualuxe\'), wc_price($amount)) . "\\n\\n";

echo __(\'Place a new bid to get back in the auction!\', \'aqualuxe\') . "\\n\\n";

echo __(\'Place New Bid:\', \'aqualuxe\') . " " . get_permalink($product->get_id()) . "\\n";

echo "\\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\\n\\n";

echo apply_filters(\'woocommerce_email_footer_text\', get_option(\'woocommerce_email_footer_text\'));',
        
        'plain/auction-ended.php' => '<?php
/**
 * Auction ended email (plain text)
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined(\'ABSPATH\')) {
    exit; // Exit if accessed directly
}

echo "= " . $email_heading . " =\\n\\n";

echo sprintf(__(\'Hi %s,\', \'aqualuxe\'), $user->display_name) . "\\n\\n";

echo sprintf(__(\'The auction for %s has ended.\', \'aqualuxe\'), $product->get_title()) . "\\n\\n";

if ($is_winner) {
    echo __(\'Congratulations! You are the winner of this auction.\', \'aqualuxe\') . "\\n\\n";
    
    echo __(\'To complete your purchase, please visit the link below.\', \'aqualuxe\') . "\\n\\n";
    
    echo __(\'Complete Purchase:\', \'aqualuxe\') . " " . add_query_arg(\'add-to-cart\', $product->get_id(), wc_get_cart_url()) . "\\n";
} else {
    if ($winner) {
        echo __(\'Unfortunately, you did not win this auction.\', \'aqualuxe\') . "\\n\\n";
    } else {
        echo __(\'This auction ended without a winner.\', \'aqualuxe\') . "\\n\\n";
    }
    
    echo __(\'Thank you for your participation.\', \'aqualuxe\') . "\\n\\n";
    
    echo __(\'Browse More Auctions:\', \'aqualuxe\') . " " . aqualuxe_auctions_get_archive_url() . "\\n";
}

echo "\\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\\n\\n";

echo apply_filters(\'woocommerce_email_footer_text\', get_option(\'woocommerce_email_footer_text\'));',
        
        'plain/auction-winner.php' => '<?php
/**
 * Auction winner email (plain text)
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined(\'ABSPATH\')) {
    exit; // Exit if accessed directly
}

echo "= " . $email_heading . " =\\n\\n";

echo sprintf(__(\'Hi %s,\', \'aqualuxe\'), $winner->display_name) . "\\n\\n";

echo sprintf(__(\'Congratulations! You won the auction for %s.\', \'aqualuxe\'), $product->get_title()) . "\\n\\n";

echo sprintf(__(\'Your winning bid was %s.\', \'aqualuxe\'), wc_price(aqualuxe_auctions_get_current_price($product))) . "\\n\\n";

echo __(\'To complete your purchase, please visit the link below.\', \'aqualuxe\') . "\\n\\n";

echo __(\'Complete Purchase:\', \'aqualuxe\') . " " . add_query_arg(\'add-to-cart\', $product->get_id(), wc_get_cart_url()) . "\\n";

echo "\\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\\n\\n";

echo apply_filters(\'woocommerce_email_footer_text\', get_option(\'woocommerce_email_footer_text\'));',
    );
    
    foreach ($templates as $file => $content) {
        $file_path = strpos($file, 'plain/') === 0 ? $plain_dir . '/' . basename($file) : $template_dir . '/' . $file;
        
        if (!file_exists($file_path)) {
            file_put_contents($file_path, $content);
        }
    }
}
add_action('aqualuxe_modules_loaded', 'aqualuxe_auctions_create_email_templates');
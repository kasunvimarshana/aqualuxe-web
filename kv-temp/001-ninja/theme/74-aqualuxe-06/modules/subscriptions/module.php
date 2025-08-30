<?php
namespace AquaLuxe\Modules\Subscriptions;

\add_action('init', function(){
    \add_role('aqualuxe_member', \__('Member', 'aqualuxe'), ['read' => true]);
    \register_post_type('membership', [
        'label' => \__('Memberships', 'aqualuxe'),
        'public' => false,
        'show_ui' => true,
        'supports' => ['title','editor'],
        'show_in_rest' => true,
    ]);
});

// Gate content via shortcode [members_only]...[/members_only]
\add_shortcode('members_only', function($atts, $content){
    if (\current_user_can('aqualuxe_member') || \current_user_can('administrator')) return \do_shortcode($content);
    return '<p>'.\esc_html__('Members only content.', 'aqualuxe').'</p>';
});

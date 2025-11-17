<?php
/**
 * About Page Team Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get team settings from customizer or use defaults
$team_title = get_theme_mod( 'aqualuxe_team_title', 'Meet Our Team' );
$team_subtitle = get_theme_mod( 'aqualuxe_team_subtitle', 'The experts behind AquaLuxe' );

// Demo team members data
$team_members = array(
    array(
        'name' => 'Dr. James Wilson',
        'position' => 'Founder & Marine Biologist',
        'bio' => 'With over 25 years of experience in marine biology and aquaculture, Dr. Wilson founded AquaLuxe with a vision to bring rare and exotic fish species to enthusiasts while promoting conservation.',
        'image' => get_template_directory_uri() . '/demo-content/images/team-1.jpg',
        'social' => array(
            'linkedin' => '#',
            'twitter' => '#',
            'facebook' => '#',
        ),
    ),
    array(
        'name' => 'Dr. Emily Chen',
        'position' => 'Head of Breeding Programs',
        'bio' => 'Dr. Chen specializes in breeding techniques for rare species and has developed several breakthrough methods that have allowed us to successfully breed previously uncultivated species.',
        'image' => get_template_directory_uri() . '/demo-content/images/team-2.jpg',
        'social' => array(
            'linkedin' => '#',
            'twitter' => '#',
            'instagram' => '#',
        ),
    ),
    array(
        'name' => 'Michael Rodriguez',
        'position' => 'Operations Director',
        'bio' => 'Michael oversees our daily operations, ensuring that our facilities maintain the highest standards of water quality and fish health. He has been with AquaLuxe for over 10 years.',
        'image' => get_template_directory_uri() . '/demo-content/images/team-3.jpg',
        'social' => array(
            'linkedin' => '#',
            'facebook' => '#',
        ),
    ),
    array(
        'name' => 'Sarah Johnson',
        'position' => 'Customer Experience Manager',
        'bio' => 'Sarah ensures that every customer receives exceptional service from initial inquiry through to delivery and follow-up care. She is passionate about helping hobbyists succeed.',
        'image' => get_template_directory_uri() . '/demo-content/images/team-4.jpg',
        'social' => array(
            'linkedin' => '#',
            'twitter' => '#',
            'instagram' => '#',
        ),
    ),
    array(
        'name' => 'David Thompson',
        'position' => 'Conservation Specialist',
        'bio' => 'David leads our conservation initiatives and partnerships with environmental organizations. He has a background in environmental science and is dedicated to protecting aquatic ecosystems.',
        'image' => get_template_directory_uri() . '/demo-content/images/team-5.jpg',
        'social' => array(
            'linkedin' => '#',
            'twitter' => '#',
        ),
    ),
    array(
        'name' => 'Lisa Patel',
        'position' => 'Aquatic Health Specialist',
        'bio' => 'Lisa monitors the health of our fish and develops protocols to ensure they remain disease-free. She has a doctorate in veterinary medicine with a specialization in aquatic species.',
        'image' => get_template_directory_uri() . '/demo-content/images/team-6.jpg',
        'social' => array(
            'linkedin' => '#',
            'facebook' => '#',
            'instagram' => '#',
        ),
    ),
);

// Filter team members through a hook to allow customization
$team_members = apply_filters( 'aqualuxe_about_team_members', $team_members );

// Return if no team members
if ( empty( $team_members ) ) {
    return;
}
?>

<section class="about-team-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html( $team_title ); ?></h2>
            <div class="section-subtitle"><?php echo wp_kses_post( $team_subtitle ); ?></div>
        </div>
        
        <div class="team-grid">
            <?php foreach ( $team_members as $member ) : ?>
                <div class="team-member">
                    <div class="team-member-inner">
                        <div class="team-member-image">
                            <img src="<?php echo esc_url( $member['image'] ); ?>" alt="<?php echo esc_attr( $member['name'] ); ?>">
                            
                            <?php if ( ! empty( $member['social'] ) ) : ?>
                                <div class="team-member-social">
                                    <?php foreach ( $member['social'] as $platform => $url ) : ?>
                                        <a href="<?php echo esc_url( $url ); ?>" class="social-icon <?php echo esc_attr( $platform ); ?>" target="_blank" rel="noopener">
                                            <span class="icon-<?php echo esc_attr( $platform ); ?>"></span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="team-member-content">
                            <h3 class="team-member-name"><?php echo esc_html( $member['name'] ); ?></h3>
                            <div class="team-member-position"><?php echo esc_html( $member['position'] ); ?></div>
                            <div class="team-member-bio">
                                <p><?php echo esc_html( $member['bio'] ); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
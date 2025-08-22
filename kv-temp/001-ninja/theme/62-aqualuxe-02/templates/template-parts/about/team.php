<?php
/**
 * About Page Team Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get team settings from customizer or ACF
$section_title = get_theme_mod( 'aqualuxe_about_team_title', __( 'Meet Our Team', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_about_team_subtitle', __( 'The talented people behind our success', 'aqualuxe' ) );
$team_layout = get_theme_mod( 'aqualuxe_about_team_layout', 'grid' );
$columns = get_theme_mod( 'aqualuxe_about_team_columns', 3 );
$show_social = get_theme_mod( 'aqualuxe_about_team_show_social', true );

// Default team members if not set in customizer
$default_team = array(
    array(
        'name'     => 'John Doe',
        'position' => 'CEO & Founder',
        'bio'      => 'John has over 15 years of experience in web development and e-commerce. He founded AquaLuxe with a vision to create the best WordPress themes for online businesses.',
        'image'    => get_template_directory_uri() . '/assets/images/about/team/team-1.jpg',
        'social'   => array(
            'linkedin' => 'https://linkedin.com/',
            'twitter'  => 'https://twitter.com/',
            'facebook' => '',
            'instagram' => '',
        ),
    ),
    array(
        'name'     => 'Jane Smith',
        'position' => 'Lead Designer',
        'bio'      => 'Jane brings her creative vision and expertise in UI/UX design to create beautiful and functional themes that delight our customers.',
        'image'    => get_template_directory_uri() . '/assets/images/about/team/team-2.jpg',
        'social'   => array(
            'linkedin' => 'https://linkedin.com/',
            'twitter'  => 'https://twitter.com/',
            'facebook' => '',
            'instagram' => 'https://instagram.com/',
        ),
    ),
    array(
        'name'     => 'Michael Johnson',
        'position' => 'Lead Developer',
        'bio'      => 'Michael is a WordPress expert with a passion for clean code and performance optimization. He leads our development team to create robust and scalable themes.',
        'image'    => get_template_directory_uri() . '/assets/images/about/team/team-3.jpg',
        'social'   => array(
            'linkedin' => 'https://linkedin.com/',
            'twitter'  => 'https://twitter.com/',
            'facebook' => '',
            'instagram' => '',
        ),
    ),
    array(
        'name'     => 'Emily Wilson',
        'position' => 'Customer Support Manager',
        'bio'      => 'Emily ensures that our customers receive the best support experience. Her team is dedicated to helping users get the most out of our themes.',
        'image'    => get_template_directory_uri() . '/assets/images/about/team/team-4.jpg',
        'social'   => array(
            'linkedin' => 'https://linkedin.com/',
            'twitter'  => '',
            'facebook' => '',
            'instagram' => 'https://instagram.com/',
        ),
    ),
);

// Get team members from customizer or use defaults
$team_members = array();
for ( $i = 1; $i <= 6; $i++ ) {
    $member_name = get_theme_mod( 'aqualuxe_about_team_member_' . $i . '_name', isset( $default_team[$i-1] ) ? $default_team[$i-1]['name'] : '' );
    $member_position = get_theme_mod( 'aqualuxe_about_team_member_' . $i . '_position', isset( $default_team[$i-1] ) ? $default_team[$i-1]['position'] : '' );
    $member_bio = get_theme_mod( 'aqualuxe_about_team_member_' . $i . '_bio', isset( $default_team[$i-1] ) ? $default_team[$i-1]['bio'] : '' );
    $member_image = get_theme_mod( 'aqualuxe_about_team_member_' . $i . '_image', isset( $default_team[$i-1] ) ? $default_team[$i-1]['image'] : '' );
    $member_linkedin = get_theme_mod( 'aqualuxe_about_team_member_' . $i . '_linkedin', isset( $default_team[$i-1] ) ? $default_team[$i-1]['social']['linkedin'] : '' );
    $member_twitter = get_theme_mod( 'aqualuxe_about_team_member_' . $i . '_twitter', isset( $default_team[$i-1] ) ? $default_team[$i-1]['social']['twitter'] : '' );
    $member_facebook = get_theme_mod( 'aqualuxe_about_team_member_' . $i . '_facebook', isset( $default_team[$i-1] ) ? $default_team[$i-1]['social']['facebook'] : '' );
    $member_instagram = get_theme_mod( 'aqualuxe_about_team_member_' . $i . '_instagram', isset( $default_team[$i-1] ) ? $default_team[$i-1]['social']['instagram'] : '' );
    
    if ( $member_name ) {
        $team_members[] = array(
            'name'     => $member_name,
            'position' => $member_position,
            'bio'      => $member_bio,
            'image'    => $member_image,
            'social'   => array(
                'linkedin'  => $member_linkedin,
                'twitter'   => $member_twitter,
                'facebook'  => $member_facebook,
                'instagram' => $member_instagram,
            ),
        );
    }
}

// Skip if no team members
if ( empty( $team_members ) ) {
    return;
}

// Team grid classes
$grid_classes = array( 'team-grid', 'layout-' . $team_layout, 'columns-' . $columns );
?>

<section class="team-section section">
    <div class="container">
        <div class="section-header text-center">
            <?php if ( $section_title ) : ?>
                <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_subtitle ) : ?>
                <div class="section-subtitle">
                    <p><?php echo wp_kses_post( $section_subtitle ); ?></p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="<?php echo esc_attr( implode( ' ', $grid_classes ) ); ?>">
            <?php foreach ( $team_members as $member ) : ?>
                <div class="team-member">
                    <?php if ( $member['image'] ) : ?>
                        <div class="member-image">
                            <img src="<?php echo esc_url( $member['image'] ); ?>" alt="<?php echo esc_attr( $member['name'] ); ?>">
                        </div>
                    <?php endif; ?>
                    
                    <div class="member-content">
                        <h3 class="member-name"><?php echo esc_html( $member['name'] ); ?></h3>
                        
                        <?php if ( $member['position'] ) : ?>
                            <div class="member-position"><?php echo esc_html( $member['position'] ); ?></div>
                        <?php endif; ?>
                        
                        <?php if ( $member['bio'] ) : ?>
                            <div class="member-bio">
                                <p><?php echo wp_kses_post( $member['bio'] ); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( $show_social ) : ?>
                            <div class="member-social">
                                <?php if ( ! empty( $member['social']['linkedin'] ) ) : ?>
                                    <a href="<?php echo esc_url( $member['social']['linkedin'] ); ?>" class="social-link linkedin" target="_blank" rel="noopener noreferrer">
                                        <i class="icon-linkedin"></i>
                                        <span class="screen-reader-text"><?php esc_html_e( 'LinkedIn', 'aqualuxe' ); ?></span>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ( ! empty( $member['social']['twitter'] ) ) : ?>
                                    <a href="<?php echo esc_url( $member['social']['twitter'] ); ?>" class="social-link twitter" target="_blank" rel="noopener noreferrer">
                                        <i class="icon-twitter"></i>
                                        <span class="screen-reader-text"><?php esc_html_e( 'Twitter', 'aqualuxe' ); ?></span>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ( ! empty( $member['social']['facebook'] ) ) : ?>
                                    <a href="<?php echo esc_url( $member['social']['facebook'] ); ?>" class="social-link facebook" target="_blank" rel="noopener noreferrer">
                                        <i class="icon-facebook"></i>
                                        <span class="screen-reader-text"><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></span>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ( ! empty( $member['social']['instagram'] ) ) : ?>
                                    <a href="<?php echo esc_url( $member['social']['instagram'] ); ?>" class="social-link instagram" target="_blank" rel="noopener noreferrer">
                                        <i class="icon-instagram"></i>
                                        <span class="screen-reader-text"><?php esc_html_e( 'Instagram', 'aqualuxe' ); ?></span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
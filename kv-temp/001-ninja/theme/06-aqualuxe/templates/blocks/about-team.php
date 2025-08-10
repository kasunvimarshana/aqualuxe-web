<?php
/**
 * About Team Block
 *
 * @package AquaLuxe
 */

// Get args from template part
$args = $args ?? array();

// Default values
$defaults = array(
    'team_title' => __( 'Meet Our Team', 'aqualuxe' ),
    'team_subtitle' => __( 'The passionate experts behind AquaLuxe', 'aqualuxe' ),
    'team_members' => array(
        array(
            'name' => 'John Smith',
            'position' => 'Founder & CEO',
            'bio' => 'John has over 20 years of experience in aquaculture and is passionate about ornamental fish breeding.',
            'image' => get_template_directory_uri() . '/assets/images/team/john-smith.jpg',
            'social' => array(
                'linkedin' => 'https://linkedin.com/',
                'twitter' => 'https://twitter.com/',
                'facebook' => '',
            ),
        ),
        array(
            'name' => 'Jane Doe',
            'position' => 'Head of Breeding Operations',
            'bio' => 'Jane oversees all breeding operations and has developed several innovative breeding techniques.',
            'image' => get_template_directory_uri() . '/assets/images/team/jane-doe.jpg',
            'social' => array(
                'linkedin' => 'https://linkedin.com/',
                'twitter' => '',
                'facebook' => 'https://facebook.com/',
            ),
        ),
        array(
            'name' => 'Mike Johnson',
            'position' => 'Chief Aquarium Designer',
            'bio' => 'Mike specializes in creating stunning aquascapes and has won numerous design awards.',
            'image' => get_template_directory_uri() . '/assets/images/team/mike-johnson.jpg',
            'social' => array(
                'linkedin' => 'https://linkedin.com/',
                'twitter' => 'https://twitter.com/',
                'facebook' => 'https://facebook.com/',
            ),
        ),
        array(
            'name' => 'Sarah Williams',
            'position' => 'Customer Experience Manager',
            'bio' => 'Sarah ensures that every customer receives exceptional service and support.',
            'image' => get_template_directory_uri() . '/assets/images/team/sarah-williams.jpg',
            'social' => array(
                'linkedin' => 'https://linkedin.com/',
                'twitter' => '',
                'facebook' => '',
            ),
        ),
    ),
);

// Merge defaults with args
$args = wp_parse_args( $args, $defaults );

// Extract variables
$title = $args['team_title'];
$subtitle = $args['team_subtitle'];
$team_members = $args['team_members'];

// Ensure we have team members
if ( empty( $team_members ) ) {
    $team_members = $defaults['team_members'];
}
?>

<section class="aqualuxe-about-team">
    <div class="aqualuxe-container">
        <div class="aqualuxe-section-header">
            <?php if ( ! empty( $title ) ) : ?>
                <h2 class="aqualuxe-section-title"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( ! empty( $subtitle ) ) : ?>
                <p class="aqualuxe-section-subtitle"><?php echo esc_html( $subtitle ); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="aqualuxe-team-grid">
            <?php foreach ( $team_members as $member ) : ?>
                <div class="aqualuxe-team-member">
                    <div class="aqualuxe-team-member-image">
                        <?php if ( ! empty( $member['image'] ) ) : ?>
                            <img src="<?php echo esc_url( $member['image'] ); ?>" alt="<?php echo esc_attr( $member['name'] ); ?>" />
                        <?php else : ?>
                            <div class="aqualuxe-team-member-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="aqualuxe-team-member-info">
                        <h3 class="aqualuxe-team-member-name"><?php echo esc_html( $member['name'] ); ?></h3>
                        <p class="aqualuxe-team-member-position"><?php echo esc_html( $member['position'] ); ?></p>
                        <p class="aqualuxe-team-member-bio"><?php echo esc_html( $member['bio'] ); ?></p>
                        
                        <?php if ( ! empty( $member['social'] ) ) : ?>
                            <div class="aqualuxe-team-member-social">
                                <?php if ( ! empty( $member['social']['linkedin'] ) ) : ?>
                                    <a href="<?php echo esc_url( $member['social']['linkedin'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( sprintf( __( '%s on LinkedIn', 'aqualuxe' ), $member['name'] ) ); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ( ! empty( $member['social']['twitter'] ) ) : ?>
                                    <a href="<?php echo esc_url( $member['social']['twitter'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( sprintf( __( '%s on Twitter', 'aqualuxe' ), $member['name'] ) ); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ( ! empty( $member['social']['facebook'] ) ) : ?>
                                    <a href="<?php echo esc_url( $member['social']['facebook'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( sprintf( __( '%s on Facebook', 'aqualuxe' ), $member['name'] ) ); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
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
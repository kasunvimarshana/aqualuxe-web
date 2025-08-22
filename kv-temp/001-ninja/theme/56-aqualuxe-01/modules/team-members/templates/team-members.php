<?php
/**
 * Team Members Template
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Extract variables
extract( $args );

// Set CSS classes based on settings
$team_classes = [
    'team-members',
    'team-members--' . $layout,
    'team-members--' . $style,
];

if ( 'none' !== $animation ) {
    $team_classes[] = 'team-members--animate-' . $animation;
}

$team_class = implode( ' ', $team_classes );

// Get column class based on columns setting
$column_class = 'team-members__item';
switch ( $columns ) {
    case 1:
        $column_class .= ' col-12';
        break;
    case 2:
        $column_class .= ' col-md-6';
        break;
    case 3:
        $column_class .= ' col-md-6 col-lg-4';
        break;
    case 4:
        $column_class .= ' col-md-6 col-lg-3';
        break;
    default:
        $column_class .= ' col-md-6 col-lg-3';
}

// Set member item class based on style
$member_item_class = 'team-members__member';
if ( 'cards' === $style ) {
    $member_item_class .= ' team-members__member--card';
} elseif ( 'minimal' === $style ) {
    $member_item_class .= ' team-members__member--minimal';
} elseif ( 'bordered' === $style ) {
    $member_item_class .= ' team-members__member--bordered';
} elseif ( 'overlay' === $style ) {
    $member_item_class .= ' team-members__member--overlay';
} elseif ( 'circle' === $style ) {
    $member_item_class .= ' team-members__member--circle';
}

// Check if we have members
if ( empty( $members ) ) {
    return;
}
?>

<section class="<?php echo esc_attr( $team_class ); ?>">
    <div class="container">
        <?php if ( ! empty( $title ) || ! empty( $subtitle ) || ! empty( $description ) ) : ?>
            <div class="team-members__header">
                <?php if ( ! empty( $subtitle ) ) : ?>
                    <h3 class="team-members__subtitle"><?php echo esc_html( $subtitle ); ?></h3>
                <?php endif; ?>

                <?php if ( ! empty( $title ) ) : ?>
                    <h2 class="team-members__title"><?php echo esc_html( $title ); ?></h2>
                <?php endif; ?>

                <?php if ( ! empty( $description ) ) : ?>
                    <div class="team-members__description">
                        <?php echo wp_kses_post( $description ); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ( 'carousel' === $layout ) : ?>
            <div class="team-members__carousel swiper">
                <div class="swiper-wrapper">
                    <?php foreach ( $members as $member ) : ?>
                        <div class="swiper-slide">
                            <div class="<?php echo esc_attr( $member_item_class ); ?>">
                                <?php if ( ! empty( $member['image'] ) ) : ?>
                                    <div class="team-members__member-image">
                                        <img src="<?php echo esc_url( $member['image'] ); ?>" alt="<?php echo esc_attr( $member['name'] ); ?>">
                                        
                                        <?php if ( 'overlay' === $style && $show_social ) : ?>
                                            <div class="team-members__member-social">
                                                <?php if ( ! empty( $member['social']['linkedin'] ) ) : ?>
                                                    <a href="<?php echo esc_url( $member['social']['linkedin'] ); ?>" class="team-members__social-link" target="_blank" rel="noopener noreferrer">
                                                        <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                                                        <span class="screen-reader-text"><?php esc_html_e( 'LinkedIn', 'aqualuxe' ); ?></span>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <?php if ( ! empty( $member['social']['twitter'] ) ) : ?>
                                                    <a href="<?php echo esc_url( $member['social']['twitter'] ); ?>" class="team-members__social-link" target="_blank" rel="noopener noreferrer">
                                                        <i class="fab fa-twitter" aria-hidden="true"></i>
                                                        <span class="screen-reader-text"><?php esc_html_e( 'Twitter', 'aqualuxe' ); ?></span>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <?php if ( ! empty( $member['social']['facebook'] ) ) : ?>
                                                    <a href="<?php echo esc_url( $member['social']['facebook'] ); ?>" class="team-members__social-link" target="_blank" rel="noopener noreferrer">
                                                        <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                                        <span class="screen-reader-text"><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></span>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <?php if ( ! empty( $member['social']['instagram'] ) ) : ?>
                                                    <a href="<?php echo esc_url( $member['social']['instagram'] ); ?>" class="team-members__social-link" target="_blank" rel="noopener noreferrer">
                                                        <i class="fab fa-instagram" aria-hidden="true"></i>
                                                        <span class="screen-reader-text"><?php esc_html_e( 'Instagram', 'aqualuxe' ); ?></span>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="team-members__member-content">
                                    <?php if ( ! empty( $member['name'] ) ) : ?>
                                        <h4 class="team-members__member-name"><?php echo esc_html( $member['name'] ); ?></h4>
                                    <?php endif; ?>

                                    <?php if ( ! empty( $member['position'] ) ) : ?>
                                        <p class="team-members__member-position"><?php echo esc_html( $member['position'] ); ?></p>
                                    <?php endif; ?>

                                    <?php if ( $show_bio && ! empty( $member['bio'] ) ) : ?>
                                        <div class="team-members__member-bio">
                                            <?php echo wp_kses_post( $member['bio'] ); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ( $show_social && 'overlay' !== $style ) : ?>
                                        <div class="team-members__member-social">
                                            <?php if ( ! empty( $member['social']['linkedin'] ) ) : ?>
                                                <a href="<?php echo esc_url( $member['social']['linkedin'] ); ?>" class="team-members__social-link" target="_blank" rel="noopener noreferrer">
                                                    <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                                                    <span class="screen-reader-text"><?php esc_html_e( 'LinkedIn', 'aqualuxe' ); ?></span>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if ( ! empty( $member['social']['twitter'] ) ) : ?>
                                                <a href="<?php echo esc_url( $member['social']['twitter'] ); ?>" class="team-members__social-link" target="_blank" rel="noopener noreferrer">
                                                    <i class="fab fa-twitter" aria-hidden="true"></i>
                                                    <span class="screen-reader-text"><?php esc_html_e( 'Twitter', 'aqualuxe' ); ?></span>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if ( ! empty( $member['social']['facebook'] ) ) : ?>
                                                <a href="<?php echo esc_url( $member['social']['facebook'] ); ?>" class="team-members__social-link" target="_blank" rel="noopener noreferrer">
                                                    <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                                    <span class="screen-reader-text"><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></span>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if ( ! empty( $member['social']['instagram'] ) ) : ?>
                                                <a href="<?php echo esc_url( $member['social']['instagram'] ); ?>" class="team-members__social-link" target="_blank" rel="noopener noreferrer">
                                                    <i class="fab fa-instagram" aria-hidden="true"></i>
                                                    <span class="screen-reader-text"><?php esc_html_e( 'Instagram', 'aqualuxe' ); ?></span>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="team-members__pagination swiper-pagination"></div>
                <div class="team-members__navigation">
                    <div class="team-members__button-prev swiper-button-prev"></div>
                    <div class="team-members__button-next swiper-button-next"></div>
                </div>
            </div>
        <?php elseif ( 'list' === $layout ) : ?>
            <div class="team-members__list">
                <?php foreach ( $members as $member ) : ?>
                    <div class="<?php echo esc_attr( $member_item_class ); ?>">
                        <div class="team-members__member-wrapper">
                            <?php if ( ! empty( $member['image'] ) ) : ?>
                                <div class="team-members__member-image">
                                    <img src="<?php echo esc_url( $member['image'] ); ?>" alt="<?php echo esc_attr( $member['name'] ); ?>">
                                </div>
                            <?php endif; ?>

                            <div class="team-members__member-content">
                                <?php if ( ! empty( $member['name'] ) ) : ?>
                                    <h4 class="team-members__member-name"><?php echo esc_html( $member['name'] ); ?></h4>
                                <?php endif; ?>

                                <?php if ( ! empty( $member['position'] ) ) : ?>
                                    <p class="team-members__member-position"><?php echo esc_html( $member['position'] ); ?></p>
                                <?php endif; ?>

                                <?php if ( $show_bio && ! empty( $member['bio'] ) ) : ?>
                                    <div class="team-members__member-bio">
                                        <?php echo wp_kses_post( $member['bio'] ); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ( $show_social ) : ?>
                                    <div class="team-members__member-social">
                                        <?php if ( ! empty( $member['social']['linkedin'] ) ) : ?>
                                            <a href="<?php echo esc_url( $member['social']['linkedin'] ); ?>" class="team-members__social-link" target="_blank" rel="noopener noreferrer">
                                                <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                                                <span class="screen-reader-text"><?php esc_html_e( 'LinkedIn', 'aqualuxe' ); ?></span>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ( ! empty( $member['social']['twitter'] ) ) : ?>
                                            <a href="<?php echo esc_url( $member['social']['twitter'] ); ?>" class="team-members__social-link" target="_blank" rel="noopener noreferrer">
                                                <i class="fab fa-twitter" aria-hidden="true"></i>
                                                <span class="screen-reader-text"><?php esc_html_e( 'Twitter', 'aqualuxe' ); ?></span>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ( ! empty( $member['social']['facebook'] ) ) : ?>
                                            <a href="<?php echo esc_url( $member['social']['facebook'] ); ?>" class="team-members__social-link" target="_blank" rel="noopener noreferrer">
                                                <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                                <span class="screen-reader-text"><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></span>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ( ! empty( $member['social']['instagram'] ) ) : ?>
                                            <a href="<?php echo esc_url( $member['social']['instagram'] ); ?>" class="team-members__social-link" target="_blank" rel="noopener noreferrer">
                                                <i class="fab fa-instagram" aria-hidden="true"></i>
                                                <span class="screen-reader-text"><?php esc_html_e( 'Instagram', 'aqualuxe' ); ?></span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="team-members__grid row">
                <?php foreach ( $members as $index => $member ) : ?>
                    <div class="<?php echo esc_attr( $column_class ); ?>" data-aos="<?php echo esc_attr( $animation ); ?>" data-aos-delay="<?php echo esc_attr( $index * 100 ); ?>">
                        <div class="<?php echo esc_attr( $member_item_class ); ?>">
                            <?php if ( ! empty( $member['image'] ) ) : ?>
                                <div class="team-members__member-image">
                                    <img src="<?php echo esc_url( $member['image'] ); ?>" alt="<?php echo esc_attr( $member['name'] ); ?>">
                                    
                                    <?php if ( 'overlay' === $style && $show_social ) : ?>
                                        <div class="team-members__member-social">
                                            <?php if ( ! empty( $member['social']['linkedin'] ) ) : ?>
                                                <a href="<?php echo esc_url( $member['social']['linkedin'] ); ?>" class="team-members__social-link" target="_blank" rel="noopener noreferrer">
                                                    <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                                                    <span class="screen-reader-text"><?php esc_html_e( 'LinkedIn', 'aqualuxe' ); ?></span>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if ( ! empty( $member['social']['twitter'] ) ) : ?>
                                                <a href="<?php echo esc_url( $member['social']['twitter'] ); ?>" class="team-members__social-link" target="_blank" rel="noopener noreferrer">
                                                    <i class="fab fa-twitter" aria-hidden="true"></i>
                                                    <span class="screen-reader-text"><?php esc_html_e( 'Twitter', 'aqualuxe' ); ?></span>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if ( ! empty( $member['social']['facebook'] ) ) : ?>
                                                <a href="<?php echo esc_url( $member['social']['facebook'] ); ?>" class="team-members__social-link" target="_blank" rel="noopener noreferrer">
                                                    <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                                    <span class="screen-reader-text"><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></span>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if ( ! empty( $member['social']['instagram'] ) ) : ?>
                                                <a href="<?php echo esc_url( $member['social']['instagram'] ); ?>" class="team-members__social-link" target="_blank" rel="noopener noreferrer">
                                                    <i class="fab fa-instagram" aria-hidden="true"></i>
                                                    <span class="screen-reader-text"><?php esc_html_e( 'Instagram', 'aqualuxe' ); ?></span>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <div class="team-members__member-content">
                                <?php if ( ! empty( $member['name'] ) ) : ?>
                                    <h4 class="team-members__member-name"><?php echo esc_html( $member['name'] ); ?></h4>
                                <?php endif; ?>

                                <?php if ( ! empty( $member['position'] ) ) : ?>
                                    <p class="team-members__member-position"><?php echo esc_html( $member['position'] ); ?></p>
                                <?php endif; ?>

                                <?php if ( $show_bio && ! empty( $member['bio'] ) ) : ?>
                                    <div class="team-members__member-bio">
                                        <?php echo wp_kses_post( $member['bio'] ); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ( $show_social && 'overlay' !== $style ) : ?>
                                    <div class="team-members__member-social">
                                        <?php if ( ! empty( $member['social']['linkedin'] ) ) : ?>
                                            <a href="<?php echo esc_url( $member['social']['linkedin'] ); ?>" class="team-members__social-link" target="_blank" rel="noopener noreferrer">
                                                <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                                                <span class="screen-reader-text"><?php esc_html_e( 'LinkedIn', 'aqualuxe' ); ?></span>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ( ! empty( $member['social']['twitter'] ) ) : ?>
                                            <a href="<?php echo esc_url( $member['social']['twitter'] ); ?>" class="team-members__social-link" target="_blank" rel="noopener noreferrer">
                                                <i class="fab fa-twitter" aria-hidden="true"></i>
                                                <span class="screen-reader-text"><?php esc_html_e( 'Twitter', 'aqualuxe' ); ?></span>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ( ! empty( $member['social']['facebook'] ) ) : ?>
                                            <a href="<?php echo esc_url( $member['social']['facebook'] ); ?>" class="team-members__social-link" target="_blank" rel="noopener noreferrer">
                                                <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                                <span class="screen-reader-text"><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></span>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ( ! empty( $member['social']['instagram'] ) ) : ?>
                                            <a href="<?php echo esc_url( $member['social']['instagram'] ); ?>" class="team-members__social-link" target="_blank" rel="noopener noreferrer">
                                                <i class="fab fa-instagram" aria-hidden="true"></i>
                                                <span class="screen-reader-text"><?php esc_html_e( 'Instagram', 'aqualuxe' ); ?></span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php
/**
 * Template part for displaying vertical language switcher
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get args
$args = isset( $args ) ? $args : array();

// Default arguments
$defaults = array(
    'location' => 'header',
    'show_flags' => true,
    'show_names' => true,
);

// Parse arguments
$args = wp_parse_args( $args, $defaults );

// Get languages
$languages = aqualuxe_get_languages();

// Get current language
$current_language = aqualuxe_get_current_language();

// Check if we have languages
if ( empty( $languages ) || count( $languages ) <= 1 ) {
    return;
}
?>

<div class="aqualuxe-language-switcher aqualuxe-language-switcher--vertical aqualuxe-language-switcher--<?php echo esc_attr( $args['location'] ); ?>">
    <ul class="aqualuxe-language-switcher__list aqualuxe-language-switcher__list--vertical">
        <?php foreach ( $languages as $code => $language ) : ?>
            <?php
            $is_current = $code === $current_language;
            $item_class = $is_current ? 'aqualuxe-language-switcher__item aqualuxe-language-switcher__item--current' : 'aqualuxe-language-switcher__item';
            $link_class = $is_current ? 'aqualuxe-language-switcher__link aqualuxe-language-switcher__link--current' : 'aqualuxe-language-switcher__link';
            ?>
            
            <li class="<?php echo esc_attr( $item_class ); ?>">
                <?php if ( $is_current ) : ?>
                    <span class="<?php echo esc_attr( $link_class ); ?>">
                <?php else : ?>
                    <a href="<?php echo esc_url( $language['url'] ); ?>" class="<?php echo esc_attr( $link_class ); ?>">
                <?php endif; ?>
                
                <?php if ( $args['show_flags'] && ! empty( $language['flag'] ) ) : ?>
                    <img src="<?php echo esc_url( $language['flag'] ); ?>" alt="<?php echo esc_attr( $language['name'] ); ?>" class="aqualuxe-language-switcher__flag" />
                <?php endif; ?>
                
                <?php if ( $args['show_names'] ) : ?>
                    <span class="aqualuxe-language-switcher__name"><?php echo esc_html( $language['name'] ); ?></span>
                <?php endif; ?>
                
                <?php if ( $is_current ) : ?>
                    </span>
                <?php else : ?>
                    </a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
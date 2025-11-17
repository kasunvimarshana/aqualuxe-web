<?php
/**
 * Template part for displaying language switcher dropdown
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

<div class="aqualuxe-language-switcher aqualuxe-language-switcher--dropdown aqualuxe-language-switcher--<?php echo esc_attr( $args['location'] ); ?>">
    <div class="aqualuxe-language-switcher__dropdown">
        <div class="aqualuxe-language-switcher__current">
            <?php if ( $args['show_flags'] && ! empty( $languages[ $current_language ]['flag'] ) ) : ?>
                <img src="<?php echo esc_url( $languages[ $current_language ]['flag'] ); ?>" alt="<?php echo esc_attr( $languages[ $current_language ]['name'] ); ?>" class="aqualuxe-language-switcher__flag" />
            <?php endif; ?>
            
            <?php if ( $args['show_names'] ) : ?>
                <span class="aqualuxe-language-switcher__name"><?php echo esc_html( $languages[ $current_language ]['name'] ); ?></span>
            <?php endif; ?>
            
            <span class="aqualuxe-language-switcher__icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 16l-6-6h12z"/></svg>
            </span>
        </div>
        
        <ul class="aqualuxe-language-switcher__list">
            <?php foreach ( $languages as $code => $language ) : ?>
                <?php if ( $code === $current_language ) continue; ?>
                
                <li class="aqualuxe-language-switcher__item">
                    <a href="<?php echo esc_url( $language['url'] ); ?>" class="aqualuxe-language-switcher__link">
                        <?php if ( $args['show_flags'] && ! empty( $language['flag'] ) ) : ?>
                            <img src="<?php echo esc_url( $language['flag'] ); ?>" alt="<?php echo esc_attr( $language['name'] ); ?>" class="aqualuxe-language-switcher__flag" />
                        <?php endif; ?>
                        
                        <?php if ( $args['show_names'] ) : ?>
                            <span class="aqualuxe-language-switcher__name"><?php echo esc_html( $language['name'] ); ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
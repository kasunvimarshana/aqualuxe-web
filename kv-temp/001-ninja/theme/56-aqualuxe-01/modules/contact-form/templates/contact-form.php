<?php
/**
 * Template for Contact Form Module
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get module settings
$title = $this->get_setting( 'title' );
$subtitle = $this->get_setting( 'subtitle' );
$description = $this->get_setting( 'description' );
$layout = $this->get_setting( 'layout', 'default' );
$style = $this->get_setting( 'style', 'default' );
$background_type = $this->get_setting( 'background_type', 'color' );
$background_color = $this->get_setting( 'background_color', '#f8fafc' );
$text_color = $this->get_setting( 'text_color', '#1e293b' );
$accent_color = $this->get_setting( 'accent_color', '#0ea5e9' );
$submit_button_text = $this->get_setting( 'submit_button_text', __( 'Send Message', 'aqualuxe' ) );
$contact_info = $this->get_setting( 'contact_info', [] );
$social_links = $this->get_setting( 'social_links', [] );
$map = $this->get_setting( 'map', [] );
$animation = $this->get_setting( 'animation', 'fade' );

// Get enabled form fields
$enabled_fields = $this->get_enabled_fields();

// Module classes
$module_classes = [
    'aqualuxe-contact-form',
    'aqualuxe-module',
    'layout-' . $layout,
    'style-' . $style,
    'bg-' . $background_type,
    'animation-' . $animation,
];

$module_class = implode( ' ', $module_classes );

// Inline styles for colors
$inline_styles = '';
if ( $background_type === 'color' && ! empty( $background_color ) ) {
    $inline_styles .= '--contact-bg-color: ' . esc_attr( $background_color ) . ';';
}
if ( ! empty( $text_color ) ) {
    $inline_styles .= '--contact-text-color: ' . esc_attr( $text_color ) . ';';
}
if ( ! empty( $accent_color ) ) {
    $inline_styles .= '--contact-accent-color: ' . esc_attr( $accent_color ) . ';';
}
?>

<section id="<?php echo esc_attr( $this->get_id() ); ?>" class="<?php echo esc_attr( $module_class ); ?>" style="<?php echo esc_attr( $inline_styles ); ?>">
    <div class="container mx-auto px-4">
        <?php if ( $title || $subtitle || $description ) : ?>
            <div class="aqualuxe-contact-form__header text-center mb-12">
                <?php if ( $subtitle ) : ?>
                    <div class="aqualuxe-contact-form__subtitle text-primary text-sm uppercase tracking-wider font-semibold mb-2">
                        <?php echo esc_html( $subtitle ); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( $title ) : ?>
                    <h2 class="aqualuxe-contact-form__title text-3xl md:text-4xl font-bold mb-4">
                        <?php echo esc_html( $title ); ?>
                    </h2>
                <?php endif; ?>
                
                <?php if ( $description ) : ?>
                    <div class="aqualuxe-contact-form__description max-w-2xl mx-auto">
                        <?php echo wp_kses_post( $description ); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="aqualuxe-contact-form__content">
            <?php if ( $layout === 'split' ) : ?>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="aqualuxe-contact-form__info">
                        <?php $this->render_contact_info( $contact_info, $social_links, $map ); ?>
                    </div>
                    <div class="aqualuxe-contact-form__form-container">
                        <?php $this->render_form( $enabled_fields, $submit_button_text ); ?>
                    </div>
                </div>
            <?php elseif ( $layout === 'centered' ) : ?>
                <div class="max-w-2xl mx-auto">
                    <?php $this->render_form( $enabled_fields, $submit_button_text ); ?>
                    
                    <?php if ( isset( $contact_info['show'] ) && $contact_info['show'] ) : ?>
                        <div class="aqualuxe-contact-form__info mt-12">
                            <?php $this->render_contact_info( $contact_info, $social_links, $map ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php elseif ( $layout === 'boxed' ) : ?>
                <div class="max-w-4xl mx-auto">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="aqualuxe-contact-form__info">
                                <?php $this->render_contact_info( $contact_info, $social_links, $map ); ?>
                            </div>
                            <div class="aqualuxe-contact-form__form-container">
                                <?php $this->render_form( $enabled_fields, $submit_button_text ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif ( $layout === 'full-width' ) : ?>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-1 aqualuxe-contact-form__info">
                        <?php $this->render_contact_info( $contact_info, $social_links, $map ); ?>
                    </div>
                    <div class="lg:col-span-2 aqualuxe-contact-form__form-container">
                        <?php $this->render_form( $enabled_fields, $submit_button_text ); ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="md:col-span-2 aqualuxe-contact-form__form-container">
                        <?php $this->render_form( $enabled_fields, $submit_button_text ); ?>
                    </div>
                    <div class="md:col-span-1 aqualuxe-contact-form__info">
                        <?php $this->render_contact_info( $contact_info, $social_links, $map ); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if ( isset( $map['show'] ) && $map['show'] ) : ?>
            <div class="aqualuxe-contact-form__map mt-12">
                <div id="aqualuxe-map" class="h-96 w-full rounded-lg overflow-hidden"></div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
/**
 * Render contact form
 *
 * @param array $fields Form fields.
 * @param string $submit_button_text Submit button text.
 * @return void
 */
function render_form( $fields, $submit_button_text ) {
    ?>
    <form class="aqualuxe-contact-form__form" method="post">
        <div class="form-response hidden"></div>
        
        <div class="form-fields space-y-6">
            <?php foreach ( $fields as $field_id => $field ) : ?>
                <?php if ( $field['type'] === 'checkbox' ) : ?>
                    <div class="form-field form-field--<?php echo esc_attr( $field_id ); ?>">
                        <label class="flex items-start">
                            <input 
                                type="checkbox" 
                                name="<?php echo esc_attr( $field_id ); ?>" 
                                id="<?php echo esc_attr( $field_id ); ?>" 
                                class="mt-1 mr-2"
                                <?php echo $field['required'] ? 'required' : ''; ?>
                            >
                            <span><?php echo esc_html( $field['label'] ); ?></span>
                        </label>
                        <div class="form-error text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                <?php elseif ( $field['type'] === 'textarea' ) : ?>
                    <div class="form-field form-field--<?php echo esc_attr( $field_id ); ?>">
                        <label for="<?php echo esc_attr( $field_id ); ?>" class="block mb-2 font-medium">
                            <?php echo esc_html( $field['label'] ); ?>
                            <?php if ( $field['required'] ) : ?>
                                <span class="text-red-500">*</span>
                            <?php endif; ?>
                        </label>
                        <textarea 
                            name="<?php echo esc_attr( $field_id ); ?>" 
                            id="<?php echo esc_attr( $field_id ); ?>" 
                            rows="5" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            <?php echo $field['required'] ? 'required' : ''; ?>
                        ></textarea>
                        <div class="form-error text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                <?php elseif ( $field['type'] === 'select' && isset( $field['options'] ) && ! empty( $field['options'] ) ) : ?>
                    <div class="form-field form-field--<?php echo esc_attr( $field_id ); ?>">
                        <label for="<?php echo esc_attr( $field_id ); ?>" class="block mb-2 font-medium">
                            <?php echo esc_html( $field['label'] ); ?>
                            <?php if ( $field['required'] ) : ?>
                                <span class="text-red-500">*</span>
                            <?php endif; ?>
                        </label>
                        <select 
                            name="<?php echo esc_attr( $field_id ); ?>" 
                            id="<?php echo esc_attr( $field_id ); ?>" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            <?php echo $field['required'] ? 'required' : ''; ?>
                        >
                            <?php foreach ( $field['options'] as $option_value => $option_label ) : ?>
                                <option value="<?php echo esc_attr( $option_value ); ?>"><?php echo esc_html( $option_label ); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-error text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                <?php else : ?>
                    <div class="form-field form-field--<?php echo esc_attr( $field_id ); ?>">
                        <label for="<?php echo esc_attr( $field_id ); ?>" class="block mb-2 font-medium">
                            <?php echo esc_html( $field['label'] ); ?>
                            <?php if ( $field['required'] ) : ?>
                                <span class="text-red-500">*</span>
                            <?php endif; ?>
                        </label>
                        <input 
                            type="<?php echo esc_attr( $field['type'] ); ?>" 
                            name="<?php echo esc_attr( $field_id ); ?>" 
                            id="<?php echo esc_attr( $field_id ); ?>" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            <?php echo $field['required'] ? 'required' : ''; ?>
                        >
                        <div class="form-error text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <div class="form-submit">
                <button type="submit" class="submit-btn bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-md transition-colors duration-300">
                    <?php echo esc_html( $submit_button_text ); ?>
                </button>
                <div class="submit-spinner hidden ml-3 inline-block">
                    <svg class="animate-spin h-5 w-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </form>
    <?php
}

/**
 * Render contact information
 *
 * @param array $contact_info Contact information.
 * @param array $social_links Social links.
 * @param array $map Map settings.
 * @return void
 */
function render_contact_info( $contact_info, $social_links, $map ) {
    if ( ! isset( $contact_info['show'] ) || ! $contact_info['show'] ) {
        return;
    }
    ?>
    <div class="contact-info">
        <?php if ( ! empty( $contact_info['address'] ) ) : ?>
            <div class="contact-info__item flex items-start mb-6">
                <div class="contact-info__icon mr-4 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div class="contact-info__content">
                    <h4 class="text-lg font-semibold mb-1"><?php esc_html_e( 'Address', 'aqualuxe' ); ?></h4>
                    <p><?php echo esc_html( $contact_info['address'] ); ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ( ! empty( $contact_info['phone'] ) ) : ?>
            <div class="contact-info__item flex items-start mb-6">
                <div class="contact-info__icon mr-4 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <div class="contact-info__content">
                    <h4 class="text-lg font-semibold mb-1"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></h4>
                    <p><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $contact_info['phone'] ) ); ?>" class="hover:text-primary transition-colors duration-300"><?php echo esc_html( $contact_info['phone'] ); ?></a></p>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ( ! empty( $contact_info['email'] ) ) : ?>
            <div class="contact-info__item flex items-start mb-6">
                <div class="contact-info__icon mr-4 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="contact-info__content">
                    <h4 class="text-lg font-semibold mb-1"><?php esc_html_e( 'Email', 'aqualuxe' ); ?></h4>
                    <p><a href="mailto:<?php echo esc_attr( $contact_info['email'] ); ?>" class="hover:text-primary transition-colors duration-300"><?php echo esc_html( $contact_info['email'] ); ?></a></p>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ( ! empty( $contact_info['hours'] ) ) : ?>
            <div class="contact-info__item flex items-start mb-6">
                <div class="contact-info__icon mr-4 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="contact-info__content">
                    <h4 class="text-lg font-semibold mb-1"><?php esc_html_e( 'Business Hours', 'aqualuxe' ); ?></h4>
                    <p><?php echo esc_html( $contact_info['hours'] ); ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ( isset( $social_links['show'] ) && $social_links['show'] ) : ?>
            <div class="contact-info__social mt-8">
                <h4 class="text-lg font-semibold mb-3"><?php esc_html_e( 'Follow Us', 'aqualuxe' ); ?></h4>
                <div class="flex space-x-4">
                    <?php if ( ! empty( $social_links['facebook'] ) ) : ?>
                        <a href="<?php echo esc_url( $social_links['facebook'] ); ?>" class="text-gray-600 hover:text-primary dark:text-gray-400 transition-colors duration-300" target="_blank" rel="noopener noreferrer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $social_links['twitter'] ) ) : ?>
                        <a href="<?php echo esc_url( $social_links['twitter'] ); ?>" class="text-gray-600 hover:text-primary dark:text-gray-400 transition-colors duration-300" target="_blank" rel="noopener noreferrer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $social_links['instagram'] ) ) : ?>
                        <a href="<?php echo esc_url( $social_links['instagram'] ); ?>" class="text-gray-600 hover:text-primary dark:text-gray-400 transition-colors duration-300" target="_blank" rel="noopener noreferrer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $social_links['linkedin'] ) ) : ?>
                        <a href="<?php echo esc_url( $social_links['linkedin'] ); ?>" class="text-gray-600 hover:text-primary dark:text-gray-400 transition-colors duration-300" target="_blank" rel="noopener noreferrer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php
}
?>
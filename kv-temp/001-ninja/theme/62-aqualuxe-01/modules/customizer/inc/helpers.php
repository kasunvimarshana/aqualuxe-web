<?php
/**
 * AquaLuxe Customizer Module Helper Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/Customizer
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Check if customizer module is active
 *
 * @return bool
 */
function aqualuxe_is_customizer_active() {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'customizer' );
    
    if ( $module ) {
        return true;
    }
    
    return false;
}

/**
 * Get Google Fonts
 *
 * @return array
 */
function aqualuxe_get_google_fonts() {
    return array(
        'inherit' => 'Default',
        'Arial, sans-serif' => 'Arial',
        'Helvetica, sans-serif' => 'Helvetica',
        'Georgia, serif' => 'Georgia',
        'Tahoma, sans-serif' => 'Tahoma',
        'Verdana, sans-serif' => 'Verdana',
        'Roboto, sans-serif' => 'Roboto',
        'Open Sans, sans-serif' => 'Open Sans',
        'Lato, sans-serif' => 'Lato',
        'Montserrat, sans-serif' => 'Montserrat',
        'Raleway, sans-serif' => 'Raleway',
        'Poppins, sans-serif' => 'Poppins',
        'Nunito, sans-serif' => 'Nunito',
        'Playfair Display, serif' => 'Playfair Display',
        'Merriweather, serif' => 'Merriweather',
        'Lora, serif' => 'Lora',
        'PT Serif, serif' => 'PT Serif',
        'Ubuntu, sans-serif' => 'Ubuntu',
        'Titillium Web, sans-serif' => 'Titillium Web',
        'Source Sans Pro, sans-serif' => 'Source Sans Pro',
    );
}

/**
 * Get font weights
 *
 * @return array
 */
function aqualuxe_get_font_weights() {
    return array(
        '100' => __( 'Thin 100', 'aqualuxe' ),
        '200' => __( 'Extra Light 200', 'aqualuxe' ),
        '300' => __( 'Light 300', 'aqualuxe' ),
        '400' => __( 'Regular 400', 'aqualuxe' ),
        '500' => __( 'Medium 500', 'aqualuxe' ),
        '600' => __( 'Semi Bold 600', 'aqualuxe' ),
        '700' => __( 'Bold 700', 'aqualuxe' ),
        '800' => __( 'Extra Bold 800', 'aqualuxe' ),
        '900' => __( 'Black 900', 'aqualuxe' ),
    );
}

/**
 * Get text transforms
 *
 * @return array
 */
function aqualuxe_get_text_transforms() {
    return array(
        'none' => __( 'None', 'aqualuxe' ),
        'capitalize' => __( 'Capitalize', 'aqualuxe' ),
        'uppercase' => __( 'Uppercase', 'aqualuxe' ),
        'lowercase' => __( 'Lowercase', 'aqualuxe' ),
    );
}

/**
 * Get site layouts
 *
 * @return array
 */
function aqualuxe_get_site_layouts() {
    return array(
        'wide' => __( 'Wide', 'aqualuxe' ),
        'boxed' => __( 'Boxed', 'aqualuxe' ),
    );
}

/**
 * Get header layouts
 *
 * @return array
 */
function aqualuxe_get_header_layouts() {
    return array(
        'default' => __( 'Default', 'aqualuxe' ),
        'centered' => __( 'Centered', 'aqualuxe' ),
        'split' => __( 'Split', 'aqualuxe' ),
        'minimal' => __( 'Minimal', 'aqualuxe' ),
    );
}

/**
 * Get footer layouts
 *
 * @return array
 */
function aqualuxe_get_footer_layouts() {
    return array(
        'default' => __( 'Default', 'aqualuxe' ),
        'centered' => __( 'Centered', 'aqualuxe' ),
        'minimal' => __( 'Minimal', 'aqualuxe' ),
    );
}

/**
 * Get blog layouts
 *
 * @return array
 */
function aqualuxe_get_blog_layouts() {
    return array(
        'standard' => __( 'Standard', 'aqualuxe' ),
        'grid' => __( 'Grid', 'aqualuxe' ),
        'masonry' => __( 'Masonry', 'aqualuxe' ),
    );
}

/**
 * Get sidebar positions
 *
 * @return array
 */
function aqualuxe_get_sidebar_positions() {
    return array(
        'right' => __( 'Right', 'aqualuxe' ),
        'left' => __( 'Left', 'aqualuxe' ),
        'none' => __( 'None', 'aqualuxe' ),
    );
}

/**
 * Get featured image sizes
 *
 * @return array
 */
function aqualuxe_get_featured_image_sizes() {
    return array(
        'thumbnail' => __( 'Thumbnail', 'aqualuxe' ),
        'medium' => __( 'Medium', 'aqualuxe' ),
        'large' => __( 'Large', 'aqualuxe' ),
        'full' => __( 'Full', 'aqualuxe' ),
    );
}

/**
 * Get post meta positions
 *
 * @return array
 */
function aqualuxe_get_post_meta_positions() {
    return array(
        'below-title' => __( 'Below Title', 'aqualuxe' ),
        'above-title' => __( 'Above Title', 'aqualuxe' ),
    );
}

/**
 * Get alignment options
 *
 * @return array
 */
function aqualuxe_get_alignment_options() {
    return array(
        'left' => __( 'Left', 'aqualuxe' ),
        'center' => __( 'Center', 'aqualuxe' ),
        'right' => __( 'Right', 'aqualuxe' ),
    );
}

/**
 * Get social networks
 *
 * @return array
 */
function aqualuxe_get_social_networks() {
    return array(
        'facebook' => __( 'Facebook', 'aqualuxe' ),
        'twitter' => __( 'Twitter', 'aqualuxe' ),
        'instagram' => __( 'Instagram', 'aqualuxe' ),
        'linkedin' => __( 'LinkedIn', 'aqualuxe' ),
        'youtube' => __( 'YouTube', 'aqualuxe' ),
        'pinterest' => __( 'Pinterest', 'aqualuxe' ),
        'tiktok' => __( 'TikTok', 'aqualuxe' ),
        'snapchat' => __( 'Snapchat', 'aqualuxe' ),
        'reddit' => __( 'Reddit', 'aqualuxe' ),
        'tumblr' => __( 'Tumblr', 'aqualuxe' ),
        'vimeo' => __( 'Vimeo', 'aqualuxe' ),
        'dribbble' => __( 'Dribbble', 'aqualuxe' ),
        'github' => __( 'GitHub', 'aqualuxe' ),
        'whatsapp' => __( 'WhatsApp', 'aqualuxe' ),
        'telegram' => __( 'Telegram', 'aqualuxe' ),
    );
}

/**
 * Get social network icon
 *
 * @param string $network Social network
 * @return string
 */
function aqualuxe_get_social_network_icon( $network ) {
    $icons = array(
        'facebook' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5z"/></svg>',
        'twitter' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M22.162 5.656a8.384 8.384 0 0 1-2.402.658A4.196 4.196 0 0 0 21.6 4c-.82.488-1.719.83-2.656 1.015a4.182 4.182 0 0 0-7.126 3.814 11.874 11.874 0 0 1-8.62-4.37 4.168 4.168 0 0 0-.566 2.103c0 1.45.738 2.731 1.86 3.481a4.168 4.168 0 0 1-1.894-.523v.052a4.185 4.185 0 0 0 3.355 4.101 4.21 4.21 0 0 1-1.89.072A4.185 4.185 0 0 0 7.97 16.65a8.394 8.394 0 0 1-6.191 1.732 11.83 11.83 0 0 0 6.41 1.88c7.693 0 11.9-6.373 11.9-11.9 0-.18-.005-.362-.013-.54a8.496 8.496 0 0 0 2.087-2.165z"/></svg>',
        'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 2c2.717 0 3.056.01 4.122.06 1.065.05 1.79.217 2.428.465.66.254 1.216.598 1.772 1.153a4.908 4.908 0 0 1 1.153 1.772c.247.637.415 1.363.465 2.428.047 1.066.06 1.405.06 4.122 0 2.717-.01 3.056-.06 4.122-.05 1.065-.218 1.79-.465 2.428a4.883 4.883 0 0 1-1.153 1.772 4.915 4.915 0 0 1-1.772 1.153c-.637.247-1.363.415-2.428.465-1.066.047-1.405.06-4.122.06-2.717 0-3.056-.01-4.122-.06-1.065-.05-1.79-.218-2.428-.465a4.89 4.89 0 0 1-1.772-1.153 4.904 4.904 0 0 1-1.153-1.772c-.248-.637-.415-1.363-.465-2.428C2.013 15.056 2 14.717 2 12c0-2.717.01-3.056.06-4.122.05-1.066.217-1.79.465-2.428a4.88 4.88 0 0 1 1.153-1.772A4.897 4.897 0 0 1 5.45 2.525c.638-.248 1.362-.415 2.428-.465C8.944 2.013 9.283 2 12 2zm0 1.802c-2.67 0-2.986.01-4.04.059-.976.045-1.505.207-1.858.344-.466.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.048 1.055-.058 1.37-.058 4.041 0 2.67.01 2.986.058 4.04.045.976.207 1.505.344 1.858.182.466.399.8.748 1.15.35.35.684.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058 2.67 0 2.987-.01 4.04-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041 0-2.67-.01-2.986-.058-4.04-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 0 0-.748-1.15 3.098 3.098 0 0 0-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.055-.048-1.37-.058-4.041-.058zm0 3.063a5.135 5.135 0 1 1 0 10.27 5.135 5.135 0 0 1 0-10.27zm0 8.468a3.333 3.333 0 1 0 0-6.666 3.333 3.333 0 0 0 0 6.666zm6.538-8.469a1.2 1.2 0 1 1-2.4 0 1.2 1.2 0 0 1 2.4 0z"/></svg>',
        'linkedin' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M18.335 18.339H15.67v-4.177c0-.996-.02-2.278-1.39-2.278-1.389 0-1.601 1.084-1.601 2.205v4.25h-2.666V9.75h2.56v1.17h.035c.358-.674 1.228-1.387 2.528-1.387 2.7 0 3.2 1.778 3.2 4.091v4.715zM7.003 8.575a1.546 1.546 0 0 1-1.548-1.549 1.548 1.548 0 1 1 1.547 1.549zm1.336 9.764H5.666V9.75H8.34v8.589zM19.67 3H4.329C3.593 3 3 3.58 3 4.297v15.406C3 20.42 3.594 21 4.328 21h15.338C20.4 21 21 20.42 21 19.703V4.297C21 3.58 20.4 3 19.666 3h.003z"/></svg>',
        'youtube' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M21.543 6.498C22 8.28 22 12 22 12s0 3.72-.457 5.502c-.254.985-.997 1.76-1.938 2.022C17.896 20 12 20 12 20s-5.893 0-7.605-.476c-.945-.266-1.687-1.04-1.938-2.022C2 15.72 2 12 2 12s0-3.72.457-5.502c.254-.985.997-1.76 1.938-2.022C6.107 4 12 4 12 4s5.896 0 7.605.476c.945.266 1.687 1.04 1.938 2.022zM10 15.5l6-3.5-6-3.5v7z"/></svg>',
        'pinterest' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.37 2.094A10.003 10.003 0 0 0 8.002 21.17a7.757 7.757 0 0 1 .163-2.293c.185-.839 1.296-5.463 1.296-5.463a3.739 3.739 0 0 1-.324-1.577c0-1.485.857-2.593 1.923-2.593a1.334 1.334 0 0 1 1.342 1.508c0 .9-.578 2.262-.88 3.54a1.544 1.544 0 0 0 1.575 1.923c1.898 0 3.17-2.431 3.17-5.301 0-2.2-1.457-3.848-4.143-3.848a4.746 4.746 0 0 0-4.93 4.794 2.96 2.96 0 0 0 .648 1.97.48.48 0 0 1 .162.554c-.46.184-.162.623-.208.784a.354.354 0 0 1-.51.254c-1.384-.554-2.036-2.077-2.036-3.816 0-2.847 2.384-6.255 7.154-6.255 3.796 0 6.32 2.777 6.32 5.747 0 3.909-2.177 6.848-5.394 6.848a2.861 2.861 0 0 1-2.454-1.246s-.578 2.316-.692 2.754a8.026 8.026 0 0 1-1.019 2.131c.923.28 1.882.42 2.846.416a9.988 9.988 0 0 0 9.996-10.003 10.002 10.002 0 0 0-8.635-9.903z"/></svg>',
        'tiktok' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M16.6 5.82s.51.5 0 0A4.278 4.278 0 0 1 15.54 3h-3.09v12.4a2.592 2.592 0 0 1-2.59 2.5c-1.42 0-2.6-1.16-2.6-2.6 0-1.72 1.66-3.01 3.37-2.48V9.66c-3.45-.46-6.47 2.22-6.47 5.64 0 3.33 2.76 5.7 5.69 5.7 3.14 0 5.69-2.55 5.69-5.7V9.01a7.35 7.35 0 0 0 4.3 1.38V7.3s-1.88.09-3.24-1.48z"/></svg>',
        'snapchat' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12.004 2c2.35 0 4.331.86 5.822 2.66 1.49 1.8 1.737 4.02 1.466 5.47.142.049.283.105.418.17.262.102.604.291.91.606.276.286.489.664.575 1.174.114.674-.053 1.183-.356 1.55-.303.366-.713.58-1.1.656-.082.016-.164.033-.246.049a.456.456 0 0 0-.378.454c.01.177.04.356.066.523.07.454.146.908.126 1.344-.036.756-.448 1.301-.818 1.65-.368.348-.92.616-1.694.724-.793.11-1.466-.027-2.043-.143-.276-.055-.532-.107-.760-.143-.302-.048-.596-.124-.872-.204-.59-.175-1.003-.36-1.594-.36-.588 0-1 .183-1.602.36-.275.08-.57.156-.87.205-.228.036-.486.088-.763.143-.576.116-1.248.252-2.042.142-.775-.108-1.326-.376-1.694-.724-.37-.349-.782-.894-.817-1.65-.022-.435.055-.89.126-1.344.026-.167.055-.346.065-.523a.456.456 0 0 0-.378-.454c-.083-.016-.165-.033-.247-.05-.387-.075-.797-.289-1.1-.655-.303-.367-.47-.876-.356-1.55.086-.51.299-.888.575-1.173.306-.315.648-.504.91-.606.135-.065.276-.121.418-.17-.27-1.45-.024-3.67 1.466-5.47C7.673 2.86 9.653 2 12.005 2zM12 4c-1.697 0-3.019.54-3.977 1.86-1.04 1.424-1.098 3.18-.929 4.65.045.39-.399.667-.75.535-.176-.065-.358-.143-.539-.224-.356-.16-.72-.334-.956-.583-.126-.135-.215-.271-.235-.412-.025-.186.029-.36.129-.483.11-.134.25-.217.35-.268.336-.162.525-.53.446-.9-.073-.363-.405-.6-.768-.532-.628.117-1.269.385-1.737.876-.532.56-.743 1.213-.574 1.98.135.612.476 1.105.935 1.424.459.32 1.023.49 1.59.49.222 0 .444-.02.659-.06.436-.08.77-.42.849-.858.040-.218.064-.436.089-.654.024-.22.049-.44.088-.653.085-.47.496-.82.978-.82.483 0 .893.35.978.82.04.213.065.433.088.653.025.218.05.436.09.654.078.438.412.779.848.858.215.04.437.06.659.06.567 0 1.131-.17 1.59-.49.46-.319.8-.812.935-1.424.17-.767-.041-1.42-.574-1.98-.468-.49-1.11-.76-1.737-.876-.363-.068-.695.17-.768.532-.08.37.11.738.446.9.1.05.24.134.35.268.1.123.154.297.128.483-.02.14-.108.277-.234.412-.236.249-.6.423-.957.583-.18.081-.363.159-.538.224-.352.132-.795-.145-.75-.535.17-1.47.111-3.226-.929-4.65C15.019 4.54 13.697 4 12 4z"/></svg>',
        'reddit' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm6.67-10a1.46 1.46 0 0 0-2.47-1 7.12 7.12 0 0 0-3.85-1.23L13 6.65l2.14.45a1 1 0 1 0 .13-.61L12.82 6a.31.31 0 0 0-.37.24l-.74 3.47a7.14 7.14 0 0 0-3.9 1.23 1.46 1.46 0 1 0-1.61 2.39 2.87 2.87 0 0 0 0 .44c0 2.24 2.61 4.06 5.83 4.06s5.83-1.82 5.83-4.06a2.87 2.87 0 0 0 0-.44 1.46 1.46 0 0 0 .81-1.33zm-10 1a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm5.81 2.75a3.84 3.84 0 0 1-2.47.77 3.84 3.84 0 0 1-2.47-.77.27.27 0 0 1 .38-.38A3.27 3.27 0 0 0 12 16a3.28 3.28 0 0 0 2.09-.61.28.28 0 1 1 .39.4v-.04zm-.18-1.71a1 1 0 1 1 1-1 1 1 0 0 1-1.01 1.04l.01-.04z"/></svg>',
        'tumblr' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M6.27 7.63A5.76 5.76 0 0 0 10.815 2h3.03v5.152h3.637v3.636h-3.636v5.454c0 .515.197 1.207.909 1.667.474.307 1.484.458 3.03.455V22h-4.242a4.545 4.545 0 0 1-4.546-4.545v-6.667H6.27V7.63z"/></svg>',
        'vimeo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M1.173 8.301c-.281-.413-.252-.413.328-.922 1.232-1.082 2.394-2.266 3.736-3.212 1.215-.852 2.826-1.402 3.927-.047 1.014 1.249.944 3.037.737 4.558-.34 2.478-1.235 4.808-2.88 6.654-.982 1.099-2.035 1.178-3.158.308-1.386-1.078-1.827-2.582-2.003-4.106-.146-1.265-.061-2.548-.685-3.723zm20.23 8.541c-2.341 1.65-5.066 2.38-7.9 2.322-1.166-.024-2.407-.822-3.583-.809-1.175.013-2.347.679-3.52.702-1.704.033-3.143-.38-4.427-1.428-2.502-2.042-3.993-4.538-3.883-7.866.07-2.062 1.247-3.834 3.182-4.809 1.681-.845 3.277-.323 4.714.675.33.23.671.458.993.713.252.198.487.18.622-.123.191-.43.373-.866.565-1.296.263-.586.854-.712 1.246-.271 1.903 2.142 3.708 4.368 5.272 6.754.546.832.656 1.544.078 2.383-.872 1.264-1.808 2.489-2.359 3.853z"/></svg>',
        'dribbble' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M19.989 11.572a7.96 7.96 0 0 0-1.573-4.351 9.749 9.749 0 0 1-.92.87 13.157 13.157 0 0 1-3.313 2.01c.167.35.32.689.455 1.009v.003a9.186 9.186 0 0 1 .11.27c1.514-.17 3.11-.108 4.657.101.206.028.4.058.584.088zm-9.385-7.45a46.164 46.164 0 0 1 2.692 4.27c1.223-.482 2.234-1.09 3.048-1.767a7.88 7.88 0 0 0 .796-.755A7.968 7.968 0 0 0 12 4a8.05 8.05 0 0 0-1.396.121zM4.253 9.997a29.21 29.21 0 0 0 2.04-.123 31.53 31.53 0 0 0 4.862-.822 54.365 54.365 0 0 0-2.7-4.227 8.018 8.018 0 0 0-4.202 5.172zm1.53 7.038c.388-.567.898-1.205 1.575-1.899 1.454-1.49 3.17-2.65 5.156-3.29l.062-.018c-.165-.364-.32-.689-.476-.995-1.836.535-3.77.869-5.697 1.042-.94.085-1.783.122-2.403.128a7.967 7.967 0 0 0 1.784 5.032zm9.222 2.38a35.947 35.947 0 0 0-1.632-5.709c-2.002.727-3.597 1.79-4.83 3.058a9.77 9.77 0 0 0-1.317 1.655A7.964 7.964 0 0 0 12 20a7.977 7.977 0 0 0 3.005-.583zm1.873-1.075a7.998 7.998 0 0 0 2.987-4.87c-.34-.085-.771-.17-1.245-.236a12.023 12.023 0 0 0-3.18-.033 39.368 39.368 0 0 1 1.438 5.14zM12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>',
        'github' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 2C6.475 2 2 6.475 2 12a9.994 9.994 0 0 0 6.838 9.488c.5.087.687-.213.687-.476 0-.237-.013-1.024-.013-1.862-2.512.463-3.162-.612-3.362-1.175-.113-.288-.6-1.175-1.025-1.413-.35-.187-.85-.65-.013-.662.788-.013 1.35.725 1.538 1.025.9 1.512 2.338 1.087 2.912.825.088-.65.35-1.087.638-1.337-2.225-.25-4.55-1.113-4.55-4.938 0-1.088.387-1.987 1.025-2.688-.1-.25-.45-1.275.1-2.65 0 0 .837-.262 2.75 1.026a9.28 9.28 0 0 1 2.5-.338c.85 0 1.7.112 2.5.337 1.912-1.3 2.75-1.024 2.75-1.024.55 1.375.2 2.4.1 2.65.637.7 1.025 1.587 1.025 2.687 0 3.838-2.337 4.688-4.562 4.938.362.312.675.912.675 1.85 0 1.337-.013 2.412-.013 2.75 0 .262.188.574.688.474A10.016 10.016 0 0 0 22 12c0-5.525-4.475-10-10-10z"/></svg>',
        'whatsapp' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M2.004 22l1.352-4.968A9.954 9.954 0 0 1 2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10a9.954 9.954 0 0 1-5.03-1.355L2.004 22zM8.391 7.308a.961.961 0 0 0-.371.1 1.293 1.293 0 0 0-.294.228c-.12.113-.188.211-.261.306A2.729 2.729 0 0 0 6.9 9.62c.002.49.13.967.33 1.413.409.902 1.082 1.857 1.971 2.742.214.213.423.427.648.626a9.448 9.448 0 0 0 3.84 2.046l.569.087c.185.01.37-.004.556-.013a1.99 1.99 0 0 0 .833-.231c.166-.088.244-.132.383-.22 0 0 .043-.028.125-.09.135-.1.218-.171.33-.288.083-.086.155-.187.21-.302.078-.163.156-.474.188-.733.024-.198.017-.306.014-.373-.004-.107-.093-.218-.19-.265l-.582-.261s-.87-.379-1.401-.621a.498.498 0 0 0-.177-.041.482.482 0 0 0-.378.127v-.002c-.005 0-.072.057-.795.933a.35.35 0 0 1-.368.13 1.416 1.416 0 0 1-.191-.066c-.124-.052-.167-.072-.252-.109l-.005-.002a6.01 6.01 0 0 1-1.57-1c-.126-.11-.243-.23-.363-.346a6.296 6.296 0 0 1-1.02-1.268l-.059-.095a.923.923 0 0 1-.102-.205c-.038-.147.061-.265.061-.265s.243-.266.356-.41a4.38 4.38 0 0 0 .263-.373c.118-.19.155-.385.093-.536-.28-.684-.57-1.365-.868-2.041-.059-.134-.234-.23-.393-.249-.054-.006-.108-.012-.162-.016a3.385 3.385 0 0 0-.403.004z"/></svg>',
        'telegram' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-3.11-8.83l.013-.007.87 2.87c.112.311.266.367.453.341.188-.025.287-.126.41-.244l1.188-1.148 2.55 1.888c.466.257.801.124.917-.432l1.657-7.822c.183-.728-.137-1.02-.702-.788l-9.733 3.76c-.664.266-.66.638-.12.803l2.497.78z"/></svg>',
    );
    
    return isset( $icons[ $network ] ) ? $icons[ $network ] : '';
}

/**
 * Sanitize checkbox
 *
 * @param bool $checked Whether the checkbox is checked
 * @return bool
 */
function aqualuxe_sanitize_checkbox( $checked ) {
    return ( isset( $checked ) && true === $checked ) ? true : false;
}

/**
 * Sanitize select
 *
 * @param string $input Select value
 * @param WP_Customize_Setting $setting Setting instance
 * @return string
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    // Get list of choices from the control associated with the setting
    $choices = $setting->manager->get_control( $setting->id )->choices;
    
    // Return input if valid or return default option
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize radio
 *
 * @param string $input Radio value
 * @param WP_Customize_Setting $setting Setting instance
 * @return string
 */
function aqualuxe_sanitize_radio( $input, $setting ) {
    // Get list of choices from the control associated with the setting
    $choices = $setting->manager->get_control( $setting->id )->choices;
    
    // Return input if valid or return default option
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize number
 *
 * @param string $input Number value
 * @param WP_Customize_Setting $setting Setting instance
 * @return int
 */
function aqualuxe_sanitize_number( $input, $setting ) {
    // Ensure $input is an absolute integer
    $input = absint( $input );
    
    // Get the input attributes associated with the setting
    $atts = $setting->manager->get_control( $setting->id )->input_attrs;
    
    // Get min
    $min = isset( $atts['min'] ) ? $atts['min'] : $input;
    
    // Get max
    $max = isset( $atts['max'] ) ? $atts['max'] : $input;
    
    // Get step
    $step = isset( $atts['step'] ) ? $atts['step'] : 1;
    
    // If the input is valid, return it; otherwise, return the default
    return ( $min <= $input && $input <= $max && is_int( $input / $step ) ? $input : $setting->default );
}

/**
 * Sanitize range
 *
 * @param string $input Range value
 * @param WP_Customize_Setting $setting Setting instance
 * @return int
 */
function aqualuxe_sanitize_range( $input, $setting ) {
    // Ensure $input is an absolute integer
    $input = absint( $input );
    
    // Get the input attributes associated with the setting
    $atts = $setting->manager->get_control( $setting->id )->input_attrs;
    
    // Get min
    $min = isset( $atts['min'] ) ? $atts['min'] : $input;
    
    // Get max
    $max = isset( $atts['max'] ) ? $atts['max'] : $input;
    
    // Get step
    $step = isset( $atts['step'] ) ? $atts['step'] : 1;
    
    // If the input is valid, return it; otherwise, return the default
    return ( $min <= $input && $input <= $max && is_int( $input / $step ) ? $input : $setting->default );
}

/**
 * Sanitize dimensions
 *
 * @param string $input Dimensions value
 * @return array
 */
function aqualuxe_sanitize_dimensions( $input ) {
    if ( is_array( $input ) ) {
        foreach ( $input as $key => $value ) {
            $input[ $key ] = sanitize_text_field( $value );
        }
        return $input;
    }
    return array();
}

/**
 * Sanitize typography
 *
 * @param string $input Typography value
 * @return array
 */
function aqualuxe_sanitize_typography( $input ) {
    if ( is_array( $input ) ) {
        foreach ( $input as $key => $value ) {
            $input[ $key ] = sanitize_text_field( $value );
        }
        return $input;
    }
    return array();
}

/**
 * Sanitize sortable
 *
 * @param string $input Sortable value
 * @return array
 */
function aqualuxe_sanitize_sortable( $input ) {
    if ( is_array( $input ) ) {
        foreach ( $input as $key => $value ) {
            $input[ $key ] = sanitize_text_field( $value );
        }
        return $input;
    }
    return array();
}

/**
 * Sanitize image
 *
 * @param string $input Image value
 * @return string
 */
function aqualuxe_sanitize_image( $input ) {
    return esc_url_raw( $input );
}

/**
 * Sanitize URL
 *
 * @param string $input URL value
 * @return string
 */
function aqualuxe_sanitize_url( $input ) {
    return esc_url_raw( $input );
}

/**
 * Sanitize hex color
 *
 * @param string $color Color value
 * @return string
 */
function aqualuxe_sanitize_hex_color( $color ) {
    if ( '' === $color ) {
        return '';
    }
    
    // 3 or 6 hex digits, or the empty string.
    if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
        return $color;
    }
    
    return '';
}

/**
 * Sanitize alpha color
 *
 * @param string $color Color value
 * @return string
 */
function aqualuxe_sanitize_alpha_color( $color ) {
    if ( '' === $color ) {
        return '';
    }
    
    // Check if the color is a hex color
    if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
        return $color;
    }
    
    // Check if the color is an rgba color
    if ( preg_match( '/^rgba\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d*(?:\.\d+)?)\s*\)$/', $color, $matches ) ) {
        // Check if the values are valid
        if ( $matches[1] >= 0 && $matches[1] <= 255 &&
             $matches[2] >= 0 && $matches[2] <= 255 &&
             $matches[3] >= 0 && $matches[3] <= 255 &&
             $matches[4] >= 0 && $matches[4] <= 1 ) {
            return $color;
        }
    }
    
    return '';
}

/**
 * Sanitize html
 *
 * @param string $input HTML value
 * @return string
 */
function aqualuxe_sanitize_html( $input ) {
    return wp_kses_post( $input );
}

/**
 * Sanitize text
 *
 * @param string $input Text value
 * @return string
 */
function aqualuxe_sanitize_text( $input ) {
    return sanitize_text_field( $input );
}

/**
 * Sanitize textarea
 *
 * @param string $input Textarea value
 * @return string
 */
function aqualuxe_sanitize_textarea( $input ) {
    return sanitize_textarea_field( $input );
}

/**
 * Sanitize email
 *
 * @param string $input Email value
 * @return string
 */
function aqualuxe_sanitize_email( $input ) {
    return sanitize_email( $input );
}

/**
 * Sanitize file
 *
 * @param string $input File value
 * @return string
 */
function aqualuxe_sanitize_file( $input ) {
    return esc_url_raw( $input );
}

/**
 * Sanitize css
 *
 * @param string $input CSS value
 * @return string
 */
function aqualuxe_sanitize_css( $input ) {
    return wp_strip_all_tags( $input );
}

/**
 * Sanitize js
 *
 * @param string $input JS value
 * @return string
 */
function aqualuxe_sanitize_js( $input ) {
    return wp_strip_all_tags( $input );
}
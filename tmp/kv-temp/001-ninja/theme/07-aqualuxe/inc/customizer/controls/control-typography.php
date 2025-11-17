<?php
/**
 * Customizer Control: Typography
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Typography control
 */
class AquaLuxe_Customize_Control_Typography extends WP_Customize_Control {
    /**
     * Control type
     *
     * @var string
     */
    public $type = 'aqualuxe-typography';

    /**
     * Font families
     *
     * @var array
     */
    public $fonts = array();

    /**
     * Font weights
     *
     * @var array
     */
    public $weights = array();

    /**
     * Font styles
     *
     * @var array
     */
    public $styles = array();

    /**
     * Font subsets
     *
     * @var array
     */
    public $subsets = array();

    /**
     * Constructor
     *
     * @param WP_Customize_Manager $manager Customizer bootstrap instance.
     * @param string               $id      Control ID.
     * @param array                $args    Control arguments.
     */
    public function __construct($manager, $id, $args = array()) {
        parent::__construct($manager, $id, $args);

        // Set default fonts
        $this->fonts = array(
            'standard' => array(
                'Arial' => 'Arial, Helvetica, sans-serif',
                'Georgia' => 'Georgia, serif',
                'Tahoma' => 'Tahoma, Geneva, sans-serif',
                'Times New Roman' => '"Times New Roman", Times, serif',
                'Verdana' => 'Verdana, Geneva, sans-serif',
            ),
            'google' => array(
                'Montserrat' => 'Montserrat',
                'Open Sans' => 'Open Sans',
                'Roboto' => 'Roboto',
                'Lato' => 'Lato',
                'Oswald' => 'Oswald',
                'Raleway' => 'Raleway',
                'Playfair Display' => 'Playfair Display',
                'Poppins' => 'Poppins',
                'Nunito' => 'Nunito',
                'Source Sans Pro' => 'Source Sans Pro',
            ),
        );

        // Set default weights
        $this->weights = array(
            '100' => esc_html__('Thin 100', 'aqualuxe'),
            '200' => esc_html__('Extra Light 200', 'aqualuxe'),
            '300' => esc_html__('Light 300', 'aqualuxe'),
            '400' => esc_html__('Regular 400', 'aqualuxe'),
            '500' => esc_html__('Medium 500', 'aqualuxe'),
            '600' => esc_html__('Semi Bold 600', 'aqualuxe'),
            '700' => esc_html__('Bold 700', 'aqualuxe'),
            '800' => esc_html__('Extra Bold 800', 'aqualuxe'),
            '900' => esc_html__('Black 900', 'aqualuxe'),
        );

        // Set default styles
        $this->styles = array(
            'normal' => esc_html__('Normal', 'aqualuxe'),
            'italic' => esc_html__('Italic', 'aqualuxe'),
        );

        // Set default subsets
        $this->subsets = array(
            'latin' => esc_html__('Latin', 'aqualuxe'),
            'latin-ext' => esc_html__('Latin Extended', 'aqualuxe'),
            'cyrillic' => esc_html__('Cyrillic', 'aqualuxe'),
            'cyrillic-ext' => esc_html__('Cyrillic Extended', 'aqualuxe'),
            'greek' => esc_html__('Greek', 'aqualuxe'),
            'greek-ext' => esc_html__('Greek Extended', 'aqualuxe'),
            'vietnamese' => esc_html__('Vietnamese', 'aqualuxe'),
        );
    }

    /**
     * Render control content
     */
    public function render_content() {
        $value = $this->value();
        $value = !is_array($value) ? json_decode($value, true) : $value;
        $value = wp_parse_args($value, array(
            'font-family' => '',
            'font-weight' => '400',
            'font-style' => 'normal',
            'font-size' => '',
            'line-height' => '',
            'letter-spacing' => '',
            'text-transform' => 'none',
            'subsets' => array('latin'),
        ));
        ?>
        <div class="aqualuxe-customizer-typography">
            <?php if (!empty($this->label)) : ?>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php endif; ?>
            <?php if (!empty($this->description)) : ?>
                <span class="customize-control-description"><?php echo wp_kses_post($this->description); ?></span>
            <?php endif; ?>
            <div class="aqualuxe-typography-control">
                <!-- Font Family -->
                <div class="aqualuxe-typography-font-family">
                    <label for="<?php echo esc_attr($this->id . '-font-family'); ?>"><?php esc_html_e('Font Family', 'aqualuxe'); ?></label>
                    <select id="<?php echo esc_attr($this->id . '-font-family'); ?>" class="aqualuxe-typography-font-family-select">
                        <option value=""><?php esc_html_e('Default', 'aqualuxe'); ?></option>
                        <?php if (!empty($this->fonts['standard'])) : ?>
                            <optgroup label="<?php esc_attr_e('Standard Fonts', 'aqualuxe'); ?>">
                                <?php foreach ($this->fonts['standard'] as $font_name => $font_stack) : ?>
                                    <option value="<?php echo esc_attr($font_stack); ?>" <?php selected($value['font-family'], $font_stack); ?>><?php echo esc_html($font_name); ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endif; ?>
                        <?php if (!empty($this->fonts['google'])) : ?>
                            <optgroup label="<?php esc_attr_e('Google Fonts', 'aqualuxe'); ?>">
                                <?php foreach ($this->fonts['google'] as $font_name => $font_name_value) : ?>
                                    <option value="<?php echo esc_attr($font_name_value); ?>" <?php selected($value['font-family'], $font_name_value); ?>><?php echo esc_html($font_name); ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Font Weight -->
                <div class="aqualuxe-typography-font-weight">
                    <label for="<?php echo esc_attr($this->id . '-font-weight'); ?>"><?php esc_html_e('Font Weight', 'aqualuxe'); ?></label>
                    <select id="<?php echo esc_attr($this->id . '-font-weight'); ?>" class="aqualuxe-typography-font-weight-select">
                        <?php foreach ($this->weights as $weight_value => $weight_label) : ?>
                            <option value="<?php echo esc_attr($weight_value); ?>" <?php selected($value['font-weight'], $weight_value); ?>><?php echo esc_html($weight_label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Font Style -->
                <div class="aqualuxe-typography-font-style">
                    <label for="<?php echo esc_attr($this->id . '-font-style'); ?>"><?php esc_html_e('Font Style', 'aqualuxe'); ?></label>
                    <select id="<?php echo esc_attr($this->id . '-font-style'); ?>" class="aqualuxe-typography-font-style-select">
                        <?php foreach ($this->styles as $style_value => $style_label) : ?>
                            <option value="<?php echo esc_attr($style_value); ?>" <?php selected($value['font-style'], $style_value); ?>><?php echo esc_html($style_label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Font Size -->
                <div class="aqualuxe-typography-font-size">
                    <label for="<?php echo esc_attr($this->id . '-font-size'); ?>"><?php esc_html_e('Font Size', 'aqualuxe'); ?></label>
                    <div class="aqualuxe-typography-input-wrapper">
                        <input type="number" id="<?php echo esc_attr($this->id . '-font-size'); ?>" class="aqualuxe-typography-font-size-input" value="<?php echo esc_attr($value['font-size']); ?>">
                        <select class="aqualuxe-typography-font-size-unit">
                            <option value="px" <?php selected(strpos($value['font-size'], 'px'), true); ?>>px</option>
                            <option value="em" <?php selected(strpos($value['font-size'], 'em'), true); ?>>em</option>
                            <option value="rem" <?php selected(strpos($value['font-size'], 'rem'), true); ?>>rem</option>
                            <option value="%" <?php selected(strpos($value['font-size'], '%'), true); ?>>%</option>
                        </select>
                    </div>
                </div>

                <!-- Line Height -->
                <div class="aqualuxe-typography-line-height">
                    <label for="<?php echo esc_attr($this->id . '-line-height'); ?>"><?php esc_html_e('Line Height', 'aqualuxe'); ?></label>
                    <input type="number" id="<?php echo esc_attr($this->id . '-line-height'); ?>" class="aqualuxe-typography-line-height-input" value="<?php echo esc_attr($value['line-height']); ?>" step="0.1">
                </div>

                <!-- Letter Spacing -->
                <div class="aqualuxe-typography-letter-spacing">
                    <label for="<?php echo esc_attr($this->id . '-letter-spacing'); ?>"><?php esc_html_e('Letter Spacing', 'aqualuxe'); ?></label>
                    <div class="aqualuxe-typography-input-wrapper">
                        <input type="number" id="<?php echo esc_attr($this->id . '-letter-spacing'); ?>" class="aqualuxe-typography-letter-spacing-input" value="<?php echo esc_attr($value['letter-spacing']); ?>" step="0.1">
                        <select class="aqualuxe-typography-letter-spacing-unit">
                            <option value="px" <?php selected(strpos($value['letter-spacing'], 'px'), true); ?>>px</option>
                            <option value="em" <?php selected(strpos($value['letter-spacing'], 'em'), true); ?>>em</option>
                        </select>
                    </div>
                </div>

                <!-- Text Transform -->
                <div class="aqualuxe-typography-text-transform">
                    <label for="<?php echo esc_attr($this->id . '-text-transform'); ?>"><?php esc_html_e('Text Transform', 'aqualuxe'); ?></label>
                    <select id="<?php echo esc_attr($this->id . '-text-transform'); ?>" class="aqualuxe-typography-text-transform-select">
                        <option value="none" <?php selected($value['text-transform'], 'none'); ?>><?php esc_html_e('None', 'aqualuxe'); ?></option>
                        <option value="capitalize" <?php selected($value['text-transform'], 'capitalize'); ?>><?php esc_html_e('Capitalize', 'aqualuxe'); ?></option>
                        <option value="uppercase" <?php selected($value['text-transform'], 'uppercase'); ?>><?php esc_html_e('Uppercase', 'aqualuxe'); ?></option>
                        <option value="lowercase" <?php selected($value['text-transform'], 'lowercase'); ?>><?php esc_html_e('Lowercase', 'aqualuxe'); ?></option>
                    </select>
                </div>

                <!-- Font Subsets (for Google Fonts) -->
                <div class="aqualuxe-typography-subsets">
                    <label><?php esc_html_e('Font Subsets', 'aqualuxe'); ?></label>
                    <div class="aqualuxe-typography-subsets-list">
                        <?php foreach ($this->subsets as $subset_value => $subset_label) : ?>
                            <label>
                                <input type="checkbox" class="aqualuxe-typography-subset-checkbox" value="<?php echo esc_attr($subset_value); ?>" <?php checked(in_array($subset_value, $value['subsets'], true), true); ?>>
                                <?php echo esc_html($subset_label); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <input type="hidden" id="<?php echo esc_attr($this->id); ?>" name="<?php echo esc_attr($this->id); ?>" value="<?php echo esc_attr(json_encode($value)); ?>" <?php $this->link(); ?>>
        </div>
        <?php
    }

    /**
     * Enqueue control scripts
     */
    public function enqueue() {
        wp_add_inline_style('customize-controls', '
            .aqualuxe-typography-control {
                margin-top: 10px;
            }
            
            .aqualuxe-typography-control > div {
                margin-bottom: 15px;
            }
            
            .aqualuxe-typography-control label {
                display: block;
                font-size: 12px;
                font-weight: 500;
                margin-bottom: 5px;
            }
            
            .aqualuxe-typography-control select,
            .aqualuxe-typography-control input {
                width: 100%;
            }
            
            .aqualuxe-typography-input-wrapper {
                display: flex;
            }
            
            .aqualuxe-typography-input-wrapper input {
                flex-grow: 1;
                margin-right: 5px;
            }
            
            .aqualuxe-typography-input-wrapper select {
                width: 60px;
            }
            
            .aqualuxe-typography-subsets-list {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }
            
            .aqualuxe-typography-subsets-list label {
                font-size: 11px;
                font-weight: normal;
            }
        ');

        wp_add_inline_script('customize-controls', '
            jQuery(document).ready(function($) {
                $(".aqualuxe-typography-control").each(function() {
                    var control = $(this);
                    var hiddenInput = control.closest(".aqualuxe-customizer-typography").find("input[type=\'hidden\']");
                    var fontFamilySelect = control.find(".aqualuxe-typography-font-family-select");
                    var fontWeightSelect = control.find(".aqualuxe-typography-font-weight-select");
                    var fontStyleSelect = control.find(".aqualuxe-typography-font-style-select");
                    var fontSizeInput = control.find(".aqualuxe-typography-font-size-input");
                    var fontSizeUnit = control.find(".aqualuxe-typography-font-size-unit");
                    var lineHeightInput = control.find(".aqualuxe-typography-line-height-input");
                    var letterSpacingInput = control.find(".aqualuxe-typography-letter-spacing-input");
                    var letterSpacingUnit = control.find(".aqualuxe-typography-letter-spacing-unit");
                    var textTransformSelect = control.find(".aqualuxe-typography-text-transform-select");
                    var subsetCheckboxes = control.find(".aqualuxe-typography-subset-checkbox");
                    
                    // Update hidden input
                    function updateValue() {
                        var value = JSON.parse(hiddenInput.val());
                        
                        value["font-family"] = fontFamilySelect.val();
                        value["font-weight"] = fontWeightSelect.val();
                        value["font-style"] = fontStyleSelect.val();
                        value["font-size"] = fontSizeInput.val() + fontSizeUnit.val();
                        value["line-height"] = lineHeightInput.val();
                        value["letter-spacing"] = letterSpacingInput.val() + letterSpacingUnit.val();
                        value["text-transform"] = textTransformSelect.val();
                        
                        // Update subsets
                        value.subsets = [];
                        subsetCheckboxes.each(function() {
                            if ($(this).is(":checked")) {
                                value.subsets.push($(this).val());
                            }
                        });
                        
                        hiddenInput.val(JSON.stringify(value)).trigger("change");
                    }
                    
                    // Handle input changes
                    fontFamilySelect.on("change", updateValue);
                    fontWeightSelect.on("change", updateValue);
                    fontStyleSelect.on("change", updateValue);
                    fontSizeInput.on("input", updateValue);
                    fontSizeUnit.on("change", updateValue);
                    lineHeightInput.on("input", updateValue);
                    letterSpacingInput.on("input", updateValue);
                    letterSpacingUnit.on("change", updateValue);
                    textTransformSelect.on("change", updateValue);
                    subsetCheckboxes.on("change", updateValue);
                    
                    // Parse initial values
                    var initialValue = JSON.parse(hiddenInput.val());
                    
                    // Parse font size
                    if (initialValue["font-size"]) {
                        var fontSizeMatch = initialValue["font-size"].match(/^(\d+(?:\.\d+)?)(px|em|rem|%)$/);
                        if (fontSizeMatch) {
                            fontSizeInput.val(fontSizeMatch[1]);
                            fontSizeUnit.val(fontSizeMatch[2]);
                        }
                    }
                    
                    // Parse letter spacing
                    if (initialValue["letter-spacing"]) {
                        var letterSpacingMatch = initialValue["letter-spacing"].match(/^(\d+(?:\.\d+)?)(px|em)$/);
                        if (letterSpacingMatch) {
                            letterSpacingInput.val(letterSpacingMatch[1]);
                            letterSpacingUnit.val(letterSpacingMatch[2]);
                        }
                    }
                });
            });
        ');
    }
}
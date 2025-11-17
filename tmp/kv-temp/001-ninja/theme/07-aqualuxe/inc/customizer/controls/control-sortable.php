<?php
/**
 * Customizer Control: Sortable
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Sortable control
 */
class AquaLuxe_Customize_Control_Sortable extends WP_Customize_Control {
    /**
     * Control type
     *
     * @var string
     */
    public $type = 'aqualuxe-sortable';

    /**
     * Render control content
     */
    public function render_content() {
        if (empty($this->choices)) {
            return;
        }

        $values = $this->value();
        $values = !is_array($values) ? explode(',', $values) : $values;
        $choices = $this->choices;
        $sorted_choices = array();

        // Sort the choices based on the saved order
        foreach ($values as $value) {
            if (array_key_exists($value, $choices)) {
                $sorted_choices[$value] = $choices[$value];
                unset($choices[$value]);
            }
        }

        // Add any remaining choices to the end
        $sorted_choices = array_merge($sorted_choices, $choices);
        ?>
        <div class="aqualuxe-customizer-sortable">
            <?php if (!empty($this->label)) : ?>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php endif; ?>
            <?php if (!empty($this->description)) : ?>
                <span class="customize-control-description"><?php echo wp_kses_post($this->description); ?></span>
            <?php endif; ?>
            <ul class="aqualuxe-sortable-list" data-id="<?php echo esc_attr($this->id); ?>">
                <?php foreach ($sorted_choices as $value => $label) : ?>
                    <li class="aqualuxe-sortable-item" data-value="<?php echo esc_attr($value); ?>">
                        <span class="aqualuxe-sortable-handle dashicons dashicons-menu"></span>
                        <span class="aqualuxe-sortable-label"><?php echo esc_html($label); ?></span>
                        <span class="aqualuxe-sortable-toggle dashicons dashicons-visibility"></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <input type="hidden" id="<?php echo esc_attr($this->id); ?>" name="<?php echo esc_attr($this->id); ?>" value="<?php echo esc_attr(implode(',', $values)); ?>" <?php $this->link(); ?>>
        </div>
        <?php
    }

    /**
     * Enqueue control scripts
     */
    public function enqueue() {
        wp_enqueue_script('jquery-ui-sortable');

        wp_add_inline_style('customize-controls', '
            .aqualuxe-sortable-list {
                margin: 0;
                padding: 0;
                list-style: none;
            }
            
            .aqualuxe-sortable-item {
                display: flex;
                align-items: center;
                padding: 10px;
                margin-bottom: 5px;
                background-color: #f9f9f9;
                border: 1px solid #ddd;
                border-radius: 4px;
                cursor: move;
            }
            
            .aqualuxe-sortable-handle {
                margin-right: 10px;
                color: #999;
            }
            
            .aqualuxe-sortable-label {
                flex-grow: 1;
            }
            
            .aqualuxe-sortable-toggle {
                cursor: pointer;
                color: #0073aa;
            }
            
            .aqualuxe-sortable-item.invisible {
                opacity: 0.5;
            }
            
            .aqualuxe-sortable-item.invisible .aqualuxe-sortable-toggle:before {
                content: "\f530";
            }
        ');

        wp_add_inline_script('customize-controls', '
            jQuery(document).ready(function($) {
                $(".aqualuxe-sortable-list").sortable({
                    handle: ".aqualuxe-sortable-handle",
                    update: function(event, ui) {
                        var id = $(this).data("id");
                        var values = [];
                        
                        $(this).find(".aqualuxe-sortable-item").each(function() {
                            values.push($(this).data("value"));
                        });
                        
                        $("#" + id).val(values.join(",")).trigger("change");
                    }
                });
                
                $(".aqualuxe-sortable-toggle").on("click", function() {
                    var item = $(this).closest(".aqualuxe-sortable-item");
                    item.toggleClass("invisible");
                    
                    var list = item.closest(".aqualuxe-sortable-list");
                    var id = list.data("id");
                    var values = [];
                    
                    list.find(".aqualuxe-sortable-item").each(function() {
                        if (!$(this).hasClass("invisible")) {
                            values.push($(this).data("value"));
                        }
                    });
                    
                    $("#" + id).val(values.join(",")).trigger("change");
                });
            });
        ');
    }
}
(function($, api) {
    'use strict';

    api.controlConstructor['repeater'] = api.Control.extend({
        ready: function() {
            let control = this;

            const updateValue = function() {
                let values = [];
                control.container.find('.repeater-field input[type="url"]').each(function() {
                    let value = $(this).val();
                    if (value) {
                        values.push(value);
                    }
                });
                control.setting.set(values);
            };

            control.container.on('click', '.repeater-add', function(e) {
                e.preventDefault();
                let field = `
                    <li class="repeater-field">
                        <div class="repeater-field-header">
                            <span class="repeater-field-title">${control.params.box_label}</span>
                            <button type="button" class="repeater-field-remove button-link-delete">Remove</button>
                        </div>
                        <input type="url" class="widefat" value="" placeholder="${control.params.field_label}" />
                    </li>`;
                control.container.find('.repeater-fields').append(field);
            });

            control.container.on('click', '.repeater-field-remove', function(e) {
                e.preventDefault();
                $(this).closest('.repeater-field').remove();
                updateValue();
            });

            control.container.on('change keyup', '.repeater-field input[type="url"]', function() {
                updateValue();
            });

            // Make fields sortable
            control.container.find('.repeater-fields').sortable({
                update: function() {
                    updateValue();
                }
            });
        }
    });

})(jQuery, wp.customize);

<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('acf_field_units') ) :

class acf_field_units extends acf_field {
    
    var $name = 'units';
    var $label = 'Units';
    var $category = 'basic';
    
    function __construct() {
        $this->name = 'units';
        $this->label = __('Units', 'acf');
        $this->category = 'basic';
        $this->defaults = array(
            'units' => array('px', '%', 'em', 'rem', 'vw', 'vh', 'vmin', 'vmax', 'pt', 'cm', 'mm', 'in', 'ex', 'ch', 'fr', 'auto'),
            'default_value' => array('value' => '0', 'unit' => 'px'),
        );
        $this->units = $this->defaults['units'];
        parent::__construct();
        add_action('admin_head', array($this, 'acf_field_units_css'));
    }

    function acf_field_units_css() {
        echo '<style>
            .acf-field-units input[type="text"]:focus + select {
                border-color: #2271b1;
                box-shadow: 0 0 0 1px #2271b1;
                outline: 2px solid transparent;
            }
        </style>';
    }
    
    function render_field( $field ) {
        $units = $this->units;
        $value = isset($field['value']['value']) 
            ? $field['value']['value'] 
            : (is_array($field['default_value']) ? $field['default_value']['value'] : '0');

        $unit = isset($field['value']['unit']) 
            ? $field['value']['unit'] 
            : (is_array($field['default_value']) ? $field['default_value']['unit'] : 'px');

        ?>
        <div style="display: inline-flex;">
            <input type="text" name="<?php echo esc_attr($field['name']) ?>[value]" value="<?php echo esc_attr($value) ?>" style="flex: 1 1 auto; border-right:none;border-top-right-radius: 0;border-bottom-right-radius: 0;" class="acf-units-input" />
            <select name="<?php echo esc_attr($field['name']) ?>[unit]" class="acf-units-select" style="flex: auto; border-left: none; width:auto; padding-left:15px; padding-right:25px; border-top-left-radius: 0;border-bottom-left-radius: 0;text-align:right; min-width:0;">
                <?php foreach( $units as $unit_option ): ?>
                    <option value="<?php echo esc_attr($unit_option) ?>" <?php selected($unit, $unit_option) ?>><?php echo esc_html($unit_option) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <script>
        (function($) {
            $(document).ready(function() {
                $('.acf-units-select').change(function() {
                    var $input = $(this).closest('div').find('.acf-units-input');
                    if ($(this).val() === 'auto') {
                        $input.val('auto').prop('readonly', true);
                    } else {
                        $input.prop('readonly', false);
                    }
                }).trigger('change');
            });
        })(jQuery);
        </script>
        <?php
    }
    
    function render_field_settings( $field ) {
        acf_render_field_setting( $field, array(
            'label'         => __('Default Value (Number)','acf'),
            'instructions'  => __('Enter the default numeric value for this field','acf'),
            'type'          => 'text',
            'name'          => 'default_value[value]',
            'value'         => isset($field['default_value']['value']) ? $field['default_value']['value'] : '0'
        ));

        acf_render_field_setting( $field, array(
            'label'         => __('Default Unit','acf'),
            'instructions'  => __('Select the default unit','acf'),
            'type'          => 'select',
            'name'          => 'default_value[unit]',
            'choices'       => array_combine($this->units, $this->units),
            'value'         => isset($field['default_value']['unit']) ? $field['default_value']['unit'] : 'px'
        ));
    }
    
    function update_value( $value, $post_id, $field ) {
        if (empty($value)) {
            $value = $field['default_value'];
        }
        return $value;
    }
}

new acf_field_units();

endif;
?>

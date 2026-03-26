<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('acf_field_units') ) :

class acf_field_units extends acf_field {
    
    // Alan tipi tanımlayıcı
    var $name = 'units';
    var $label = 'Units';
    var $category = 'basic';
    
    // Yapıcı
    function __construct() {
        $this->name = 'units';
        $this->label = __('Units', 'acf');
        $this->category = 'basic';
        $this->defaults = array(
            'units' => array('px', '%', 'em', 'rem', 'vw', 'vh', 'vmin', 'vmax', 'pt', 'cm', 'mm', 'in', 'ex', 'ch', 'fr'),
            'default_value' => array('value' => '0', 'unit' => 'px'),
        );
        $this->units = array('px', '%', 'em', 'rem', 'vw', 'vh', 'vmin', 'vmax', 'pt', 'cm', 'mm', 'in', 'ex', 'ch', 'fr');
        parent::__construct();
        add_action('admin_head', array($this, 'acf_field_units_css'));
        //add_filter('acf/format_value/type=units', array($this, 'format_value'), 10, 3);
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
    
    // Alanı render etme
    function render_field( $field ) {
        //print_r($field);
        // Textarea'dan gelen birimleri işleme
        $units = $this->units;//$field['units'];
        if(is_array($units)){
            $units = implode("\n", $units);
        }
        $units = array_map('trim', explode("\n", $units));
        
        $value = isset($field['value']['value']) ? $field['value']['value'] : '';
        $unit = isset($field['value']['unit']) ? $field['value']['unit'] : '';

        // Default value ayarlaması
        if (empty($value)) {
            if(!empty($field["default_value"])){
                $value = $field["default_value"];
            }else{
                $value = $this->defaults["default_value"]["value"];
            }
        }
        if (empty($unit)) {
            $unit = $this->defaults["default_value"]["unit"];
        }
        if (is_array($value)) {
            $value = $value["value"];
        }
        ?>
        <div style="display: inline-flex;">
            <input type="text" name="<?php echo esc_attr($field['name']) ?>[value]" value="<?php echo esc_attr($value) ?>" style="flex: 1 1 auto; border-right:none;border-top-right-radius: 0;border-bottom-right-radius: 0;" />
            <select name="<?php echo esc_attr($field['name']) ?>[unit]" style="flex: auto; border-left: none; width:auto; padding-left:15px; padding-right:25px; border-top-left-radius: 0;border-bottom-left-radius: 0;text-align:right; min-width:0;">
                <?php foreach( $units as $unit_option ): ?>
                    <option value="<?php echo esc_attr($unit_option) ?>" <?php selected($unit, $unit_option) ?>><?php echo esc_html($unit_option) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }
    
    // Alan ayarlarını render etme
    function render_field_settings( $field ) {
        acf_render_field_setting( $field, array(
            'label'         => __('Default Value','acf'),
            'instructions'  => __('Enter the default value for this field','acf'),
            'type'          => 'text',
            'name'          => 'default_value',
            'value'         => $field['default_value']
        ));
        return $field;
    }
    
    // Değerin veritabanına kaydedilmesi
    function update_value( $value, $post_id, $field ) {
        //error_log(json_encode($field));
        if (empty($value)) {
            $value['value'] = $field['default_value']["value"];
            $value['unit'] = $field['default_value']["unit"];
        }
        return $value;
    }

   /*function format_value($value, $post_id, $field) {
        $formatted_value = '';
        if (is_array($value)) {
            if (isset($value['value']) && !empty($value['value']) && isset($value['unit'])) {
                $formatted_value = $value['value'] . $value['unit'];
            }
        }
        return $formatted_value;
    } */


}

// Alan tipini kaydet
new acf_field_units();

endif;

?>
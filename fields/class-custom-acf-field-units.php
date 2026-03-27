<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( class_exists( 'acf_field_units' ) ) return;

class acf_field_units extends acf_field {

    public $name     = 'units';
    public $label    = 'Units';
    public $category = 'basic';
    public $units    = [];

    private static $assets_enqueued = false;

    function __construct() {
        $this->label    = __( 'Units', 'acf' );
        $this->defaults = [
            'units'         => ['px', '%', 'em', 'rem', 'vw', 'vh', 'vmin', 'vmax', 'pt', 'cm', 'mm', 'in', 'ex', 'ch', 'fr', 'auto', ''],
            'default_value' => ['value' => '0', 'unit' => ''],
        ];
        $this->units = $this->defaults['units'];
        parent::__construct();
    }

    /**
     * Admin CSS/JS — ACF input sayfasında sadece 1 kez yüklenir.
     * admin_head'e inline basma yok, her sayfada çalışmaz.
     */
    function input_admin_enqueue_scripts() {
        if ( self::$assets_enqueued ) return;
        self::$assets_enqueued = true;

        // Inline CSS — tek sefer
        wp_add_inline_style( 'acf-input', '
            .acf-field-units .acf-units-wrap {
                display: inline-flex;
                align-items: stretch;
                max-width: 220px;
            }
            .acf-field-units .acf-units-input {
                flex: 1 1 auto;
                border-right: none;
                border-top-right-radius: 0;
                border-bottom-right-radius: 0;
                min-width: 0;
            }
            .acf-field-units .acf-units-select {
                flex: 0 0 auto;
                border-left: none;
                border-top-left-radius: 0;
                border-bottom-left-radius: 0;
                padding-left: 10px;
                padding-right: 22px;
                text-align: right;
                min-width: 0;
                background-position: right 4px center;
            }
            .acf-field-units .acf-units-input:focus + .acf-units-select {
                border-color: #2271b1;
                box-shadow: 0 0 0 1px #2271b1;
                outline: 2px solid transparent;
            }
            .acf-field-units .acf-units-input[readonly] {
                background: #f0f0f1;
                color: #8c8f94;
                cursor: default;
                font-style: italic;
            }
            .acf-field-units .acf-units-select[data-auto="1"] {
                background-color: #f0f6fc;
                color: #2271b1;
                font-weight: 500;
            }
        ');

        // Inline JS — scope'lanmış, her field için ayrı çalışır
        wp_add_inline_script( 'acf-input', '
            if (typeof acf !== "undefined") {
                function acf_units_init($field) {
                    var $select = $field.find(".acf-units-select");
                    var $input  = $field.find(".acf-units-input");
                    $select.off("change.acf_units").on("change.acf_units", function() {
                        var isAuto = $(this).val() === "auto";
                        $input.prop("readonly", isAuto);
                        $(this).attr("data-auto", isAuto ? "1" : "0");
                        if (isAuto) { $input.val("auto"); }
                        else if ($input.val() === "auto") { $input.val(""); }
                    }).trigger("change.acf_units");
                }
                acf.add_action("ready_field/type=units", acf_units_init);
                acf.add_action("append_field/type=units", acf_units_init);
            }
        ');
    }

    function render_field( $field ) {
        $units = $this->units;
        $value = isset( $field['value']['value'] ) && is_array( $field['value'] )
            ? $field['value']['value']
            : ( is_array( $field['default_value'] ) ? $field['default_value']['value'] : '0' );
        $unit = isset( $field['value']['unit'] ) && is_array( $field['value'] )
            ? $field['value']['unit']
            : ( is_array( $field['default_value'] ) ? $field['default_value']['unit'] : '' );
        ?>
        <div class="acf-units-wrap">
            <input type="text"
                   name="<?php echo esc_attr( $field['name'] ); ?>[value]"
                   value="<?php echo esc_attr( $value ); ?>"
                   class="acf-units-input" />
            <select name="<?php echo esc_attr( $field['name'] ); ?>[unit]"
                    class="acf-units-select"
                    <?php echo $unit === 'auto' ? 'data-auto="1"' : ''; ?>>
                <?php foreach ( $units as $u ) : ?>
                    <option value="<?php echo esc_attr( $u ); ?>" <?php selected( $unit, $u ); ?>><?php echo $u === '' ? '—' : esc_html( $u ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }

    function render_field_settings( $field ) {
        acf_render_field_setting( $field, [
            'label'        => __( 'Default Value (Number)', 'acf' ),
            'instructions' => __( 'Enter the default numeric value for this field', 'acf' ),
            'type'         => 'text',
            'name'         => 'default_value_value',
            'value'        => ! empty( $field['default_value_value'] ) ? $field['default_value_value'] : '0',
        ]);

        acf_render_field_setting( $field, [
            'label'        => __( 'Default Unit', 'acf' ),
            'instructions' => __( 'Select the default unit', 'acf' ),
            'type'         => 'select',
            'name'         => 'default_value_unit',
            'choices'      => array_combine( $this->units, $this->units ),
            'value'        => ! empty( $field['default_value_unit'] ) ? $field['default_value_unit'] : '',
        ]);
    }

    function update_value( $value, $post_id, $field ) {
        if ( empty( $value ) || ! is_array( $value ) ) {
            return [
                'value' => $field['default_value']['value'] ?? '0',
                'unit'  => $field['default_value']['unit']  ?? '',
            ];
        }

        $val  = $value['value'] ?? ( $field['default_value']['value'] ?? '0' );
        $unit = $value['unit']  ?? ( $field['default_value']['unit']  ?? '' );

        if ( $unit === 'auto' ) {
            $val = 'auto';
        }

        return [ 'value' => $val, 'unit' => $unit ];
    }
}

acf_register_field_type( new acf_field_units() );

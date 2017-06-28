<div class="plg-tab-content">
    <div class="bwpb-panel-form">

        <h3><?php esc_html_e( 'General', 'AAA' ); ?></h3>

        <form class="plg-layouts-options bwpb-panel-content" id="plg-layouts-options-general">

            <?php

                $layouts_options_arr = require PL_DIR . 'inc/options_general.php';
                $layouts_options = apply_filters( 'bwpb_layouts_options', $layouts_options_arr );

                $layouts_options_new = array();

                foreach( $layouts_options as $option_name => $attr ) {

                    Playouts_Option_Type::render_option( $option_name, $attr );

                }

            ?>

        </form>

        <div class="plg-panel-footer">

            <a href="#" id="plg-do-layouts-settings-save" class="bwpb-button-round bwpb-button-save bwpb-button-primary"><?php esc_html_e('Save Settings', 'AAA'); ?></a>

        </div>

    </div>
</div>

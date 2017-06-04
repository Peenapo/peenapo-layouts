<div id="bwpb-modal" class="bwpb-modal bwpb-modal-has-favorites">

    <div class="bwpb-modal-header">

        <div class="bwpb-table">
            <div class="bwpb-cell">
                <h4><?php _e( 'Add Modules', 'AAA' ); ?></h4>
            </div>
            <div class="bwpb-cell">
                <?php do_action( 'pl_modal_tabs' ); ?>
            </div>
            <div class="bwpb-cell bwpb-align-right">
                <span class="bwpb-button-close"><span><?php _e( 'Close', 'AAA' ); ?></span><i class="bwpb-plus bwpb-close"></i></span>
            </div>
        </div>

    </div>

    <div class="bwpb-modal-content">

        <?php do_action( 'pl_modal_tabs_content' ); ?>

    </div>

    <div class="bwpb-modal-favorites">

        <?php do_action( 'bwpb_get_template_modal_favorites' ); ?>

    </div>

</div>

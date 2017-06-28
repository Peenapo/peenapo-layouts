<!-- TODO: move this on the bottom of the layout -->
<!-- custom css -->
<div id="bwpb-custom-css" class="bwpb-panel bwpb-panel-form">
    <div class="bwpb-panel-header">
        <h4 class="bwpb-panel-title"><?php _e( 'Custom CSS', 'peenapo-layouts-txd' ); ?></h4>
        <span class="bwpb-button-close"><em><?php _e( 'Close', 'AAA' ); ?></em><i class="bwpb-plus bwpb-close"><span></span></i></span>
    </div>
    <div class="bwpb-panel-tabs bwpb-no-select"></div>
    <div class="bwpb-panel-content">
        <div class="bwpb-panel-row bwpb-row-option-textarea">
            <div class="bwpb-panel-row-inner">
                <p><?php _e( 'Add additional CSS code, for the current post.', 'peenapo-layouts-txd' ); ?></p>
                <textarea class="bwpb-custom-css-textarea" name="bw_custom_css"><?php echo strip_tags( Playouts_Admin::get_custom_css() ); ?></textarea>
            </div>
        </div>
    </div>
    <div class="bwpb-panel-footer">
        <span class="bwpb-button-round bwpb-button-close"><?php _e( 'Close', 'peenapo-layouts-txd' ); ?></span>
        <span class="bwpb-button-round bwpb-button-save bwpb-button-primary"><?php _e( 'Save Custom CSS', 'peenapo-layouts-txd' ); ?></span>
    </div>
</div> <!-- end custom css -->

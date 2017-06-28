<!-- post status -->
<input type="hidden" id="bwpb_status" name="bwpb_status" value="<?php echo Playouts_Admin::$status ? '1' : ''; ?>">

<div class="bwpb-controls-section bwpb-controls-top">
    <div class="bwpb-button bwpb-button-primary bwpb-open-modal bwpb-open-modal-top pl-trigger-tooltip"data-tooltip="<?php esc_html_e( 'Add New Module', 'AAA' ); ?>" data-view="__solo" data-placement="before"><i class="pl-icon-grid"></i></div>
    <?php if( get_post_type() !== 'pl_layout' ): ?>
        <div class="bwpb-button bwpb-open-custom-css-panel pl-trigger-tooltip" data-tooltip="<?php esc_html_e( 'Add Custom CSS Code', 'AAA' ); ?>"><i class="pl-icon-page"></i></div>
    <?php endif; ?>
    <div class="bwpb-button bwpb-open-prompt bwpb-button-save-custom-layout pl-trigger-tooltip" data-tooltip="<?php esc_html_e( 'Save Content as Custom Layout', 'AAA' ); ?>" data-save-layout="content" data-prompt="save-layout"><i class="pl-icon-import"></i></div>
    <div class="bwpb-button bwpb-empty-content pl-trigger-tooltip" data-tooltip="<?php esc_html_e( 'Empty Content', 'AAA' ); ?>"><i class="pl-icon-trash-2"></i></div>
</div>

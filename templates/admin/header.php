<!-- post status -->
<input type="hidden" id="bwpb_status" name="bwpb_status" value="<?php echo Playouts_Admin::$status ? '1' : ''; ?>">

<div class="bwpb-controls-section bwpb-controls-top">
    <div class="bwpb-button bwpb-button-primary bwpb-open-modal bwpb-open-modal-top" data-view="__solo" data-placement="before"><i class="pl-icon-grid"></i></div>
    <?php if( get_post_type() !== 'pl_layout' ): ?>
        <div class="bwpb-button bwpb-open-custom-css-panel"><i class="pl-icon-page"></i></div>
    <?php endif; ?>
    <div class="bwpb-button bwpb-open-prompt bwpb-button-save-custom-layout" data-save-layout="content" data-prompt="save-layout"><i class="pl-icon-import"></i></div>
    <div class="bwpb-button bwpb-empty-content"><i class="pl-icon-trash-2"></i></div>
</div>

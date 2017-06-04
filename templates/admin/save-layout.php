<div class="bwpb-prompt bwpb-prompt-save-layout bwpb-panel bwpb-panel-size-small bwpb-panel-form">
    <div class="bwpb-panel-header">
        <h4 class="bwpb-panel-title"><?php _e( 'Save Layout', 'peenapo-layouts-txd' ); ?></h4>
        <span class="bwpb-button-close bwpb-prompt-close bwpb-prompt-key-escape"><span><?php _e( 'Close', 'AAA' ); ?></span><i class="bwpb-plus bwpb-close"></i></span>
    </div>
    <div class="bwpb-panel-tabs">
        <p><?php esc_html_e( 'Give it some name and save this layout so you can use it later in your project', 'AAA' ); ?></p>
    </div>
    <div class="bwpb-panel-content">
        <form>
            <div class="bwpb-panel-row">
                <div class="bwpb-panel-row-inner">
                    <h5><?php esc_html_e( 'Layout Name', 'AAA' ); ?></h5>
                    <input type="text" id="bwpb-field-layout-name" name="bwpb_field_layout_name">
                </div>
            </div>
            <div class="bwpb-panel-row">
                <div class="bwpb-panel-row-inner">
                    <h5>
                        <?php esc_html_e( 'Select Category', 'AAA' ); ?>
                        <i class="bwpb-icon-info bwpb-no-select"></i>
                    </h5>
                    <div class="bwpb-header-info">
                        <p><?php esc_html_e( 'Select a category ( optional ) for this template so you can find it easier.', 'AAA' ); ?></p>
                    </div>
                    <ul class="bw-save-layout-cats"></ul>
                    <input type="text" id="bwpb-field-layout-category" name="bwpb_field_layout_category" placeholder="<?php esc_html_e( 'Add new category', 'AAA' ); ?>">
                </div>
            </div>
        </form>
    </div>
    <div class="bwpb-panel-footer">
        <span class="bwpb-button-round bwpb-button-close bwpb-prompt-close"><?php _e( 'Close', 'peenapo-layouts-txd' ); ?></span>
        <span class="bwpb-button-round bwpb-button-primary bwpb-prompt-button-save-layout bwpb-prompt-key-enter"><?php _e( 'Save Layout', 'peenapo-layouts-txd' ); ?></span>
    </div>
</div>

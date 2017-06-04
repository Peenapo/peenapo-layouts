<div id="bwpb-favorites" class="bwpb-favorites">
    <div class="bwpb-favorites-header">
        <h4><?php _e( 'My Favorites', 'AAA' ); ?></h4>
        <p><?php _e( 'Add your favorite modules here for fast access', 'AAA' ); ?></p>
        <a href="#" class="bwpb-button-add"
            data-label-manage="<?php _e( 'Manage', 'AAA' ); ?>"
            data-label-save="<?php _e( 'Save', 'AAA' ); ?>">
                <?php _e( 'Manage', 'AAA' ); ?>
        </a>
    </div>
    <?php $list_html = Playouts_Admin_Modal::get_favorites_list(); ?>
    <ul class="bwpb-favorite-list bwpb-no-select<?php if( empty( $list_html ) ) { echo ' bwpb-empty'; } ?>"><?php echo $list_html; ?></ul>
</div>

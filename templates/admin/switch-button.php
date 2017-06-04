<?php if( get_post_type() !== 'pl_layout' ): ?>
    <div id="bwpb-switch-button" class="<?php echo join( ' ', apply_filters( 'bwpb_switch_class', array( 'bwpb-switch-button' ) ) ); ?>">
        <span class="bwpb-switch-mode bwpb-switch-mode-pb"><?php _e( 'WP Editor', 'AAA' ); ?></span>
        <span class="bwpb-switch-mode bwpb-switch-mode-classic"><?php _e( 'Enable Peenapo Layouts', 'AAA' ); ?></span>
    </div>
<?php endif; ?>

<div id="bwpb-main" class="<?php echo join( ' ', apply_filters( 'bwpb_main_class', array( 'bwpb-main', 'bwpb-ajaxing' ) ) ); ?>">

    <?php
    /*
     * get header template
     *
     */
    do_action( 'bwpb_get_template_header' );

    /*
     * get welcome template
     *
     */
    do_action( 'bwpb_get_template_welcome' );

    /*
     * get mosaic template
     * the ui of the modules
     *
     */
    do_action( 'bwpb_get_template_mosaic' );

    /*
     * get footer template
     *
     */
    do_action( 'bwpb_get_template_footer' );
    ?>

</div>

<?php
/*
 * get modal template
 * to add new elements and templates
 *
 */
do_action( 'bwpb_get_template_modal' );

/*
 * custom css panel
 *
 */
do_action( 'bwpb_get_template_custom_css_panel' );

/*
 * get overlay
 *
 */
do_action( 'bwpb_get_template_overlay' );

?>

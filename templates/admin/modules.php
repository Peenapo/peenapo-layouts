<?php

$controls_row = "
<div class='bwpb-controls'>
    <span class='bwpb-row-option bwpb-drag' title='" .  __( 'Move', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-move'></i></span>
    <span class='bwpb-row-option bwpb-edit' title='" .  __( 'Edit', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-edit'></i></span>
    <span class='bwpb-row-option bwpb-edit-columns' title='" .  __( 'Edit Columns', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-columns'></i></span>
    <span class='bwpb-row-option bwpb-cut' title='" .  __( 'Add New Column', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-column-add'></i></span>
    <span class='bwpb-row-option bwpb-duplicate' title='" .  __( 'Duplicate', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-duplicate'></i></span>
    <span class='bwpb-row-option bwpb-visibility' title='" .  __( 'Visibility', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-lock'></i></span>
    <span class='bwpb-row-option bwpb-open-prompt bwpb-button-save-custom-layout' title='" .  __( 'Save Layout', 'peenapo-layouts-txd' ) . "' data-save-layout='row' data-prompt='save-layout'><i class='pl-icon-import'></i></span>
    <span class='bwpb-row-option bwpb-trash' title='" .  __( 'Delete', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-trash'></i></span>
</div>
";

$block_edit_buttons = "
<div class='just-edit'>
    <div class='bwpb-label bwpb-no-select'></div>
    <div class='bwpb-option-holder'>
        <div class='bwpb-option bwpb-drag' title='" .  __( 'Move', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-move'></i></div>
        <div class='bwpb-option bwpb-edit' title='" .  __( 'Edit', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-edit'></i></div>
        <div class='bwpb-option bwpb-duplicate' title='" .  __( 'Duplicate', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-duplicate'></i></div>
        <div class='bwpb-option bwpb-open-prompt bwpb-button-save-custom-layout' title='" .  __( 'Save Layout', 'peenapo-layouts-txd' ) . "' data-save-layout='element' data-prompt='save-layout'><i class='pl-icon-import'></i></div>
        <div class='bwpb-option bwpb-trash' title='" .  __( 'Delete', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-trash'></i></div>
    </div>
    <span class='bwpb-block-plus bwpb-open-modal' data-view='column' data-placement='manually_after'><i class='bwpb-plus'></i></span>
</div>";

$block_drag = "
<div class='bwpb-drag-placeholder'>
    <div class='bwpb-drag-label'></div>
</div>
";
?>

<!-- row -->
<script type="text/template" id="bwpb_template-row">
    <div class="bwpb-block block-row bwpb-is-empty" data-id="" data-module="">
        <div class="bwpb-block-container">
            <?php echo $controls_row; ?>
            <div class="bwpb-content"></div>
        </div>
        <?php echo $block_drag; ?>
        <div class="bwpb-col-drag-bg"></div>
        <span class="bwpb-row-plus bwpb-open-modal" data-view="row" data-placement="after"><i class="bwpb-plus"></i></span>
    </div>
</script>

<!-- column -->
<script type="text/template" id="bwpb_template-column">
    <div class="bwpb-block block-column bwpb-is-empty" data-id="" data-module="" data-col-width="">
        <div class="bwpb-block-container">
            <span class="bwpb-col-plus bwpb-open-modal" data-view="column" data-placement="insert_bottom"><i class="bwpb-plus"></i></span>
            <div class="bwpb-content"></div>
        </div>
        <span class="bwpb-column-drag"><span class="bwpb-col-drag-handle"></span></span>
        <div class="bwpb-column-width">
            <span class="bwpb-col-width-label"><em>50</em></span>
        </div>
    </div>
</script>

<!-- row inner -->
<script type="text/template" id="bwpb_template-row_inner">
    <div class="bwpb-block block-row block-row-inner bwpb-is-empty" data-id="" data-module="">
        <div class="bwpb-block-container">
            <?php echo $controls_row; ?>
            <div class="bwpb-content"></div>
        </div>
        <?php echo $block_drag; ?>
        <div class="bwpb-col-drag-bg"></div>
        <span class="bwpb-row-plus bwpb-open-modal" data-view="row" data-placement="after"><i class="bwpb-plus"></i></span>
    </div>
</script>

<!-- column inner -->
<script type="text/template" id="bwpb_template-column_inner">
    <div class="bwpb-block block-column block-column-inner bwpb-is-empty" data-id="" data-module="" data-col-width="">
        <div class="bwpb-block-container">
            <span class="bwpb-col-plus bwpb-open-modal" data-view="column" data-placement="insert_bottom"><i class="bwpb-plus"></i></span>
            <div class="bwpb-content"></div>
        </div>
        <span class="bwpb-column-drag"><span class="bwpb-col-drag-handle"></span></span>
        <div class="bwpb-column-width">
            <span class="bwpb-col-width-label"><em>50</em></span>
        </div>
    </div>
</script>

<!-- block element -->
<script type="text/template" id="bwpb_template-element">
    <div class="bwpb-block bwpb-block-draggable bwpb-is-empty" data-id="" data-module="">
        <div class="bwpb-block-container">
            <?php echo $block_edit_buttons; ?>
        </div>
        <?php echo $block_drag; ?>
    </div>
</script>

<!-- repeater -->
<script type="text/template" id="bwpb_template-repeater">
    <div class="bwpb-block bwpb-block-draggable bwpb-block-repeater bwpb-is-empty" data-id="" data-module="">
        <div class="bwpb-block-container">
            <?php echo $block_edit_buttons; ?>
            <div class="bwpb-content"></div>
        </div>
        <?php echo $block_drag; ?>
    </div>
</script>

<!-- repeater item -->
<script type="text/template" id="bwpb_template-repeater_item">
    <div class="bwpb-block bwpb-block-repeater-item" data-id="" data-module=""></div>
</script>

<!-- panel repeater item -->
<script type="text/html" id="bwpb_template-panel_repeater_item">
    <div class="bwpb-item" data-id="">
        <div class="bwpb-item-container bwpb-no-select">
            <a href="#" class="bwpb-item-drag-handle">DRAG</a>
            <a href="#" class="bwpb-item-edit">EDIT</a>
            <a href="#" class="bwpb-item-delete">DELETE</a>
            <a href="#" class="bwpb-item-duplicate">DRUPLICATE</a>
        </div>
        <?php echo $block_drag; ?>
    </div>
</script>

<!-- divider -->
<script type="text/template" id="bwpb_template-separator">
    <div class="bwpb-block bwpb-separator-block" data-id="" data-module="">
        <div class="bwpb-block-container">
            <?php echo $block_edit_buttons; ?>
        </div>
        <?php echo $block_drag; ?>
    </div>
</script>

<!-- panel column -->
<script type="text/template" id="bwpb_template-panel_columns">
    <div class="bwpb-option-column" data-column-width="" data-id="">
        <span class="bwpb-option-column-drag"><span class="bwpb-option-column-dragger"></span></span>
        <span class="bwpb-column-label"></span>
    </div>
</script>

<!-- save custom layout panel - category item -->
<script type="text/template" id="bwpb_template-save_custom_layout_category_item">
    <li>
        <div class="bwpb-option-checkbox">
            <input type="checkbox" value="" id="" name="bwpb_field_layout_category_list">
            <label for="">
                <span></span>
            </label>
        </div>
    </li>
</script>

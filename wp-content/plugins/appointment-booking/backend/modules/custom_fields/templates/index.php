<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?php _e('Custom Fields', 'ab') ?></h3>
    </div>
    <div class="panel-body">
        <ul id="ab-custom-fields"></ul>

        <div id="ab-add-fields">
            <button class="button" data-type="text-field"><i class="glyphicon glyphicon-plus"></i> <?php _e( 'Text Field', 'ab' ) ?></button>&nbsp;
            <button class="button" data-type="textarea"><i class="glyphicon glyphicon-plus"></i> <?php _e( 'Text Area', 'ab' ) ?></button>&nbsp;
            <button class="button" data-type="checkboxes"><i class="glyphicon glyphicon-plus"></i> <?php _e( 'Checkbox Group', 'ab' ) ?></button>&nbsp;
            <button class="button" data-type="radio-buttons"><i class="glyphicon glyphicon-plus"></i> <?php _e( 'Radio Button Group', 'ab' ) ?></button>&nbsp;
            <button class="button" data-type="drop-down"><i class="glyphicon glyphicon-plus"></i> <?php _e( 'Drop Down', 'ab' ) ?></button>
        </div>

        <ul id="ab-templates" style="display:none">

            <li data-type="text-field">
                <i class="ab-handle glyphicon glyphicon-move"></i>
                <h2 class="ab-field-title">
                    <?php _e( 'Text Field', 'ab' ) ?>
                    <i class="ab-delete glyphicon glyphicon-remove" title="<?php echo esc_attr( __( 'Remove field', 'ab' ) ) ?>"></i>
                </h2>
                <div class="input-group">
                    <input class="ab-label form-control" type="text" value="" placeholder="<?php echo esc_attr( __( 'Enter a label', 'ab' ) ) ?>" />
                    <span class="input-group-addon">
                        <label>
                            <input class="ab-required" type="checkbox" />
                            <span><?php _e( 'Required field', 'ab' ) ?></span>
                        </label>
                    </span>
                </div>
            </li>

            <li data-type="textarea">
                <i class="ab-handle glyphicon glyphicon-move"></i>
                <h2 class="ab-field-title">
                    <?php _e( 'Text Area', 'ab' ) ?>
                    <i class="ab-delete glyphicon glyphicon-remove" title="<?php echo esc_attr( __( 'Remove field', 'ab' ) ) ?>"></i>
                </h2>
                <div class="input-group">
                    <input class="ab-label form-control" type="text" value="" placeholder="<?php echo esc_attr( __( 'Enter a label', 'ab' ) ) ?>" />
                    <span class="input-group-addon">
                        <label>
                            <input class="ab-required" type="checkbox" />
                            <span><?php _e( 'Required field', 'ab' ) ?></span>
                        </label>
                    </span>
                </div>
            </li>

            <li data-type="checkboxes">
                <i class="ab-handle glyphicon glyphicon-move"></i>
                <h2 class="ab-field-title">
                    <?php _e( 'Checkbox Group', 'ab' ) ?>
                    <i class="ab-delete glyphicon glyphicon-remove" title="<?php echo esc_attr( __( 'Remove field', 'ab' ) ) ?>"></i>
                </h2>
                <div class="input-group">
                    <input class="ab-label form-control" type="text" value="" placeholder="<?php echo esc_attr( __( 'Enter a label', 'ab' ) ) ?>" />
                    <span class="input-group-addon">
                        <label>
                            <input class="ab-required" type="checkbox" />
                            <span><?php _e( 'Required field', 'ab' ) ?></span>
                        </label>
                    </span>
                </div>
                <ul class="ab-items"></ul>
                <button class="button" data-type="checkboxes-item"><i class="glyphicon glyphicon-plus"></i> <?php _e( 'Checkbox', 'ab' ) ?></button>
            </li>

            <li data-type="radio-buttons">
                <i class="ab-handle glyphicon glyphicon-move"></i>
                <h2 class="ab-field-title">
                    <?php _e( 'Radio Button Group', 'ab' ) ?>
                    <i class="ab-delete glyphicon glyphicon-remove" title="<?php echo esc_attr( __( 'Remove field', 'ab' ) ) ?>"></i>
                </h2>
                <div class="input-group">
                    <input class="ab-label form-control" type="text" value="" placeholder="<?php echo esc_attr( __( 'Enter a label', 'ab' ) ) ?>" />
                    <span class="input-group-addon">
                        <label>
                            <input class="ab-required" type="checkbox" />
                            <span><?php _e( 'Required field', 'ab' ) ?></span>
                        </label>
                    </span>
                </div>
                <ul class="ab-items"></ul>
                <button class="button" data-type="radio-buttons-item"><i class="glyphicon glyphicon-plus"></i> <?php _e( 'Radio Button', 'ab' ) ?></button>
            </li>

            <li data-type="drop-down">
                <i class="ab-handle glyphicon glyphicon-move"></i>
                <h2 class="ab-field-title">
                    <?php _e( 'Drop Down', 'ab' ) ?>
                    <i class="ab-delete glyphicon glyphicon-remove" title="<?php echo esc_attr( __( 'Remove field', 'ab' ) ) ?>"></i>
                </h2>
                <div class="input-group">
                    <input class="ab-label form-control" type="text" value="" placeholder="<?php echo esc_attr( __( 'Enter a label', 'ab' ) ) ?>" />
                    <span class="input-group-addon">
                        <label>
                            <input class="ab-required" type="checkbox" />
                            <span><?php _e( 'Required field', 'ab' ) ?></span>
                        </label>
                    </span>
                </div>
                <ul class="ab-items"></ul>
                <button class="button" data-type="drop-down-item"><i class="glyphicon glyphicon-plus"></i> <?php _e( 'Option', 'ab' ) ?></button>
            </li>

            <li data-type="checkboxes-item">
                <i class="ab-inner-handle glyphicon glyphicon-move"></i>
                <input class="form-control ab-inline-block" type="text" value="" placeholder="<?php echo esc_attr( __( 'Enter a label', 'ab' ) ) ?>" />
                <i class="ab-delete glyphicon glyphicon-remove" title="<?php echo esc_attr( __( 'Remove item', 'ab' ) ) ?>"></i>
            </li>

            <li data-type="radio-buttons-item">
                <i class="ab-inner-handle glyphicon glyphicon-move"></i>
                <input class="form-control ab-inline-block" type="text" value="" placeholder="<?php echo esc_attr( __( 'Enter a label', 'ab' ) ) ?>" />
                <i class="ab-delete glyphicon glyphicon-remove" title="<?php echo esc_attr( __( 'Remove item', 'ab' ) ) ?>"></i>
            </li>

            <li data-type="drop-down-item">
                <i class="ab-inner-handle glyphicon glyphicon-move"></i>
                <input class="form-control ab-inline-block" type="text" value="" placeholder="<?php echo esc_attr( __( 'Enter a label', 'ab' ) ) ?>" />
                <i class="ab-delete glyphicon glyphicon-remove" title="<?php echo esc_attr( __( 'Remove item', 'ab' ) ) ?>"></i>
            </li>

        </ul>
    </div>
    <div class="panel-footer">
        <input type="submit" value="<?php echo esc_attr( __( 'Save', 'ab' ) ) ?>" class="btn btn-info ab-update-button" />
        <span class="spinner left"></span>
        <button class="btn btn-info ab-reset-form" type="reset"><?php _e( ' Reset ', 'ab' ) ?></button>
    </div>
</div>


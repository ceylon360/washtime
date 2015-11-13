<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div>
    <div id="ab_new_customer_dialog" class="modal fade">
        <div class="modal-dialog">
            <form style="margin-bottom: 0;" ng-hide=loading>
                <div class="modal-content">
                    <div class="modal-header">
                        <button type=button class=close data-dismiss=modal aria-hidden=true>×</button>
                        <h4 class="modal-title"><?php _e( 'New Customer', 'ab' ) ?></h4>
                    </div>
                    <div class="modal-body">
                        <fieldset>
                            <legend><?php _e( 'Personal Information', 'ab' ) ?></legend>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="wp_user"><?php _e( 'User' , 'ab' ) ?></label>
                                    <select ng-model="form.wp_user_id" class="form-control" id="wp_user">
                                        <option value=""></option>
                                        <?php foreach ( $wp_users as $wp_user ): ?>
                                            <option value="<?php echo $wp_user->ID ?>">
                                                <?php echo $wp_user->display_name ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="username"><?php _e( 'Name' , 'ab' ) ?></label>
                                    <input class="form-control" type="text" ng-model="form.name" id="username"/>
                                    <span style="font-size: 11px;color: red" ng-show="errors.name.required"><?php _e( 'Required' , 'ab' ) ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="phone"><?php _e( 'Phone' , 'ab' ) ?></label>
                                    <input class="form-control" type="text" ng-model=form.phone id="phone"/>
                                </div>
                                <div class="form-group">
                                    <label for="email"><?php _e( 'Email' , 'ab' ) ?></label>
                                    <input class="form-control" type="text"  ng-model=form.email id="email"/>
                                </div>
                                <div class="form-group">
                                    <label for="notes"><?php _e( 'Notes' , 'ab' ) ?></label>
                                    <textarea class="form-control" ng-model=form.notes id="notes"></textarea>
                                </div>
                            </div>
                        </fieldset>

                        <?php if ($module !== 'customer'): ?>
                            <fieldset>
                                <legend><?php _e( 'Custom Fields', 'ab' ) ?></legend>
                                <div class="new-customer-custom-fields">
                                    <div class="col-md-12">
                                        <?php foreach ( $custom_fields as $custom_field ): ?>
                                            <div class="form-group">
                                                <label><?php echo $custom_field->label ?></label>
                                                <div class="ab-formField" data-type="<?php echo $custom_field->type ?>" data-id="<?php echo $custom_field->id ?>">
                                                    <?php if ( $custom_field->type == 'text-field' ): ?>
                                                        <input type="text" class="form-control ab-custom-field" />

                                                    <?php elseif ( $custom_field->type == 'textarea' ): ?>
                                                        <textarea rows="3" class="form-control ab-custom-field"></textarea>

                                                    <?php elseif ( $custom_field->type == 'checkboxes' ): ?>
                                                        <?php foreach ( $custom_field->items as $item ): ?>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input class="ab-custom-field" type="checkbox" value="<?php echo esc_attr( $item ) ?>" />
                                                                    <?php echo $item ?>
                                                                </label>
                                                            </div>
                                                        <?php endforeach ?>

                                                    <?php elseif ( $custom_field->type == 'radio-buttons' ): ?>
                                                        <?php foreach ( $custom_field->items as $item ): ?>
                                                            <div class="radio">
                                                                <label>
                                                                    <input type="radio" name="<?php echo $custom_field->id ?>" class="ab-custom-field" value="<?php echo esc_attr( $item ) ?>" />
                                                                    <?php echo $item ?>
                                                                </label>
                                                            </div>
                                                        <?php endforeach ?>

                                                    <?php elseif ( $custom_field->type == 'drop-down' ): ?>
                                                        <select class="form-control ab-custom-field">
                                                            <option value=""></option>
                                                            <?php foreach ( $custom_field->items as $item ): ?>
                                                                <option value="<?php echo esc_attr( $item ) ?>"><?php echo $item ?></option>
                                                            <?php endforeach ?>
                                                        </select>

                                                    <?php endif ?>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            </fieldset>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <button ng-click=processForm() class="btn btn-info ab-popup-save ab-update-button"><?php _e( 'Create customer' , 'ab' ) ?></button>
                        <button class=ab-reset-form data-dismiss=modal aria-hidden=true><?php _e( 'Cancel' , 'ab' ) ?></button>
                    </div>
                </div><!-- /.modal-content -->
            </form>
            <div ng-show=loading class=loading-indicator>
                <img src="<?php echo plugins_url( 'backend/resources/images/ajax_loader_32x32.gif', AB_PATH . '/main.php' ) ?>" alt="" />
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
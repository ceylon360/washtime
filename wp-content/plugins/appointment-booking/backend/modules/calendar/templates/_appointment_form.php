<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly ?>
<style>
    .search-choice {
        display: none;
    }
</style>
<div ng-controller=appointmentDialogCtrl>
    <div id=ab_appointment_dialog class="modal fade">
        <div class="modal-dialog">
            <div ng-show=loading class="modal-content loading-indicator">
                <div class="modal-body">
                    <img src="<?php echo plugins_url( 'backend/resources/images/ajax_loader_32x32.gif', AB_PATH . '/main.php' ) ?>" alt="" />
                </div>
            </div>
            <div ng-hide=loading class="modal-content">
                <form ng-submit=processForm() class=form-horizontal>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><?php _e( 'New appointment', 'ab' ) ?></h4>
                    </div>
                    <div class="modal-body">

                        <div style="padding: 0 15px;">
                            <div class=form-group>
                                <label for="ab_provider"><?php _e( 'Provider', 'ab' ) ?></label>
                                <select id="ab_provider" class="field form-control" ng-model="form.staff" ng-options="s.full_name for s in dataSource.data.staff" ng-change="onStaffChange()"></select>
                            </div>

                            <div class=form-group>
                                <label for="ab_service"><?php _e( 'Service', 'ab' ) ?></label>
                                <div my-slide-up="errors.service_required" style="color: red; margin-top: 5px;">
                                    <?php _e( 'Please select a service', 'ab' ) ?>
                                </div>
                                <select id="ab_service" class="field form-control" ng-model="form.service" ng-options="s.title for s in form.staff.services" ng-change="onServiceChange()">
                                    <option value=""><?php _e( '-- Select a service --', 'ab' ) ?></option>
                                </select>
                            </div>

                            <div class=form-group>
                                <label for="ab_date"><?php _e( 'Date', 'ab' ) ?></label>
                                <input id="ab_date" class="form-control ab-auto-w" type=text ng-model=form.date ui-date="dateOptions"/>
                            </div>

                            <div class=form-group>
                                <label for="ab_period"><?php _e( 'Period', 'ab' ) ?></label>
                                <div>
                                    <div my-slide-up=errors.date_interval_not_available id=date_interval_not_available_msg style="color: red; margin-top: 5px;">
                                        <?php _e( 'The selected period is occupied by another appointment', 'ab' ) ?>
                                    </div>
                                    <select id="ab_period" style="display: inline" class="form-control ab-auto-w" ng-model=form.start_time ng-options="t.title for t in dataSource.data.time" ng-change=onStartTimeChange()></select>
                                    <span><?php _e( ' to ', 'ab' ) ?></span>
                                    <select style="display: inline" class="form-control ab-auto-w" ng-model=form.end_time
                                            ng-options="t.title for t in dataSource.getDataForEndTime()"
                                            ng-change=onEndTimeChange()></select>

                                    <div my-slide-up=errors.date_interval_warning id=date_interval_warning_msg style="color: red; margin-top: 5px;">
                                        <?php _e('The selected period does\'t match default duration for the selected service', 'ab') ?>
                                    </div>
                                    <div my-slide-up="errors.time_interval" ng-bind="errors.time_interval" style="color: red; margin-top: 5px;"></div>
                                </div>
                            </div>

                            <div class=form-group>
                                <label>
                                    <?php _e( 'Customers', 'ab' ) ?>
                                    <span ng-show="form.service" title="<?php echo esc_attr( __( 'Selected / maximum', 'ab' ) ) ?>">({{dataSource.getTotalNumberOfPersons()}}/{{form.service.capacity}})</span>
                                </label>
                                <div my-slide-up="errors.customers_required" style="color: red; margin-top: 5px;"><?php _e( 'Please select a customer', 'ab' ) ?></div>
                                <div my-slide-up="errors.overflow_capacity" ng-bind="errors.overflow_capacity" style="color: red; margin-top: 5px;"></div>
                                <ul class="ab-customer-list">
                                    <li ng-repeat="customer in form.customers">
                                        {{customer.number_of_persons}}&times;<img src="<?php echo plugins_url('backend/modules/calendar/resources/images/user.png', AB_PATH . '/main.php') ?>" alt="" />
                                        <a ng-click="editCustomFields(customer)" title="<?php echo esc_attr( __( 'Edit booking details', 'ab' ) ) ?>">{{customer.name}}</a>
                                        <span ng-click="removeCustomer(customer)" class="glyphicon glyphicon-remove ab-pointer" title="<?php echo esc_attr( __( 'Remove customer', 'ab' ) ) ?>"></span>
                                    </li>
                                </ul>

                                <div ng-show="!form.service || dataSource.getTotalNumberOfPersons() < form.service.capacity">
                                    <select id="chosen" multiple data-placeholder="<?php echo esc_attr( __( '-- Search customers --', 'ab' ) ) ?>"
                                            class="field chzn-select form-control" chosen="dataSource.data.customers"
                                            ng-model="form.customers" ng-options="c.name for c in dataSource.data.customers">
                                    </select><br/>
                                    <a href=#ab_new_customer_dialog class="{{btn_class}}" data-backdrop={{backdrop}} data-toggle="modal"><?php _e( 'New customer' , 'ab' ) ?></a>
                                </div>
                            </div>

                            <div class=form-group>
                                <label></label>
                                <input class="form-control" style="margin-top: 0" type="checkbox" ng-model=form.email_notification /> <?php _e( 'Send email notifications', 'ab' ) ?>
                                <img
                                    src="<?php echo plugins_url( 'backend/resources/images/help.png', AB_PATH . '/main.php' ) ?>"
                                    alt=""
                                    class="ab-popover"
                                    popover="<?php echo esc_attr( __( 'If email or SMS notifications are enabled and you want the customer or the staff member to be notified about this appointment after saving, tick this checkbox before clicking Save.', 'ab' ) ) ?>"
                                    style="width:16px;margin-left:0;"
                                    />
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <div class=dialog-button-wrapper>
                            <input type=submit class="btn btn-info ab-update-button" value="<?php _e('Save') ?>"/>
                            <a ng-click=closeDialog() class=ab-reset-form href="" data-dismiss="modal"><?php _e('Cancel') ?></a>
                        </div>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div style="margin-bottom: 2px;" class="ab-inline-block ab-create-customer" new-customer-dialog=createCustomer(customer) backdrop=false btn-class=""></div>
    <?php include '_custom_fields_form.php' ?>
</div>


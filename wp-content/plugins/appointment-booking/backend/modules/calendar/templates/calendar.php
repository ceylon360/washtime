<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    // wp start day
    $week_start_day = get_option( 'start_of_week', 1 );
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?php _e( 'Calendar', 'ab' ) ?></h3>
    </div>
    <div class="panel-body">
        <div ng-app=appointmentForm class="">
            <div id="ab_calendar_header">
                <div class="ab-nav-calendar">
                    <div class="btn-group right-margin left">
                        <button class="btn btn-info ab-calendar-switch-view ab-calendar-day"><?php _e( 'Day', 'ab' ) ?></button>
                        <button class="btn btn-info ab-calendar-switch-view ab-calendar-week ab-button-active"><?php _e( 'Week', 'ab' ) ?></button>
                    </div>
                    <button class="btn btn-info ab-calendar-today pull-left"><?php _e( 'Today', 'ab' ) ?></button>
                    <div id="week-calendar-picker" class="ab-week-picker-wrapper pull-left" data-first_day="<?php echo esc_attr( $week_start_day ) ?>">
                        <div class="input-group">
                            <span class="ab-week-picker-arrow prev input-group-addon ab-col-arrow">&#9668;</span>
                            <i class="glyphicon glyphicon-calendar"></i>
                            <input class="form-control ab-date-calendar" readonly="readonly" id="appendedPrependedInput" size="16" type="text" value="" />
                            <span class="ab-week-picker-arrow next input-group-addon ab-col-arrow">&#9658;</span>
                        </div>
                        <div class="ab-week-picker"></div>
                    </div>
                    <div id="day-calendar-picker" class="ab-week-picker-wrapper pull-left ab-auto-w" style="display: none;" data-first_day="<?php echo esc_attr( $week_start_day ) ?>">
                        <ul class="pagination pull-left">
                            <li><a href="#" class="ab-week-picker-arrow-prev">&#9668;</a></li>
                            <li><a style="padding: 0" href="#"></a></li>
                        </ul>
                        <div class="input-group pull-left" style="width: 168px">
                            <input style="width:131px;margin-left:-2px;border-radius:0;height:34px!important;" class="form-control" id="appendedInput" size="16" type="text" value="" />
                            <span style="border-radius:0" class="input-group-addon ab-col-arrow">▼</span>
                        </div>
                        <ul class="pagination pull-left">
                            <?php for ( $i = 1; $i <= 7; ++ $i ) : ?>
                                <li>
                                    <a href="#" class="ab-day-of-month" <?php if ( 1 == $i ) : ?> style="border-radius:0"<?php endif; ?>></a>
                                </li>
                            <?php endfor; ?>
                            <li><a href="#" class="ab-week-picker-arrow-next">&#9658;</a></li>
                        </ul>
                    </div>
                    <div class="btn-group pull-right">
                        <a class="btn btn-info ab-staff-filter-button" href="javascript:void(0)">
                            <i class="glyphicon glyphicon-user"></i>
                            <span id="ab-staff-button">
                                <?php
                                $staff_numb = count($collection);
                                if ($staff_numb == 0) {
                                    _e(' No staff selected','ab');
                                } else if ($staff_numb == 1) {
                                    echo $collection[0]->full_name;
                                } else {
                                    echo $staff_numb . ' '. __('staff members','ab');
                                }
                                ?>
                            </span>
                        </a>
                        <a class="btn btn-info dropdown-toggle ab-staff-filter-button" href="javascript:void(0)"><span class="caret"></span></a>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <?php if( $collection ) : ?>
                                <a href="javascript:void(0)">
                                    <input style="margin-right: 5px;" type="checkbox" checked="checked" id="ab-filter-all-staff" class="left">
                                    <label for="ab-filter-all-staff"><?php _e('All staff','ab') ?></label>
                                </a>

                                <?php foreach ($collection as $staff) : ?>
                                    <a style="padding-left: 35px;" href="javascript:void(0)">
                                        <input style="margin-right: 5px;" type="checkbox" checked="checked" id="ab-filter-staff-<?php echo $staff->id ?>" value="<?php echo $staff->id ?>" class="ab-staff-option left">
                                        <label style="padding-right: 15px;" for="ab-filter-staff-<?php echo $staff->id ?>"><?php echo $staff->full_name ?></label>
                                    </a>
                                <?php endforeach ?>
                                <?php else : ?>
                                    <a href="<?php echo AB_CommonUtils::escAdminUrl( AB_StaffController::page_slug ) ?>"><?php _e( 'New Staff Member', 'ab' ) ?></a>
                                <?php endif ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php if ( $collection ) : ?>
                <?php
                $user_names = array();
                $user_ids   = array();
                ?>
                <div id="week_calendar_wrapper">
                    <div class="tabbable" style="margin-top: 20px;">
                        <ul class="nav nav-tabs" style="margin-bottom:0;border-bottom: 6px solid #1f6a8c">
                            <?php foreach ( $collection as $i => $staff ) : ?>
                                <li class="ab-staff-tab-<?php echo $staff->id ?> ab-calendar-tab<?php echo 0 == $i ? ' active' : '' ?>" data-staff-id="<?php echo $staff->id ?>">
                                    <a href="#" data-toggle="tab"><?php echo $staff->full_name ?></a>
                                </li>
                                <?php
                                $user_names[] = $staff->full_name;
                                $user_ids[]   = $staff->id;
                                ?>
                            <?php endforeach ?>
                        </ul>
                    </div>
                    <div class="ab-calendar-element-container">
                        <div class="ab-calendar-element"></div>
                    </div>
                </div>
                <div id="day_calendar_wrapper" style="display: none">
                    <div class="ab-calendar-element-container">
                        <div class="ab-calendar-element"></div>
                    </div>
                </div>

                <?php include '_appointment_form.php' ?>

                <span id="staff_ids" style="display: none"><?php echo json_encode( $user_ids ) ?></span>
                <span id="ab_calendar_data_holder" style="display: none">
                    <span class="ab-calendar-first-day"><?php echo $week_start_day ?></span>
                    <span class="ab-calendar-time-format"><?php echo get_option( 'time_format' ) ?></span>
                    <span class="ab-calendar-users"><?php echo implode( '|', $user_names ) ?></span>
                </span>
            <?php endif; ?>
        </div>
    </div>
</div>


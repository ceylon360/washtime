jQuery(function($) {
    // menu fix for WP 3.8.1
    $('#toplevel_page_ab-system > ul').css('margin-left', '0px');

    /* exclude checkboxes in form */
    var $checkboxes = $('.ab-notifications > legend > input:checkbox[id!=_active]');

    $checkboxes.change(function () {
        if ($(this).is(":checked")) {
            $(this).parent().next('div.ab-form-field').show(200);
            toggleArrowDown($(this).parents('.ab-notifications').find('.ab-toggle-arrow'), false );
        } else {
            $(this).parent().next('div.ab-form-field').hide(200);
            toggleArrowDown($(this).parents('.ab-notifications').find('.ab-toggle-arrow'), true );
        }
    }).change();

    $('.ab-toggle-arrow').click(function () {
        var $element =  $(this).nextAll().nextAll('.ab-form-field');
        toggleArrowDown($(this), $element.is(":visible") );
        $element.toggle(200);
    });

    function toggleArrowDown($element, down){
        if( down ){
            $element.addClass('down').removeClass('up');
        }else{
            $element.removeClass('down').addClass('up');
        }
    }

    // filter sender name and email
    var escapeXSS = function (infected) {
        var regexp = /([<|(]("[^"]*"|'[^']*'|[^'">])*[>|)])/gi;
        return infected.replace(regexp, '');
    };
    $('input.ab-sender').on('change', function() {
        var $val = $(this).val();
        $(this).val(escapeXSS($val));
    });

});
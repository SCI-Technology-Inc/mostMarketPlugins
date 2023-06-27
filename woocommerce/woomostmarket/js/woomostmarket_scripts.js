/*
 * This file is part of is free software.
 */
/*
 Created on : 15.06.2023, 13:45:40
 Author     : Dmitrij Nedeljković https://dmitrydevelopment.com/
 */



(function ($) {

    $(document).on('click', '.y_save_btn_all', function (event) {

        event.preventDefault();


  var url = $('#most_market_url').val();

        var key = $('#most_market_key').val();
        var secret = $('#most_market_secret').val();

        if ($('#most_market_show_coupon').is(":checked"))
        {
            var show_coupon = 1;
        } else {
            var show_coupon = 0;
        }

        $.ajax({
            url: "/wp-admin/admin-ajax.php",
            type: 'POST',
            dataType: 'json',
            data: {action: 'woomostmarket_setup_save', url: url, key: key, secret: secret, show_coupon: show_coupon},
            success: function (response) {
                $('.woocommerce_saved1_most_p').fadeIn();
            }
        });
    });

    $(document).on('click', '.y_save_btn_coupon', function (event) {

        event.preventDefault();
        var multipleValues = {};
        $('.Most_product-search').each(function () {
            var service = $(this).attr("data-most_service");
            multipleValues[service] = $(this).val();
        });

        $.ajax({
            url: "/wp-admin/admin-ajax.php",
            type: 'POST',
            dataType: 'json',
            data: {action: 'woo_most_coupon_setup_save', multipleValues: JSON.stringify(multipleValues)},
            success: function (response) {
                $('.woocommerce_saved2_most_p').fadeIn();
            }
        });
    });

    $(document).on('click', '.y_save_btn_report', function (event) {

        event.preventDefault();

        $('.woocommerce_report_most_a').hide();

        var most_market_date_start = $('#most_market_date_start').val();
        var most_market_date_end = $('#most_market_date_end').val();

        $.ajax({
            url: "/wp-admin/admin-ajax.php",
            type: 'POST',
            dataType: 'json',
            data: {action: 'woo_most_coupon_get_report', most_market_date_start: most_market_date_start, most_market_date_end: most_market_date_end},
            success: function (response) {
                $('.woocommerce_report_most_a').fadeIn();
            }
        });
    });


})(jQuery);




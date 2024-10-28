jQuery(document).ready(function ($) {
    jQuery('.ct_wo_bid_user_role').select2({});
    jQuery('.ct_dashboard_user_role').select2({
        ajax: {
            url: ct_bidd_data.admin_url,
            dataType: 'json',
            type: 'POST',
            delay: 20,
            data: function (params) {
                return {
                    q: params.term,
                    action: 'ct_dashboard_user_role',
                    nonce: ct_bidd_data.nonce,
                };
            },
            processResults: function (data) {
                var options = [];
                if (data) {
                    $.each(data, function (index, user) {
                        options.push({ id: user.ID, text: user.display_name });
                    });
                }
                return { results: options };
            },
            cache: true
        },
        multiple: true,
        placeholder: 'Choose users',
    });
    jQuery('.ct_dfw_product_live_search').select2(
        {
            ajax: {
                url: ct_bidd_data.admin_url, // AJAX URL is predefined in WordPress admin.
                dataType: 'json',
                type: 'POST',
                delay: 20, // Delay in ms while typing when to perform a AJAX search.
                data: function (params) {
                    return {
                        q: params.term, // search query
                        action: 'af_rfd_prod_search', // AJAX action for admin-ajax.php.//aftaxsearchUsers(is function name which isused in adminn file)
                        nonce: ct_bidd_data.nonce // AJAX nonce for admin-ajax.php.
                    };
                },
                processResults: function (data) {
                    var options = [];
                    if (data) {
                        // data is the array of arrays, and each of them contains ID and the Label of the option.
                        $.each(
                            data, function (index, text) {
                                // do not forget that "index" is just auto incremented value.
                                options.push({ id: text[0], text: text[1] });
                            }
                        );
                    }
                    return {
                        results: options
                    };
                },
                cache: true
            },
            multiple: true,
            placeholder: 'Choose Products',
            // minimumInputLength: 3 // the minimum of symbols to input before perform a search.
        });
    jQuery(document).on('click', '.ct_bidding_send_email_data', function (e) {

        // e.preventDefault();
        // Send data via AJAX
        jQuery.ajax({
            type: 'POST',
            url: ct_bidd_data.admin_url,
            data: {
                action: 'ct_email_data_user',
                type: $(this).data('type'),
                current_post_id: $(this).data('current_post_id'),

            },
            success: function (response) {
                // window.location.reload( true );
            }

        });
    });

    jQuery(document).on('click', '.ct_disapprove_email_button', function (e) {

        // e.preventDefault();
        // Send data via AJAX
        jQuery.ajax({
            type: 'POST',
            url: ct_bidd_data.admin_url,
            data: {
                action: 'ct_disapprove_email',
                type: $(this).data('type'),
                current_post_id: $(this).data('current_post_id'),
                nonce: ct_bidd_data.nonce
            },
            success: function (response) {
                // window.location.reload( true );
            }

        });
    });

    jQuery('.ct_dfw_categroy_live_search').select2(
        {
            ajax: {
                url: ct_bidd_data.admin_url, // AJAX URL is predefined in WordPress admin.
                dataType: 'json',
                type: 'POST',
                delay: 20, // Delay in ms while typing when to perform a AJAX search.
                data: function (params) {
                    return {
                        q: params.term, // search query
                        action: 'category_search', // AJAX action for admin-ajax.php.//aftaxsearchUsers(is function name which isused in adminn file)
                        nonce: ct_bidd_data.nonce // AJAX nonce for admin-ajax.php.
                    };
                },
                processResults: function (data) {
                    var options = [];
                    if (data) {
                        // data is the array of arrays, and each of them contains ID and the Label of the option.
                        $.each(
                            data, function (index, text) {
                                // do not forget that "index" is just auto incremented value.
                                options.push({ id: text[0], text: text[1] });
                            }
                        );
                    }
                    return {
                        results: options
                    };
                },
                cache: true
            },
            multiple: true,
            placeholder: 'Choose category',
            minimumInputLength: 3 // the minimum of symbols to input before perform a search.
        });

    // user role
    jQuery('.ct_bid_serch_user').select2();
    jQuery(document).on('click change', '.ct_bidding_type , .ct_bidding_type_man', function (e) {
        ct_bidding_bid_type();

    });
});


ct_bidding_bid_type();

function ct_bidding_bid_type() {

    if ('automatic' == jQuery('.ct_bidding_type_man').val()) {
        jQuery('.ct_bidding_inc').closest('tr').css('display', 'revert');
    } else {
        jQuery('.ct_bidding_inc').closest('tr').css('display', 'none');

    }
}


jQuery(document).ready(function ($) {
    // Function to convert table data to CSV format


    // Event listener for the export CSV button
    $(document).on('click', '.devsoul-biiding-detail-export-csv-btn', function (e) {
        e.preventDefault();

        var csv = [];



        $(this).closest('.devsoul-table-detail').find('.bidding-data-detail-table tr').each(function () {
            var curre_tds = [];

            $(this).find('td , th').each(function () {
                // Escape double quotes and wrap each cell value in double quotes
                curre_tds.push('"' + $(this).text().replace(/"/g, '""') + '"');
            });

            csv.push(curre_tds.join(',')); // Join each cell with a comma
        });

        var filename = 'bidding_data.csv';
        var csvFile;
        var downloadLink;

        // Create CSV file
        csvFile = new Blob([csv.join('\n')], { type: 'text/csv' }); // Join rows with new lines

        // Create download link
        downloadLink = document.createElement('a');
        downloadLink.download = filename;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = 'none';

        // Append link to the body and click it
        document.body.appendChild(downloadLink);
        downloadLink.click();
    });

    $(document).on('submit', 'form.filter-container-content', function (event) {
        event.preventDefault();

        let dashboard_url = ct_bidd_data.dashboard_url;
        dashboard_url += '&' + $(this).serialize();

        window.location.href = dashboard_url;
    });

    $(document).on('click', '.devsoul-afw-clear-filter', function (event) {
        event.preventDefault();
        let location = $(this).data('location');

        let url = ct_bidd_data[location];

        window.location.href = url;


    })

});

jQuery(document).ready(function ($) {
    $('.show-bidding-details').on('click', function (event) {
        event.preventDefault();
        $(this).next('.show-user-detail-in-popup').fadeIn();
    });

    $('.close-popup').on('click', function (event) {
        event.preventDefault();
        $(this).closest('.show-user-detail-in-popup').fadeOut();
    });
});


jQuery(document).ready(function ($) {
    var mediaUploader;

    $('.devsoul-upload-badge').on('click', function (e) {
        e.preventDefault();

        // If the uploader object has already been created, reopen the dialog
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        // Extend the wp.media object
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });

        // When a file is selected, grab the URL and set it as the value of the hidden input field
        mediaUploader.on('select', function () {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('input[name="af_bid_select_badge"]').val(attachment.url);
        });

        // Open the uploader dialog
        mediaUploader.open();
    });
});

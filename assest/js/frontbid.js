jQuery(document).ready(function ($) {
    var reserve_price = php_vars_bid.ct_bid_reserve_price;
    jQuery(document).on('click', '.ct_bidding_button', function (e) {
        var ctBidPrice = $('#ct_bid_price').val();
        if (ctBidPrice == '') {
            alert("Field must be filled out ");
            return false;
        }

        jQuery.ajax({
            type: 'POST',
            url: php_vars_bid.admin_url,
            data: {
                action: 'ct_bidding_user',
                form_data: $(this).closest('form').serialize(),
                nonce: php_vars_bid.nonce,

            },
            success: function (response) {

                if (response && (response.reload || response['reload'])) {
                    window.location.reload(true);
                }

            }

        });
    });



    jQuery(document).on('click', '.ct_bid_offer_product', function (e) {
        jQuery.ajax({
            type: 'POST',
            url: php_vars_bid.admin_url,
            data: {
                action: 'ct_bidding_offer_product',
                form_data: $(this).closest('form').serialize(),
                nonce: php_vars_bid.nonce,

            },
            success: function (response) {

                if (response && (response.reload || response['reload'])) {
                    window.location.reload(true);
                }

            }

        });
    });
    jQuery(document).on('click', '.ct_bid_show_bidder', function (e) {
        var userName = "John Doe"; // Replace with the actual user name
        var bidPrice = 100; // Replace with the actual bid price

        jQuery('.popup_user_name span').text(userName);
        jQuery('.popup_bid_price span').text(bidPrice);

        jQuery('.ct_bidder_popup').show();
    });

    jQuery(document).on('click', '.close_popup', function (e) {
        // Close the popup
        jQuery('.ct_bidder_popup').hide();
    });

    // Close the popup if the user clicks outside of it
    jQuery(document).on('click', function (e) {
        if (!jQuery(e.target).closest('.ct_bidder_popup, .ct_bid_show_bidder').length) {
            jQuery('.ct_bidder_popup').hide();
        }
    });

});




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

});
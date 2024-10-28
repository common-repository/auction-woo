jQuery(document).ready(function ($) {
    // jQuery('.ct_dashboard_user_role').select2({});
    var biddingDetails = php_var.bidding_detail;
    // console.log('Bidding details:');
    // console.log(biddingDetails);


    if (!biddingDetails || biddingDetails.length === 0) {
        console.log('No bidding details found');
        return;
    }
    if ('all_products' == php_var.show_data) {

        google.charts.load('current', { packages: ['corechart', 'bar'] });
        google.charts.setOnLoadCallback(drawChart);
    }
    if ('date_according' == php_var.show_data) {

        // google.charts.load('current', { packages: ['corechart', 'line'] });
        // google.charts.setOnLoadCallback(drawChart);

        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(drawChart);

    }

    function drawChart() {
        if ('all_products' == php_var.show_data) {
            // console.log('all products');
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Product Name');
            data.addColumn('number', 'Highest Bid');

            for (var i = 1; i < biddingDetails.length; i++) {
                data.addRow([biddingDetails[i][0], parseFloat(biddingDetails[i][1])]);
            }

            var options = {
                title: 'Bidding Data',
                hAxis: {
                    title: 'Product Name'
                },
                vAxis: {
                    title: 'Highest Bid'
                },
                bars: 'vertical',
                height: 400,
                series: {
                    0: { axis: 'Highest Bid' }
                },
                axes: {
                    y: {
                        HighestBid: { label: 'Highest Bid (in currency)' }
                    }
                }
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('bidding-table'));
            chart.draw(data, options);
        }

        if ('date_according' == php_var.show_data) {

            var data = google.visualization.arrayToDataTable(biddingDetails);

            var options = {
                title: 'Product Auction Detail',
                curveType: 'function',
                legend: { position: 'bottom' },
                hAxis: {
                    title: 'Date',
                    format: 'MMM dd, yyyy',
                    gridlines: { count: 15 }
                },
                vAxis: {
                    title: 'Bid',
                    minValue: 0
                }
            };

            var chart = new google.visualization.LineChart(document.getElementById('bidding-table'));
            chart.draw(data, options);

        }

    }
});

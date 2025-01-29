define(['jquery', 'core/ajax', 'https://cdn.jsdelivr.net/npm/chart.js'], function ($, Ajax, Chart) {
    return {
        init: function () {
            var ctx = document.getElementById('quickstats-chart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [], // To be filled with AJAX call
                    datasets: [{
                        label: 'Active Users',
                        data: [], // To be filled with AJAX call
                        borderColor: 'blue',
                        borderWidth: 2,
                        fill: false,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Period',
                                font: {
                                    padding: 4,
                                    size: 20,
                                    weight: 'bold',
                                },
                                color: 'blue'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Active Users',
                                font: {
                                    size: 20,
                                },
                                color: 'blue'
                            },
                            beginAtZero: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Values',
                            }
                        }
                    }
                }
            });

            Ajax.call([{
                methodname: 'local_quickstats_get_active_users',
                args: {},
                done: function (data) {
                    console.log('Data received:', data); // Add this line for debugging
                    chart.data.labels = data.labels;
                    chart.data.datasets[0].data = data.counts;
                    chart.update();
                },
                fail: function (error) {
                    console.error('Error fetching data:', error); // Log any errors
                }
            }]);
        }
    };
});

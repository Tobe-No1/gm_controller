<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Highcharts Example</title>
        <script type="text/javascript" src="http://cdn.bootcss.com/jquery/1.8.2/jquery.min.js"></script>
        <style type="text/css"></style>
        <script type="text/javascript">
            $(function () {
                Highcharts.chart('container', {
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: '<?php echo $user_info['date'];?>'
                    },
                    xAxis: {
                        categories: [
                            <?php
                                foreach($datas as $d) { echo sprintf("\"%s\",",$d['stat_date']); }
                            ?>
                        ]
                    },
                    yAxis: {
                        title: {
                            text: '元'
                        }
                    },
                    plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: true
                            },
                            enableMouseTracking: false
                        }
                    },
                    series: [{
                            name: '<?php echo $user_info['mg_user_name']; ?>',
                            data: [
                                <?php
                                foreach($datas as $d) { echo sprintf("%d,",$d['total_money']/100); }
                            ?>
                            ]
                        }]
                });
            });
        </script>
    </head>
    <body>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </body>
</html>

<?php

if($_SESSION["sess_agent_status"] != "admin") {
    die("Acces denied");
}
?>
<script src="includes/js/highcharts.js"></script>
<script src="includes/js/exporting.js"></script>
<script type="text/javascript">
    
    $(function () {
        $('#container').highcharts({
            chart: {
                type: 'spline'
            },
            title: {
                text: 'Visits per hours'
            },
            subtitle: {
                text: 'Numbers of Visits per hours <?php echo ($get["informer_name"])?"for website: ".$get["informer_name"]:"" ?> <?php echo ($get["campaign_name"])?"for campaign: ".$get["campaign_name"]:"" ?> <?php echo ($get["country"])?"for country: ".$get["country"]:"" ?>'
            },
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: { // don't display the dummy year
                    // month: '%e. %b',
                    year: '%b'
                }
            },
            yAxis: {
                title: {
                    text: 'Visits <?php echo ($get["informer_name"])?" [website: ".$get["informer_name"]."]":"" ?> <?php echo ($get["campaign_name"])?"[campaign: ".$get["campaign_name"]."]":"" ?> <?php echo ($get["country"])?"[country: ".$get["country"]."]":"" ?>'
                },
                min: 0
            },
            tooltip: {
                formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
                        Highcharts.dateFormat('%e. %b', this.x) +': '+ this.y +'';
                }
            },
            series: [{
                name: 'Visits per hour',
                data: [ <?php
                    $sql2 = "SELECT 
                                YEAR(MIN(f.`inserted`)) as min_year, 
                                MONTH(MIN(f.`inserted`)) as min_month,
                                DAY(MIN(f.`inserted`)) as min_day,
                                HOUR(MIN(f.`inserted`)) as min_hour,
                                YEAR(MAX(f.`inserted`)) as max_year, 
                                MONTH(MAX(f.`inserted`)) as max_month,
                                DAY(MAX(f.`inserted`)) as max_day,
                                HOUR(MAX(f.`inserted`)) as max_hour
                            FROM `tbl_feedbacks` f ";
                    $sql2 .= $WHERE;
                    $sql2 .= " ORDER BY f.`inserted`
                            LIMIT 1;"; 

                    $query = mysqli_query($conn, $sql2);
                    list($min_year, $min_month, $min_day, $min_hour, $max_year, $max_month, $max_day, $max_hour) = mysqli_fetch_row($query);

                    if(!$min_year || !$min_month || !$min_day) { 
                        $from = new DateTime($get["from"]);
                        $min_year = $from->format('Y');
                        $min_month = $from->format('m');
                        $min_day = 1;
                    }
                    if(!$min_hour) {
                        $min_hour = 0;
                    }
                    if(!$max_year || !$max_month || !$max_day) {
                        $to = new DateTime($get["to"]);
                        $max_year = $to->format("Y");
                        $max_month = $to->format("m");
                        $max_day = $to->format("d");
                    }
                    if(!$max_hour) {
                        $max_hour = date("H");
                    }
                    
                    $date = $min_year."-".$min_month."-".$min_day." ".$min_hour.":00:00";
                    while (strtotime($date) <= strtotime($max_year."-".$max_month."-".$max_day." ".$max_hour.":00:00")) {
                        $data[(int)date("Y", strtotime($date))][(int)date("m", strtotime($date))][(int)date("d", strtotime($date))][(int)date("H", strtotime($date))][1] = 0;
                        $date = date("Y-m-d H:i:s", strtotime("+1 hour", strtotime($date)));
                    }
                    
                    $sql = "SELECT 
                                YEAR(f.`inserted`) as year1, 
                                MONTH(f.`inserted`) as month1,
                                DAY(f.`inserted`) as day1,
                                HOUR(f.`inserted`) as hour1
                            FROM `tbl_feedbacks` f";
                    $sql .= $WHERE;
                    $sql .= " ORDER BY f.`inserted`;";
                    $query = mysqli_query($conn, $sql);
                    while(list($year1, $month1, $day1, $hour1) = mysqli_fetch_row($query)) {
                        $data[$year1][$month1][$day1][$hour1][1] = $data[$year1][$month1][$day1][$hour1][1] + 1;
                    }
                    $count = 0;
                    foreach($data as $year1 => $lv1) {
                        foreach($lv1 as $month1 => $lv2) {
                            foreach($lv2 as $day1 => $lv3) {
                                foreach($lv3 as $hour1 => $lv4) {
                                    foreach($lv4 as $min1 => $lv5) {
                                        $count++;
                                    } 
                                } 
                            }         
                        } 
                    } 
                    if($count == 1) {
                        echo '[Date.UTC('.$min_year.','.$min_month.','.$min_day.','.$min_hour.',0,0), 0],';
                    }
                    foreach($data as $year1 => $lv1) {
                        foreach($lv1 as $month1 => $lv2) {
                            foreach($lv2 as $day1 => $lv3) {
                                foreach($lv3 as $hour1 => $lv4) {
                                    foreach($lv4 as $min1 => $lv5) {
                                        echo '[Date.UTC('.$year1.','.$month1.','.$day1.','.$hour1.','.$min1.',0), '.$data[$year1][$month1][$day1][$hour1][$min1].'],';
                                    } 
                                } 
                            }         
                        } 
                    } 
                    if($count == 1) {
                        echo '[Date.UTC('.$min_year.','.$min_month.','.$min_day.','.$min_hour.',2,0), 0],';
                    }
                ?>
                ]
            }]
        });
    });

</script> 

<?php
// echo $sql; 
// echo $sql2; 
// echo "<pre>", print_r($data), "</pre>"; 
?>

<div id="container" style="min-width:400px; width: 100%; height:550px; margin:0 auto;"></div>
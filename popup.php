<?php

$saturday = json_encode($saturday);
$sunday = json_encode($sunday);
$mean_time = json_encode($mean_time);
//ポップアップ内容
$alert = "<script type='text/javascript'>
            const saturday = $saturday;
            const sunday = $sunday;
            const mean_time = $mean_time;
            alert(sunday + ' ～ ' + saturday + 'の平均作業時間は' + mean_time + '分です');
         </script>";
echo $alert;
?>
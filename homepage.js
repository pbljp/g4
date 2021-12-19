//グラフ表示ファイル
window.onload = function (){
    let array = [];
    var today = new Date();
    const end_day = new Date(today.getFullYear(), today.getMonth()+1, 0);
    var tomorrow = end_day.toLocaleString();
    const end_date = end_day.getDate();//11月の月末日を取得

    var cnt = 1;
    for (var i =1; i<=end_date; i++){
        array.push(i);
    }

    console.log(array);
    //document.getElementById('date').innerHTML=end_date;
}

let array = [];
var today = new Date();
const end_day = new Date(today.getFullYear(), today.getMonth()-1, 0);
var tomorrow = end_day.toLocaleString();
const end_date = end_day.getDate();//11月の月末日を取得

var cnt = 1;
for (var i =1; i<=end_date; i++){
    array.push(i+'日');
}

console.log(junre1);
document.addEventListener('DOMContentLoaded', function () {
    var myChart = Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: date_title
        },
        xAxis: {
            categories: array
        },
        yAxis: {
            title: {
                text: '作業時間'
            }
        },
        plotOptions: {
            column: {
                stacking: 'normal'
            }
        },
        series: [{
            name: j1_name,
            data: junre1
        }, {
            name: j2_name,
            data: junre2
        }, {
            name: j3_name,
            data: junre3
        }]
    });
});
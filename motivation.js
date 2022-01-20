//モチベーショングラフ表示ファイル
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
//日にちの表示バグを修正(2022/01/0102)
const end_day = new Date(today.getFullYear(), today.getMonth()+1, 0);
var tomorrow = end_day.toLocaleString();
const end_date = end_day.getDate();//11月の月末日を取得

var cnt = 1;
for (var i =1; i<=end_date; i++){
   array.push(i+'日');
}
console.log(array);
console.log(junre1);

document.addEventListener('DOMContentLoaded', function () {
    var myChart = Highcharts.chart('container', {

        title: {
            text: date_title
        },

        subtitle: {
            text: 'Source: thesolarfoundation.com'
        },

        yAxis: [{
            title: {
                text: 'モチベーション値'
            },
            max: 5
        }],

        xAxis: {
            categories: array
        },

        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
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
        }],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }

    });
});
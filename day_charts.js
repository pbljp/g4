//日毎に何時何分から何時何分まで作業をしたかのグラフを出力する
document.addEventListener('DOMContentLoaded', function () {
  //xAxisの範囲を設定するのに使う．00:00～24:00
  var xAxis_min = new Date(Date.UTC(year, month-1,   day,  0, 0));
  var xAxis_max = new Date(Date.UTC(year, month-1, day+1,  0, 0));

  var myChart = Highcharts.chart('container', {
  chart: {
    type: 'xrange'
  },
  title: {
    text: year+'年'+month+'月'+day+'日'
  },
  accessibility: {
    point: {
      descriptionFormatter: function(point) {
        var ix = point.index + 1,
          category = point.yCategory,
          from = new Date(point.x),
          to = new Date(point.x2);
        return ix + '. ' + category + ', ' + from.toDateString() +
          ' to ' + to.toDateString() + '.';
      }
    }
  },
  xAxis: {
    type: 'datetime',
    tickInterval: 3600 * 1000,
    min: xAxis_min.getTime(),
    max: xAxis_max.getTime(),
  },
  yAxis: {
    title: {
      text: 'ジャンル名'
    },
    categories: [type1_name, type2_name, type3_name],
    reversed: true,
  },
  legend: {
    enabled: false
  },
  tooltip: {
    formatter: function(){
      var s = '時間：' + Highcharts.dateFormat('%H:%M', this.x) + '～' + Highcharts.dateFormat('%H:%M', this.x2);
      //モチベーションの値だけ☆が増える
      s += ('<br>' + 'モチベーション：');
      for(var i = 0; i < 5; i++){
        if(i < motivation_array[parseInt(this.point.name)]){
          s += '☆';
        }else{
          s += '・';
        }
      }
      s += ('<br>' + 'コメント：' + comment_array[parseInt(this.point.name)]);
      return s;
    }
  },
  series: [{
    //縦軸が全て表示されるようにダミーデータを入れる
    name: '全てのジャンル名を表示',
    // pointPadding: 0,
    // groupPadding: 0,
    borderColor: 'red',
    pointWidth: 1,

    data: [{
      x:  0,
      x2: 0,
      y:  0,
    }, {
      x:  0,
      x2: 0,
      y:  1,
    }, {
      x:  0,
      x2: 0,
      y:  2,
    }],
    dataLabels: {
      enabled: false
    }
  }, {
    name: year+'年'+month+'月'+day+'日',
    // pointPadding: 0,
    // groupPadding: 0,
    borderColor: 'gray',
    pointWidth: 20,

    data: data_json,
    dataLabels: {
      enabled: true
    }
  },]
  

});
});
var myConfig = {
    type: "area",
    borderRadius: "5px",
    globals: {
      fontFamily: "Noto Sans",
      fontSize: "16px"
    },
    stacked: true,
    plot: {
      aspect: "spline",
      alphaArea: 0.6
    },
    plotarea: {
      margin: "dynamic"
    },
    tooltip: {
      visible: false
    },
    scaleY: {
      short: true,
      shortUnit: 'k',
      lineColor: "#AAA5A5",
      tick: {
        lineColor: "#AAA5A5"
      },
      item: {
        fontColor: "#616161",
        paddingRight: 5
      },
      guide: {
        visible: false
      },
      label: {
        text: "Quantity",
        fontColor: "#616161"
      }
    },
    scaleX: {
      lineColor: "#AAA5A5",
      labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug"],
      tick: {
        lineColor: "#AAA5A5"
      },
      item: {
        fontColor: "#616161",
        paddingTop: 5
      },
      label: {
        text: "2016",
        fontColor: "#616161"
      }
    },
    series: [{
      values: [3435, 4212, 1627, 3189, 2325, 1334, 1567, 2685],
      text: "Footware",
      backgroundColor: "#2BE0BA",
      lineColor: "#2BE0BA",
      marker: {
        backgroundColor: "#2BE0BA",
        borderColor: "#2BE0BA"
  
      }
    }, {
      values: [2221, 3535, 4340, 2232, 4212, 1259, 3611, 4230],
      text: "Accessories",
      backgroundColor: "#AAA3D5",
      lineColor: "#AAA3D5",
      marker: {
        backgroundColor: "#AAA3D5",
        borderColor: "#AAA3D5"
  
      }
    }, {
      values: [1145, 2368, 1210, 1229, 1336, 1551, 1647, 1660],
      text: "Pants",
      backgroundColor: "#00BCD4",
      lineColor: "#00BCD4",
      marker: {
        backgroundColor: "#00BCD4",
        borderColor: "#00BCD4"
  
      }
    }]
  };
  
  zingchart.render({
    id: 'myChart',
    data: myConfig,
    height: '100%',
    width: '100%'
  });
  
  var myConfig = {
    globals: {
      fontFamily: "Noto Sans",
    },
    graphset: [{
      type: "line",
      borderRadius: "5px",
      x: 0,
      y: 0,
      height: "31%",
      width: "100%",
      backgroundColor: "#66D7E5",
      tooltip: {
        backgroundColor: "#66D7E5"
      },
      legend: {
        backgroundColor: "#66D7E5",
        borderColor: "none",
        item: {
          fontColor: "#fff"
        }
      },
      scaleY: {
        visible: false
      },
      scaleX: {
        visible: false
      },
      series: [{
        values: [35, 42, 67, 89, 25, 34, 67, 85],
        lineColor: "#fff",
        marker: {
          visible: false
        },
      }]
    }, {
      type: "bar",
      borderRadius: "5px",
      x: 0,
      y: "33%",
      height: "31%",
      width: "100%",
      legend: {
        borderColor: "none"
      },
      scaleY: {
        visible: false
      },
      scaleX: {
        visible: false
      },
      series: [{
        values: [35, 42, 67, 89, 25, 34, 67, 85],
        backgroundColor: "#2BE0BA",
        lineColor: "#AAA3D5"
      }]
    }, {
      type: "area",
      borderRadius: "5px",
      x: 0,
      y: "66%",
      height: "33%",
      width: "100%",
      legend: {
        borderColor: "none"
      },
      scaleY: {
        visible: false
      },
      scaleX: {
        visible: false
      },
      series: [{
        values: [35, 42, 67, 89, 25, 34, 67, 85],
        backgroundColor: "#AAA3D5",
        lineColor: "#AAA3D5",
        marker: {
          visible: false
        }
      }]
    }]
  };
  
  zingchart.render({
    id: 'myChart1',
    data: myConfig,
    height: '100%',
    width: '100%'
  });

  $("button").click(function(){
    $("#table2excel").table2excel({
      // exclude CSS class
      exclude: ".noExl",
      name: "Worksheet Name",
      filename: "SomeFile" //do not include extension
    }); 
  });
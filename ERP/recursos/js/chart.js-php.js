// Will be filled with canvas
var ChartJSPHP = new Array();

// You must call this function after document.ready
function loadChartJsPhp(DivID) {
 // Getting all chart.js canvas
 var elements = document.querySelectorAll("[data-chartjs]");

 // console.log(elements);

 // Looping every canvas
 for (var i in elements)
 {

  // Escaping length and item in the loop
  if (i === 'length' || i === 'item') {
   continue;
  }

  var ca= elements[i];
  var canvas = document.getElementById(DivID);
  var ctx = canvas.getContext('2d');
  var id = canvas.id;
  // Getting ctx from canvas
  // var ctx = canvas.getContext('2d');

  // Getting values in data attributes
  var htmldata = canvas.dataset;
  var data = JSON.parse(htmldata.data);
  var type = htmldata.chartjs;

  // Creating chart and saving for later use
  ChartJSPHP[id] = new Chart(ctx)[type](data);
 }
};

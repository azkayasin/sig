

var image = new ol.style.Circle({   
  radius: 5,
  fill: null,
  stroke: new ol.style.Stroke({color: 'red', width: 1})
});

var styles = {
  'Point': new ol.style.Style({
    image: image
  }),
  'LineString': new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: 'green',
      width: 1
    })
  }),
  'MultiLineString': new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: 'blue',
      width: 6
    })
  }),
  'MultiPoint': new ol.style.Style({
    image: image
  }),
  'MultiPolygon': new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: 'yellow',
      width: 1
    }),
    fill: new ol.style.Fill({
      color: 'rgba(255, 255, 0, 0.1)'
    })
  }),
  'Polygon': new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: 'blue',
      lineDash: [4],
      width: 3
    }),
    fill: new ol.style.Fill({
      color: 'rgba(0, 0, 255, 0.1)'
    })
  }),
  'GeometryCollection': new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: 'magenta',
      width: 2
    }),
    fill: new ol.style.Fill({
      color: 'magenta'
    }),
    image: new ol.style.Circle({
      radius: 10,
      fill: null,
      stroke: new ol.style.Stroke({
        color: 'magenta'
      })
    })
  }),
  'Circle': new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: 'red',
      width: 2
    }),
    fill: new ol.style.Fill({
      color: 'rgba(255,0,0,0.2)'
    })
  })
};

var styles1 = {
  'Point': new ol.style.Style({
    image: image
  }),
  'LineString': new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: 'green',
      width: 1
    })
  }),
  'MultiLineString': new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: 'red',
      width: 2
    })
  }),
  'MultiPoint': new ol.style.Style({
    image: image
  }),
  'MultiPolygon': new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: 'yellow',
      width: 1
    }),
    fill: new ol.style.Fill({
      color: 'rgba(255, 255, 0, 0.1)'
    })
  }),
  'Polygon': new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: 'yellow',
      lineDash: [4],
      width: 3
    }),
    fill: new ol.style.Fill({
      color: 'rgba(0, 0, 255, 0.1)'
    })
  }),
  'GeometryCollection': new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: 'magenta',
      width: 2
    }),
    fill: new ol.style.Fill({
      color: 'magenta'
    }),
    image: new ol.style.Circle({
      radius: 10,
      fill: null,
      stroke: new ol.style.Stroke({
        color: 'magenta'
      })
    })
  }),
  'Circle': new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: 'red',
      width: 2
    }),
    fill: new ol.style.Fill({
      color: 'rgba(255,0,0,0.2)'
    })
  })
};

function CenterMap(long, lat) {
    console.log("Long: " + long + " Lat: " + lat);
    map.getView().setCenter(ol.proj.transform([long, lat], 'EPSG:4326', 'EPSG:3857'));
    map.getView().setZoom(5);
}

var geojsonObject;


var styleFunction = function(feature) {
  return styles[feature.getGeometry().getType()];
};

var styleFunction1 = function(feature) {
  return styles1[feature.getGeometry().getType()];
};

var format = new ol.format.GeoJSON({
featureProjection:"EPSG:3857"
});


var vectorLayer = new ol.layer.Vector({
  source: new ol.source.Vector(),
  style: styleFunction
});

var vectorLayer1 = new ol.layer.Vector({
  source: new ol.source.Vector(),
  style: styleFunction1
});

var map = new ol.Map({
  layers: [
    new ol.layer.Tile({
      source: new ol.source.OSM()
    }),
    vectorLayer,
    vectorLayer1,
  ],
  target: 'map',
  controls: ol.control.defaults({
    attributionOptions: {
      collapsible: false
    }
  }),
  view: new ol.View({
    center: ol.proj.fromLonLat([112.7425, -7.265278]),
    zoom: 12
  })
});
function execute(){
  //vectorLayer.getSource().clear();
  if(vectorLayer.getSource()){
    vectorLayer.getSource().clear();
  }
  if(vectorLayer1.getSource()){
    vectorLayer1.getSource().clear();
  }
  var format = new ol.format.GeoJSON({
  featureProjection:"EPSG:3857"
  });
  //ini untuk menggambar
  var done= new Promise(function(resolve,reject){
      var elements=document.forms.lembar.getElementsByTagName('input');
      //var koor0=$('#koor0');
      //var koor_0 = koor0.val();
      var koor1 = $('#koor1');
      var koor_1 = koor1.val();

      var temp21 = $('#latitude');
      var tempx = temp21.val();

      var temp22 = $('#longitude');
      var tempy = temp22.val();
      //var tempp = koor_0.split(',');
      var tempp1 = koor_1.split(',');
      var oReq = new XMLHttpRequest();
      oReq.onload = reqListener;
      var url="http://localhost/ver/find3.php?x1="+tempx+"&y1="+tempy+"&x2="+tempp1[0]+"&y2="+tempp1[1];
      // var url="http://localhost/tryMap/find.php?x1="+elements[0].value+"&y1="+elements[1].value+"&x2="+elements[2].value+"&y2="+elements[3].value;
      oReq.open("GET",url, true);
      oReq.send();
      console.log(url);
      function reqListener(e) {
          geojsonObject = JSON.parse(this.responseText);
          resolve(geojsonObject);
      }
  });

  done.then((geojsonObject)=>{
    console.log(geojsonObject);
    //console.log(vectorLayer.getSource());
    vectorLayer.getSource().addFeatures(format.readFeatures(geojsonObject.astar));
    vectorLayer1.getSource().addFeatures(format.readFeatures(geojsonObject.dijkstra));
    //console.log(vectorLayer.getSource());
    // var vectorSource = new ol.source.Vector({
    //   features: (new ol.format.GeoJSON()).readFeatures(geojsonObject)
    // });

    //var geojsonObject = t;
    //window.alert(geojsonObject);
  }).catch((error)=>{
    console.log(error);
  });
  //ini untuk mengghitung
  var done1= new Promise(function(resolve,reject){
      var elements=document.forms.lembar.getElementsByTagName('input');
      // var koor0=$('#koor0');
      // var koor_0 = koor0.val();
      var koor1 = $('#koor1');
      var koor_1 = koor1.val();
      var temp21 = $('#latitude');
      var tempx = temp21.val();

      var temp22 = $('#longitude');
      var tempy = temp22.val();

      //var tempp = koor_0.split(',');
      var tempp1 = koor_1.split(',');
      var oReq = new XMLHttpRequest();
      oReq.onload = reqListener;
      var url="http://localhost/ver/jarak3.php?x1="+tempx+"&y1="+tempy+"&x2="+tempp1[0]+"&y2="+tempp1[1];
      // var url="http://localhost/tryMap/jarak.php?x1="+elements[0].value+"&y1="+elements[1].value+"&x2="+elements[2].value+"&y2="+elements[3].value;
      oReq.open("GET",url, true);
      oReq.send();
      console.log(url);
      function reqListener(e) {
          geojsonObject = JSON.parse(this.responseText);
          resolve(geojsonObject);
      }
  });

  done1.then((geojsonObject)=>{
    console.log(geojsonObject);
    var points = geojsonObject;
    var dist_inMeter = points*51.7647059/1000;
    var dist_string = dist_inMeter.toString();
    dist_string = dist_string.split(".")
    console.log(dist_string);
    var sisa = dist_string[1]
    console.log(sisa);
    var total = dist_string[0]+"."+sisa[0];
    var tex = $('#jarakGanti');
    tex.text("Jarak : "+total+"km");
    //console.log(vectorLayer.getSource());
    vectorLayer.getSource().addFeatures(format.readFeatures(geojsonObject.astar));
    vectorLayer1.getSource().addFeatures(format.readFeatures(geojsonObject.dijkstra));
    //console.log(vectorLayer.getSource());
    // var vectorSource = new ol.source.Vector({
    //   features: (new ol.format.GeoJSON()).readFeatures(geojsonObject)
    // });

    //var geojsonObject = t;
    //window.alert(geojsonObject);
  }).catch((error)=>{
    console.log(error);
  });
  return false; 
}

// ==============================================


function terdekat()
{
  //vectorLayer.getSource().clear();
  if(vectorLayer.getSource()){
    vectorLayer.getSource().clear();
  }
  if(vectorLayer1.getSource()){
    vectorLayer1.getSource().clear();
  }
  var format = new ol.format.GeoJSON({
  featureProjection:"EPSG:3857"
  });
  var jarak1 = 0;
  var jarak2 = 0;
  var jarak3 = 0;
  var jarak4 = 0;
  var jarak5 = 0;
  var jarak6 = 0;
  var semuajarak = [99999,0,0,0,0,0,0];
  console.log(semuajarak);
                      var done1= new Promise(function(resolve,reject){
                      var elements=document.forms.lembar.getElementsByTagName('input');
                      // var koor0=$('#koor0');
                      // var koor_0 = koor0.val();
                      //var koor1 = $('#koor1');
                      //var koor_1 = koor1.val();
                      var temp21 = $('#latitude');
                      var tempx = temp21.val();

                      var temp22 = $('#longitude');
                      var tempy = temp22.val();
                      var masjidx1 = -7.28235;
                      var masjidy1 = 112.79307;
                      // console.log(tempx);
                      // console.log(tempy);
                      // console.log(masjidx1);
                      // console.log(masjidy1);
                      //var tempp = koor_0.split(',');
                      //var tempp1 = koor_1.split(',');
                      var oReq = new XMLHttpRequest();
                      oReq.onload = reqListener;
                      var url="http://localhost/ver/jarak3.php?x1="+tempx+"&y1="+tempy+"&x2="+masjidx1+"&y2="+masjidy1;
                      // var url="http://localhost/tryMap/jarak.php?x1="+elements[0].value+"&y1="+elements[1].value+"&x2="+elements[2].value+"&y2="+elements[3].value;
                      oReq.open("GET",url, true);
                      oReq.send();
                      //console.log(url);
                      function reqListener(e) {
                          geojsonObject = JSON.parse(this.responseText);
                          resolve(geojsonObject);
                      }
                  });

                  done1.then((geojsonObject)=>{
                    //console.log(geojsonObject);
                    var points = geojsonObject;
                    var dist_inMeter = points*51.7647059/1000;
                    var dist_string = dist_inMeter.toString();
                    dist_string = dist_string.split(".")
                    //console.log(dist_string);
                    var sisa = dist_string[1]
                    //console.log(sisa);
                    var total = dist_string[0]+"."+sisa[0];
                    console.log(total);
                    semuajarak[1]=total;
                    console.log(semuajarak);
                  })

                  var done2= new Promise(function(resolve,reject){
                      var elements=document.forms.lembar.getElementsByTagName('input');
                      var temp21 = $('#latitude');
                      var tempx = temp21.val();

                      var temp22 = $('#longitude');
                      var tempy = temp22.val();
                      var masjidx1 = -7.28996;
                      var masjidy1 = 112.79694;
                      var oReq = new XMLHttpRequest();
                      oReq.onload = reqListener;
                      var url="http://localhost/ver/jarak3.php?x1="+tempx+"&y1="+tempy+"&x2="+masjidx1+"&y2="+masjidy1;
                      // var url="http://localhost/tryMap/jarak.php?x1="+elements[0].value+"&y1="+elements[1].value+"&x2="+elements[2].value+"&y2="+elements[3].value;
                      oReq.open("GET",url, true);
                      oReq.send();
                      function reqListener(e) {
                          geojsonObject = JSON.parse(this.responseText);
                          resolve(geojsonObject);
                      }
                  });

                  done2.then((geojsonObject)=>{
                    var points = geojsonObject;
                    var dist_inMeter = points*51.7647059/1000;
                    var dist_string = dist_inMeter.toString();
                    dist_string = dist_string.split(".")
                    var sisa = dist_string[1]
                    var total = dist_string[0]+"."+sisa[0];
                    semuajarak[2]=total;
                    console.log(total);
                    console.log(semuajarak);
                  })

                  
                  
}
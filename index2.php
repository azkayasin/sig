<!DOCTYPE html>
<html>
  <head>
    <title>GeoJSON</title>
    <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css">
    <!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
    <script src="https://openlayers.org/en/v4.6.5/build/ol.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
  </head>
  <body>
    <div class="containter-fluid">
      <div class="row flex-xl-nowrap">
        <!-- <div class="col-12 col-md-3 col-xl-2 bd-sidebar">
          <div  style="margin-left: 10px; margin-top: 10px;" >
            <form id="lembar">
              <div class="form-group">
                <label for="exampleFormControlInput1">Koordinat Awal</label>
                <input type="text" class="form-control" id="X1" placeholder="masukkan X1">
                <input type="text" class="form-control" id="Y1" placeholder="masukkan Y1">
              </div>
              <div class="form-group">
                <label for="exampleFormControlInput1">Koordinat Tujuan</label>
                <input type="text" class="form-control" id="X2" placeholder="masukkan X2">
                <input type="text" class="form-control" id="Y2" placeholder="masukkan Y2">
              </div>
              <button onclick="return execute()" class="btn btn-danger mb-2">Submit</button>
            </form>
          </div>
        </div> -->
        <main class="col-12 col-md-9 col-xl-10 py-md-3 pl-md-5 bd-content" role="main">
          <center>
          <h1>ClearRoute</h1>
          </center>
              <button onclick="getLocation()">Posisi anda</button>
              <button onclick="return terdekat()" class="btn btn-danger mb-2" id="buttonCariJarak">Jarak Terdekat</button>
              
              <input type="text" class="form-control" name="latitude" id="latitude" >
              <input type="text" class="form-control" name="longitude" id="longitude" >
              <form id="lembar">
              <div class="form-group">
                <label for="exampleFormControlInput1">Tujuan</label>
                <select id="koor1">
                  <option disabled value="Option">Option</option>
                  <option value="-7.28235,112.79307">Masjid Manarul Ilmi</option>
                  <option value="-7.28996,112.79694">Masjid As Saadah</option>
                  <option value="-7.29648,112.80044">Masjid Nur-Hasan</option>
                  <option value="-7.29915,112.80023">Masjid Al-Muklisin</option>
                  <option value="-7.32045,112.79848">Masjid At Taqwa</option>
                  <option value="-7.30676,112.79871">Masjid Nashrulloh</option>
                </select>
              </div>
              <button onclick="return execute()" class="btn btn-danger mb-2" id="buttonCariJarak">Submit</button>
              <p id="jarakGanti">Jarak : </p>
            </form>
          <div id="map" class="map"></div>
        </main>

      </div>
    </div>
    
    <script>
                  var sigcuk = document.getElementById("demo");

                  function getLocation() {
                      if (navigator.geolocation) {
                          navigator.geolocation.getCurrentPosition(showPosition);

                      } else { 
                          sigcuk.innerHTML = "Geolocation is not supported by this browser.";
                      }
                  }

                  function showPosition(position) {
                      
                      var latitude = position.coords.latitude
                      var longitude = position.coords.longitude
                      console.log(latitude)
                      console.log(longitude)
                      $('#latitude').attr('value',latitude)
                      $('#longitude').attr('value',longitude)
                  }
        </script>
        <script>
                function terdekat(){
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
        </script>
        <script src="script3.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  </body>
</html>
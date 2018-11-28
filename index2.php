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
        <script src="script3.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  </body>
</html>
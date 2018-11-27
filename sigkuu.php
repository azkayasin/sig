<!DOCTYPE html>
<html>
<body>

<p>Click the button to get your coordinates.</p>

<button onclick="getLocation()">Try It</button>

<p id="demo"></p>
 <input type="text" class="form-control" name="latitude" id="latitude" >
 <input type="text" class="form-control" name="longitude" id="longitude" >
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
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

</body>
</html>

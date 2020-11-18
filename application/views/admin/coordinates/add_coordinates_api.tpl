{literal}
<script type="text/javascript">
 
var marker;
 
function initialize() {
if (GBrowserIsCompatible())
{
	var w = $(window);	
	var map = new GMap2(
							document.getElementById("map_canvas"),
							{
								size:new GSize(w.width(), w.height()-50),
								draggableCursor:"crosshair",
								draggingCursor:"move"
							}
						);
						
	map.setCenter(new GLatLng({/literal}{$current_position}{literal}), 14);
	map.addControl(new GLargeMapControl());
	map.addControl(new GMapTypeControl());
 
	GEvent.addListener(map, "click", function(overlay, latlng) {
	if (latlng) {
            marker = new GMarker(latlng, {draggable:true});
            GEvent.addListener(marker, "click", function() {
              var html = "<table>" +
                         "<tr><td>Наименование места:</td> <td><input type='text' id='name'/> </td> </tr>" +
                         "<tr><td>Тип места:</td><td>{/literal}{$placement_type}{literal}" +
                         "</td></tr>" +
                         "<tr><td></td><td><input type='button' value='Сохранить' onclick='saveData()'/></td></tr></form>";
 
              marker.openInfoWindow(html);
            });
            map.addOverlay(marker);
          }
        });
 
      }
    }
 
    function saveData() {
      var name = escape(document.getElementById("name").value);
      var type = document.getElementById("placetype").value;
      var latlng = marker.getLatLng();
      var lat = latlng.lat();
      var lng = latlng.lng();
 
	  var url = "{/literal}{php}echo base_url();{/php}{literal}admin/savenewplace?name=" + name + "&type=" + type + "&lat=" + lat + "&lng=" + lng;
      GDownloadUrl(url, function(data, responseCode) {
//        if (responseCode == 200 && data.length <= 1) {
          marker.closeInfoWindow();
          document.getElementById("message").innerHTML = "Данные добавлены.";
//        }
      });
    }
    </script>
{/literal}
<div id="message"></div>
<div id="map_canvas" style="width: 500px; height: 500px"></div>

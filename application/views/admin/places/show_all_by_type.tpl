{literal}
<script type="text/javascript">

	var map, marker, gmarkers = [];
 
	$(document).ready(function(){
		var w = $(window);	
		map = new GMap2(
							document.getElementById("map_canvas"),
							{
								size:new GSize(w.width()-250, w.height()-50),
							}
						);
		map.setCenter(new GLatLng({/literal}{$current_position}{literal}), {/literal}{$current_zoom}{literal});
 
		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
 
		$('#markerTypes input[type="checkbox"]').bind('click', function () {
 
			var markersType = $(this).val();
	
			if($(this).attr("checked")) {
 
				if(!gmarkers[markersType]) {
 
					gmarkers[markersType] = [];
					
					$.ajax({
							url: "{/literal}{php} echo base_url();{/php}admin/getplacesbytype{literal}/"+markersType, 
							success: function(data){
								setMarkers(data, markersType);
							},
							dataType: 'json'
					});
					
				} else {
					show(markersType);
				}
 
			} else {
				hide(markersType);
			} 
		});	
		
		{/literal}{$listener_block}{literal}
		
});  // end of domready
 
function setMarkers(data, category)
{
	var baseIcon = new GIcon();
	
	baseIcon.image = "http://webmap-blog.ru/files/gmap/gicon/" + data.mimg;
    baseIcon.shadow = 'http://webmap-blog.ru/files/gmap/gicon/mm_20_shadow.png';
    baseIcon.iconSize = new GSize(12, 20);
    baseIcon.shadowSize = new GSize(22, 20);
    baseIcon.iconAnchor = new GPoint(6, 20);
    baseIcon.infoWindowAnchor = new GPoint(5, 1);
 
	var marker_point = new Array();
    var html = new Array();
 
	for(var i = 0; i < data.markers.length; i++)
	{
    	marker_point[i] = new GLatLng(data.markers[i].lat, data.markers[i].lon);	
		
		html[i] = '<strong>' +
				   data.markers[i].mname +
				   '</strong><br />' + 
				   data.markers[i].address;
				   
		map.addOverlay(createMarker(marker_point[i], html[i], baseIcon, category));
	} // end of for
	
} // end of function 
 
function createMarker(point, html, icon, category) {

	var marker = new GMarker(point, icon);
	marker.mycategory = category; 
	
	GEvent.addListener(marker, "click", function() {   
		map.openInfoWindowHtml(point, html);
	});
	
	gmarkers.push(marker);
	return marker;	
} // end of function
 
function show(category) {
        for (var i=0; i<gmarkers.length; i++) {
          if (gmarkers[i].mycategory == category) {
            gmarkers[i].show();
          }
        }
        // == check the checkbox ==
        document.getElementById(category+"box").checked = true;
      }
 
      // == hides all markers of a particular category, and ensures the checkbox is cleared ==
      function hide(category) {
        for (var i=0; i<gmarkers.length; i++) {
          if (gmarkers[i].mycategory == category) {
            gmarkers[i].hide();
          }
        }
        // == clear the checkbox ==
        document.getElementById(category+"box").checked = false;
        // == close the info window, in case its open on a marker that we just hid
        map.closeInfoWindow();
      }
 
    function saveData() {
      var name = escape(document.getElementById("name").value);
      var address = escape(document.getElementById("address").value);
      var type = document.getElementById("type").value;
      var latlng = marker.getLatLng();
      var lat = latlng.lat();
      var lng = latlng.lng();
 
      var url = "phpsqlinfo_addrow.php?name=" + name + "&address=" + address +
                "&type=" + type + "&lat=" + lat + "&lng=" + lng;
      GDownloadUrl(url, function(data, responseCode) {
        if (responseCode == 200 && data.length <= 1) {
          marker.closeInfoWindow();
		  document.getElementById("message").innerHTML = "Данные добавлены.";
        }
      });
    } // end of function 
</script>
{/literal}

<div id="map_canvas" style="float:left; width:auto;"></div>

<div id="list_of_types" style="float:right; width:220px;"> 
	<ul id="markerTypes" style="padding:0px;margin:0px"> 
		{$types}
	</ul>
</div>

<div id="message"></div>
 
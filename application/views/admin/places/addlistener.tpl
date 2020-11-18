{literal}
		GEvent.addListener(map, "click", function(overlay, latlng) {
			if (latlng) {
				marker = new GMarker(latlng, {draggable:true});
				GEvent.addListener(marker, "click", function() {
					var html = "<table>" +
                         "<tr><td>Наименование:</td> <td><input type='text' id='name'/> </td> </tr>" +
                         "<tr><td>Адрес:</td> <td><input type='text' id='address'/></td> </tr>" +
                         "<tr><td>Тип:</td> <td><select id='type'>" +
                         "<option value='bar' SELECTED>Бар</option>" +
                         "<option value='restaurant'>Ресторан</option>" +
                         "<option value='cafe'>Кафе</option>" +
                         "</select> </td></tr>" +
                         "<tr><td></td><td><input type='button' value='Сохранить' onclick='saveData()'/></td></tr></form>";
 
					marker.openInfoWindow(html);
				});
            map.addOverlay(marker);
		}
	});
{/literal}
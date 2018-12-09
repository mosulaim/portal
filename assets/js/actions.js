// init Ext libs
Ext.Loader.setConfig({
	enabled: true
});
Ext.Loader.setPath('Ext.ux', '../../ext4/examples/ux');

Ext.require([
	'Ext.data.*',
	'Ext.grid.*',
	'Ext.ux.grid.TransformGrid'
]);

var nearby_dockstn;
var distr_stats;

// Tool: Find docking stations within radius of given position.
function findNearby() {

	cleanMap();
	
	// Retrieve (x,y,r) from interface.
	var xpos = document.getElementById("xcoord").value;
	var ypos = document.getElementById("ycoord").value;
	var radius = document.getElementById("radius").value;

	// Prepare AJAX request endpoint link.
	var endpoint = perlurl + "getNearby.pl?x=" + xpos + "&y=" + ypos + "&r=" + radius;

	// Define callback function.
	/*xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
	        	alert(xmlhttp.responseText);
	}

	// Send AJAX request.
	xmlhttp.open("GET", endpoint, true);
	xmlhttp.send();*/

	// Create GML results.
	nearby_dockstn = new OpenLayers.Layer.Vector("Nearby Docking Stations", {
                protocol: new OpenLayers.Protocol.HTTP({
                    url: endpoint,
                    format: new OpenLayers.Format.GML()
                }),
                strategies: [new OpenLayers.Strategy.Fixed()],
		styleMap: new OpenLayers.StyleMap()
        });

	// Style results.
	nearby_dockstn.styleMap.styles["default"] = sld.namedLayers["DockStnNearby"].userStyles[0];

	// Add all layers to the map.
	map.addLayer(nearby_dockstn);

	toggleSelectResults();


}

// Tool: Find closest docking station to given position.
function findClosest() {

	cleanMap();

	// Retrieve (x,y,n) from interface.
	var xpos = document.getElementById("xcoord").value;
	var ypos = document.getElementById("ycoord").value;
	var n = document.getElementById("closest_no").value;

	// Prepare AJAX request endpoint link.
	var endpoint = perlurl + "getClosest.pl?x=" + xpos + "&y=" + ypos + "&n=" + n;

	// Create GML results.
	nearby_dockstn = new OpenLayers.Layer.Vector("Nearby Docking Stations", {
                protocol: new OpenLayers.Protocol.HTTP({
                    url: endpoint,
                    format: new OpenLayers.Format.GML()
                }),
                strategies: [new OpenLayers.Strategy.Fixed()],
		styleMap: new OpenLayers.StyleMap()
        });

	// Style results.
	nearby_dockstn.styleMap.styles["default"] = sld.namedLayers["DockStnNearby"].userStyles[0];

	// Add all layers to the map.
	map.addLayer(nearby_dockstn);

	toggleSelectResults();


}

// Tool: Find stations within given district.
function findStationDistrict() {

	// Retrieve pcid from selected polygon.
	var pcid = selectedFeature.attributes.gid;

	cleanMap();
		
	// Prepare AJAX request endpoint link.
	var endpoint = perlurl + "getStationsDistrict.pl?pcid=" + pcid;

	// Create GML results.
	nearby_dockstn = new OpenLayers.Layer.Vector("Nearby Docking Stations", {
                protocol: new OpenLayers.Protocol.HTTP({
                    url: endpoint,
                    format: new OpenLayers.Format.GML()
                }),
                strategies: [new OpenLayers.Strategy.Fixed()],
		styleMap: new OpenLayers.StyleMap()
        });

	// Style results.
	nearby_dockstn.styleMap.styles["default"] = sld.namedLayers["DockStnNearby"].userStyles[0];

	// Add all layers to the map.
	map.addLayer(nearby_dockstn);
	
	toggleSelectResults();

}

// toggle district selection by mouse.
function enableDistrictSelect() {

	cleanMap();	
	selectControl.activate();

}
// toggle district selection by mouse.
function enableIncSelect() {

	cleanMap();	
	selIncControl.activate();

}


function showChart() {

Ext.get("chartwin").setVisible(!Ext.get("chartwin").isVisible());

	cleanMap();	
	voteControl.activate();

}
// clean previous result layers from map
function cleanMap() {

	// Disable position selection by mouse click.
	disablePositionSelect();

	// Remove existing nearby stations layer.
	if (map.getLayersByName("Nearby Docking Stations")[0] != null)
		map.removeLayer(map.getLayersByName("Nearby Docking Stations")[0]);
		
	// Reset select controls.
	if (selectControl !=null) {
		selectControl.unselectAll();
		selectControl.deactivate();
	}
  	if (selIncControl !=null) {
		selIncControl.unselectAll();
		selIncControl.deactivate();
	}
  	if (voteControl !=null) {
		voteControl.unselectAll();
		voteControl.deactivate();
	}                 
	if (selectDockControl != null) {
		selectDockControl.unselectAll();
		selectDockControl.deactivate();
	}

	if (distr_stats != null)
		map.removeLayer(distr_stats);
}
// clean previous result layers from map
function cleanforload() {
		
	// Reset select controls.
	if (selectControl !=null) {
		selectControl.unselectAll();
	}
  	if (selIncControl !=null) {
		selIncControl.unselectAll();
	}
  	if (voteControl !=null) {
		voteControl.unselectAll();
	}                 
	if (selectDockControl != null) {
		selectDockControl.unselectAll();
	}

}

function toggleSelectResults() {

	// Remove previous.
	map.removeControl(selectDockControl);

	// Select docking stations.
	selectDockControl = new OpenLayers.Control.SelectFeature(nearby_dockstn, {onSelect: onDockSelect, onUnselect: onDockUnselect});

	map.addControl(selectDockControl);
	selectDockControl.activate();

}
  function partysymbol() {
  if (pstyle==1) {
	nigstates.styleMap.styles["default"] = sld.namedLayers["Districts"].userStyles[0];
   nigstates.redraw();
   pstyle = 0;
  } else {
    nigstates.styleMap.styles["default"] = sld.namedLayers["StateElect"].userStyles[0];
    nigstates.redraw();
    pstyle = 1;
  }
}

   function statereload() {
   cleanforload();
     mapreload(nigstates); 
   }  
   function increload() {
    cleanforload();
     mapreload(electevent);
     if (document.getElementById("incauto").checked)
       setTimeout(function() {increload()}, 5000);
   }  
     
   function mapreload(layer) {
   layer.loaded = false;
   //nigstates.setVisibility(false);
   layer.setVisibility(true);
   //nigstates.redraw();
   layer.refresh({ force: true, params: { 'key': Math.random()} });
   }
                 //function called// //timer// //layer to refresh//
///window.setInterval(mapreload, 5000, electevent);



function onDockSelect(feature) {

	//selectedDock = feature;
	if (feature.attributes.availability == null)
		feature.attributes.availability = feature.attributes.capacity;
	popup = new OpenLayers.Popup.FramedCloud("chicken", 
			     feature.geometry.getBounds().getCenterLonLat(),
			     null,
			     "<div style='font-size:1.2em'>ID: " + feature.attributes.did +"<br>Post Code: " + feature.attributes.pcid + "<br>Capacity: " + feature.attributes.capacity + "<br>Availability: " + feature.attributes.availability + "</div>",
			     null, true, onPopupClose);
	feature.popup = popup;
	map.addPopup(popup);

}

function onDockUnselect(feature) {

	map.removePopup(feature.popup);
	feature.popup.destroy();
	feature.popup = null;

}

function getReport() {
  
	var endpoint;
	var handler = "http://geostation.herokuapp.com/assets/inctable.php";

 	if (document.getElementById("vote_list").checked) 
 		handler = "http://geostation.herokuapp.com/assets/anytable.php?geotable=statevote";
 	if (document.getElementById("leading_p").checked) 
 		handler = "http://geostation.herokuapp.com/assets/anytable.php?geotable=stateelect&fields=gid,state,party";
// 	if (document.getElementById("tot_stat_district").checked) 
// 		handler = "dstnperdistr.pl";

	endpoint =  handler;

	// AJAX request
	var xmlhttp;
	if (window.XMLHttpRequest)
		xmlhttp=new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
	else
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
	
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("reportcontainer-body").innerHTML = xmlhttp.responseText;
			Ext.getCmp("reportcontainer").expand();
		}
	}

	xmlhttp.open("GET",endpoint,true);
	xmlhttp.send();

}
 
function stateChart(feature) {
  
  selectedFeature = feature;

	var handler = "votesinlagos.php?state="+ feature.attributes.state;
  if (document.getElementById("allparties").checked) 
		handler = "votesinlagos.php?all=1&state="+ feature.attributes.state;
	if (document.getElementById("leadparties").checked) 
		handler = "votesinlagos.php?state="+ feature.attributes.state + "&np=" + document.getElementById("np").value;

	// AJAX request
	var xmlhttp;
	if (window.XMLHttpRequest)
		xmlhttp=new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
	else
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
	
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			var myData = Ext.JSON.decode(xmlhttp.responseText);
			store1.loadRawData(myData);
		}
	}

	xmlhttp.open("GET",handler,true);
	xmlhttp.send();

}

function showInc(pcid) {

	cleanMap();

	// Create GML layers.
	distr_stats = new OpenLayers.Layer.Vector("Selected District", {
                protocol: new OpenLayers.Protocol.HTTP({
                    url: "http://xweb.geos.ed.ac.uk/~s1157724/cgi_bin/asdm/selectdistr.pl?pcid=" + pcid,
                    format: new OpenLayers.Format.GML()
                }),
                strategies: [new OpenLayers.Strategy.Fixed()],
		styleMap: new OpenLayers.StyleMap()
        });

	map.addLayer(distr_stats);

}


var bingKey = "AqFzpgE-PsZTDcy1QeyA78I7lFRMNcXvNF-nbE1GqtbM9Y9vssfHSxDWimFcP3gn";
var format = new OpenLayers.Format.SLD(), sld;
var xcoord;
var ycoord;
var mouse_select = false;
var selectControl;
var interestControl;
var selectedFeauture;

map = new OpenLayers.Map('map');
	map.addControl(new OpenLayers.Control.LayerSwitcher());
  
	// Create Google basemap layers.
/* 	var gphy = new OpenLayers.Layer.Google(
		"Google Physical",
		{type: google.maps.MapTypeId.TERRAIN, visibility: false}
	);
	var ghyb = new OpenLayers.Layer.Google(
		"Google Satellite",
		{type: google.maps.MapTypeId.HYBRID, numZoomLevels: 22, visibility: true}
	);
  	var gmap = new OpenLayers.Layer.Google(
		"Google Streets", // the default
		{numZoomLevels: 20, visibility: false}
	);
 var bmap = new OpenLayers.Layer.Bing({
    key: bingKey,
    type: "Road",
    // custom metadata parameter to request the new map style - only useful
    // before May 1st, 2011
    metadataParams: {mapVersion: "v1"}
});

var bhybrid = new OpenLayers.Layer.Bing({
    key: bingKey,
    type: "AerialWithLabels",
    name: "Bing Aerial"
});                                         */
var esrimap = new OpenLayers.Layer.XYZ( "Terrain Base -Geostation.net",
                    "http://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/${z}/${y}/${x}",
                    {sphericalMercator: true} ); 
                    
  
  var plotsdata = './bld.json'   //use estateplots for kara               
	// Create JSON layers.
	estplots = new OpenLayers.Layer.Vector("Estate Plots", {
                protocol: new OpenLayers.Protocol.HTTP({
                    url: plotsdata,
                    format: new OpenLayers.Format.GeoJSON()
                }),
                strategies: [new OpenLayers.Strategy.Fixed()],
		styleMap: new OpenLayers.StyleMap()
        }); 

var vlayer = new OpenLayers.Layer.Vector( "Editable" ); 
 var editpanel = new OpenLayers.Control.EditingToolbar(vlayer);  
 zb = new OpenLayers.Control.ZoomBox({title:"Zoom Rectangle: Select, click and drag to zoom."});
  ze = new OpenLayers.Control.ZoomToMaxExtent({title:"Zoom to the max extent"}) 
  
   nav = new OpenLayers.Control.NavigationHistory();
            // parent control must be added to the map
            map.addControl(nav);
            //editpanel.addControls([zb, ze, nav.next, nav.previous]);
            
           // map.addControl (editpanel); 
            
  	// Load SLD.
	OpenLayers.Request.GET({
		url: "http://geostation.herokuapp.com/taxform/sld/sld.xml",
		success: sld_complete
	});  
  
  	// Add all layers to the map.
	map.addLayers([esrimap, estplots]);

  //esrimap.setVisibility(true);
   esrimap.setVisibility(true);
  
 
	// Google.v3 uses EPSG:900913 as projection, so we have to
	// transform our coordinates      // use 3.388, 6.833 for kara
	map.setCenter(new OpenLayers.LonLat(3.186, 6.478).transform(
	new OpenLayers.Projection("EPSG:4326"),
	map.getProjectionObject()
	), 17);
  
  	// Register mouse events.
	map.events.register('click', map, handleMapClick);

	// Select polygons.
	selectControl = new OpenLayers.Control.SelectFeature(estplots, {onSelect: onFeatureSelect, onUnselect: onFeatureUnselect});

	map.addControl(selectControl);  	
  selectControl.deactivate();
	//selectControl.deactivate();
  
  	// Register Interest COntrol
	interestControl = new OpenLayers.Control.SelectFeature(estplots, {onSelect: interestSelect, onUnselect: interestUnselect});

	map.addControl(interestControl);  	
  interestControl.activate();
  
            map.addControl(
                new OpenLayers.Control.MousePosition({
                    prefix: '<a target="_blank" ' +
                        'href="http://www.geostation.net">' +
                        'GeoStation</a> coordinates: ',
                    separator: ' | ',
                    numDigits: 2   //,    emptyString: 'Sulaiman GIS.'
                })
            );

function myreginterest() {

//	cleanMap();	

  	if (document.getElementById("regmessage").style.display == "none")
  	{	
     //document.getElementById("regmessage").style.display = "block";
    	interestControl.activate();
      alert('Select a vacant property you are interested in on the map and fill your details in the form that comes up');
    }  
   
	else {
		//document.getElementById("regmessage").style.display = "none";
    interestControl.deactivate();
  }
}

function interestSelect(feature) {
	selectedFeature = feature;
  document.getElementsByName("ppty_id")[0].value = feature.attributes.PPTY_ID;
  document.getElementsByName("bld_id")[0].value = feature.attributes.OBJECTID_1;

  var pptyid = feature.attributes.PPTY_ID;
  getbusiness(pptyid);

  document.getElementById("buildprop").style.display = "inline";

}

function interestUnselect(feature) {
	selectedFeature = null;
}  

function getbusiness(pptyid) {
  var endpoint;
  var handler;
  handler = 'table.php?geotable=business&fields=gid,businame,busiprod,lt_pd_year,lt_amt_pd&parameters="ppty_id"=\''  + pptyid + '\'';

  endpoint =  handler;

  // AJAX request
  var xmlhttp;
  if (window.XMLHttpRequest)
	  xmlhttp=new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
  else
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
  
  xmlhttp.onreadystatechange=function() {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		  document.getElementById("busitable").innerHTML = xmlhttp.responseText;
	  }
  }

  xmlhttp.open("GET",endpoint,true);
  xmlhttp.send();
}

function clearControls() {
	interestControl.deactivate();
	selectControl.deactivate();
	mouse_select = true;
	document.getElementById("map").style.display = "block";

}
function showform() {
	document.getElementById("mainform").style.display = "inline";
	document.getElementById("map").style.display = "none";

}
function showmap() {
	document.getElementById("mainform").style.display = "none";
	document.getElementById("map").style.display = "block";
	interestControl.activate();

}

function addnew() {
	showform(); 
	document.getElementById("action").value = "add";	
}

function mreturn() {
	document.getElementById("map").style.display = "block";	
	interestControl.activate();
}

function updatebusi(bgid){  
	var id = bgid;
//	$('#modal-title').html("Edit Employee");
	$.ajax({  
		 url:"update.php?gid="+id,  
		 method:"GET", 
		 dataType:"json",  
		 success:function(data){ 
		  console.log(data); 
			  $('#action').val('edit'); 
			  $('#gid').val(id); 
			  $('#businame').val(data.businame);    
			  $('#busitype').val(data.busitype);
			  $('#busiprod').val(data.busiprod);
			  $('#busireg_st').val(data.busireg_st);  
			 // $('#btn_add').html("Update");
			 // $('#action').val("edit");   
		 }  
	});
	showform();  
}
function submitform() {
	data = $("#busiform").serializeArray();
	$.ajax({
	  type: "POST",  
	  url: "response.php",  
	  data: data,
	  dataType: "json",       
	  success: function(response)  
	  {
		$('#submit#msg').html('');
		  if(response.status) {
			$('#submitmsg').html('<div class="alert alert-success">Successfully! added record</div>');
			getbusiness(document.getElementsByName("ppty_id")[0].value);
		  } else {
			  $('#submitmsg').html('<div class="alert alert-danger ">Error! inserting record</div>');	
		  }
		
	  },
	  error: function(jqXHR, textStatus, errorThrown) {
		  $('#submitmsg').html('<div class="alert alert-danger ">Error returned: '+textStatus+'!'+errorThrown);
		}  
	});
	showmap();
}
 function toggleMenu() {

	if (document.getElementById("mapmenu").style.visibility == "hidden")
		document.getElementById("mapmenu").style.visibility = "visible";
	else 
		document.getElementById("mapmenu").style.visibility = "hidden";
}
 
 function toggleLegend() {

	if (document.getElementById("mlegend").style.display == "none")
		document.getElementById("mlegend").style.display = "block";
	else 
		document.getElementById("mlegend").style.display = "none";
}

  function legendsOff() {
		document.getElementById("plotsLandUse").style.display = "none";
    document.getElementById("plotProfession").style.display = "none";
    document.getElementById("plotStatus").style.display = "none";
}
 // handler for the OpenLayers.Request.GET function in the init method
function sld_complete(req) {
	sld = format.read(req.responseXML || req.responseText);
        //alert(req.responseText);
  //electevent.styleMap.styles["default"] = sld.namedLayers["DockStn"].userStyles[0];
	estplots.styleMap.styles["default"] = sld.namedLayers["BLDLandUse"].userStyles[0];
   estplots.redraw();
	//districts.styleMap.styles.select = sld.namedLayers["Districts"].userStyles[1];

}  
function plotsymbol(mytheme) {
	estplots.styleMap.styles["default"] = sld.namedLayers[mytheme].userStyles[0];
   estplots.redraw();
   legendsOff();
   divReset();
   document.getElementById(mytheme).style.display = "block";
   document.getElementById("div" + mytheme).setAttribute("class", "dhighlight");
	//districts.styleMap.styles.select = sld.namedLayers["Districts"].userStyles[1];

}

function showroad() {

	if (document.getElementById("roads").style.display == "none")
		document.getElementById("roads").style.display = "block";     //formerly inline
	else 
		document.getElementById("roads").style.display = "none";
}

// toggle dselection by mouse.
function enableSelect() {

//	cleanMap();	
	selectControl.activate();
}

// Selection popups
function onPopupClose(evt) {
	selectControl.unselect(selectedFeature);
}

function onFeatureSelect(feature) {

	selectedFeature = feature;
	popup = new OpenLayers.Popup.FramedCloud("chicken", 
			     feature.geometry.getBounds().getCenterLonLat(),
			     null,
           "<div>Plot Number: " + feature.attributes.id +"<br>Status: "+ feature.attributes.status +"<br>Land Use: "+ feature.attributes.landuse +"<br>Profession: "+ feature.attributes.profession +"<br>Quoted Plot Size: " + feature.attributes.plotsize +"Sq. Mts.</div>",
			     null, true, onPopupClose);                                                                                                                                                                                                                     // used before for area: feature.geometry.getArea()
	feature.popup = popup;
	
	//document.getElementById("gid").value = feature.attributes.gid;
	map.addPopup(popup);
}

function onFeatureUnselect(feature) {
	selectedFeature = null;
	map.removePopup(feature.popup);
	feature.popup.destroy();
	feature.popup = null;
}    

// Map mouse event handling.
function handleMapClick(e) {
	if (mouse_select == true) {
		// Convert viewport coordinates to map coordinates and transform to WGS84.
		var lonlat = map.getLonLatFromViewPortPx(e.xy).transform(map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));
		var xpos = lonlat.lon;
		var ypos = lonlat.lat;

	document.getElementsByName("x")[0].value = xpos;
	document.getElementsByName("y")[0].value = ypos;	
    var xcoord = xpos;
	var ycoord = ypos;
	document.getElementById("map").style.display = "none";
	mouse_select = false;
	}
	
} 

function divReset() {
document.getElementById("divplotsLandUse").setAttribute("class", "mapmenu_wrapper");
document.getElementById("divplotProfession").setAttribute("class", "mapmenu_wrapper");
document.getElementById("divplotStatus").setAttribute("class", "mapmenu_wrapper");
}
 document.getElementById("divplotsLandUse").setAttribute("class", "dhighlight");
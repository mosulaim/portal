var map;
var nigstates, electevent;
var format = new OpenLayers.Format.SLD(), sld, pstyle;
var mouse_select = false;
var selectControl, select_district_active = false;
var selectedFeauture;
var selectDockControl;
var selectedDock;

var statedata = 'nigeria.geojson';
var incdata ='pdata.php?geotable=electionevent&key=' + Math.random();

var mapPanel, legendPanel;

Ext.require([
    'GeoExt.panel.Map', 
    'GeoExt.container.WmsLegend',
    'GeoExt.container.UrlLegend',
    'GeoExt.container.VectorLegend',
    'GeoExt.panel.Legend'
]);

function initMap() {
	map = new OpenLayers.Map('mapcontainer-body', {allOverlays: true});
	map.addControl(new OpenLayers.Control.LayerSwitcher());


	// Create Google basemap layers.
	var gmap = new OpenLayers.Layer.Google(
		"Google Streets", // the default
		{numZoomLevels: 20, visibility: false}
	);
	var gsat = new OpenLayers.Layer.Google(
		"Google Satellite",
		{type: google.maps.MapTypeId.SATELLITE, numZoomLevels: 22}
	);
	var gphy = new OpenLayers.Layer.Google(
		"Google Physical",
		{type: google.maps.MapTypeId.TERRAIN, visibility: false}
	);
	var ghyb = new OpenLayers.Layer.Google(
		"Google Hybrid",
		{type: google.maps.MapTypeId.HYBRID, numZoomLevels: 22, visibility: false}
	);

	// Create GML layers.
	nigstates = new OpenLayers.Layer.Vector("Nigeria States", {
                protocol: new OpenLayers.Protocol.HTTP({
                    url: statedata,
                    format: new OpenLayers.Format.GeoJSON()
                }),
                strategies: [new OpenLayers.Strategy.Fixed()],
		styleMap: new OpenLayers.StyleMap()
        });

	electevent = new OpenLayers.Layer.Vector("Election Events", {
                protocol: new OpenLayers.Protocol.HTTP({
                    url: incdata,
                    format: new OpenLayers.Format.GeoJSON()
                }),
                strategies: [new OpenLayers.Strategy.Fixed()],
		styleMap: new OpenLayers.StyleMap()
        });
        
  	// Load SLD.
	OpenLayers.Request.GET({
		url: "http://portal-geomos.1d35.starter-us-east-1.openshiftapps.com/polity/sld/sld.xml",
		success: sld_complete
	});  
  
	// Add all layers to the map.
	map.addLayers([gmap, gsat, gphy, ghyb, nigstates, electevent]);

	gmap.setVisibility(true);
	gsat.setVisibility(false);

	// Google.v3 uses EPSG:900913 as projection, so we have to
	// transform our coordinates
	map.setCenter(new OpenLayers.LonLat(6.9885, 7.9513).transform(
	new OpenLayers.Projection("EPSG:4326"),
	map.getProjectionObject()
	), 6);



	// Register mouse events.
	map.events.register('click', map, handleMapClick);

	// Select polygons.
	selectControl = new OpenLayers.Control.SelectFeature(nigstates, {onSelect: onFeatureSelect, onUnselect: onFeatureUnselect});

	map.addControl(selectControl);
	selectControl.deactivate();

  	// Select polygons.
	voteControl = new OpenLayers.Control.SelectFeature(nigstates, {onSelect: stateChart, onUnselect: voteUnselect});

	map.addControl(voteControl);
	voteControl.deactivate();
  
  	// Select Incident.
	selIncControl = new OpenLayers.Control.SelectFeature(electevent, {onSelect: onIncSelect, onUnselect: onIncUnselect});

	map.addControl(selIncControl);
	selIncControl.deactivate();
  
   mapPanel = Ext.create('GeoExt.panel.Map', {
            region: 'center',
            height: 600,
            width: '100%',
            map: map,
        });
  legendPanel = Ext.create('GeoExt.panel.Legend', {
            defaults: {
                labelCls: 'mylabel',
                style: 'padding:5px'
            },
            
            bodyStyle: 'padding:5px',
            width: '100%',
            height: 400,
            renderTo: 'mlegend',
            autoScroll: true,
            region: 'west'
        });
  /*       Ext.create('Ext.panel.Panel', {
            layout: 'border',
            renderTo: 'mapcontainer-body',
            width: '100%',
            height: '100%',
            items: [mapPanel, legendPanel]
        });                                     */
        
}

// Selection popups
function onPopupClose(evt) {
	selectControl.unselect(selectedFeature);
	selectDockControl.unselect(selectedDock);
}

function onFeatureSelect(feature) {

	selectedFeature = feature;
	popup = new OpenLayers.Popup.FramedCloud("chicken", 
			     feature.geometry.getBounds().getCenterLonLat(),
			     null,
			     "<div style='font-size:.8em'>Feature: " + feature.attributes.gid +"<br>State: "+ feature.attributes.state +"<br>Area: " + feature.geometry.getArea()+"</div>",
			     null, true, onPopupClose);
	feature.popup = popup;
	
	document.getElementById("gid").value = feature.attributes.gid;
	map.addPopup(popup);
}

function onFeatureUnselect(feature) {
	selectedFeature = null;
	map.removePopup(feature.popup);
	feature.popup.destroy();
	feature.popup = null;
}    

function onIncSelect(feature) {

	//selectedFeature = feature;
	popup = new OpenLayers.Popup.FramedCloud("chicken", 
			     feature.geometry.getBounds().getCenterLonLat(),
			     null,
			     "<div style='font-size:.8em'>Feature: " + feature.attributes.gid +"<br>Name: "+ feature.attributes.name +"<br>Description: " + feature.attributes.description+'<br /><img src="'+feature.attributes.fileURL+'" alt="'+feature.attributes.fileURL+'"style="height: 300px"/></div>',
			     null, true, onPopupClose);
	feature.popup = popup;

	map.addPopup(popup);
}

function onIncUnselect(feature) {
	//selectedFeature = null;
	map.removePopup(feature.popup);
	feature.popup.destroy();
	feature.popup = null;
}   

function voteUnselect(feature) {
//	selectedFeature = null;
}   

// handler for the OpenLayers.Request.GET function in the init method
function sld_complete(req) {
	sld = format.read(req.responseXML || req.responseText);
        //alert(req.responseText);
  electevent.styleMap.styles["default"] = sld.namedLayers["DockStn"].userStyles[0];
	nigstates.styleMap.styles["default"] = sld.namedLayers["Districts"].userStyles[0];
   nigstates.redraw();
	//districts.styleMap.styles.select = sld.namedLayers["Districts"].userStyles[1];

}  
// Toggle select coordinates by mouse click
function togglePositionSelect() {

	if (mouse_select)
		disablePositionSelect();
	else 
		enablePositionSelect();
}

// enable position selection by mouse.
function enablePositionSelect() {

	mouse_select = true;
	select_district_active = false;
	if (selectControl !=null)
		selectControl.deactivate();
	if (selectDockControl != null)
		selectDockControl.deactivate();

}

// disable position selection by mouse.
function disablePositionSelect() {

	mouse_select = false;

}

// Map mouse event handling.
function handleMapClick(e)
{
	
	if (mouse_select == true) {
		// Convert viewport coordinates to map coordinates and transform to WGS84.
		var lonlat = map.getLonLatFromViewPortPx(e.xy).transform(map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));
		var xpos = lonlat.lon;
		var ypos = lonlat.lat;

		document.getElementById("xcoord").value = xpos;
		document.getElementById("ycoord").value = ypos;	
	}
	
} 

function setLayerStyles() {
    // set the default style for each layer from sld
    for (var l in sld.namedLayers) {
	alert(l);
        var styles = sld.namedLayers[l].userStyles, style;
        for (var i=0,ii=styles.length; i<ii; ++i) {
            style = styles[i];
            if (style.isDefault) {
                //map.getLayersByName(l)[0].styleMap.styles["default"] = style;
                break;
            }
        }
    }
    // select style for mouseover on WaterBodies objects
    //waterBodies.styleMap.styles.select = sld.namedLayers["WaterBodies"].userStyles[1];
}

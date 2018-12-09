/*

This file is part of Ext JS 4

Copyright (c) 2011 Sencha Inc

Contact:  http://www.sencha.com/contact

GNU General Public License Usage
This file may be used under the terms of the GNU General Public License version 3.0 as published by the Free Software Foundation and appearing in the file LICENSE included in the packaging of this file.  Please review the following information to ensure the GNU General Public License version 3.0 requirements will be met: http://www.gnu.org/copyleft/gpl.html.

If you are unsure which license is appropriate for your use, please contact the sales department at http://www.sencha.com/contact.

*/
var map;

Ext.require(['*']);
Ext.onReady(function() {
    var cw;
             
    Ext.create('Ext.Viewport', {
        layout: {
            type: 'border',
            padding: 5
        },
        defaults: {
            split: true
        },
        items: [/*{
            region: 'north',
            //collapsible: true,
            //title: 'North',
            split: true,
            minHeight: '10%',
            maxHeight: 100,
            height: '15%',
            html: '<img src="http://localhost:8080/asdm/sulgis.jpg" alt="Nigeria Map" style="height: 90px"/>'
        }   */
        {
                id: 'app-header',
                xtype: 'box',
                region: 'north',
                height: 60,
                maxHeight: 60,
                html: '<img src="./img/geostation.jpg" alt="GeoStation" style="position: absolute; top: 0; right: 5px; border: 0; height: 60px"/> Enumeration Dashboard'
            },{
            region: 'west',
            collapsible: true,
           //collapsed: true,
            title: 'Tools',
            split: true,
            width: '25%',
            minWidth: 100,
            minHeight: 140,
            layout: 'accordion',
	    items:[{
			id: 'tool_nearby',
			title: 'My Position / Identify Assets / Symbology',
      autoScroll: true,
			layout: 'fit'
		},{
			id: 'tool_bydistrict',
			title: 'Assets or Customers by States',
      layout: 'fit'
		},{
			id: 'map_legend',
			title: 'Map Legend',
      layout: 'fit'
		},{
			id: 'tool_reports',
			title: 'Reports',
      layout: 'fit'
		}]
        },{
            region: 'center',
            layout: 'fit',
            border: false,
	    id: 'mapcontainer'
	    //html: '<iframe id="mapfr" style="border: none; width: 100%; height: 100%" src="http://localhost:8080/geoxplorer/viewer/#maps/1"></iframe>'
        },{
            region: 'south',
	    id: 'reportcontainer',
	    layout: 'fit',
            collapsible: true,
	    collapsed: true,
            split: true,
            height: 200,
            minHeight: 120,
            title: 'Report Results'
        }]
    });
});


Ext.onReady(function() {

	// Append tools to accordeon.
	document.getElementById('tool_nearby-body').appendChild(document.getElementById('nearbyStationsForm'));
	document.getElementById('tool_bydistrict-body').appendChild(document.getElementById('statByDistrictForm'));
	document.getElementById('tool_reports-body').appendChild(document.getElementById('reportForm'));
  
  document.getElementById('map_legend-body').appendChild(document.getElementById('mlegend'));
  
	// Initialize map.
	initMap();
  drawchart();

});


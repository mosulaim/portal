Ext.require('Ext.chart.*');
Ext.require(['Ext.Window', 'Ext.layout.container.Fit', 'Ext.fx.target.Sprite', 'Ext.window.MessageBox']);
Ext.require(['Ext.data.*']);

function drawchart() {

Ext.regModel('Details', { fields: [{ name: 'name' }, { name: 'data1'}] });

window.store1 = new Ext.data.Store({
    model: 'Details',
    proxy: {
        type: 'ajax',
        url: 'http://geostation.herokuapp.com/polity/votesinlagos.php',
        reader: {
            root: 'data',
            type: 'json'
        }
    },
    autoLoad: true
});

    var chart = Ext.create('Ext.chart.Chart', {
            style: 'background:#fff',
            animate: true,
            shadow: true,
            store: store1,
            axes: [{
                type: 'Numeric',
                position: 'left',
                fields: ['data1'],
                label: {
                    renderer: Ext.util.Format.numberRenderer('0,0')
                },
                title: 'Number of Votes',
                grid: true,
                minimum: 0
            }, {
                type: 'Category',
                position: 'bottom',
                fields: ['name'],
                title: 'Political Party'
            }],
            series: [{
                type: 'column',
                axis: 'left',
                highlight: true,
                tips: {
                  trackMouse: true,
                  width: 140,
                  height: 28,
                  renderer: function(storeItem, item) {
                    this.setTitle(storeItem.get('name') + ': ' + storeItem.get('data1') + ' Votes');
                  }
                },
                label: {
                  display: 'insideEnd',
                  'text-anchor': 'middle',
                    field: 'data1',
                    renderer: Ext.util.Format.numberRenderer('0'),
                    orientation: 'vertical',
                    color: '#333'
                },
                xField: 'name',
                yField: 'data1'
            }]
        });


    var win = Ext.create('Ext.window.Window', {
        width: 500,
        height: 400,
        minHeight: 300,
        minWidth: 300,
        hidden: false,
        maximizable: false,
        constrainHeader: true,
        closable: false,
        minimizable: true,
        minimize: function() {
             document.getElementById("showChart").click();
                },
        id: 'chartwin',
        title: 'Chart showing Hypothetical Vote Count per Party in Lagos',
        autoShow: true,
        layout: 'fit',
        tbar: [{
            text: 'Save Chart',
            handler: function() {
                Ext.MessageBox.confirm('Confirm Download', 'Would you like to download the chart as an image?', function(choice){
                    if(choice == 'yes'){
                        chart.save({
                            type: 'image/png'
                        });
                    }
                });
            }
        }
        ],
        items: chart    
    });
    win.alignTo("mapcontainer", "br-br", [-10, -10]);
};

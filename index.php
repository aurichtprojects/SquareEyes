<?php
    session_start();

    $display_error_login = false;
    $logged_in_this_page = false;

    require_once('ws/inc/error.inc.php');
    require_once('ws/inc/database.inc.php');

    try {
        // Checking if we already have logged in
        if (!isset($_SESSION['logged-in']) || $_SESSION['logged-in'] !== true) {
            if (isset($_POST['login_username']) && isset($_POST['login_password']))
            {
                // Establishing connection to database to check that the username and password submitted are valid
                $pgconn = pgConnection();
                $sql = "select 1 from \"user\" where name='".$_POST['login_username']."' and password='".$_POST['login_password']."'";
                //echo $sql;
                $recordSet = $pgconn->prepare($sql);
                $recordSet->execute();

                $_SESSION['logged-in']=false;
                while ($row  = $recordSet->fetch())
                {
                    $_SESSION['logged-in']=true;
                }

                if ($_SESSION['logged-in'])
                {
                    // Heading to the same page
                    header('Location: index.php');
                    exit;
                }
                else
                {
                    // Do something to the form so that it shows the same login window, but an error message 
                    $display_error_login = true;
                }

            }
            else
            {
                // The login icon will open the login form
            }
        }
        else
        {
            // We are already logged in, the login icon will logout
            $logged_in_this_page = true;
        }

    }
    catch (Exception $e) {
        trigger_error("Caught Exception: " . $e->getMessage(), E_USER_ERROR);
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>SquareEyes - NZ Weed Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Styles -->
    <link rel="stylesheet" href="openlayers/theme/default/style.css" type="text/css">
    <link rel="stylesheet" href="ol-style.css" type="text/css">
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap-fileupload.min.css" rel="stylesheet">
    <link href="select2/select2.css" rel="stylesheet"/>
    <style type="text/css">

        body {
            padding: 10px;
        }

        .container-fluid {
            padding: 0;
        }

        /* CSS for a fluid-fixed layout based on http://jsfiddle.net/andresilich/6vPqA/13/ */

        .well {
            padding:9px;
            margin-bottom:0;
        }

        .scrollable {
            overflow: auto;
        }

        .fluid-fixed {
            margin-right: 310px;
            margin-left:auto !important;
            display: block;
        }

        .row-fluid > .sidebar-nav {
            position: relative;
            top: 0;
            left:auto;
            width: 280px;
        }

        .right {
            display: block;
            float:right;
        }

        .left {
            display: block;
            float:left;
        }

        .singleLineTools {
            display: inline-block;
            vertical-align:top;
        }

        #extraTools {
          margin-top:10px;
          text-align:center;
        }

        .pointer {
            cursor: pointer;
        }

        #myHistoryModal.modal {
            bottom: 10px;
            top:auto !important;
            left: 290px;
            width: 850px;
        }

        #myLoginModal.modal {
            width: 300px;
        }

        .modal-header {
            padding: 0;
            border-bottom:none !important;
        }

        .modal-header .close {
            margin-right: 5px;
        }

        .modal-body {
            padding: 5px;
        }

        .table {
            margin-bottom: 0;
        }

        .row {
            margin-left: 20px;
        }

        #extraTools div {
          display: inline-block;
          margin: 5px;
          background-repeat: no-repeat;
          border: 1px;
        }

        #extraLayers {
          margin:10px 0 5px;
          text-align:left;
        }

        #extraInfo {
          text-align:left;
        }

        #formOutput {
            font-size:12px;
        }

        .labelSpan {
            display: inline;
        }

        label {
            margin: 5px;
        }

        legend {
            margin-bottom: 0;
        }

        input[type="radio"], input[type="checkbox"] {
            margin: 0;
        }

        .unselectBtnItemInactive {
          width:  24px;
          height: 22px;
          background-position: -312px 0;
          background-image: url("bootstrap/img/glyphicons-halflings-white.png");
        }

        .unselectBtnItemActive {
          width:  24px;
          height: 22px;
          background-position: -312px 0;
          background-image: url("bootstrap/img/glyphicons-halflings.png");
        }

        .selectByPolyItemActive {
          width:  24px;
          height: 22px;
          background-position: -240px 0;
          background-image: url("bootstrap/img/glyphicons-halflings-white.png");
        }

        .selectByPolyItemInactive {
          width:  24px;
          height: 22px;
          background-position: -240px 0;
          background-image: url("bootstrap/img/glyphicons-halflings.png");
        }

        .downloadItemActive {
          width:  24px;
          height: 22px;
          background-position: -96px -24px;
          background-image: url("bootstrap/img/glyphicons-halflings-white.png");
        }

        .downloadItemInactive {
          width:  24px;
          height: 22px;
          background-position: -96px -24px;
          background-image: url("bootstrap/img/glyphicons-halflings.png");
        }

        #loginDiv {

        }

    </style>
    <!--<link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">-->
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

  </head>

  <body>
    <?php
        $login_add_class = "hide";
        if ($display_error_login){
            $login_add_class = "";
        }
        echo "<div id=\"myLoginModal\" class=\"modal ".$login_add_class."\">";
    ?>
        <div class="modal-header">
            <button id="closeLoginModal" type="button" class="close">&times;</button>
        </div>
        <div class="modal-body">
            <div class="content">
              <div class="row">
                <div class="login-form">
                  <h2>Login</h2>
                <?php
                    $login_add_class = "hide";
                    if ($display_error_login){
                        $login_add_class = "";
                    }
                    echo "<div class=\"alert alert-error ".$login_add_class."\">Incorrect username or password</div>";
                ?>
                  <form id="login_form" action="<?php $PHP_SELF; ?>" method="post">
                    <fieldset>
                      <div class="clearfix">
                        <input type="text" name="login_username" placeholder="Username" style="height:15px;"/>
                      </div>
                      <div class="clearfix">
                        <input type="password" name="login_password" placeholder="Password" style="height:15px;"/>
                      </div>
                      <button id="login-submit" class="btn btn-primary" style="float: right;">Sign in</button>
                    </fieldset>
                  </form>
                </div>
              </div>
            </div>
        </div>
    </div> <!-- /container -->

    <div class="container-fluid">
        <div class="row-fluid">
            <!-- the sidebar needs to be defined before the map fluid content! -->
            <div id="sidebar" class="well right sidebar-nav scrollable">
                <!-- Drop down -->
                <input type="hidden" id="e1" style="width:265px"/>
                <!-- Controls and additional layers -->
                <div id="baseTools">
                    <?php
                        $icon_class = "icon-user";
                        if ($logged_in_this_page){
                            $icon_class .= " icon-white";
                        };
                        echo "<i id=\"loginTool\" class=\"".$icon_class." singleLineTools pointer\" style=\"margin:15px 65px 15px 5px;\"></i>";
                    ?>
                    <div id="extraTools" class="hide pointer"></div>
                    <div id="currentCell" class="singleLineTools" style="margin-top:12px;margin-left:30px;"></div>
                </div>
                <!-- Message / info -->
                <div id="extraLayers" class="hide"></div>
                <div id="extraLegend" class="hide" style="margin: 0 0 15px;">
                    <img id="legendImg"/>
                </div>
                <!-- Action / form -->
                <div id="extraActions" class="hide">
                    <form id="mod_form" action="ws/ws_create_observation.php" method="POST">
                        <div id="extraInfo" class="hide"></div>
                        <!-- Message / info -->
                        <legend>Report / Moderate</legend>
                        <label class="radio">
                            <input type="radio" name="field_options_radios" id="optionsRadios1" value="1">Reject
                        </label>
                        <label class="radio">
                            <input type="radio" name="field_options_radios" id="optionsRadios2" value="2">Report presence
                        </label>
                        <label class="radio">
                            <input type="radio" name="field_options_radios" id="optionsRadios3" value="3">Approve
                        </label>
                        <input type="text" name="field_email_address" placeholder="Your email address">
                        <textarea rows="2" name="field_comment" placeholder="Your comments"></textarea>
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 100px; height: 100px;">
                                <img src="http://www.placehold.it/100x100/EFEFEF/AAAAAA&text=no+image" />
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail" style="width: 100px; height: 100px;"></div>
                            <span class="btn btn-file">
                                <span class="fileupload-new">Attach photo</span>
                                <span class="fileupload-exists">Change photo</span>
                                <input name="field_file" type="file" />
                            </span>
                            <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove photo</a>
                        </div>
                        <input type="hidden" name="field_asset" value="">
                        <input type="hidden" name="field_selected_cells" value="">
                        <button type="button" id="save" class="btn btn-primary right">Save</button>
                        <button type="button" id="cancel" class="btn btn-danger">Cancel</button>
                    </form>
                </div>
                <div id="formOutput" class="well hide"></div>
            </div>
            <div class="well fluid-fixed">
                <!--Body content-->
                <div id="map" class="smallmap"></div>
                <div id="myHistoryModal" class="modal hide">
                    <div class="modal-header">
                        <button id="closeModal" type="button" class="close">&times;</button>
                    </div>
                    <div class="modal-body">
                       <p id="historyDiv" style="margin: 0;"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="openlayers/OpenLayers.js"></script>
    <!-- Letting Google host and serve jQuery for us -->
    <!-- based on http://encosia.com/3-reasons-why-you-should-let-google-host-jquery-for-you/ -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="jquery.form.js"></script>
    <script src="bootstrap/js/bootstrap-fileupload.js"></script>
    <script src="select2/select2.js"></script>
    <script>

        var vmap, wfs_layer,assets_array,gridMaxRes=400,highlightCtrl,selectCtrl,toolPanel,unselectAllCtrl,unselectAllFeatures,historyClick,getSelectedCellsArray;
        var geoserver_root = "/geoserver";
        var current_occurence_label = "Current occurence";
        var baseline_occurence_label = "Baseline occurence";

        // Window resize function
        var rsz = function(){
            var h = $(window).height();
            var w = $(window).width();
            // Setting height to window height minus the header and footer sizes
            $("#map").css('height',h - 42);
            $("#map").css('width', w - 352);
            // Setting the height of the sidebar as well, as it's getting an unwelcome scrollbar lateral shift
            $("#sidebar").css('height',h - 40)
        };

        // Initial width/height values are required for display of vector layer
        rsz();
        $(window).resize(rsz);

        $(document).ready(function () {

            function initMap(){
                vmap = new OpenLayers.Map('map',
                    {
                        projection: "EPSG:900913",
                        units: "m",
                        maxResolution: 156543.0339,
                        maxExtent: new OpenLayers.Bounds(-20037508.34, -20037508.34, 20037508.34, 20037508.34),
                        controls:[
                            new OpenLayers.Control.Navigation(),
                            new OpenLayers.Control.Zoom(),
                            new OpenLayers.Control.ScaleLine(),
                            new OpenLayers.Control.ZoomBox({'keyMask': OpenLayers.Handler.MOD_SHIFT})
                        ],
                        numZoomLevels:20,
                        theme: null
                    }
                );

                vmap.addControl(new OpenLayers.Control.LayerSwitcher(
                    {'div':$('#extraLayers')[0]}
                ));

                // Rewriting the text for Overlays, based on the OpenLayers control nested structure
                $('#extraLayers div div.dataLbl').html("<legend>Layers available</legend>");

                osm = new OpenLayers.Layer.OSM("Simple OSM Map","",{'displayInLayerSwitcher':false});
                vmap.addLayer(osm);
                vmap.setCenter(
                    new OpenLayers.LonLat(172, -40).transform(
                        new OpenLayers.Projection("EPSG:4326"),
                        vmap.getProjectionObject()
                    ), 5
                );

                // Style definition for the grid vector layer
                var defaultStyle = new OpenLayers.Style({
                    'strokeColor': 'black',
                    'strokeOpacity': 0.5,
                    'strokeWidth': 0.5,
                    'fillOpacity': 0
                });

                var selectStyle = new OpenLayers.Style({
                    'strokeColor': 'blue',
                    'strokeWidth': 0.5,
                    'fillColor':'blue',
                    'fillOpacity': 0.5
                });

                var sm = new OpenLayers.StyleMap({'default': defaultStyle,'select': selectStyle});

                // Vector layer for the grid of cells
                wfs_layer = new OpenLayers.Layer.Vector("Grid", {
                    strategies: [new OpenLayers.Strategy.BBOX()],
                    protocol: new OpenLayers.Protocol.WFS({
                        version: "1.1.0",
                        url: geoserver_root+"/wfs",
                        featureType: "CELL",
                        featureNS: "http://www.pozi.com/project1",
                        srsName: "EPSG:900913"
                    }),
                    styleMap: sm,
                    maxResolution:gridMaxRes,
                    displayInLayerSwitcher:false
                    }
                );

                vmap.addLayer(wfs_layer);

                var report = function(e) {
                    $('#currentCell').html(e.feature.fid.split(".")[1]);
                };

                var unreport = function(e) {
                    $('#currentCell').html("");
                };

                highlightCtrl = new OpenLayers.Control.SelectFeature(wfs_layer, {
                    hover: true,
                    highlightOnly: true,
                    renderIntent: "temporary",
                    eventListeners: {
                        featurehighlighted: report,
                        featureunhighlighted: unreport
                    }
                });

                var reportSelection = function(e) {
                    if (e)
                    {
                        var nsf = e.feature.layer.selectedFeatures.length;

                        // Adjust the number of cell selected if the event is an unhighlight
                        if (e.type == "featureunhighlighted")
                        {
                            nsf = nsf - 1;
                        }

                        // Display the number of cells selected
                        if (nsf>0)
                        {
                            $('#extraInfo').show();
                            $('#extraActions').show();
                            $('#extraInfo').html("For the "+(nsf==1?"":nsf+" ")+"selected cell"+(nsf==1?"":"s")+", show <a href='#' onClick='historyClick()'>history</a> or:");
                            unselectAllCtrl.activate();
                            $('#formOutput').html("").hide();
                        }
                        else
                        {
                            $('#extraInfo').hide();
                            $('#extraActions').hide();
                            $('#extraInfo').html("");
                            unselectAllCtrl.deactivate();
                            $('#formOutput').html("").hide();
                        }
                     }
                     else
                     {
                            $('#extraInfo').hide();
                            $('#extraActions').hide();
                            $('#extraInfo').html("");
                            unselectAllCtrl.deactivate();
                            $('#formOutput').html("").hide();
                     }
                };

                selectCtrl = new OpenLayers.Control.SelectFeature(wfs_layer,{
                    multiple: true,
                    toggle: true,
                    eventListeners: {
                        featurehighlighted:reportSelection,
                        featureunhighlighted:reportSelection
                    }
                });

                // Required so that the map can still be dragged and panned even when the user clicks on a cell
                // Based on: http://osgeo-org.1560.x6.nabble.com/vector-layer-prevents-map-pan-and-drag-td4567876.html
                highlightCtrl.handlers.feature.stopDown = false;
                selectCtrl.handlers.feature.stopDown = false;

                vmap.addControl(highlightCtrl);
                vmap.addControl(selectCtrl);

                unselectAllFeatures = function() {
                    // The order of unselection counts: select controls before highlight control
                    selectCtrl.unselectAll();
                    selectByPolygon.unselectAll();
                    highlightCtrl.unselectAll();
                    reportSelection();
                    selectByPolygon.deactivate();
                };

                unselectAllCtrl = new OpenLayers.Control.Button({
                    displayClass: "unselectBtn", trigger: unselectAllFeatures
                });

                selectByPolygon = new OpenLayers.Control.SelectFeature(wfs_layer,{
                    id:'selectByPolyId',
                    displayClass:"selectByPoly",
                    multiple:true, // means that the selections from this tool are additive to other selections,
                    toggle:true,
                    box:true,
                    eventListeners: {
                        featurehighlighted:reportSelection,
                        featureunhighlighted:reportSelection
                    },
                    type: OpenLayers.Control.TYPE_TOGGLE // the tool icon can be pushed and pushed back
                });

                downloadCtrl = new OpenLayers.Control.Button({
                    displayClass: "download", trigger: function(){alert("Download not implemented");}
                });

                var container = document.getElementById("extraTools");
                toolPanel = new OpenLayers.Control.Panel({div:container});
                toolPanel.addControls([unselectAllCtrl,selectByPolygon,downloadCtrl]);

                $('#loginTool').click(function(){
                    if ($(this).hasClass('icon-white'))
                    {
                        //alert('You will be logged out now');
                        window.location = 'logout.php';
                    }
                    else
                    {
                        $('#myLoginModal').show();
                    }
                })

                vmap.addControl(toolPanel);

                $('#legendImg').attr('src',geoserver_root+"/wms?request=GetLegendGraphic&format=image%2Fpng&width=15&height=15&layer=CURRENT_OCCURENCE&transparent=true");

            }

            initMap();

            $.getJSON('ws/ws_asset_list.php', function(data) {
                // Asset JSON must have a "rows" attribute that contains an array of id,text pair values 
                // {"total_rows":"184","rows":
                //      [
                //       {"id":"1","text":"Acer pseudoplatanus"},
                //       {"id":"2","text":"Agapanthus praecox"}, ...
                //      ]
                // }
                assets_array = data.rows;

                $('#myHistoryModal').modal({backdrop:false,show:false});
                $('#closeModal').click(function(){
                    $('#myHistoryModal').hide();
                });

                $('#myLoginModal').modal({backdrop:true,show:false});
                $('#closeLoginModal').click(function(){
                    $('#myLoginModal').hide();
                });

                $('#login-submit').click(function(){
                    // Submitting the form with the username and password to the same page
                    $('#login_form').submit();
                });

                historyClick = function(){
                    var selCell = getSelectedCellsArray().join(",");
                    var selAsset = $("#e1")[0].value;

                    $.getJSON('ws/ws_get_history.php', { asset: selAsset, selected_cells: selCell })
                    .done(function(data) {
                            // An array of flat objects, ready to be plugged into an HTML table
                            var json_res = data.rows;

                            var html_str = "<table class='table table-hover table-striped table-bordered table-condensed'><thead><tr><th>Cell</th><th>Source type</th><th>Status</th><th>Stakeholder</th><th>Time</th></tr></thead>";
                            html_str += "<tbody>";
                            for (r in json_res)
                            {
                                html_str += "<tr>";
                                html_str += "<td>"+json_res[r].cell_id+"</td>";
                                html_str += "<td>"+json_res[r].source_type+"</td>";
                                html_str += "<td>"+json_res[r].status+"</td>";
                                html_str += "<td>"+json_res[r].stakeholder+"</td>";
                                html_str += "<td>"+json_res[r].time_mark+"</td>";
                                html_str += "</tr>";
                            }
                            html_str += "</tbody></table>";

                            // Showing a history div and populating it with the result
                            $('#historyDiv').html(html_str);
                            $('#myHistoryModal').show();

                    });
                };

                getSelectedCellsArray = function(){
                    var cell_arr=[];
                    for (f in wfs_layer.selectedFeatures)
                    {
                        if (wfs_layer.selectedFeatures[f].fid)
                        {
                            // Pushing elements in the array
                            cell_arr.push(wfs_layer.selectedFeatures[f].fid.split('.')[1])
                        }
                    }
                    return cell_arr;
                }


                $("#e1").select2({
                    data: assets_array,
                    placeholder: "Select a weed",
                    allowClear: true
                }).on("change", function(e) {

                        // Removing the previous layers
                        var layerToRemove = vmap.getLayersByName(current_occurence_label);
                        if (layerToRemove.length)
                        {
                            // By construction, there is only one WMS layer with this name
                            vmap.removeLayer(layerToRemove[0]);
                        }

                        var layerToRemove2 = vmap.getLayersByName(baseline_occurence_label);
                        if (layerToRemove2.length)
                        {
                            // By construction, there is only one WMS layer with this name
                            vmap.removeLayer(layerToRemove2[0]);
                        }

                        // If the selection was changed to another weed (i.e. not cleared)
                        if (e.added)
                        {
                            // Adding the new ones
                            var co_wms = new OpenLayers.Layer.WMS(current_occurence_label,
                                geoserver_root+"/PROJECT1/wms?",
                                {
                                    "transparent":"true",
                                    "layers":"CURRENT_OCCURENCE",
                                    "format":"image/png8",
                                    "viewparams":"asset_id:"+e.added.id
                                },{
                                    isBaseLayer: false,
                                    singleTile:true,
                                    transitionEffect:'resize'
                                }
                            );

                           var bo_wms = new OpenLayers.Layer.WMS(baseline_occurence_label,
                                geoserver_root+"/PROJECT1/wms?",
                                {
                                    "transparent":"true",
                                    "layers":"BASELINE_OCCURENCE",
                                    "format":"image/png8",
                                    "viewparams":"asset_id:"+e.added.id
                                },{
                                    isBaseLayer: false,
                                    singleTile:true,
                                    visibility: false,
                                    transitionEffect:'resize'
                                }
                            );

                            // Add WMS layer to our map
                            vmap.addLayers([co_wms,bo_wms]);

                            // Activating the controls
                            highlightCtrl.activate();
                            selectCtrl.activate();
                            unselectAllCtrl.deactivate();

                            // Showing panel with extra tools
                            $('#extraTools').removeClass('hide').addClass('singleLineTools');

                            // Showing panel with extra layer control
                            $('#extraLayers').show();
                            $('#extraLegend').show();

                        }
                        else
                        {
                            // De-selecting and deactivating the controls
                            highlightCtrl.unselectAll();
                            selectCtrl.unselectAll();
                            highlightCtrl.deactivate();
                            selectCtrl.deactivate();

                            $('#extraTools').addClass('hide').removeClass('singleLineTools');
                            $('#extraLayers').hide();
                            $('#extraActions').hide();
                            $('#extraLegend').hide();

                        }

                });

            });

            // Initialising the file upload component
            $('.fileupload').fileupload({uploadType:'image'});

            $('#cancel').on('click',function(){
                //alert("Clicked");
                $('.fileupload').fileupload('reset');
                $('#mod_form').each (function(){this.reset();});
            })

            $('#save').on('click',function(){
                // Modifying hidden values before submission:
                // Currently selected asset
                $('form input[name="field_asset"]').val($("#e1")[0].value);
                // Currently selected cells
                var cell_arr = getSelectedCellsArray();
                $('form input[name="field_selected_cells"]').val(cell_arr);

                var processJson = function(data){
                    if (data.success){
                        var layerToRefresh = vmap.getLayersByName(current_occurence_label);
                        if (layerToRefresh.length)
                        {
                            // By construction, there is only one WMS layer with this name
                            layerToRefresh[0].redraw(true);
                        }
                        var img_block = "";
                        if (data.uploaded_img != "NONE")
                        {
                            img_block = " <a href='"+data.uploaded_img+"' target=\"_blank\" style=\"float:right;\"><img height=30 width=30 src='"+data.uploaded_img+"'/></a>";
                        }

                        // Clearing up the form and the selection
                        $('#cancel').click();
                        unselectAllFeatures();

                        $('#formOutput').html("Just created observation #"+data.observation_id+img_block).show();
                    }
                };

                // Submitting the form
                var mod_form_data = $('#mod_form').ajaxSubmit({
                    // dataType identifies the expected content type of the server response 
                    dataType: 'json',
                    // success identifies the function to invoke when the server response has been received 
                    success: processJson
                });

                return false; 

            })

        });
    </script>
  </body>
</html>
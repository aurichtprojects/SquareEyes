<?php
    session_start();

    $display_error_login = false;
    $logged_in = false;
    $logged_in_user = '';
    $logged_in_role = '';

    require_once('ws/inc/error.inc.php');
    require_once('ws/inc/database.inc.php');

    try {
        // Checking if we already have logged in
        if (!isset($_SESSION['logged-in']) || $_SESSION['logged-in'] !== true) {
            if (isset($_POST['login_username']) && isset($_POST['login_password']))
            {
                // Establishing connection to database to check that the username and password submitted are valid
                $pgconn = pgConnection();
                $sql = "select (select lower(r.label) from nz.r_role r where r.id=role_id) from nz.\"user\" where name='".$_POST['login_username']."' and md5(password)='".$_POST['login_password']."'";
                //echo $sql;
                $recordSet = $pgconn->prepare($sql);
                $recordSet->execute();

                $_SESSION['logged-in'] = false;
                $_SESSION['logged-in-user'] = '';
                $_SESSION['logged-in-role'] = '';

                while ($row  = $recordSet->fetch())
                {
                    $_SESSION['logged-in'] = true;
                    $_SESSION['logged-in-user'] = $_POST['login_username'];
                    $_SESSION['logged-in-role'] = $row[0];
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
                // We are logged out and have not submitted any request to login
                // The login icon will open the login form
            }
        }
        else
        {
            // We are already logged in, the login icon will logout
            $logged_in = true;
            $logged_in_user = $_SESSION['logged-in-user'];
            $logged_in_role = $_SESSION['logged-in-role'];
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
    <link rel="stylesheet" href="openlayers/theme/default/google.css" type="text/css">
    <style type="text/css">

        body {
            padding: 5px;
        }

        .container-fluid {
            padding: 0;
        }

        h3 {
            font-size: 14.5px;
            font-weight: normal;
            line-height: 20px;
            margin: 0;
        }

        form {
            margin: 0;
        }

        /* CSS for a fluid-fixed layout based on http://jsfiddle.net/andresilich/6vPqA/13/ */
        .well {
            padding:5px;
            margin-bottom: 5px;
        }

        .scrollable {
            overflow: auto;
        }

        .fluid-fixed {
            margin-right: 325px;
            margin-left:auto !important;
            display: block;
        }

        .row-fluid > .sidebar-nav {
            position: relative;
            top: 0;
            left:auto;
            width: 305px;
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
            width: 320px;
        }

        #userMgmtModal.modal {
            width:500px;
            height:400px;
        }

        #userMgmtModal.modal .modal-body {
            height: 98%;
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
            background-repeat: no-repeat;
            border: 1px none;
            margin: 3px 0;
        }

        #extraLayers {
          margin:0px 0 5px;
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
            font-size: 25px;
            margin-bottom: 5px;
        }

        input[type="radio"], input[type="checkbox"] {
            margin: 0;
        }

        div.olControlZoom a {
            background: none repeat scroll 0 0 rgba(160, 160, 160, 0.25);
        }

        div.olControlZoom a:hover {
            background: none repeat scroll 0 0 rgba(160, 160, 160, 0.75);
        }

        div.olControlZoom a.olControlZoomToMaxExtent {
          width:  22px;
          height: 22px;
          background-image: url("nz.png");
          background-repeat: no-repeat;
          background-color: rgba(160, 160, 160, 0.25);
        }

        div.olControlZoom a.olControlZoomToMaxExtent:hover {
          width:  22px;
          height: 22px;
          background-image: url("nz.png");
          background-repeat: no-repeat;
          background-color: rgba(160, 160, 160, 0.75);
        }

        #helloP {
            display: inline-block;
            font-size: 10px;
            line-height: 10px;
            margin: 11px 40px 3px 2px;
            vertical-align: bottom;
            width: 25px;
        }

        a.legendLink {
            margin:0;
            display:block;
            float:right;
            font-size:8px;
            cursor:pointer;
        }

        .btn-mini {
            padding: 1px 4px;
            margin-right:5px;
        }

        .tooltipClass {
            margin-top: 10px;
        }

        #basemapControls {
            left: 100%;
            margin-left: -385px;
            position: absolute;
            top: 15px;
            z-index: 1000;
        }

        .dropdown-menu .divider {
            margin: 1px;
        }

        .dropdown-menu > li > a {
            padding:3px 10px;
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
            if ($display_error_login){$login_add_class = "";}
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
                                if ($display_error_login){$login_add_class = "";}
                                echo "<div class=\"alert alert-error ".$login_add_class."\">Incorrect username or password</div>";
                            ?>
                            <form id="login_form" action="<?php $PHP_SELF; ?>" method="post">
                                <fieldset>
                                    <div class="clearfix">
                                        <input type="text" name="login_username" placeholder="Username" value="<?php
                                            if ($display_error_login){echo $_POST['login_username'];}
                                        ?>" style="height:15px;">
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
        </div> <!-- /modal -->

        <div class="modal hide" id="userMgmtModal">
            <div class="modal-header">
                <button id="closeUserMgmtModal" type="button" class="close">&times;</button>
            </div>
            <div class="modal-body">
                <iframe width="100%" height="98%" frameborder="0" scrolling="yes" allowtransparency="true" src="users/index.php" style="overflow-x:hidden; overflow-y:auto;"></iframe>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row-fluid">
                <!-- the sidebar needs to be defined before the map fluid content! -->
                <div id="sidebar" class="well right sidebar-nav scrollable" style="margin-bottom:0;">
                    <!-- Drop down -->
                    <div class="well">
                        <legend>Weed</legend>
                        <input type="hidden" id="e1" style="width:272px;"/>
                        <!-- Controls and additional layers -->
                        <div id="baseTools">
                            <?php
                                $icon_class = "icon-arrow-right";
                                $logged_in_user_message = "";
                                $tooltip_msg="Login";
                                if ($logged_in){
                                    $icon_class = "icon-arrow-left icon-white";
                                    $logged_in_user_message = "hello ".$logged_in_user;
                                    $tooltip_msg="Logout";
                                };
                                echo "<a href='#' class='btn btn-mini tooltipClass' data-toggle='tooltip' title='".$tooltip_msg."'><i id=\"loginTool\" class=\"".$icon_class." singleLineTools\" style=\"margin:2px 0px;\"></i></a>";
                                echo "<p id='helloP'>".$logged_in_user_message."</p>";
                                if ($logged_in_role=="moderator")
                                {
                                    echo '<a id="userManagementCtrl" class="btn btn-mini tooltipClass" href="#" title="Manage users & logins"><i class="icon-user"></i></a>';
                                }
                            ?>
                            <div id="extraTools" class="hide">
                                <?php
                                    if ($logged_in){
                                        echo '<a id="unselectAllCtrl" class="btn btn-mini tooltipClass disabled" href="#" title="Unselect all selected cells"><i class="icon-remove"></i></a>';
                                        echo '<a id="selectByPolyCtrl" class="btn btn-mini tooltipClass" href="#" title="Select cells by drawing a polygon"><i class="icon-th"></i></a>';
                                        if ($logged_in_role=="moderator")
                                        {
                                            echo '<a id="downloadCtrl" class="btn btn-mini tooltipClass" href="#" title="Download current occurrence as SHP"><i class="icon-download-alt"></i></a>';
                                        }
                                    }
                                ?>
                            </div>
                            <div id="currentCell" class="singleLineTools" style="margin-top:12px;line-height:10px; margin-left:5px;font-size:10px;text-align:center;width:35px;"></div>
                        </div>
                    </div>

                    <!-- Message / info -->
                    <div id="extraLayers" class="well hide"></div>

                    <!-- Action / form -->
                    <div class="<?php if (!$logged_in) {echo "hide";} ?>">
                        <div id="extraActions" class="well hide">
                            <!-- Show history -->
                            <div id="extraInfo" class="hide"></div>
                            <!-- Report / moderate form -->
                            <form id="mod_form" action="ws/ws_create_observation.php" method="POST">
                                <div class="well">
                                    <h3><?php if ($logged_in_role == 'moderator') {echo "Moderate";} else {echo "Report";} ?>:</h3>
                                    <label class="radio <?php if ($logged_in_role == 'user') {echo "hide";} ?>">
                                        <input type="radio" name="field_options_radios" id="optionsRadios1" value="1">Reject
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="field_options_radios" id="optionsRadios2" value="2">Report presence
                                    </label>
                                    <label class="radio <?php if ($logged_in_role == 'user') {echo "hide";} ?>">
                                        <input type="radio" name="field_options_radios" id="optionsRadios3" value="3">Approve
                                    </label>
                                    <!-- START COMMENT UPLOAD-->
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
                                    <!-- END COMMENT UPLOAD -->
                                    <input type="hidden" name="field_asset" value="">
                                    <input type="hidden" name="field_selected_cells" value="">
                                    <button type="button" id="save" class="btn btn-primary right">Save</button>
                                    <button type="button" id="cancel" class="btn btn-link right">Clear</button>
                                    <!-- Style issue here where the buttons appear outside of the well -->
                                    <br/><br/>
                                </div>
                            </form>

                    </div>
                    <div id="formOutput" class="well hide"></div>
                </div>
            </div>

            <div class="well fluid-fixed" style="margin-bottom:0;">
                <!--Body content-->
                <div id="map" class="smallmap" style="background-color: white;"></div>
                <div id="basemapControls">
                    <div class="btn-group">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#" style="padding: 4px 6px;">
                            <img src="layers.png" style="width:16px;height:16px;" class="img-rounded">
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" style="left:auto; right:0; min-width: 60px; font-size:10px;">
                            <li><a href="#" onClick="changeBasemapTo('OSM')">OpenStreetMap</a></li>
                            <li class="divider"></li>
                            <li><a href="#" onClick="changeBasemapTo('MapQuest')">MapQuest</a></li>
                            <li class="divider"></li>
                            <li><a href="#" onClick="changeBasemapTo('Google Satellite')">Google Aerial</a></li>
                            <li class="divider"></li>
                            <li><a href="#" onClick="changeBasemapTo('Google Physical')">Google Terrain</a></li>
                            <li class="divider"></li>
                            <li><a href="#" onClick="changeBasemapTo('Google Streets')">Google Maps</a></li>
                            <li class="divider"></li>
                            <li><a href="#" onClick="changeBasemapTo('None')">None</a></li>
                        </ul>
                    </div>
                </div>
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

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="openlayers/OpenLayers.js"></script>
        <!-- Google Maps API -->
        <script src='http://maps.google.com/maps/api/js?v=3&amp;sensor=false'></script>
        <!-- Letting Google host and serve jQuery for us -->
        <!-- based on http://encosia.com/3-reasons-why-you-should-let-google-host-jquery-for-you/ -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <!-- A plugin for AJAXifying form serialisation, especially useful for file upload -->
        <script src="jquery.form.js"></script>
        <!-- A plugin in for a nice file upload field in Bootstrap -->
        <script src="bootstrap/js/bootstrap-fileupload.js"></script>
        <!-- A fully-fledged plugin for the asset drop-down -->
        <script src="select2/select2.js"></script>
        <!-- Placeholder plugin required for proper bahaviour of the placeholder attribute in forms in IE -->
        <script src="jquery-placeholder/jquery.placeholder.min.js"></script>
        <!-- Crypto library used to encode the password in MD5 before POSTing it for login attempt -->
        <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/md5.js"></script>
        <script>
            var vmap, changeBasemapTo,activateControls, wfs_layer,assets_array,highlightCtrl,selectCtrl,toolPanel,unselectAllCtrl,unselectAllFeatures,historyClick,getSelectedCellsArray;
            var gridMaxRes = 400;
            // Reduce the number of cells rendered in IE < 9
            // Note: browser detection function is deprecated in jQuery 1.9+
            if ($.browser.msie)
            {
                if (parseInt($.browser.version.split(".")[0]) < 9)
                {
                    gridMaxRes = 100;
                }
            }
            var geoserver_root = "/geoserver";
            var current_occurrence_label = "Current occurrence";
            var baseline_occurrence_label = "Baseline occurrence";
            var pd_current_label = "Potential distribution current";
            var pd_future_label = "Potential distribution future";
            var initialMapCenter = new OpenLayers.LonLat(172, -42).transform(
                new OpenLayers.Projection("EPSG:4326"),
                new OpenLayers.Projection("EPSG:900913")
            );
            var initialZoomLevel=5;
            var cellLayerName="CELL";
            var cellLayerNamespace = "http://www.pozi.com/squareeyes";
            var workspace_name="SQUAREEYES";
            var current_occurrence_layername="CURRENT_OCCURENCE";
            var current_occurrence_download_layername = "CURRENT_OCCURENCE_DL";
            var baseline_occurrence_layername="BASELINE_OCCURENCE";
            var pd_current_layername="PD_CURRENT";
            var pd_future_layername="PD_FUTURE";

            var cell_history_ws = 'ws/ws_get_history.php';
            var asset_list_ws = 'ws/ws_asset_list.php';

            // Window resize function
            var rsz = function(){
                var h = $(window).height();
                var w = $(window).width();
                // Setting height to window height minus the header and footer sizes
                $("#map").css('height',h - 25);
                $("#map").css('width', w - 351);
                // Setting the height of the sidebar as well, as it's getting an unwelcome scrollbar lateral shift
                $("#sidebar").css('height',h - 23)
            };

            // Initial width/height values are required for display of vector layer
            rsz();
            $(window).resize(rsz);

            $(document).ready(function () {
                function initMap(){
                    // Managing activated controls
                    activateControls = function(){
                        if (vmap && wfs_layer)
                        {
                            // Controls should only be activated if the layer is both visible and in range
                            if (wfs_layer.getVisibility() && wfs_layer.calculateInRange() && $('#e1').val())
                            {
                                if (!highlightCtrl.active)
                                {
                                    highlightCtrl.activate();
                                }
                                if (!selectCtrl.active)
                                {
                                    selectCtrl.activate();
                                }
                            }
                            else
                            {
                                if (highlightCtrl.active)
                                {
                                    highlightCtrl.unselectAll();
                                    highlightCtrl.deactivate();
                                }
                                if (selectCtrl.active)
                                {
                                    selectCtrl.unselectAll();
                                    selectCtrl.deactivate();
                                }
                            }
                        }
                    };

                    var mapOptions = {
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
                            theme: null,
                            eventListeners: {
                                "zoomend": activateControls
                            }
                    };

                    // disabling the OpenLayers 2.13 smooth zoom, it's just not smooth in IE/Firefox
                    if (!($.browser.webkit))
                    {
                        mapOptions["zoomMethod"]=null;
                    };

                    vmap = new OpenLayers.Map('map', mapOptions);

                    // Overriding the default behaviour of OpenLayers
                    // because we don't want to rebuild the entire div when a layer visibility changes
                    OpenLayers.Control.LayerSwitcher.prototype.checkRedraw = function(){
                        if ( !this.layerStates.length ||
                             (this.map.layers.length != this.layerStates.length) ) {
                            return true;
                        }

                        for (var i = 0, len = this.layerStates.length; i < len; i++) {
                            var layerState = this.layerStates[i];
                            var layer = this.map.layers[i];
                            if ( (layerState.name != layer.name) ||
                                 // The workaround is here .. (for when the grid gets in/out of range)
                                 // (layerState.inRange != layer.inRange) ||
                                 (layerState.id != layer.id)
                                 // .. and here (for when overlays are ticked on/off)
                                 // || (layerState.visibility != layer.visibility)
                                 )
                            {
                                return true;
                            }
                        }

                        return false;
                    };

                    // Layer control definition
                    vmap.addControl(new OpenLayers.Control.LayerSwitcher(
                        {'div':$('#extraLayers')[0]}
                    ));
                    // Rewriting the text for Overlays, based on the OpenLayers control nested structure
                    $('#extraLayers div div.dataLbl').html("<legend>Layers</legend>");

                    // Adding a zoom to full extent button between the zoom +/-
                    $('.olControlZoomIn').after("<a id='olControlZoomToMaxExtent' class='olControlZoomToMaxExtent olButton' href='#zoomToMaxExtent'>&nbsp;</a>");
                    $('#olControlZoomToMaxExtent').click(function(){
                        vmap.moveTo(initialMapCenter,initialZoomLevel);
                    });

                    var osm = new OpenLayers.Layer.OSM("OSM","",{displayInLayerSwitcher:false});
                    vmap.addLayer(osm);

                    var mq = new OpenLayers.Layer.OSM("MapQuest",
                        ["http://otile1.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
                        "http://otile2.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
                        "http://otile3.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
                        "http://otile4.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg"],
                        {displayInLayerSwitcher:false}
                    );
                    vmap.addLayer(mq);

                    var clearBaseLayer = new OpenLayers.Layer("None", {isBaseLayer: true, displayInLayerSwitcher:false}); 
                    vmap.addLayer(clearBaseLayer);

                    var glayers= [
                        new OpenLayers.Layer.Google(
                            "Google Physical",
                            {type: google.maps.MapTypeId.TERRAIN, displayInLayerSwitcher:false}
                        ),
                        new OpenLayers.Layer.Google(
                            "Google Streets", // the default
                            {numZoomLevels: 20, displayInLayerSwitcher:false}
                        ),
                        /*  Unused layers have been commented out but left in case they are needed
                        new OpenLayers.Layer.Google(
                            "Google Hybrid",
                            {type: google.maps.MapTypeId.HYBRID, numZoomLevels: 20, displayInLayerSwitcher:false}
                        ), */
                        new OpenLayers.Layer.Google(
                            "Google Satellite",
                            {type: google.maps.MapTypeId.SATELLITE, numZoomLevels: 22, displayInLayerSwitcher:false}
                        )
                    ];
                    vmap.addLayers(glayers);

                    changeBasemapTo = function(basemapInternalName){
                        vmap.setBaseLayer(vmap.getLayersByName(basemapInternalName)[0]);
                    }

                    vmap.setCenter(initialMapCenter, initialZoomLevel);

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
                        strategies: [new OpenLayers.Strategy.BBOX({ratio:1.1,resFactor:3})],
                        protocol: new OpenLayers.Protocol.WFS({
                            version: "1.1.0",
                            url: geoserver_root+"/wfs",
                            featureType: cellLayerName,
                            featureNS: cellLayerNamespace,
                            srsName: "EPSG:900913",
                            outputFormat: "json",
                            readFormat: new OpenLayers.Format.GeoJSON()
                        }),
                        styleMap: sm,
                        maxResolution:gridMaxRes,
                        displayInLayerSwitcher:false,
                        transitionEffect:'resize',
                        visibility: <?php if ($logged_in) {echo 'true';} else {echo 'false';} ?>
                        }
                    );

                    vmap.addLayer(wfs_layer);

                    var report = function(e) {
                        $('#currentCell').html("cell ID "+e.feature.fid.split(".")[1]);
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
                                $('#extraInfo').html("<legend>Selection: "+nsf+" cell"+(nsf==1?"":"s")+"</legend><div class='well'><h3>Show <a href='#' onClick='historyClick()'>history</a></h3></div>");
                                $('#unselectAllCtrl').removeClass("disabled");
                                $('#formOutput').html("").hide();
                            }
                            else
                            {
                                $('#extraInfo').hide();
                                $('#extraActions').hide();
                                $('#extraInfo').html("");
                                $('#unselectAllCtrl').addClass("disabled");
                                $('#formOutput').html("").hide();
                            }
                         }
                         else
                         {
                                $('#extraInfo').hide();
                                $('#extraActions').hide();
                                $('#extraInfo').html("");
                                $('#unselectAllCtrl').addClass("disabled");
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

                    // Login tool
                    $('#loginTool').click(function(){
                        // If the class icon-white is present (i.e. we are logged in), then clicking the control triggers logout
                        if ($(this).hasClass('icon-white'))
                        {
                            //alert('You will be logged out now');
                            window.location = 'logout.php';
                        }
                        else
                        {
                            // We display the modal to enter username/password
                            $('#myLoginModal').show();
                        }
                    })

                    unselectAllFeatures = function() {
                        // The order of unselection counts: select controls before highlight control
                        selectCtrl.unselectAll();
                        highlightCtrl.unselectAll();
                        reportSelection();
                        selectByPolygon.deactivate();
                        $('#selectByPolyCtrl i').removeClass("icon-white");
                        $('#unselectAllCtrl').blur();
                    };

                    $('#unselectAllCtrl').click(unselectAllFeatures);

                    selectByPolygon = new OpenLayers.Control.SelectFeature(wfs_layer,{
                        id:'selectByPolyId',
                        multiple:true, // means that the selections from this tool are additive to other selections,
                        toggle:true,
                        box:true,
                        eventListeners: {
                            featurehighlighted:reportSelection,
                            featureunhighlighted:reportSelection
                        }
                    });

                    // Adding the control to the map
                    vmap.addControl(selectByPolygon);

                    $('#selectByPolyCtrl').click(function()
                    {
                        if (selectByPolygon.active)
                        {
                            selectByPolygon.deactivate();
                            // Icon back to black
                            $('#selectByPolyCtrl i').removeClass("icon-white");
                        }
                        else
                        {
                            selectByPolygon.activate();
                            // Icon switches to white
                            $('#selectByPolyCtrl i').addClass("icon-white");
                        }
                    })

                    $('#downloadCtrl').click(function(){
                        // Finding the ID and label of the object in the assets_array structure
                        var selected_asset_idx = $('#e1').val();
                        var asset_id,asset_name;
                        for (i=0;i<assets_array.length;i++)
                        {
                            if (assets_array[i].id==selected_asset_idx)
                            {
                                asset_id = assets_array[i].id;
                                asset_name = assets_array[i].text.toLowerCase().replace(" ","_");
                                break;
                            }
                        }
                        // Setting the download link correctly
                        var url = window.location.protocol+"//"+window.location.host+geoserver_root+"/"+workspace_name+"/ows?service=WFS&version=1.0.0&request=GetFeature&typeName="+workspace_name+"%3A"+current_occurrence_download_layername+"&maxfeatures=3500&outputformat=SHAPE-ZIP&VIEWPARAMS=asset_id:"+asset_id+"&format_options=filename:"+asset_name;
                        window.open(url, 'Download');
                    });

                    $('#userManagementCtrl').click(function(){
                        //alert('User management not implemented yet');
                        $('#userMgmtModal').show();
                    });

                }

                initMap();

                $.getJSON(asset_list_ws, function(data) {
                    // Asset JSON must have a "rows" attribute that contains an array of id,text pair values 
                    // {"total_rows":"184","rows":
                    //      [
                    //       {"id":"1","text":"Acer pseudoplatanus"},
                    //       {"id":"2","text":"Agapanthus praecox"}, ...
                    //      ]
                    // }
                    assets_array = data.rows;

                    // Somehow needed to add a hide function to the history modal close button
                    $('#myHistoryModal').modal({backdrop:false,show:false});
                    $('#closeModal').click(function(){
                        $('#myHistoryModal').hide();
                    });

                    // Somehow needed to add a hide function to the login modal close button
                    $('#myLoginModal').modal({backdrop:true,show:false});
                    $('#closeLoginModal').click(function(){
                        $('#myLoginModal').hide();
                    });

                    $('#userMgmtModal').modal({backdrop:true,show:false});
                    $('#closeUserMgmtModal').click(function(){
                        $('#userMgmtModal').hide();
                    });

                    // Clicking the login form button ... sends the form
                    $('#login-submit').click(function(){
                        $('#closeLoginModal').click();
                        // Encrypting the password in MD5 before submitting it
                        var password_before_encrypt = $('form input[name="login_password"]').val();
                        var password_after_encrypt = CryptoJS.MD5(password_before_encrypt);
                        $('form input[name="login_password"]').val(password_after_encrypt);
                        // Submitting the form with the username and password to the same page
                        $('#login_form').submit();
                    });

                    // Fixing placeholder issues in IE
                    $('input, textarea').placeholder();

                    // Tooltips on ... tools
                    $('.tooltipClass').tooltip();

                    // Gathering information to display in the history table for the selected cells
                    historyClick = function(){
                        var selCell = getSelectedCellsArray().join(",");
                        var selAsset = $("#e1")[0].value;

                        $.getJSON(cell_history_ws, { asset: selAsset, selected_cells: selCell })
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
                        var layerToRemove = vmap.getLayersByName(current_occurrence_label);
                        if (layerToRemove.length)
                        {
                            // By construction, there is only one WMS layer with this name
                            vmap.removeLayer(layerToRemove[0]);
                        }

                        var layerToRemove2 = vmap.getLayersByName(baseline_occurrence_label);
                        if (layerToRemove2.length)
                        {
                            // By construction, there is only one WMS layer with this name
                            vmap.removeLayer(layerToRemove2[0]);
                        }

                        var layerToRemove3 = vmap.getLayersByName(pd_current_label);
                        if (layerToRemove3.length)
                        {
                            // By construction, there is only one WMS layer with this name
                            vmap.removeLayer(layerToRemove3[0]);
                        }

                        var layerToRemove4 = vmap.getLayersByName(pd_future_label);
                        if (layerToRemove4.length)
                        {
                            // By construction, there is only one WMS layer with this name
                            vmap.removeLayer(layerToRemove4[0]);
                        }

                        // If the selection was changed to another weed (i.e. not cleared)
                        if (e.added)
                        {
                            // Adding the new ones
                            var co_wms = new OpenLayers.Layer.WMS(current_occurrence_label,
                                geoserver_root+"/"+workspace_name+"/wms?",
                                {
                                    "transparent":"true",
                                    "layers":current_occurrence_layername,
                                    "format":"image/png8",
                                    "viewparams":"asset_id:"+e.added.id
                                },{
                                    isBaseLayer: false,
                                    singleTile:true,
                                    transitionEffect:'resize',
                                    ratio : 1.3
                                }
                            );

                           var bo_wms = new OpenLayers.Layer.WMS(baseline_occurrence_label,
                                geoserver_root+"/"+workspace_name+"/wms?",
                                {
                                    "transparent":"true",
                                    "layers":baseline_occurrence_layername,
                                    "format":"image/png8",
                                    "viewparams":"asset_id:"+e.added.id
                                },{
                                    isBaseLayer: false,
                                    singleTile:true,
                                    visibility: false,
                                    transitionEffect:'resize',
                                    ratio : 1.3
                                }
                            );

                            // Add WMS layer to our map
                            vmap.addLayers([co_wms,bo_wms]);

                            // Adding potential distribution layers if the asset_id is 
                            if (e.added.pd_flag == "t")
                            {
                                // Adding the new ones
                                var pdc_wms = new OpenLayers.Layer.WMS(pd_current_label,
                                    geoserver_root+"/"+workspace_name+"/wms?",
                                    {
                                        "transparent":"true",
                                        "layers":pd_current_layername,
                                        "format":"image/png8",
                                        "viewparams":"asset_id:"+e.added.id
                                    },{
                                        isBaseLayer: false,
                                        singleTile:true,
                                        visibility: false,
                                        transitionEffect:'resize',
                                        ratio : 1.3
                                    }
                                );

                                var pdf_wms = new OpenLayers.Layer.WMS(pd_future_label,
                                    geoserver_root+"/"+workspace_name+"/wms?",
                                    {
                                        "transparent":"true",
                                        "layers":pd_future_layername,
                                        "format":"image/png8",
                                        "viewparams":"asset_id:"+e.added.id
                                    },{
                                        isBaseLayer: false,
                                        singleTile:true,
                                        visibility: false,
                                        transitionEffect:'resize',
                                        ratio : 1.3
                                    }
                                );

                                // Add WMS layer to our map
                                vmap.addLayers([pdc_wms,pdf_wms]);
                            }

                            // Adding a legend link to show / hide the legend
                            $('.labelSpan').each(function(idx,e){
                                $(e).after("<a id='legendLine"+idx+"' class='legendLink'>legend</a>");
                                $("#legendLine"+idx).click(function(){
                                    var imgLgd = $('#imgLegendLine'+idx);
                                    if (imgLgd.hasClass('hide')) {imgLgd.removeClass('hide');imgLgd.css({display:'block'});} else {imgLgd.addClass('hide');imgLgd.css({display:'none'});}
                                });
                            });

                            // Adding a legend image
                            $('.dataLayersDiv > br').each(function(idx,e){
                                var layerNameArr = [current_occurrence_layername,baseline_occurrence_layername];
                                // Array index starts at 0 whereas asset_id starts at 1 - the first asset is at position 0 in the array
                                if (assets_array[$("#e1")[0].value-1].pd_flag == "t")
                                {
                                    layerNameArr=layerNameArr.concat([pd_current_layername,pd_future_layername]);
                                }
                                $(e).replaceWith("<br><img id='imgLegendLine"+idx+"' class='hide' src='"+geoserver_root+"/wms?request=GetLegendGraphic&format=image%2Fpng&width=15&height=15&layer="+layerNameArr[idx]+"&transparent=true&LEGEND_OPTIONS=fontSize:11;fontAntiAliasing:true'/>");
                            });

                            // Activating the controls
                            activateControls();

                            // Showing panel with extra tools
                            $('#extraTools').removeClass('hide').addClass('singleLineTools');

                            // Showing panel with extra layer control
                            $('#extraLayers').show();
                            $('#extraLegend').show();

                        }
                        else
                        {
                            // De-selecting and deactivating the controls
                            activateControls();

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
                    $('form input[type="text"], form textarea').val('');
                    // Finish by deselecting cells
                    unselectAllFeatures();
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
                            var layerToRefresh = vmap.getLayersByName(current_occurrence_label);
                            if (layerToRefresh.length)
                            {
                                // By construction, there is only one WMS layer with this name
                                layerToRefresh[0].redraw(true);
                            }
                            var img_block = "";
                            if (data.uploaded_img != "NONE")
                            {
                                img_block = " <a href='"+data.uploaded_img+"' target=\"_blank\" style=\"float:right;\"><img style=\"height:30px;width:30px;\" src='"+data.uploaded_img+"'/></a>";
                            }

                            // Clearing up the form and the selection
                            $('#cancel').click();

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
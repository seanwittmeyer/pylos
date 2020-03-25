<?php $includes = '/includes/app/de/'; ?><!DOCTYPE html>
<!--
//The MIT License (MIT)
//
//Copyright (c) 2018 Sean Wittmeyer based on substantial work by Thornton Tomasetti, Inc.
//
//Permission is hereby granted, free of charge, to any person obtaining a copy
//of this software and associated documentation files (the "Software"), to deal
//in the Software without restriction, including without limitation the rights
//to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
//copies of the Software, and to permit persons to whom the Software is
//furnished to do so, subject to the following conditions:
//
//The above copyright notice and this permission notice shall be included in
//all copies or substantial portions of the Software.
//
//THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
//IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
//FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
//AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
//LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
//OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
//THE SOFTWARE.
-->



<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Design Explorer 2</title>

    <link rel="shortcut icon" href="<?php echo $includes; ?>favicon.ico">
    <link rel="icon" type="image/png" href="<?php echo $includes; ?>favicon.png">

    <!-- D3 -->
    <script src="<?php echo $includes; ?>d3/d3.v3.min.js" charset="utf-8"></script>

    <!-- jQuery -->
    <script type="text/javascript" src="<?php echo $includes; ?>js/jquery-1.11.1.min.js"></script>
    <!--<script type="text/javascript" src="<?php echo $includes; ?>js/jquery.lazy.min.js"></script>-->

    <!-- Sliders script and css -->
    <script src="<?php echo $includes; ?>rangeslider_files/js/ion.rangeSlider.js"></script>
    <link rel="stylesheet" href="<?php echo $includes; ?>rangeslider_files/css/normalize.css" />
    <link rel="stylesheet" href="<?php echo $includes; ?>rangeslider_files/css/ion.rangeSlider.css" />
    <link rel="stylesheet" href="<?php echo $includes; ?>rangeslider_files/css/ion.rangeSlider.skinNice.css" />

    <!-- Parallel Coordinates -->
    <script type="text/javascript" src="<?php echo $includes; ?>pc_source_files/d3/d3.parcoords.js"></script>

    <!-- D3 Parallel Coordinates CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo $includes; ?>pc_source_files/css/d3.parcoords.css">

    <!-- Radar Chart -->
    <!-- TODO: add a switch for picking different charts to present -->
    <script type="text/javascript" src="<?php echo $includes; ?>radar_source_files/radar-chart.js"></script>

    <!-- Radar Chart CSS -->
    <!-- TODO: add a switch for picking different charts to present -->
    <!-- <link rel="stylesheet" type="text/css" href="<?php echo $includes; ?>radar_source_files/radar-chart.css" /> -->

    <!-- Scatter-matrix -->
    <script src="<?php echo $includes; ?>scatter_matrix_source_files/scatter-matrix.js"></script>
    <!-- Scatter-matrix CSS -->
    <link type="text/css" rel="stylesheet" href="<?php echo $includes; ?>scatter_matrix_source_files/scatter-matrix.css">

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo $includes; ?>bootstrap_side_bar/css/bootstrap.min.css" rel="stylesheet">
    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->

    <!-- Custom CSS -->
    <link href="<?php echo $includes; ?>bootstrap_side_bar/css/simple-sidebar.css" rel="stylesheet">
    <link href="<?php echo $includes; ?>css/style.css" rel="stylesheet">

    <!-- Various libraries that vA3C depends upon -->
    <link rel="stylesheet" type="text/css" href="<?php echo $includes; ?>va3c_source_files/css/datgui_styleOverride.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $includes; ?>va3c_source_files/css/SPECTACLES.css">
    <script type="text/javascript" src="<?php echo $includes; ?>va3c_source_files/js/libs/dat.gui.js"></script>
    <script type="text/javascript" src="<?php echo $includes; ?>va3c_source_files/js/libs/three.js"></script>
    <script type="text/javascript" src="<?php echo $includes; ?>va3c_source_files/js/libs/OrbitControls.js"></script>
    <script type="text/javascript" src="<?php echo $includes; ?>va3c_source_files/js/libs/Projector.js"></script>
    <script type="text/javascript" src="<?php echo $includes; ?>va3c_source_files/js/libs/stats.js"></script>
    <script type="text/javascript" src="<?php echo $includes; ?>va3c_source_files/js/libs/jquery-ui.js"></script>
    <script type="text/javascript" src="<?php echo $includes; ?>va3c_source_files/js/libs/SPECTACLES.js"></script>


    <!-- Pace for loading bar-->
    <link rel="stylesheet" type="text/css" href="<?php echo $includes; ?>pace/pace.css" />
    <script type="text/javascript" src="<?php echo $includes; ?>pace/pace.min.js"></script>

    <!-- rating system -->
    <script src="<?php echo $includes; ?>rating_source_files/jquery.rating.js" type="text/javascript" language="javascript"></script>
    <link href="<?php echo $includes; ?>rating_source_files/jquery.rating.css" type="text/css" rel="stylesheet" />

    <!-- Design Explorer Scripts -->
    <script src="<?php echo $includes; ?>js/designExplorer.js" type="text/javascript" language="javascript"></script>
    <!-- for clean parameters before new data comes in -->
    
  

    <!-- Google Client Scripts -->
    <!--<script src="<?php echo $includes; ?>js/GoogleClient.js" type="text/javascript" language="javascript"></script>-->

</head>

<script>
    //load FolderInfo ,Json data_ Mingbo Peng Modified--------------------------------------------------------------------

    var _googleReturnObj ={ //{"fileName":Google Drive ID}
        csvFiles:{},
        imgFiles:{},
        jsonFiles:{},
        settingFiles:{}
    }; 
    var _folderInfo = {
        "DE_PW":"",
        "inLink":"",
        "url":"",
        "type":""
    }

    var _userSetting = {
        studyInfo: {
            name:"<?php echo $this->input->get('Title'); ?>",
            date:""
        },
        dimScales:{},
        dimTicks:{},
        dimMark:{}
    }

    function readyToLoad(csvFilePathLink) {
        if (csvFilePathLink.length > 0) {
            // remove the current selection
            unloadPageContent();
            d3.csv(csvFilePathLink, function (data) {
                loadDataToDesignExplorer(data);
                loadSetting();
                updateLayoutOnDeselect();
            } );
            //window.prompt only if user input data, not from URL
            if (document.getElementById("folderLink").value) {
                showStillLink();
            }

            //updateLayoutOnDeselect();

            //d3.select("#welcome").style("display", "none");
        } else {
            alert("You have to put Google Drive Folder information first!");
        }

    }

    function makeUrl(file,type) {
        var link;
        
        // no img link
        if(file === 0 || file === undefined){
            return "https://pylos.zgf.com/<?php echo $includes; ?>base.gif";
        }
 
        if (file.startsWith("http")) {
            // file is a valid full Url path
            // file: http://abcd.com/img01.png      
            link = file;
            
        } else {        
            //loadImgfrom inputs
            // file: img01.png

            if (_folderInfo.type === "userServerLink" ) {  //folder is on user's server
                
                link = _folderInfo.url + file;

            } else {                                       //folder is one GoogleDrive or OneDrive
                
                if(type =="img"){
                    link = _googleReturnObj.imgFiles[file];
                    
                }else if(type =="threeD"){
                    link = _googleReturnObj.jsonFiles[file];
                    
                }else if(type =="setting"){
                    link = _googleReturnObj.settingFiles[file];
                    
                }else if(type =="csv"){
                    link = _googleReturnObj.csvFiles[file];
                    
                }

            }
        }

        return link;
    }


    function showStillLink() {

        var studyCaseLink = encodeUrl(_folderInfo.inLink);
        var studyEncodedUrl = "";

        if (studyCaseLink.length===0) {
            var warningString = "There is no static link for you if you are not using online shared folder.";
            d3.select("#myStudyID").html(warningString);
            $("#showStillLink").modal()
            return;

        }else if (_folderInfo.DE_PW.length>0){
            // if _folderInfo.DE_PW existed, just return.
            studyEncodedUrl = document.location.origin + "/designexplorer/view?ID=" + _folderInfo.DE_PW; 
            d3.select("#showShortUrl").html("https://goo.gl/" +_folderInfo.DE_PW);
            d3.select("#myStudyID").html(studyEncodedUrl); 
            $("#showStillLink").modal();
            return;

        }

        //valid online folder available
        var studyLongUrl = document.location.origin +"/designexplorer/view?ID="+ studyCaseLink;
        d3.select("#welcome").style("display","none");
        
        
        makeUrlId(studyLongUrl, function name(UrlId) {
            _folderInfo.DE_PW = UrlId;
            

            if(UrlId.length ==6){ // google url id
                
                studyEncodedUrl = document.location.origin +"/designexplorer/view?ID="+_folderInfo.DE_PW;
                d3.select("#showShortUrl").html("https://goo.gl/"+ _folderInfo.DE_PW);

            }else{
                studyEncodedUrl = studyLongUrl;
            }

            d3.select("#myStudyID").html(studyEncodedUrl);
            $("#showStillLink").modal()
            
        })
        

    }

</script>

<body >

    <!-- Navbar -->
    <nav class="navbar" style="margin-bottom:0">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#buttons-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <div class="navbar-header" title="Design Explorer">
                    <a href="/designexplorer"> <h1 style="font-weight: 800; text-transform: lowercase; letter-spacing: -0.04em; color: #333; margin-left: 19px;">Design Explorer<?php if ($this->input->get('Title')) { ?> <span style="font-weight: 200;text-transform: none;font-size: 30px;">→  <?php echo $this->input->get('Title'); ?></span><?php } ?></h1><!--<img style="max-width:350px; padding-top: 10px;" src="./img/DesignExplorerLogo3.png">--></a>
                </div>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="buttons-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <!--button to load csv uncommented--
                        <p class="navbar-text" style=" margin-left: 35px; padding-top: 13px;">
                            <button id="showLoadData" class="file-upload" style="border-radius: 20px; background: #eeeeee">Get Data</button> &nbsp;
                            <button id="" onclick="window.location.assign('/designexplorer/create');" class="file-upload" style="border-radius: 20px; background: #eeeeee">Upload a Dataset</button> &nbsp;
                            <button id="" onclick="window.location.assign('/designexplorer');" class="file-upload" style="border-radius: 20px; background: #eeeeee">My Datasets</button>
                        </p>
                        -->
                    </li>
                </ul>
                <div id = "navbar-studyInfo">
                    <!--
                    <h4><?php echo $this->input->get('Title'); ?></h4>
                    <h6><?php echo $this->input->get('Date'); ?></h6>
                    -->
                    <p class="navbar-text" style=" margin-left: 35px; padding-top: 13px;">
                        <button id="showLoadData" class="file-upload" style="border-radius: 20px; background: #eeeeee">Get Data</button> &nbsp;
                        <button id="" onclick="window.location.assign('/designexplorer/create');" class="file-upload" style="border-radius: 20px; background: #eeeeee">Upload a Dataset</button> &nbsp;
                        <button id="" onclick="window.location.assign('/designexplorer');" class="file-upload" style="border-radius: 20px; background: #eeeeee">My Datasets</button>
                    </p>
                </div>
            </div>
            <!-- /.navbar-collapse -->

        </div>
    </nav>

    <div id="wrapper" class="toggled">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <div id="legend-heading" class="sidebar-brand col-xs-12" style="text-align:center"></div>
                <div class="legend col-xs-12" id="legend">
                    <!--place holder for legends-->
                </div>
                <!--<div id="sliders-heading" class="sidebar-brand" style="text-align:center">
                    LOAD YOUR DATA!
                </div>-->
                <div class="inputSliders col-xs-11" id="inputSliders">
                    <form class="sliders">
                        <!-- I will use this form to disable all the sliders together. -->
                    </form>
                </div>
                <div id="sliders-report" class="sidebar-brand col-xs-12" style="text-align:center"></div>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <!--Here starts the Charts row-->
                <div class="col-lg-12">
                    <div class="row">

                        <!--here starts the parallel coordinates row-->
                        <div id="PcChartWrapper" class="col-lg-9">
                            <div class="row">
                                <!-- parallel coordiantes' buttons (reset,remove... ) -->
                                <div class="btn-group col-xs-12" >
                                    <button type="button" class="btn btn-default btn-xs" id="menu-toggle" title="Show sliders">
                                        <span class="glyphicon glyphicon-triangle-left" aria-hidden="true"></span>
                                    </button>
                                    <button type="button" class="btn btn-default btn-xs" id="pcgraph" data-toggle="collapse">
                                        <span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span>
                                    </button>
                                    <button id="reset" class="btn btn-default btn-xs">Reset Selection</button>
                                    <button id="remove" class="btn btn-default btn-xs">Exclude Selection</button>
                                    <button id="keep" class="btn btn-default btn-xs">Zoom to Selection</button>
                                    <button id="savecsv" class="btn btn-default btn-xs" onclick="saveToCSV()">Save Selection to File</button>
                                    <button id="stilllink" class="btn btn-default btn-xs" onclick="showStillLink()">My Static Link</button>

                                    <!-- Change label size -->
                                    <div class="btn-group col-xs-3" style="width: auto;float: right;">
                                        <button id="dataSetting" class="btn btn-default btn-xs" onclick="dataSetting(graph)" title="Setting">Setting</button>
                                        <button id="LL" class="btn btn-default btn-xs" onclick="changeLabelSize('largeLabel')" title="Large Label">L</button>
                                        <button id="MM" class="btn btn-default btn-xs" onclick="changeLabelSize('mediumLabel')" title="Medium Label">M</button>
                                        <button id="SS" class="btn btn-default btn-xs" onclick="changeLabelSize('smallLabel')" title="Small Label">S</button>
                                        <button id="pcFullScreen-toggle" class="btn btn-default btn-xs" title="Full Screen">
                                            <span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </div>
                                
                            </div>
                            <!-- parallel coordinates -->
                            <div class="row-fluid">
                                <div id="graph" class="parcoords collapse in">
                                    <!-- <h1>Parallel coordinates graph</h1> -->
                                </div>
                            </div>

                            
                        </div>
                        <!-- end of left side charts-->

                        <!-- start of right side charts-->
                        <div id="ScChartWrapper" class="col-lg-3">

                            <div class="row-fluid">
                                <div id="rightTopButtonGroup" class="btn-group col-xs-12">
                                    <button type="button" class="btn btn-default btn-xs" id="scatter-fullscreen-toggle" title="Full Screen">
                                        <span class="glyphicon glyphicon-triangle-left" aria-hidden="true"></span>
                                    </button>
                                    <a id="copyRightButton" href="//pylos.zgf.com/" class="btn btn-default btn-xs" target="_blank" style="width: calc(100% - 46px);">
                                        Version 2020-03-14
                                    </a>
                                    <!--<button type="button" class="btn btn-default btn-xs" id="rcgraph" data-toggle="collapse" data-target="#radarChart">
                                        <span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span>
                                    </button>-->
                                    <button type="button" class="btn btn-default btn-xs" id="scatter-menu-toggle" title="Show Dimensions">
                                        <span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>

                            <div class="row chartSide">

                                <div id="radarChart" class="col-lg-12 collaps in center-block" style="font-size:14px; font-weight:bold">
                                    <!-- // <h5>Radar Chart Place Holder</h5> -->
                                </div>
                                <!--<div id="scatterChart" class="col-lg-12 collaps in center-block">
                                        place holder for the scatterChart
                                </div>-->
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row-fluid col-lg-12">


                    <!-- Zoomed div for zoomed in areas -->
                    <div class="col-lg-9" id="zoomedArea" style="height:0px">
                        <div class="panel-group Spectacles_attributeList" id="IterationData" style="display: none;right: auto;bottom: 1%;height: auto; width: auto;min-width: 280px;">
                                <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse1">Attributes</a>
                                    </h4>
                                </div>
                                <div id="collapse1" class="panel-collapse collapse in">
                                    <div class="panel-body"><p>data</p></div>
                                </div>
                            </div>
                        </div>
                        <div class="pull-right" id="vis">
                            <form>

                                <label><input type="radio" class="toggleView" name="visMode" value="2D" checked> 2D</label>
                                <!-- show 2D image on load-->
                                <label id="threeDLabel"><input type="radio" class="toggleView" name="visMode" value="3D" id="threeDRadio"> 3D</label>

                                <button type="button" class="btn btn-default btn-xs hide" id="toggleComments" title="Add Comments">
                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                </button>
                                <button type="button" class="btn btn-default btn-xs" id="fullscreentoggle" title="Full Screen Toggle">
                                    <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                                </button>
                                <button type="button" class="btn btn-default btn-xs"title="Unselect" onclick="updateLayoutOnDeselect()">
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                </button>

                            </form>
                            <form>
                                <!--rating div-->
                                <input class="star" type="radio" name="rating" value="1" />
                                <input class="star" type="radio" name="rating" value="2" />
                                <input class="star" type="radio" name="rating" value="3" />
                                <input class="star" type="radio" name="rating" value="4" />
                                <input class="star" type="radio" name="rating" value="5" />
                            </form>
                            <form class="dropdown imgSwitch"></form>

                        </div>
                        <div id="viewer3d" style="width:100%; height:100%" class="hidden">
                            <!-- place holder for the viewer -->
                        </div>
                        <div id="viewer2d" style="width:100%; height:100%">
                            <!-- place holder for the image -->
                            <img>
                        </div>
                    </div>
                    

                    <!--Here starts the thumbnails-btm_container-->
                    <div class="col-lg-12" id="thumbnails-btm_container">
                        <div class="col-lg-12 sorting"></div>
                        <div class="col-lg-12" id="thumbnails-btm"></div>
                    </div>


                </div>
            </div>
            <!-- /#Welcome content -->
            <div class="row-fluid col-lg-12 row-centered vertical-center fullscreen popupWindow" id="welcome">
                <div class="col-sm-12 col-centered" id="welcomeContainer">
                    <button class="btn btn-default btn-sm pull-right popupWindowClose">
                        <span class="glyphicon glyphicon-remove"></span>
                    </button>
                    <br>
                    <h1 class="text-center" style="margin-top: 1em;"> Welcome to Design Explorer!</h1>
                    <h4 class="text-center">
                        Design Explorer is an open source tool for exploring multi-dimensional parametric studies!
                    </h4>
                    <br>

                    <h5 class="text-center">Try one of following sample design sets or load your own data.<a href="http://www.mpendesign.com/category/tutorial/" target="_blank"> (Tutorial)</a></h5> 
                    <h5 class="text-center"></h5>
                    <div class="row">
                        <div class="col-sm-4">
                            <img src="<?php echo $includes; ?>img/RedBox.gif" class="sampleImage image-rounded" id="sample1">
                        </div>
                        <div class="col-sm-4">
                            <img src="<?php echo $includes; ?>img/Daylighting.gif" class="sampleImage image-rounded" id="sample2">
                        </div>
                        <div class="col-sm-4">
                            <img src="<?php echo $includes; ?>img/BoxBuilding.gif" class="sampleImage image-rounded" id="sample3">
                        </div>
                        <br>
                        <br>
                    </div>
                    <div class="col-lg-12" style="position: absolute;bottom: 2em;width: calc(100% - 60px);">
                        <h3 class="text-center"> Happy Exploring! </h3>
                        <h5 class="text-center">v3 (27 August 2018)</h5>
                        <br>
                        <h5 class="text-center">
                            &copy; CORE studio | Thornton Tomasetti |
                            <a href="http://core.thorntontomasetti.com/" target="_blank"> Original Developer</a> |
                        </h5>
                        <h5 class="text-center">
                            Updated by <a href="http://seanwittmeyer.com" target="_blank">Sean Wittmeyer</a> and Written by
                            <a href="http://www.mpendesign.com" target="_blank">Mingbo Peng</a>
                        </h5>
                    </div>
                </div>

                <!--load data div-->
                <div class="col-sm-12 col-centered" id="loadDataContainer" style=" display:none;width: 700px;height: 500px">
                    <button class="btn btn-default btn-sm pull-right popupWindowClose">
                        <span class="glyphicon glyphicon-remove"></span>
                    </button>

                    <h4 class="text-center" style="margin: 100px 0 30px 0;">
                        From the cloud:
                    </h4>
                    <p class="text-center">
                        <input type="text" id="folderLink" class="form-control" style="display: inline-block;" name="imgfolder" placeholder="GoogleDrive, OneDrive, or server link"
                        />
                        <input type="text" id="folderLinkID" class="btn btn-default btn-hd" name="imgfolder" placeholder="FolderID" />
                        <button id="loadData" onclick="MP_getGoogleIDandLoad()" class="file-upload" style="border-radius:0 20px 20px 0; background: #eeeeee; height: 31px;">Load Data</button>
                    </p>
                    <h5 class="text-center" style="color: #9d9d9d;">Please put data.csv, images, and 3D-json files into the same folder.(<a href="<?php echo $includes; ?>design_explorer_data/example/online/example.zip"
                            target="_blank">example.zip</a>)</h5>
                    
                    <div id="loadFileLocally">
                        <h4 class="text-center" style="margin: 60px 0 60px 0;">
                            Or
                        </h4>
                        <p class="text-center">
                            <input type="file" id="csv-file" class="btn btn-default btn-sm" name="files">
                        </p>
                        <h5 class="text-center" style="color: #9d9d9d;">Please put valid images and 3D-json files URL. (Example:<a href="<?php echo $includes; ?>design_explorer_data/example/LocalCSV_example.csv" target="_blank">data.csv</a>)</h5>
                    </div>
                    
                </div>

            </div>

            <!--show static link-->
            <!-- Modal -->
            <div class="modal fade" id="showStillLink" role="dialog">
                <div class="modal-dialog">
                
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Static link</h4>
                    </div>
                    <div class="modal-body"> 
                        <h5 >Link for share </h5>
                        <p >
                            <code id="myStudyID">Not available</code>
                            <button id="copy-link" class="btn btn-xs btn-default" onclick="CopyToClipboard('#myStudyID')" data-toggle="tooltip" data-placement="bottom" title="Click to copy the link!">
                                <span class="glyphicon glyphicon-share"></span>
                            </button>
                        </p> 
                        <h5 >Short link </h5>
                        <p >
                            <code id="showShortUrl">Not available</code>
                            <button id="copy-showShortUrl" class="btn btn-xs btn-default " onclick="CopyToClipboard('#showShortUrl')" data-toggle="tooltip" data-placement="bottom" title="Click to copy the link!">
                                <span class="glyphicon glyphicon-share"></span>
                            </button>
                        </p> 
                        
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                
                </div>
            </div>

            <!--data setting -->
            <div class="row-fluid col-lg-12 row-centered vertical-center fullscreen popupWindow" id="showSetting" style=" display:none">
                <div class="col-sm-12 col-centered" id="dataSetting" style="width: 700px;height: 600px">
                    <button class="btn btn-default btn-sm pull-right popupWindowClose">
                        <span class="glyphicon glyphicon-remove"></span>
                    </button>

                    <h1 style="margin: 30px 0 10px 0;">Setting</h1>
                    <h6 style="margin: 0 30px 30px 0;">All settings will not be effective once this page is reloaded, unless save the setting file with data.csv on the cloud.</h6>

                    <div class="panel-group" id="dimSettingList">
                        <!--placeholder for scale setting list-->
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#dimSettingList" href="#settingStudyInfo">Study Information</a>
                                </h4>
                            </div>
                            <div id="settingStudyInfo" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <ul class="list-group">
                                        <li class="list-group-item form-inline">Study Name: <input id="studyNameInput" class="form-control input-sm" type="text" style="width: 80%"></li>
                                        <li class="list-group-item form-inline">Study Date: <input id="studyDateInput" class="form-control input-sm" type="text" style="width: 80%;    margin-left: 1.1em;"></li>
                                        <li class="list-group-item form-inline">Password: <input class="form-control input-sm" placeholder="WIP" type="text" style="width: 40%; margin-left: 1.6em;" disabled><input class="form-control input-sm" placeholder="Confirm" type="text" style="width: 39%;" disabled></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!--buttons for setting list-->
                    <div class="col-xs-6 col-md-offset-3" style="margin-top: 1.5em;">
                        <button id="setUserSetting" type="button" class="btn col-xs-4 col-md-offset-1">Set</button>
                        <button id="saveUserSetting" type="button" class="btn col-xs-4 col-md-offset-1" data-toggle="tooltip" data-placement="bottom" title ="Export the setting file, and save to where data.csv located!">Save</button>
                    </div>

                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->


    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo $includes; ?>bootstrap_side_bar/js/bootstrap.min.js"></script>

    <!-- Hide sliders if user asked for a stripped down version -->
    <script type="text/javascript">
        // A dictionary to carry users input to customize the interface
        var inputParameters = {
            "min": false
        }; // currently only a single item, other options to be added later

        // get url parameters
        // current valid key is on "min" for minimum interface
        function parseUri() {
            var pars = window.location.search.replace("?", "").split("&");
            if (pars[0] === "") return null;
            pars.forEach(function (d) {
                keyValue = d.split("=");
                inputParameters[keyValue[0].toLowerCase()] = keyValue[1].toLowerCase();
            });
        }

        // read optional url parameters
        parseUri();

        if (inputParameters.min == 'true') {
            // remove the button so sliders are hidden
            d3.selectAll("button#menu-toggle").remove();
        }

    </script>

    <!-- welcome div  -->
    <script type="text/javascript">
        sampleFiles = {
            1: "<?php echo $includes; ?>design_explorer_data/LittleRedBox.csv",
            2: "<?php echo $includes; ?>design_explorer_data/default_onload.csv",
            3: "<?php echo $includes; ?>design_explorer_data/AIA building.csv"
        };

        sampleSettingFiles = {
            1: "<?php echo $includes; ?>design_explorer_data/RedBox/settings.json",
            2: "<?php echo $includes; ?>design_explorer_data/DefaultData/settings.json",
            3: "<?php echo $includes; ?>design_explorer_data/AIAbuilding/settings.json"
        };

        d3.selectAll(".sampleImage")
            .on("click", function () {
                var selSample = d3.select(this);
                var id = selSample[0][0].id.replace("sample", "");

                var filePath = sampleFiles[id];
                var settingFilePath =sampleSettingFiles[id];

                // remove the current selection
                unloadPageContent();

                d3.select("div#welcome").style("display", "none");

                // load the new file
                d3.csv(filePath, function (data) {
                    loadDataToDesignExplorer(data);
                    loadSetting(settingFilePath);
                    });

            });

    </script>

    <!-- right side chart -->
    <script type="text/javascript">
        // update radar chart
        var scatterChart;

        // add scatter chart
        function addScatterChart() {
            var pcHeight = d3.select("#graph").style("height").replace("px", "");
            var rightChartWidth = d3.select("#radarChart").style("width").replace("px", "");
            var selectedData;
            if (graph.brushed().length > 0) {
                selectedData = graph.brushed();
            } else {
                selectedData = graph.data();

            }
            var sm = new ScatterMatrix("", selectedData, '#radarChart');
            sm.cellSize((pcHeight - 60) / 2);
            sm.render();

            // adjust the chart style
            //var chartWidth = d3.select(".scatter-matrix-svg svg").style("width").replace("px", "");
            d3.select(".scatter-matrix-svg")
                .style("width", pcHeight - 10 + "px");

            d3.select(".scatter-matrix-control")
                .style("height", pcHeight - 30 + "px");
            
            scatterChart = sm;

            //return sm;

        }

        //unique id for each Circle
        function getCircleID(d) {
            var id;
            var keys = d3.keys(graph.data()[0]);
        
            keys.forEach(function(key) {
                id = "scatter_" + cleanString(d[key].toString());
            });
        
            return id;
        }

        function updateScatterChart() {
            var data = graph.brushed().length > 0 ? graph.brushed() : graph.data();

            d3.selectAll(".cell circle")
                .classed("faded", false)
                .attr("visibility", "hidden");

            data.forEach(function (d) {
                d3.selectAll("#" + getCircleID(d)).attr("visibility", "shown");
            });

        }

        function highlightScatterDot() {
            var data = graph.highlighted();
            if (data.length > 0) {

                d3.selectAll(".cell circle")
                    .classed("faded", true);

                unlighlightScatter();

                data.forEach(function (d) {
                    d3.selectAll("#" + getCircleID(d))
                        .classed("highlighted", true);
                });

            }

        }

        function unlighlightScatter() {
            d3.selectAll(".cell circle")
                .classed("highlighted", false);

        }

    </script>

    <!-- Dynamic Divs script -->
    <script>
        var isAnyItemSelected = false;
        var isToggled = true;
        var isScatterToggled = true;
        var isLeftChartFullScreenToggled = false;
        var isRightChartFullScreenToggled = false;


        // side bar for sliders
        $("#menu-toggle").click(function (e) {
            e.preventDefault();

            //$("#wrapper").toggleClass("toggled");
            isToggled = !isToggled;
            d3.select("#wrapper")
                .classed("toggled", isToggled)
                .transition().duration(300); //length of toggle animation
            //.each("end", resizeRadarChart())
        });


        $("#scatter-menu-toggle, .scatter-matrix-control").click(function (e) {

            isScatterToggled = !isScatterToggled;
            d3.select(".scatter-matrix-control")
                .classed("toggled", isScatterToggled);
        });


        //--------------------------------------------------set right side chart full screen -----------------------------------//

        var divWidth = d3.select("#ScChartWrapper").style("width").replace("px", "");

        $("#scatter-fullscreen-toggle").click(function (e) {
            isRightChartFullScreenToggled = !isRightChartFullScreenToggled;

            d3.selectAll(
                    "#ScChartWrapper," +
                    ".chartSide,"+
                    ".col-lg-3 .row-fluid,"+
                    ".thumbnailSide,"+
                    "#copyRightButton, "+
                    ".scatter-matrix-container circle,"+
                    " #PcChartWrapper, "+
                    "#thumbnails-btm_container, "+
                    "#thumbnails-btm_container #thumbnails-btm, "+
                    " #zoomedArea"
                )
                .classed("rightChartFullScreenToggled", isRightChartFullScreenToggled);

            if (isRightChartFullScreenToggled) {
                //var newDim =  windowHeight - 200;
                d3.select("#thumbnails-btm_container").style("height", cleanHeight + "px");
            } else {
                d3.select("#thumbnails-btm_container").style("height", zoomedHeight+"px");
            }


        });

        function resizeScChart(newWidth, newHeight) {


            d3.select(".scatter-matrix-svg") // for centering the chart
                .style("width", newWidth + "px");

            d3.select(".scatter-matrix-control")
                .style("height", newHeight + "px");
        }



        // update layout on select
        function updateLayoutOnSelect() {

            var selectedImage = d3.select(this);

            // highlight the line graph
            graph.highlight(selectedImage.data());


            // update rating based
            setStarValue(selectedImage.data()[0].Rating);


            // show zoomed area
            var thumbnailsHeight =  d3.select("#thumbnails-btm_container").style("height").replace("px", "");
            d3.select("#zoomedArea")
                .style("height", thumbnailsHeight + "px");
            

            // move btm thumbnail to right
            d3.select("#thumbnails-btm_container")
                    .classed("onRight",true);

            // push the image to zoomed viewer2d area
            d3.select("#viewer2d")
                .select("img")
                .data(selectedImage.data())
                .attr("src", makeUrl(selectedImage.datum()[imageLinkKeys[0]],"img"))
                .attr("title", getTitle);
                
            addImgSwitch(selectedImage);
            updateIterationData(selectedImage.data());


            // load 3d geometry if the view is set to 3D
            if (currentView == "3D") loadNew3DModel();

            if (!isAnyItemSelected) {
                // if there is a brushed selection
                // update the range of the sliders
                updateSlidersTickValues();
            }

            // update the values for slider
            updateSlidersValue(graph.highlight()[0]);

            // enable sliders
            enableAllSliders();

            //highlight the radar chart  //-----TODO:here needs a switch for different charts-------
            //rc.highlight(selectedImage.data());
            highlightScatterDot();


            // var radarHeight = d3.select("#radarChart")
            //     .style("height").replace("px", "");

            // let's side thumbnail show up
            d3.select("#thumbnails-side_container")
                .transition().duration(300)
                .style("height", function () {

                    if (isRightChartFullScreenToggled) {
                        return d3.select(".scatter-matrix-svg").style("width").replace("px", "") - 20 + "px";
                    } else {
                        return zoomedHeight + "px";
                    }
                    //console.log(isRightChartFullScreenToggled);

                });

            isAnyItemSelected = true;

        }

        function addImgSwitch(selectedData){
            

            if(imageLinkKeys.length <=1){
                d3.select(".imgSwitch").classed("hidden", true)
                return;
            }else{
                d3.select(".imgSwitch").classed("hidden", false)
                //remove current first
                d3.selectAll(".imgSwitch select").remove();

                //add
                var select = d3.select(".imgSwitch")
                .append("select")
                .attr("class", "btn btn-default btn-xs");

                 // add img options
                select.selectAll("option")
                    .data(imageLinkKeys)
                    .enter()
                    .append("option")
                    .attr("value", function (d) {
                        return d;
                    })
                    .text(function (d) {
                        return d;
                    });

                // add change event to show images
                select.on("change", function (d) {
                    imgKey = d3.select(this).property("value");

                    // update the value in other dropdown menu
                    d3.selectAll("select option").filter(function (d) {
                        return d == imgKey;
                    }).attr("selected", "true");

                    d3.select("#viewer2d img")
                        .attr("src", makeUrl(selectedData.datum()[imgKey],"img"));
                });
            }

           
        }
       
        // function viewer2dNextImg(imgKeyList, currentImgKey){

        //     if(imgKeyList.length>=1){
        //         var currentImgIndex = imgKeyList.indexOf(currentImgKey);
                
        //         if(currentImgIndex+1<imgKeyList.length){

        //             nextImgKey = imgKeyList[currentImgIndex+1];

        //         }else{
        //             nextImgKey = imgKeyList[0];
        //         }

        //         return nextImgKey;

        //     }

        // }


        // update layout on select
        function updateLayoutOnDeselect() {

            // find height of parallel coordinate
            // to find the height for thumbnail div
            var pcHeight = d3.select("#graph")
                .style("height").replace("px", "");

            // hide zoomed area
            d3.selectAll("#zoomedArea")
                .transition().duration(300)
                .style("height", "0px");

            // show btm thumbnail
            d3.select("#thumbnails-btm_container")
                // .attr("class","col-lg-12")
                    // .select("#thumbnails-btm")
                    .classed("onRight",false);

            // // hide side thumbnail
            // d3.select("#thumbnails-side_container")
            //     .transition().duration(1500)
            //     .style("height", "0px");


            d3.select("#IterationData")
                .style("display", "none");

            // unhighlight the line graph
            graph.unhighlight();

            // enable sliders
            disableAllSliders();

            // unhighlight the radar chart        //-----TODO:here needs a switch for different charts-------//
            //rc.unhighlight();
            unlighlightScatter();

            //show radar chart
            $("#radarChart").collapse("show");

            isAnyItemSelected = false;
        }

        // hide/show parallel coordinates
        d3.select("#pcgraph").on("click", function () {
            if (cleanedData.length === 0) return; // don't let the user change the size before loading the file

            var height = d3.select("#graph").style("height").replace("px", "");

            if (height > 0) { 
                // collapse
                d3.select("#graph")
                    .attr("class", "parcoords collapse 2s")
                    .style("height", "0px");

                //mirror collapse behavior with radar chart
                $("#radarChart").collapse("hide");

                //disable buttons
                d3.select("#reset").classed("disabled", true);
                d3.select("#remove").classed("disabled", true);
                d3.select("#keep").classed("disabled", true);

                if (graph.highlight().length !== 0) {
                    d3.selectAll("#zoomedArea")
                            .style("height", cleanHeight + "px");
                }

                d3.select("#thumbnails-btm_container").style("height", cleanHeight + "px");

            } else {
                // collapse in
                var pcHeight = 0;
                if (isLeftChartFullScreenToggled) {
                    pcHeight = (windowHeight - 109) * 0.5-50;
                }else{
                    pcHeight = graphHeight;
                }
                d3.select("#graph")
                    .transition("ease out").attr("class", "parcoords collapse in")
                    .style("height", pcHeight + "px");
                

                 //mirror collapse behavior with radar chart
                $("#radarChart").collapse("show");

                //enable buttons
                d3.select("#reset").classed("disabled", false);
                d3.select("#remove").classed("disabled", false);
                d3.select("#keep").classed("disabled", false);

                if (graph.highlight().length !== 0) {
                    d3.selectAll("#zoomedArea")
                            .style("height", zoomedHeight + "px");
                }
                d3.select("#thumbnails-btm_container").style("height", zoomedHeight + "px");
                
            }
        });


    </script>

    <!-- process input data -->
    <script type="text/javascript">
        // this script imports the csv file and does the initial data processing
        // such as separating inputs, outputs, images, etc.
        var hasImgLink = true;
        var has3DLink = false;

        function analyzeInputData(originalData) {
            var keys = d3.keys(originalData[0]);
            //get keys []   
            keys.forEach(function (key) {
                key = key.trim().replace(".", " ");

                if(key.toUpperCase().startsWith("IMG"))
                {
                    imageLinkKeys.push(key);
                }
                else if(key.toUpperCase().startsWith("THREED")){
                    //do nothing
                }
                else{
                    var cleanKey = key.trim().replace(".", " ").replace("in:", "").replace("out:", "");
                    cleanedKeys4pc[cleanKey] = {};
                }

            })

            originalData.forEach(function (row, rowCount) {

                var inputParams = {},
                    outputParams = {},
                    otherParams ={},
                    imgParams ={},
                    threeDParams ={},
                    cleanedParams = {};

                keys.forEach(function (key, i) {
                    var cleanKey = key.trim().replace(".", " ");

                    if (cleanKey.toUpperCase().startsWith("IN:")) {
                        cleanKey = cleanKey.slice(3);
                        inputParams[cleanKey] = row[key];

                    } else if (cleanKey.toUpperCase().startsWith("OUT:")) {
                        cleanKey = cleanKey.slice(4);
                        outputParams[cleanKey] = row[key];

                    } else if (cleanKey.toUpperCase().startsWith("IMG")) {

                        imgParams[cleanKey] = row[key];

                    } else if (cleanKey.toUpperCase().startsWith("THREED")) {
                        
                        threeDParams[cleanKey] = row[key];

                    } else {
                        otherParams[cleanKey] = row[key];
                    }
                });

                Object.assign(cleanedParams,inputParams,outputParams,otherParams,imgParams,threeDParams);

                var id = getCaseId(inputParams);
                ids.push(id);

                allDataCollector[id] = {};
                allDataCollector[id].cleanedParams = cleanedParams;

                inputData.push(inputParams);
                outputData.push(outputParams);
                cleanedData.push(cleanedParams);
                
            });


            // add rating of 0 to all the options if not already there
            if (keys.indexOf("Rating") == -1) {
                cleanedKeys4pc.Rating = {};
                cleanedData.forEach(function (d) {
                    d.Rating = 0;
                });
            }

            //check imageLinkKeys array if it is empty
            if (imageLinkKeys.length ==0) { 
                hasImgLink = false;
                d3.select("#thumbnails-btm_container").remove();
            }else{
                hasImgLink = true;
                
            }

            //remove threeD radio
            if (keys.indexOf("threeD") == -1) { 
                has3DLink = false;
                d3.select("#threeDLabel").classed("hidden",true);
            }else{
                has3DLink = true;
                d3.select("#threeDLabel").classed("hidden", false);
            }

        }


        function getCaseId(inputParams) {
            // create the id based on inputs
            var params = [];
            d3.keys(inputParams).forEach(function (key) {
                // remove spaces
                //var k = key.replace(/\s/g, '');
                // convert to numbers and back to string
                // so zeros doesn't make inconsistency
                var value;
                if (isNaN(inputParams[key])) {
                    value = inputParams[key];
                } else {
                    value = parseFloat(inputParams[key]);
                }

                // add them to params
                //params.push(k);
                params.push(value);
            });

            // join all of them together
            var id = params.join("");
            return id;
        }

        function prepareSlidersInfo() { // modified 6/7/2016 by Mingbo, this is only for input information.
            var sliderNames = d3.keys(inputData[0]);
            var tickValues = {};
            var originalTickValues = {}; // keep track of original values

            // create an object with place holder for tickValues
            sliderNames.forEach(function (name) {
                originalTickValues[name] = [];
                tickValues[name] = [];
                slidersMapping[name] = {};
            });

            // find tick values
            inputData.forEach(function (d, i) {
                sliderNames.forEach(function (name) {
                    // check if the value is already in list - add it to the list if not
                    if (tickValues[name].indexOf(d[name]) == -1) {
                        slidersMapping[name][d[name]] = []; // create an empty list for this value
                        tickValues[name].push(d[name]);
                        originalTickValues[name].push(d[name]);
                    }

                    //add the new combination to the list
                    slidersMapping[name][d[name]].push(cleanedData[i]);
                });
            });


            // sort values and collect them
            sliderNames.forEach(function (name) {
                //var tValues = tickValues[name].sort();
                var tValues = Object.keys(slidersMapping[name]);
                var oValues = tValues; //it is equal at the beginning
                slidersInfo.push({
                    name: name,
                    originalTickValues: oValues,
                    tickValues: tValues
                });

            });

            // take care of whitespace in slider names! oh people...
            slidersInfo.forEach(function (d) {
                d.namewithnospace = string_as_unicode_escape(d.name);
            });
        }

        function string_as_unicode_escape(input) {
            var output = '';
            for (var i = 0, l = input.length; i < l; i++)
                output += input.charCodeAt(i).toString(16);

            return output;
        }

    </script>

    <!-- NOTE:  draw parallel coordinates graph -->
    <script type="text/javascript">
        var graph;
        //set up heights of divs
        var opacity = 0.4;


        var pcIsColoredBy;

        var color = d3.scale.linear()
            .range(["#3182bd", "#f33"]);


        var scatterHeight = d3.select("#ScChartWrapper").style("height").replace("px", "");
        window.addEventListener("resize", reDrawPcChart);

        //redraw the charts when window size changes
        function reDrawPcChart () { 
            calWidthAndHeight();

            var newGraphWidth = windowWidth*0.75-30; // (col-lg-9 x0.73) - (boside padding 15*2)
            // var newGraphWidth = windowWidth-scatterWidth-90;
            var newGraphHeight = graphHeight;

            if (newGraphHeight< scatterHeight) {
                newGraphHeight = newGraphHeight;
            }

            if (isLeftChartFullScreenToggled) {
                newGraphHeight = (windowHeight - 85) * 0.5-50;
                newGraphWidth = windowWidth - 30;  

            }

            if(windowWidth<1200){
                newGraphWidth = windowWidth-30;
            }

            resizePcChart(newGraphWidth,newGraphHeight);

            var zoomedAreaHeight = d3.select("#zoomedArea").style("height").replace("px", "");
            if(zoomedAreaHeight >1){
                d3.selectAll("#thumbnails-btm_container, #zoomedArea")
                    .style("height", zoomedHeight + "px");
            }else{
                d3.select("#thumbnails-btm_container")
                    .style("height", zoomedHeight  + "px");
            }            
            //console.log(graphHeight+"x"+zoomedHeight);

        };


        function drawParallelCoordinates() {

            // remove Rating for on page load
            var iniDimensions = d3.keys(cleanedKeys4pc).filter(function (d) {
                return (d != "Rating") && (d != "Description");
            }).reduce(function (acc, cur) {
                acc[cur] = {};
                return acc;
            }, {});


            // set the height for graph div - this is critical otherwise
            // parallel coordinates graph will be drawn by height of 0px
            d3.select("#graph").style("height", graphHeight + "px");

            // draw parallel coordiantes chart
            // click event should highlight the image in thumbnails and show it in zoomed
            // brush should filter thumbnails
            // get parallel coordinates

            graph = d3.parcoords()('#graph')
                .data(cleanedData)
                .margin({
                    top: 50,
                    left: 20,
                    bottom: 10,
                    right: 20
                })
                .alpha(opacity)
                .mode("queue")
                .rate(40)
                .dimensions(iniDimensions)
                .render()
                .brushMode("1D-axes") // enable brushing
                .interactive()
                .reorderable();

            updateLabels();

        }

        // update pc chart labels and axis color based on input and outputs parameters
        function updateLabels() {
            // modify labels
            var inputLabelColor = "black",
                outputLabelColor = "steelblue",
                inputLabels = d3.keys(inputData[0]),
                outputLabels = d3.keys(outputData[0]),
                colorset = []; //color set for lables

            d3.selectAll("text.label")
                .style("fill", function (d) {
                    // change colors for inputs vs outputs
                    if (inputLabels.indexOf(d) != -1) {
                        colorset.push(inputLabelColor);
                        return inputLabelColor; // it is an input

                    } else if (outputLabels.indexOf(d) != -1) {
                        colorset.push(outputLabelColor);
                        return outputLabelColor; //it's an output

                    } else {
                        // undefined
                        colorset.push("black");
                        return "black";
                    }
                });


            //modify axes
            d3.selectAll("path.domain")
                .style("stroke", function (d, i) {
                    return colorset[i];
                });


            setupPcLabelEvent();

        }

        // click label to activate coloring
        function setupPcLabelEvent() {
            d3.selectAll(".dimension")
                .on("click", function (d) {
                    pcIsColoredBy = d;
                    update_colors(d);
                });
        }


        function valueToNumber(value, dim) {
            if (graph.dimensions()[dim] !== undefined) {
                return graph.dimensions()[dim].yscale(value);
            } else {
                return 1;
            }

        }

        // update color and font weight of chart based on axis selection
        function update_colors(dim, chart) {
            pcIsColoredBy = dim;

            //console.log(dim);

            // change color of lines
            // set domain of color scale
            var values = graph.data().map(function (d) { 
                    return parseFloat(valueToNumber(d[dim], dim)); 
                });
            
            var highlightedData = graph.highlighted();
            
            
            colorDomain = color.domain(d3.extent(values));

            // change colors for each line
            graph.color(function (d) {
                return color([valueToNumber(d[dim], dim)]);
            }).render();

            //keep highlighted data
            if (highlightedData.length>0) {
                graph.highlight(highlightedData)
            } 
            

            // change border color for images
            d3.selectAll("img#thumbnails-btm, img#thumbnails-side").style("border-color", function (d) {
                //console.log(color(yscale(d[dim])));
                return color(valueToNumber(d[dim], dim));
            });
            //d3.selectAll("img#thumbnails-side").style("border-color", function(d) {
            //    return color(yscale(d[dim]));
            //});

            // change ScatterMatrix dots color
            update_sc_colorsOnly();

        }

        //update_sc_colorsOnly
        function update_sc_colorsOnly() {

            d3.selectAll(".cell circle").style("fill", function (d) {

                return color(valueToNumber(d[pcIsColoredBy], pcIsColoredBy));
            });

        }

    </script>

    <!-- draw legend -->
    <script type="text/javascript">
        function drawLegend() {

            // add text
            //d3.select("#legend-heading").text("LEGEND");

            // get width and height for legend area
            var legendWidth = d3.select("#legend").style("width").replace("px", "");
            var legendHeight = d3.select("#sidebar-wrapper").style("height").replace("px", "") / 3;

            // legend boxes are diminsions of the graph
            //console.log(Object.keys(graph.dimensions()));
            dimensions = d3.keys(cleanedKeys4pc).map(function (d) {
                return {
                    name: d,
                    enabled: true
                };
            });

            // find size of the square based on the height
            var keyCounts = d3.keys(cleanedKeys4pc).length;
            //var legendSquareSize = 16;
            var legendSquareSize = Math.max(legendHeight / (keyCounts + 4), 12);


            var legend = d3.select("#legend").style("height", legendHeight+"px");

            // create one group for each rect and text
            var legend = d3.select("#legend").append("svg")
                .attr("width", legendWidth)
                .attr("height", legendSquareSize*(keyCounts+10))
                .selectAll("g")
                .data(dimensions)
                .enter().append("g")
                .attr("transform", function (d, i) {
                    return "translate(10," + ((legendSquareSize + 3) * i) + ")";
                });

            legend.append("rect")
                .attr("width", legendSquareSize)
                .attr("height", legendSquareSize)
                .attr("class", "legend")
                .style("fill", "grey")
                .style("stroke", "black")
                .style("stroke-width", 1)
                .style("cursor", "pointer");

            legend.append("text")
                .attr("x", legendSquareSize + 6)
                .attr("y", legendSquareSize / 2)
                .attr("dy", ".35em")
                .text(function (d) {
                    return d.name;
                });
        }

    </script>

    <!-- add sorting dropdowns -->
    <script type="text/javascript">
        function addSortDropdowns() {

            sortBy = d3.keys(graph.dimensions())[0]; // will be used to sort thumbnail images
            ascending = true;

            var select = d3.select(".sorting")
                .text("Sort by: ")
                .append("select")
                .attr("class", "btn btn-default btn-xs");

            // add options based on input and outputs
            select.selectAll("option")
                .data(d3.keys(graph.dimensions()))
                .enter()
                .append("option")
                .attr("value", function (d) {
                    return d;
                })
                .text(function (d) {
                    return d;
                });

            // add change event to resort images
            select.on("change", function (d) {
                sortBy = d3.select(this).property("value");

                // update the value in other dropdown menu
                d3.selectAll("select option").filter(function (d) {
                    return d == sortBy;
                }).attr("selected", "true");

                updateImageGrid(graph.brushed(), false);
            });

            // add option to reverse sorting order
            var sortingBtn = d3.selectAll(".sorting")
                .append("button")
                .attr("type", "button")
                .attr("class", "btn btn-default btn-xs")
                .attr("id", "reverseSortOrder")
                .attr("title", "Reverse sorting order");

            // append icon
            sortingBtn.append("span")
                .attr("class", "glyphicon glyphicon-circle-arrow-up")
                .attr("aria-hidden", "true");

            sortingBtn.on("click", function () {
                ascending = !ascending;

                sortingBtn.selectAll("span")
                    .attr("class", function () {
                        return ascending ? "glyphicon glyphicon-circle-arrow-up" :
                            "glyphicon glyphicon-circle-arrow-down";
                    });

                updateImageGrid(graph.brushed(), false);
            });
        }

    </script>

    <!-- add thumbnail images to both thumbnail divs-->
    <script type="text/javascript">
        function getTitle(data) {

            // get title for each image
            var title = [];

            d3.keys(graph.dimensions()).forEach(
                function (key) {
                    var info = String(key) + ":" + data[key];
                    title.push(info);
                }
            );

            return title.join("\n");
        }



        function updateIterationData(dataObj) {

            // get title for each image
            var title = [];
            var data = dataObj[0];
            d3.keys(data).forEach(
                function (key) {
                    if (!String(key).startsWith("img") && !String(key).startsWith("threeD") && !String(key).startsWith(
                            "scid")) {
                        var info = '<strong>' + key + '</strong>' + " : " + data[key];
                        title.push(info);
                    }

                }
            );

            d3.select("#IterationData")
                .style("display", "block")
                .select('p')
                .html(title.join('<br>'));
        }

        function updateImageGrid(data, resizeImageDiv) {

            // this is based on bootstraps 12 column grid
            // we can replace it with a fancier version later
            var gridSizes = [1, 2, 3, 4, 6];
            var fgridSize = 1; //gridSizes[gridSize];

            if (!data) data = graph.data(); //if data is false then use graph data
            //var xFloat = parseFloat(valueToNumber(, sortBy));
            //var yFloat = parseFloat(valueToNumber(y[sortBy], sortBy));
            // sort data based on dropdown selecion
            if (ascending) {
                data.sort(function (x, y) {
                    return d3.ascending(x[sortBy], y[sortBy]);
                });
            } else {
                data.sort(function (x, y) {
                    return d3.descending(x[sortBy], y[sortBy]);
                });
            }

            // remove all the current divs
            // I think eventually I will change this to a filter function
            d3.select("#thumbnails-btm").selectAll("div").remove();
            //d3.select("#thumbnails-side").selectAll("div").remove();

            // var pcHeight = d3.select("#graph")
            //     .style("height").replace("px", "");

            if (resizeImageDiv) {
                d3.select("#thumbnails-btm_container")
                    .style("height", zoomedHeight + "px");
            }

            // attach images ---------------------------------------------------------------------------------------------------
            var imgLink = function (d) {
                var theFirstImg = imageLinkKeys[0];
                return makeUrl(d[theFirstImg],"img");
            };


            d3.select("#thumbnails-btm")
                //.style("height", "100%") //20px for credit line -
                .selectAll("div")
                .data(data).enter()
                .append("div")
                .attr("class", "col-xs-1")
                .style("cursor", "pointer")
                .append("img")
                .attr("id", "thumbnails-btm")
                .attr("src", imgLink)
                .on("click", updateLayoutOnSelect)
                .attr("title", getTitle)
                .style("border-color", function (d) {
                    return color(valueToNumber(d[pcIsColoredBy], pcIsColoredBy));
                });

        }


        function addPlaceHolderImage() {
            // add an image holder with toggle between 2D and 3D
            // just toggle between 2d and 3d
            // d3.select("#zoomed")
            //     .style("height", zoomedHeight)
            //     .append("img");
            //.attr("src", "https://raw.githubusercontent.com/tt-acm/DesignExplorer/gh-pages/design_explorer_data/img/placeholder/placeholder.png?token=ACx89Xl7jGRusi88wWHKH97INGjRifH5ks5Vy6YGwA%3D%3D");
        }

    </script>

    <!-- set up events between charts and graphs -->
    <script type="text/javascript">
            
        // change the chart size;
        function resizePcChart(newWidth, newHeight) {

            //resize #graph
            d3.select("#graph.parcoords")
                    .transition().duration(500)
                    .style("height", newHeight + "px")
                    .style("width", newWidth + "px");

            //redraw pcChart
            var highlightedData = graph.highlighted();
            //graph.width(newWidth).height(newHeight);
            graph.chartSize([newWidth,newHeight]);
            //graph.reRender();
            if (highlightedData.length > 0) graph.highlight(highlightedData);
            updateLabels();
            update_colors(pcIsColoredBy);

        }

        function setupEvents() {
            // brushing events for parallel coordinates graph
            graph.on("brush", function (d) {
                //rc.updateData(graph.brushed());   //-----TODO:here needs a switch for different charts-------
                updateScatterChart();
                updateImageGrid(d, false);
            });

            graph.on("brushend", function (d) {

                // if nothing is highlighted return
                if (typeof (graph.highlight()[0]) == "undefined") return;


                if (graph.brushed() == false) {
                    updateLayoutOnDeselect();
                    return;
                }
                // in case highlighted data not in brused then deselect
                if (graph.brushed().length == inputData.length || graph.brushed().indexOf(graph.highlight()[0]) ==-1) {

                    updateLayoutOnDeselect();
                }
            });

            // assign buttons
            // Keep brushed (zoom to selection)
            d3.select("#keep")
                .on("click", function () {

                    var newData = graph.brushed();
                    if (newData === false || newData.length === 0) {
                        alert("You need to select some data to be kept!");
                    } else {
                        graph.data(newData).dimensions(d3.keys(graph.dimensions()));
                        updateSlidersTickValues(newData);

                    }
                });

            d3.select("#remove")
                .on("click", function () {

                    var selectedData = graph.brushed();

                    if (selectedData === false || selectedData.length === 0) {
                        alert("You need to select some data to be removed!");
                    } else {
                        // update graph with new data
                        var newData = graph.data().filter(function (d) {
                            return selectedData.indexOf(d) == -1;
                        });
                        updateGraph(newData);
                    }
                });

            // go back to original
            d3.select("#reset")
                .on("click", function () {
                    graph.reset();
                    updateGraph(cleanedData);
                    update_colors(pcIsColoredBy);

                    setSettings(graph,_userSetting);

                });


            function updateGraph(newData) {
                
                graph.data(newData).brushReset().render().interactive().reorderable();
                graph.updateAxes();
                //updateRadarChart();                   //-----TODO:here needs a switch for different charts-------
                updateScatterChart();
                updateLayoutOnDeselect();
                updateImageGrid(newData, false);
                updateSlidersTickValues(newData);
            }

            //events for legend click
            d3.selectAll("rect.legend").on('click', function (d) {
                var rect = d3.select(this);
                var name = d.name;
                var enabled = true;

                if (rect.attr('class') === 'legend disabled') {
                    rect.attr('class', 'legend');
                } else {
                    rect.attr('class', 'legend disabled');
                    enabled = false;
                }

                // update dimensions
                dimensions.forEach(function (dim) {
                    if (d.name === name) d.enabled = enabled;
                });

                // get active dimensios to update parallel coordinate charts
                var activeDimensions = dimensions.filter(function (d) {
                    return d.enabled;
                }).map(function (d) {
                    return d.name;
                });

                //console.log(activeDimensions);
                graph.dimensions(activeDimensions);

                //if removed the pcIsColoredBy's dim, then update color by the first 
                if (activeDimensions.lastIndexOf(pcIsColoredBy) == -1) {
                    update_colors(activeDimensions[0]);
                }


                if (graph.highlighted().length !== 0) graph.highlight(graph.highlighted());
                updateLabels();
            });



            //turn off rating rectangle
            d3.selectAll("rect.legend").attr("class", function (d) {
                if (d.name == "Rating" || d.name == "Description") {
                    d.enabled = false;
                    return 'legend disabled';
                }
            });

           

            d3.select("#pcFullScreen-toggle")
                .on("click", function () {

                    isLeftChartFullScreenToggled = !isLeftChartFullScreenToggled;

                    d3.select(".col-lg-3") //hide right side
                        .transition().delay(200)
                        .style("display", function () {
                            if (isLeftChartFullScreenToggled) {
                                return "none";
                            } else {
                                return "block";
                            }
                        });

                    

                    d3.select("#PcChartWrapper")
                    .classed("col-lg-9", !isLeftChartFullScreenToggled)
                    .classed("col-lg-12", isLeftChartFullScreenToggled);

                    if (isLeftChartFullScreenToggled) {
                        var newpcHeight = (windowHeight - 109) * 0.5-50;
                        var newpcWidth = windowWidth - 30;  

                        resizePcChart(newpcWidth,newpcHeight);

                        
                        if (graph.highlighted().length>0) {
                            d3.selectAll("#thumbnails-btm_container, #zoomedArea").style("height", (cleanHeight - newpcHeight - 24) + "px");
                        } else {
                            d3.select("#thumbnails-btm_container").style("height", (cleanHeight - newpcHeight - 24) + "px");
                        }

                    } else {
                        // set left chart full screen
                        var oldpcHeight = graphHeight;
                        var oldpcWidth = 0;
                        // var oldpcWidth = d3.select("#graph").style("width").replace("px", "");
                        if (windowWidth<1200) {
                            oldpcWidth = windowWidth -70;
                        } else {
                            oldpcWidth = windowWidth*0.75 - 30;
                        }
                        
                        //back to normal size mode
                        resizePcChart(oldpcWidth,oldpcHeight);

                        if (graph.highlighted().length > 0) {
                                d3.selectAll("#thumbnails-btm_container, #zoomedArea").style("height", zoomedHeight + "px");
                            } else {
                                d3.select("#thumbnails-btm_container").style("height", zoomedHeight + "px");
                            }

                    }
                    loadSetting();

                });



        }

    </script>

    <!-- import data  //  onload functions to close intro window and read input data -->
    <script>
        function loadDataToDesignExplorer(data) {

            overwriteInitialGlobalValues();

            // analyze data and separate them as input/output and general data
            analyzeInputData(data);

            // get sliders information based on input data
            prepareSlidersInfo();

            // add sliders to the page
            initiateSliders();

            // draw parallel coordinates graph
            drawParallelCoordinates();

            //draw radar chart
            //updateRadarChart();                //-----TODO:here needs a switch for different charts-------

            //draw scatter chart
            addScatterChart();

            // set color change
            // set the initial coloring based on the 3rd column
            pcIsColoredBy = graph.getOrderedDimensionKeys()[0];
            update_colors(pcIsColoredBy);

            // draw legend in side bar
            drawLegend();

            // add sorting divs
            addSortDropdowns();

            // add thumbnails
            updateImageGrid(graph.data(), true); // true will set the size of btm div

            

            // add place holder image to zoomed area
            //addPlaceHolderImage();

            // set up events between graphs
            setupEvents();

            //lazy load
            // $(function() {
            //     $('.lazy').Lazy({
            //         // your configuration goes here
            //         scrollDirection: 'vertical',
            //         effect: 'fadeIn',
            //         visibleOnly: true,
            //         onError: function(element) {
            //             console.log('error loading ' + element.data('src'));
            //         },
            //         appendScroll: $('div#thumbnails-btm')

            //     });
            // });
        }


        window.onload = function () {
            var isIE = /*@cc_on!@*/false || !!document.documentMode;

            if(isIE){
                alert("Please use modern browsers to ensure the maximized experience.\n\nGoogle Chrome, or Firefox is suggested to use.");
            }
            
            if (window.location.href.toUpperCase().search("GFOLDER") > 0 || window.location.href.toUpperCase().search("ID=") > 0) {
                // this is old user with google drive files.
                d3.select("#welcome").style("display", "none");
                MP_getGoogleIDandLoad("URL");
            } else {
                // load default data
                //parse the csv data from the design_explorer_data folder
                d3.csv("design_explorer_data/default_onload.csv", function (data) {
                    loadDataToDesignExplorer(data);
                    loadSetting("design_explorer_data/DefaultData/settings.json");
                    });

            }

            //function to close intro window div
            d3.selectAll(".popupWindowClose")
                .on("click", function () {
                    d3.selectAll(".popupWindow")
                        .style("display", "none");
                });

            d3.select("#showLoadData")
                .on("click", function () {

                    d3.select("#welcome")
                        .style("display", "flex");

                    d3.select("#welcomeContainer")
                        .style("display", "none");

                    d3.select("#loadDataContainer")
                        .style("display", "block");
                    
                    d3.select("#loadFileLocally").style("display", "block");
                    d3.select("#showStaticLink").classed("Show",false);

                    //clean previous inputs
                    document.getElementById('csv-file').value = "";
                    document.getElementById("folderLink").value = "";
                    document.getElementById("folderLinkID").value = "";

                });

            var fileInput = document.getElementById('csv-file');

            fileInput.addEventListener('change', function (e) {
                //clean whatever was added via Google
                document.getElementById("folderLinkID").value = "";

                unloadPageContent();


                var file = fileInput.files[0];
                var reader = new FileReader();

                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.csv|.txt)$/;


                if (regex.test(file.name.toLowerCase())) {

                    // Update project name
                    var inputFileName = file.name.toUpperCase().replace(".CSV", "");

                    d3.select("p.navbar-brand").text(inputFileName);

                    reader.onload = function (e) {

                        var inputString = e.target.result;

                        // I need to add some defense here
                        // separateInputOutputs(e);

                        var data = d3.csv.parse(inputString);

                        //originalDataLength = data.length;

                        if (data !== null) {
                            loadDataToDesignExplorer(data);
                            loadSetting();
                            };
                    };


                } else {
                    alert("Please upload a valid CSV file!");
                }

                reader.readAsText(file);
                d3.select("#welcome").style("display", "none");
            });

        }; // end of window.onload

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    }); 

    </script>

    <!-- Setting up sliders -->
    <script type="text/javascript">
        function initiateSliders() {
            // this just runs first onload of the page
            // all the sliders should be disabled on load until the user select one of the options
            //d3.select(".sidebar-brand").text("INPUT SLIDERS");

            // insert divs for sliders
            d3.selectAll("#inputSliders")
                .selectAll("div")
                .data(slidersInfo).enter()
                .append("div")
                .attr("class", "inputSlider") // full width
                .text(function (d) {
                    return d.name;
                })
                .append("input")
                .attr("id", function (d) {
                    return d.namewithnospace;
                })
                .attr("name", function (d) {
                    return d.name;
                });


            // if number of ticks is less than 7 then show the numbers under slider
            var showGrid = function (info) {
                return info.tickValues.length < 7;
            };

            // set up slider properties
            // they will be all set to disabled until user selects one option
            slidersInfo.forEach(function (info, i) {

                $("#" + info.namewithnospace).ionRangeSlider({
                    grid: showGrid(info),
                    prettify_separator: ",",
                    values: info.tickValues,
                    disable: true,
                    onChange: function (data) {
                        // remove white spaces
                        // var sliderName = data.input[0].name.replace(/\s/g, '');
                        updateSliderValue(data.input[0].name, data.from_value);
                    }

                });
            });
        }

        function updateSlidersTickValues(unbrushedData) {

            // This function only gets called once there is a new selection
            // and for major changes in data selection (reset/exclude/zoom to selection)
            // don't call this frequently as this is a fairly expensive run


            if (graph.brushed() == [] && graph.brushed().length == inputData.length) {
                // go back to default
                // disable all sliders once no option is selected
                slidersInfo.forEach(function (info) {
                    $("#" + info.namewithnospace).data("ionRangeSlider")
                        .update({
                            values: info.originalTickValues
                        });
                });

            } else {

                var tickValues = {};
                var data;

                slidersInfo.forEach(function (info) {
                    tickValues[info.name] = [];
                }); //create an empty list for each slider

                if (!graph.brushed()) {
                    // in case graph is just rendered
                    data = graph.data();
                    //console.log("graph.data()"+data.length);
                } else {
                    data = graph.brushed();
                    //console.log("graph.brushed()"+data.length);
                }

                

                // find tick values
                data.forEach(function (d) {
                    slidersInfo.forEach(function (info) {
                        // check if the value is already in list - add it to the list if not
                        if (tickValues[info.name].indexOf(d[info.name]) == -1) tickValues[info.name].push(
                            d[info.name]);
                    });
                });


                slidersInfo.forEach(function (sliderInfo) {

                    var sliderName = sliderInfo.name;
                    // remove spaces
                    var namewithnospace = string_as_unicode_escape(sliderName);
                    var newTickValues = tickValues[sliderName].sort();
                    //console.log(sliderName);

                    // update values in sliderInfo
                    sliderInfo.tickValues = newTickValues;

                    $("#" + namewithnospace).data("ionRangeSlider")
                        .update({
                            values: newTickValues
                        });
                });

                // update current values
                updateSlidersValue(graph.highlighted()[0]);

            }
        }


        function disableAllSliders() {
            // disable all sliders once no option is selected
            slidersInfo.forEach(function (info) {
                $("#" + info.namewithnospace).data("ionRangeSlider")
                    .update({
                        disable: true
                    });
            });
        }


        function enableAllSliders() {
            // enable all sliders once an option is selected
            // This will be called after selecting a new option
            slidersInfo.forEach(function (info) {
                $("#" + info.namewithnospace).data("ionRangeSlider")
                    .update({
                        disable: false
                    });
            });
        }

        function updateSlidersValue(data) {

            if(data ==undefined){
                return;
            }

            // clear report area
            d3.selectAll("#sliders-report").text("");

            // update the value of all the sliders based on an input object
            // this function will be called when a new option is selected by user
            slidersInfo.forEach(function (info) {
                var value;

                if (isNaN(data[info.name])) {
                    value = data[info.name]; // if a number
                } else {
                    value = parseFloat(data[info.name]); // otherwise
                }

                currentSliderValues[info.name] = value;
                //console.log(info.tickValues.indexOf(value));
                $("#" + info.namewithnospace).data("ionRangeSlider")
                    .update({
                        from: info.tickValues.indexOf(value), //update the value
                    });
            });
        }


        function updateSliderValue(changedSliderName, newValue) {
            // This function update the value for a single slider
            // All the sliders call this function on update
            if (!isNaN(newValue) || typeof (newValue) == "string") {

                currentSliderValues[changedSliderName] = newValue;
            } else {
                return;
            }

            // create the id
            var id = getCaseId(currentSliderValues);
            var selectedData;

            if (ids.indexOf(id) != -1) {

                selectedData = allDataCollector[id].cleanedParams;
                //console.log(selectedData);

                // update chart
                graph.highlight([selectedData]);

                //update radar chart
                //rc.highlight([selectedData]);
                highlightScatterDot(); //-----TODO:here needs a switch for different charts-------

                //updat iterationData
                updateIterationData([selectedData]);


                // push the image to zoomed area
                d3.select("#viewer2d")
                    .select("img")
                    .attr("src", makeUrl(selectedData[imageLinkKeys[0]],"img"));

                if (currentView == "3D") loadNew3DModel();

                d3.selectAll("#sliders-report").text("");

            } else {

                // try to remap the values based on slider value
                var possibleCombinations = slidersMapping[changedSliderName][newValue];

                if (possibleCombinations.length == 1) {
                    // there is only one combination update the values to the new combination
                    updateSlidersValue(possibleCombinations[0]);

                } else {

                    // sort them based on distance to current values
                    possibleCombinations.sort(function (x, y) {
                        return d3.ascending(distanceFromCurrent(x), distanceFromCurrent(y));
                    });


                    // if it is in the seletion then change the sliders to that value
                    selectedData = graph.brushed();

                    if (!selectedData) selectedData = graph.data();

                    for (var i = 0; i < possibleCombinations.length; i++) {
                        var combination = possibleCombinations[i];
                        if (selectedData.indexOf(combination) != -1) {
                            updateSlidersValue(combination);
                            // console.log(i);
                            break;
                        }
                    }
                }

            }
        }

        function distanceFromCurrent(sliderValues) {
            var distance = 0;

            slidersInfo.forEach(function (info) {
                var tValues = info.originalTickValues;
                var d = tValues.indexOf(currentSliderValues[info.name]) - tValues.indexOf(sliderValues[info.name]);
                distance += Math.abs(d);
            });


            return distance;

        }

    </script>

    <!-- 3D Viewer -->
    <script type="text/javascript">
        var currentView = "2D";
        var initit3DViewer = true;
        var fullscreen = false;
        var va3cViewer;

        // Initiate 3D viewer
        function initiate3DViewer() {

            var jsonFileAddress = makeUrl(graph.highlighted()[0].threeD,"threeD");
            var viewerId = "viewer3d";

            //load JSON file
            d3.json(jsonFileAddress, function (data) {
                //Initialize a VA3C viewer
                va3cViewer = new SPECTACLES($("#" + viewerId), data, function (app) {
                    //call the UI / functionality modules
                    app.setBackgroundColor(0xFFFFFF);
                });
            });
        }

        // load new models
        function loadNew3DModel() {
            var jsonFileAddress = makeUrl(graph.highlighted()[0].threeD,"threeD");
            d3.json(jsonFileAddress, function (data) {
                va3cViewer.loadNewModel(data);
            });
        }


        // toggle between 2d and 3d
        d3.selectAll("input.toggleView")
            .on("change", toggleView);

        function toggleView() {

            currentView = this.value;

            if (currentView == "3D" && initit3DViewer) {

                initit3DViewer = false;

                // initiate the viewer
                initiate3DViewer();

            } else if (currentView == "3D") {

                // user is changing to 3D view so let's load it.
                //$.when(loadNew3DModel()).then(va3cViewer.viewerDiv.resize());
                loadNew3DModel();
                
            }


            var onOff = {
                "2D": ["zoomed", "viewer3d"],
                "3D": ["viewer3d", "zoomed"]
            };

            d3.select("#" + onOff[currentView][0])
                .attr("class", "zoomed");

            d3.select("#" + onOff[currentView][1])
                .attr("class", "zoomed hidden");

            // remove spectacles footer
            d3.select(".Spectacles_Footer").remove();

            // resize the view
            if (va3cViewer !==undefined) {
                va3cViewer.viewerDiv.resize();
            }
            

        }


        // active and de-activate full screen mode
        d3.select("button#fullscreentoggle")
            .on("click", toggleFullscreen);


        function toggleFullscreen() {

            fullscreen = !fullscreen;

            // select zoomed Div and change style
            d3.select("#zoomedArea")
                .attr("class", function () {
                    return fullscreen ? "zoomed fullscreen" : "col-lg-9";
                });

            //make sure width and height are 100%

            // d3.select("#viewer3d")
            //     .style("width", "100%")
            //     .style("height", "100%");

            // d3.select("#zoomed")
            //     .style("width", "100%")
            //     .style("height", "100%");

            //change full screen icon
            d3.select("button#fullscreentoggle").select("span")
                .attr("class", function () {
                    return fullscreen ? "glyphicon glyphicon-resize-small" : "glyphicon glyphicon-fullscreen";
                });


            if (initit3DViewer) {
                return 0;
            } else {
                va3cViewer.viewerDiv.resize();
            }


        }

    </script>

    <script type="text/javascript">
        // Rating system
        var firstRating = true;

        // submit the value on change
        $('.star').rating({
            callback: function (value, link) {
                // 'value' is the value selected
                if (firstRating === true) {

                    //turn on rating rectangle
                    d3.selectAll("rect.legend")
                        .attr("class", function (d) {

                            if (d.name == "Rating") {
                                d.enabled = true;
                                return 'legend';
                            }
                        });

                    // add Rating to dimensions
                    graph.dimensions(cleanedKeys4pc);
                    graph.scale("Rating", [0, 5]);
                    graph.updateAxes();
                    firstRating = false;
                }

                // check if this is the first rating

                // update rating value
                graph.highlighted()[0].Rating = value;

                // this method is not really optimized. I couldn't find a solution just to change          //I agree
                // extent of dimensions for just rating axis

                graph.update().interactive().reorderable();
                //if(graph.brushed()) graph.clear("foreground");

                // highlight selected option
                graph.highlight(graph.highlighted());



            }
        });


        // set the value for rating based on the current value
        function setStarValue(value) {

            // remove any current value
            $('.star').data('rating').current = undefined; // set current value to undefined

            // set current value to undefined
            var offClass = "star-rating rater-0 star star-rating-applied star-rating-live";
            var onClass = offClass + " star-rating-on";

            //select rating divs
            d3.selectAll("div.star-rating")
                .attr("class", function (d, i) {
                    return i < value ? onClass : offClass;
                });

        }

    </script>

    <!-- saveToCSV -->
    <script type="text/javascript">
        function saveToCSV() {

            // modified from http://stackoverflow.com/questions/14964035/how-to-export-javascript-array-info-to-csv-on-client-side
            var data = graph.brushed() === false ? graph.data() : graph.brushed();
            var csvTitle = d3.keys(data[0]);

            var csvContent = "data:attachment/csv;charset=utf-8,";
            var scidIndex = csvTitle.indexOf("scid");
            csvTitle.splice(scidIndex, 1);

            csvContent += csvTitle.join(",") + "\n"; //add first row

            data.forEach(function (infoArray, index) {
                dataString = d3.values(infoArray);
                dataString.splice(scidIndex, 1);
                dataString = dataString.join(",");
                csvContent += index < data.length ? dataString + "\n" : dataString;

            });

            csvContent = encodeURI(csvContent);

            var a = d3.select("body")
                .append("a")
                .attr("href", csvContent)
                .attr("target", '_blank')
                .attr("download", 'DesignExplorer_SelectedResults.csv');

            a[0][0].click();
        }

    </script>

    <!--dataSetting-->
    <script type="text/javascript">


        function dataSetting(chart) {
            d3.select("#showSetting")
                    .style("display", "flex");
            
            var dimKeys = d3.keys(graph.dimensions()); 
            var dimSettingListDiv = d3.select("#dimSettingList");

            var newUserSetting = {
                studyInfo: {
                    name:_userSetting.studyInfo.name,
                    date:_userSetting.studyInfo.date
                },
                dimScales:{},
                dimTicks:{},
                dimMark:{}
            };

            // var userStudyInfo = {
            //     name:"",
            //     date:""
            // }

            var dimScales ={};//for current value
            // var userDimScale = {};

            var dimTicks = {}; //for current value
            //var userDimTicks = {}; //for saved

            var dimMark = {}; //for current value
            // var userDimMark = {}; //for saved

            //filter dimensions are string type
            d3.selectAll("#graph svg .dimension")
                .each(function(d){
                    if (graph.dimensions()[d].type != "string") {
                        dimScales[d] = graph.dimensions()[d].yscale.domain();
                        dimTicks[d] = d3.select(this).selectAll(".tick").size();
                        if(_userSetting.dimMark[d] !== undefined){
                            dimTicks[d] --;
                        }
                    }
                })
            

            //remove current
            dimSettingListDiv.selectAll(".panel-default").remove();

            //Add this study/project info
            dimSettingListDiv.select("#studyNameInput")
                            .attr("value", newUserSetting.studyInfo.name)
                            .on("change", function(d){
                                newUserSetting.studyInfo.name = $(this).val();
                            });
            dimSettingListDiv.select("#studyDateInput")
                            .attr("value", newUserSetting.studyInfo.date)
                            .on("change", function(d){
                                newUserSetting.studyInfo.date = $(this).val();
                            });


            var selectList = dimSettingListDiv.selectAll('#dimSettingList')
                        .data(d3.keys(dimScales)).enter()
                        .append("div")
                        .attr("class", "panel panel-default");
                    
            var panelHeading = selectList.append("div").attr("class", "panel-heading");

            panelHeading.append("h4")
                        .attr("class", "panel-title")
                            .append("a")
                            .attr("data-toggle", "collapse")
                            .attr("data-parent", "#dimSettingList")
                            .attr("href", function(d){return "#setting"+string_as_unicode_escape(d);})
                            .text(function(d){return d;});

            
            var panelBody = selectList.append("div")
                        .attr("id", function(d){return "setting"+string_as_unicode_escape(d);})
                        .attr("class", "panel-collapse collapse");
            
            var panelBodyContent = panelBody.append("div").attr("class", "panel-body")
                                                .append("ul").attr("class", "list-group");
            
            //TickNumber setting----------------------------------------------------------------------------------------------------------
            var itemTickNumberSetting = panelBodyContent.append("li")
                                                    .attr("class", "list-group-item form-inline")
                                                    .text("Tick Number: ");
            
            itemTickNumberSetting.append("input")
                        .attr("class", "form-control input-sm")
                        .attr("placeholder", function(d){return dimTicks[d];})
                        .attr("type", "text")
                        .on("change", function(d){
                            var value = $(this).val();
                            newUserSetting.dimTicks[d] = value;
                        });

            
            //scale setting----------------------------------------------------------------------------------------------------------
            var itemScaleSetting = panelBodyContent.append("li")
                                                    .attr("class", "list-group-item form-inline")
                                                    .text("Scale Range: ");

            //min scale input
            itemScaleSetting.append("input")
                        .attr("class", "form-control input-sm")
                        .attr("placeholder", function(d){return "From: " +dimScales[d][0];})
                        .attr("type", "text")
                        .on("change", function(d){
                            var value = $(this).val();
                            newUserSetting.dimScales[d] = OnScaleChanged(
                                {"min":value}, dimScales[d], newUserSetting.dimScales[d]
                            )
                        });

            //max scale input
            itemScaleSetting.append("input")
                        .attr("class", "form-control input-sm")
                        .attr("placeholder", function(d){return "TO: "+dimScales[d][1];})
                        .attr("type", "text")
                        .on("change", function(d){
                            var value = $(this).val();
                            newUserSetting.dimScales[d] = OnScaleChanged(
                                {"max":value}, dimScales[d] , newUserSetting.dimScales[d]
                            )
                        });


            //TargetMark setting----------------------------------------------------------------------------------------------------------
            var itemTickNumberSetting = panelBodyContent.append("li")
                                                    .attr("class", "list-group-item form-inline")
                                                    .text("Target Mark: ");
            
            itemTickNumberSetting.append("input")
                        .attr("class", "form-control input-sm")
                        .attr("style","margin-left: 1.1em;")
                        .attr("placeholder", function(d){return _userSetting.dimMark[d]!==undefined?_userSetting.dimMark[d].value:"Mark value";})
                        .attr("type", "text")
                        .attr("id", function(d){return "setting_markValue_"+string_as_unicode_escape(d);})
                        .on("change", function(d){
                            if(newUserSetting.dimMark[d] ===undefined){
                                newUserSetting.dimMark[d] = {
                                    value:"",
                                    prefix:""
                                    }
                            }
                            newUserSetting.dimMark[d].value = $(this).val();
                        });

            itemTickNumberSetting.append("input")
                        .attr("class", "form-control input-sm")
                        .attr("placeholder", function(d){return _userSetting.dimMark[d]!==undefined?_userSetting.dimMark[d].prefix:"Prefix text";} )
                        .attr("type", "text")
                        .attr("id", function(d){return "setting_markPrefix_"+string_as_unicode_escape(d);})
                        .on("change", function(d){
                            if(newUserSetting.dimMark[d] ===undefined){
                                newUserSetting.dimMark[d] = {
                                    value:"",
                                    prefix:""
                                    }
                            }
                            newUserSetting.dimMark[d].prefix = $(this).val();
                        });

            itemTickNumberSetting.append("button")
                        .attr("class", "btn btn-sm")
                        .text("Remove")
                        .attr("type", "button")
                        .on("click", function(d){
                            d3.select("#setting_markValue_"+string_as_unicode_escape(d)).attr("value", "ToBeRemoved");
                            d3.select("#setting_markPrefix_"+string_as_unicode_escape(d)).attr("value", "ToBeRemoved");
                            newUserSetting.dimMark[d] ={
                                    value:"ToBeRemoved",
                                    prefix:""
                                    };
                            
                        });


            //set up button event
            d3.select("#dataSetting #setUserSetting").on("click",function (d) {
                setSettings(chart,newUserSetting);
                d3.select("#showSetting")
                .style("display", "none");
            })

             d3.select("#dataSetting  #saveUserSetting").on("click", function(d){
                 saveSetting(_userSetting,newUserSetting);

             })

        }

        function setSettings(chart,userSetting) {

            var isThereNewStudyInfo = Object.keys(userSetting.studyInfo).length >0;
            var isThereNewDimScales = Object.keys(userSetting.dimScales).length >0;
            var isThereNewDimTicks = Object.keys(userSetting.dimTicks).length >0;
            

            if (isThereNewStudyInfo) {
                    setStudyInfo(userSetting.studyInfo);
            }

            if (isThereNewDimScales) {
                    setAllScales(chart, userSetting.dimScales);
            }

            if (isThereNewDimTicks) {
                    setAllTicks(chart, userSetting.dimTicks);
            }

            if(isThereNewDimScales || isThereNewDimTicks){
                //only redraw the graph if any defined
                graph.updateAxes();

                //chart.reRender();
                update_colors(pcIsColoredBy);
                
            }
            
            //set target mark must be after graph.updateAxes();
            setAllTargetMarks(chart, userSetting.dimMark);
            
        }

        function setStudyInfo(studyInfoData){
            var studyInfo = d3.select("#navbar-studyInfo");
            studyInfo.select("h4").text(studyInfoData.name);
            studyInfo.select("h6").text(studyInfoData.date);

            //update the global _userSetting
            _userSetting.studyInfo.name = studyInfoData.name;
            _userSetting.studyInfo.date = studyInfoData.date;
            
        }

        function setAllScales(chart, scales){
            d3.keys(scales).forEach( function (d) {
                    //update scales
                    chart.dimensions()[d].yscale.domain(scales[d]);
                    chart.yscaleDomains()[d] = scales[d]; 

                    //update the global _userSetting
                    _userSetting.dimScales[d] = scales[d];
                 })
        }

        function setAllTicks(chart, tickNumbers){
            d3.keys(tickNumbers).forEach( function (d) {
                    var scale = chart.dimensions()[d].yscale.domain();
                    //update ticks
                    chart.dimensions()[d].tickFormat = d3.format("");
                    chart.dimensions()[d].tickValues = calTickValues(scale,tickNumbers[d]);

                    //update the global _userSetting
                    _userSetting.dimTicks[d] = tickNumbers[d];
                 })
        }

        function setAllTargetMarks(chart, targetMark){
            
            d3.keys(targetMark).forEach( function (d) {
                    //update the global _userSetting
                    _userSetting.dimMark[d] = targetMark[d];
                    
            })
            
            //set marks based on global _userSetting
            d3.keys(_userSetting.dimMark).forEach( function (d) {
                    setMarkOnDim(chart, d, _userSetting.dimMark[d]);
            })
        }

        function setMarkOnDim(chart,dim,dimMark){

            markValue = dimMark.value;
            markPrefix = "";

            if (dimMark.prefix !==undefined){
                markPrefix =dimMark.prefix
            }


            dimID = "dim_"+string_as_unicode_escape(dim); 
            //remove the current first
            d3.select("#"+dimID).select(".axis .mark").remove();

            if (markValue=="ToBeRemoved") {
                delete _userSetting.dimMark[dim];

            } else {

                var canvasHeight = d3.select("#graph canvas").style("height").replace("px","");

                domain = chart.dimensions()[dim].yscale.domain();
                yHeight = d3.scale.linear().domain(domain).range([canvasHeight,0])(markValue);
                
                //add mark
                var dim = d3.select("#"+dimID).select(".axis").append("g").attr("class", "tick mark").attr("transform", "translate(0,"+yHeight+")");
                dim.append("line").attr("x2", "-8").attr("x1", "8");
                dim.append("text").attr("x", "-9").attr("y", "0").attr("dy", ".32em").attr("text-anchor","end").text(markPrefix+": "+markValue);
            }


            
        }

        function calTickValues (scale, tickNumber){
            tickNumber--;

            if(tickNumber<=0){
                tickNumber = 5;
            }
            newDimTickValues = d3.range(scale[0], scale[1], (scale[1]-scale[0])/tickNumber)
            newDimTickValues.push(scale[1]); // add the last

            return newDimTickValues;
        }

        function OnScaleChanged(params,oldScale, userScale) {
            var newScale=[0,0];
            var preScale=[0,0];

            var min =  parseFloat(params.min);
            var max =  parseFloat(params.max);

            if (userScale ===undefined) {
                preScale = oldScale;
            }else{
                preScale = userScale;
            }

            newScale[0] = !isNaN(min) ? min:preScale[0];
            newScale[1] = !isNaN(max) ? max:preScale[1];

            return newScale;
        }

        function saveSetting(globalUserSetting,newUserSetting) {

            Object.assign(globalUserSetting.dimMark,newUserSetting.dimMark)
            Object.assign(globalUserSetting.dimScales,newUserSetting.dimScales)
            Object.assign(globalUserSetting.dimTicks,newUserSetting.dimTicks)
            Object.assign(globalUserSetting.studyInfo,newUserSetting.studyInfo)

            var exportJson = globalUserSetting;

            var JsonContent = "data:attachment/json;charset=utf-8,";
            

            JsonContent += encodeURI(JSON.stringify(exportJson));

            var a = d3.select("body")
                .append("a")
                .attr("href", JsonContent)
                .attr("target", '_blank')
                .attr("download", 'settings.json');

            a[0][0].click();

        }

        function loadSetting(settingFilePath) {
            
            var jsonFileLink = "";

            if(settingFilePath!==undefined){
                jsonFileLink =settingFilePath;
            }else{

                var settingFiles =  d3.keys(_googleReturnObj.settingFiles);
                if (settingFiles.length ==0) {
                     jsonFileLink = "design_explorer_data/settings.json"

                }else {

                    var jsonFile = settingFiles[0];
                    jsonFileLink = _googleReturnObj.settingFiles[jsonFile];
                }

                
            }

            d3.json(jsonFileLink, function (d) {
                //updata to global _userSetting
                _userSetting = d;
                setSettings(graph,_userSetting);
            })

            
        }



    </script>


</body>

</html>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Map</title>
  <style type="text/css" media="screen">
  .map {
      width: 100vw;
      height: 100vh;
  }
  </style>
</head>
<body>
<h1 class="sr-only" style="display: none;">Map</h1>
<div id="map_canvas" class="map"></div>

<?php
include_once "../../mainfile.php";
if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/tinymap.php")) {
    redirect_header("http://campus-xoops.tn.edu.tw/modules/tad_modules/index.php?module_sn=1", 3, _TAD_NEED_TADTOOLS);
}
include_once XOOPS_ROOT_PATH . "/modules/tadtools/tinymap.php";
$tinymap = new tinymap('#map_canvas', $_GET['latitude'], $_GET['longitude'], '');
$tinymap->set_key('AIzaSyB6QwK6qMlF7jBQA6olHTWSIk4Be3CYgoE');
echo $tinymap->render();

?>
  </body>
</html>
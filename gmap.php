<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script type="text/javascript" src="../tadtools/jquery/jquery.js"></script>
    <script src="http://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript"></script>
    <script type="text/javascript" src="class/gmap3.min.js"></script>

    <style>
      .gmap3{
        border: 1px dashed #C0C0C0;
        width: 100%;
        height: 100%;
      }
    </style>

    <script type="text/javascript">
      $(function(){
        $("#map_canvas").gmap3({
          marker:{
            latLng: [ <?php echo $_GET['latitude'];?> , <?php echo $_GET['longitude'];?>]
          },
          map:{
            options:{
              zoom: 19
            }
          }
        });
      });

    </script>
  </head>
  <body>
    <div id="map_canvas" class="gmap3"></div>
  </body>
</html>
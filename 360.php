<?php
use Xmf\Request;
require_once __DIR__ . '/header.php';

$sn = Request::getInt('sn');
$photo = Request::getString('file');
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A simple example</title>
    <link rel="stylesheet" href="class/pannellum/pannellum.css">
    <script type="text/javascript" src="class/pannellum/pannellum.js"></script>
    <style>
        html, body {
            height: 100%;
            margin: 0px;
        }

        }font-family:

        #panorama {
          width: 100%;
          height: 100%;
        }
    </style>
</head>
<body>
<div id="panorama"></div>
<script>
pannellum.viewer('panorama', {
    "type": "equirectangular",
    "autoLoad":true,
    "panorama": "<?php echo $photo; ?>"
});
</script>

</body>
</html>

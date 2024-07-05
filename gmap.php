<!DOCTYPE html>
<html lang="zh">
<head>
    <title>中文OpenStreetMap地圖顯示</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div id="map"></div>

    <script>
        var latitude = <?=$_GET['latitude']?>;  // 替換成你的緯度
        var longitude = <?=$_GET['longitude']?>;  // 替換成你的經度

        var map = L.map('map').setView([latitude, longitude], 15);

        // 使用支持中文的圖層
        L.tileLayer('https://{s}.tile.openstreetmap.de/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([latitude, longitude]).addTo(map)
            .bindPopup('我的位置')
            .openPopup();
    </script>
</body>
</html>
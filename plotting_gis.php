<?php
include "koneksi.php";

if (!isset($_GET['id'])) { die("ID Aset tidak ditemukan."); }
$id = $_GET['id'];

$q = mysqli_query($koneksi, "SELECT a.*, u.nama_unit_setting FROM master_aset a JOIN ms_unit_setting u ON a.id_unit_setting = u.id_unit_setting WHERE a.id_aset='$id'");
$d = mysqli_fetch_array($q);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Plotting GIS | SIKAKAP</title>
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <style>
        #map { height: 500px; width: 100%; border-radius: 8px; border: 2px solid #333; }
        .leaflet-draw-toolbar a { background-color: #fff !important; }
    </style>
</head>
<body class="bg-light p-3">
    <div class="card shadow">
        <div class="card-header bg-navy">
            <h3 class="card-title"><i class="fas fa-map-marker-alt"></i> Plotting: <?= $d['nama_unit_setting']; ?></h3>
            <a href="master_aset.php" class="btn btn-sm btn-danger float-right">Keluar</a>
        </div>
        <div class="card-body">
            <div id="map"></div>
            
            <form action="proses_aset.php" method="POST" class="mt-3">
                <input type="hidden" name="id_aset" value="<?= $id; ?>">
                <div class="form-group">
                    <label>Koordinat Poligon (JSON)</label>
                    <textarea name="koordinat_polygon" id="coords" class="form-control" rows="3" readonly required><?= $d['koordinat_polygon']; ?></textarea>
                </div>
                <button type="submit" name="update_gis" class="btn btn-success btn-block shadow-lg">
                    <i class="fas fa-save"></i> SIMPAN LOKASI ASET
                </button>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script>
        // 1. Koordinat Default (UPT PP Bulu Tuban)
        var map = L.map('map').setView([-6.7042, 111.7547], 18);

        // 2. Definisi Berbagai Peta (Agar jika satu hitam, bisa pindah ke yang lain)
        var googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            subdomains:['mt0','mt1','mt2','mt3'], attribution: 'Google Satellite'
        });

        var esriSat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Esri Satellite'
        });

        var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'OpenStreetMap'
        });

        // Masukkan Google Sat sebagai default
        googleSat.addTo(map);

        // 3. Tombol Pilih Peta (Control Layer) - Muncul di pojok kanan atas
        var baseMaps = {
            "Google Satellite (Hybrid)": googleSat,
            "Esri Satellite (Alternatif)": esriSat,
            "Peta Jalan (OSM)": osm
        };
        L.control.layers(baseMaps).addTo(map);

        // 4. Fitur Gambar
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        // Load data lama jika ada
        <?php if(!empty($d['koordinat_polygon'])): ?>
            var poly = L.polygon(<?= $d['koordinat_polygon']; ?>, {color: 'red'}).addTo(drawnItems);
            map.fitBounds(poly.getBounds());
        <?php endif; ?>

        var drawControl = new L.Control.Draw({
            position: 'topright',
            draw: { polygon: true, rectangle: true, circle: false, marker: false, polyline: false },
            edit: { featureGroup: drawnItems }
        });
        map.addControl(drawControl);

        // 5. Tangkap hasil gambar
        map.on('draw:created', function (e) {
            var layer = e.layer;
            drawnItems.clearLayers();
            drawnItems.addLayer(layer);
            var latlngs = layer.getLatLngs()[0].map(ll => [ll.lat, ll.lng]);
            document.getElementById('coords').value = JSON.stringify(latlngs);
        });

        map.on('draw:edited', function (e) {
            var layers = e.layers;
            layers.eachLayer(function (layer) {
                var latlngs = layer.getLatLngs()[0].map(ll => [ll.lat, ll.lng]);
                document.getElementById('coords').value = JSON.stringify(latlngs);
            });
        });
    </script>
</body>
</html>
<?php $this->layout('renderedmap', ['title' => 'Photo Map'])?>

<div id="map"></div>

<?php $this->start('js') ?>
<script>
var map;
function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: 45.081780, lng: 7.660570},
    zoom: 4
  });
  <?php foreach($data as $el): ?>
  var myLatLng = {lat: <?= $this->e($el['Latitude']) ?>, lng:  <?= $this->e($el['Longitude']) ?>};
  var photoName = ' <?= (string)$this->e($el['Name']) ?>';
  var marker = new google.maps.Marker({
    position: myLatLng,
    title: photoName,
    url : '/../showsingleblob.php?name=<?= (string)$this->e($el['Name']) ?>'
  });
  marker.setMap(map);
  google.maps.event.addListener(marker, 'click', function() {
        window.open(marker.url);
  });
  <?php endforeach ?>
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=&callback=initMap" async defer></script>
<?php $this->stop() ?>

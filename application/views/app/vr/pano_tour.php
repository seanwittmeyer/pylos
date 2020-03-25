<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width, shrink-to-fit=no">
    <title>Linked Panorama</title>
    <style>
      html, body {
        margin: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        background-color: #000;
      }

      a:link, a:visited{
        color: #bdc3c7;
      }

      .credit{
        position: absolute;
        text-align: center;
        width: 100%;
        padding: 20px 0;
        color: #fff;
      }
    </style>
  </head>

  <body>
    
    <div class="credit">street view, pano, and pano video</div>

    <script src="/includes/app/vr/three/three.min.js"></script>
    <script src="/includes/app/vr/panolens/panolens.min.js"></script>

    <script>

      var panorama1, panorama2, viewer;

      panorama1 = new PANOLENS.GoogleStreetviewPanorama( 'zHVa_ReerpE-PrH6iP5BdQ' );

      panorama2 = new PANOLENS.ImagePanorama( '/upload/vr/home.jpg' );

      panorama3 = new PANOLENS.VideoPanorama( '/upload/vr/ClashofClans.mp4' );

      viewer = new PANOLENS.Viewer({output: 'overlay', viewIndicator: true});
      viewer.add( panorama1 );
      viewer.add( panorama2 );
      viewer.add( panorama3 );

      // Linking between panoramas
      
      // Pair
      panorama1.link( panorama2, new THREE.Vector3( -3145.23, -3704.40, 1149.48 ) );
      panorama2.link( panorama1, new THREE.Vector3( -3429.01, 1205.85, -3421.88 ) );

      // Pair with custom scale and image
      panorama1.link( panorama3, new THREE.Vector3( -1611.08, -3234.51, 3451.63 ), 400, '/upload/vr/1941-battle-thumb.jpg' );
      panorama3.link( panorama2, new THREE.Vector3( 2092.2, -159.02, -4530.91 ) );

    </script>

  </body>
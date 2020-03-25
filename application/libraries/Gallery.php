<?php
/**
 * Modified nanoPhotosProvider2 add-on for nanogallery2 which has been embedded into Sean Wittmeyer's Builder site.
 *
 *
 */



/* START NANO PROVIDER 2 CODE 
 *
 * This is an add-on for nanogallery2 (image gallery - http://nanogallery2.nanostudio.org).
 * This PHP application will publish your images and albums from a PHP webserver to nanogallery2.
 * The content is provided on demand, one album at one time.
 * Responsive thumbnails are generated automatically.
 * Dominant colors are extracted as a base64 GIF.
 * 
 * License: For personal, non-profit organizations, or open source projects (without any kind of fee), you may use nanogallery2 for free. 
 * -------- ALL OTHER USES REQUIRE THE PURCHASE OF A COMMERCIAL LICENSE.
 *
 */
 
class galleryData
{
    public $fullDir = '';
    //public $images;
    //public $URI;
}

class galleryItem
{
    public $src         = '';             // image URL
    public $title       = '';             // item title
    public $description = '';             // item description
    public $ID          = '';             // item ID
    public $albumID     = '0';            // parent album ID
    public $kind        = '';             // 'album', 'image'
    public $t_url       = array();        // thumbnails URL
    public $t_width     = array();        // thumbnails width
    public $t_height    = array();        // thumbnails height
    public $dc          = '#888';         // image dominant color
    // public $dcGIF       = '#000';   // image dominant color


}

class Gallery
{
	protected $config   = array();
	protected $data;
	protected $albumID;
	protected $album;
	protected $tn_size  = array();
	protected $ctn_urls = array();
	protected $ctn_w    = array();
	protected $ctn_h    = array();
	protected $currentItem;
	const ICONV_TRANSLIT = "TRANSLIT";
	const ICONV_IGNORE = "IGNORE";
	const WITHOUT_ICONV = "";
            
    //const CONFIG_FILE    = './nano_photos_provider2.cfg'; // see note below.

    public function __construct()
    {
      // retrieve the album ID in the URL
      $this->album   = '/';
      $this->albumID = '';
      if (isset($_GET['albumID'])) {
        $this->albumID = rawurldecode($_GET['albumID']);
      }
      if (!$this->albumID == '0' && $this->albumID != '' && $this->albumID != null) {
        $this->album = '/' . $this->CustomDecode($this->albumID) . '/';
      } else {
        $this->albumID = '0';
      }

      //$this->setConfig(self::CONFIG_FILE); // SW - we don't use this anymore, feeding in the config via defined constants. define the assocaitive array as NANOCONFIG and gallery folder as NANOFOLDER
      $this->setConfig(); 
      
      // thumbnail responsive sizes
      $this->tn_size[	'wxs']   = $this->CheckThumbnailSize( $_GET['wxs'] );
      $this->tn_size['hxs']   = $this->CheckThumbnailSize( $_GET['hxs'] );
      $this->tn_size['wsm']   = $this->CheckThumbnailSize( $_GET['wsm'] );
      $this->tn_size['hsm']   = $this->CheckThumbnailSize( $_GET['hsm'] );
      $this->tn_size['wme']   = $this->CheckThumbnailSize( $_GET['wme'] );
      $this->tn_size['hme']   = $this->CheckThumbnailSize( $_GET['hme'] );
      $this->tn_size['wla']   = $this->CheckThumbnailSize( $_GET['wla'] );
      $this->tn_size['hla']   = $this->CheckThumbnailSize( $_GET['hla'] );
      $this->tn_size['wxl']   = $this->CheckThumbnailSize( $_GET['wxl'] );
      $this->tn_size['hxl']   = $this->CheckThumbnailSize( $_GET['hxl'] );
      
      
      

      
      $this->data           = new galleryData();
      $this->data->fullDir  = ($this->config['contentFolder']) . ($this->album);

      $lstImages = array();
      $lstAlbums = array();
      
      $dh = opendir($this->data->fullDir);

      // loop the folder to retrieve images and albums
      if ($dh != false) {
        while (false !== ($filename = readdir($dh))) {
          if (is_file($this->data->fullDir . $filename) ) {
            // it's a file
            if ($filename != '.' &&
                    $filename != '..' &&
                    $filename != '_thumbnails' &&
                    preg_match("/\.(" . $this->config['fileExtensions'] . ")*$/i", $filename) &&
                    strpos($filename, $this->config['ignoreDetector']) == false )
            {
              $lstImages[] = $this->PrepareData($filename, 'IMAGE');
            }
          }
          else {
            // it's a folder
            $files = glob($this->data->fullDir . $filename."/*.{".str_replace("|",",",$this->config['fileExtensions'])."}", GLOB_BRACE);    // to check if folder contains images
            if ($filename != '.' &&
                    $filename != '..' &&
                    $filename != '_thumbnails' &&
                    strpos($filename, $this->config['ignoreDetector']) == false && 
                    !empty($files) )
            {
              $lstAlbums[] = $this->PrepareData($filename, 'ALBUM');
            }
          }
        }
        closedir($dh);
      }

      // sort data
      usort($lstAlbums, array('gallery','Compare'));
      usort($lstImages, array('gallery','Compare'));

      $response = array('nano_status' => 'ok', 'nano_message' => '', 'album_content' => array_merge($lstAlbums, $lstImages));

      $this->SendData($response);
    }
    
    /**
     * CHECK IF THUMBNAIL SIZE IS ALLOWED (if not allowed: send error message and exit)
     * 
     * @param string $size
     * @return boolean
     */
    protected function CheckThumbnailSize( $size )
    {
      if( $this->config['thumbnails']['allowedSizeValues'] == "" ) {
        return $size;
      }
      
      $s=explode('|', $this->config['thumbnails']['allowedSizeValues']);
      if( is_array($s) ) {
        foreach($s as $one) {
          $one = trim($one);
          if( $one == $size ) {
            return $size;
          }
        }
      }
      
      $response = array( 'nano_status' => 'error', 'nano_message' => 'requested thumbnail size not allowed: '. $size );
      $this->SendData($response);
      exit;
      
    }
    

    
    /**
     * SEND THE RESPONSE BACK
     * 
     * @param string $response
     */
    protected function SendData( $response )
    {
      // set the Access-Control-Allow-Origin header
      $h=explode('|', $this->config['security']['allowOrigins']);
      $cnt=0;
      if( is_array($h) ) {
        foreach($h as $one) {
          $one = trim($one);
          $overwrite = false;
          if( $cnt == 0 ) {
            $overwrite=true;
          }
          header('Access-Control-Allow-Origin: ' . $one , $overwrite);
          $cnt++;
        }
      }
      
      // set the content-type header
      header('Content-Type: application/json; charset=utf-8');
    
      // return the data
      $output = json_encode($response);     // UTF-8 encoding is mandatory
      if (isset($_GET['jsonp'])) {
        // return in JSONP
        echo $_GET['jsonp'] . '(' . $output . ')';
      } else {
        // return in JSON
        echo $output;
      }
    
    }
    
    protected function setConfig()
    {
      //$config = parse_ini_file($filePath, true);
      $config = NANOCONFIG;
      
	      // general settings
	      
	      $this->config['folderPath']             = $config['config']['photosBasePath'];
	      $this->config['contentFolder']          = NANOFOLDER;
	      $this->config['fileExtensions']         = $config['config']['fileExtensions'];
	      $this->config['sortOrder']              = strtoupper($config['config']['sortOrder']);
	      $this->config['titleDescSeparator']     = $config['config']['titleDescSeparator'];
	      $this->config['albumCoverDetector']     = $config['config']['albumCoverDetector'];
	      $this->config['ignoreDetector']         = strtoupper($config['config']['ignoreDetector']);
	
	      // images
	      $this->config['images']['maxSize'] = 0;
	      $ms = $config['images']['maxSize'];
	      if( is_numeric(strval($ms)) ){
	        $this->config['images']['maxSize'] = $ms;
	      }
	      $iq = $config['images']['jpegQuality'];
	      $this->config['images']['jpegQuality'] = 85; // default jpeg quality
	      if( is_numeric(strval($iq)) ){
	        $this->config['images']['jpegQuality'] = $iq;
	      }
	      
	      // thumbnails
	      $tq = $config['thumbnails']['jpegQuality'];
	      $this->config['thumbnails']['jpegQuality'] = 85; // default jpeg quality
	      if( is_numeric(strval($tq)) ){
	        $this->config['thumbnails']['jpegQuality'] = $tq;
	      }
	
	      $tbq = $config['thumbnails']['blurredImageQuality'];
	      $this->config['thumbnails']['blurredImageQuality'] = 3; // default blurred image quality
	      if( is_numeric(strval($tbq)) ){
	        $this->config['thumbnails']['blurredImageQuality'] = $tbq;
	      }
	
	      $asv = trim($config['thumbnails']['allowedSizeValues']);
	      if( $asv != '' ) {
	         $this->config['thumbnails']['allowedSizeValues']=$asv;
	      }

      
      // security
      $this->config['security']['allowOrigins'] = $config['security']['allowOrigins'];
    }

    /**
     * RETRIEVE THE COVER IMAGE (THUMBNAIL) OF ONE ALBUM (FOLDER)
     * 
     * @param string $baseFolder
     * @return string
     */
    protected function GetAlbumCover($baseFolder)
    {

      // look for cover image
      $files = glob($baseFolder . '/' . $this->config['albumCoverDetector'] . '*.*');
      if (count($files) > 0) {
        $i = basename($files[0]);
        if (preg_match("/\.(" . $this->config['fileExtensions'] . ")*$/i", $i)) {
          $this->GetThumbnail2( $baseFolder, $i);
          return $baseFolder . $i;
        }
      }

      // no cover image found --> use the first image for the cover
      $i = $this->GetFirstImageFolder($baseFolder);
      if ($i != '') {
        $this->GetThumbnail2( $baseFolder, $i);
        return $baseFolder . $i;
      }

      return '';
    }

    /**
     * Retrieve the first image of one folder --> ALBUM THUMBNAIL
     * 
     * @param string $folder
     * @return string
     */
    protected function GetFirstImageFolder($folder)
    {
      $image = '';

      $dh       = opendir($folder);
      while (false !== ($filename = readdir($dh))) {
        if (is_file($folder . '/' . $filename) && preg_match("/\.(" . $this->config['fileExtensions'] . ")*$/i", $filename)) {
          $image = $filename;
          break;
        }
      }
      closedir($dh);

      return $image;
    }

    /**
     * 
     * @param object $a
     * @param object $b
     * @return int
     */
    protected function Compare($a, $b)
    {
      $al = strtolower($a->title);
      $bl = strtolower($b->title);
      if ($al == $bl) {
          return 0;
      }
      $b = false;
      switch ($this->config['sortOrder']) {
        case 'DESC' :
          if ($al < $bl) {
            $b = true;
          }
          break;
        case 'ASC':
        default:
          if ($al > $bl) {
            $b = true;
          }
          break;
      }
      return ($b) ? +1 : -1;
    }


    /**
     * RETRIEVE ONE IMAGE'S DISPLAY URL
     * 
     * @param type $baseFolder
     * @param type $filename
     */
    protected function GetImageDisplayURL( $baseFolder, $filename )
    {
    
      if( $this->config['images']['maxSize'] < 100 ) {
        return '';
      }

      if (!file_exists($this->config['folderPath']. $baseFolder . '_thumbnails' )) {
        mkdir($this->config['folderPath'] . $baseFolder . '_thumbnails', 0755, true );
      }

      
      $lowresFilenamePath = $this->config['folderPath'] . $baseFolder . '_thumbnails/' . $filename;
      $lowresFilename = $baseFolder . '_thumbnails/' . $filename;
      
      
      if (file_exists($lowresFilenamePath)) {
        if( filemtime($lowresFilenamePath) > filemtime($this->config['folderPath'] . $baseFolder . $filename) ) {
          // original image file is older as the image use for display
          $size = getimagesize($lowresFilenamePath);
          $this->currentItem->imgWidth  = $imgSize[0];
          $this->currentItem->imgHeight = $imgSize[1];
          return rawurlencode($this->CustomEncode($lowresFilename));
        }
      }

      $size = getimagesize($this->config['folderPath'] . $baseFolder . $filename);

      switch ($size['mime']) {
        case 'image/jpeg':
          $orgImage = imagecreatefromjpeg($this->config['folderPath'] . $baseFolder . $filename);
          break;
        case 'image/gif':
          $orgImage = imagecreatefromgif($this->config['folderPath'] . $baseFolder . $filename);
          break;
        case 'image/png':
          $orgImage = imagecreatefrompng($this->config['folderPath'] . $baseFolder . $filename);
          break;
        default:
          return false;
          break;
      }

      $width  = $size[0];
      $height = $size[1];

      if( $width <= $this->config['images']['maxSize'] && $height <= $this->config['images']['maxSize'] ) {
        // original image is smaller than max size -> return original file
        $this->currentItem->imgWidth  = $width;
        $this->currentItem->imgHeight = $height;
        return rawurlencode($this->CustomEncode($baseFolder . $filename));
      }
      
      $newWidth = $width;
      $newHeight = $height;
      if( $width > $height ) {
        if( $width > $this->config['images']['maxSize'] ) {
          $newWidth = $this->config['images']['maxSize'];
          $newHeight = $this->config['images']['maxSize'] / $width * $height;
        }
      }
      else {
        if( $height > $this->config['images']['maxSize'] ) {
          $newHeight = $this->config['images']['maxSize'];
          $newWidth = $this->config['images']['maxSize'] / $height * $width;
        }
      }
      
      $display_image = imagecreatetruecolor($newWidth, $newHeight);

      // Resize
      imagecopyresampled($display_image, $orgImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

      // save to disk
      switch ($size['mime']) {
        case 'image/jpeg':
          imagejpeg($display_image, $lowresFilenamePath, $this->config['images']['jpegQuality'] );
          break;
        case 'image/gif':
          imagegif($display_image, $lowresFilenamePath);
          break;
        case 'image/png':
          imagepng($display_image, $lowresFilenamePath, 1);
          break;
      }

      $this->currentItem->imgWidth  = $newWidth;
      $this->currentItem->imgHeight = $newHeight;
      return rawurlencode($this->CustomEncode($lowresFilename));

    }

    
    /**
     * RETRIEVE ONE IMAGE'S THUMBNAILS
     * 
     * @param type $baseFolder
     * @param type $filename
     * @return type
     */
    protected function GetThumbnail2( $baseFolder, $filename )
    {

      $s  = array( 'xs',   'sm',   'me',   'la',   'xl'  );
      $sw = array( 'wxs',  'wsm',  'wme',  'wla',  'wxl' );
      $sh = array( 'hxs',  'hsm',  'hme',  'hla',  'hxl' );
      for( $i = 0; $i < count($s) ; $i++ ) {

        $pi=pathinfo($filename);
        $tn= $pi['filename'] . '_' . $this->tn_size[$sw[$i]] . '_' . $this->tn_size[$sh[$i]] . '.' . $pi['extension'];
        if ( $this->GenerateThumbnail2($baseFolder, $filename, $tn, $this->tn_size[$sw[$i]], $this->tn_size[$sh[$i]], $i ) == true ) {
          $this->currentItem->t_url[$i]= $this->CustomEncode('/'.$baseFolder . '_thumbnails/' . $tn);
        }
        else {
          // fallback: original image (no thumbnail)
          $this->currentItem->t_url[$i]= $this->CustomEncode('/'.$baseFolder . $filename);
        }
      }
    }
    
    /**
     * GENERATE A SMALL BASE64 GIF WITH ONE IMAGE'S DOMINANT COLORS
     * 
     * @param type $baseFolder
     * @param type $filename
     * @return gif
     */
    protected function GetDominantColorsGIF( $img )
    {
      $size = getimagesize($img);
      switch ($size['mime']) {
        case 'image/jpeg':
          $orgImage = imagecreatefromjpeg($img);
          break;
        case 'image/gif':
          $orgImage = imagecreatefromgif($img);
          break;
        case 'image/png':
          $orgImage = imagecreatefrompng($img);
          break;
        default:
          return '';
          break;
      }
      $width  = $size[0];
      $height = $size[1];
      $thumb = imagecreate(3, 3);

      imagecopyresampled($thumb, $orgImage, 0, 0, 0, 0, 3, 3, $width, $height);

      ob_start(); 
      imagegif( $thumb );
      $image_data = ob_get_contents(); 
      ob_end_clean();         
     
      return base64_encode( $image_data );
    }

    /**
     * RETRIVE ONE IMAGE'S DOMINANT COLOR
     * 
     * @param type $baseFolder
     * @param type $filename
     * @return gif
     */
    protected function GetDominantColor( $img )
    {
      $size = getimagesize($img);
      switch ($size['mime']) {
        case 'image/jpeg':
          $orgImage = imagecreatefromjpeg($img);
          break;
        case 'image/gif':
          $orgImage = imagecreatefromgif($img);
          break;
        case 'image/png':
          $orgImage = imagecreatefrompng($img);
          break;
        default:
          return '#000000';
          break;
      }
      $width  = $size[0];
      $height = $size[1];
      
      $pixel = imagecreatetruecolor(1, 1);

      imagecopyresampled($pixel, $orgImage, 0, 0, 0, 0, 1, 1, $width, $height);

      $rgb = imagecolorat($pixel, 0, 0);
      $color = imagecolorsforindex($pixel, $rgb);
      $hex=sprintf('#%02x%02x%02x', $color[red], $color[green], $color[blue]);
      
      return $hex;
    }

    /**
     * GENERATE ONE THUMBNAIL
     * 
     * @param type $baseFolder
     * @param type $imagefilename
     * @param type $thumbnailFilename
     * @param type $thumbWidth
     * @param type $thumbHeight
     * @param type $s (reponsive size)
     * @return string
     */
    protected function GenerateThumbnail2($baseFolder, $imagefilename, $thumbnailFilename, $thumbWidth, $thumbHeight, $s)
    {
      if (!file_exists( $baseFolder . '_thumbnails' )) {
        mkdir( $baseFolder . '_thumbnails', 0755, true );
      }
        
      $generateThumbnail = true;
      if (file_exists($baseFolder . '_thumbnails/' . $thumbnailFilename)) {
        if( filemtime($baseFolder . '_thumbnails/' . $thumbnailFilename) > filemtime($baseFolder.$imagefilename) ) {
          // image file is older as the thumbnail file
          $generateThumbnail=false;
        }
      }
      
      $generateDominantColors = true;
      if( $s != 0 ) {
        $generateDominantColors=false;
      }
      else {
        $generateDominantColors= ! $this->GetDominantColors($baseFolder . $imagefilename, $baseFolder . '_thumbnails/' . $thumbnailFilename . '.data');
      }
     
      $size = getimagesize($baseFolder . $imagefilename);
      
      if( $generateThumbnail == true || $generateDominantColors == true ) {
        switch ($size['mime']) {
          case 'image/jpeg':
            $orgImage = imagecreatefromjpeg($baseFolder . $imagefilename);
            break;
          case 'image/gif':
            $orgImage = imagecreatefromgif($baseFolder . $imagefilename);
            break;
          case 'image/png':
            $orgImage = imagecreatefrompng($baseFolder . $imagefilename);
            break;
          default:
            return false;
            break;
        }
      }
        
      $width  = $size[0];
      $height = $size[1];

      $originalAspect = $width / $height;
      $thumbAspect    = intval($thumbWidth) / intval($thumbHeight);

      if ( $thumbWidth != 'auto' && $thumbHeight != 'auto' ) {
        // IMAGE CROP
        // some inspiration found in donkeyGallery (from Gix075) https://github.com/Gix075/donkeyGallery 
        if ($originalAspect >= $thumbAspect) {
          // If image is wider than thumbnail (in aspect ratio sense)
          $newHeight = $thumbHeight;
          $newWidth  = $width / ($height / $thumbHeight);
        } else {
          // If the thumbnail is wider than the image
          $newWidth  = $thumbWidth;
          $newHeight = $height / ($width / $thumbWidth);
        }

        // thumbnail image size
        $this->currentItem->t_width[$s]=$newWidth;
        $this->currentItem->t_height[$s]=$newHeight;

        if( $generateThumbnail == true ) {
          $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
          // Resize and crop
          imagecopyresampled($thumb, $orgImage,
                0 - ($newWidth - $thumbWidth) / 2,    // dest_x: Center the image horizontally
                0 - ($newHeight - $thumbHeight) / 2,  // dest-y: Center the image vertically
                0, 0, // src_x, src_y
                $newWidth, $newHeight, $width, $height);
        }
          
      } else {
        // NO IMAGE CROP
        if( $thumbWidth == 'auto' ) {
          $newWidth  = $width / $height * $thumbHeight;
          $newHeight = $thumbHeight;
        }
        else {
          $newHeight = $height / $width * $thumbWidth;
          $newWidth  = $thumbWidth;
        }

        // thumbnail image size
        $this->currentItem->t_width[$s]=$newWidth;
        $this->currentItem->t_height[$s]=$newHeight;
        
        if( $generateThumbnail == true ) {
          $thumb = imagecreatetruecolor($newWidth, $newHeight);

          // Resize
          imagecopyresampled($thumb, $orgImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        }
      }

      if( $generateThumbnail == true ) {
        switch ($size['mime']) {
          case 'image/jpeg':
            imagejpeg($thumb, $baseFolder . '/_thumbnails/' . $thumbnailFilename, $this->config['thumbnails']['jpegQuality'] );
            break;
          case 'image/gif':
            imagegif($thumb, $baseFolder . '/_thumbnails/' . $thumbnailFilename);
            break;
          case 'image/png':
            imagepng($thumb, $baseFolder . '/_thumbnails/' . $thumbnailFilename, 1);
            break;
        }
      }
      
      if( $generateDominantColors == true ) {
        // Dominant colorS -> GIF
        $dc3 = imagecreate($this->config['thumbnails']['blurredImageQuality'], $this->config['thumbnails']['blurredImageQuality']);
        imagecopyresampled($dc3, $orgImage, 0, 0, 0, 0, 3, 3, $width, $height);
        ob_start(); 
        imagegif( $dc3 );
        $image_data = ob_get_contents(); 
        ob_end_clean();         
        $this->currentItem->dcGIF= base64_encode( $image_data );
        
        // Dominant color -> HEX RGB
        $pixel = imagecreatetruecolor(1, 1);
        imagecopyresampled($pixel, $orgImage, 0, 0, 0, 0, 1, 1, $width, $height);
        $rgb = imagecolorat($pixel, 0, 0);
        $color = imagecolorsforindex($pixel, $rgb);
        $hex=sprintf('#%02x%02x%02x', $color['red'], $color['green'], $color['blue']);
        $this->currentItem->dc= $hex;

        // save to cache
        $fdc = fopen($baseFolder . '_thumbnails/' . $thumbnailFilename . '.data', 'w');
        if( $fdc ) { 
          fwrite($fdc, 'dc=' . $hex . "\n");
          fwrite($fdc, 'dcGIF=' . base64_encode( $image_data ));
          fclose($fdc);
        }
        else {
          // exit without dominant color
          return false;
        }
      }

      return true;
    }

    
    protected function GetDominantColors($fileImage, $fileDominantColors)
    {
    
      if (file_exists($fileDominantColors)) {
        if( filemtime($fileDominantColors) < filemtime($fileImage) ) {
          // image file is older as the dominant colors file
          return false;
        }

        // read cached data
        $cnt=0;
        $myfile = fopen($fileDominantColors, "r");
        if( $myfile ) { 
          while(!feof($myfile)) {
            $l=fgets($myfile);
            $s=explode('=', $l);
            if( is_array($s) ) {
              $property=trim($s[0]);
              $value=trim($s[1]);
              if( $property != '' &&  $value != '' ) {
                $this->currentItem->$property=$value;
                $cnt++;
              }
            }
          }
          fclose($myfile);
        }
        
        if( $cnt == 2 ) {
          // ok, 2 values found
          return true;
        }
      }
      
      return false;
      
    }

    /**
     * Extract title and description from filename
     * 
     * @param string $filename
     * @param boolean $isImage
     * @return \item
     */
    protected function GetMetaData($filename, $isImage)
    {
      $f=$filename;
  
      if ($isImage) {
        $filename = $this->file_ext_strip($filename);
      }

      $oneItem = new galleryItem();
      if (strpos($filename, $this->config['titleDescSeparator']) > 0) {
        // title and description
        $s              = explode($this->config['titleDescSeparator'], $filename);
        $oneItem->title = $this->CustomEncode($s[0]);
        if ($isImage) {
          $oneItem->description = $this->CustomEncode(preg_replace('/.[^.]*$/', '', $s[1]));
        } else {
          $oneItem->description = $this->CustomEncode($s[1]);
        }
      } else {
        // only title
        if ($isImage) {
          $oneItem->title = $this->CustomEncode($filename);  //(preg_replace('/.[^.]*$/', '', $filename));
        } else {
          $oneItem->title = $this->CustomEncode($filename);
        }
        $oneItem->description = '';
      }

      $oneItem->title = str_replace($this->config['albumCoverDetector'], '', $oneItem->title);   // filter cover detector string
        
      // the title (=filename) is the ID
      $oneItem->ID= $oneItem->title;
        
      // read meta data from external file (only images)
      if ($isImage) {
        if( file_exists( $this->data->fullDir . '/' . $filename . '.txt' ) ) {
          $myfile = fopen($this->data->fullDir . '/' . $filename . '.txt', "r") or die("Unable to open file!");
          while(!feof($myfile)) {
            $l=fgets($myfile);
            $s=explode('=', $l);
            if( is_array($s) ) {
              $property=trim($s[0]);
              $value=trim($s[1]);
              if( $property != '' &&  $value != '' ) {
                $oneItem->$property=$value;
              }
            }
          }
          fclose($myfile);
        }
        
      }
      return $oneItem;
    }

    /**
     * Returns only the file extension (without the period).
     * 
     * @param string $filename
     * @return string
     */
    protected function file_ext($filename)
    {
      if (!preg_match('/./', $filename)) {
        return '';
      }
      return preg_replace('/^.*./', '', $filename);
    }

    /**
     * Returns the file name, less the extension.
     * 
     * @param string $filename
     * @return string
     */
    protected function file_ext_strip($filename)
    {
      return preg_replace('/.[^.]*$/', '', $filename);
    }

    
    
    /**
     * 
     * @param string $s
     * @return string
     */
    protected function CustomEncode($s)
    {
      return $this->toUTF8(($s));
      //return \ForceUTF8\Encoding::toUTF8(($s));
      //return \ForceUTF8\Encoding::fixUTF8(($s));
    }

    /**
     * 
     * @param type $s
     * @return type
     */
    protected function CustomDecode($s)
    {
      return utf8_decode($s);
      // return $s;
    }


    /**
     * Returns the number of items in one disk folder.
     * 
     * @param type $d
     * @return integer
     */
    protected function AlbumCountItems( $d )
    {
      $cnt = 0;
      $dh = opendir($d);
      
      // loop the folder to retrieve images and albums
      if ($dh != false) {
        while (false !== ($filename = readdir($dh))) {
          
          if (is_file($this->data->fullDir . $filename) ) {
            // it's a file
            if ($filename != '.' &&
                    $filename != '..' &&
                    $filename != '_thumbnails' &&
                    preg_match("/\.(" . $this->config['fileExtensions'] . ")*$/i", $filename) &&
                    strpos($filename, $this->config['ignoreDetector']) == false &&
                    strpos($filename, $this->config['albumCoverDetector']) == false )
            {
              $cnt++;
            }
          }
          else {
            // it's a folder
            if ($filename != '.' &&
                    $filename != '..' &&
                    $filename != '_thumbnails' &&
                    strpos($filename, $this->config['ignoreDetector']) == false && 
                    !empty($filename) )
            {
              $cnt++;
            }
          }
        }
      }
      else {
        closedir($dh);
      }
      
      return $cnt;

   }

    
    
    protected function PrepareData($filename, $kind)
    {
      // $oneItem = new item();
      $this->currentItem = new galleryItem();
      // if (is_file($this->data->fullDir . $filename) && preg_match("/\.(" . $this->config['fileExtensions'] . ")*$/i", $filename)) {
      if ( $kind == 'IMAGE' ) {
        // ONE IMAGE
        $this->currentItem->kind            = 'image';
        
        $e = $this->GetMetaData($filename, true);
        $this->currentItem->title           = $e->title;
        $this->currentItem->description     = $e->description;
        // $this->currentItem->src             = rawurlencode($this->CustomEncode($this->config['contentFolder'] . $this->album . '/' . $filename));
        $this->currentItem->originalURL     = rawurlencode($this->CustomEncode('/'.$this->config['contentFolder'] . $this->album . '/' . $filename));
        $this->currentItem->src             = $this->GetImageDisplayURL('/'.$this->data->fullDir, $filename);
        if( $this->currentItem->src == '' ) {
          $this->currentItem->src = $this->currentItem->originalURL;
          $imgSize = getimagesize($this->data->fullDir . '/' . $filename);
          $this->currentItem->imgWidth        = $imgSize[0];
          $this->currentItem->imgHeight       = $imgSize[1];
        }

        $this->GetThumbnail2($this->data->fullDir, $filename);
        $this->currentItem->albumID         = rawurlencode($this->albumID);
        if ($this->albumID == '0' || $this->albumID == '') {
            $this->currentItem->ID          = rawurlencode($this->CustomEncode($e->ID));
        } else {
            $this->currentItem->ID          = rawurlencode($this->albumID . $this->CustomEncode('/' . $e->ID));
        }
        return $this->currentItem;
      }
      else {
        // ONE ALBUM
        $this->currentItem->kind            = 'album';

        $e = $this->GetMetaData($filename, false);
        $this->currentItem->title           = $e->title;
        $this->currentItem->description     = $e->description;

        $this->currentItem->albumID         = rawurlencode($this->albumID);
        if ($this->albumID == '0' || $this->albumID == '') {
          $this->currentItem->ID            = rawurlencode($this->CustomEncode($filename));
        } else {
          $this->currentItem->ID            = rawurlencode($this->albumID . $this->CustomEncode('/' . $filename));
        }
        $ac=$this->GetAlbumCover($this->data->fullDir . $filename . '/');
        if ( $ac != '' ) {
          // $path = '';
          // if ($this->albumID == '0') {
            // $path = $filename;
          // } else {
            // $path = $this->album . '/' . $filename;
          // }
          $this->currentItem->cnt           = $this->AlbumCountItems( $this->data->fullDir . $filename . '/');
          return $this->currentItem;
        }
      }
    }


/*
Copyright (c) 2008 Sebastián Grignoli
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions
are met:
1. Redistributions of source code must retain the above copyright
   notice, this list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright
   notice, this list of conditions and the following disclaimer in the
   documentation and/or other materials provided with the distribution.
3. Neither the name of copyright holders nor the names of its
   contributors may be used to endorse or promote products derived
   from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED
TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL COPYRIGHT HOLDERS OR CONTRIBUTORS
BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
POSSIBILITY OF SUCH DAMAGE.
*/

/**
 * @author   "Sebastián Grignoli" <grignoli@gmail.com>
 * @package  Encoding
 * @version  2.0
 * @link     https://github.com/neitanod/forceutf8
 * @example  https://github.com/neitanod/forceutf8
 * @license  Revised BSD
  */

//namespace ForceUTF8;



  protected static $win1252ToUtf8 = array(
        128 => "\xe2\x82\xac",

        130 => "\xe2\x80\x9a",
        131 => "\xc6\x92",
        132 => "\xe2\x80\x9e",
        133 => "\xe2\x80\xa6",
        134 => "\xe2\x80\xa0",
        135 => "\xe2\x80\xa1",
        136 => "\xcb\x86",
        137 => "\xe2\x80\xb0",
        138 => "\xc5\xa0",
        139 => "\xe2\x80\xb9",
        140 => "\xc5\x92",

        142 => "\xc5\xbd",


        145 => "\xe2\x80\x98",
        146 => "\xe2\x80\x99",
        147 => "\xe2\x80\x9c",
        148 => "\xe2\x80\x9d",
        149 => "\xe2\x80\xa2",
        150 => "\xe2\x80\x93",
        151 => "\xe2\x80\x94",
        152 => "\xcb\x9c",
        153 => "\xe2\x84\xa2",
        154 => "\xc5\xa1",
        155 => "\xe2\x80\xba",
        156 => "\xc5\x93",

        158 => "\xc5\xbe",
        159 => "\xc5\xb8"
  );

    protected static $brokenUtf8ToUtf8 = array(
        "\xc2\x80" => "\xe2\x82\xac",

        "\xc2\x82" => "\xe2\x80\x9a",
        "\xc2\x83" => "\xc6\x92",
        "\xc2\x84" => "\xe2\x80\x9e",
        "\xc2\x85" => "\xe2\x80\xa6",
        "\xc2\x86" => "\xe2\x80\xa0",
        "\xc2\x87" => "\xe2\x80\xa1",
        "\xc2\x88" => "\xcb\x86",
        "\xc2\x89" => "\xe2\x80\xb0",
        "\xc2\x8a" => "\xc5\xa0",
        "\xc2\x8b" => "\xe2\x80\xb9",
        "\xc2\x8c" => "\xc5\x92",

        "\xc2\x8e" => "\xc5\xbd",


        "\xc2\x91" => "\xe2\x80\x98",
        "\xc2\x92" => "\xe2\x80\x99",
        "\xc2\x93" => "\xe2\x80\x9c",
        "\xc2\x94" => "\xe2\x80\x9d",
        "\xc2\x95" => "\xe2\x80\xa2",
        "\xc2\x96" => "\xe2\x80\x93",
        "\xc2\x97" => "\xe2\x80\x94",
        "\xc2\x98" => "\xcb\x9c",
        "\xc2\x99" => "\xe2\x84\xa2",
        "\xc2\x9a" => "\xc5\xa1",
        "\xc2\x9b" => "\xe2\x80\xba",
        "\xc2\x9c" => "\xc5\x93",

        "\xc2\x9e" => "\xc5\xbe",
        "\xc2\x9f" => "\xc5\xb8"
  );

  protected static $utf8ToWin1252 = array(
       "\xe2\x82\xac" => "\x80",

       "\xe2\x80\x9a" => "\x82",
       "\xc6\x92"     => "\x83",
       "\xe2\x80\x9e" => "\x84",
       "\xe2\x80\xa6" => "\x85",
       "\xe2\x80\xa0" => "\x86",
       "\xe2\x80\xa1" => "\x87",
       "\xcb\x86"     => "\x88",
       "\xe2\x80\xb0" => "\x89",
       "\xc5\xa0"     => "\x8a",
       "\xe2\x80\xb9" => "\x8b",
       "\xc5\x92"     => "\x8c",

       "\xc5\xbd"     => "\x8e",


       "\xe2\x80\x98" => "\x91",
       "\xe2\x80\x99" => "\x92",
       "\xe2\x80\x9c" => "\x93",
       "\xe2\x80\x9d" => "\x94",
       "\xe2\x80\xa2" => "\x95",
       "\xe2\x80\x93" => "\x96",
       "\xe2\x80\x94" => "\x97",
       "\xcb\x9c"     => "\x98",
       "\xe2\x84\xa2" => "\x99",
       "\xc5\xa1"     => "\x9a",
       "\xe2\x80\xba" => "\x9b",
       "\xc5\x93"     => "\x9c",

       "\xc5\xbe"     => "\x9e",
       "\xc5\xb8"     => "\x9f"
    );

  static function toUTF8($text){
  /**
   * Function \ForceUTF8\Encoding::toUTF8
   *
   * This function leaves UTF8 characters alone, while converting almost all non-UTF8 to UTF8.
   *
   * It assumes that the encoding of the original string is either Windows-1252 or ISO 8859-1.
   *
   * It may fail to convert characters to UTF-8 if they fall into one of these scenarios:
   *
   * 1) when any of these characters:   ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞß
   *    are followed by any of these:  ("group B")
   *                                    ¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶•¸¹º»¼½¾¿
   * For example:   %ABREPRESENT%C9%BB. «REPRESENTÉ»
   * The "«" (%AB) character will be converted, but the "É" followed by "»" (%C9%BB)
   * is also a valid unicode character, and will be left unchanged.
   *
   * 2) when any of these: àáâãäåæçèéêëìíîï  are followed by TWO chars from group B,
   * 3) when any of these: ðñòó  are followed by THREE chars from group B.
   *
   * @name toUTF8
   * @param string $text  Any string.
   * @return string  The same string, UTF8 encoded
   *
   */

    if(is_array($text))
    {
      foreach($text as $k => $v)
      {
        $text[$k] = self::toUTF8($v);
      }
      return $text;
    } elseif(is_string($text)) {

      if ( function_exists('mb_strlen') && ((int) ini_get('mbstring.func_overload')) & 2) {
         $max = mb_strlen($text,'8bit');
      } else {
         $max = strlen($text);
      }

      $buf = "";
      for($i = 0; $i < $max; $i++){
          $c1 = $text{$i};
          if($c1>="\xc0"){ //Should be converted to UTF8, if it's not UTF8 already
            $c2 = $i+1 >= $max? "\x00" : $text{$i+1};
            $c3 = $i+2 >= $max? "\x00" : $text{$i+2};
            $c4 = $i+3 >= $max? "\x00" : $text{$i+3};
              if($c1 >= "\xc0" & $c1 <= "\xdf"){ //looks like 2 bytes UTF8
                  if($c2 >= "\x80" && $c2 <= "\xbf"){ //yeah, almost sure it's UTF8 already
                      $buf .= $c1 . $c2;
                      $i++;
                  } else { //not valid UTF8.  Convert it.
                      $cc1 = (chr(ord($c1) / 64) | "\xc0");
                      $cc2 = ($c1 & "\x3f") | "\x80";
                      $buf .= $cc1 . $cc2;
                  }
              } elseif($c1 >= "\xe0" & $c1 <= "\xef"){ //looks like 3 bytes UTF8
                  if($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf"){ //yeah, almost sure it's UTF8 already
                      $buf .= $c1 . $c2 . $c3;
                      $i = $i + 2;
                  } else { //not valid UTF8.  Convert it.
                      $cc1 = (chr(ord($c1) / 64) | "\xc0");
                      $cc2 = ($c1 & "\x3f") | "\x80";
                      $buf .= $cc1 . $cc2;
                  }
              } elseif($c1 >= "\xf0" & $c1 <= "\xf7"){ //looks like 4 bytes UTF8
                  if($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf" && $c4 >= "\x80" && $c4 <= "\xbf"){ //yeah, almost sure it's UTF8 already
                      $buf .= $c1 . $c2 . $c3 . $c4;
                      $i = $i + 3;
                  } else { //not valid UTF8.  Convert it.
                      $cc1 = (chr(ord($c1) / 64) | "\xc0");
                      $cc2 = ($c1 & "\x3f") | "\x80";
                      $buf .= $cc1 . $cc2;
                  }
              } else { //doesn't look like UTF8, but should be converted
                      $cc1 = (chr(ord($c1) / 64) | "\xc0");
                      $cc2 = (($c1 & "\x3f") | "\x80");
                      $buf .= $cc1 . $cc2;
              }
          } elseif(($c1 & "\xc0") == "\x80"){ // needs conversion
                if(isset(self::$win1252ToUtf8[ord($c1)])) { //found in Windows-1252 special cases
                    $buf .= self::$win1252ToUtf8[ord($c1)];
                } else {
                  $cc1 = (chr(ord($c1) / 64) | "\xc0");
                  $cc2 = (($c1 & "\x3f") | "\x80");
                  $buf .= $cc1 . $cc2;
                }
          } else { // it doesn't need conversion
              $buf .= $c1;
          }
      }
      return $buf;
    } else {
      return $text;
    }
  }

  static function toWin1252($text, $option = self::WITHOUT_ICONV) {
    if(is_array($text)) {
      foreach($text as $k => $v) {
        $text[$k] = self::toWin1252($v, $option);
      }
      return $text;
    } elseif(is_string($text)) {
      return static::utf8_decode($text, $option);
    } else {
      return $text;
    }
  }

  static function toISO8859($text) {
    return self::toWin1252($text);
  }

  static function toLatin1($text) {
    return self::toWin1252($text);
  }

  static function fixUTF8($text, $option = self::WITHOUT_ICONV){
    if(is_array($text)) {
      foreach($text as $k => $v) {
        $text[$k] = self::fixUTF8($v, $option);
      }
      return $text;
    }

    $last = "";
    while($last <> $text){
      $last = $text;
      $text = self::toUTF8(static::utf8_decode($text, $option));
    }
    $text = self::toUTF8(static::utf8_decode($text, $option));
    return $text;
  }

  static function UTF8FixWin1252Chars($text){
    // If you received an UTF-8 string that was converted from Windows-1252 as it was ISO8859-1
    // (ignoring Windows-1252 chars from 80 to 9F) use this function to fix it.
    // See: http://en.wikipedia.org/wiki/Windows-1252

    return str_replace(array_keys(self::$brokenUtf8ToUtf8), array_values(self::$brokenUtf8ToUtf8), $text);
  }

  static function removeBOM($str=""){
    if(substr($str, 0,3) == pack("CCC",0xef,0xbb,0xbf)) {
      $str=substr($str, 3);
    }
    return $str;
  }

  public static function normalizeEncoding($encodingLabel)
  {
    $encoding = strtoupper($encodingLabel);
    $encoding = preg_replace('/[^a-zA-Z0-9\s]/', '', $encoding);
    $equivalences = array(
        'ISO88591' => 'ISO-8859-1',
        'ISO8859'  => 'ISO-8859-1',
        'ISO'      => 'ISO-8859-1',
        'LATIN1'   => 'ISO-8859-1',
        'LATIN'    => 'ISO-8859-1',
        'UTF8'     => 'UTF-8',
        'UTF'      => 'UTF-8',
        'WIN1252'  => 'ISO-8859-1',
        'WINDOWS1252' => 'ISO-8859-1'
    );

    if(empty($equivalences[$encoding])){
      return 'UTF-8';
    }

    return $equivalences[$encoding];
  }

  public static function encode($encodingLabel, $text)
  {
    $encodingLabel = self::normalizeEncoding($encodingLabel);
    if($encodingLabel == 'UTF-8') return Encoding::toUTF8($text);
    if($encodingLabel == 'ISO-8859-1') return Encoding::toLatin1($text);
  }

  protected static function utf8_decode($text, $option)
  {
    if ($option == self::WITHOUT_ICONV || !function_exists('iconv')) {
       $o = utf8_decode(
         str_replace(array_keys(self::$utf8ToWin1252), array_values(self::$utf8ToWin1252), self::toUTF8($text))
       );
    } else {
       $o = iconv("UTF-8", "Windows-1252" . ($option == self::ICONV_TRANSLIT ? '//TRANSLIT' : ($option == self::ICONV_IGNORE ? '//IGNORE' : '')), $text);
    }
    return $o;
  }



}
?>
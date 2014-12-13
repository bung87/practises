<?php
class Thumb{
   private $image;
   private $tWidth;  
   private $tHeight;
   private $folder;
   private $cutpos;
   private $onError;
 
  public function __construct(){
      $this->image = array();
      $this->cutpos = 2;
  }
  public function Thumb(){
      $this->__construct();
  }
 
  public function setErrorHandle($errorFunction){
      if (!empty($errorFunction)){
          $this->onError = $errorFunction;
      }
      return $this;
  }
 
  public function setImage($image){
      $onError = $this->onError;
      if( empty($image) ){
          $onError('Fatal Error:No image input!');
      }
      if( file_exists($image) ){
          $infos = pathinfo($image);
          if(in_array($infos['extension'], array('jpg', 'gif', 'png'))){
              $this->image = array(
                    'path' => $image,
                    'name' => $infos['filename'],
                    'ext' => strtolower($infos['extension'])
                   );
          } else {
              $onError('Fatal Error:Image type must be JPG or GIF or PNG!');
          }
       } else {
              $onError('Fatal Error:Image does not exists!');
       }
       return $this;
  }
 
  public function setSize($tWidth, $tHeight){
      if( is_int($tWidth) && is_int($tHeight)){
          $this->tWidth = $tWidth;
          $this->tHeight = $tHeight;
      }
      return $this;
  }
   
    public function setCutPosition($pos){
        $this->cutpos = intval($pos);
        return $this;
    }
 
  public function setFolder($folder){
      if( is_dir($folder) && is_writeable($folder) ){
          $this->folder = $folder;
      }
      return $this;
  }
 
  public function save(){
      //Check if canbe run
      empty($this->image) && $onError('No image given!');
      empty($this->folder) && $onError('Folder dose not exists or can not write!');
      ($this->tWidth && $this->tHeight) or $onError('Size error!');
  
      //Define the image types
      $types = array('gif'=>'gif', 'jpg'=>'jpeg', 'png'=>'png');
      //Check required functions
      if(function_exists("imagecopyresampled") &&
            function_exists("imagecreatetruecolor")) {
           $funcCreate = "imagecreatetruecolor";
           $funcResize = "imagecopyresampled";
        } elseif (function_exists("imagecreate") &&
            function_exists("imagecopyresized")) {
            $funcCreate = "imagecreate";
            $funcResize = "imagecopyresized";
        } else {
            $onError('Fatal Error:Importent function disabled!');
        }
  //Start to calculate
        $ratio=$this->tWidth/$this->tHeight;
  list($width, $height) = getimagesize($this->image['path']);
  if( $width < $this->tWidth && $height > $this->tHeight){
      $this->tWidth=$width;
      $this->tHeight=$width/$ratio;
  }
  if( $height < $this->tHeight && $width > $this->tWidth){
    $this->tHeight=$height;
    $this->tWidth=$height*$ratio;
  }
  if($width < $this->tWidth && $height < $this->tHeight){
    $this->tWidth=$width;
    $this->tHeight=$width/$ratio;
  }
  $cf = 'imagecreatefrom' . $types[$this->image['ext']];
  $oImg = $cf($this->image['path']);
  $oRate = $width/$height;
        //Cute Start Position
  $_x = 0;
        $_y = 0;
        $tRate = $this->tWidth/$this->tHeight;
  if ($tRate > $oRate) {
     $nHeight = round($this->tWidth/$oRate);
     $nWidth = $this->tWidth;
  } else {
     $nWidth = round($this->tHeight*$oRate);
     $nHeight = $this->tHeight;
  }
  
        switch($this->cutpos){
            case 1:
                break;
            case 2:
                $_x = round(($nWidth-$this->tWidth)/2);
                $_y = round(($nHeight-$this->tHeight)/2);
                break;
            case 3:
                if( $tRate > $oRate ){
                    $_y = $nHeight-$this->tHeight;
                }else{
                    $_x = $nWidth-$this->tWidth;
                }
                break;
            default:
                break;
        }
  
  $process = $funcCreate($nWidth, $nHeight);
  
  $funcResize($process, $oImg, 0, 0, 0, 0,
     $nWidth, $nHeight,
     $width, $height);
  $thumb = $funcCreate($this->tWidth, $this->tHeight);
  $funcResize($thumb, $process, 0, 0, $_x, $_y,
     $this->tWidth, $this->tHeight,
     $this->tWidth, $this->tHeight);
  //Free the resource
  imagedestroy($process);
  imagedestroy($oImg);
  
  //Output Image file

  $outputfile = $this->image['name'] . '.thumb.' . $this->image['ext'];
  $op = 'image' . $types[$this->image['ext']];
  $statu = $op($thumb, $this->folder . $outputfile);
      if($statu){
           return $outputfile;
      } else {
           return false;
      }
  }

}


?>
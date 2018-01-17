<?php        //nx: change to graphics() or GD()
class graphics{
    //all operations ONLY applies to the current object , i.e this object always the distination img resource and the other img resourse always the source
/*
nx:
compare libs: http://php.net/manual/en/refs.utilspec.image.php
imagefilltoborder() ??
imagegammacorrect()
imagegrabscreen() , imagegrabwindow() : grab() or capture()
imagesavealpha()
imagesetstyle()
imagestringup VS imagestring , imagefttext
iptcparse() , iptcembed()
*/
function __construct($img,$fill=null,$type=null,$trueColor=true){
if(is_string($img)){ //&&!empty($img)
if($fill=='string'||$fill=='data')$this->img=imageCreateFromString($img);
else{
if(empty($type))$type=$this->ext($img);
if($type=='jpg'||$type=='jpeg'){$this->img=imagecreatefromjpeg($img);$this->type='jpeg';}
elseif($type=='png'){$this->img=imagecreatefrompng($img);$this->type='png';}
elseif($type=='gif '){$this->img=imagecreatefromgif($img);$this->type='gif';}
elseif($type=='gd2'){$this->img=imagecreatefromgd2($img);$this->type='gd2';}
elseif($type=='gd'){$this->img=imagecreatefromgd($img);$this->type='gd';}
elseif($type=='bmp'){$this->img=imagecreatefrombmp($img);$this->type='bmp';}
elseif($type=='webp'){$this->img=imagecreatefromwebp($img);$this->type='webp';}
elseif($type=='xbm'){$this->img=imagecreatefromxbm($img);$this->type='xbm';}
else $this->img=imageCreateFromString($img); //type not supplied & no  ext
}
}
elseif($trueColor)$this->img=imagecreatetruecolor($img,$fill); //or if(is_array($img)) --> new image([w,h],true);
else $this->img=imagecreate($img,$fill);

if(empty($this->type))$this->type='png'; //default image type (good if the image includes texts & lines)
if($fill){
$dim=$this->dimensions();
imagefilledrectangle($this->img, 0, 0,$dim[0]-1,$dim[1]-1, $fill);
}

/*
//assign some basic colors  //use: $red=getColor('red') ;
$this->colors=[
'red'=>imagecolorallocate($this->img,255,0,0),
'green'=>imagecolorallocate($img,0,255,0),
'blue'=>imagecolorallocate($img,0,0,255),
'white'=>imagecolorallocate($img,255,255,255),
'black'=>imagecolorallocate($img,0,0,0),
//nx: yellow,
];*/

$this->color=$this->getColor('white'); //default color
return $this->img;
}

#--- Drawing functions

function pixel($x=0,$y=0,$color=null){if(!$color)$color=$this->color;return imagesetpixel($this->img,$x,$y,$color);} //nx: Example http://php.net/manual/en/function.imagesetpixel.php

function line($a=[0,0],$b=[0,0],$color=null,$n=null){
    //imagedashedline() is deprecated , use $color[] instead
    //$color[] is a style pattern array each element represents a pixel ex: $color=[$red,$red,$white,$white,$white,IMG_COLOR_TRANSPARENT], to put a transparent pixel use IMG_COLOR_TRANSPARENT
    if(is_array($color)){
       imagesetStyle($this->img,$color);
       return imageline($this->img,IMG_COLOR_STYLED);
      }elseif($color===true||is_int($n)){
          //draw a dashed line  ; use $n to scale the line ex: if n=3 : $color=[$c,$c,$c,T,T,T]
          if(!is_int($n))$n=3;
           $color=array_merge(array_fill(0,$n,$this->color),array_fill(0,$n,IMG_COLOR_TRANSPARENT));
           return $this->line($a,$b,$color);
      }else{
      if(!$color)$color=$this->color;
      return imageline($this->img,$a[0],$a[1],$b[0],$b[1],$color);
      }
    }

function line_h($a=[0,0],$length=100,$color=null,$n=null){return $this->line($a,[$a[0]+$length,$a[1]],$color,$n);}
function line_v($a=[0,0],$length=100,$color=null,$n=null){return $this->line($a,[$a[0],$a[1]+$length],$color,$n);}

function ellipse($a=[0,0],$dim=[],$color=null,$fill=false){if(!$color)$color=$this->color;if(!$fill)return imageellipse ($this->img,$a[0],$a[1],$dim[0],$dim[1],$color);else return imagefilledellipse($this->img,$a[0],$a[1],$dim[0],$dim[1],$color);}
 function arc($r,$center=null,$color=null,$part=[0,0],$style=null){
    if(!$color)$color=$this->color;
    if(!$center)$center=$this->getCenter();
    if(!is_array($r))$r=[$r,$r];
    elseif(!isset($r[1]))$r[1]=$r[0];
    if(!$style)return imagearc($this->img,$center[0],$center[1],$r[0]*2,$r[1]*2,$color,$part[0],$part[1],$color);
    //Draw a filled arc
    if($style=='pie')$style=IMG_ARC_PIE;
    elseif($style=='chord')$style=IMG_ARC_CHORD;
    elseif($style=='noFill')$style=IMG_ARC_NOFILL;
    elseif($style=='edged')$style=IMG_ARC_EDGED;
    return imagefilledarc($this->img,$center[0],$center[1],$r[0]*2,$r[1]*2,$part[0],$part[1],$color,$style);
    }

function circle($center=[0,0],$radius,$color=null,$fill=false){
        return $this->ellipse($center,$radius,$color,$fill);
        //or return $this->arc($radius,$center,$color,[0,360]);
    }

function polygon($points,$color=null,$mode=null){
//$points=[x1,y1,x2,y2,x3,y3,....] or [ [x1,y1],[x2,y2],.. ]
if(!$color)$color=$this->color;
$num=count($points)/2;
if($mode==='open')return imageopenpolygon($this->img,$points,$num,$color ); //php=7
elseif($mode)return imagefilledpolygon($this->img,$points,$num,$color ); //filled
return imagepolygon($this->img,$points,$num,$color );
}

function rectangle($p1=[0,0],$p2=[0,0],$color=null,$fill=false){
    if(!$color)$color=$this->color;
    if($fill)return imagefilledrectangle($this->img,$p1[0],$p1[1],$p2[0],$p2[1],$color);
    else return imagerectangle($this->img,$p1[0],$p1[1],$p2[0],$p2[1],$color);
}
function square($p1=[0,0],$length,$color=null,$fill=false){
 //p1 is top-left corner
 return $this->rectangle($p1,[$p1[0]+$length,$p1[1]+$length],$color,$fill);
}
#--- Color functions
function color($r=null,$g=null,$b=null,$alpha=null){
if($r==null)return $this->color;
if($g==null)$g=0;
if($b==null)$b=0;
if($alpha==null)$c=imagecolorallocate($this->img,$r,$g,$b);else $c=imagecolorallocatealpha($this->img,$r,$g,$b);
//$this->color=$c;
return $c;
}
function fill($color=null,$x=0,$y=0,$border=null){if(!$color)$color=$this->color;if(!$border)return imagefill($this->img,$x,$y,$color);else return imageFillToBorder($this->img,$x,$y,$border,$color);}

function getColor($x=0,$y=0,$z=null,$alpha=true){
    if($x=='white')return imagecolorallocate($this->img,255,255,255);
    elseif($x=='black')return imagecolorallocate($this->img,0,0,0);
    elseif($x=='red')return imagecolorallocate($this->img,255,0,0);
    elseif($x=='green')return imagecolorallocate($this->img,0,255,0);
    elseif($x=='blue')return imagecolorallocate($this->img,0,0,255);
    //nx: yellow,

    if(!$z)return imagecolorat($this->img,$x,$y);
    elseif(!$alpha) return imageColorClosest($this->img,$x,$y,$z);//r,g,b
    elseif($alpha===1)return imageColorClosestHWB($this->img,$x,$y,$z); //get the color which has a hue,white,blackness closest to this color
    else return imageColorClosestAlpha($this->img,$x,$y,$z);
    }
function closest($r=0,$g=0,$b=0,$alpha=null){if($alpha==null)return imagecolorclosest($this->img,$r,$g,$b);elseif($alpha===true)return imagecolorclosesthwb($this->img,$r,$g,$b);else return imagecolorclosestalpha($this->img,$r,$g,$b,$alpha);}
function index($r=0,$g=0,$b=0,$alpha=null,$resolve=false){/*colorIndex()*/if(!$resolve){if($alpha==null)return imagecolorexact($this->img,$r,$g,$b);else return imagecolorexactalpha ($this->img,$r,$g,$b,$alpha);}else{if($alpha==null)return imagecolorresolve($this->img,$r,$g,$b);else imagecolorresolvealpha($this->img,$r,$g,$b,$alpha);}}
function getIndex($r=0,$g=0,$b=0,$alpha=null,$resolve=false){return $this->index($r,$g,$b,$alpha,$resolve);} //compitable with getColor()
function indexColors($index){return imagecolorsforindex($this->img,$index );}
//function match($img,$x=false){if(!$x)return imagecolormatch ($this->img,$img);else return imagecolormatch ($img,$this->img);}
function match($img){imagecolormatch ($this->img,$img);}
function colorSet($r=0,$g=0,$b=0,$index=0,$alpha=0){return imageColorSet($this->img,$r,$g,$b,$index,$alpha);}
function Colors(){return imagecolorstotal($this->img);} //totalColors()
function transparent($color=null){if(!$color)$color=$this->color;return imagecolortransparent($this->img,$color);} //convert the color to a transparent
function copyPalette($img){imagepalettecopy($this->img,$img);}
function toTrueColor(){return imagePaletteToTrueColor($this->img);}
function toPalette($colors=255,$dither=false){return imageTrueColorToPalette($this->img,$dither,$colors);}
function trueColor(){return imageistruecolor($this->img); }


#--- info functions
function GD(){return extension_loaded('gd')&&function_exists('gd_info');}
function ext($f,$mode=null,$x=true){
    if($mode=='type')return image_type_to_extension($f,$x); //converts IMAGE_PNG to png($x=false) or .png($x=true)
    return strtolower(ltrim(strrchr($f,'.'),'.'));
    }
function mime($type){return image_type_to_mime_type($type);}
function info(){return gd_info();}
function dimensions(){  //dimentions ; dont use getImageSize() because it will load the image again to memory , and if the image in a semote server it will download it each time you call getImageSize() then load it to memory
    $w=imagesx($this->img);
    $h=imagesy($this->img);
    return [$w,$h,$w/2,$h/2]; //width,height,center_x,center_y
}
function size(){return $this->dimensions();} //or return FileSize

function type($x=null){
  //not GD
  if($x==null)return image_type_to_mime_type($this->img);
  else return image_type_to_extension($this->img,$x); //$x:to include (.)
}
function types(){return imagetypes();}
function supported($types=[]){
    if(!imagetypes())return false; //all types are not supported ; or: foreach($types as $v)$types[$v]=false; return $types
    if(!is_array($types)){
        if($types=='png'){if(IMG_PNG)return true;else return false;}
        if($types=='JPG'||$types=='JPEG'){if(IMG_JPG)return true;else return false;}
        if($types=='WBMP'){if(IMG_WBMP)return true;else return false;}
        if($types=='BMP'){if(IMG_BMP)return true;else return false;}
        if($types=='XPM'){if(IMG_XPM)return true;else return false;}
        if($types=='WEBP'){if(IMG_WEBP)return true;else return false;}
    }

    foreach($types as $v){
      $types[$v]=$this->supported($v);
    }
    return $types;
}
function affine($affine,$clip){return imageAffine($this->img,$affine,$clip);} //nx: imageAffineMatrixConcat() , imageAffineMatrixGet() //affineMatrix()

#--- Opertions
function crop($rect=-1,$threshold=0.5){
/*
to crop a rectangle from the image , just provide the rectangle as an array(w=W,h=H,x=W/2,y=H/2)
to auto crop the image based on the background color put $rect=mode ex: crop(transparent) will crop the transparent background
or put $rect=color_index and $threshold_percent to try to detct the background color to crop it
*/

if(is_array($rect)){  //$rect=[w=W,h=H,x=W/2,y=H/2]
$dim=$this->dimensions();
 if(!isset($rect[0]))$rect[0]=$dim[0];
 if(!isset($rect[1]))$rect[1]=$dim[1];
 if(!isset($rect[2]))$rect[2]=$dim[2];
 if(!isset($rect[3]))$rect[3]=$dim[3];
 return imagecrop($this->img,['x' => $rect[2], 'y' => $rect[3], 'width' => $rect[0], 'height' => $rect[1]]);
}else{
$auto=['default'=>IMG_CROP_DEFAULT,'trans'=>IMG_CROP_TRANSPARENT,'black'=> IMG_CROP_BLACK,'white'=>IMG_CROP_WHITE,'sides'=>IMG_CROP_SIDES,'threshold'=>IMG_CROP_THRESHOLD];
if(is_string($rect)&&!empty($auto[$rect]))return imagecropauto ($this->img,$auto[$rect]);
elseif(in_array($rect,array_values($auto)))return imagecropauto ($this->img,$rect);
else{
 if(!$rect)$rect=$this->color;
 return imagecropauto ($this->img,IMG_CROP_THRESHOLD,$threshold,$rect);
}
}
}

function rotate($angle=90,$color=null,$tr=false){if(!$color)$color=$this->color;return imagerotate($this->img,$angle,$color,$tr);}
function scale($w=null,$h=null,$mode=null){
    if($w==null&&$h==null)return;
    $dim=$this->dimensions();
    if(strstr($w,'%')){
        $r=str_replace('%','',$w);
        $w=$dim[0]*$r/100;
        $h=$dim[1]*$r/100;
    }elseif(!$w){
     $w=$dim[0]*$h/$dim[1];  // "Aspect ratio"
    }elseif(!$h){
     $h=$dim[1]*$w/$dim[0];
    }
    if($mode=='min'||$mode=='max'){  //applied if both w,h supplied & if $w=% ; not applied if w or h calculated with aspect ratio
      $r1=$w/$dim[0];
      $r2=$h/$dim[1];
      if($r1>$r2){if($mode=='min')$w=$r2*$dim[0];else $h=$r1*$dim[1];}
      else{if($mode=='max')$w=$r2*$dim[0];else $h=$r1*$dim[1];}
      $mode=null;
    }
    $this->img=imagescale($this->img,$w,$h,$mode);
    return $this->img;
    //to keep editing the original image , copy it's reference before scaling the image i.e: $img2=$img; $img->scale(500);
    }
 function resize($w=null,$h=null,$mode=null){return $this->scale($w,$h,$mode);}

function screenShot($window=null,$area=null){
    if($window===null)return imagegrabscreen(); //new image resource ;
    else return imagegrabwindow($window);  //windows only ;ex:  $browser = new COM("InternetExplorer.Application"); $window= $browser->HWND;
    //window id = COM->HWND for applications
    //http://php.net/manual/en/function.imagegrabwindow.php
}
function convolution($matrix,$divisor,$offset=false){
 //convolution matrix is an array of 3 elements , each element has 3 values , this matrix applies some effects on the image
if($matrix=='emboss')$matrix=[[2,0,0], [0,-1,0], [0,0,-1]]; //give a default values for the emboss effect
elseif($matrix=='blur'){}
elseif($matrix=='guassian blur')$matrix=[[1,2,1], [2,4,2], [1,2,1]];
elseif($matrix=='sharpen'){}
//nx: search for kernal (convolution matrix)

return imageconvolution ($this->img,$matrix,$divisor,$offset );

}
function copy($img,$dest=[0,0],$src=[0,0],$dim=[],$merge=null,$mode=null,$fast=false){if($merge==null)return imagecopy($this->img,$img,$dest[0],$dest[1],$src[0],$src[1],$dim[0],$dim[1]);if($mode=='gray')return imagecopymergegray($this->img,$img,$dest[0],$dest[1],$src[0],$src[1],$dim[0],$dim[1],$merge);elseif($mode=='resampled'||$mode=='re'||($mode=='resize'&&!$fast))return imagecopyresampled($this->img,$img,$dest[0],$dest[1],$src[0],$src[1],$dim[0],$dim[1],$merge);elseif($mode=='resize')return imagecopyresized($this->img,$img,$dest[0],$dest[1],$src[0],$src[1],$dim[0],$dim[1],$merge); else return imagecopymerge($this->img,$img,$dest[0],$dest[1],$src[0],$src[1],$dim[0],$dim[1],$merge); }
function flip($mode='h'){
    if($mode=='h'||$mode=='HORIZONTAL')$mode=IMG_FLIP_HORIZONTAL;
    elseif($mode=='v'||$mode=='vertical')$mode=IMG_FLIP_VERTICAL;
    elseif($mode=='b'||$mode=='BOTH')$mode=IMG_FLIP_BOTH;
    return imageflip($this->img,$mode);
    }


#-- effects
function filter($type,...$p){ //http://php.net/manual/en/function.imagefilter.php
  //filters: NEGATE,GRAYSCALE,BRIGHTNESS,CONTRAST,COLORIZE,EDGEDETECT,EMBOSS,SELECTIVE_BLUR,GAUSSIAN_BLUR,MEAN_REMOVAL,SMOOTH,PIXELATE
 if(is_string($type)){
 if('GRAY')$type=GRAYSCALE;else if('BLUR')$type=IMG_FILTER_SELECTIVE_BLUR;elseif($type=='sketchy')$type=IMG_FILTER_MEAN_REMOVAL;
 else $type=constant('IMG_FILTER_'.strtoupper(str_replace(' ','_',$type)));
 }
 return imagefilter($this->img,$type,...$p);
}
function layer($effect=IMG_EFFECT_OVERLAY){
//effects:  REPLACE,ALPHABLEND(=NORMAL),OVERLAY,MULTIPLY
if(is_string($effect)){
if($effect=='BLEND'||$effect='ALPHA')$effect=IMG_EFFECT_ALPHABLEND;
else $effect=constant('IMG_EFFECT_'.strtoupper($effect));
}
return imagelayereffect($this->img,$effect);
}


#--- settings
function brush($brush){return imagesetbrush($this->img, $brush);} //$brush is an img resource i.e: $other->img
function gamma($in,$out){return imageGammaCorrect($in,$out);}
function gama($in,$out){return imageGammaCorrect($in,$out);}  //for typo mistake
function blend($enable=true){return imagealphablending($this->img,$enable);}  //enable blending mode for transparent images
//if enabled: copying $img2 to $img will result to mixing the colors of the two images,
//if disabled will result to respect colors & alpha channels of $img2 (disable blending mode on the destination img will make it respect the colors of the source img) , good when copying a transparent image (png) to our image

 function saveAlpha($on=true,$b=true){
   if($b)imageAlphaBlending($this->img, false); //u must set imageAlphaBlending=false to use  imageSaveAlpha()
   imageSaveAlpha($this->img, true);
 }
function clip($x1=null,$y1=0,$x2=0,$y2=0){if($x1===null)return imageGetClip($this->img);else return imageSetClip($this->img,$x1,$y1,$x2,$y2);}  //php7
function thickness($thickness){return imagesetthickness($this->img,$thickness );}
function tile($tile){return imagesettile($this->img,$tile);}  //Set the tile image for filling
function interlace($i=0){return imageinterlace($this->img,$i);}
function resolution($x=null,$y=null){return imageresolution($this->img,$x,$y);}
function res($x=null,$y=null){return $this->resolution($x,$y);}

function interpolation($method=null){
    $m=['BELL'=>IMG_BELL,'BESSEL'=>IMG_BESSEL,'BICUBIC'=>IMG_BICUBIC,'BICUBIC_FIXED'=>IMG_BICUBIC_FIXED,'FIXED'=>IMG_BILINEAR_FIXED,'BLACKMAN'=>IMG_BLACKMAN,'BOX'=>IMG_BOX,'Spline'=>IMG_BSPLINE,'CATMULLROM'=>IMG_CATMULLROM,'GAUSSIAN'=>IMG_GAUSSIAN,'CUBIC'=>IMG_GENERALIZED_CUBIC,'HERMITE'=>IMG_HERMITE,'HAMMING'=>IMG_HAMMING,'HANNING'=>IMG_HANNING,'MITCHELL'=>IMG_MITCHELL,'POWER'=>IMG_POWER,'QUADRATIC'=>IMG_QUADRATIC,'SINC'=>IMG_SINC,'NEIGHBOUR'=>IMG_NEAREST_NEIGHBOUR,'TRIANGLE'=>IMG_TRIANGLE];
    if(!empty($m[$mode]))$mode=$m[$mode];
    return  imagesetinterpolation ($this->img,$method);
    }

#--- Text & font
function char($text,$pos=[0,0],$color=null,$font=2,$vertical=false){
    if(!$color)$color=$this->color;
    if($vertical)return imagecharup ($this->img,$font ,$pos[0] ,$pos[1], $text, $color );
    else return imagechar ($this->img,$font ,$pos[0] ,$pos[1], $text, $color );
}
function font($font){return [imagefontwidth($font),imagefontheight($font)];}
function load($font){return imageloadfont($this->img);} //loadFont()

function text($text,$point=[0,0],$font=5,$color=null,$vertical=false){
    if(!$color)$color=$this->color;
    if($vertical)return imageStringUp ($this->img ,$font,$point[0],$point[1],$text,$color);
    return imageString($this->img ,$font,$point[0],$point[1],$text,$color);
    //to control font size & angle & line spacing use write()
    }

    //nx: converting top-left to lower-left in write() and the opposite in textBox() need revision
function write($text,$fontFile,$point=[0,0],$size=18,$angle=0,$color=null,$linespacing=1){ //nx: convert (x,y) to be upper-left corner (needs to calculate textBox()
//write using FreeType; $font:path to the font file
//! This function is only available if PHP is compiled with freetype support (--with-freetype-dir=DIR ) ; check by function_exists('imagettftext'), or gd_info() or php_info()
//for ftb function (x,y) is the lower-left corner (not upper-left as the image and other functions)
    if(!$color)$color=$this->color;
    if($linespacing)$linespacing=['linespacing'=>$linespacing];
    $box=$this->textBox($text,$fontFile,$size,$angle,$linespacing); //to give (x,y) as upper-left corner & calculate the lower-left corner

   //convert relative coordinates to absolute coordinates , box corners are relative to TEXT
   //valid for any angle
    $x=$point[0]-$box[0][0]; $y=$point[1]-$box[0][1];
    $abs=[ [$point[0],$point[1]] , [$box[1][0]+$x,$box[1][1]+$y] , [$box[2][0]+$x,$box[2][1]+$y] , [$box[3][0]+$x,$box[3][1]+$y]  ];
    $point=[$x,$y];
    $x=imagefttext($this->img,$size,$angle,$point[0],$point[1],$color ,$fontFile,$text,$linespacing); //$fontFile:path to the font file
    if($x)return $abs;
    }

function textBox($text,$fontFile,$size=18,$angle=0,$linespacing=1){
 //returns the 4 corners of the boundary arround this text , the corners is relative to the text (not the image) ; starting from lower-left corner
if($linespacing)$linespacing=['linespacing'=>$linespacing];
$box=imageftbbox($size,$angle,$fontFile,$text,$linespacing);   //return $box;
return [[$box[6],$box[7]] ,[$box[4],$box[5]] ,[$box[2],$box[3]] ,[$box[0],$box[1]] ];  //combine each point coordinates , start from upper-left corner (same as image)
//return [[$box[0],$box[1]] ,[$box[2],$box[3]] ,[$box[4],$box[5]] ,[$box[6],$box[7]] , ];
}
//text() write a text and return the coordinates of the textBox [x1,y1,x2,y2,x3,y3,..] or [ [x1,y1],[x2,y2],..], textBox() returns the coordinates of the textBox without writing the text


#--- outPut functions
function outPut($type=null,$f=null,...$p){ //p0=quality
if(empty($type))$type=$this->type;
if($type=='jpeg'||$type=='jpg'){
    if(is_int($f)){$p[0]=$f;$f=null;}elseif(!$p[0])$p[0]=100;
    if(!$f)header('content-type:image/JPEG');
    return imageJpeg ($this->img,$f,$p[0]);
}elseif($type=='png'){ //p0=compression level  ;p1=filters
    if(is_int($f)){$p[1]=$p[0];$p[0]=$f;}elseif(!$p[0])$p[0]=0;
    if($p[1]===false)$p[1]=PNG_NO_FILTER;elseif($p[1]===true)$p[1]=PNG_ALL_FILTERS;
    elseif(is_string($p[1]))$p[1]=constant('PNG_FILTER_'.strtoupper(str_replace(' ','_',$p[1])));
    if(!$f)header('content-type:image/PNG');
    return imagePng($this->img,$f,$p[0],$p[1]);
}elseif($type=='gif'){
    if(!$f)header('content-type:image/gif');
    return imageGif($this->img,$f);
}elseif($type=='GD'){
    if(!$f)header('content-type:image/GD');
    return imageGD($this->img,$f);
}elseif($type=='GD2'){ //p0=$chunk_size(=128); p1=type(=IMG_GD2_RAW)
    if(!$f)header('content-type:image/GD2');
    if(is_int($f)){$p[1]=$p[0];$p[0]=$f;$f=null;}
    return imageGD2($this->img,$f,$p[0],$p[1]);
}elseif($type=='bmp'){ //p0=compressed(=true)
    if(!$f)header('content-type:image/bmp');
    if(is_bool($f)){$p[0]=$f;$f=null;}
    return imageBmp($this->img,$f,$p[0]);
}elseif($type=='wbmp'){
    if(!$f)header('content-type:image/wbmp');
    if(is_int($f)){$p[0]=$f;$f=null;}
    return imageWbmp($this->img,$f,$p[0]);
}elseif($type=='2wbmp'){  //imagecreatefrom2wbmp() not registered
    if(!$f)header('content-type:image/2wbmp');
    if(is_int($f)){$p[0]=$f;$f=null;}
    return image2wbmp($this->img,$f,$p[0]);
}elseif($type=='webp'){ //p0=Quality
    if(!$f)header('content-type:image/webp');
    if(is_int($f)){$p[0]=$f;$f=null;}
    if(!$p[0])$p[0]=100;
    return imageWebp($this->img,$f,$p[0]);
}elseif($type=='xbm'){  //p0=foreground
    if(!$f)header('content-type:image/xbm');
    if(is_int($f)){$p[0]=$f;$f=null;}
    return imageXbm($this->img,$f,$p[0]);
}
else return $this->outPut(null,$f,...$p); //ex: outPut('photo.jpg',100)
//imagedestroy($this->img); //maybe the image still needed to make some operations after save
}
function show($type,...$p){return $this->outPut($type,null,...$p);} //outPut to browser
//function save($type,$f,...$p){return $this->outPut($type,$f,...$p);} //alternative to outPut()
function destroy(){return imagedestroy($this->img);}



//nx: ftb , Psb functions: such as imageFtbText(),imagePsbBox(),...
}
?>
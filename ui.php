<?php     //nx: in post() we temporary changed src to data-src in <img> elements to load them by Lazy (jQuery plugin); need to provide $post->data-img without $post->img and load the image from data-img attribute
class ui{  //dependences : bootstrap & jquery & fontawsome
public $br;
function __construct($readable=false){
 if($readable)$this->br=$this->br;else $readable->br='';
//Cannot echo the condtruct i.e: echo $h=new html()
}
function start($title='',$head=[],$info=[],$manifest=null){
$x='<!DOCTYPE html><html';
if(!empty($info['lang'])){$x.=' lang="'.$info['lang'].'"';if(empty($info['dir']))if(in_array($info['lang'],['ar']))$x.=' dir="rtl"';else $x.=' dir="ltr"';}
if(!empty($info['dir']))$x.=' dir="'.$info['dir'].'"';
$x.=$this->attr($info['body']).($manifest?' manifest="'.$manifest.'"':'').'>'.$this->br.$this->head($title,$head).'<body '.$this->attr($info['body']).'>'.$this->br;
return $x;
 }
function head($title='',$h=[]){
$x='<head>'.$this->br.'<meta charset="UTF-8">'.$this->br;
if(!isset($h['viewport']))$h['viewport']='width=device-width, initial-scale=1,maximum-scale=1.0';
if(empty($h['type']))$h['type']='website';
foreach($h as $k=>$v){
if(is_int($k))$x.=$v.$this->br;//extra html code  ; is_int($k) MUST be the first option in the loop
elseif($k=='title'){if(empty($title))$title=$v; }
elseif($k=='name'){if(empty($title))$title=$v;$x.='<meta property="og:site_name" content="'.$v.'"/>'.$this->br;}//site name
elseif($k=='icon')$x.='<link rel="shortcut icon" type="image/x-icon" href="'.$v.'">'.$this->br;
elseif($k=='description'||$k=='desc')$x.='<meta name="description" content="'.$v.'" /><meta property="og:description" content="'.$v.'"/><meta name="twitter:description" content="'.$v.'" />'.$this->br;
elseif($k=='keywords')$x.='<meta name="keywords" content="'.$v.'" />'.$this->br;
elseif($k=='url')$x.='<link rel="canonical" href="'.$v.'" /><meta property="og:url" content="'.$v.'"/>'.$this->br;
elseif($k=='type'){$x.='<meta property="og:type" content="'.$v.'"/>'.$this->br;}
elseif($k=='img'){
if(is_array($v)){$w=$v[1];$h=$v[2];$v=$v[0];}else{$w=$h='';}
$x.='<link rel="image_src"  href="'.$v.'" /><meta property="og:image" content="'.$v.'"/><meta property="og:image:width" content="'.$w.'" /><meta property="og:image:height" content="'.$h.'" /><meta itemprop="image" content="'.$v.'"><meta name="twitter:image" content="'.$v.'" />'.$this->br;
unset($w,$h);
}
elseif($k=='fb_app')$x.='<meta property="fb:app_id" content="'.$v.'"/>'.$this->br;
elseif($k=='hashtag')$x.='<meta name="twitter:site" content="@'.$v.'" />'.$this->br;
elseif($k=='style')$x.='<style><!-- '.$v.'--></style>'.$this->br;
elseif($k=='css'){foreach($v as $css)$x.='<link rel="stylesheet" type="text/css" href="'.$css.'" async>'.$this->br;}
elseif($k=='js'){foreach($v as $js)$x.='<script src="'.$js.'" async></script>'.$this->br;}
elseif($k=='jsb'){foreach($v as $js)$x.='<script src="'.$js.'"></script>'.$this->br;}
elseif($k=='viewport'){$x.='<meta name="viewport" content="'.$v.'">'.$this->br;}
elseif($k=='adsense')$x.=$this->code('adsense','script').($v?$this->code('adsense','page',$v):'');
elseif($k=='analytics')$x.='<script>'.$this->code('analytics',...$v).'</script>';
//elseif($k=='fontawsome'){if($v=='')$v='4.4.0';$x.='<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/'.$v.'/css/font-awesome.min.css">'.$this->br;}
//elseif($k=='fontawsome'){$x.='<link rel="stylesheet" href="'.$v.'/inc/font-awesome-4.7.0/css/font-awesome.min.css">'.$this->br;}
//elseif($k=='google_analytics'){if(is_array($v))$x.=$this->google_analytics($v[0],$v[1],$v[2]).$this->br;elseif(!empty($v))$x.=$this->google_analytics($v).$this->br;}
//else $x.='<meta name="'.$k.'" content="'.$v.'" />'.$this->br;
}
if(!empty($title))$x.='<title>'.$title.'</title><meta name="title" content="'.$title.'" /><meta property="og:title" content="'.$title.'"/><meta itemprop="name" content="'.$title.'"><meta name="twitter:title" content="'.$title.'" />'.$this->br;
$x.='<meta name="twitter:card" content="summary_large_image" />'.$this->br;
$x.='</head>'.$this->br;
return $x;
}
function end(){
return '</body></html>';
}


function load($path='',$files='all'){   //nx: auto get $path
    //load required files from CDN
    if(is_array($files)){
        $x='';
        foreach($files as $f){
            if($f=='jquery')$x.='<script src="'.$path.'inc/jQuery 3.1.1.min.js" async></script>';
            elseif($f=='bootstrap')$x.='<link rel="stylesheet" href="'.$path.'inc/bootstrap-4.0.0_min.min.css" async> <script src="'.$path.'inc/bootstrap.min.js" async></script>';
            elseif($f=='font-awesome'||$f=='fa')$x.='<link rel="stylesheet" href="'.$path.'inc/font-awesome-4.7.0/font-awesome.min.css">';
            elseif($f=='eldeeb')$x.='<link rel="stylesheet" href="'.$path.'eldeeb.min.css" async> <script src="'.$path.'eldeeb.min.js" ></script>';
            elseif($f=='eldeeb.css')$x.='<link rel="stylesheet" href="'.$path.'eldeeb.min.css" async>';
            elseif($f=='eldeeb.js')$x.='<script src="'.$path.'eldeeb.min.js" ></script>';
        }
        return $x;
    }

   return
       '<script src="'.$path.'inc/jQuery 3.1.1.min.js" async></script>
        <link rel="stylesheet" href="'.$path.'inc/bootstrap-4.0.0_min.min.css" async>
        <script src="'.$path.'inc/bootstrap.min.js" async></script>
        <link rel="stylesheet" href="'.$path.'inc/font-awesome-4.7.0/font-awesome.min.css">
        <link rel="stylesheet" href="'.$path.'eldeeb.min.css" async>
        <script src="'.$path.'eldeeb.min.js" ></script>';



/*ver3 : $x.='<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script><link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">';
 Ver4:
 $x.='<script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
 <link rel="stylesheet" href="inc/bootstrap_override.css">';
*/
 return $x;
}

function attr($a=[]){
if(is_array($a)){
 $x='';
 foreach($a as $k=>$v){if(is_int($k))$x.=$v;else $x.=$k.'="'.$v.'"';}
 return $x;
}else return $a;
}
function link($content='',$href='',$blank=true,$class='',$extra=''){  //ex: $ui->link('Text','http://link',1,'ok sm custom','id="MyId"');
   $class=explode(' ',$class); $cc='';
   foreach($class as $c){
   if($c=='ok')$c='success';elseif($c=='error')$c='danger';
   if(in_array($c,array('danger','success','warning','primary','info')))$cc.=' btn btn-'.$c;
   elseif(in_array($c,array('sm','md','lg')))$cc.=' btn-'.$c;
   else $cc.=' '.$c;
   }
   $class=trim($cc);
if($href===1)$href=$content;               //if(empty($href))$href=$content; //nx: only if $content: is link
return '<a href="'.$href.'"'.($blank?' target="_blank"':'').($class?' class="'.$class.'" ':'').' '.$extra.'>'.$content.'</a>';   //nx: class=addClass(extra,class)
}
function a($content='',$href='',$blank=true,$class='',$extra=''){return $this->link($content,$href,$blank,$class,$extra);}
function el($tag=null,$content='',$class=null,$extra=null){
$x='<'.$tag.($class?' class="'.$class.'"':'');
if(is_array($extra)){foreach($extra as $k=>$v)$x.=' '.$k.'='.'"'.$v.'"';}else $x.=' '.$extra;
$x.='>'.$content.'</'.$tag.'>'.$this->br;
return $x;
}
function img($src='',$dim=[],$alt='',$extra=''){return '<img src="'.$src.'" alt="'.$alt.'" '.(!empty($dim[0])?'width="'.$dim[0].'" ':'').(!empty($dim[1])?'height="'.$dim[1].'" ':'').$extra.'/>';}
function div($content='',$class=null,$id=null,$extra=null){
    if(!empty($id)){if(!is_array($extra))$extra.=' id="'.$id.'"';else $extra['id']=$id;}
    return  $this->el('div',$content,$class,$extra);
    }
function span($content='',$class=null,$id=null,$extra=null){
    if(!empty($id)){if(!is_array($extra))$extra.=' id="'.$id.'"';else $extra['id']=$id;}
    return $this->el('span',$content,$class,$extra);
    }
function icon($name,$animate=false,$size=null,$rotate=null,$extra=""){ //load font awsome first ; $size=lg/2/3/4/5
    $x='';
    if($animate===1)$x.=' fa-pulse';elseif($animate)$x.=' fa-spin';
    if($size){$x.=' fa-'.$size;if(is_int($size))$x.='x';}
    if($rotate=='h')$x.=' fa-flip-horizontal';elseif($rotate=='v')$x.=' fa-flip-vertical';
    elseif(!empty($rotate))$x.=' fa-rotate-'.$rotate;
    return '<span '.$this->addClass('fa fa-'.$name.$x,$extra,false).' aria-hidden="true"></span>';
    }

    function icons($icons,$size=null){
        $x='<span class="fa-stack';
        if(!empty($size)){$x.=' fa-'.$size; if(is_int($size))$x.='x';}
        $x.='">';
        foreach($icons as $ic)$x.=$this->icon($ic[0],$ic[1],$ic[2],$ic[3]);  //nx: replace fa-$size with fa-stack-$size
        return $x.'</span>';
    }
//function jsAuto($content=''){return '(function(){'.$content.'})()'.$this->br;}

function post($p=[],$type=''){//if $type , add schema & ARIA attributes , ex: for $type=article  <div role="article" itemtype="http://schema.org/Article" ..>
$data='';
if($p['data']&&is_array($p['data'])){
foreach($p['data'] as $k=>$v){$data.=' data-'.$k.'="'.$v.'"';}
}
if($type=='article')$tag='article';else $tag='div';  //nx: $p[data][type]
$x='<'.$tag.' class="post" id="post_'.$p['id'].'"  dir="auto" data-post="'.$p['id'].'" '.$data.'>';
$date='';
if(!empty($p['time'])){$date='<div class="post_time" data-time="'.$p['time'].'"><a href="'.$p['link'].'" target="_blank">'.date('j/n/Y',$p['time']).'</a></div>'.$this->br;}
if(is_array($p['user'])&&!empty($p['user'][1])){
if(!empty($p['user'][0]))$link='<a href="'._HROOT.'profile/?id='.$p['user'][0].'" target="_blank">';else $link='';
$x.='<div class="post_user" dir="auto">'.$this->br;
if(!empty($p['user'][2])){
    $img='<img data-src="'.$p['user'][2].'" alt="'.$p['user'][1].'"/>'.$this->br;
    $x.='<div class="user_photo">'.(empty($link)?$img:$link.$img.'</a>').'</div>'.$this->br;
}
$x.='<div class="user_info"><div class="user_name">'.(empty($link)?$p['user'][1]:$link.$p['user'][1].'</a>').'</div>'.$date.'</div></div>'.$this->br;
}

$x.='<div class="post_body">'.$this->br;
if(!empty($p['title'])){$x.='<h1 class="post_title" dir="auto">'.(empty($p['link'])?$p['title']:'<a href="'.$p['link'].'" target="_blank">'.$p['title'].'</a>').'</h1>'.$this->br;}
if(!empty($p['subtitle'])){$x.='<h2 class="post_subtitle" dir="auto">'.(empty($p['sublink'])?$p['subtitle']:'<a href="'.$p['link'].'" target="_blank">'.$p['subtitle'].'</a>').'</h2>'.$this->br;}
if(!empty($p['time'])&&empty($p['user'][1]))$x.='<small class="post_time2 text-muted">'.date('j/n/Y',$p['time']).'</small>'.$this->br;
if(array_key_exists('img',$p)){ //even if has no value (isset() returns false if $[img] has no value)
$img='';  //[src,w,h,srcSet,sizes]
if(is_array($p['img'])&&(!empty($p['img'][0])||!empty($p['img'][3]))){  //dynamically switch between src,srcSet & data-src,data-srcSet "data-srcSet is used for jQuery.LazyLoad"
       if(!empty($p['img'][0]))$img.=' data-src="'.$p['img'][0].'" ';
       if(!empty($p['img'][3])){$img.=' data-srcSet="'.$p['img'][3].'" ';if(!empty($p['img'][4]))$img.='sizes="'.$p['img'][4].'" ';}
       if(!empty($p['img'][1]))$img.=' width="'.$p['img'][1].'" height="'.$p['img'][2].'" '; 
     }elseif(!empty($p['img']))$img='data-src="'.$p['img'].'" ';
if(!empty($img))$x.='<div class="post_image"><img '.$img.$this->addClass('post_image_img',$p['img_extra'],false).' alt="'. strip_tags($p['title']).'" /></div>'.$this->br;  //(!empty($p['img_onclick'])?'onclick="'.$p['img_onclick'].'"':'') $img.addEventListener()
}

if($p['more']===true)$p['more']='see more...'.$this->br;
$x.='<div class="post_details" dir="auto"'.($p['direct']?' data-direct':'').'>'.$p['details'].(!empty($p['more'])?'<br /><a href="'.$p['link'].'" target="_blank" class="post_more">'.$p['more'].'</a>':'').'</div>'.$this->br;
if(!empty($p['actions']))$x.='<div class="post_actions">'.$p['actions'].'</div>'.$this->br;
if(!empty($p['footer']))$x.='<div class="footer text-muted text-center">'.$p['footer'].'</div>'.$this->br;
$x.='</div><div class="post_extra" id="post_extra_'.$p['id'].'">'.(!empty($p['extra'])?$p['extra']:'').'</div>'.$this->br; // .post_body
return $x.='<div class="post_share"'.(!empty($p['id'])?' data-id="'.$p['id'].'"':'').(!empty($p['type'])?' data-type="'.$p['type'].'"':'').'></div></'.$tag.'>'.$this->br; //.post
}
function schema($type,$data='',$extra1=null,$extra2=null){
if($type=='article')return 'itemscope itemtype="http://schema.org/Article"';
elseif($type=='web')return '<meta itemscope itemprop="mainEntityOfPage"  itemType="https://schema.org/WebPage" itemid="http://www.almogtama3.com/"/>';
elseif($type=='rating')return '<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"><meta itemprop="ratingValue" content="5"><meta itemprop="ratingCount" content="5"></span>';
elseif($type=='author')return '<div style="font-weight:bold" itemprop="author"><span itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name">'.$data.'</span></span></div>';
elseif($type=='img')return '<meta itemprop="url" content="'.$data.'"><meta itemprop="width" content="'.$extra1.'"><meta itemprop="height" content="'.$extra2.'">';
elseif($type=='date')return '<meta itemprop="datePublished" content="'.$data.'"/> <meta itemprop="dateModified" content="'.$data.'"/>';
elseif($type=='org')return '<div itemprop="publisher" itemscope itemtype="http://schema.org/Organization"><span itemprop="name">'.$data.'</span><span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject"><meta itemprop="url" content="'.$extra1.'"><meta itemprop="width" content="800"><meta itemprop="height" content="800"></span></div>';
}

//function __destruct(){echo $this->end();}

function __toString(){return '==TOSTRING===';}  //nx:

function cover($bkg,$txt,$style=[],$overlay=[]){   //$style=[$w,$h,] txt position or padding (h,v)
    /*
    $txt='<h1>hello</h1><h4>by '.$h->link('Folan Elfolany','#').'</h4>';
    echo $h->cover(['img.jpg','50% 15%'],$txt,['#FFF','0.2']);
    echo $h->cover('#8892BF',$txt,['black','0.6']);
    */
if(!is_array($overlay))$overlay=[$overlay];
if(!empty($bkg)){
    if($bkg[0]=='#')$st='background-color:'.$bkg.';';
    else {if(!is_array($bkg))$bkg=[$bkg];if(!empty($bkg[1]))$pos=$bkg[1];else $pos='50% 50%';$st='background-image:url('.trim($bkg[0]).');background-position:'.$pos.';';}
}else $st='';
if(!is_array($style))$style=[$style[0],null];
if(!empty($style[0]))$st.=';height:'.$style[0];
if(!empty($style[1]))$st.=';width:'.$style[1];

//$st=';padding:120px 25%;';
$x='<div class="cover" style="'.$st.'">'.$this->br;
if(!empty($overlay[0]))$x.='<div class="overlay" style="background-color:'.$overlay[0].(!empty($overlay[1])?';opacity:'.$overlay[1]:'').'"></div>';

$x.='<div class="cover-text">'.$txt.'</div>'.$this->br;
/*
$x.='<div class="cover_text" style="z-index:2;color:#FFF;">';
if(!is_array($title))$title=[$title];
$x.='<h1 class="cover_title">'.$title[0].'</h1>';
if(!empty($title[1]))$x.='<h4 class="cover_subtitle">'.$title[1].'</h4>';
if(!empty($title[2]))$x.='<small class="cover_small">'.$title[2].'</small>';
$x='</div>';
*/
return $x.'</div>'.$this->br;
}

function clear(){return '<div style="clear:both"></div>';}

function re($u='/',$t=0){die('<meta http-equiv="REFRESH" content="'.$t.';url='.$u.'">');}

################ Bootstrap #######################
################################## Basics ##############################################
function is_html($el){return preg_match('#<.+[/>|</.+>]#',trim($el));}
function addClass($class='',$el='',$html=true){return $this->addAttribute('class',$class,$el,$html,false);}
function addAttribute($k='',$v='',$el='',$html=true,$replace=false){
if(empty($k)||empty($v))return $el;//to allow removing the value of this attribute remove empty($v) from the condition
/*
if $k not exists : directly add the new attribute
if ($replace || id) -meane new value must REPLACE old- : 1-remove the old attribute 2- directly add the new one (OR) replace it with regex
else -new value added to the old one if not exists- : 1- explode new values as $v, if($v not exists in the old values)add $v
*/

if($html){
 if(empty($el))return '';
 if(strpos($el,$k)===false)return preg_replace('#(/?>)#U',' '.$k.'="'.$v.'"'.' $1',$el,1);

}else{
 if(empty($el))return $k.'="'.$v.'"';
 if(strpos($el,$k)===false)return $el.' '.$k.'="'.$v.'"';


}






if(empty($el)){if(!$html)return $k.'="'.$v.'"';else return ''; }


if(strpos($el,$k)===false){if($html)return preg_replace('#(/?>)#U',' '.$k.'="'.$v.'"'.' $1',$el,1);else return $el.' '.$k.'="'.$v.'"';}
if(preg_match('/'.$k.'\s*=\s*["|\']\s*["|\']/U',$el,$m)){return str_replace($m[0],$k.'="'.$v.'"',$el);}
if($replace||in_array($k,['id'])){return preg_replace('/'.$k.'\s*=\s*["|\'](.+)["|\']/U',$k.'="'.$v.'"',$el);}

preg_match('/'.$k.'\s*=\s*["|\'](.+)["|\']/U',$el,$m);
$old=trim($m[1]);
$new=explode(' ',$v);
foreach($new as $n){if(!empty($n)&&!strpos($old,$n))$old.=' '.$n;}
return str_replace($m[0],$k.'="'.$old.'"',$el);
}


function is_tag($tag,$el){return preg_match('#<'.trim($tag).' .+[/>|</.+>]#',trim($el));}
################################## /Basics ##############################################

function slideshow($data,$id='SlideShow',$int=3,$auto=true){
$x='<div id="'.$id.'" class="carousel slide" data-interval="'.($int*1000).'" '.($auto?'data-ride="carousel"':'').' data-keyboard=true><div class="carousel-inner" role="listbox">'.$this->br;

//Content
foreach($data as $k=>$v){
$x.='<div class="'.($k==0?'active ':'').'carousel-item"><div class="overlay"></div>'.$v[0].$this->br;
if(!empty($v[1])||!empty($v[2]))$x.='<div class="carousel-caption">'.(!empty($v[1])?'<h3>'.$v['1'].'</h3>':'').(!empty($v[2])?'<p>'.$v['2'].'</p>':'').'</div>'.$this->br;
$x.='</div>'.$this->br;
}
$x.='</div>'.$this->br; //.carousel-inner
//Controlers
$x.='  <a class="carousel-control-next" href="#'.$id.'" role="button" data-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="sr-only">Next</span></a>'.$this->br;
$x.='  <a class="carousel-control-prev" href="#'.$id.'" role="button" data-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="sr-only">Previous</span></a>'.$this->br;

/* bootstrap 3
$x.='<a class="carousel-control left" href="#'.$id.'" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>'.$this->br;
$x.='<a class="carousel-control right" href="#'.$id.'" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>'.$this->br;
*/
//nx: $dir = direction of slide ex: $dir=left : left arrow go to next content

//indicators:
$x.='<ol class="carousel-indicators">'.$this->br.'<li data-target="#'.$id.'" data-slide-to="0" class="active"></li>'.$this->br;
$c=count($data);
for($i=1;$i<$c;$i++){$x.='<li data-target="#'.$id.'" data-slide-to="'.$i.'"></li>'.$this->br;}
$x.='</ol>'.$this->br;

$x.='</div>'.$this->br;// #$id
return $x;
}
################################## Forms ##############################################
function form($inputs=[],$op=[],$up=true){
    /*
      nx: if(value=array)multi inputs inside the same group i.e one label + multi-inputs
      $p = [method , action , id , class , id , style ,....] form options
      $type= (!deprecated) [type=v,right,left] ,h: horizontal form , i:inline_form , v:vertical_form  ex: ['h','col-md-2','col-md-10']
      .form-label,.input-part,.form-group-part is not a bootstrap class
    */
if($op['label']!==false&&$op['type']!='inline')$op['label']=true;
if($op['placeholder']!==false)$op['placeholder']=true;
if(empty($op['method']))$op['method']='post';
$extra=$op['extra'];
if(!empty($op['class'])){$extra=$this->addClass($op['class'],$extra,false);}
if(!empty($op['type'])){$extra=$this->addClass('form-'.$op['type'],$extra,false);}
unset($op['extra'],$op['class'],$op['type']);
$x='<form role="form" '.$extra.' '.$this->atr($op).($up?' enctype="multipart/form-data"':'').' >'.$this->br;
$x.=$this->inputs($inputs,$op);
if(count($inputs)>0)$x.='</form>'.$this->br;
return $x;
}

function inputs($inputs=[],$op=[]){  //use this function if some form inputs are separated ; ex: $h->form(); $h->inputs(); $h->inputs(); $h->form_end()
$x='';
foreach($inputs as $p){    //nx: $p[input_extra] : class="x" add "x" to the existing class via regex (also for $p[label_extra] , $p[options_extra])
if(is_array($p[0]))$x.=$this->inputs($p,$op);
else{
if($p['hide_label']=='yes'||($op['type']=='inline'&&$op['hide_labels']!='no'&&$p['hide_label']!='no'))$p['hide_label']=true;else $p['hide_label']=false;
if(!isset($p['placeholder'])&&isset($op['placeholder']))$p['placeholder']=$op['placeholder'];
if(!isset($p['label'])&&isset($op['label']))$p['label']=$op['label'];
if(!isset($p['value'])){if(isset($p['name'])&&isset($op['value']['name']))$p['value']=$op['value']['name'];elseif(isset($op['value'][$p[0]]))$p['value']=$op['value'][$p[0]];}
$x.=$this->input($p);
}
}
return $x;
}
function input($p,$part=null,$name=null,$value=null){
$x='';
if(!empty($p[0])&&empty($p['name']))$p['name']=$p[0]; //[$name,$type,$k=>$v]
if(!empty($p[1])&&empty($p['type']))$p['type']=$p[1];
if($part!==null){
if(empty($p['value']))$p['value']=$value;
if(!empty($p['name']))$vlu=$this->form_value($p['name'],$p['value']);
else {$vlu=$this->form_value($name,$p['value']);$p['name']=$name.'[]';if(is_array($vlu))$vlu=$vlu[$part];}
}else $vlu=$this->form_value($p['name'],$p['value']);
if(empty($p['type'])){if($p['name']=='password')$p['type']='password';elseif($p['name']=='submit')$p['type']='submit';elseif($p['name']=='csrf')$p['type']='csrf';else $p['type']='text';}
if($p['type']=='submit')$x.='<button type="submit" '.$this->addClass('btn btn-primary form-submit '.$p['class'],$p['input_extra'],false).' name='.(!empty($p['name'])?'"'.$p['name'].'"':'"submit"').' value="'.(!empty($p['value'])?$p['value']:'true').'">'.(!empty($p['text'])?$p['text']:'Submit').'</button>'.$this->br;
elseif($p['type']=='reset')$x.='<button type="reset" '.$this->addClass('btn btn-primary form-reset '.$p['class'],$p['input_extra'],false).' name='.(!empty($p['name'])?'"'.$p['name'].'"':'"reset"').'>'.(!empty($p['text'])?$p['text']:'Reset').'</button>'.$this->br;
elseif($p['type']=='hidden'){$x.='<input type="hidden" name="'.$p['name'].'" value="'.$p['value'].'">'.$this->br; }
elseif($p['type']=='csrf'){$x.='<input type="hidden" name="'.(!empty($p['name'])?$p['name']:'csrf').'" value="===Random value ===">'.$this->br; }  //nx:
elseif($p[0]=='static')$x.=$p[1].$this->br; //elseif($p['type']=='static')$x.=$p['value'].$this->br;
else{
if($part===null)$x.='<div class="form-group"'.(!empty($p['id'])?' id="'.$p['id'].'"':'').'>'.$this->br;
if($p['label']===true)$p['label']=$p['name'];
if($p['placeholder']===true){if(!empty($p['label']))$p['placeholder']=$p['label'];else $p['placeholder']=$p['name'];}
if($p['type']=='button')$x.='<button type="button" '.$this->addClass('btn',$p['input_extra'],false).(!empty($p['click'])?' onclick="'.$p['click'].'"':'').'>'.(!empty($p['value'])?$p['value']:'Button').'</button>'.$this->br;
elseif($p['type']=='checkbox'){$x.='<label for='.$p['name'].' class="form-label'.($p['hide_label']?' sr-only':'').'" '.$p['label_extra'].($p['required']?' required="required"':'').'>'.$p['label'].'</label>'.$this->br;foreach($p['options'] as $k=>$v){$x.='<div class="form-check"><label class="form-check-label"><input type="checkbox" name="'.$p['name'].'[]" value="'.$k.'" '.((is_array($vlu) && in_array($k,$vlu))?'checked':'').' '.$p['input_extra'].' /> <span>'.$v.'</span></label></div>'.$this->br;}}
elseif($p['type']=='radio'){$x.='<label for='.$p['name'].' class="form-label'.($p['hide_label']?' sr-only':'').'" '.$p['label_extra'].'>'.$p['label'].'</label>';foreach($p['options'] as $k=>$v){$x.='<div class="form-check"><label class="form-check-label"><input type="radio" name="'.$p['name'].'" value="'.$k.'" '.(($k==$vlu)?'checked="checked"':'').' '.$p['input_extra'].($p['required']?' required="required"':'').' /> <span>'.$v.'</span></label></div>'.$this->br;}}
else{
if($p['type']=='password'){if(empty($p['name']))$p['name']='password';if(empty($p['label']))$p['label']='Password';}
if(!empty($p['label'])){$x.='<label for='.$p['name'].' class="form-label'.($p['hide_label']?' sr-only':'').'" '.$p['label_extra'].'>'.$p['label'].'</label>'.$this->br;}
if($part===null)$x.='<div class="input-group'.($p['type']=='parts'?' input-group-part':'').'">'.$this->br;
if(!empty($p['before'])){if(!is_array($p['before']))$p['before']=[$p['before']];foreach($p['before'] as $v){$x.='<span class="input-group-addon">'.$v.'</span>'.$this->br; }}//html code , for icons use icon($name)
if($p['type']=='textarea'){$x.='<textarea '.$this->addClass('form-control'.($part!==null?' input-part':''),$p['input_extra'],false).' '.(!empty($p['placeholder'])?' placeholder="'.$p['placeholder'].'"':'').(($p['rows'])?' rows="'.$p['rows'].'"':'').(($p['cols'])?' cols="'.$p['cols'].'"':'').' name="'.$p['name'].'"  '.($p['required']?' required="required"':'').'>'.$vlu.'</textarea>'.$this->br;}
elseif($p['type']=='file'){
if(!isset($p['multiple']))$p['multiple']=true;

if($p['accept']=='image')$p['accept']='image/*';   //if(in_array($p['accept'],['image','images','photos','pictures']))$p['accept']='image/*';
elseif($p['accept']=='image')$p['accept']='image/*';
elseif($p['accept']=='audio')$p['accept']='audio/*';
elseif($p['accept']=='video')$p['accept']='video/*';
//nx accept=>'extension,audio,video,image,media_type' or array

$x.='<input type="file" '.$this->addClass('form-control-file'.($part!==null?' input-part':''),$p['input_extra'],false).' '.($p['multiple']?' name="'.$p['name'].'[]" multiple="multiple" ':' name="'.$p['name'].'" ').(!empty($p['accept'])?' accept="'.$p['accept'].'"':'').($p['required']?' required="required"':'').'>'.$this->br;
//nx: files(extensions& types)
}
elseif($p['type']=='select'){$x.='<select '.$this->addClass('form-control'.($part!==null?' input-part':''),$p['input_extra'],false).' name="'.$p['name'].'" '.($p['required']?' required="required"':'').'>';foreach($p['options'] as $k=>$v){$x.='<option value="'.$k.'" '.(($k==$vlu)?'selected="selected" ':'').$p['options_extra'].'>'.$v.'</option>'.$this->br;}$x.='</select>'.$this->br;}
elseif($p['type']=='date'){  //date is not Parts , and always $_POST[name]=array()
 //not supported in all browsers , so we replace it with normal select inputs
//$x.='<select class="form-control input-part" name="'.(is_array($p['name'])?$p['name'][0]:$p['name'].'[]').'" '.$p['input_extra'].'>'.$this->br;
$x.='<select '.$this->addClass('form-control input-part',$p['input_extra'],false).' name="'.$p['name'].'[]'.'" '.($p['required']?' required="required"':'').'>'.$this->br;
$r=range(1,31);
foreach($r as $v){$x.='<option value="'.$v.'" '.$p['options_extra'].(($v==$vlu[0])?'selected="selected"':'').'>'.$v.'</option>'.$this->br;}
$x.='</select>';

$x.='<select '.$this->addClass('form-control input-part',$p['input_extra'],false).' name="'.$p['name'].'[]'.'" '.($p['required']?' required="required"':'').'>'.$this->br;
$r=range(1,12);
foreach($r as $v){$x.='<option value="'.$v.'" '.$p['options_extra'].(($v==$vlu[1])?'selected="selected"':'').'>'.$v.'</option>'.$this->br;}
$x.='</select>'.$this->br;
$x.='<select '.$this->addClass('form-control input-part',$p['input_extra'],false).' name="'.$p['name'].'[]'.'" '.($p['required']?' required="required"':'').'>'.$this->br;
$y=date('Y');
$range=explode(',',$p['range']); //to ensure $range is string ; ex: array(-1,+1) will consider +1 as int(1) so the value will be 1 not $y+1
if(empty($range[0]))$range[0]=$y; $f=substr($range[0],0,1);if($f=='-'||$f=='+')$range[0]=$y+$range[0];
if(empty($range[1]))$range[1]=$y; $f=substr($range[1],0,1);if($f=='-'||$f=='+')$range[1]=$y+$range[1];
foreach(range($range[0],$range[1]) as $v){$x.='<option value="'.$v.'" '.$p['options_extra'].(($v==$vlu[2])?'selected="selected"':'').'>'.$v.'</option>'.$this->br;}
$x.='</select>';
}elseif($p['type']=='parts'){foreach($p['parts'] as $k=>$prt){if($p['required'])$prt['required']=true;$x.=$this->input($prt,$k,$p['name'],$p['value'][$k]);}}
else{
 $x.='<input type="'.$p['type'].'" name="'.$p['name'].'" '.(!empty($p['placeholder'])?' placeholder="'.$p['placeholder'].'"':'').' value="'.$vlu.'"  '.$p['input_extra'].' class="form-control'.($part!==null?' input-part':'').'"'.($p['required']?' required="required"':'').'>'.$this->br;
}

if(!empty($p['after'])){if(!is_array($p['after']))$p['after']=[$p['after']];foreach($p['after'] as $v){$x.='<span class="input-group-addon">'.$v.'</span>'.$this->br;}}
if($part===null)$x.='</div>'.$this->br;//.input-group
}

if(!empty($p['note']))$x.='<small class="form-text text-muted">'.$p['note'].'</small>'.$this->br;
if($part===null)$x.='<div class="form-control-feedback" id="'.($p['type']=='parts'&&empty($p['name'])?$p['parts'][0]['name']:$p['name']).'_feedback"></div></div>'.$this->br; //.form-group ; feedback to be filled with js (add .form-control-$type to .form-control and #($name_feedback).innerHTML='TEXT';) $type= success|danger|warning (ok,error,warning)
}

return $x;
}

function atr($p=[]){$a='';foreach($p as $k=>$v){$a.=$k.'="'.$v.'" ';} return $a;}

/*private function form_value($name,$default=null,$i=false){
if(isset($_REQUEST[$name])){if($i===false)return $_REQUEST[$name];else return $_REQUEST[$name][$i];}
return $default;
} */

private function form_value($name,$default=null){if(isset($_REQUEST[$name]))return $_REQUEST[$name];else return $default;}
################################## /Forms ##############################################
function btn($value='Button',$onclick='',$type='',$info=[]){     //nx: check if $onclick contains (" or ')
//$info = [active,block,outline,disabled,toggle]
if($type=='ok')$type='success';elseif($type=='error')$type='danger';
$info['extra']=$this->addClass('btn'.(!empty($type)?' btn-'.(in_array('outline',$info)?'outline-':'').$type:'').(in_array('block',$info)?' btn-block':'').(!empty($info['size'])?' btn-'.$info['size']:'').(in_array('active',$info)?' active':''),$info['extra'],false);
return '<button type="button" '.$info['extra'].(in_array('disabled',$info)?' disabled':'').(!empty($info['toggle'])?'  data-toggle="'.$info['toggle'].'"':'').(!empty($info['target'])?'  data-target="#'.$info['target'].'"':'').' aria-pressed="'.(in_array('active',$info)?'true':'false').'" autocomplete="off" onclick="'.htmlentities($onclick).'">'.$value.'</button>';
//nx: add class="cls" to the exsisting class
}
function button($value='Button',$onclick='',$type='',$info=[]){return self::btn($value,$onclick,$type,$info);}
function buttonGroup($btns=[],$vertical=false,$extra=''){return self::btnGroup($btns,$vertical,$extra);}
function btnGroup($btns=[],$vertical=false,$extra=''){
$x.='<div class="btn-group'.($vertical?'-vertical':'').'" role="group" '.$extra.'>';
foreach($btns as $v){
    if(is_array($v))$x.='<div class="btn-group" role="group">'.$this->dropdown($v[0],$v[1],$v[2],$v[3],$v[4]).'</div>';
   // else $x.='<button type="button" class="btn btn-secondary">'.$v.'</button>';
     else $x.=$v; //else $x.=$this->addClass('btn-secondary',$v);
}
return $x.'</div>'; //.btn-group
}

function btnToolbar($groups=[]){
$x.='<div class="btn-toolbar" role="toolbar">';
foreach($groups as $v)$x.=$v; //groups may be btn-group or input-group , create the final html for each group using btnGroup() or inputGroup()
return $x.='</div>';
}
function dropdown($title,$items=[],$btn=false,$extra='',$extra_menu='',$right=false,$up=false,$id=''){  //nx: $extra=[nav,menu,item]
  //nx: $items = [ [1,2,3] , [4,5,6] , [title=>part3 , 7,8,9]  ]
//if(empty($id))$id=str_replace(' ','_',$title);
if($up)$dir='dropup';else $dir='dropdown';
if($btn){if(empty($btn))$btn='secondary';$x='<div '.$this->addClass($dir,$extra,false).'><button id="'.$id.'" type="button" class="btn btn-'.$btn.' dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$title.'</button><div '.$this->addClass('dropdown-menu '.($right?'dropdown-menu-right':''),$extra_menu,false).' aria-labelledby="'.$id.'">';}
else $x='<div '.$this->addClass($dir,$extra,false).'><a id="'.$id.'"  class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$title.'</a><div class="dropdown-menu'.($right?' dropdown-menu-right':'').'" '.$extra_menu.'" aria-labelledby="aa'.$id.'bb">';
foreach($items as $v){if($v=='|')$x.='<div class="dropdown-divider"></div>';else $x.='<div class="dropdown-item">'.$v.'</div>';}
return $x.'</div></div>';
}

function card($blocks=[],$info=[],$extra=''){
 $x='<div class="card'.(in_array('inverse',$info)?' card-inverse':'').(in_array('center',$info)?' text-center':'').(!empty($info['type'])?' card-'.(in_array('outline',$info)?'outline-':'').$info['type']:'').'"'.$extra.'>'.$this->br;
 if(!empty($info['header']))$x.='<div class="card-header bold">'.$info['header'].'</div>'.$this->br;
 if(!empty($info['img']))$x.='<img class="card-img'.(!in_array('overlay',$info)?'-top':'').'" src="'.$info['img'].'" />'.$this->br;
 foreach($blocks as $b){
 $x.='<div class="'.(in_array('overlay',$info)?'card-img-overlay':'card-block').'"'.(!empty($info['id'])?' id="'.$info['id'].'"':'').'>'.(!empty($b[0])?'<h4 class="card-title">'.$b[0].'</h4>':'').(!empty($b[1])?'<p class="card-text">'.$b[1].'</p>':'').$this->br;
 //if(isset($b[2])){foreach($b[2] as $v){$x.='<a class="card-link" href="'.$v[1].'" '.($v[2]?'target="_blank"':'').'>'.$v[0].'</a>';}}
 if(!empty($b[2])){if(is_array($b[2])){foreach($b[2] as $v){$x.=$this->addClass('card-link',$v);}}else $x.=$this->addClass('card-link',$b[2]);}

 $x.='</div>'.$this->br; //.card-block
 }
 if(!empty($info['footer']))$x.='<div class="card-footer text-muted">'.$info['footer'].'</div>';
 return $x.'</div>';
 //nx: create links via $html->link() and add .card-link into existing class
}
function cardGroup($cards=[],$type='group'){
$x='<div class="card-'.$type.'">';foreach($cards as $v){$x.=$v;}
return $x.'</div>';
}
function mediaBox($media,$content='',$right=false,$list=false){ //use css direction:rtl instead of $right
//image or video besides the content that dosent wrap arount the media
//$list only used to adjust the output code for medialist()
$media='<span class="media-object">'.$media.'</span>'; //.media-object is not a bootstrap class
$content='<div class="media-body">'.$content.'</div>';
if($list)$x='<li class="media">'.$this->br;else $x='<div class="media">'.$this->br;
if(!$right)$x.=$media.$this->br.$content;else $x.=$content.$this->br.$media;
if($list)$x.='</li>'.$this->br;else $x.='</div>'.$this->br;
return $x;
}

function mediaList($mediaBoxes=[],$right=false){  //nx: $mediaBoxes=[$b->mediabox(),....] not =[ [v0,v1,v2],... ]
    $x='<ul class="list-unstyled mediaList">'.$this->br;foreach($mediaBoxes as $v){if($right)$v[2]=true;$x.=$this->mediaBox($v[0],$v[1],$v[2],true);}$x.='</ul>'.$this->br; //.mediaList is not a bootstrap class
    return $x;
}
function quote($content,$source='',$right=false){
 //nx: $this->dir=rtl; to set a global default direction for all the page , replace $right=true with $change_fir=true
$x='<blockquote class="blockquote '.($right?'blockquote-reverse':'').'"><p>'.$content.'</p>'.$this->br;
if(!empty($source))$x.='<footer class="blockquote-footer"><cite title="">'.$source.'</cite></footer>'.$this->br;//nx: trouble in title="source" if sourse is html
return $x.'</blockquote>'.$this->br;
}

function ul($items=[],$m=0){ //cannot use list()
//$m=1 : remove list-style ; $m=2 : inline-list (horizontal list)  and also removing list-style
 $x='<ul class="'.($m==2?'list-inline':$m==1?'list-unstyled':'').'">'.$this->br;
 foreach($items as $v){$x.='<li '.($m==2?'class="list-inline-item"':'').'>'.$v.'</li>'.$this->br;}
 return $x.'</ul>'.$this->br;
 }
function table($data=[],$head=[],$borders=false,$dark=false){
$x.='<table class="table'.($dark?' table-inverse':'').($borders?' table-bordered':'').' table-striped ">'.$this->br; //table-hover
if(count($head)>0){$x.='<thead class="thead-inverse"><tr>'.$this->br; foreach($head as $v){$x.='<th>'.$v.'</th>';}$x.='</thead>'.$this->br;}
$x.='<tbody>'; foreach($data as $v){$x.='<tr>';foreach($v as $v2){$x.='<td>'.$v2.'</td>';}$x.='</tr>';}$x.='</tbody>';
return $x.'</table>';
//nx: colspan="2" (i.e rowsize = 2)
//nx: color some <tr> with table-* (active , success , warning , danger , info)
}
function figure($data,$caption='',$right=false){
return '<figure class="figure">'.$data.(!empty($caption)?'<figcaption class="figure-caption '.($right?'text-right':'').'">'.$caption.'</figcaption>':'').'</figure>';
}
function alert($msg,$type='info',$extra='',$close=true,$id=''){
    //put .alert-link for links of $msg , or add this Css code .alert a{ color:inherit; font-weight: 700 }
    //fade show to add animation on close
    //use the $id to track close btn $('#$id).on('closed.bs.alert', function () { .. })
    //close.bs.alert : occures after clicking on close btn , closed.bs.alert occures after closing & removing alert box
    $msg=preg_replace_callback('#(<a.+?>.+?</a>)#',function($m){return $this->addClass('alert-link',$m[0],true);},$msg);
    if($type=='ok')$type='success';elseif($type=='error')$type='danger';return '<div '.$this->addClass('alert alert-'.$type.($close?' alert-dismissible fade show':''),$extra,false).' role="alert" id="'.$id.'">'.($close?'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>':'').$msg.'</div>';
    }
function badge($content,$type='default'){return '<span class="badge badge-'.$type.'">'.$content.'</span>';}
function tag($content,$type='default'){return $this->badge($content,$type);}//,$x=false ;if($x){$content=trim($content);if(empty($x))return '';}
function path($data=[],$base=''){
    $x.='<ol class="breadcrumb">';$c=count($data)-1;
    for($i=0;$i<=$c;$i++){
    $part='';
    if(!empty($data[$i][1])||(!empty($base)&&$i!=$c))$part='<a href="'.$base.$data[$i][1].'">'.$data[$i][0].'</a>';else $part=$data[$i][0];
    $x.='<li class="breadcrumb-item'.($i==$c?' active':'').'">'.$part.'</li>';
    }
    return $x.'</ol>';
    }
function collapse($title='',$content='',$id='',$show=false,$type=null){
$x='<div class="card"><div class="card-header"><button class="btn'.(!empty($type)?' btn-'.$type:'').'" type="button" data-toggle="collapse" data-target="#'.$id.'" aria-controls="'.$id.'">'.$title.'</button></div>';
$x.='<div class="collapse'.($show?' show':'').'" id="'.$id.'"><div class="card card-block">'.$content.'</div></div>';
return $x;
}

function collapseGroup($collapses=[],$id='',$accordion=false){
$x='<div id="'.$id.'" class="collapseGroup" role="tablist" aria-multiselectable="true">'.$this->br;
if(!$accordion){
$x.='<div class="card"><div class="card-header">'.$this->br; //also btns may be wrapped with .btn-group
foreach($collapses as $v){$x.='<button class="btn '.(!empty($v[4])?' btn-'.$v[4]:'').'" type="button" data-toggle="collapse" data-target="#'.$v[2].'" data-parent="#'.$id.'" aria-controls="'.$v[2].'">'.$v[0].'</button>'.$this->br;}
$x.='</div>';//.card-header
foreach($collapses as $v){$x.='<div class="collapse'.($v[3]?' show':'').'" id="'.$v[2].'"><div class="card card-block">'.$v[1].'</div></div>'.$this->br;}
$x.='</div>';//.card
}else{
foreach($collapses as $v){
//$x.='<div class="card"><div class="card-header" role="tab">'.$this->br.'<button class="btn '.(!empty($v[4])?' btn-'.$v[4]:'').'" type="button" data-toggle="collapse" data-target="#'.$v[2].'" data-parent="#'.$id.'" aria-controls="'.$v[2].'">'.$v[0].'</button>'.$this->br.'</div>';
//$x.='<div class="card"><div class="card-header" role="tab">'.$this->br.'<h5><a class=""type="button" data-toggle="collapse" href="#'.$v[2].'" data-parent="#'.$id.'" aria-controls="'.$v[2].'">'.$v[0].'</a></h5>'.$this->br.'</div>';
$x.='<div class="card"><div class="card-header pointer" role="tab" data-toggle="collapse" data-target="#'.$v[2].'" data-parent="#'.$id.'" aria-controls="'.$v[2].'">'.$this->br.'<h5>'.$v[0].'</h5>'.$this->br.'</div>';
$x.='<div id="'.$v[2].'" class="collapse'.($v[3]?' show':'').'" role="tabpanel" aria-labelledby="'.$id.'">'.$this->br.'<div class="card-block">'.$v[1].'</div></div></div>'.$this->br;
}
}
return $x.='</div>'.$this->br;
}

function jumbotron($content='',$fluid=false){
    if($fluid)$content='<div class="container-fluid">'.$content.'</div>';
    return '<div class="jumbotron '.($fluid?'jumbotron-fluid':'').'">'.$content.'</div>';
}
function bigMessage($content='',$fluid=false){return $this->jumbotron($content,$fluid);}
function bigError($content='',$fluid=false){return $this->jumbotron($this->alert($content,'error'),$fluid);}

function listView($items=[],$op=[],$auto=true){ // [html,active?,badge,extra]
$op['extra']=$this->addClass('list-group '.$op['class'],$op['extra'],false);
$x='<ul '.$op['extra'].'>';
foreach($items as $v){
if(!is_array($v))$v=[$v];
$item_extra=$this->addClass('list-group-item list-group-item-action'.($v[1]?' active':'').(!empty($v[2])?' justify-content-between':''),$op['item_extra'],false);
if(!is_array($v))$v=[$v];
if(!empty($v[2]))$v[0].=$this->tag($v[2]);
$x.='<li '.$item_extra.' '.$v[3].($auto?' dir="auto"':'').'>'.$v[0].'</li>';}
return $x.'</ul>';
}

function chat($name='',$msg='',$img=null,$date=null,$link=null,$footer='',$extra='',$status=null){
    //nx: [msg=> ,name=> ,....]
    //img:html code (not src) ; $link = link to profile ; footer is .text-muted and may be html code ot plain text
    //use $extra to customize each chat msg ex: put style or class or any html attributes
    //$status: 0,1=delivered,2=read
    //use listView() to build the full chat history

    if(!empty($name)&&!empty($link))$name='<a href="'.$link.'" target="_blank">'.$name.'</a>';
    if(is_int($date))$date=date('j/m/Y g:i A'); //be sure to provide the timestamp (or date) of the user not time server
    $x='<div '.$this->addClass('d-flex w-100 justify-content-between',$extra,false).'><h5 class="mb-1">'.$name.'</h5><small  class="text-muted">'.$date.'</small></div>';
    $x.='<p class="mb-1">'.$msg.'</p>';
    if(!empty($footer))$x.='<small class="text-muted">'.$footer.'</small>';

    return $this->mediaBox($img,$x);
}
function modal($id,$btn=null,$content='',$title='',$footer=null,$size=''){
$x='';
if(!empty($btn)){$btn=$this->addAttribute('data-toggle','modal',$btn,true,true);$btn=$this->addAttribute('data-target','#'.$id,$btn,true,true);$x=$btn;}
if($content!==null){
$x.='<div class="modal fade" id="'.$id.'" tabindex="-1" role="dialog" aria-labelledby="'.$id.'" aria-hidden="true"><div class="modal-dialog'.(!empty($size)?' modal-'.$size:'').'" role="document"><div class="modal-content">';
$x.='<div class="modal-header">'.(!empty($title)?'<h5 class="modal-title">'.$title.'</h5>':'').'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> ';
$x.='<div class="modal-body">'.$content.'</div>';
$x.='<div class="modal-footer text-muted">'.$footer.'</div>';//if($footer!=null)
$x.='</div></div></div>';
}
return $x;
}
function dialog($id,$btn=null,$content='',$title='',$footer='',$size=''){return $this->modal($id,$btn,$content,$title,$footer,$size);}

function tabs($items=[],$fill=false,$nav_type=''){
//nx: btn_extra pane_extra
//nx: nav-item may be an interactive item (link/button) , not showing any content (ex: normal link)
//$nav_type : tabs , pills , NULL
$x='<nav class="nav'.($fill?' nav-fill':'').(!empty($nav_type)?' nav-'.$nav_type:'').'" role="tablist">'; $y='';
foreach($items as $v){
 //nx: dropdown needs to be adjusted
//$x.=$this->btn($v['title'],$v['type'],$v['size'],['extra'=>'data-toggle="tab" data-target="#'.$v['id'].'" class="nav-item"'.(in_array('active',$v)?' active':'').'"']);
if(!empty($v['dropdown'][0]))$v['title']=$this->dropdown($v['dropdown'][0],$v['dropdown'][1],$v['dropdown'][2],$v['dropdown'][3],$v['dropdown'][4]);
$x.='<li class="nav-item'.(!empty($v['dropdown'][0])?' dropdown':'').'"><a class="nav-link'.(in_array('active',$v)?' active':'').'" href="#'.$v['id'].'" data-toggle="tab">'.$v['title'].'</a></li>';
$y.='<div class="tab-pane'.(in_array('active',$v)?' active':'').'" id="'.$v['id'].'" role="tabpanel">'.$v['content'].'</div>';
};
return $x.'</nav><div class="tab-content">'.$y.'</div>';
}
function nav($items=[],$fill=false,$nav_type=''){
//Tabs showing contents ; but nav are buttons & links
$x='<ul class="nav">';
foreach($items as $v){
if($this->is_tag('a',$v))$v=$this->addClass('nav-link',$v);
$x.='<li class="nav-item active">'.$v.'</li>';
}
return $x.'</ul>';
}

function navbar($logo='',$content='',$position='',$bg='',$id='navbar',$size='md',$inverse=true){
  //$size : responsive size , where navbar toggles to button
 //use nav(),form-inline,... to create content
 //nx: add position ex: fixed-top , sticky-top or add via $extra ..
 // $info[] : bg,inverse,fixed=top/bottom, extra
 //nx: for <nav> only addClass('navbar-nav mr-auto',..); this will make other elements at the other side
 if($bg=='dark')$bg='inverse';
if($position=='top'||$position=='fixed')$position=' fixed-top';elseif($position=='bottom')$position=' fixed-bottom';elseif($position=='sticky')$position=' sticky-top';
$content=preg_replace('#(<.+class\s*=\s*["|\'][.+ ]?)nav([.+]?["|\'].+>)#s','$1navbar-nav mr-auto$2',trim($content));
$x='<nav class="navbar navbar-toggleable-'.$size.($inverse?' navbar-inverse':'').(!empty($bg)?' bg-'.$bg:'').$position.'"> <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#'.$id.'" aria-controls="'.$id.'" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button> ';
if(!empty($logo))$x.='<span class="navbar-brand">'.$logo.'</span>';
$x.='<div class="collapse navbar-collapse" id="'.$id.'">'.$content.'</div>';

return $x.'</nav>';
}

function justify($content,$tag='div',$extra=''){
return '<'.$tag.' '.$this->addClass('d-flex w-100 justify-content-between',$extra,false).'>'.$content.'</'.$tag.'>';
}

function cols($rows=[],$c=null,$gutters=true){
 if($c==='fluid')$c='container-fluid';elseif($c)$c='container';else $c='';
 $x='<div class="'.$c.'">'.$this->br;
 foreach($rows as $r){
 $x.='<div class="row '.(!$gutters?' no-gutters':'').'">'.$this->br; //.no-gutters removes padding&margin from cols
 if(!is_array($r))$r=[$r];
 foreach($r as $col){
 $x.=$this->addClass('col',$col).$this->br; //if array : nested cols

 }
 $x.='</div>'.$this->br;//.row
 }

 return $x.'</div>'.$this->br;//.container
}


function keywords($keys){    //keys=a,b,c or [a ,b ,c]
   // $keys=trim($keys);if(empty($keys))return;
   if(is_string($keys)){
   $keys=trim($keys);
   if(empty($keys))return '';
   $keys=explode(',',$keys);
   }
   $x='';
    foreach($keys as $k=>$v){
     if(is_string($k)){$k=trim($k);if(empty($k))continue;$x.=$this->tag($this->link($k,$v));}
     else{$v=trim($v);if(empty($v))continue;$x.=$this->tag($v);}
    }
    return $x;
}
################ / Bootstrap ######################
################ helpers ######################


################ / helpers ######################

function up($name='Select files',$multiple=true,$extra=''){
return '<label class="custom-file"><input type="file" '.($multiple?'name="'.$name.'[]" multiple="multiple" ':'name="'.$name.'" ').$extra.' class="custom-file-input"><span>'.$name.'</span></label>';
}

function code($s,...$p){//the (...$p) operator needs php 5.6 or use func_get_arguments()    ;nx:  if($sc)$x='</script>';
if(is_array($s)){
    $code='';
    foreach($s as $v){$s=$v[0];array_shift($v);$code.=$this->code($s,...$v);}
    return $code;
}
if($s=='google_analytics'||$s=='analytics'){  //UA_id,user_id,session,options[]  //nx: add options
 $x='(function(i,s,o,g,r,a,m){i["GoogleAnalyticsObject"]=r;i[r]=i[r]||function(){
 (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,"script","https://www.google-analytics.com/analytics.js","ga");'.$this->br;
  if(!strstr($p[0],'UA-'))$p[0]='UA-'.$p[0].'-1';
  $x.='ga("create","'.$p[0].'","auto",{'.(!empty($p[1])?'"clientId":"'.$p[1].'",':'').'"alwaysSendReferrer":true,"cookieName":"gaCookie","cookieExpires":315360000});'.$this->br;
  $x.='ga("set",{'.(!empty($p[2])?'"userId":"'.$p[2].'",':'').'"encoding":"UTF-8"});'.$this->br;
  return $x.'ga("send", "pageview");'.$this->br;
  //nx: event: ga(send,event,$category,$action,$label,$fieldsObject);
}elseif($s=='adsense'){// format(type),id/slot,layout,layout_key,extra , other options...
if(strstr($_SERVER['HTTP_HOST'],'localhost'))return ; //or add data-adtest="on" https://support.google.com/adxseller/answer/4599514?hl=en
 //1- load script code(adsense,'src') 2-place ad units code(adsense,$id,$slot) 3-push code(adsense,'push')
 if($p[0]=='src')return 'http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js';
 elseif($p[0]=='push'){
 if(is_array($p[1])){$tmp='';foreach($p[1] as $k=>$v)$tmp.=$k.'="'.$v.'" ';$p[1]=$tmp;}
 return '(adsbygoogle = window.adsbygoogle || []).push({'.(!empty($p[1])?$p[1]:'').'});'.$this->br;
 } elseif($p[0]=='pushAll'){
 return 'adsbygoogle = window.adsbygoogl||[];ins=document.getElementsByTagName("ins");for(i=0;i<ins.length;i++){adsbygoogle.push({});}'.$this->br; //or create array of adUnit options i.e  adsbygoogle=[ [],[],[adSlot:1] ]
 }

 list($id,$slot)=explode('/',$p[1]);
 if(!strstr($id,'ca-pub-'))$id='ca-pub-'.$id;//adsense_id
 if($p[0]=='page')return $this->code('adsense','push','google_ad_client: "'.$id.'",enable_page_level_ads: true');
 if($p[0]=='top'||$p[0]=='responsive')$p[0]='auto';
 elseif($p[0]=='posts'||$p[0]=='feed'||$p[0]=='in-feed'){$p[0]='fluid';if(!isset($p[2]))$p[2]='image-top';} //in-feed Ads
 elseif($p[0]=='article'||$p[0]=='in-article'){$p[0]='fluid';$p[2]='in-article';}
 //place it inside head or just after <body>
 $x='<ins class="adsbygoogle"  data-ad-client="'.$id.'"'.(!empty($slot)?' data-ad-slot="'.$slot.'"':'').' data-ad-format="'.(!empty($p[0])?$p[0]:'auto').'"'.(!empty($p[2])?' data-ad-layout="'.$p[2].'"':'').(!empty($p[3])?' data-ad-layout-key="'.$p[3].'"':'').' '.(is_string($p[4])?$p[4]:'').'></ins>';
 if(end($p)===true)$x.='(adsbygoogle = window.adsbygoogle || []).push({});'; //to dismiss this code put the last $p[n]=false  (if needed to push() ad units later or via js )
 return $x.$this->br;
 //imp: without display:block adsenese will thought that this area width=0 ; so no area available for any ad
}elseif($s=='google_search'||$s=='search'){ //cx,options[]
return '<script>(function(){'.$this->br.'varcx="'.$p[0].'";var gcse=document.createElement("script");gcse.type="text/javascript";gcse.async=true;gcse.src="https://cse.google.com/cse.js?cx="+cx;var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(gcse,s);})();</script><gcse:search></gcse:search>'.$this->br;   //nx: remove <script>
}elseif($s=='BuzzBoost'){ //display Rss feed as html comtent
  $op=$p[1];
  $x='';
  if($op['items'])$x.='&nItems='.$op['items']; //number of items
  if($op['title'])$x.='&feedTitle='.$op['title'].'&displayTitle';else $x.='&displayTitle=false';
  if(is_array($op['hide'])){
      if(in_array('content',$op['hide']))$x.='&displayExcerpts=false';else $x.='&displayExcerpts=true';
      if(in_array('date',$op['hide']))$x.='&displayDate=false';else $x.='&displayDate=true';
      if(in_array('files',$op['hide']))$x.='&displayEnclosures=false';else $x.='&displayEnclosures=true';
  }

  if(isset($op['plain'])){if(empty($op['plain']))$op['plain']='0'; $x.='&excerptFormat=plain&excerptLength='.$op['plain'];} //leave it to show the content as full html
  if(!empty($op['date']))$x.='&dateFormat='.$op['date']; //'dd/MM/YYYY'
  if(in_array('blank',$op))$x.='&openLinks=new'; //target="_blank"
  return 'http://feeds.feedburner.com/'.$p[0].'?format=sigpro'.$x;
}elseif($s=='fb_ads'){
    //ads for instance articles
    return '<figure class="op-ad"><iframe style="border:0; margin:0;" src="https://www.facebook.com/adnw_request?placement='.$p[0].'" '.(!empty($p[1])?'width="300"':'').' '.(!empty($p[2])?'height="250"':'').'></iframe></figure>';

 }
}


function cleanLink($str,$d='-',$en=false){   //test: cleanLink('clean' link@ x ...$%&^*&^*^*&66'); //use (-) not (_) http://rynoweb.com/clean-urls-good-seo/
if($en===1)$rgx='A-Za-z';//only english letters
elseif($en)$rgx='A-Za-z0-9';//english and numbers
else $rgx='\pL0-9'; //all unicode letters and numbers ; user to replace french,greek,... letters to the oposite english letters
$str = preg_replace('#[^'.$rgx.$d.']#u', '', str_replace(' ',$d, trim($str)));  //allow all unicode charactesr including Arabic
return trim(preg_replace('#'.$d.'+#',$d, $str),$d);  //remove duplicate __ "str_replace not help in __ & ___" ; remove first&last _ if exists
}
}

return new ui();
?>
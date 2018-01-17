<?php  //provideing code for fb instance articles  ; nx: title,descriotion -> html2tet()
class instantArticles{
public $zone=null; //time zone ex: +0200

function __construct(){}

#------------------------ Main ------------------------------------------#
function article($title,$head=[],$body='',$footer=[],$time=null,$modified=null,$rtl=false,$more='read more...'){    //create a full instant article page
$body=$this->body($body);
//if(empty($body))return '';
return '<!DOCTYPE html><html'.($rtl?' dir="rtl"':'').'>'.PHP_EOL.$this->head($title,$head).'<body><article>'.PHP_EOL.$this->header($title,$head,$time,$modified).$body.($more&&!empty($head['link'])?'<p><a href="'.$head['link'].(strstr('?',$head['link'])?'&':'?').'live">'.$more.'</a></p>':'').PHP_EOL.$this->footer($footer).PHP_EOL.'</article></body></html>'; //nx:media<p>body</p> for elements needs to be stand alone i.e outside <p> of body ; <p> is important, without it facebook will consider the body is empty
}

function rss($channel,$items,$ver='2.0'){  //user to convert title,description into plain text (via $data->html2text);
if(!empty($channel['zone']))$this->zone=$channel['zone'];
$x='<?xml version="1.0" encoding="UTF-8" ?><rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">'.PHP_EOL.'<channel>'.PHP_EOL;
if(!empty($channel['title']))$x.='<title>'.$channel['title'].'</title>'.PHP_EOL;
if(!empty($channel['link']))$x.='<link>'.$channel['link'].'</link>'.PHP_EOL;
if(!empty($channel['description']))$x.='<description>'.$channel['description'].'</description>'.PHP_EOL;
if(!empty($channel['language']))$x.='<language>'.$channel['language'].'</language>'.PHP_EOL;
if(!empty($channel['time']))$x.='<lastBuildDate>'.$this->time($channel['time'],false).'</lastBuildDate>'.PHP_EOL;
 if(!empty($channel['img']))$x.='<image><url>'.$channel['img'].'</url></image>'.PHP_EOL;

foreach($items as $item){
 //$h=['title'=>$item['title'],'link'=>$item['link'],'description'=>$item['description'],'img'=>$item['img'],'autor'=>$item['author'],'subtitle'=>$item['subtitle'],'kicker'=>$item['kicker']];
 if(is_string($item)){$x.=$this->item($item);continue;}//it already contains the article inside it
 $item['content']=$this->article($item['title'],$item,$item['content'],$item['footer'],$item['time'],$item['modified'],$item['rtl'],$item['more']);
 if(empty($item['content']))continue;   //dont send empty content as it considerd as fetal error
 $x.=$this->item($item);
}
 return $x.'</channel></rss>';

}

#--------------------------- /Main ---------------------------------------#
#--------------------------- Article parts -------------------------------#
function head($title,$h=[]){
$x='<head>'.PHP_EOL.'<meta charset="utf-8">'.PHP_EOL;
foreach($h as $k=>$v){
if(is_int($k))$x.=$v.PHP_EOL;
if(!array_key_exists('version',$h)&&!array_key_exists('ver',$h))$h['version']='1.0';
$v=str_replace('"','\'',$v);
if($k=='title'&&!empty($v)){if(empty($title))$title=$v;}
elseif($k=='ads'&&$v!==false){$x.='<meta property="fb:use_automatic_ad_placement" content="enable=true ad_density=default"/>'.PHP_EOL;} //place ad template inside <header>
elseif(($k=='description'||$k=='desc')&&!empty($v))$x.='<meta property="og:description" content="'.$v.'"/>'.PHP_EOL;
elseif($k=='link'&&!empty($v))$x.='<link rel="canonical" href="'.$v.'" />'.PHP_EOL;
elseif($k=='img'&&!empty($v))$x.='<meta property="og:image" content="'.$v.'"/>'.PHP_EOL;
elseif($k=='version'||$k=='ver'){if(empty($v))$v='1.0';$x.='<meta property="op:version" content="'.$v.'"/>'.PHP_EOL;}
elseif($k=='style'){if(empty($v))$v='1.0';$x.='<meta property="fb:article_style" content="'.$v.'"/>'.PHP_EOL;}
elseif($k=='meta'&&!empty($v)){$x.='<meta property="'.$v[0].'" content="'.$v[1].'"/>'.PHP_EOL;}
//else $x.='<meta name="'.$k.'" content="'.$v.'" />'.PHP_EOL;  dont place headder elements
}
if(!empty($title))$x.='<meta property="og:title" content="'.$title.'"/>'.PHP_EOL;
$x.='</head>'.PHP_EOL;
return $x;
}


function header($title,$h=[],$time=null,$modified=null){  //the header part inside <body> //or move $time,modified inside $h
$x='<header>'.PHP_EOL;
foreach($h as $k=>$v){
if(is_int($k))$x.=$v.PHP_EOL;
elseif($k=='title'){if(empty($title))$title=$v;}
elseif($k=='img'&&!empty($v))$x.=$this->img($v).PHP_EOL;
elseif($k=='author'){if(is_array($v))foreach($v as $vv)$x.=$this->author($vv);else $x.=$this->author($v);}
elseif($k=='subtitle'&&!empty($v))$x.='<h2>'.$v.'</h2>'.PHP_EOL;
elseif($k=='kicker'&&!empty($v))$x.='<h3>'.$v.'</h3>'.PHP_EOL;
elseif($k=='ads'&&is_string($v))$x.=$this->ad($v.'&adtype=banner300x250','fb').PHP_EOL; //ad template for fb:use_automatic_ad_placement
}
if(!empty($title))$x.='<h1>'.$title.'</h1>'.PHP_EOL;
if($modified===true||$modified===1)$modified=$time; //must be before $time=$this->time()
$time=$this->time($time);
$x.='<time class="op-published" datetime="'.$time[0].'">'.$time[1].'</time>';
if(!empty($modified)){
    $modified=$this->time($modified);
    $x.='<time class="op-modified" datetime="'.$modified[0].'">'.$modified[1].'</time>';
}
return $x.PHP_EOL.'</header>'.PHP_EOL;
}


function footer($credits='',$copyright=''){
  if(is_array($credits))return $this->footer($credits[0],$credits[1]);
 $x='<footer>';
 if(!empty($credits)){
  $x.='<aside>';
  if(is_array($credits))foreach($credits as $v)$x.='<p>'.$v.'</p>';
  else $x.=$credits;
  $x.='</aside>';
 }
 if(!empty($copyright))$x.='<small>'.$copyright.'</small>';
 return $x;
}

function body($text,$br=false){ // return $text;
 //convert normal html tags to instant article codes
   if($br)$text=nl2br($text);
   $text=str_replace("\r\n",'',trim($text));

  $text=preg_replace('#(.*)\s*(?:<br\s*/?>|$)#iUs','<p>$1</p>'.PHP_EOL,$text); //convert linebreaks to <p> to avoid warning:Too Many Line Breaks
  $text=preg_replace('#<(/)?(div|article|section)[^>]*>#iUs','<$1p>'.PHP_EOL,$text); //convert block elements to <p>
  $text=preg_replace('#(<p[^>]*>[^<>]*)(<(h[12]|p)[^>]*>[^<]*</\3>)([^<]*</p>)#iUs','$1</p>'.PHP_EOL.'$2'.PHP_EOL.'<p>$4'.PHP_EOL,$text); //move nested p,h1,h2 out of <p>
  $text=preg_replace('#<(h[3456])[^>]*>(.*)</\1>#iUs','<p>$2</p>'.PHP_EOL,$text); //h3->6 not supported
  $text=preg_replace('#<p>\s*((?:https?://|ftps?://|www\.)[^\s<]+)#is','<a href="$1">$1</a>',$text); //convert plain liks to hyper links; nx: starts with http or ends with .com
   //'/(?:\s*<br[^>]*>\s*){2,}/iUs'=>'<br />', //remove multiple <br> after replaceng & removing all tags (already replaced with <p>)


  //adjust media elements by DomDocument ex: <img> whitch parent is not <figure> to <figure><img></>
  $text=trim($text); //must be trimmed AFTER removing empty tags
  $d=new DomDocument;
  $d->loadHTML('<body>'.mb_convert_encoding($text,'HTML-ENTITIES','UTF-8').'</body>',8196); //we need to add all tag as a children of only one parent


  $figures=['img','iframe'];
  foreach($figures as $fig){
   $el=$d->getElementsByTagName($fig);
   if(!$el||$el->length<1)continue;
   foreach($el as $v){
      $p=$v->parentNode;
      if($p->tagName=='figure'){
       if($fig=='iframe')$p->setAttribute('class','op-interactive');
       continue;
      }
      $e= $d->createElement($fig);
      $e->setAttribute('src',$v->getAttribute('src'));
      $e->setAttribute('width',$v->getAttribute('width'));
      $e->setAttribute('height',$v->getAttribute('height'));

      $f= $d->createElement('figure');
      if($fig=='iframe')$f->setAttribute('class','op-interactive');
      $f->appendChild($e);    //nx: directly append $v to $f then replece $v with $f; $f->appent($v); $p->replace($f,$v)
      if($p->tagName=='p'){$v=$p;$p=$p->parentNode;}  //nx: check first that parentNode dosent have other childs
      $p->replaceChild($f,$v);
  }
  }

  $a=$d->getElementsByTagName('a');
  foreach($a as $v){
      $h=$v->getAttribute('href');
      $x=substr($h,0,10);
      if(strstr($x,'://'))continue;
      $v->setAttribute('href','http://'.$h);//resolve Incomplete  URL ;nx:resolve relative path; or preg_replace('/href\s*=\s*("|\')\s*((?!https?:\/\/|ftps?:\/\/).*)\1/iUs','href="http://$2"',$text);
  }
  $text=$d->saveHTML();  //nx: encoding ;
  $text=preg_replace('#</?body[^>]*>#iUs','',$text); //remove <body></body> , or use DOM to insert <header>,<footer>,...
  $text=mb_convert_encoding($text,'UTF-8','HTML-ENTITIES'); //return $text to it's original encoding
  do {$text=preg_replace('#<p\s*[^>]*>\s*</p\s*>#iUs', '', $text,-1,$count); } while ( $count > 0 );   //remove empty tags (must be last step) ; run multiple times until no empty tag rested for example <p><p></p></p>
  //do {$text=preg_replace('#<(\w+)\s*[^>]*>\s*</\1\s*>#iUs', '', $text,-1,$count); } while ( $count > 0 );   //remove empty tags ; run multiple times until no empty tag rested for example <p><p></p></p>
  //nx: imp: some empty tags are not empty (self closed) ex: <iframe----></iframe> , must be executed  (temporary only remove empty <p>)


   //if(substr($text,0,3)!="<p>")$text='<p>'.$text.'</p>';   //to be sure that all <body> elements are inside <p>s ;
   return $text;

}
#--------------------------- /Article parts -------------------------------#
#--------------------------- helper functions -------------------------------#
function time($time=null,$readable=true){ //nx: needs review ; set/change timeZone via php
if(empty($time))$time=time();
$d=date('c',($time)); //ISO 8601 ;if(empty($this->zone))$this->zone='Z'; $d=date('Y-m-d\TH:i:s',($time)).$zone;
if($readable)return [$d,date('d/M/Y H:i a',$time)];
else return $d;
}


function img($src,$caption='',$location='',$mode='',$cite=''){ //nx: data-feedback="fb:likes fb:comments"
if(empty($src))return '';
$x='<figure '.(!empty($mode)?'data-mode="'.$mode.'"':'').'><img src="'.$src.'" />';
if(!empty($caption))$x.='<figCaption><h1>'.$caption.'</h1></figcaption>';
if(!empty($location))$x.=$this->location($location);
if(!empty($cite))$x.='<cite>'.$cite.'</cite>';
return $x.'</figure>';
}

function author($name,$extra=''){
    if(!empty($name))return '<address><a>'.$name.'</a>'.$extra.'</address>';
}

function caption($text,$cls='',$cite=''){ //$cls: space separated classess
    if(!empty($text))return '<figCaption'.(!empty($cls)?' class="'.$cls.'"':'').'><h1>'.$text.'</h1>'.(!empty($cite)?'<cite>'.$cite.'</cite>':'').'</figCaption>';
    //some functions may pass a null or empty value of text , no need to return captions ex: location(....,$map=true)
}

function embed($data,$src=true,$cls='',$caption=''){
   $x='<figCaption'.(!empty($cls)?' class="'.$cls.'"':'').'>';
   if($src)$x.='<iframe src="'.$data.'" />';
   else $x.='<iframe>'.$data.'</iframe>';
   if(!empty($caption))$x.=$this->caption($caption);
   return $x.'</figCaption>';
}

function _list($items,$ordered=false){
   $tag=($ordered?'ul':'ol');
   $x='<'.$tag.'>';
   foreach($items as $v){$x.='<li>'.$v.'</li>';}
   return $x.'</'.$tag.'>';

}
function location($coords=[],$title='',$opt=[],$map=false){
   if(count($coords)==0&&empty($title))return;
  if(empty($opt['type']))$opt['type']='point';
  if(in_array('hybird',$opt))$opt['style']='hybird';elseif(empty($opt['style']))$opt['style']='satellite';
  $x=array('geometry'=>['coordinates'=>$coords,'type'=>$opt['type']],'properties'=>['title'=>$title,'style'=>$opt['style']]);
  if(!empty($opt['radius']))$x['properties']['raduis']=$opt['raduis'];
  if(!empty($opt['pivot']))$x['properties']['pivot']=$opt['pivot']; elseif(in_array('pivot',$opt))$x['properties']['pivot']=true;

  $x='<script class="op-geotag" type="application/json">'.@json_encode($x).'</script>';
  if($map)return '<figure class="op-map">'.$this->caption($map).'</figure>';
  else return $x;
}

function related($links){
 $x='<ul class="op-related-articles">';
 foreach($links as $v){
   $x.='<li><a href="'.$v.'"></a></li>'; //not <a>title</a>
 }
 return $x.'</ul>';
}

function slideShow($imgs,$caption='',$location=null,$cite=''){
    $x='<figure class="op-slideshow">';
    foreach($imgs as $v){
     $x.=$this->img(...$v);
    }
    return $x.$this->caption($caption).$this->location($location).(!empty($cite)?'<cite>'.$cite.'</cite>':'').'</figure>';
  }
function video($src,$caption='',$_360=false){
return '<figure'.($_360?' data-fb-parse-360':'').'><video><source=""></video>'.$this->caption($caption).'</figure>';

}

function ad($code,$src=true,$extra=''){
    if($src=='fb')$code='<iframe style="border:0; margin:0;" src="https://www.facebook.com/adnw_request?placement='.$code.'" '.$extra.'></iframe>' ;
    else if($src)$code='<iframe src="'.$code.'" '.$extra.'></iframe>';
    return '<figure class="op-ad">'.$code.'</figure>';

}

function ads($ads=[]){   //a group of ads , insert them in <header> to recycle ad templates , ex: if you have 3 templates and facebook wants to show 6 ads, so it will use the three templates in the same order then recycle again
 $x='<section class="op-ad-template">';
 foreach($ads as $ad)$x.=$this->ad(...$ad); //ad($ad[0],$ad[1])
 return $x.'</section>';
}

function item($item,$tags=true){
 if(is_string(($item))){
    $item=trim($item);
    if(is_string($tags))$item.='<content:encoded><![CDATA['.PHP_EOL.$tags.PHP_EOL.']]></content:encoded>'.PHP_EOL;
    if(substr($item,0,6)!='<item>')$item='<item>'.PHP_EOL.$item.'</item>'.PHP_EOL;
   return $item;
 }

 if($tags)$x='<item>'.PHP_EOL;else $x='';
 if(empty($item['guid']))$item['guid']=$item['link'];
 if(!empty($item['title']))$x.='<title>'.$item['title'].'</title>'.PHP_EOL;
 if(!empty($item['link']))$x.='<link>'.$item['link'].'</link>'.PHP_EOL;
 if(!empty($item['guid']))$x.='<guid>'.$item['guid'].'</guid>'.PHP_EOL;
 if(!empty($item['time']))$x.='<pubDate>'.$this->time($item['time'],false).'</pubDate>'.PHP_EOL;
 if(!empty($item['author']))$x.='<author>'.$item['author'].'</author>'.PHP_EOL;
 if(!empty($item['description']))$x.='<description><![CDATA['.$item['description'].']]></description>'.PHP_EOL; //$dt->html2text()
 if(array_key_exists('ads',$item)){if($item['ads']===false)$item['ads']='enable=false';elseif(!is_string($item['ads']))$item['ads']='enable=true ad_density=default';$x.='<meta property="fb:use_automatic_ad_placement" content="'.$item['ads'].'"/>'.PHP_EOL;} //dont use empty($item[ad]) as true=!empty
 $x.='<content:encoded><![CDATA['.PHP_EOL.$item['content'].PHP_EOL.']]></content:encoded>'.PHP_EOL;
 if($tags)$x.='</item>'.PHP_EOL;
 return $x;
}

}
return new instantArticles();
?>
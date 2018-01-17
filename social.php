<?php
class social{
function __construct(){}

############# Facebook ######################
//https://developers.facebook.com/docs/plugins/

function sdk($s,...$p){
if($s=='fb'||$s=='facebook')return 'http://connect.facebook.net/'.(!empty($p[0])?$p[0]:'en_US').'/sdk.js#xfbml=1&version=v'.(!empty($p[1])?$p[1]:'2.9');
elseif($s=='fb_pixel')return 'https://connect.facebook.net/'.(!empty($p[0])?$p[0]:'en_US').'/fbevents.js';
elseif($s=='instagram'||$s=='insta')return 'http://platform.instagram.com/'.(!empty($p[0])?$p[0]:'en_US').'/embeds.js';
}



function fb_sdk($lang='en_US',$ver='2.9'){ //nx: cache(<div#fb-root> file_get($sdk));
return '<div id="fb-root"></div><script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/'.$lang.'/sdk.js#xfbml=1&version=v'.$ver.'";fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));</script>'.PHP_EOL;
}


function fb_page($id,$tabs='timeline,messages',$friends=true,$js=false){ //$tabs=timeline,messages,events
if(!$js)return '<iframe class="fb-page'.(empty($tabs)?'-short':'').'" src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2F'.$id.'%2F&tabs='.$tabs.'&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile='.$friends.'&appId" style="overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>'.PHP_EOL;
return '<div class="fb-page" data-href="https://www.facebook.com/'.$id.'/" data-tabs="'.$tabs.'" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="'.$friends.'"></div>'.PHP_EOL;
}

function fb_save($link){ //dosen't support iframe
 return '<div class="fb-save" data-uri="'.$link.'" data-size="large"></div>'.PHP_EOL;
}

function fb_send($link,$ref=''){   //No iframe
    return '<div class="fb-send" data-href="'.$link.'" data-ref="'.$ref.'" data-size="large"></div>'.PHP_EOL;
}

function fb_share($link,$type='button_count',$js=false){
 if($type=='box')$type='box_count';
 if(!$js)return '<iframe class="fb-share-button" src="https://www.facebook.com/plugins/share_button.php?href='.urlencode($link).'&layout='.$type.'&size=large&mobile_iframe=true&appId" style="overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>'.PHP_EOL;
 return '<div class="fb-share-button" data-href="'.$link.'" data-layout="'.$type.'" data-size="large" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u='.urlencode($link).'">Share</a></div>'.PHP_EOL;

}

function fb_comments($url,$order='social',$num='',$width=''){  //No iframe
if($order=='old'||$order=='older')$order=='time';elseif($order=='new'||$order=='newer')$order=='reverse_time';elseif($order=='top')$order=='social';
return '<div class="fb-comments" data-href="'.$url.'" data-numposts="'.$num.'" data-order-by="'.$order.'" data-width="'.$width.'"></div>'.PHP_EOL;
}

function fb_comment($link,$width='500'){ //show a comment
  return '<div class="fb-comment-embed" data-href="'.$link.'" data-width="'.$width.'"></div>'.PHP_EOL;
}

function fb_post($link,$js=false){
 if(!$js)return '<iframe class="fb-post" src="https://www.facebook.com/plugins/post.php?href='.urlencode($link).'&show_text=true&height=&appId" style="overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>'.PHP_EOL;
 return '<div class="fb-post" data-href="'.$link.'" data-show-text="true"></div>'.PHP_EOL;

}

function fb_video($link,$post=true,$js=false){
     if(is_numeric($link))$link='https://www.facebook.com/facebook/videos/'.$link;
    if(!$js)return '<iframe src="https://www.facebook.com/plugins/video.php?href='.urlencode($link).'&width=500&show_text='.$post.'&appId" style="overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>'.PHP_EOL;
    return '<div class="fb-video" data-href="'.$link.'/" data-width="500" data-show-text="'.$post.'"></div>'.PHP_EOL;
}

function fb_follow($id,$type='standard',$js=false){    //$type=standard(+faces) , box_count , button_count , button
    if($type=='box')$type='box_count';elseif($type=='count')$type='button_count';elseif($type=='faces')$type='standard';
    if(!$js)return '<iframe  class="fb-follow fb-follow-'.$type.'" src="https://www.facebook.com/plugins/follow.php?href=https%3A%2F%2Fwww.facebook.com%2F'.$id.'&layout='.$type.'&size=large&show_faces=true&appId"  style="overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>'.PHP_EOL;
    return '<div class="fb-follow fb-follow-'.$type.'" data-href="https://www.facebook.com/'.$id.'" data-height="1000px" data-layout="'.$type.'" data-size="large" data-show-faces="true"></div>'.PHP_EOL;
}

function fb_like($link,$type='standard',$share=true,$js=false){ //https://developers.facebook.com/docs/plugins/like-button
  if($type=='box')$type='box_count';elseif($type=='count')$type='button_count';elseif($type=='faces')$type='standard';
  if(!$js)return '<iframe src="https://www.facebook.com/plugins/like.php?href='.urldecode($link).'&layout='.$type.'&action=like&size=large&show_faces=true&share='.$share.'&height=80&appId" width="450" height="80" style="overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>'.PHP_EOL;
  return '<div class="fb-like" data-href="'.$link.'" data-layout="'.$type.'" data-action="like" data-size="large" data-show-faces="true" data-share="'.$share.'"></div>'.PHP_EOL;
}

function fb_recomment($link,$type='standard',$share=true,$js=false){
  if($type=='box')$type='box_count';elseif($type=='count')$type='button_count';elseif($type=='faces')$type='standard';
  if(!$js)return '<iframe src="https://www.facebook.com/plugins/like.php?href='.urldecode($link).'&layout='.$type.'&action=recommend&size=large&show_faces=true&share='.$share.'&height=80&appId" width="450" height="80" style="overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>'.PHP_EOL;
  return '<div class="fb-like" data-href="'.$link.'" data-layout="'.$type.'" data-action="recommend" data-size="large" data-show-faces="true" data-share="'.$share.'"></div>'.PHP_EOL;
}

function fb_quote(){ //Place this code to any place in the document , when the user select any text with show a "share' button to share the selected text and og attributes
return '<div class="fb-quote"></div>'.PHP_EOL;
}

function fb_data($link,$video=false,$data=true){ //returns a JSON of content details of a post,photo,video, or any facebook link ; https://developers.facebook.com/docs/plugins/oembed-endpoints
if($video)$type='video';else $type='post';
$link='https://www.facebook.com/plugins/'.$type.'/oembed.json/?url='.urlencode($link);
if(!$data)return $link; else return @file_get_contents($link).PHP_EOL; //or use curl
}

function fb_bot($app,$v='2.6',$js=false){   //Send to messenger ; call fb_bot($app,$ver,true) once then call fb_id($app,$page) where u want to show this plugin
if($v===true){$v='2.6';$js=true;}
if(!$js)return '<div class="fb-send-to-messenger" messenger_app_id="'.$app.'" page_id="'.$v.'" data-ref="" color="blue" size="xlarge"> </div>';
return 'window.fbAsyncInit = function(){FB.init({ appId:"'.$app.'", xfbml : true, version:"v'.$v.'"});};'; //put inside <script>
}

function fb_pixel($id,$options=[],$ver='2.0'){  //https://developers.facebook.com/docs/ads-for-websites/pixel-events/v2.11
$x='!function(t,s){
  if(window.fbq)return;n=window.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!window._fbq)window._fbq=n;n.push=n;n.loaded=!0;n.version="'.$ver.'";
  n.queue=[];}();';

if(is_array($options)){
    $op='';
    $a=array('email','f_name','l_name','first name','last name','phone','gender','birthday','birthdate','city','state','zip','zip code');
    $b=array('em','fn','ln','fn','ln','ph','ge','gb','gb','ct','st','zp','zp');
    foreach($options as $k=>$v){
    $k=str_replace($a,$b,$k);
    if($k=='gb'&&is_numeric($v))$v=date('Ymd',$v); //timeStamp to yyyymmdd
    elseif($k=='ct')$v=str_replace(' ','',$v);
    elseif($k=='ge')$v=str_replace(array('male','female'),array('m','f'),$v);
    $op.=$k.':"'.strtolower($v).'",';
    }
}else $op=$options;
$x.=' fbq("init","'.$id.'",{'.$op.'});';
$x.='fbq("track","PageView");</script>';
return $x.'<noscript><img height="1" width="1" style="display:none"src="https://www.facebook.com/tr?id='.$id.'&ev=PageView&noscript=1"/></noscript>'.PHP_EOL;
}

############# /Facebook ######################


function share($link,$site='facebook',$extra=[]){
  $link=urlencode($link);
  if($site=='facebook')return 'https://www.facebook.com/sharer/sharer.php?u='.$link;
  if($site=='twitter')return 'https://twitter.com/home?status='.$link; //share text (not only link)
  if($site=='plus')return 'https://plus.google.com/share?url='.$link;
  if($site=='linkedin')return 'https://www.linkedin.com/shareArticle?mini=true&url='.$link.'&title='.$extra['title'].'&summary='.$extra['summary'].'&source='.$extra['source'];
  if($site=='pinterest')return 'https://pinterest.com/pin/create/button/?url='.$extra['source'].'&media='.$link.'&description='.$extra['description'];
  if($site=='email')return 'mailto:'.$link.'?&cc='.$extra['cc'].'&bcc='.$extra['bcc'].'&subject='.$extra['subject'].'&body='.$extra['body'];
}



}
return new social();
?>

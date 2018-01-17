<?php
class htaccess{
function __construct($path='',$base='/'){
$this->path=$path;
if(!empty($base)){
    //@unlink($path.'.htaccess'); //remove it manually
    $this->write('RewriteEngine On'.PHP_EOL.'RewriteBase '.$base);
    $this->RewriteEngine='on';
}


}

function write($data=''){ @file_put_contents($this->path.'.htaccess',$data.PHP_EOL,8);}
function read($echo=false){$data=@file_get_contents($this->path.'.htaccess');if($echo)echo str_replace(PHP_EOL,'<br />',$data);else return $data;}
function RewriteEngine(){if($this->RewriteEngine!='on'){$this->write('RewriteEngine On'.PHP_EOL.'RewriteBase /');$this->RewriteEngine='on';}}

function re(){
 //redirect non-www
 $this->RewriteEngine();
 $this->write('# Redirect non-www to www');
 $this->write('RewriteCond %{HTTP_HOST} "!^www\." [NC]');
 $this->write('RewriteCond %{HTTP_HOST} "!^localhost$" [NC]');
 $this->write('RewriteCond %{HTTPS} "on" [NC]');
 $this->write('RewriteRule "^/?(.*)" "https://www.%{HTTP_HOST}/$1" [R=301,L]');
 $this->write('RewriteCond %{HTTP_HOST} "!^www\." [NC]');
 $this->write('RewriteCond %{HTTP_HOST} "!^localhost$" [NC]');
 $this->write('RewriteRule "^/?(.*)" "http://www.%{HTTP_HOST}/$1" [R=301,L]');
}

function hotlink($files='jpg|jpeg|gif|png|bmp',$direct=true,$allowed=['facebook.com']){
  //disable hotlinking
 $this->write('#disable hotlinking');
 $this->write('RewriteCond %{HTTP_REFERER} !^$ ');
 if(!$direct)$this->write('RewriteCond expr "! %{HTTP_REFERER} -strmatch \'*://%{HTTP_HOST}/*\'"  [NC]');
 if(is_array($allowed)){foreach($allowed as $v)$this->write('RewriteCond %{HTTP_REFERER} !^.*://.*'.$v.'.*  [NC]');}
 $this->write('RewriteRule .*\.('.$files.')$ - [F,NC] ');
}



function cache($data=[]){  //nx: control $fileType & time
 $this->write('#Cache');
 $this->write('ExpiresActive On ');
 if(count($data)==0){
 $this->write('ExpiresByType image/gif "access 1 week"');
 $this->write('ExpiresByType image/jpg "access 1 week" ');
 $this->write('ExpiresByType image/jpeg "access 1 week"');
 $this->write('ExpiresByType image/png "access 1 week"');
 $this->write('ExpiresByType text/css "access 1 week" ');
 $this->write('ExpiresByType text/js "access 1 week" ');
 $this->write('ExpiresByType application/x-shockwave-flash "access 1 week"');
 $this->write('ExpiresByType application/x-javascript  "access 1 week"');
 }else{
 foreach($data as $v){
     if(empty($v[1]))$v[1]='1 week';
     if($v[0]=='photos'||$v['0']=='images'){
      $this->write('ExpiresByType image/gif "access '.$v[1].'"');
      $this->write('ExpiresByType image/jpg "access '.$v[1].'" ');
      $this->write('ExpiresByType image/jpeg "access '.$v[1].'"');
      $this->write('ExpiresByType image/png "access '.$v[1].'"');
     }elseif($v[0]=='gif')$this->write('ExpiresByType image/gif "access '.$v[1].'"');
     elseif($v[0]=='png')$this->write('ExpiresByType image/png "access '.$v[1].'"');
     elseif($v[0]=='jpg'||$v[0]=='jpeg'){$this->write('ExpiresByType image/jpg "access '.$v[1].'"');$this->write('ExpiresByType image/jpeg "access '.$v[1].'"');}
     elseif($v[0]=='css')$this->write('ExpiresByType text/css "access '.$v[1].'"');
     elseif($v[0]=='html')$this->write('ExpiresByType text/js "access '.$v[1].'"');
     elseif($v[0]=='flash')$this->write('ExpiresByType application/x-shockwave-flash "access '.$v[1].'"');
     else $this->write($v[0].' "access '.$v[1].'"');
 }

 }

 $this->write('Header set cache-control "max-age=2592000,public"');
}

function error($path='/404.php'){$this->write('ErrorDocument 404  '.$path);}

function auto(){
 @unlink($this->path.'.htaccess');
 $this->re();
 $this->cache();
 $this->error();
}

function AddType($type,$ext){
if(!empty($ext)&&$ext[0]!=='.')$ext='.'.$ext;
$this->write('AddType  '.$type.' '.$ext);

}

function  Rewrite($cond=[],$rule=[]){
 foreach($cond as $v){}
 foreach($rule as $v){}


}
}
?>

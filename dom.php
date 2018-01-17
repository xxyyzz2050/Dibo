<?php
class dom{  // extends DomDocument
function __construct($file=null,$encoding=false,$xml=false){
 //$xml :load() , loadxml() , savexml() , save()
 $this->error='';
 if($encoding=='utf')$encoding='UTF-8';elseif($encoding=='ar')$encoding='windows-1256';
 $this->encoding=$encoding;
 if($encoding)$this->dom=new DomDocument(null,$encoding);
 else $this->dom=new DomDocument;

  if($file){
   if(substr($file,0,4)=='http')@$this->dom->loadHTMLFile($file,8196); else @$this->dom->loadHTML($file,8196);
   /*
   the second parameter of loadHTML() and loadHTMLFIle() is to prevent saveHTML() from addind <!DOCTYPE .....
   LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD=8196
   check http://php.net/manual/en/libxml.constants.php for all options available

   */
 }
 }

function get($find,$el=false,$n=false){
 if(!$el)$el=$this->dom;
 if($find[0]=='#')return $el->getElementById(substr($find,1));
  elseif($find[0]=='.'){
  return $this->get('//*[@class="'.substr($find,1).'"]',$el,$n);
}elseif($find[0]==':'||substr($find,0,2)=='//'||substr($find,0,3)=='.//'){
 //xpath
 if(!$this->xpath)$this->xpath=new DOMXPath($this->dom);
 $r=$this->xpath->query($find,$el);   
 if($n===false)return $r; elseif($r)return $r->item($n);

}else{
   $r=$el->getElementsByTagName($find);
   if($n===false)return $r; else return $r->item($n);
 }
}
function getvalue($find,$el=false,$n=false,$txt=false,$decode=false){
$v=$this->get($find,$el=false,$n=false);
//############################################################if(typeof $v==DomNodeList) foreach($v)$value[]=$this->value($v);
if($this->encoding)return iconv('utf-8',$this->encoding,$this->value($v));
else return $this->value($v,$decode,$txt);
}
function save($file='',$node=NULL){   //nx: foreach($child)$html.=$this->save($child)
if(!is_string($file)){$node=$file;$file='';} //nx: if(file: nodeElement)
if(phpversion()>5.3){
    if(!empty($file))return $this->dom->saveHTMLFile($file); //saveHTMLFile($file,$node) ; nx: save a node
    else return $this->dom->saveHTML($node);
}else{
  if($node){
    $tmp=new DomDocument;
    $this->copy($node,$tmp);
    if($file)return $tmp->saveHTMLFile(); else return $tmp->saveHTML();
  } else if($file)return $this->dom->saveHTMLFile(); else return $this->dom->saveHTML();

  }
}
function html($file='',$node=NULL){return $this->save($file,$node);}
function copy($el,$doc=false){
if(!is_object($el))return;
  $copy=$el->cloneNode(true);
  if(!$doc)return $copy;
  $paste=$doc->importNode($copy,true);
  $doc->appendChild($paste);
}
function value($el,$txt=false,$decode=false){
if($txt)$v=$el->textContent;
elseif(phpversion()>5.3)$v=$this->dom->saveHTML($el);
else{
    $tmp=new DomDocument;
    $this->copy($el,$tmp);
    $v=$tmp->saveHTML();
    $v=html_entity_decode($v,ENT_QUOTES,'utf-8');
}
  if($decode)$v=utf8_decode($v);
  if($this->encoding)$v=iconv('UTF-8',$this->encoding,$v);
  return $v;
}

function create($el,$content=false,$attributes=false,$node=false){
  if(!$node)$node=$this->dom;
  $e=$node->createElement($el,$content);
  if(is_array($attributes)){
    foreach($attributes as $k=>$v){$node->setAttribute($k,$v);}
  }
  $node->appendChild($e);
}
function comment($comment){return $this->om->createComment($comment);}
function info(){
  //document info
  return array(
  'encodng'=>$this->dom->encoding,
  'type'=>$this->dom->doctype,
  'uri'=>$this->dom->documentURI,
  );
}
function title(){
  $title=$this->dom->getElementsByTagName('title')->item(0)->textContent;
  if($this->encoding)return iconv('UTF-8',$this->encoding,$title); else return $title;
  }

function remove($node){return $node->parentNode->removeChild($node);}
}

?>
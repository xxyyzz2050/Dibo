<?php
class db_Sqlite extends SQLite3{
public $pk;
function __construct($db='database.db',$create=true,$pk='_ID'){$this->db=$db;if(!$create&&!file_exists($db)){/*nx: make $this->error() returns No Database*/return;}$this->open($db);$this->pk=$pk;/*for Mysql if db not exists auto create db & select*/}
//function msg($q,$name='',$track=''){if($q)echo '<div class="ok">'.$name.': '.$track.' OK</div>';else echo '<div class="error">Error '.$name.' : '.$track.'<br />'.$this->error().'</div>';}
function index($table,$cols,$name='index',$unique=null){
    $q=@$this->query('CREATE '.(($unique)?'UNIQUE':'').' INDEX  '.$name.' on '.$table.' ('.$cols.')');
    return $q;
 }

 function create(){$this->open($this->db);}
function exists($tbl=null){if(!$tbl)return file_exists($this->db); else  return in_array($tbl,$this->tables());}
function table($name,$cols){
    //$this->exec('CREATE TABLE IF NOT EXISTS '.$name.' ('.str_replace(array('=t','=i','=n','=d'),array(' TEXT',' INTEGER',' NOT NULL',' DEFAULT '),$cols).');');
    //return $this->query('select 1 from '.$name.' limit 1');
    $q=@$this->query('CREATE TABLE IF NOT EXISTS '.$name.' ('.$this->convert_cols($cols).');');
    return $q;
}
function rename($table,$new){
    return $this->query('ALTER TABLE '.$table.' RENAME TO '.$new);
}
function insert($table,$data=[],$replace=true){
$d=$this->prepare($data);     //echo '===insert into '.$table.' ('.$d[0].') VALUES ('.$d[1].');===<br />';
$q=@$this->query('insert '.($replace?'or replace ':'').'into '.$table.' ('.$d[0].') VALUES ('.$d[1].');');
if($q)return $this->id(); else return false;
}

function prepare($data=[]){
$keys='';$values='';
foreach($data as $k=>$v){$keys.=$k.',';$values.=$this->value($v).',';}
return [rtrim($keys,','),rtrim($values,',')];
}
function select($table,$cols='',$cond='',$order='',$limit=''){
 if(is_numeric($cols)&&empty($cond))return $this->select($table,'rowid,*','rowid='.$cols,'',1); //if $cond : select 1 from $table where $cond ,else : select * from $table where rowid=$cols
 if(is_numeric($cond))return $this->select($table,$cols,'rowid='.$cond,'',1);
 if(is_numeric($order))return $this->select($table,$cols,$cond,'',$order);
 if(empty($cols))$cols='rowid,*';
 if(!empty($cond))$cond=' where '.$cond;
 if($order===1||$order===true)$order=' order by RANDOM() ';elseif(!empty($order))$order=' order by '.str_replace(array('=+','=-'),array(' ASC',' DESC'),$order);
 if(!empty($limit))$limit=' limit '.$limit;    //echo 'select '.$cols.' from '.$table.$cond.$order.$limit.';<hr />';
 return @$this->query('select '.$cols.' from '.$table.$cond.$order.$limit.';');    //dont return fetchArray($this->query() as it will make a continuous loop
}

function get($table,$cols='',$cond='',$order='',$limit=''){
$q=$this->select($table,$cols,$cond,$order,$limit);
if(!$q)return;
if(is_numeric($cond)||is_numeric($cols)||is_numeric($order)||$limit==1)return $q->fetchArray(1);
$data=array();
while($r=$q->fetchArray(1)){$data[]=$r;} //or $data[$r[rowid]]=$r
return $data;
}
//select1() & get1() deprecated 27.6.2017
function update($table,$data,$cond='',$limit=''){
   if(is_array($data)){
       $d='';
       foreach($data as $k=>$v){$d.=$k.'='.$this->value($v).',';}
       $data=rtrim($d,',');
   }
   $q='update '.$table.' set '.$data;
   if(is_numeric($cond))$cond='rowid='.$cond;
   if(!empty($cond))$q.=' where '.$cond;     //echo $q;
   //if(!empty($limit))$q.=' LIMIT '.$limit; //Must enable SQLITE_ENABLE_UPDATE_DELETE_LIMIT , https://www.sqlite.org/compile.html#enable_update_delete_limit
  $q=@$this->query($q);
  return $q;
  //if($q)return $this->id(); else return false;   // $this->id() available for insert() only
}

function delete($tbl,$cond='',$limit='',$drop=false){   //sqlite dosent support limit with delete &update
    if(is_numeric($cond))$cond='rowid='.$cond;
    if($cond===true||$limit===true||$drop)return $this->drop($tbl);
    return @$this->query('DELETE FROM '.$tbl.($cond?' WHERE '.$cond:''));
}
function del($tbl,$cond='',$limit='',$drop=false){return $this->delete($tbl,$cond,$limit,$drop);}
function addCols($table,$cols){
if(!is_array($cols))$cols=explode(',',$this->convert_cols($cols));
$r=[];
foreach($cols as $v){$x=$this->query('ALTER TABLE '.$table.' ADD COLUMN '.$v);$r[$v]=$x;}
return $r;
}
function removeCols($table,$cols=[]){
    if(!is_array($cols))$cols=explode(',',$cols);
    $all=$this->cols($table);
    $new=''; $get='';
    foreach($all as $k=>$v){
     if(in_array($k,$cols))continue;
     $new.=$k.' '.$v['type'];
     $get.=','.$k;
     if($v['notnull']==1)$new.=' NOT NULL';
     if(!empty($v['dflt_value']))$new.=' DEFAULT '.$v['dflt_value'];
     $new.=',';
    }
    $new=rtrim($new,',');

    while(true){
        $name=$table.'_'.rand().'_'.rand();
        if(!$this->exists($name))break;
    }
    $this->rename($table,$name);
    $c=$this->table($table,$new);
    if(!$c)return;
    $q=$this->query('select rowid,'.$get.' from '.$name);
    while($r=$q->fetchArray(1)){
        $this->insert($table,$r);
    }
    $this->drop($name);
   return $this->cols($table);

}
/*
function removeCols($table,$cols=[]){ //Sqlite not support drop coloumns 1-rename the current table (be sure the new name not existing $table_rand()) 2-get the current coloumns and remove the unwanted cols 3- create the new table and transfer data then drop the old one
    if(!is_array($cols))$cols=explode(',',$cols);
    $r=[];
    foreach($cols as $v){$x=$this->query('ALTER TABLE '.$table.' DROP COLUMN '.$v);$r[$v]=$x;}
    return $r;
}*/
function cols($table){
$q=@$this->query('PRAGMA table_info('.$table.');');
$cols=[];
while($r=$q->fetchArray(1)){$n=$r['name'];unset($r['name']);$cols[$n]=$r;}
return $cols;
}
function tables($full=false){
    $q=@$this->query('SELECT * FROM sqlite_master WHERE type="table";');   //echo $this->error().'<hr />';
    if(!$q)return; $data=[] ;
   while($r=$q->fetchArray(1)){if(!$full)$data[]=$r['name'];else $data[]=$r;}
   return $data;
}
function text($x,$trim=true){if($trim)$x=trim($x);return '\''.$this->escapeString($x).'\'';}
function trim($x){return '\''.$this->escapeString(trim($x)).'\'';} //deprecated as text() includes trim 9.11.2017
function num($x){if(empty($x))return 0;else return $x;}
function value($x){if(is_string($x))return $this->text($x);elseif(is_int($x))return $this->num($x);elseif(is_array($x))return $this->text(json_encode($x));elseif(is_null($x))return 'NULL';else return $x; }
function id(){return $this->lastInsertRowID();}
function error(){return $this->lastErrorMsg();}
private function convert_cols($cols){return str_replace(array('=t','=i','=n','=d'),array(' TEXT',' INTEGER',' NOT NULL',' DEFAULT '),$cols);}
function drop($tbl,$empty=false){if(!$empty)return @$this->query('drop table '.$tbl);else return $this->query('delete from '.$tbl);}
function max($table){
    $max=$this->select($table,'max(rowid)');
    if(!$max)return;
    $max=$max->fetchArray(2);
    return $max[0];
}

}
//$db=new dblite();
?>
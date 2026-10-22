<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
// Standalone regression tests: php tests/tracking-page.php (no DB/network required).
error_reporting(E_ALL & ~E_DEPRECATED);
function check($condition, $message) { if (!$condition) { throw new RuntimeException($message); } }
class ModuleFrontController { public $module; public $context; public function initContent() {} public function setTemplate($s) { return $s; } }
class Tools { public static $values = []; public static function getValue($key) { return self::$values[$key] ?? false; } }
class AgCorreiosTracking {
    public static function getFullTrackingEvents($code) { return [['desc'=>'Previously stored', 'created'=>'2026-09-20 10:00:00']]; }
    public static function normalizeTrackingEventsForDisplay(array $events) { foreach ($events as &$event) { $event['date_add']=$event['created']; } return $events; }
}
class DbQuery { public $clauses=[]; public function __call($name,$args) { $this->clauses[]=$args[0]; return $this; } }
function pSQL($value) { return addslashes((string)$value); }
class Db {
    public static $known = true; public static $persisted = false;
    public static function getInstance() { return new self; }
    public function executeS($q) { return self::$known ? [['id_agcorreios_tracking'=>7]] : []; }
    public function getValue($q) { return self::$persisted ? 1 : false; }
}
class AgCorreiosTrackingEvents { public static $saved=[]; public function save() { self::$saved[]=$this; return true; } }
class ApiStub { public $timeout=0; public function getRequestTimeout(){return $this->timeout;} public function setRequestTimeout($s){$this->timeout=$s;} }
class ModuleStub {
    public $options=[]; public $calls=0; public $fail=false; public $object; public $apis;
    public function __construct() { $this->apis=[new ApiStub,new ApiStub]; }
    public function getOptions(){return $this->options;}
    public function get($name){
        if (strpos($name,'Infrastructure')!==false) return strpos($name,'Autentica')!==false ? $this->apis[0] : $this->apis[1];
        if (substr($name,-13)==='Configuration') return new class { function getUsername(){return 'fake';} function getPassword(){return 'fake';} function getCartaoPostagem(){return 'fake';} };
        if (substr($name,-14)==='TokenRetriever') return new class { function exec(...$args){return 'fake-token';} };
        if (substr($name,-15)==='TrackingObjects') return new class($this) {
            private $m; function __construct($m){$this->m=$m;}
            function exec($objects,$token){
                $this->m->calls++;
                check(count($objects)===1 && $objects[0]->tracking_code==='AD953047640BR','Only requested code');
                check($this->m->apis[0]->timeout===15 && $this->m->apis[1]->timeout===15,'Bounded requests');
                if($this->m->fail)throw new RuntimeException('HTTP failure');
                return new class($this->m->object){private $o;function __construct($o){$this->o=$o;}function getObjetos(){return [$this->o];}};
            }
        };
        throw new RuntimeException('Unexpected service '.$name);
    }
}
$b=dirname(__DIR__);
require $b.'/src/Infrastructure/API/Remote/Rastro/Objetos/Objeto.php';
require $b.'/src/Infrastructure/API/Remote/Rastro/Objetos/Evento.php';
require $b.'/classes/AgCorreiosTrackingRefresh.php';
require $b.'/controllers/front/viewrastreio.php';
function page($module){
    $controller=new agcorreiosViewrastreioModuleFrontController;
    $controller->module=$module;
    $smarty=new class { public $assigned; function assign($v){$this->assigned=$v;} };
    $controller->context=(object)['smarty'=>$smarty];
    $controller->initContent(); return $smarty->assigned;
}
$m=new ModuleStub;
Tools::$values=['tracking_number'=>'AD953047640BR'];
$r=page($m); check($m->calls===0 && !$r['trackingRefreshFailed'] && $r['trackingEvents'][0]['desc']==='Previously stored','Missing option defaults to DB');
$m->options=['AGCORREIOS_TRACKING_REALTIME'=>'0'];page($m);check($m->calls===0,'Disabled makes no API requests');
$o=new AGTI\Correios\Infrastructure\API\Remote\Rastro\Objetos\Objeto;
$o->setCodObjeto('AD953047640BR');
$events=[];
foreach(['2026-09-23 09:55:00','2026-09-28 13:59:00'] as $date){
 $e=new AGTI\Correios\Infrastructure\API\Remote\Rastro\Objetos\Evento;
 $e->setCodigo('BDE')->setTipo('01')->setDescricao('Delivered')->setDtHrCriado(new DateTime($date));$events[]=$e;
}
$o->setEventos($events);$m->object=$o;$m->options=['AGCORREIOS_TRACKING_REALTIME'=>'1'];
$r=page($m);check($m->calls===1 && !$r['trackingRefreshFailed'] && count(AgCorreiosTrackingEvents::$saved)===2,'Refresh persists known tracking');
check($r['trackingEvents'][0]['date_add']==='2026-09-28 13:59:00','Newest event first');
check($m->apis[0]->timeout===0 && $m->apis[1]->timeout===0,'Timeout settings restored');
Db::$persisted=true;page($m);check(count(AgCorreiosTrackingEvents::$saved)===2,'Existing events not duplicated');
Db::$known=false;Db::$persisted=false;page($m);check(count(AgCorreiosTrackingEvents::$saved)===2,'Unknown code does not create tracking');
$o->setMensagem('SRO-009: Objeto não pertence ao contrato');
$r=page($m);check($r['trackingRefreshFailed'] && $r['trackingEvents'][0]['desc']==='Previously stored','SRO-009 preserves DB history');
$o->setMensagem(null);$o->setEventos([]);check(page($m)['trackingRefreshFailed'],'No events preserves history');
$m->fail=true;check(page($m)['trackingRefreshFailed'],'HTTP errors preserve history');
check($m->apis[0]->timeout===0,'Timeout restored after exception');
$before=$m->calls;Tools::$values=['objeto'=>'invalid&injection=1'];check(page($m)['trackingRefreshFailed'] && $m->calls===$before,'Invalid input never queries API');
Tools::$values=[];check(!page($m)['trackingRefreshFailed'] && $m->calls===$before,'Empty code makes no request');
echo "PASS: defaults, disabled, live lookup, persistence, ordering, deduplication, unknown tracking, SRO-009, empty events, HTTP failures, timeouts, invalid/empty input.\n";

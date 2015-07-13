<?php
require_once 'venders/limonade.php';
require_once 'cloudapiser/cloudapi.class.php';
require_once 'curl/curl.class.php';


$apptoken="a91r5k7jhq17dzykzu4i66z86m00q62da3s8meteyr637fq6618q20fo3jk9643cwu491w20p42bqy5txufyu76w";
$apiroot="http://58.185.157.58:8000";

function getsession_tag() {
  $json = file_get_contents("php://input");
  if(empty($json)) {
    return "";
  }
  $session = json_decode($json);
  if (!is_object($session) || !property_exists($session, "session")) {
    return "";
  }
  if (!is_object($session->session) || !property_exists($session->session, "tag")) {
    return "";
  }
  return $session->session->tag;
}
  

$cloudapi = new Cloudapi();
dispatch_post('/', 'entry');
function entry(){
    global $cloudapi;
	global $apptoken;
	global $apiroot;
	try {
		$cloudapiResult = new Result();  //电话后续控制交互
		select();
	} catch (Exception $e) {
		$cloudapiSession = new Session(); //电话第一次打入的session
		$cloudapi->cloudlogSer("qqgetsession_tag" . getsession_tag());
		$cloudapi->cloudlogSer("qqgetFrom" . $cloudapiSession->getTo()->id);
		if(getsession_tag() === "this_is_a_reject_and_callback_service") {
			menu();
		} elseif(getsession_tag() === "this_is_outcall_for_advertise") {
			$params = array('name'=>'advertise_playing','voice'=>'zh','timeout'=>30.0,'attempts'=>3,'mode'=>'dtmf','interdigitTimeout'=>5,'terminator'=>'#','choices'=>'[1-6 DIGITS]');
			$cloudapi->askSer("脑白金，最后5盒, 按1,预订",$params);
			$cloudapi->on(array('event'=>'continue','next'=>''));
			$cloudapi->on(array('event'=>'hangup','next'=>''));
			$cloudapi->renderJSON();
		} elseif($cloudapiSession->getTo()->id === "6531520067") {
			$UserNumber = $cloudapiSession->getFrom()->id;
			$curl = new mycurl($apiroot . "/v1/token_fire/out_call?app_number=" . $cloudapiSession->getTo()->id . "&call_to_number=" . $UserNumber . "&tag=this_is_a_reject_and_callback_service");
			$curl->setToken($apptoken);
			$curl->setContentType("application/json; charset=utf-8");
			$curl->createCurl();
			$cloudapiSession->reject();
			$cloudapi->renderJSON();
		} else {
			menu();
		}
	}   
}

function menu(){
	global $cloudapi;
	$cloudapi->cloudlogSer("this is cloudlog test");
  $params = array('name'=>'service','voice'=>'zh','timeout'=>30.0,'attempts'=>3,'mode'=>'dtmf','interdigitTimeout'=>5,'terminator'=>'#','choices'=>'[1-6 DIGITS]');
  $cloudapi->askSer("请按键选择测试功能,1:会议,2:呼叫转移,3:短信发送,4:录音,5:被动挂机,6:呼出电话并放广告,7:等待5秒后放音",$params);  //,
  $cloudapi->on(array('event'=>'continue','next'=>''));
	$cloudapi->on(array('event'=>'hangup','next'=>''));
	$cloudapi->renderJSON();  //生成控制命令
}

function select(){
	global $cloudapi;
	global $apptoken;
	global $apiroot;
	$cloudapiResult = new Result();
	$actions = $cloudapiResult->getActions();
	$replyInput = "*";
	if (!isset($actions)){
		menu();
	} else {
		foreach ($actions as $action){
			if($action->name === "service") {
				menu_main($action->value);
			}elseif($action->name === "sms") {
				$cloudapi->saySer("you sms have send",array('name'=>'smssay'));
				$cloudapi->on(array('event'=>'continue','next'=>''));
				$cloudapi->on(array('event'=>'hangup','next'=>''));
				$cloudapi->renderJSON();
			}elseif($action->name === "ask8") {
				$cloudapi->saySer("now you are in same file menu",array('name'=>'smssay'));
				$cloudapi->on(array('event'=>'continue','next'=>''));
				$cloudapi->on(array('event'=>'hangup','next'=>''));
				$cloudapi->renderJSON();
			}elseif($action->name === "recordby7") {
				$cloudapi->saySer("record file done",array('name'=>'smssay'));
				$cloudapi->on(array('event'=>'continue','next'=>''));
				$cloudapi->on(array('event'=>'hangup','next'=>''));
				$cloudapi->renderJSON();
			}elseif($action->name === "dtmftransfer") {
				$cloudapi->saySer("record file done",array('name'=>'smssay'));
				$cloudapi->on(array('event'=>'continue','next'=>''));
				$cloudapi->on(array('event'=>'hangup','next'=>''));
				$cloudapi->renderJSON();
			}elseif($action->name === "askconfnumber") {
				$cloudapi->saySer("�������������进入的会议号是：" . $action->value,array('voice'=>'zh'));
				$cloudapi->conferenceSer($action->value,array('name'=>'conference'));
				$cloudapi->on(array('event'=>'continue','next'=>''));
				$cloudapi->on(array('event'=>'hangup','next'=>''));
				$cloudapi->renderJSON();
			}elseif($action->name === "asktransfernumber") {
				$cloudapi->saySer("将为你呼叫转移到：" . $action->value,array('voice'=>'zh'));
				$cloudapi->transferSer("tel:" . $action->value,array('name'=>'dotransfer','early_media'=>'/root/ringmedia/apple.mp3','timeout'=>40.0));
				$cloudapi->on(array('event'=>'continue','next'=>''));
				$cloudapi->on(array('event'=>'hangup','next'=>''));
				$cloudapi->renderJSON();
			}elseif($action->name === "dotransfer") {
				$cloudapi->saySer("呼叫转移结束,回到主菜单",array('voice'=>'zh'));
				menu();
			}elseif($action->name === "askcallnumber") {
				$cloudapi->saySer("将呼出并播放广告到号码：" . $action->value,array('voice'=>'zh'));
				$cloudapi->callSer($action->value,array('from'=>"6531520093",'name'=>'docall','timeout'=>40.0,'say'=>"这是一段来自10086的中文广告",'voice'=>'zh'));
				$cloudapi->on(array('event'=>'continue','next'=>''));
				$cloudapi->on(array('event'=>'hangup','next'=>''));
                $cloudapi->renderJSON();
				
			//	$curl = new mycurl($apiroot . "/v1/token_fire/out_call?app_number=6531520069&call_to_number=" . $action->value . "&tag=this_is_outcall_for_advertise");
			//	$curl->setToken($apptoken);
			//	$curl->setContentType("application/json; charset=utf-8");
			//	$curl->createCurl();
			//	$cloudapi->cloudlogSer($curl->__tostring());
			//	$cloudapi->renderJSON();
			}elseif($action->name === "advertise_playing") {
				$cloudapi->saySer("正在转接销售人员",array('voice'=>'zh'));
				$cloudapi->transferSer("tel:861010086" . $action->value,array('name'=>'advertise_to_operator','timeout'=>40.0));
				$cloudapi->renderJSON();
			}elseif($action->name === "docall") {
				$cloudapi->saySer("广告播放结束,回��主菜单",array('voice'=>'zh'));
				menu();
			}elseif($action->name === "asksmsnumber") {
				$cloudapi->saySer("将发送短信到：" . $action->value, array('voice'=>'zh'));
				$cloudapi->messageSer("somebody try send sms to you with中文",array('name'=>'sms','to'=>$action->value,'from'=>'6582400886'));
				$cloudapi->on(array('event'=>'continue','next'=>''));
				$cloudapi->on(array('event'=>'hangup','next'=>''));
				$cloudapi->renderJSON();
			}elseif($action->name === "dorecord") {
				$cloudapi->saySer("录音结束,请到云主页查看录音文件，现在转回主菜单：" . $action->value, array('voice'=>'zh'));
				menu();
			}else {
				//menu();
			}
		}
	}

}

function menu_main($replyInput) {
	global $cloudapi;
		if ("#"===$replyInput){
		}elseif ("1"===$replyInput){
			$cloudapi->askSer("请输入3位会议号",array('name'=>'askconfnumber','voice'=>'zh','choices'=>'[3 DIGITS]'));
			$cloudapi->on(array('event'=>'continue','next'=>''));
			$cloudapi->on(array('event'=>'hangup','next'=>''));
			$cloudapi->renderJSON();
		}elseif ("2"===$replyInput){
			$cloudapi->askSer("��输����带国��������呼叫转移号码",array('name'=>'asktransfernumber','voice'=>'zh','choices'=>'[8-13 DIGITS]','timeout'=>30.0));
			$cloudapi->on(array('event'=>'continue','next'=>''));
			$cloudapi->on(array('event'=>'hangup','next'=>''));
			$cloudapi->renderJSON();
		}elseif ("3"===$replyInput){
			$cloudapi->askSer("请输入带国家码的短信接收号码",array('name'=>'asksmsnumber','voice'=>'zh','choices'=>'[8-13 DIGITS]','timeout'=>30.0));
			$cloudapi->on(array('event'=>'continue','next'=>''));
			$cloudapi->on(array('event'=>'hangup','next'=>''));
			$cloudapi->renderJSON();
		}elseif ("4"===$replyInput){
			$cloudapi->saySer("录音开始,按任意键结束录音" . $action->value, array('voice'=>'zh'));
			$cloudapi->recordSer("",array('name'=>'dorecord','to'=>'8613980616143','from'=>'6582400886'));
			$cloudapi->on(array('event'=>'continue','next'=>''));
			$cloudapi->on(array('event'=>'hangup','next'=>''));
			$cloudapi->renderJSON();
		}elseif ("5"===$replyInput){
			$cloudapi->saySer("将会挂掉���个电话", array('voice'=>'zh'));
			$cloudapi->hangupSer();
			$cloudapi->on(array('event'=>'continue','next'=>''));
			$cloudapi->on(array('event'=>'hangup','next'=>''));
			$cloudapi->renderJSON();
		}elseif ("6"===$replyInput){
			$cloudapi->askSer("请输入带国家码的呼出号码",array('name'=>'askcallnumber','voice'=>'zh','choices'=>'[8-13 DIGITS]','timeout'=>30.0));
			$cloudapi->on(array('event'=>'continue','next'=>''));
			$cloudapi->on(array('event'=>'hangup','next'=>''));
			$cloudapi->renderJSON();
		}elseif ("7"===$replyInput){
			$cloudapi->saySer("将静默5秒后，放音并挂掉", array('voice'=>'zh'));
			$cloudapi->waitSer(array('milliseconds'=>5000));
			$cloudapi->saySer("静默结束，将挂机", array('voice'=>'zh'));
			$cloudapi->renderJSON();
		}elseif ("8"===$replyInput){
			$cloudapi->askSer("test menu item in same file",array('name'=>'ask8','choices'=>'[1-6 DIGITS]'));
			$cloudapi->on(array('event'=>'continue','next'=>''));
			$cloudapi->on(array('event'=>'hangup','next'=>''));
			$cloudapi->renderJSON();
		}elseif ("0"===$replyInput){
			$cloudapi->askSer("按任意键跳转到全功能会议系统",array('voice'=>'zh','choices'=>'[1-1 DIGITS]','timeout'=>30.0));
			$cloudapi->on(array('event'=>'continue','next'=>'http://devcloudapi.gnum.com:8181/GRCloudAPIApp/conference/main'));
			$cloudapi->on(array('event'=>'hangup','next'=>''));
			$cloudapi->renderJSON();
		} else {
			//所有命令的例子
		//	$cloudapi->saySer("you sms have send",array('name'=>'askcallnumber','voice'=>'zh'));
		//	$cloudapi->waitSer(array('milliseconds'=>5000));
		//	$cloudapi->hangupSer();
		//	$cloudapi->rejectSer();
		//	$cloudapi->redirectSer();  //还有一些问题
		//	$cloudapi->recordSer(array('name'=>'recordby7','timeout'=>'300','max_length'=>'3600'));
		//	$cloudapi->transferSer("tel:8613980616143",array('name'=>'transfer','timeout'=>30.0));
		//	$cloudapi->conferenceSer("8888",array('name'=>'conference'));
		//	$cloudapi->askSer("Please select test number and terminate with #.",array('name'=>'service','voice'=>'zh','choices'=>'[1-6 DIGITS]','timeout'=>30.0,'attempts'=>3)); //time是放音开始就计时, choices是必填字段
		//	$cloudapi->cloudlogSer("this is cloudlog test");
		//	$cloudapi->callSer("tel:8613980616143",array('name'=>'call'));
		//	$cloudapi->on(array('event'=>'continue','next'=>'/other.php'));
		//	$cloudapi->on(array('event'=>'continue','next'=>''));  //next = '' mean still use self
		}
}

run();
?>

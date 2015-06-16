<?php
cloudlog("test is starting ....");

function entry(){
	cloudlog("in the entry");
	
	$text="请按键选择测试功能,向外呼出电话并放广告,请按1;短信发送,请按2;录音,请按3;会议,请按4;播放音频,请按5;呼叫转移,请按6;令人工服务请按0";
	$params = array("voice"=>"zh","timeout"=>60,"attempts"=>3,"mode"=>"dtmf","interdigitTimeout"=>5,"terminator"=>"#","choices" =>"[1 DIGITS]"," bargein"=>"true");
	$result = ask($text,$params);
	$value=$result->value;
	cloudlog("the first enter value is ".$value);
	
	if($value == 1)//呼出电话并播放广告
	{
		cloudlog("1----call");
		
		$res = ask("请输入带国家码的呼出号码", array("voice"=>"zh","choices"=>"[8-13 DIGITS]","timeout"=>30, "attempts"=>3, "terminator"=>"#", "bargein"=>"true"));
		$params = array("value"=>"这是一段来自10086的中文广告","voice"=>"zh","timeout"=>30,"callerID"=>"10086","onBusy"=>"isBusy","onCallFailure"=>"isFailure","onError"=>"isError","onTimeout"=>"isTimeout");
		
		cloudlog("1----:the input telnumber is ".$res->value);
		
		call("tel:".$res->value,$params);
		
		cloudlog("1----call end");
	}
	elseif($value == 2)//短信发送message
	{
		cloudlog("2----message");
		
		$params = array("voice"=>"zh","timeout"=>60,"attempts"=>3,"mode"=>"dtmf","terminator"=>"#","choices" =>"[13 DIGITS]"," bargein"=>"true");
		$res = ask("请输入带国家码的短信接收的号码，按#号键结束",$params);

		cloudlog("2----message:the input telnumber is ".$value);
		
		message("somebody sends one message to you ",array("to"=>$res->value,"network"=>"SMS","callerID"=>"6582400886"));
		
		cloudlog("2----message end");
	}
	elseif($value == 3)//录音record
	{
		cloudlog("3----record");
		
		$params=array("silenceTimeout"=>10,"maxTime"=>60,"terminator"=>"#","attempts"=>1,"bargein"=>"true","beep"=>"true","timeout"=>15,"voice"=>"en","onError"=>"isError","onEvent"=>"isEvent","onHangup"=>"isHangup","onTimeout"=>"isTimeout");
		record("recording will start",$params);

		cloudlog("3----record end");		
	}
	elseif($value == 4)//会议conference
	{
		cloudlog("4----conference");
		
		$res=ask("please three numbers as the room ,exit the conference;",array("bargein"=>"true","choices"=>"[3 DIGITS]","interdigitTimeout"=>5,"attempts"=>2,"mode"=>"dtmf"));
		$value=$res->value;
		
		cloudlog("4----conference:the input number is ".$value);
		
		$params=array("terminator"=>"*","joinPrompt"=>"true","leavePrompt"=>"true","onError"=>"isError","onChoice"=>"conChoice","onTimeout"=>"isTimeout","onHangup"=>"isHangup");
		conference($value,$params);
		
		cloudlog("4----conference end");
	}
	elseif($value == 5)//等待5秒后放音say,wait,hangup
	{
		cloudlog("5----wait,say");
		
		say("wait for 5 seconds");
		wait(5000);
		cloudlog("after the wait...and  say start");
		say("Thank you for calling in ,you can call in once again.");
		hangup();
		
		cloudlog("5----wait,say end");
	}
	elseif($value == 6)//呼叫转移
	{
		cloudlog("6----transfer");
		
		$res = ask("请输入带国家码的呼叫转移号码", array("voice"=>"zh","choices"=>"[8-13 DIGITS]","timeout"=>30, "attempts"=>3, "terminator"=>"#", "bargein"=>"true"));
		$params=array('timeout'=>30,"onTimeout"=>"isTimeout","onCallFailure"=>"isCallFailure","onError"=>"isError","onSuccess"=>"isSuccess");
		transfer("tel:".$res->value,$params);
		
		cloudlog("6----transfer end");
	}
	elseif($value == 0)//人工服务
	{
		cloudlog("6----manual work");
		
		$params=array('timeout'=>30,"onTimeout"=>"isTimeout","onCallFailure"=>"isCallFailure","onError"=>"isError","onSuccess"=>"isSuccess");
		transfer("sip:maji@caas.grcaassip.com",$params);
		
		cloudlog("6----manual work end");
	}
	else
	{
		say("sorry,the number you entered is false");	
	}
}

function conChoice($event)
{
	cloudlog("in the conChoice,the event is ".json_encode($event));
	say("you exit the conference.");
}
function isError($event)
{
	cloudlog("in the isError,the event is ".json_encode($event));
	say("the system is error ,please try it again later.");
}
function isBusy($event)
{
	cloudlog("in the isBusy,the event is ".json_encode($event));
	say("the user is busy,please try it again later.");
}
function isFailure($event)
{
	cloudlog("in the isFailure,the event is ".json_encode($event));
	say("the call is Failure,please try it again later.");
}
function isTimeout($event)
{
	cloudlog("in the isTimeout,the event is ".json_encode($event));
	say("the call isTimeout ,please try it again later.");
}
function isHangup($event)
{
	cloudlog("in the isHangup,the event is ".json_encode($event));
	say("somebody has hangup,and exit.");
}
function isEvent($event)
{
	cloudlog("in the isEvent,the event is ".json_encode($event));
	say("a event fires...");
}
function isCallFailure($event)
{
	cloudlog("in the isCallFailure,the event is ".json_encode($event));
	say("the call is failure.");
}
function isSuccess($event)
{
	cloudlog("in the isSuccess,the event is ".json_encode($event));
	say("call is success");
}
function isBadeChoice($event)
{
	cloudlog("in the isBadeChoice,the event is ".json_encode($event));
	say("is badeChoice...");
}
function isChoice($event)
{
	cloudlog("in the isChoice,the event is ".json_encode($event));
	say("is isChoice...");
}
function isConnect($event)
{
	cloudlog("in the isConnect ,the event is ".json_encode($event));
	say("the call is connect ,please hold on");
}

do
{
	$exit=true;
	entry();
	
	$params = array("voice"=>"zh","timeout"=>10.0,"attempts"=>3,"mode"=>"dtmf","interdigitTimeout"=>5,"terminator"=>"#","choices" =>"[1 DIGITS]"," bargein"=>"true");
	$result=ask("回到主菜单,请按1",$params);
	if($result->value == 1 )
	{
		$exit=false;
	}
	else
	{
		say("sorry ,the number you enter is false.Thank for your calling,see you");
		hangup();
	}
}while($exit)

cloudlog("end....");
?>
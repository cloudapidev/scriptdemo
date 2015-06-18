function entry(){
	cloudlog("in the entry");
	
	var text="请按键选择测试功能,向外呼出电话并放广告,请按1;短信发送,请按2;录音,请按3;会议,请按4;播放音频,请按5;呼叫转移,请按6;令人工服务请按0";
	var result = ask(text,{voice:"zh",timeout:60,attempts:3,mode:"dtmf",interdigitTimeout:5,terminator:"#",choices:"[1 DIGITS]",bargein:"true"});
	var value= result.value;
	cloudlog("the first enter value is " + value.toString());
	
	if(value == 1)//呼出电话并播放广告
	{
		cloudlog("1----call");
		
		var res = ask("请输入带国家码的呼出号码", {"voice":"zh","choices":"[8-13 DIGITS]","timeout":30, "attempts":3, "terminator":"#", "bargein":"true"});
		var params = {value:"这是一段来自10086的中文广告",voice:"zh",timeout:30,callerID:"10086",onBusy:isBusy,onCallFailure:isFailure,onError:isError,onTimeout:isTimeout};
		
		cloudlog("1----:the input telnumber is " + res.value.toString());
		
		call("tel:" + res.value.toString(),params);
		
		cloudlog("1----call end");
	}
	else if(value == 2)//短信发送message
	{
		cloudlog("2----message");
		
		var params = {voice:"zh",timeout:60,attempts:3,mode:"dtmf",terminator:"#",choices:"[13 DIGITS]",bargein:"true"};
		var res = ask("请输入带国家码的短信接收的号码，按#号键结束",params);

		cloudlog("2----message:the input telnumber is " + value.toString());
		
		message("somebody sends one message to you ",{to:res.value.toString(),network:"SMS",callerID:"6582400886"});
		
		cloudlog("2----message end");
	}
	else if($value == 3)//录音record
	{
		cloudlog("3----record");
		
		var params = {silenceTimeout:10,maxTime:60,terminator:"#",attempts:1,bargein:"true",beep:"true",timeout:15,voice:"en",onError:isError,onEvent:isEvent,onHangup:isHangup,onTimeout:isTimeout};
		record("recording will start",params);

		cloudlog("3----record end");		
	}
	else if($value == 4)//会议conference
	{
		cloudlog("4----conference");
		
		var res = ask("please three numbers as the room ,exit the conference;",{bargein:"true",choices:"[3 DIGITS]",interdigitTimeout:5,attempts:2,mode:"dtmf"});
		var value = res.value;
		
		cloudlog("4----conference:the input number is " + value.toString());
		
		var params = {terminator:"*",joinPrompt:"true",leavePrompt:"true",onError:isError,onChoice:conChoice,onTimeout:isTimeout,onHangup:isHangup};
		conference(value,params);
		
		cloudlog("4----conference end");
	}
	else if($value == 5)//等待5秒后放音say,wait,hangup
	{
		cloudlog("5----wait,say");
		
		say("wait for 5 seconds");
		wait(5000);
		cloudlog("after the wait...and  say start");
		say("Thank you for calling in ,you can call in once again.");
		hangup();
		
		cloudlog("5----wait,say end");
	}
	else if($value == 6)//呼叫转移
	{
		cloudlog("6----transfer");
		
		var res = ask("请输入带国家码的呼叫转移号码", {voice:"zh",choices:"[8-13 DIGITS]",timeout:30,attempts:3,terminator:"#",bargein:"true"});
		var params = {timeout:30,onTimeout:isTimeout,onCallFailure:isCallFailure,onError:isError,onSuccess:isSuccess};
		transfer("tel:" + res.value.toString(),params);
		
		cloudlog("6----transfer end");
	}
	else if($value == 0)//人工服务
	{
		cloudlog("6----manual work");
		
		var params = {timeout:30,onTimeout:isTimeout,onCallFailure:isCallFailure,onError:isError,onSuccess:isSuccess};
		transfer("sip:maji@caas.grcaassip.com",params);
		
		cloudlog("6----manual work end");
	}
	else
	{
		say("sorry,the number you entered is false");	
	}
}

conChoice = function(event)
{
	cloudlog("in the conChoice");
	say("you exit the conference.");
}
isError = function(event)
{
	cloudlog("in the isError");
	say("the system is error ,please try it again later.");
}
isBusy = function(event)
{
	cloudlog("in the isBusy");
	say("the user is busy,please try it again later.");
}
function isFailure(event)
{
	cloudlog("in the isFailure");
	say("the call is Failure,please try it again later.");
}
isTimeout = function(event)
{
	cloudlog("in the isTimeout");
	say("the call isTimeout ,please try it again later.");
}
isHangup = function(event)
{
	cloudlog("in the isHangup");
	say("somebody has hangup,and exit.");
}
isEvent = function(event)
{
	cloudlog("in the isEvent");
	say("a event fires...");
}
isCallFailure = function(event)
{
	cloudlog("in the isCallFailure");
	say("the call is failure.");
}
isSuccess = function(event)
{
	cloudlog("in the isSuccess");
	say("call is success");
}
isBadeChoice = function(event)
{
	cloudlog("in the isBadeChoice");
	say("is badeChoice...");
}
isChoice = function(event)
{
	cloudlog("in the isChoice");
	say("is isChoice...");
}
isConnect = function(event)
{
	cloudlog("in the isConnect");
	say("the call is connect ,please hold on");
}

do
{
	var flag=false;
	entry();
	
	var params = {"voice":"zh","timeout":10.0,"attempts":3,"mode":"dtmf","interdigitTimeout":5,"terminator":"#","choices" :"[1 DIGITS]"," bargein":"true"};
	var result=ask("回到主菜单,请按1",params);
	if(result.value == 1 )
	{
		flag=true;
	}
	else
	{
		say("sorry ,the number you enter is false.Thank for your calling,see you");
		hangup();
	}
}while(flag);

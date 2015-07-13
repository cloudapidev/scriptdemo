<?php
/**
* ivr menu:
*   1: call out and play a advertise  
*   2: send sms message 				  
*   3: record voice						     
*   4: join conference 			    
*   5: wait for 5s and say			 
*   6: transfer	to pstn number				     		     
*/

function entry(){
    _log("in the entry");    
	$text="Welcome to Tela. calling out and playing a advertise,press one ;sending message ,press two;recording,press three;joining conference ,press four;playing a audio,press five;transfering,press six";
	$params = array("voice"=>"en","timeout"=>60,"attempts"=>3,"mode"=>"dtmf","interdigitTimeout"=>5,"terminator"=>"#","choices" =>"[1 DIGITS]"," bargein"=>"true","onBadChoice"=>"onBadChoice");
	$result = ask($text,$params);
	$value=$result->value;
	_log("the first input value is ".$value);
	
	if($value == 1)//calling out and playing a advertise
	{
		_log("in the calling");		
		$res = ask("Please enter the calling number with country code", array("voice"=>"en","choices"=>"[8-13 DIGITS]","timeout"=>30, "attempts"=>3, "terminator"=>"#", "bargein"=>"true","onBadChoice"=>"onBadChoice"));
		$params = array("value"=>"This is a Chinese advertising from 10086","voice"=>"en","timeout"=>30,"callerID"=>"10086","onBusy"=>"isBusy","onCallFailure"=>"isFailure","onError"=>"isError","onTimeout"=>"isTimeout");
		
		_log("the input telnumber is ".$res->value);
		
		call("tel:+".$res->value,$params);
		
		_log("calling  ends....");
	}
	elseif($value == 2)//send message
	{
		_log("in the message");		
		$params = array("voice"=>"en","timeout"=>60,"attempts"=>3,"mode"=>"dtmf","terminator"=>"#","choices" =>"[13 DIGITS]"," bargein"=>"true","onBadChoice"=>"onBadChoice");
		$res = ask("Please enter the phone number which receives the message with country code, press the # key to end",$params);
		_log("the input telnumber is ".$res->value);		
		message("somebody sends one message to you ",array("to"=>$res->value,"network"=>"SMS","callerID"=>"6582400886"));		
		_log("sending message ends");
	}
	elseif($value == 3)//record
	{
		_log("int the record");
		
		$params=array("silenceTimeout"=>10,"maxTime"=>60,"terminator"=>"#","attempts"=>1,"bargein"=>"true","beep"=>"true","timeout"=>15,"voice"=>"en","onError"=>"isError","onEvent"=>"isEvent","onHangup"=>"isHangup","onTimeout"=>"isTimeout");
		record("recording will start",$params);
		_log("record ends");		
	}
	elseif($value == 4)//conference
	{
		_log("in the conference");
		
		$res=ask("Please enter the three-digit room number, press star to exit conference room",array("bargein"=>"true","choices"=>"[3 DIGITS]","interdigitTimeout"=>5,"attempts"=>2,"mode"=>"dtmf","onBadChoice"=>"onBadChoice"));
		$value=$res->value;		
		_log("the input number is ".$value);
		if(is_numeric($value) && $value > 99 && $value < 1000)
		{
			say("you will enter in conference room ".$value);
			$params=array("terminator"=>"*","joinPrompt"=>"true","leavePrompt"=>"true","onError"=>"isError","onChoice"=>"conChoice","onTimeout"=>"isTimeout","onHangup"=>"isHangup","playTones"=true);
			conference($value,$params);			
			_log("conference ends");			
		}
		else
		{
			say("Sorry, the  room number you entered is  wrong ");
		}
	}
	elseif($value == 5)//wait for 5s and say
	{
		_log("int the wait");
		
		say(" playing a audio after waiting for five second, and then hanging up");
		wait(5000);
		_log("after the wait...and  say start");
		say("Thank you for calling, please call again, goodbye");
		hangup();		
		_log("wait and say end");
	}
	elseif($value == 6)//transfer
	{
		_log("6----transfer");
		
		$res = ask("Please enter the transfering phone number with country code", array("voice"=>"en","choices"=>"[8-13 DIGITS]","timeout"=>30, "attempts"=>3, "terminator"=>"#", "bargein"=>"true","onBadChoice"=>"onBadChoice"));
		$params=array('timeout'=>30,"onTimeout"=>"isTimeout","onCallFailure"=>"isCallFailure","onError"=>"isError","onSuccess"=>"isSuccess","onConnect"=>"screen");
		say("you will be transfer to number ".$value);
		transfer("tel:".$res->value,$params);		
		_log("transfering  ends");
	}
	else
	{
		say("sorry,the number you entered is incorrect");	
	}
}

function screen($event)
{
	$result = ask("Press 1 to accept the call, press any other key to reject.", array(
	        "choices" => "1",
	        "mode" => "dtmf"
	    ));
	    if ($result->name == "choice") {
	        say("Connecting you now.");
	    } else {
	        say("Rejecting the call. Goodbye.");
	        hangup();
	    }
}

function onBadChoice($event)
{
	_log("in the onBadChoice");
	say("you do not provid a valid response");
}
function conChoice($event)
{
	_log("in the conChoice");
	say("you exit the conference.");
}
function isError($event)
{
	_log("in the isError");
	say("the system is error ,please try it again later.");
}
function isBusy($event)
{
	_log("in the isBusy");
	say("the user is busy,please try it again later.");
}
function isFailure($event)
{
	_log("in the isFailure");
	say("the call is Failure,please try it again later.");
}
function isTimeout($event)
{
	_log("in the isTimeout");
	say("the call isTimeout ,please try it again later.");
}
function isHangup($event)
{
	_log("in the isHangup");
	say("somebody has hangup,and exit.");
}
function isEvent($event)
{
	_log("in the isEvent");
	say("a event fires...");
}
function isCallFailure($event)
{
	_log("in the isCallFailure");
	say("the call is failure.");
}
function isSuccess($event)
{
	_log("in the isSuccess");
	say("call is success");
}
function isBadeChoice($event)
{
	_log("in the isBadeChoice");
	say("is badeChoice...");
}
function isChoice($event)
{
	_log("in the isChoice");
	say("is isChoice...");
}
function isConnect($event)
{
	_log("in the isConnect");
	say("the call is connect ,please hold on");
}

_log("test is starting ....");
do
{
	$flag=false;
	entry();	
	$params = array("voice"=>"en","timeout"=>30,"attempts"=>3,"mode"=>"dtmf","interdigitTimeout"=>5,"terminator"=>"#","choices" =>"[1 DIGITS]"," bargein"=>"true");
	$result = ask("Return to the main menu,please press one",$params);
    _log("Return to the  main menu,the input is :".$result->value);
	if($result->value == 1 )
	{
		$flag=true;
	}
	else
	{
		say("sorry ,the number you entered is incorrect.Thank for calling,Bye");
		hangup();
	}
}while($flag)
_log("test has ended....");
?>
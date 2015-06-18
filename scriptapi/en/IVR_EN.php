<?php
/**
call out and play a advertise   -----press one
send message 				    -----press two
record 						    -----press three
join conference 			    -----press four
wait for 5s and say			    -----press five
transfer					    -----press six
manual work 				    -----press zero
*/

function entry(){
    cloudlog("in the entry");    
	$text="Please choose number to test the function:calling out and playing a advertise,press one ;sending message ,press two;recording,press three;joining conference ,press four;playing a audio,press five;transfering,press six;Artificial service,press zero";
	$params = array("voice"=>"en","timeout"=>60,"attempts"=>3,"mode"=>"dtmf","interdigitTimeout"=>5,"terminator"=>"#","choices" =>"[1 DIGITS]"," bargein"=>"true");
	$result = ask($text,$params);
	$value=$result->value;
	cloudlog("the first input value is ".$value);
	
	if($value == 1)//calling out and playing a advertise
	{
		cloudlog("in the calling");		
		$res = ask("Please enter the target phone  number with country code", array("voice"=>"en","choices"=>"[8-13 DIGITS]","timeout"=>30, "attempts"=>3, "terminator"=>"#", "bargein"=>"true"));
		$params = array("value"=>"This is a Chinese advertising from 10086","voice"=>"en","timeout"=>30,"callerID"=>"10086","onBusy"=>"isBusy","onCallFailure"=>"isFailure","onError"=>"isError","onTimeout"=>"isTimeout");
		
		cloudlog("the input telnumber is ".$res->value);
		
		call("tel:".$res->value,$params);
		
		cloudlog("calling  ends....");
	}
	elseif($value == 2)//send message
	{
		cloudlog("in the message");		
		$params = array("voice"=>"en","timeout"=>60,"attempts"=>3,"mode"=>"dtmf","terminator"=>"#","choices" =>"[13 DIGITS]"," bargein"=>"true");
		$res = ask("Please enter the phone number which receives the message  with country code, press the # key to end",$params);
		cloudlog("the input telnumber is ".$res->value);		
		message("somebody sends one message to you ",array("to"=>$res->value,"network"=>"SMS","callerID"=>"6582400886"));		
		cloudlog("sending message ends");
	}
	elseif($value == 3)//record
	{
		cloudlog("int the record");
		
		$params=array("silenceTimeout"=>10,"maxTime"=>60,"terminator"=>"#","attempts"=>1,"bargein"=>"true","beep"=>"true","timeout"=>15,"voice"=>"en","onError"=>"isError","onEvent"=>"isEvent","onHangup"=>"isHangup","onTimeout"=>"isTimeout");
		record("recording will start",$params);
		cloudlog("record ends");		
	}
	elseif($value == 4)//conference
	{
		cloudlog("in the conference");
		
		$res=ask("Please enter the three-digit room number, exit the conference please press *",array("bargein"=>"true","choices"=>"[3 DIGITS]","interdigitTimeout"=>5,"attempts"=>2,"mode"=>"dtmf"));
		$value=$res->value;		
		cloudlog("the input number is ".$value);
		if(is_numeric($value) &&¡¡$value > 99 && $value < 1000)
		{
			$params=array("terminator"=>"*","joinPrompt"=>"true","leavePrompt"=>"true","onError"=>"isError","onChoice"=>"conChoice","onTimeout"=>"isTimeout","onHangup"=>"isHangup");
			conference($value,$params);			
			cloudlog("conference ends");			
		}
		else
		{
			say("Sorry, the wrong room number you entered is  wrong ");
		}
	}
	elseif($value == 5)//wait for 5s and say
	{
		cloudlog("int the wait");
		
		say(" playing a audio after waiting for 5s, and then hanging up");
		wait(5000);
		cloudlog("after the wait...and  say start");
		say("Thank you for calling, please call again, goodbye");
		hangup();		
		cloudlog("wait and say end");
	}
	elseif($value == 6)//transfer
	{
		cloudlog("6----transfer");
		
		$res = ask("Please enter the transfering phone number with country code", array("voice"=>"en","choices"=>"[8-13 DIGITS]","timeout"=>30, "attempts"=>3, "terminator"=>"#", "bargein"=>"true"));
		$params=array('timeout'=>30,"onTimeout"=>"isTimeout","onCallFailure"=>"isCallFailure","onError"=>"isError","onSuccess"=>"isSuccess");
		transfer("tel:".$res->value,$params);		
		cloudlog("transfering  ends");
	}
	elseif($value == 0)//manual work
	{
		cloudlog("manual work");
		
		$params=array('timeout'=>30,"onTimeout"=>"isTimeout","onCallFailure"=>"isCallFailure","onError"=>"isError","onSuccess"=>"isSuccess");
		transfer("sip:maji@caas.grcaassip.com",$params);		
		cloudlog("manual work ends");
	}
	else
	{
		say("sorry,the number you entered is incorrect");	
	}
}
function conChoice($event)
{
	cloudlog("in the conChoice");
	say("you exit the conference.");
}
function isError($event)
{
	cloudlog("in the isError");
	say("the system is error ,please try it again later.");
}
function isBusy($event)
{
	cloudlog("in the isBusy");
	say("the user is busy,please try it again later.");
}
function isFailure($event)
{
	cloudlog("in the isFailure");
	say("the call is Failure,please try it again later.");
}
function isTimeout($event)
{
	cloudlog("in the isTimeout");
	say("the call isTimeout ,please try it again later.");
}
function isHangup($event)
{
	cloudlog("in the isHangup");
	say("somebody has hangup,and exit.");
}
function isEvent($event)
{
	cloudlog("in the isEvent");
	say("a event fires...");
}
function isCallFailure($event)
{
	cloudlog("in the isCallFailure");
	say("the call is failure.");
}
function isSuccess($event)
{
	cloudlog("in the isSuccess");
	say("call is success");
}
function isBadeChoice($event)
{
	cloudlog("in the isBadeChoice");
	say("is badeChoice...");
}
function isChoice($event)
{
	cloudlog("in the isChoice");
	say("is isChoice...");
}
function isConnect($event)
{
	cloudlog("in the isConnect");
	say("the call is connect ,please hold on");
}

cloudlog("test is starting ....");
do
{
	$flag=false;
	entry();	
	$params = array("voice"=>"en","timeout"=>30,"attempts"=>3,"mode"=>"dtmf","interdigitTimeout"=>5,"terminator"=>"#","choices" =>"[1 DIGITS]"," bargein"=>"true");
	$result = ask("Return to the main menu,please press one",$params);
    cloudlog("Return to the  main menu,the input is :".$result->value);
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
cloudlog("test has ended....");
?>
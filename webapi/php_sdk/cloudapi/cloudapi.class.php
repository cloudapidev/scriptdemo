<?php
include 'cloudapi-rest.class.php';

class Cloudapi extends BaseClass {
  public $Cloudapi;
  private $_voice;
  private $_language;

  public function __construct() {
    $this->Cloudapi = array();
  }

  public function setVoice($voice) {
    $this->_voice = $voice;
  }
  public function setLanguage($language) {
    $this->_language = $language;
  }

  public function ask($ask, Array $params=NULL) {
    if(!is_object($ask)) {
      $p = array('terminator', 'as', 'event', 'voice', 'attempts', 'bargein', 'minConfidence', 'name', 'required', 'timeout', 'allowSignals', 'recognizer', 'interdigitTimeout', 'sensitivity', 'speechCompleteTimeout', 'speechIncompleteTimeout','mode');
      foreach ($p as $option) {
        $$option = null;
        if (is_array($params) && array_key_exists($option, $params)) {
          $$option = $params[$option];
        }
      }
      $say[] = new Say($ask, $as, null, $voice, null, null);
      if (!isset($voice) && isset($this->_voice)) {
        $voice = $this->_voice;
      }
      $choices = isset($params["choices"]) ? new Choices($params["choices"], $mode, $terminator, $interdigitTimeout) : null;
      $ask = new Ask($attempts, $bargein, $choices, $minConfidence, $name, $required, $say, $timeout, $allowSignals, $recognizer, $sensitivity, $speechCompleteTimeout, $speechIncompleteTimeout);
    }
    $this->ask = sprintf('%s', $ask);
  }

  public function call($call, Array $params=NULL) {
    if(!is_object($call)) {
      $p = array('to', 'from', 'network', 'channel', 'answerOnMedia', 'timeout', 'headers', 'recording', 'allowSignals', 'machineDetection', 'voice','name','attempts','say');
      foreach ($p as $option) {
        $$option = null;
        if (is_array($params) && array_key_exists($option, $params)) {
          $$option = $params[$option];
        }
      }
      $say = new Say($say, null, null, $voice, null, $attempts);
      $call = new Call($call, $from, $network, $channel, $answerOnMedia, $timeout, $headers, $recording, $allowSignals, $machineDetection, null , $name, $say);
    }
    $this->call = sprintf('%s', $call);
  }

  public function conference($conference, Array $params=NULL) {
    if(!is_object($conference)) {
      $p = array('name', 'id', 'mute', 'on', 'playTones', 'required', 'terminator', 'allowSignals', 'interdigitTimeout', 'joinPrompt', 'leavePrompt', 'voice');
      foreach ($p as $option) {
        $$option = null;
        if (is_array($params) && array_key_exists($option, $params)) {
          $$option = $params[$option];
        }
      }
      $id = (empty($id) && !empty($conference)) ? $conference : $id;
      $name = (empty($name)) ? (string)$id : $name;
      @$choices = new Choices((isset($params["choices"]) ? $params["choices"] : null), $mode, $terminator);
      $conference = new Conference($name, $id, $mute, $on, $playTones, $required, $terminator, $allowSignals, $interdigitTimeout, $joinPrompt, $leavePrompt, $voice, $choices);
    }
    $this->conference = sprintf('%s', $conference);
  }

  public function hangup() {
    $hangup = new Hangup();
    $this->hangup = sprintf('%s', $hangup);
  }

  public function message($message, Array $params=null) {
    if(!is_object($message)) {
      $say = new Say($message);
      $to = $params["to"];
      $p = array('channel', 'network', 'from', 'voice', 'timeout', 'answerOnMedia','headers','name');
      foreach ($p as $option) {
        $$option = null;
        if (is_array($params) && array_key_exists($option, $params)) {
          $$option = $params[$option];
        }
      }
      $message = new Message($say, $to, $channel, $network, $from, $voice, $timeout, $answerOnMedia, $headers, $name);
    }
    $this->message = sprintf('%s', $message);
  }

  public function on($on) {
    if (!is_object($on) && is_array($on))	{
      $params = $on;
      $next = (array_key_exists('next', $params)) ? $params["next"] : null;
      $on = new On($params["event"], $next);
    }
    $this->on = array(sprintf('%s', $on));
  }

  public function record($record) {
    if(!is_object($record) && is_array($record)) {
      $params = $record;
      $p = array('as', 'voice', 'emailFormat', 'transcription', 'terminator');
      foreach ($p as $option) {
        $params[$option] = array_key_exists($option, $params) ? $params[$option] : null;
      }
      $choices = isset($params["terminator"]) ? new Choices(null, null, $params["terminator"]) : null;
      $say = null;
      if (is_array($params['transcription'])) {
        $p = array('url', 'id', 'emailFormat');
        foreach ($p as $option) {
          $$option = null;
          if (!is_array($params["transcription"]) || !array_key_exists($option, $params["transcription"])) {
            $params["transcription"][$option] = null;
          }
        }
        $transcription = new Transcription($params["transcription"]["url"],$params["transcription"]["id"],$params["transcription"]["emailFormat"]);
      } else {
        $transcription = $params["transcription"];
      }
      $p = array('name', 'attempts', 'allowSignals', 'bargein', 'beep', 'format', 'maxTime', 'maxSilence', 'method', 'password', 'required', 'timeout', 'username', 'url', 'voice', 'minConfidence', 'interdigitTimeout');
      foreach ($p as $option) {
        $$option = null;
        if (is_array($params) && array_key_exists($option, $params)) {
          $$option = $params[$option];
        }
      }
      $record = new Record($name, $attempts, $allowSignals, $bargein, $beep, $choices, $format, $maxSilence, $maxTime, $method, $password, $required, $say, $timeout, $transcription, $username, $url, $voice, $minConfidence, $interdigitTimeout);
    }
    $this->record = sprintf('%s', $record);
  }

  public function redirect($redirect, Array $params=NULL) {
    if(!is_object($redirect)) {
      $to = isset($params["to"]) ? $params["to"]: null;
      $from = isset($params["from"]) ? $params["from"] : null;
      $name = isset($params["name"]) ? $params["name"] : null;
      $timeout = isset($params["timeout"]) ? $params["timeout"] : null;
      $redirect = new Redirect($redirect, $from, $name, $timeout);
    }
    $this->redirect = sprintf('%s', $redirect);
  }

  public function reject() {
    $reject = new Reject();
    $this->reject = sprintf('%s', $reject);
  }

  public function say($say, Array $params=NULL) {
    if(!is_object($say)) {
      $p = array("required",'as', 'format', 'event','voice', 'allowSignals','attempts','name','terminator');
      $value = $say;
      foreach ($p as $option) {
        $$option = null;
        if (is_array($params) && array_key_exists($option, $params)) {
          $$option = $params[$option];
        }
      }
      $voice = isset($voice) ? $voice : $this->_voice;
      $say = new Say($value, $as, $event, $voice, $allowSignals, $attempts, $name, $terminator, $required);
    }
    $this->say = array(sprintf('%s', $say));
  }
  
  public function cloudlog($logcontent, Array $params=NULL) {
    if(!is_object($logcontent)) {
      $p = array('level');
      foreach ($p as $option) {
        $$option = null;
        if (is_array($params) && array_key_exists($option, $params)) {
          $$option = $params[$option];
        }
      }
      $logcontent = new CloudLog($logcontent, $level);
    }
    $this->cloudlog = array(sprintf('%s', $logcontent));
  }

  public function startRecording($startRecording) {
    if(!is_object($startRecording) && is_array($startRecording)) {
      $params = $startRecording;
      $p = array('format', 'method', 'password', 'url', 'username', 'transcriptionID', 'transcriptionEmailFormat', 'transcriptionOutURI');
      foreach ($p as $option) {
        $$option = null;
        if (is_array($params) && array_key_exists($option, $params)) {
          $$option = $params[$option];
        }
      }
      $startRecording = new StartRecording($format, $method, $password, $url, $username, $transcriptionID, $transcriptionEmailFormat, $transcriptionOutURI);
    }
    $this->startRecording = sprintf('%s', $startRecording);
  }

  public function stopRecording() {
    $stopRecording = new stopRecording();
    $this->stopRecording = sprintf('%s', $stopRecording);
  }

  public function transfer($transfer, Array $params=NULL) {
    if(!is_object($transfer)) {
      $choices = isset($params["choices"]) ? $params["choices"] : null;
      $choices = isset($params["terminator"])
        ? new Choices(null, null, $params["terminator"]) 
        : $choices;
      $to = isset($params["to"]) ? $params["to"] : $transfer;
      $p = array('answerOnMedia', 'ringRepeat', 'timeout', 'from', 'allowSignals', 'headers', 'machineDetection', 'voice', 'name');
      foreach ($p as $option) {
        $$option = null;
        if (is_array($params) && array_key_exists($option, $params)) {
          $$option = $params[$option];
        }
      }
      $on = null;
      $transfer = new Transfer($to, $answerOnMedia, $choices, $from, $ringRepeat, $timeout, $on, $allowSignals, $headers, $machineDetection, $voice, $name);
    }
    $this->transfer = sprintf('%s', $transfer);
  }
  
  public function wait($wait) {
     if (!is_object($wait) && is_array($wait)){
        $params = $wait;
        $signal = isset($params['allowSignals']) ? $params['allowSignals'] : null;
        $wait = new Wait($params["milliseconds"], $signal);
    }
    $this->wait = sprintf('%s', $wait);
    
  }

  public function createSession($token, Array $params=NULL) {
    try {
      $session = new SessionAPI();
      $result = $session->createSession($token, $params);
      return $result;
    }
    catch (Exception $ex) {
      throw new CloudapiException($ex->getMessage(), $ex->getCode());
    }
  }

  public function sendEvent($session_id, $value) {
    try {
      $event = new EventAPI();
      $result = $event->sendEvent($session_id, $value);
      return $result;
    }
    catch (Exception $ex) {
      throw new CloudapiException($ex->getMessage(), $ex->getCode());
    }
  }
  public function createApplication($userid, $password, Array $params) {
    $p = array('href', 'name', 'voiceUrl', 'messagingUrl', 'platform', 'partition');
    foreach ($p as $property) {
      $$property = null;
      if (is_array($params) && array_key_exists($property, $params)) {
        $$property = $params[$property];
      }
    }
    try {
      $provision = new ProvisioningAPI($userid, $password);
      $result = $provision->createApplication($href, $name, $voiceUrl, $messagingUrl, $platform, $partition);
      return $result;
    }
    catch (Exception $ex) {
      throw new CloudapiException($ex->getMessage(), $ex->getCode());
    }
  }

  public function updateApplicationAddress($userid, $passwd, $applicationID, Array $params) {
    $p = array('type', 'prefix', 'number', 'city', 'state', 'channel', 'username', 'password', 'token');
    foreach ($p as $property) {
      $$property = null;
      if (is_array($params) && array_key_exists($property, $params)) {
        $$property = $params[$property];
      }
    }
    try {
      $provision = new ProvisioningAPI($userid, $passwd);
      $result = $provision->updateApplicationAddress($applicationID, $type, $prefix, $number, $city, $state, $channel, $username, $password, $token);
      return $result;
    }
    catch (Exception $ex) {
      throw new CloudapiException($ex->getMessage(), $ex->getCode());
    }
  }
  public function updateApplicationProperty($userid, $password, $applicationID, Array $params) {
    $p = array('href', 'name', 'voiceUrl', 'messagingUrl', 'platform', 'partition');
    foreach ($p as $property) {
      $$property = null;
      if (is_array($params) && array_key_exists($property, $params)) {
        $$property = $params[$property];
      }
    }
    try {
      $provision = new ProvisioningAPI($userid, $password);
      $result = $provision->updateApplicationProperty($applicationID, $href, $name, $voiceUrl, $messagingUrl, $platform, $partition);
      return $result;
    }
    catch (Exception $ex) {
      throw new CloudapiException($ex->getMessage(), $ex->getCode());
    }
  }

  public function deleteApplication($userid, $password, $applicationID) {
    $provision = new ProvisioningAPI($userid, $password);
    return $provision->deleteApplication($applicationID);
  }

  public function deleteApplicationAddress($userid, $password, $applicationID, $addresstype, $address) {
    $provision = new ProvisioningAPI($userid, $password);
    return $provision->deleteApplicationAddress($applicationID, $addresstype, $address);
  }

  public function viewApplications($userid, $password) {
    $provision = new ProvisioningAPI($userid, $password);
    return $provision->viewApplications();
  }
  public function viewSpecificApplication($userid, $password, $applicationID) {
    $provision = new ProvisioningAPI($userid, $password);
    return $provision->viewSpecificApplication($applicationID);
  }

  public function viewAddresses($userid, $password, $applicationID) {
    $provision = new ProvisioningAPI($userid, $password);
    return $provision->viewAddresses($applicationID);
  }
  public function viewExchanges($userid, $password) {
    $provision = new ProvisioningAPI($userid, $password);
    return $provision->viewExchanges();
  }
  public function renderJSON() {
    header('Content-type: application/json');
    echo $this;
  }

  public function __set($name, $value) {
    array_push($this->Cloudapi, array($name => $value));
  }
  public function __toString() {
    // Remove voice and language so they do not appear in the rednered JSON.
    unset($this->_voice);
    unset($this->_language);

    // Call the unescapeJSON() method in the parent class.
    return parent::unescapeJSON(json_encode($this));
  }
}


abstract class BaseClass {

  abstract public function __toString();

  public function __set($attribute, $value) {
    $this->$attribute= $value;
  }

  public function unescapeJSON($json) {
    return str_replace(array('\"', "\"{", "}\"", '\\\\\/', '\\\\'), array('"', "{", "}", '/', '\\'), $json);
  }
}

class EmptyBaseClass {

  final public function __toString() {
    return json_encode(null);
  }
}



class Ask extends BaseClass {

  private $_required;
  private $_choices;
  private $_bargein;
  private $_minConfidence;
  private $_name;  
  private $_say;
  private $_timeout;
  private $_allowSignals;
  private $_recognizer;
  private $_sensitivity;
  private $_speechCompleteTimeout;
  private $_speechIncompleteTimeout;
  private $_attempts;

  /**
  * Class constructor
  *
  * @param boolean $bargein
  * @param Choices $choices
  * @param float $minConfidence
  * @param string $name
  * @param boolean $required
  * @param Say $say
  * @param int $timeout
  * @param string|array $allowSignals
  * @param integer $interdigitTimeout
  * @param integer $sensitivity 
  * @param float $speechCompleteTimeout
  * @param float $speechIncompleteTimeout
  */
  public function __construct($attempts=NULL, $bargein=NULL, Choices $choices=NULL, $minConfidence=NULL, $name=NULL, $required=NULL, $say=NULL, $timeout=NULL, $allowSignals=NULL, $recognizer=NULL, $sensitivity=NULL, $speechCompleteTimeout=NULL, $speechIncompleteTimeout=NULL) {

    $this->_attempts = $attempts;
  	$this->_required=$required;
    $this->_bargein = $bargein;
    $this->_choices = isset($choices) ? sprintf('%s', $choices) : null ;
    $this->_minConfidence = $minConfidence;
    $this->_name = $name;
    $this->_required = $required;
    $this->_say = isset($say) ? $say : null;
    $this->_timeout = $timeout;
    $this->_allowSignals = $allowSignals;
    $this->_recognizer = $recognizer;
    $this->_sensitivity = $sensitivity;
    $this->_speechCompleteTimeout = $speechCompleteTimeout;
    $this->_speechIncompleteTimeout = $speechIncompleteTimeout;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    if(isset($this->_attempts)) { $this->attempts = $this->_attempts; }
    if(isset($this->_bargein)) { $this->bargein = $this->_bargein; }
    if(isset($this->_choices)) { $this->choices = $this->_choices; }
    if(isset($this->_minConfidence)) { $this->minConfidence = $this->_minConfidence; }
    if(isset($this->_name)) { $this->name = $this->_name; }
    if(isset($this->_required)) { $this->required = $this->_required; }
    if(isset($this->_say)) { $this->say = $this->_say; }
    if (is_array($this->_say)) {
      foreach ($this->_say as $k => $v) {
        $this->_say[$k] = sprintf('%s', $v);
      }
    }
    if(isset($this->_timeout)) { $this->timeout = $this->_timeout; }
    if(isset($this->_allowSignals)) { $this->allowSignals = $this->_allowSignals; }
    if(isset($this->_recognizer)) { $this->recognizer = $this->_recognizer; }
    if(isset($this->_sensitivity)) { $this->sensitivity = $this->_sensitivity; }
    if(isset($this->_speechCompleteTimeout)) { $this->speechCompleteTimeout = $this->_speechCompleteTimeout; }
    if(isset($this->_speechIncompleteTimeout)) { $this->speechIncompleteTimeout = $this->_speechIncompleteTimeout; }
    return $this->unescapeJSON(json_encode($this));
  }

  /**
  * Adds an additional Say to the Ask
  *
  * Used to add events such as a prompt to say on timeout or nomatch
  *
  * @param Say $say A say object
  */
  public function addEvent(Say $say) {
    $this->_say[] = $say;
  }
}

class Call extends BaseClass {

  private $_to;
  private $_from;
  private $_network;
  private $_channel;
  private $_answerOnMedia;
  private $_timeout;
  private $_headers;
  private $_recording;
  private $_allowSignals;
  private $_machineDetection;
  private $_voice;
  private $_name;
  private $_say;

  /**
  * Class constructor
  *
  * @param string $to
  * @param string $from
  * @param string $network
  * @param string $channel
  * @param boolean $answerOnMedia
  * @param int $timeout
  * @param array $headers
  * @param StartRecording $recording
  * @param string|array $allowSignals
  */
  public function __construct($to, $from=NULL, $network=NULL, $channel=NULL, $answerOnMedia=NULL, $timeout=NULL, Array $headers=NULL, StartRecording $recording=NULL, $allowSignals=NULL, $machineDetection=NULL, $voice=NULL, $name=NULL, Say $say=NULL) {
    $this->_to = $to;
    $this->_from = $from;
    $this->_network = $network;
    $this->_channel = $channel;
    $this->_answerOnMedia = $answerOnMedia;
    $this->_timeout = $timeout;
    $this->_headers = $headers;
    $this->_recording = isset($recording) ? sprintf('%s', $recording) : null ;
    $this->_allowSignals = $allowSignals;
    $this->_machineDetection = $machineDetection;
    $this->_voice = $voice;
    $this->_name = $name;
    $this->_say = isset($say) ? sprintf('%s', $say) : null ;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    $this->to = $this->_to;
    if(isset($this->_from)) { $this->from = $this->_from; }
    if(isset($this->_network)) { $this->network = $this->_network; }
    if(isset($this->_channel)) { $this->channel = $this->_channel; }
    if(isset($this->_timeout)) { $this->timeout = $this->_timeout; }
    if(isset($this->_answerOnMedia)) { $this->answerOnMedia = $this->_answerOnMedia; }
    if(count($this->_headers)) { $this->headers = $this->_headers; }
    if(isset($this->_recording)) { $this->recording = $this->_recording; }
    if(isset($this->_allowSignals)) { $this->allowSignals = $this->_allowSignals; }
    if(isset($this->_machineDetection)) {
      if(is_bool($this->_machineDetection)){
        $this->machineDetection = $this->_machineDetection; 
      }else{
        $this->machineDetection->introduction = $this->_machineDetection; 
        if(isset($this->_voice)){
          $this->machineDetection->voice = $this->_voice; 
        }
      }
    }
    if(isset($this->_voice)) { $this->voice = $this->_voice; }
    if(isset($this->_name)) { $this->name = $this->_name; }
    if(isset($this->_say)) { $this->say = $this->_say; }
    return $this->unescapeJSON(json_encode($this));
  }
}

class Choices extends BaseClass {

  private $_value;
  private $_mode;
  private $_terminator;
  private $_interdigitTimeout;

  /**
  * Class constructor
  *
  * @param string $value
  * @param string $mode
  * @param string $terminator
  */
  public function __construct($value=NULL, $mode=NULL, $terminator=NULL, $interdigitTimeout=NULL) {
    $this->_value = $value;
    $this->_mode = $mode;
    $this->_terminator = $terminator;
    $this->_interdigitTimeout = $interdigitTimeout;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    if(isset($this->_value)){ $this->value = $this->_value; }
    if(isset($this->_mode)) { $this->mode = $this->_mode; }
    if(isset($this->_terminator)) { $this->terminator = $this->_terminator; }
    if(isset($this->_interdigitTimeout)) { $this->interdigitTimeout = $this->_interdigitTimeout; }
    return $this->unescapeJSON(json_encode($this));
  }
}

class Conference extends BaseClass {

  private $_id;
  private $_mute;
  private $_name;
  private $_on;
  private $_playTones;
  private $_required;
  private $_terminator;
  private $_allowSignals;
  private $_interdigitTimeout;
  private $_joinPrompt;
  private $_leavePrompt;
  private $_voice;
  private $_choices;


  /**
  * Class constructor
  *
  * @param int $id
  * @param boolean $mute
  * @param string $name
  * @param On $on
  * @param boolean $playTones
  * @param boolean $required
  * @param string $terminator
  * @param string|array $allowSignals
  * @param int $interdigitTimeout
  */
  public function __construct($name, $id=NULL, $mute=NULL, On $on=NULL, $playTones=NULL, $required=NULL, $terminator=NULL, $allowSignals=NULL, $interdigitTimeout=NULL, $joinPrompt=NULL, $leavePrompt=NULL, $voice=NULL, Choices $choices=null) {
    $this->_name = $name;
    $this->_id = (string) $id;
    $this->_mute = $mute;
    $this->_on = isset($on) ? sprintf('%s', $on) : null;
    $this->_playTones = $playTones;
    $this->_required = $required;
    $this->_terminator = $terminator;
    $this->_allowSignals = $allowSignals;
    $this->_interdigitTimeout = $interdigitTimeout;
    $this->_joinPrompt = $joinPrompt;
    $this->_leavePrompt = $leavePrompt;
    $this->_voice = $voice;
    $this->_choices = isset($choices) ? sprintf('%s', $choices) : null ;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    $this->name = $this->_name;
    if(isset($this->_id)) { $this->id = $this->_id; }
    if(isset($this->_mute)) { $this->mute = $this->_mute; }
    if(isset($this->_on)) { $this->on = $this->_on; }
    if(isset($this->_playTones)) { $this->playTones = $this->_playTones; }
    if(isset($this->_required)) { $this->required = $this->_required; }
    if(isset($this->_terminator)) { $this->terminator = $this->_terminator; }
    if(isset($this->_allowSignals)) { $this->allowSignals = $this->_allowSignals; }
    if(isset($this->_interdigitTimeout)) { $this->interdigitTimeout = $this->_interdigitTimeout; }
    if(isset($this->_joinPrompt)) {
       $this->joinPrompt = $this->_joinPrompt; 
       if(isset($this->_voice)) { 
         $this->joinPrompt->voice = $this->_voice; 
       }
    }
    if(isset($this->_leavePrompt)) {
       $this->leavePrompt = $this->_leavePrompt; 
       if(isset($this->_voice)) { 
         $this->leavePrompt->voice = $this->_voice; 
       }
    }
    if(isset($this->_choices)) { $this->choices = $this->_choices; }
    return $this->unescapeJSON(json_encode($this));
  }
}

class Hangup extends EmptyBaseClass { }

class Message extends BaseClass {

  private $_say;
  private $_to;
  private $_channel;
  private $_network;
  private $_from;
  private $_voice;
  private $_timeout;
  private $_answerOnMedia;
  private $_headers;
  private $_name;

  /**
  * Class constructor
  *
  * @param Say $say
  * @param string $to
  * @param string $channel
  * @param string $network
  * @param string $from
  * @param string $voice
  * @param integer $timeout
  * @param boolean $answerOnMedia
  * @param array $headers
  */
  public function __construct(Say $say, $to, $channel=null, $network=null, $from=null, $voice=null, $timeout=null, $answerOnMedia=null, Array $headers=null,$name=NULL,$value=NULL) {
    $this->_say = isset($say) ? sprintf('%s', $say) : null ;
    $this->_to = $to;
    $this->_channel = $channel;
    $this->_network = $network;
    $this->_from = $from;
    $this->_voice = $voice;
    $this->_timeout = $timeout;
    $this->_answerOnMedia = $answerOnMedia;
    $this->_headers = $headers;
    $this->_name = $name;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    $this->say = $this->_say;
    $this->to = $this->_to;
    if(isset($this->_channel)) { $this->channel = $this->_channel; }
    if(isset($this->_network)) { $this->network = $this->_network; }
    if(isset($this->_from)) { $this->from = $this->_from; }
    if(isset($this->_voice)) { $this->voice = $this->_voice; }
    if(isset($this->_timeout)) { $this->timeout = $this->_timeout; }
    if(isset($this->_answerOnMedia)) { $this->answerOnMedia = $this->_answerOnMedia; }
    if(count($this->_headers)) { $this->headers = $this->_headers; }
    if(isset($this->_name)) { $this->name = $this->_name; }
    return $this->unescapeJSON(json_encode($this));
  }
}

class On extends BaseClass {

  private $_event;
  private $_next;
  private $_say;
  private $_voice;
  private $_ask;
  private $_message;
  private $_wait;
  private $_order;

  /**
  * Class constructor
  *
  * @param string $event
  * @param string $next
  * @param Say $say
  * @param string $voice
  */
  public function __construct($event=NULL, $next=NULL, Say $say=NULL, $voice=Null, $ask=NULL, Message $message=NULL, Wait $wait=NULL, $order=NULL) {
    $this->_event = $event;
    $this->_next = $next;
    $this->_say = isset($say) ? sprintf('%s', $say) : null ;
    $this->_voice = $voice;
    $this->_ask = isset($ask) ? sprintf('%s', $ask) : null;
    $this->_message = isset($message) ? sprintf('%s', $message) : null;
    $this->_wait = isset($wait) ? sprintf('%s', $wait) : null;
    $this->_order = $order;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    
    if($this->_event == "connect") {  
      $this->event =  $this->_event;        
      switch($this->_order){
        case 'ask':
        $this->ask = $this->_ask;
        break;
        case  'say':
        $this->say = $this->_say;
        break;
        case 'wait':
        $this->ask = $this->_ask;  
        break;
        case 'message':
        $this->message = $this->_message;
        break;
      }          
      return $this->unescapeJSON(json_encode(($this)));
    }else{
      if(isset($this->_event)) { $this->event = $this->_event; }
      if(isset($this->_next)) { $this->next = $this->_next; }
      if(isset($this->_say)) { $this->say = $this->_say; }
      if(isset($this->_voice)) { $this->voice = $this->_voice; }
      return $this->unescapeJSON(json_encode($this));
    }
  }
}

class Record extends BaseClass {

  private $_attempts;
  private $_allowSignals;
  private $_bargein;
  private $_beep;
  private $_choices;
  private $_format;
  private $_maxSilence;
  private $_maxTime;
  private $_method;
  private $_password;
  private $_required;
  private $_say;
  private $_timeout;
  private $_transcription;
  private $_username;
  private $_url;
  private $_voice;
  private $_minConfidence;
  private $_interdigitTimeout;


  /**
  * Class constructor
  *
  * @param int $attempts
  * @param string|array $allowSignals
  * @param boolean $bargein
  * @param boolean $beep
  * @param Choices $choices
  * @param string $format
  * @param int $maxSilence
  * @param string $method
  * @param string $password
  * @param boolean $required
  * @param Say $say
  * @param int $timeout
  * @param string $username
  * @param string $url
  * @param string $voice
  * @param int $minConfidence
  * @param int $interdigitTimeout
  */
  public function __construct($name=NULL,$attempts=NULL, $allowSignals=NULL, $bargein=NULL, $beep=NULL, Choices $choices=NULL, $format=NULL, $maxSilence=NULL, $maxTime=NULL, $method=NULL, $password=NULL, $required=NULL, $say=NULL, $timeout=NULL, Transcription $transcription=NULL, $username=NULL, $url=NULL, $voice=NULL, $minConfidence=NULL, $interdigitTimeout=NULL) {
  	$this->_name=$name;
    $this->_attempts = $attempts;
    $this->_allowSignals = $allowSignals;
    $this->_bargein = $bargein;
    $this->_beep = $beep;
    $this->_choices = isset($choices) ? sprintf('%s', $choices) : null;
    $this->_format = $format;
    $this->_maxSilence = $maxSilence;
    $this->_maxTime = $maxTime;
    $this->_method = $method;
    $this->_password = $password;
    if (!is_object($say)) {
      $say = new Say($say);
    }
    $this->_say = isset($say) ? sprintf('%s', $say) : null;
    $this->_timeout = $timeout;
    $this->_transcription = isset($transcription) ? sprintf('%s', $transcription) : null;
    $this->_username = $username;
    $this->_url = $url;
    $this->_voice = $voice;
    $this->_minConfidence = $minConfidence;
    $this->_interdigitTimeout = $interdigitTimeout;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    if(isset($this->_attempts)) { $this->attempts = $this->_attempts; }
    if(isset($this->_allowSignals)) { $this->allowSignals = $this->_allowSignals; }
    if(isset($this->_bargein)) { $this->bargein = $this->_bargein; }
    if(isset($this->_beep)) { $this->beep = $this->_beep; }
    if(isset($this->_choices)) { $this->choices = $this->_choices; }
    if(isset($this->_format)) { $this->format = $this->_format; }
    if(isset($this->_maxSilence)) { $this->maxSilence = $this->_maxSilence; }
    if(isset($this->_maxTime)) { $this->maxTime = $this->_maxTime; }
    if(isset($this->_method)) { $this->method = $this->_method; }
    if(isset($this->_password)) { $this->password = $this->_password; }
    if(isset($this->_say)) { $this->say = $this->_say; }
    if(isset($this->_timeout)) { $this->timeout = $this->_timeout; }
    if(isset($this->_transcription)) { $this->transcription = $this->_transcription; }
    if(isset($this->_username)) { $this->username = $this->_username; }
    if(isset($this->_url)) { $this->url = $this->_url; }
    if(isset($this->_voice)) { $this->voice = $this->_voice; }
    if(isset($this->_minConfidence)) { $this->minConfidence = $this->_minConfidence; }
    if(isset($this->_interdigitTimeout)) { $this->interdigitTimeout = $this->_interdigitTimeout; }
    return $this->unescapeJSON(json_encode($this));
  }
}

class Redirect extends BaseClass {

  private $_to;
  private $_from;
  private $_name;
  private $_timeout;

  /**
  * Class constructor
  *
  * @param Endpoint $to
  * @param Endpoint $from
  */
  public function __construct($to=NULL, $from=NULL, $name=NULL, $timeout=NULL ) {
    $this->_to = sprintf('%s', $to);
    $this->_from = isset($from) ? sprintf('%s', $from) : null;
    $this->_name = $name;
    $this->_timeout = $timeout;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    $this->to = $this->_to;
    if(isset($this->_from)) { $this->from = $this->_from; }
    if(isset($this->_name)) { $this->name = $this->_name; }
    if(isset($this->_timeout)) { $this->timeout = $this->_timeout; }
    return $this->unescapeJSON(json_encode($this));
  }
}

class Reject extends EmptyBaseClass { }

class Result {

  private $_sessionId;
  private $_state;
  private $_sessionDuration;
  private $_sequence;
  private $_error;
  private $_calledId;
  private $_duration;
  private $_connectedDuration;
  private $_actions;
  private $_name;
  private $_attempts;
  private $_disposition;
  private $_confidence;
  private $_interpretation;
  private $_concept;
  private $_userType;
  private $_utterance;
  private $_value;
  private $_transcription;

  /**
  * Class constructor
  *
  * @param string $json
  */
  public function __construct($json=NULL) {
    if(empty($json)) {
      $json = file_get_contents("php://input");
      // if $json is still empty, there was nothing in
      // the POST so throw an exception
      if(empty($json)) {
        throw new CloudapiException('No JSON available.');
      }
    }
    $result = json_decode($json);
    if (!is_object($result) || !property_exists($result, "result")) {
      throw new CloudapiException('Not a result object.');
    }
    $this->_sessionId = $result->result->sessionId;
    $this->_state = isset($result->result->state)?$result->result->state:null;
    $this->_sessionDuration = isset($result->result->sessionDuration)?$result->result->sessionDuration:null;
    $this->_sequence = isset($result->result->sequence)?$result->result->sequence:null;
    $this->_error = isset($result->result->error)?$result->result->error:null;
    $this->_calledId = isset($result->result->CalledID)?$result->result->CalledID:null;
    $this->_duration = isset($result->result->duration)?$result->result->duration:null;
    $this->_connectedDuration = isset($result->result->connectedDuration)?$result->result->connectedDuration:null;
    $this->_userType = isset($result->result->userType)?$result->result->userType:null;
    $this->_actions = isset($result->result->actions)?$result->result->actions:null;
    $this->_name = isset($result->result->actions->name)?$result->result->actions->name:null;
    $this->_attempts = isset($result->result->actions->attempts)?$result->result->actions->attempts:null;
    $this->_disposition = isset($result->result->actions->disposition)?$result->result->actions->disposition:null;
    $this->_confidence = isset($result->result->actions->confidence)?$result->result->actions->confidence:null;
    $this->_interpretation = isset($result->result->actions->interpretation)?$result->result->actions->interpretation:null;
    $this->_utterance = isset($result->result->actions->utterance)?$result->result->actions->utterance:null;
    $this->_value = isset($result->result->actions->value)?$result->result->actions->value:null;
    $this->_concept = isset($result->result->actions->concept) ? $result->result->actions->concept : null;
    $this->_transcription = isset($result->result->transcription) ? $result->result->transcription : null;
  }

  public function getSessionId() {
    return $this->_sessionId;
  }

  public function getState() {
    return $this->_state;
  }

  public function getSessionDuration() {
    return $this->_sessionDuration;
  }

  public function getSequence() {
    return $this->_sequence;
  }

  public function getError() {
    return $this->_error;
  }
  
  public function getDuration() {
    return $this->_duration;
  }
  
  public function getCalledId() {
    return $this->_calledId;
  }
  
  public function getConnectedDuration() {
    return $this->_connectedDuration;
  }

  public function getUserType() {
    return $this->_userType;
  }
  
  public function getActions() {
    return $this->_actions;
  }

  public function getName() {
    return $this->_name;
  }

  public function getAttempts() {
    return $this->_attempts;
  }

  public function getDisposition() {
    return $this->_disposition;
  }

  public function getConfidence() {
    return $this->_confidence;
  }

  public function getInterpretation() {
    return $this->_interpretation;
  }

  public function getConcept() {
    return $this->_concept;
  }

  public function getUtterance() {
    return $this->_utterance;
  }

  public function getValue() {
    return $this->_value;
  }

  public function getTranscription() {
    return $this->_transcription;
  }
}

class Say extends BaseClass {

  private $_value;
  private $_as;
  private $_event;
  private $_format;
  private $_voice;
  private $_allowSignals;
  private $_attempts;
  private $_name;
  private $_terminator;
  private $_required;

  /**
  * Class constructor
  *
  * @param string $value
  * @param SayAs $as
  * @param string $event
  * @param string $voice
  * @param string|array $allowSignals
  */
  public function __construct($value, $as=NULL, $event=NULL, $voice=NULL, $allowSignals=NULL, $attempts=NULL, $name=NULL, $terminator=NULL, $required=NULL) {
  	$this->_required=$required;
    $this->_value = $value;
    $this->_as = $as;
    $this->_event = $event;
    $this->_voice = $voice;
    $this->_allowSignals = $allowSignals;
    $this->_attempts = $attempts;
    $this->_name = $name;
    $this->_terminator = $terminator;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    if(isset($this->_event)) { $this->event = $this->_event; }
    $this->value = $this->_value;
    if(isset($this->_as)) { $this->as = $this->_as; }
    if(isset($this->_voice)) { $this->voice = $this->_voice; }
    if(isset($this->_allowSignals)) { $this->allowSignals = $this->_allowSignals; }
    if(isset($this->_attempts)) { $this->attempts = $this->_attempts; }
    if(isset($this->_name)) { $this->name = $this->_name; }
    if(isset($this->_terminator)) { $this->terminator = $this->_terminator; }
    if(isset($this->_required)) { $this->required = $this->_required; }
    return $this->unescapeJSON(json_encode($this));
  }
}

class CloudLog extends BaseClass {

  private $_value;
  private $_level;

  /**
  * Class constructor
  *
  * @param string $value
  * @param SayAs $as
  * @param string $event
  * @param string $voice
  * @param string|array $allowSignals
  */
  public function __construct($value, $level=NULL) {
    $this->_value = $value;
    $this->_level = $level;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    $this->value = $this->_value;
    if(isset($this->_level)) { $this->level = $this->_level; }
    return $this->unescapeJSON(json_encode($this));
  }
}

class Session {

  private $_id;
  private $_accountId;
  private $_callId;
  private $_timestamp;
  private $_initialText;
  private $_to;
  private $_from;
  private $_headers;
  private $_parameters;

  /**
  * Class constructor
  *
  * @param string $json
  */
  public function __construct($json=NULL) {
    if(empty($json)) {
      $json = file_get_contents("php://input");
      // if $json is still empty, there was nothing in
      // the POST so throw exception
      if(empty($json)) {
        throw new CloudapiException('No JSON available.', 1);
      }
    }
    $session = json_decode($json);
    if (!is_object($session) || !property_exists($session, "session")) {
      throw new CloudapiException('Not a session object.', 2);
    }
    $this->_id = $session->session->id;
    $this->_accountId = $session->session->accountId;
    
    
    $this->_callId = isset($session->session->callId)?$session->session->callId:null;
    $this->_timestamp = isset($session->session->timestamp)?$session->session->timestamp:null;
    $this->_initialText = isset($session->session->initialText)?$session->session->initialText:null;
    
    $this->_to = isset($session->session->to)
    ? array(
      "id" => $session->session->to->id,	
      "channel" => $session->session->to->channel,
      "name" => $session->session->to->name,
      "network" => $session->session->to->network
        )
      : array(
        "id" => null,
        "channel" => null,
        "name" => null,
        "network" => null
        );
        $this->_from = isset($session->session->from->id)
        ? array(
          "id" => $session->session->from->id,
          "channel" => $session->session->from->channel,
          "name" => $session->session->from->name,
          "network" => $session->session->from->network
            )
          : array(
            "id" => null,
            "channel" => null,
            "name" => null,
            "network" => null
            );

            $this->_headers = isset($session->session->headers)
              ? self::setHeaders($session->session->headers)
              : array();
            $this->_parameters = property_exists($session->session, 'parameters') ? (Array) $session->session->parameters : null;
          }

          public function getId() {
            return $this->_id;
          }

          public function getAccountID() {
            return $this->_accountId;
          }

          public function getCallId() {
            return $this->_callId;
          }

          public function getTimeStamp() {
            return $this->_timestamp;
          }

          public function getInitialText() {
            return $this->_initialText;
          }

          public function getTo() {
            return $this->_to;
          }

          public function getFrom() {
            return $this->_from;
          }

          function getFromChannel() {
            return $this->_from['channel'];
          }

          function getFromNetwork() {
            return $this->_from['network'];
          }

          public function getHeaders() {
            return $this->_headers;
          }

          /**
          * Returns the query string parameters for the session api
          *
          * If an argument is provided, a string containing the value of a
          * query string variable matching that string is returned or null
          * if there is no match. If no argument is argument is provided,
          * an array is returned with all query string variables or an empty
          * array if there are no query string variables.
          *
          * @param string $name A specific parameter to return
          * @return string|array $param
          */
          public function getParameters($name = null) {
            if (isset($name)) {
              if (!is_array($this->_parameters)) {
                // We've asked for a specific param, not there's no params set
                // return a null.
                return null;
              }
              if (isset($this->_parameters[$name])) {
                return $this->_parameters[$name];
              } else {
                return null;
              }
            } else {
              // If the parameters field doesn't exist or isn't an array
              // then return an empty array()
              if (!is_array($this->_parameters)) {
                return array();
              }
              return $this->_parameters;
            }
          }

          public function setHeaders($headers) {
            $formattedHeaders = new Headers();
            // headers don't exist on outboud calls
            // so only do this if there are headers
            if (is_object($headers)) {
              foreach($headers as $name => $value) {
                $formattedHeaders->$name = $value;
              }
            }
            return $formattedHeaders;
          }
        }

class StartRecording extends BaseClass {

  private $_name;
  private $_format;
  private $_method;
  private $_password;
  private $_url;
  private $_username;
  private $_transcriptionID;
  private $_transcriptionEmailFormat;
  private $_transcriptionOutURI;

  /**
  * Class constructor
  *
  * @param string $name
  * @param string $format
  * @param string $method
  * @param string $password
  * @param string $url
  * @param string $username
  * @param string $transcriptionID
  * @param string $transcriptionEmailFormat
  * @param string $transcriptionOutURI
  */
  public function __construct($format=NULL, $method=NULL, $password=NULL, $url=NULL, $username=NULL, $transcriptionID=NULL, $transcriptionEmailFormat=NULL, $transcriptionOutURI=NULL) {
    $this->_format = $format;
    $this->_method = $method;
    $this->_password = $password;
    $this->_url = $url;
    $this->_username = $username;
    $this->_transcriptionID = $transcriptionID;
    $this->_transcriptionEmailFormat = $transcriptionEmailFormat;
    $this->_transcriptionOutURI = $transcriptionOutURI;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    if(isset($this->_format)) { $this->format = $this->_format; }
    if(isset($this->_method)) { $this->method = $this->_method; }
    if(isset($this->_password)) { $this->password = $this->_password; }
    if(isset($this->_url)) { $this->url = $this->_url; }
    if(isset($this->_username)) { $this->username = $this->_username; }
    if(isset($this->_transcriptionID)) { $this->transcriptionID = $this->_transcriptionID; }
    if(isset($this->_transcriptionEmailFormat)) { $this->transcriptionEmailFormat = $this->_transcriptionEmailFormat; }
    if(isset($this->_transcriptionOutURI)) { $this->transcriptionOutURI = $this->_transcriptionOutURI; }
    return $this->unescapeJSON(json_encode($this));
  }
}

class StopRecording extends EmptyBaseClass { }

class Transcription extends BaseClass {

  private $_url;
  private $_id;
  private $_emailFormat;

  /**
  * Class constructor
  *
  * @param string $url
  * @param string $id
  * @param string $emailFormat
  */
  public function __construct($url, $id=NULL, $emailFormat=NULL) {
    $this->_url = $url;
    $this->_id = $id;
    $this->_emailFormat = $emailFormat;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    if(isset($this->_id)) { $this->id = $this->_id; }
    if(isset($this->_url)) { $this->url = $this->_url; }
    if(isset($this->_emailFormat)) { $this->emailFormat = $this->_emailFormat; }
    return $this->unescapeJSON(json_encode($this));
  }
}

class Transfer extends BaseClass {

  private $_answerOnMedia;
  private $_choices;
  private $_from;
  private $_on;
  private $_ringRepeat;
  private $_timeout;
  private $_to;
  private $_allowSignals;
  private $_headers;
  private $_machineDetection;
  private $_voice;
  private $_name;

  /**
  * Class constructor
  *
  * @param string $to
  * @param boolean $answerOnMedia
  * @param Choices $choices
  * @param Endpoint $from
  * @param On $on
  * @param int $ringRepeat
  * @param int $timeout
  * @param string|array $allowSignals
  * @param array $headers
  */
  public function __construct($to, $answerOnMedia=NULL, Choices $choices=NULL, $from=NULL, $ringRepeat=NULL, $timeout=NULL, $on=NULL, $allowSignals=NULL, Array $headers=NULL, $machineDetection=NULL, $voice=NULL,$name=NULL) {
    $this->_to = $to;
    $this->_answerOnMedia = $answerOnMedia;
    $this->_choices = isset($choices) ? sprintf('%s', $choices) : null;
    $this->_from = $from;
    $this->_ringRepeat = $ringRepeat;
    $this->_timeout = $timeout;
    $this->_on = isset($on) ? array(sprintf('%s', $on)) : null;
    $this->_allowSignals = $allowSignals;
    $this->_headers = $headers;
    $this->_machineDetection = $machineDetection;
    $this->_voice = $voice;
    $this->_name = $name;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    $this->to = $this->_to;
    if(isset($this->_answerOnMedia)) { $this->answerOnMedia = $this->_answerOnMedia; }
    if(isset($this->_choices)) { $this->choices = $this->_choices; }
    if(isset($this->_from)) { $this->from = $this->_from; }
    if(isset($this->_ringRepeat)) { $this->ringRepeat = $this->_ringRepeat; }
    if(isset($this->_timeout)) { $this->timeout = $this->_timeout; }
    if(isset($this->_on)) { $this->on = $this->_on; }
    if(isset($this->_allowSignals)) { $this->allowSignals = $this->_allowSignals; }
    if(count($this->_headers)) { $this->headers = $this->_headers; }
    if(isset($this->_machineDetection)) {
      if(is_bool($this->_machineDetection)){
        $this->machineDetection = $this->_machineDetection; 
      }else{
        $this->machineDetection->introduction = $this->_machineDetection; 
        if(isset($this->_voice)){
          $this->machineDetection->voice = $this->_voice; 
        }
      }
    }
    if(isset($this->_name)) { $this->name = $this->_name; }
    return $this->unescapeJSON(json_encode($this));
  }
}

class Wait extends BaseClass {

  private $_milliseconds;
  private $_allowSignals;

  /**
  * Class constructor
  *
  * @param integer $milliseconds
  * @param string|array $allowSignals
  */
  public function __construct($milliseconds, $allowSignals=NULL) {
    $this->_milliseconds = $milliseconds;
    $this->_allowSignals = $allowSignals;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    $this->milliseconds = $this->_milliseconds; 
    if(isset($this->_allowSignals)) { $this->allowSignals = $this->_allowSignals; }
    return $this->unescapeJSON(json_encode($this));
  }
}
class Endpoint extends BaseClass {

  private $_id;
  private $_channel;
  private $_name = 'unknown';
  private $_network;

  /**
  * Class constructor
  *
  * @param string $id
  * @param string $channel
  * @param string $name
  * @param string $network
  */
  public function __construct($id, $channel=NULL, $name=NULL, $network=NULL) {

    $this->_id = $id;
    $this->_channel = $channel;
    $this->_name = $name;
    $this->_network = $network;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {

    if(isset($this->_id)) { $this->id = $this->_id; }
    if(isset($this->_channel)) { $this->channel = $this->_channel; }
    if(isset($this->_name)) { $this->name = $this->_name; }
    if(isset($this->_network)) { $this->network = $this->_network; }
    return $this->unescapeJSON(json_encode($this));
  }
}

/**
* A helper class for wrapping exceptions. Can be modified for custom excpetion handling.
*
*/
class CloudapiException extends Exception { }

class Date {
  public static $monthDayYear = "mdy";
  public static $dayMonthYear = "dmy";
  public static $yearMonthDay = "ymd";
  public static $yearMonth = "ym";
  public static $monthYear = "my";
  public static $monthDay = "md";
  public static $year = "y";
  public static $month = "m";
  public static $day = "d";
}

class Duration {
  public static $hoursMinutesSeconds = "hms";
  public static $hoursMinutes = "hm";
  public static $hours = "h";
  public static $minutes = "m";
  public static $seconds = "s";
}

class Event {

  public static $continue = 'continue';
  public static $incomplete = 'incomplete';
  public static $error = 'error';
  public static $hangup = 'hangup';
  public static $join = 'join';
  public static $leave = 'leave';
  public static $ring = 'ring';
}

class Format {
  public $date;
  public $duration;
  public static $ordinal = "ordinal";
  public static $digits = "digits";

  public function __construct($date=NULL, $duration=NULL) {
    $this->date = $date;
    $this->duration = $duration;
  }
}

class SayAs {
  public static $date = "DATE";
  public static $digits = "DIGITS";
  public static $number = "NUMBER";
}

class Network {
  public static $pstn = "PSTN";
  public static $voip = "VOIP";
  public static $aim = "AIM";
  public static $gtalk = "GTALK";
  public static $jabber = "JABBER";
  public static $msn = "MSN";
  public static $sms = "SMS";
  public static $yahoo = "YAHOO";
  public static $twitter = "TWITTER";
}

class Channel {
  public static $voice = "VOICE";
  public static $text = "TEXT";
}

class AudioFormat {
  public static $wav = "audio/wav";
  public static $mp3 = "audio/mp3";
}

class Voice {
  public static $Castilian_Spanish_male = "jorge";
  public static $Castilian_Spanish_female = "carmen";
  public static $French_male = "bernard";
  public static $French_female = "florence";
  public static $US_English_male = "dave";
  public static $US_English_female = "jill";
  public static $British_English_male = "dave";
  public static $British_English_female = "kate";
  public static $German_male = "stefan";
  public static $German_female = "katrin";
  public static $Italian_male = "luca";
  public static $Italian_female = "paola";
  public static $Dutch_male = "willem";
  public static $Dutch_female = "saskia";
  public static $Mexican_Spanish_male = "carlos";
  public static $Mexican_Spanish_female = "soledad";
}

class Recognizer {
  public static $German = 'de-de';
  public static $British_English = 'en-gb';
  public static $US_English = 'en-us';
  public static $Castilian_Spanish = 'es-es';
  public static $Mexican_Spanish = 'es-mx';
  public static $French_Canadian = 'fr-ca';
  public static $French = 'fr-fr';
  public static $Italian = 'it-it';
  public static $Polish = 'pl-pl';
  public static $Dutch = 'nl-nl';
}

class Headers {

  public function __set($name, $value) {
    if(!strstr($name, "-")) {
      $this->$name = $value;
    } else {
      $name = str_replace("-", "_", $name);
      $this->$name = $value;
    }
  }
}

?>

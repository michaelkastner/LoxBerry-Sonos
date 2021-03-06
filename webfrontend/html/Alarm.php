<?php

/**
* Submodul: Alarm
*
**/


/**
* Function: turn_off_alarms --> turns off all Sonos alarms
*
* @param: empty
* @return: disabled alarms
**/
function turn_off_alarms() {
	global $sonoszone, $master, $psubfolder, $home;
	
	$sonos = new PHPSonos($sonoszone[$master][0]);
	$alarm = $sonos->ListAlarms();
	$quan = count($alarm);
	for ($i=0; $i<$quan; $i++) {
		$sonos->UpdateAlarm($alarm[$i]['ID'], $alarm[$i]['StartTime'], $alarm[$i]['Duration'], $alarm[$i]['Recurrence'], 
		$alarm[$i]['Enabled'] = 0, $alarm[$i]['RoomUUID'], $alarm[$i]['ProgramURI'], $alarm[$i]['ProgramMetaData'], 
		$alarm[$i]['PlayMode'], $alarm[$i]['Volume'], $alarm[$i]['IncludeLinkedZones']);
	}
	LOGGING("All Sonos alarms has been turned off.", 6);
}


/**
* Function: restore_alarms --> turns on all previous saved Sonos alarms
*
* @param: empty
* @return: disabled alarms
**/
function restore_alarms() {
	global $sonos, $sonoszone, $psubfolder, $home, $master;
	
	$sonos = new PHPSonos($sonoszone[$master][0]);
	$alarm = $sonos->ListAlarms();
	$quan = count($alarm);
	for ($i=0; $i<$quan; $i++) {
		$sonos->UpdateAlarm($alarm[$i]['ID'], $alarm[$i]['StartTime'], $alarm[$i]['Duration'], $alarm[$i]['Recurrence'], 
		$alarm[$i]['Enabled'] = 1, $alarm[$i]['RoomUUID'], $alarm[$i]['ProgramURI'], $alarm[$i]['ProgramMetaData'], 
		$alarm[$i]['PlayMode'], $alarm[$i]['Volume'], $alarm[$i]['IncludeLinkedZones']);
	}
	LOGGING("All Sonos alarms has been turned on.", 6);
		
}



/**
* Function: sleeptimer --> setzt einen Sleeptimer
*
* @param: empty
* @return: 
**/
function sleeptimer() {
	
	if(isset($_GET['timer']) && is_numeric($_GET['timer']) && $_GET['timer'] > 0 && $_GET['timer'] < 60) {
		$timer = $_GET['timer'];
		if($_GET['timer'] < 10) {
			$timer = '00:0'.$_GET['timer'].':00';
		} else {
			$sonos = new PHPSonos($sonoszone[$master][0]);
			$timer = '00:'.$_GET['timer'].':00';
			$timer = $sonos->Sleeptimer($timer);
		}
		LOGGING("Sleeptimer has been switched on. Time to sleep is: ".$timer, 6);
	} else {
		LOGGING('The entered time is not correct, please correct', 4);
	}
}


/**
* Function: turn_off_alarm --> disable specific Sonos alarms
*
* @param: empty
* @return: disable alarm
**/
function turn_off_alarm() {
	global $master, $sonoszone, $psubfolder, $home;
	
	$alarmid = $_GET['id'];
	$sonos = new PHPSonos($sonoszone[$master][0]);
	$alarm = $sonos->ListAlarms();
	$alarmi = str_replace(' ','',$alarmid); 
	$alarmarr = explode(',', $alarmi);
	foreach ($alarmarr as $alarmid)  {
		$arrid = recursive_array_search($alarmid, $alarm);
		if ($arrid === false) {
			LOGGING("The entered Alarm-ID 'ID=".$alarmid."' seems to be not valid. Please run '...action=listalarms' in Browser and doublecheck your syntax!", 3);
			continue;
		}
		$sonos->UpdateAlarm($alarm[$arrid]['ID'], $alarm[$arrid]['StartTime'], $alarm[$arrid]['Duration'], $alarm[$arrid]['Recurrence'], 
		$alarm[$arrid]['Enabled'] = 0, $alarm[$arrid]['RoomUUID'], $alarm[$arrid]['ProgramURI'], $alarm[$arrid]['ProgramMetaData'], 
		$alarm[$arrid]['PlayMode'], $alarm[$arrid]['Volume'], $alarm[$arrid]['IncludeLinkedZones']);
		LOGGING("Sonos Alarm-ID 'ID=".$alarmid."' has been disabled.", 6);
	}
}


/**
* Function: restore_alarm --> enable specific Sonos alarms
*
* @param: empty
* @return: enable alarm
**/
function restore_alarm() {
	global $sonoszone, $psubfolder, $home, $master;
	
	$alarmid = $_GET['id'];
	$sonos = new PHPSonos($sonoszone[$master][0]);
	$alarm = $sonos->ListAlarms();
	$alarmi = str_replace(' ','',$alarmid); 
	$alarmarr = explode(',', $alarmi);
	foreach ($alarmarr as $alarmid)  {
		$arrid = recursive_array_search($alarmid, $alarm);
		if ($arrid === false) {
			LOGGING("The entered Alarm-ID 'ID=".$alarmid."' seems to be not valid. Please run '...action=listalarms' in Browser and doublecheck your syntax!", 3);
			continue;
		}
		$sonos->UpdateAlarm($alarm[$arrid]['ID'], $alarm[$arrid]['StartTime'], $alarm[$arrid]['Duration'], $alarm[$arrid]['Recurrence'], 
		$alarm[$arrid]['Enabled'] = 1, $alarm[$arrid]['RoomUUID'], $alarm[$arrid]['ProgramURI'], $alarm[$arrid]['ProgramMetaData'], 
		$alarm[$arrid]['PlayMode'], $alarm[$arrid]['Volume'], $alarm[$arrid]['IncludeLinkedZones']);
		LOGGING("Sonos Alarm-ID 'ID=".$alarmid."' has been enabled.", 6);
	}
}



?>
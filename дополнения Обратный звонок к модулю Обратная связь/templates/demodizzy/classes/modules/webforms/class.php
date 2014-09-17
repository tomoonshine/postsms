<?php
    class webforms_custom extends def_module {

        public function sendSMS() {

		$oObjects = umiObjectsCollection::getInstance();
		$SMSobjID = getRequest('system_sms_to');
		$telefon = $oObjects->getObject($SMSobjID)->getValue('telefon');

			
			$src = '<?xml version="1.0" encoding="UTF-8"?>   
<SMS>
	<operations> 
		<operation>SEND</operation>
	</operations>
	<authentification>   
		<username>kakadupark@yandex.ru</username>  
		<password>yjdjrhsvcrfz</password>  
	</authentification>  
	<message>
		<sender>kakadu</sender>   
		<text>Обратный звонок '.$_REQUEST['data']['new']['fio'].' '.$_REQUEST['data']['new']['telefon'].' '.$fio = $_REQUEST['data']['new']['vremya_dlya_zvonka'].'</text>  
	</message>   
	<numbers>
		<number>'.$telefon.'</number>
	</numbers>   
</SMS>'; 

			$Curl = curl_init();   
			$CurlOptions = array(  
			CURLOPT_URL=>'http://atompark.com/members/sms/xml', 
			CURLOPT_FOLLOWLOCATION=>false,  
			CURLOPT_POST=>true, 
			CURLOPT_HEADER=>false,  
			CURLOPT_RETURNTRANSFER=>true,   
			CURLOPT_CONNECTTIMEOUT=>15, 
			CURLOPT_TIMEOUT=>100,   
			CURLOPT_POSTFIELDS=>array('XML'=>$src),  
			); 
			curl_setopt_array($Curl, $CurlOptions);
			
			if(false === ($Result = curl_exec($Curl))) {   
				throw new Exception('Http request failed');
			}  
				 
			curl_close($Curl); 

			$this->send();
			
        }
		

    };
?>
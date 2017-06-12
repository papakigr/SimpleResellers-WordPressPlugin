<?php
set_time_limit(200) ;
//version 9 :  noemvrios 2011
//require ('libs/ActiveLink/alink_include.php');
//import('org.active-link.net.HTTPClient');
//import('org.active-link.xml.XMLDocument');



function papaki_getConfigArray() {
	$configarray = array(
	// "Username" => array( "Type" => "text", "Size" => "20", "Description" => "Enter the username given from Papaki (Leave empty if Apikey is used)", ),
//	 "Password" => array( "Type" => "password", "Size" => "20", "Description" => "Enter the password given from Papaki (Leave empty if Apikey is used)", ),
	 "APIkey" => array( "Type" => "text", "Size" => "100", "Description" => "Enter the apikey", ),
	 "PostUrl" => array( "Type" => "text", "Size" => "64", "Description" => "Enter http://api.papaki.com/register_url2.aspx", ),
	 "TestMode" => array( "Type" => "no", ),
	 "Convert Punycode domains" => array( "Type" => "no", ),
	);
	return $configarray;
}

function papaki_GetNameservers($params) { 
	$username ='';
	$password = '';
	$apikey = encodetolatin($params["APIkey"]);
	$posturl = encodetolatin($params["PostUrl"]);
	$testmode = encodetolatin($params["TestMode"]);
	$tld = encodetolatin($params["tld"]);
	$sld = encodetolatin($params["sld"]);
	# Put your code to get the nameservers here and return the values below
	
	 
	$json = new Services_JSON();
	$jsonarray=array("request"=>array("do"=>'getnameservers',"username"=>$username,"password"=>$password,"apiKey"=>$apikey,"domainname"=>$sld.".".$tld,"registration_type"=>'2',"test"=>'False'));
	$Xpost = $json->encode($jsonarray);
	$Xpost=latintogreek($Xpost);

		
	
	 
		
		
	$headers = array('Content-type: application/x-www-form-urlencoded');
					 
		$pageContents = HttpClient::quickPost($params["PostUrl"],  array(
    'message' => $Xpost
));	 
		
		 
		$responsearray = $json->decode($pageContents);
		$codeNode = $responsearray->response->code;
		$message = $responsearray->response->message;
 
 
 
	 
	$nameserver1=$responsearray->response->ns1;
	$nameserver2=$responsearray->response->ns2;
	$nameserver3=$responsearray->response->ns3;
	$nameserver4=$responsearray->response->ns4;
	
	 
	 
		
    if ($codeNode!="1000" ){
       
	  $values["error"]='Error: ' .  $codeNode . ' - ' .  $message;
	 }
	 

	#####################################	
	
	
	$values["ns1"] = $nameserver1;
	$values["ns2"] = $nameserver2;
    $values["ns3"] = $nameserver3;
    $values["ns4"] = $nameserver4;
	# If error, return the error message in the value below
	//$values["error"] = $error;
	return $values;	
}

function papaki_SaveNameservers($params) { 
	$username ='';
	$password = '';
	$apikey = encodetolatin($params["APIkey"]);
	$testmode = encodetolatin($params["TestMode"]);
	$posturl = encodetolatin($params["PostUrl"]);
	$tld = encodetolatin($params["tld"]);
	$sld = encodetolatin($params["sld"]);
    $nameserver1 = $params["ns1"];
	$nameserver2 = $params["ns2"];
    $nameserver3 = $params["ns3"];
	$nameserver4 = $params["ns4"];
	 
	$ip1="";
	$ip2="";
	$ip3="";
	$ip4="";
	if($nameserver1!=""){
	$ip1 = gethostbyname($nameserver1);
	if($ip1==$nameserver1)
		$ip1="";
	}
	if($nameserver2!=""){
	$ip2 = gethostbyname($nameserver2);
	if($ip2==$nameserver2)
		$ip2="";
	}
	if($nameserver3!=""){
	$ip3 = gethostbyname($nameserver3);
	if($ip3==$nameserver3)
		$ip3="";
	}
	if($nameserver4!=""){
	$ip4 = gethostbyname($nameserver4);
	if($ip4==$nameserver4)
		$ip4="";
	}
	
 $nsarray=array();
$counting=0;
		
		
	$nameserver1 = encodetolatin($params["ns1"]);
	$nameserver2 = encodetolatin($params["ns2"]);
    $nameserver3 = encodetolatin($params["ns3"]);
	$nameserver4 = encodetolatin($params["ns4"]);
		
		
	if($ip1!=""){	
 
		
		$nsarray[$counting]=array("Name"=>$nameserver1, "Ip"=>$ip1);
		$counting=$counting+1;
	} 
if($ip2!=""){	
 
	$nsarray[$counting]=array("Name"=>$nameserver2, "Ip"=>$ip2);
		$counting=$counting+1;
}
 
if($ip3!=""){	
 $nsarray[$counting]=array("Name"=>$nameserver3, "Ip"=>$ip3);
		$counting=$counting+1;
}
 
 
if($ip4!=""){	
	 $nsarray[$counting]=array("Name"=>$nameserver4, "Ip"=>$ip4);
		$counting=$counting+1;
}
 
 
	
	$json = new Services_JSON();
	$jsonarray=array("request"=>array("do"=>'editnameservers',"username"=>$username,"password"=>$password,"apiKey"=>$apikey,"domainname"=>$sld.".".$tld,"registration_type"=>'2',"test"=>'False',"lang"=>'el',"NameServers"=>array("v4"=>$nsarray)));
	$Xpost = $json->encode($jsonarray);
	 $Xpost=latintogreek($Xpost);
	 
	$headers = array('Content-type: application/x-www-form-urlencoded');
					 
		$pageContents = HttpClient::quickPost($params["PostUrl"],  array(
    'message' => $Xpost
));	
		 
		$responsearray = $json->decode($pageContents);
		$codeNode = $responsearray->response->code;
		$message = $responsearray->response->message;
		
	 
 
	
	 
	 
		
    if ($codeNode!="1000" ){
       
	  $values["error"]='Error: ' .  $codeNode . ' - ' .  $message;
	 }

	#####################################	
	
	return $values;
}

function papaki_GetRegistrarLock($params) {  
	$username ='';
	$password = '';
	$apikey = encodetolatin($params["APIkey"]);
	$posturl = encodetolatin($params["PostUrl"]);
	$testmode = encodetolatin($params["TestMode"]);
	$tld = encodetolatin($params["tld"]);
	$sld = encodetolatin($params["sld"]);
	# Put your code to get the lock status here
	
	if (isgrdomain($sld.".".$tld) or iseudomain($sld.".".$tld) ){
		$lockstatus="unlocked";
		return $lockstatus;
	}
	
		########################
		
		$json = new Services_JSON();
	$jsonarray=array("request"=>array("do"=>'getregistrarlock',"username"=>$username,"password"=>$password,"apiKey"=>$apikey,"domainname"=>$sld.".".$tld,"registration_type"=>'2',"test"=>'False',"customer_language"=>'gr'));
	$Xpost = $json->encode($jsonarray);
	$Xpost=latintogreek($Xpost);	
	 
	
	$headers = array('Content-type: application/x-www-form-urlencoded');
	 		 
		$pageContents = HttpClient::quickPost($params["PostUrl"],  array(
    'message' => $Xpost
));	
		
		 
		$responsearray = $json->decode($pageContents);
		$codeNode = $responsearray->response->code;
		$message = $responsearray->response->message;
	 
	$lock="0";
	
	//echo "ns1".$nameserver1;
	 
		//echo $responseXML;
		//die("");
    if ($codeNode!="1000" ){
       
	  $values["error"]='Error: ' .  $codeNode . ' - ' .  $message;
	 }else{
		 $lock=$responsearray->response->lock_state;
		 
	 }
	 
	#####################################	
	
	
	 
	
	
	if ($lock=="True") {
		$lockstatus="locked";
	} else {
		$lockstatus="unlocked";
	}
	
	return $lockstatus;
}

function papaki_SaveRegistrarLock($params) { 
$username ='';
	$password = '';
	$apikey = encodetolatin($params["APIkey"]);
	$posturl = encodetolatin($params["PostUrl"]);
	$testmode = encodetolatin($params["TestMode"]);
	$tld = encodetolatin($params["tld"]);
	$sld = encodetolatin($params["sld"]);
	
	if (isgrdomain($sld.".".$tld) or iseudomain($sld.".".$tld) ){
		return $values;
	} 
	
	if ($params["lockenabled"]=="locked") {
		$lockstatus="enable";
	} else {
		$lockstatus="disable";
	}
	
	
		
		$json = new Services_JSON();
	$jsonarray=array("request"=>array("do"=>'reglock',"username"=>$username,"password"=>$password,"apiKey"=>$apikey,"domainname"=>$sld.".".$tld,"registration_type"=>'2',"customer_language"=>'gr',"locktype"=>$lockstatus));
	$Xpost = $json->encode($jsonarray);
	$Xpost=latintogreek($Xpost);
	 
	
	  $headers = array('Content-type: application/x-www-form-urlencoded');
		
	 
		
	$pageContents = HttpClient::quickPost($params["PostUrl"],  array(
    'message' => $Xpost
));	
	 
		$responsearray = $json->decode($pageContents);
		$codeNode = $responsearray->response->code;
		$message = $responsearray->response->message;
		
	 
		
	  
	 
	  
	  
	   
	  
    if ($codeNode!="1000" ){
       
	  $values["error"]='Error: ' .  $codeNode . ' - ' .  $message;
	  }
	
	//
	
	# Put your code to save the registrar lock here
	# If error, return the error message in the value below
	//$values["error"] = $Enom->Values["Err1"];
	return $values;
}

 
 


function papaki_GetEmailForwarding($params) { //den uposthrizetai apo to Api
	$username ='';
	$password = '';
 
	$testmode = $params["TestMode"];
	$tld = $params["tld"];
	$sld = $params["sld"];
	# Put your code to get email forwarding here - the result should be an array of prefixes and forward to emails (max 10)
	foreach ($result AS $value) {
		$values[$counter]["prefix"] = $value["prefix"];
		$values[$counter]["forwardto"] = $value["forwardto"];
	}
	return $values;
}

function papaki_SaveEmailForwarding($params) { //den uposthrizetai apo to Api
	$username ='';
	$password = '';
	$testmode = $params["TestMode"];
	$tld = $params["tld"];
	$sld = $params["sld"];
	foreach ($params["prefix"] AS $key=>$value) {
		$forwardarray[$key]["prefix"] =  $params["prefix"][$key];
		$forwardarray[$key]["forwardto"] =  $params["forwardto"][$key];
	}
	# Put your code to save email forwarders here
}

function papaki_GetDNS($params) { 
 
 	$username ='';
	$password = '';
	$apikey = $params["APIkey"];
	$posturl = $params["PostUrl"];
	$testmode = $params["TestMode"];
	$tld = $params["tld"];
	$sld = $params["sld"];
    # Put your code here to get the current DNS settings - the result should be an array of hostname, record type, and address
   		########################

	 

	#####################################	
   
    $hostrecords = array();
	$hostrecords[] = array( "hostname" => $ns1, "type" => "A", "address" => $ip1, );
	$hostrecords[] = array( "hostname" => $ns2, "type" => "A", "address" => $ip2, );
	$hostrecords[] = array( "hostname" => $ns3, "type" => "A", "address" => $ip3, );
	$hostrecords[] = array( "hostname" => $ns4, "type" => "A", "address" => $ip4, );
    //$hostrecords[] = array( "hostname" => "ns1", "type" => "A", "address" => "192.168.0.1", );
   // $hostrecords[] = array( "hostname" => "ns2", "type" => "A", "address" => "192.168.0.2", );
	return $hostrecords;
}

function papaki_SaveDNS($params) {
   $username ='';
	$password = '';
	$apikey = $params["APIkey"];
	$posturl = $params["PostUrl"];
	$testmode = $params["TestMode"];
	$tld = $params["tld"];
	$sld = $params["sld"];
    # Loop through the submitted records
	foreach ($params["dnsrecords"] AS $key=>$values) {
		$hostname = $values["hostname"];
		$type = $values["type"];
		$address = $values["address"];
		# Add your code to update the record here
	}
    # If error, return the error message in the value below
	$values["error"] = $Enom->Values["Err1"];
	return $values;
}

function papaki_registerdomain($params) {

//////////////////////////////////////////////////////////////////////////////////////////////////
	//fix problem with greek contact setails
	
$sql="select * from tbldomains,tblorders  where tbldomains.orderid=tblorders.id    and   tbldomains.id=".$params["domainid"]."";
	
	$result = mysql_query($sql);
	while ($row = mysql_fetch_array($result)) {
		if ($row{'contactid'}=="0"){
			$sql2="select * from tblclients where id=".$row{'userid'}."";
			$result2 = mysql_query($sql2);
			while ($row2 = mysql_fetch_array($result2)) {
			
			
				$params["companyname"]=$row2{'companyname'};
				$params["firstname"]=$row2{'firstname'};
				$params["lastname"]=$row2{'lastname'};
				$params["address1"]=$row2{'address1'};
				$params["address2"]=$row2{'address2'};
				$params["city"]=$row2{'city'};
				$params["state"]=$row2{'state'};
				$params["postcode"]=$row2{'postcode'};
				$params["country"]=$row2{'country'};
				$params["email"]=$row2{'email'};
				$params["phonenumber"]=$row2{'phonenumber'};
			}
		}
		
		else{
			$sql3="select * from tbldomains,tblorders,tblcontacts where tbldomains.orderid=tblorders.id  and tblorders.contactid=tblcontacts.id and   tbldomains.id=".$params["domainid"]."";
			$result3 = mysql_query($sql3);
//fetch tha data from the database
			while ($row3 = mysql_fetch_array($result3)) {
   				 $params["companyname"]=$row3{'companyname'};
				 $params["firstname"]=$row3{'firstname'};
				 $params["lastname"]=$row3{'lastname'};
				 $params["address1"]=$row3{'address1'};
				 $params["address2"]=$row3{'address2'};
				 $params["city"]=$row3{'city'};
				 $params["state"]=$row3{'state'};
				 $params["postcode"]=$row3{'postcode'};
				 $params["country"]=$row3{'country'};
				 $params["email"]=$row3{'email'};
				 $params["phonenumber"]=$row3{'phonenumber'};
			}	
		
		}
		
	}


/////////////////////////////////////////////////////////////



	$username ='';
	$password = '';
	$apikey = encodetolatin($params["APIkey"]);
	$posturl = encodetolatin($params["PostUrl"]);
	$testmode = encodetolatin($params["TestMode"]);
	$description_ar=encodetolatin($params["additionalfields"]);
	$description=encodetolatin($description_ar["Description"]);
	 if (trim($description)=='')
	  $description= ' ';
	$tld = encodetolatin($params["tld"]);
	$sld = encodetolatin($params["sld"]);
	$regperiod = $params["regperiod"];
	$nameserver1 = encodetolatin($params["ns1"]);
	$nameserver2 = encodetolatin($params["ns2"]);
    $nameserver3 = encodetolatin($params["ns3"]);
    $nameserver4 = encodetolatin($params["ns4"]);
	$idprotection= encodetolatin($params["idprotection"]);
	if($idprotection=="1"){
	$idprotection="true";	
	}else{
	$idprotection="false";	
	}
		if (trim($nameserver1)=='')
	  $nameserver1= ' ';
	  if (trim($nameserver2)=='')
	  $nameserver2= ' ';
	  if (trim($nameserver3)=='')
	  $nameserver3= ' ';
	  if (trim($nameserver4)=='')
	  $nameserver4= ' ';
	# Registrant Details
	$RegistrantFirstName = encodetolatin($params["firstname"]);
	$RegistrantLastName = encodetolatin($params["lastname"]);
	$RegistrantAddress1 = encodetolatin($params["address1"]);
	$RegistrantAddress2 = encodetolatin($params["address2"]);
	$RegistrantCity = encodetolatin($params["city"]);
	$RegistrantStateProvince = encodetolatin($params["state"]);
	$RegistrantPostalCode = encodetolatin($params["postcode"]);
	$RegistrantCountry = encodetolatin($params["country"]);
	$RegistrantEmailAddress = encodetolatin($params["email"]);
	$RegistrantPhone = encodetolatin($params["phonenumber"]);
	# Admin Details
	$AdminFirstName = encodetolatin($params["adminfirstname"]);
	$AdminLastName = encodetolatin($params["adminlastname"]);
	$AdminAddress1 = encodetolatin($params["adminaddress1"]);
	$AdminAddress2 = encodetolatin($params["adminaddress2"]);
	$AdminCity = encodetolatin($params["admincity"]);
	$AdminStateProvince = encodetolatin($params["adminstate"]);
	$AdminPostalCode = encodetolatin($params["adminpostcode"]);
	$AdminCountry = encodetolatin($params["admincountry"]);
	$AdminEmailAddress = encodetolatin($params["adminemail"]);
	$AdminPhone = encodetolatin($params["adminphonenumber"]);
	# Put your code to register domain here
	# If error, return the error message in the value below
	
	
	$json = new Services_JSON();
	$jsonarray=array("request"=>array("do"=>'domainregister',"username"=>$username,"password"=>$password,"apiKey"=>$apikey,"domainname"=>$sld.".".$tld,"registration_type"=>'2',"test"=>'False',"description"=>$description,"ip1"=>' ',"ip2"=>' ',"ip3"=>' ',"ip4"=>' ',"ns1"=>encodetolatin($params["ns1"]),"ns2"=>encodetolatin($params["ns2"]),"ns3"=>encodetolatin($params["ns3"]),"ns4"=>encodetolatin($params["ns4"]),"owner_fullname"=>encodetolatin($params["companyname"]),"owner_firstname"=>encodetolatin($params["firstname"]),"owner_lastname"=>encodetolatin($params["lastname"]),"owner_email"=>encodetolatin($params["email"]),"owner_address"=>encodetolatin($params["address1"]),"owner_state"=>encodetolatin($params["state"]),"owner_city"=>encodetolatin($params["city"]),"owner_postcode"=>encodetolatin($params["postcode"]),"owner_country"=>encodetolatin($params["country"]),"owner_phone"=>'+30.'.encodetolatin($params["phonenumber"]),"owner_fax"=>'+30.2',"owner_litepsd"=>' ',"owner_title"=>' ',"customer_exists"=>'0',"regperiod"=>$params["regperiod"],"idprotect"=>$idprotection,"customer_language"=>"gr"));
	$Xpost = $json->encode($jsonarray);
	$Xpost=latintogreek($Xpost);
	


	
	  $headers = array('Content-type: application/x-www-form-urlencoded');
		
		
		
			 
	 	$pageContents = HttpClient::quickPost($params["PostUrl"],  array(
    'message' => $Xpost
));	
	 
	 
		$responsearray = $json->decode($pageContents);
		$codeNode = $responsearray->response->code;
		$message = $responsearray->response->message;
		 
	 
	
	  
	  
	 
	  
	  
	   
	  
    if ($codeNode!="1000" ){
       
	  $values["error"]='Error: ' .  $codeNode . ' - ' .  $message;
	  }

		
	
	return $values;
}

function papaki_TransferDomain($params) {





	$username ='';
	$password = '';
	$apikey = encodetolatin($params["APIkey"]);
	$posturl = encodetolatin($params["PostUrl"]);
	$testmode = encodetolatin($params["TestMode"]);
	$tld = encodetolatin($params["tld"]);
	$sld = encodetolatin($params["sld"]);
	$regperiod = encodetolatin($params["regperiod"]);
	$transfersecret = encodetolatin($params["transfersecret"]);
	
		
	if(trim($transfersecret)==""){
		$transfersecret = encodetolatin($_POST["eppcode"]);
	}
	if(trim($transfersecret)==""){
		$transfersecret = encodetolatin($_SESSION["eppcode"]);
	}
	
	
	if(trim($transfersecret)==""){
		 
		for($i=0; $i<count($_SESSION['cart']['domains']); $i++){
	$mydomain=$sld.".".$tld;
			if($_SESSION['cart']['domains'][$i]['domain']== $mydomain){
				$transfersecret=encodetolatin($_SESSION['cart']['domains'][$i]['eppcode']);
			}
		}
		 
		 
	}
	
	
	
	$nameserver1 = encodetolatin($params["ns1"]);
	$nameserver2 = encodetolatin($params["ns2"]);
	# Registrant Details
	$RegistrantFirstName = encodetolatin($params["firstname"]);
	$RegistrantLastName = encodetolatin($params["lastname"]);
	$RegistrantAddress1 = encodetolatin($params["address1"]);
	$RegistrantAddress2 = encodetolatin($params["address2"]);
	$RegistrantCity = encodetolatin($params["city"]);
	$RegistrantStateProvince = encodetolatin($params["state"]);
	$RegistrantPostalCode = encodetolatin($params["postcode"]);
	$RegistrantCountry = encodetolatin($params["country"]);
	$RegistrantEmailAddress = encodetolatin($params["email"]);
	$RegistrantPhone = encodetolatin($params["phonenumber"]);
	# Admin Details
	$AdminFirstName = encodetolatin($params["adminfirstname"]);
	$AdminLastName = encodetolatin($params["adminlastname"]);
	$AdminAddress1 = encodetolatin($params["adminaddress1"]);
	$AdminAddress2 = encodetolatin($params["adminaddress2"]);
	$AdminCity = encodetolatin($params["admincity"]);
	$AdminStateProvince = encodetolatin($params["adminstate"]);
	$AdminPostalCode = encodetolatin($params["adminpostcode"]);
	$AdminCountry = encodetolatin($params["admincountry"]);
	$AdminEmailAddress = encodetolatin($params["adminemail"]);
	$AdminPhone = encodetolatin($params["adminphonenumber"]);
		
			$json = new Services_JSON();
	$jsonarray=array("request"=>array("do"=>'changeregistrar',"username"=>$username,"password"=>$password,"apiKey"=>$apikey,"domainname"=>$sld.".".$tld,"registration_type"=>'2',"test"=>'False',"customer_language"=>'gr',"authcode"=>$transfersecret,"customer_exists"=>"0"));
	$Xpost = $json->encode($jsonarray);
	$Xpost=latintogreek($Xpost);	
		
		 
	 
	  $headers = array('Content-type: application/x-www-form-urlencoded');
		
		$pageContents = HttpClient::quickPost($params["PostUrl"],  array(
    'message' => $Xpost
));	
		$responsearray = $json->decode($pageContents);
		$codeNode = $responsearray->response->code;
		$message = $responsearray->response->message;
		
	 
	 
	 
	 
 
      if ($codeNode!="1000" ){ 
	  	$values["error"]='Error: ' .  $codeNode . ' - ' .  $message;
	  }
	
	return $values;
}

function papaki_RenewDomain($params) {
	$username ='';
	$password = '';
	$apikey = encodetolatin($params["APIkey"]);
	$posturl = encodetolatin($params["PostUrl"]);
	$testmode = encodetolatin($params["TestMode"]);
	$tld = encodetolatin($params["tld"]);
	$sld = encodetolatin($params["sld"]);
	$regperiod = $params["regperiod"];
	$idprotection= $params["idprotection"];
	if($idprotection=="1"){
	$idprotection="true";	
	}else{
	$idprotection="false";	
	}
	# Put your code to renew domain here
	# If error, return the error message in the value below
	
			$json = new Services_JSON();
	$jsonarray=array("request"=>array("do"=>'domainupdate',"username"=>$username,"password"=>$password,"apiKey"=>$apikey,"domainname"=>$sld.".".$tld,"registration_type"=>'2',"customer_language"=>'gr',"regperiod"=>$regperiod,"idprotect"=>$idprotection));
	$Xpost = $json->encode($jsonarray);
	$Xpost=latintogreek($Xpost);
	
	 
 
	 $headers = array('Content-type: application/x-www-form-urlencoded');
			 
		$pageContents = HttpClient::quickPost($params["PostUrl"],  array(
    'message' => $Xpost
));	
		 
		$responsearray = $json->decode($pageContents);
		 
		$codeNode = $responsearray->response->code;
		 
		$message = $responsearray->response->message;
		
	 
		
 
      if ($codeNode!="1000" ){ 
	  	$values["error"]='Error: ' .  $codeNode . ' - ' .  $message;
	  }
	
	return $values;
	
}


function papaki_GetContactDetails($params) {  
	$username ='';
	$password = '';
	$apikey = encodetolatin($params["APIkey"]);
	$posturl = encodetolatin($params["PostUrl"]);
	$testmode = encodetolatin($params["TestMode"]);
	$tld = encodetolatin($params["tld"]);
	$sld = encodetolatin($params["sld"]);
	# Put your code to get WHOIS data here
	# Data should be returned in an array as follows
	
	########################
	$json = new Services_JSON();
	$jsonarray=array("request"=>array("do"=>'getcontactdetails',"username"=>$username,"password"=>$password,"apiKey"=>$apikey,"domainname"=>$sld.".".$tld,"registration_type"=>'2'));
	$Xpost = $json->encode($jsonarray);
	$Xpost=latintogreek($Xpost);
 
	 
	
	$headers = array('Content-type: application/x-www-form-urlencoded');
					 
		$pageContents = HttpClient::quickPost($params["PostUrl"],  array(
    'message' => $Xpost
));	
		 
		 
		$responsearray = $json->decode($pageContents);
		$codeNode = $responsearray->response->code;
		$message = $responsearray->response->message;
		
	 
	
	$firstname=$responsearray->response->registrantFirstName;
	$LastName=$responsearray->response->registrantLastname;
	$OrganizationName=$responsearray->response->registrantFullname;
	$JobTitle=$responsearray->response->registrantjob;
	$EmailAddress=$responsearray->response->registrantemail;
	$Address1=$responsearray->response->registrantaddress1;
	$Address2=$responsearray->response->registrantaddress2;
	$City=$responsearray->response->registrantcity;
	$StateProvince=$responsearray->response->registrantstate;
	$PostalCode=$responsearray->response->registrantpostcode;
	$Country=$responsearray->response->registrantcountry;
	$Phone=$responsearray->response->registrantphone;
	$Fax=$responsearray->response->registrantfax;
	
	$Adminfirstname=$responsearray->response->adminFirstName;
	$AdminLastName=$responsearray->response->adminLastname;
	$AdminOrganizationName=$responsearray->response->adminFullname;
	$AdminJobTitle=$responsearray->response->adminjob;
	$AdminEmailAddress=$responsearray->response->adminemail;
	$AdminAddress1=$responsearray->response->adminaddress1;
	$AdminAddress2=$responsearray->response->adminaddress2;
	$AdminCity=$responsearray->response->admincity;
	$AdminStateProvince=$responsearray->response->adminstate;
	$AdminPostalCode=$responsearray->response->adminpostcode;
	$AdminCountry=$responsearray->response->admincountry;
	$AdminPhone=$responsearray->response->adminphone;
	$AdminFax=$responsearray->response->adminfax;
	
	
	$Techfirstname=$responsearray->response->techFirstName;
	$TechLastName=$responsearray->response->techLastname;
	$TechOrganizationName=$responsearray->response->techFullname;
	$TechJobTitle=$responsearray->response->techjob;
	$TechEmailAddress=$responsearray->response->techemail;
	$TechAddress1=$responsearray->response->techaddress1;
	$TechAddress2=$responsearray->response->techaddress2;
	$TechCity=$responsearray->response->techcity;
	$TechStateProvince=$responsearray->response->techstate;
	$TechPostalCode=$responsearray->response->techpostcode;
	$TechCountry=$responsearray->response->techcountry;
	$TechPhone=$responsearray->response->techphone;
	$TechFax=$responsearray->response->techfax;
	

 
	
		
    if ($codeNode!="1000" ){
       
	  $values["error"]='Error: ' .  $codeNode . ' - ' .  $message;
	 }

	#####################################	
	
	  $values["Registrant"]['First Name'] = $firstname;
      $values["Registrant"]['Last Name'] = $LastName;
      $values["Registrant"]['Organisation Name'] = $OrganizationName;
      $values["Registrant"]['Job Title'] = $JobTitle;
      $values["Registrant"]['Email'] = $EmailAddress;
      $values["Registrant"]['Address 1'] = $Address1;
      $values["Registrant"]['Address 2'] = $Address2;
      $values["Registrant"]['City'] = $City;
      $values["Registrant"]['State'] = $StateProvince;
      $values["Registrant"]['Postcode'] = $PostalCode;
      $values["Registrant"]['Country'] = $Country;
      $values["Registrant"]['Phone'] = $Phone;
      $values["Registrant"]['Fax'] = $Fax;
	  
	  
	  $values["Admin"]['First Name'] = $Adminfirstname;
      $values["Admin"]['Last Name'] = $AdminLastName;
      $values["Admin"]['Organisation Name'] = $AdminOrganizationName;
      $values["Admin"]['Job Title'] = $AdminJobTitle;
      $values["Admin"]['Email'] = $AdminEmailAddress;
      $values["Admin"]['Address 1'] = $AdminAddress1;
      $values["Admin"]['Address 2'] = $AdminAddress2;
      $values["Admin"]['City'] = $AdminCity;
      $values["Admin"]['State'] = $AdminStateProvince;
      $values["Admin"]['Postcode'] = $AdminPostalCode;
      $values["Admin"]['Country'] = $AdminCountry;
      $values["Admin"]['Phone'] = $AdminPhone;
      $values["Admin"]['Fax'] = $AdminFax;
	  
	  
	  $values["Tech"]['First Name'] = $Techfirstname;
      $values["Tech"]['Last Name'] = $TechLastName;
      $values["Tech"]['Organisation Name'] = $TechOrganizationName;
      $values["Tech"]['Job Title'] = $TechJobTitle;
      $values["Tech"]['Email'] = $TechEmailAddress;
      $values["Tech"]['Address 1'] = $TechAddress1;
      $values["Tech"]['Address 2'] = $TechAddress2;
      $values["Tech"]['City'] = $TechCity;
      $values["Tech"]['State'] = $TechStateProvince;
      $values["Tech"]['Postcode'] = $TechPostalCode;
      $values["Tech"]['Country'] = $TechCountry;
      $values["Tech"]['Phone'] = $TechPhone;
      $values["Tech"]['Fax'] = $TechFax;
	
	
	#####################################	
	
	//$values["Registrant"]["First Name"] = $firstname;
	//$values["Registrant"]["Last Name"] = $lastname;
	//$values["Admin"]["First Name"] = $adminfirstname;
	//$values["Admin"]["Last Name"] = $adminlastname;
	//$values["Tech"]["First Name"] = $techfirstname;
	//$values["Tech"]["Last Name"] = $techlastname;
	
	return $values;
}

function papaki_SaveContactDetails($params) { 
	$username ='';
	$password = '';
	$apikey = encodetolatin($params["APIkey"]);
	$posturl = encodetolatin($params["PostUrl"]);
	$testmode = encodetolatin($params["TestMode"]);
	$tld = encodetolatin($params["tld"]);
	$sld = encodetolatin($params["sld"]);
	# Data is returned as specified in the GetContactDetails() function
	
	
	
	$firstname= encodetolatin($_POST['contactdetails']["Registrant"]['First Name']);
	$lastname= encodetolatin($_POST['contactdetails']["Registrant"]['Last Name']);
	$fullname= encodetolatin($_POST['contactdetails']["Registrant"]['Organisation Name']);	
	  $EmailAddress= encodetolatin($_POST['contactdetails']["Registrant"]['Email']);
      $Address1= encodetolatin($_POST['contactdetails']["Registrant"]['Address 1']);
      $Address2=encodetolatin($_POST['contactdetails']["Registrant"]['Address 2']);
      $City=encodetolatin($_POST['contactdetails']["Registrant"]['City']);
      $StateProvince= encodetolatin($_POST['contactdetails']["Registrant"]['State']);
      $PostalCode=encodetolatin($_POST['contactdetails']["Registrant"]['Postcode']);
      $Country=encodetolatin($_POST['contactdetails']["Registrant"]['Country']);
      $Phone=encodetolatin($_POST['contactdetails']["Registrant"]['Phone']);
      $Fax= encodetolatin($_POST['contactdetails']["Registrant"]['Fax']);
	  
	  $adminfirstname= encodetolatin($_POST['contactdetails']["Admin"]['First Name']);
	  $adminlastname= encodetolatin($_POST['contactdetails']["Admin"]['Last Name']);
	  $adminfullname= encodetolatin($_POST['contactdetails']["Admin"]['Organisation Name']);
	  $AdminEmailAddress= encodetolatin($_POST['contactdetails']["Admin"]['Email']);
      $AdminAddress1= encodetolatin($_POST['contactdetails']["Admin"]['Address 1']);
      $AdminAddress2=encodetolatin($_POST['contactdetails']["Admin"]['Address 2']);
      $AdminCity=encodetolatin($_POST['contactdetails']["Admin"]['City']);
      $AdminStateProvince= encodetolatin($_POST['contactdetails']["Admin"]['State']);
      $AdminPostalCode=encodetolatin($_POST['contactdetails']["Admin"]['Postcode']);
      $AdminCountry=encodetolatin($_POST['contactdetails']["Admin"]['Country']);
      $AdminPhone=encodetolatin($_POST['contactdetails']["Admin"]['Phone']);
      $AdminFax= encodetolatin($_POST['contactdetails']["Admin"]['Fax']);
	  
	  
	  $techfirstname= encodetolatin($_POST['contactdetails']["Tech"]['First Name']);
	  $techlastname= encodetolatin($_POST['contactdetails']["Tech"]['Last Name']);
	  $techfullname= encodetolatin($_POST['contactdetails']["Tech"]['Organisation Name']);
	  $TechEmailAddress= encodetolatin($_POST['contactdetails']["Tech"]['Email']);
      $TechAddress1= encodetolatin($_POST['contactdetails']["Tech"]['Address 1']);
      $TechAddress2=encodetolatin($_POST['contactdetails']["Tech"]['Address 2']);
      $TechCity=encodetolatin($_POST['contactdetails']["Tech"]['City']);
      $TechStateProvince= encodetolatin($_POST['contactdetails']["Tech"]['State']);
      $TechPostalCode=encodetolatin($_POST['contactdetails']["Tech"]['Postcode']);
      $TechCountry=encodetolatin($_POST['contactdetails']["Tech"]['Country']);
      $TechPhone=encodetolatin($_POST['contactdetails']["Tech"]['Phone']);
      $TechFax= encodetolatin($_POST['contactdetails']["Tech"]['Fax']);
	  
	 
		
	  if (trim($EmailAddress)=='')
	  $EmailAddress= ' ';
	  if (trim($Address1)=='')
      $Address1=' ';
	  if (trim($Address2)=='')
      $Address2=' ';
	  if (trim($City)=='')
      $City=' ';
	  if (trim($StateProvince)=='')
      $StateProvince= ' ';
	  if (trim($PostalCode)=='')
      $PostalCode=' ';
	  if (trim($Country)=='')
      $Country=' ';
	  if (trim($Phone)=='')
      $Phone=' ';
	  if (trim($Fax)=='')
      $Fax= ' ';
	  if (trim($AdminEmailAddress)=='')
	  $AdminEmailAddress= ' ';
	  if (trim($AdminAddress1)=='')
      $AdminAddress1= ' ';
	  if (trim($AdminAddress2)=='')
      $AdminAddress2=' ';
	  if (trim($AdminCity)=='')
      $AdminCity=' ';
	  if (trim($AdminStateProvince)=='')
      $AdminStateProvince=' ';
	  if (trim($AdminPostalCode)=='')
      $AdminPostalCode=' ';
	  if (trim($AdminCountry)=='')
      $AdminCountry=' ';
	  if (trim($AdminPhone)=='')
      $AdminPhone=' ';
	  if (trim($AdminFax)=='')
      $AdminFax= ' ';
	  if (trim($TechEmailAddress)=='')
	  $TechEmailAddress=' ';
	  if (trim($TechAddress1)=='')
      $TechAddress1= ' ';
	  if (trim($TechAddress2)=='')
      $TechAddress2=' ';
	  if (trim($TechCity)=='')
      $TechCity=' ';
	  if (trim($TechStateProvince)=='')
      $TechStateProvince= ' ';
	  if (trim($TechPostalCode)=='')
      $TechPostalCode=' ';
	  if (trim($TechCountry)=='')
      $TechCountry=' ';
	  if (trim($TechPhone)=='')
      $TechPhone=' ';
	  if (trim($TechFax)=='')
      $TechFax= ' ';
	   if (trim($firstname)=='')
      $firstname= ' ';
	  if (trim($adminfirstname)=='')
      $adminfirstname= ' ';
	  if (trim($techfirstname)=='')
      $techfirstname= ' ';
	  if (trim($lastname)=='')
      $lastname= ' ';
	   if (trim($adminlastname)=='')
      $adminlastname= ' ';
	   if (trim($techlastname)=='')
      $techlastname= ' ';
	   if (trim($fullname)=='')
      $fullname= ' ';
	  if (trim($adminfullname)=='')
      $adminfullname= ' ';
	  if (trim($techfullname)=='')
      $techfullname= ' ';
	  
	  
	  
	  
	 
	  
	  
	  
	 
	
	
	//$adminfirstname = $params["contactdetails"]["Admin"]["First Name"];
//	$adminlastname = $params["contactdetails"]["Admin"]["Last Name"];
//	$techfirstname = $params["contactdetails"]["Tech"]["First Name"];
//	$techlastname = $params["contactdetails"]["Tech"]["Last Name"];
	# Put your code to save new WHOIS data here
		########################
		
	
	
		
	 
	
 
		
		if (isgrdomain($sld.".".$tld) or iseudomain($sld.".".$tld) ){
		$fullname= ' ';
		$techfullname=' ';
		$adminfullname=' ';
		$firstname= ' ';
		$techfirstname=' ';
		$adminfirstname=' ';
		$lastname= ' ';
		$techlastname=' ';
		$adminlastname=' ';
		}
		
		$json = new Services_JSON();
	$jsonarray=array("request"=>array("do"=>'savecontactdetails',"username"=>$username,"password"=>$password,"apiKey"=>$apikey,"domainname"=>$sld.".".$tld,"registration_type"=>'2',"test"=>'False',"firstname"=>$firstname	,"lastname"=>$lastname	,"fullname"=>$fullname	,"emailaddress"=>$EmailAddress	,"address1"=>$Address1	,"address2"=>$Address2	,"city"=>$City	,"stateprovince"=>$StateProvince	,"postalcode"=>$PostalCode	,"country"=>$Country	,"phone"=>$Phone	,"fax"=>$Fax	,"adminfirstname"=>$adminfirstname	,"adminlastname"=>$adminlastname	,"adminfullname"=>$adminfullname	,"adminemailaddress"=>$AdminEmailAddress	,"adminaddress1"=>$AdminAddress1	,"adminaddress2"=>$AdminAddress2	,"admincity"=>$AdminCity	,"adminstateprovince"=>$AdminStateProvince	,"adminpostalcode"=>$AdminPostalCode	,"admincountry"=>$AdminCountry	,"adminphone"=>$AdminPhone	,"adminfax"=>$AdminFax	,"techfirstname"=>$techfirstname	,"techlastname"=>$techlastname	,"techfullname"=>$techfullname	,"techemailaddress"=>$TechEmailAddress	,"techaddress1"=>$TechAddress1	,"techaddress2"=>$TechAddress2	,"techcity"=>$TechCity	,"techstateprovince"=>$TechStateProvince	,"techpostalcode"=>$TechPostalCode	,"techcountry"=>$TechCountry	,"techphone"=>$TechPhone	,"techfax"=>$TechFax));
								  
	$Xpost = $json->encode($jsonarray);
	$Xpost=latintogreek($Xpost);
	$Xpost = str_replace("\n", "", $Xpost);
	$Xpost=str_replace("&",urlencode("&"),$Xpost);  
	
	 
 
	
	$headers = array('Content-type: application/x-www-form-urlencoded');
					 
		$pageContents = HttpClient::quickPost($params["PostUrl"],  array(
    'message' => $Xpost
));	
		$responsearray = $json->decode($pageContents);
		$codeNode = $responsearray->response->code;
		$message = $responsearray->response->message;
		
	 
	
	
	 
	
	
    if ($codeNode!="1000" ){
       
	  $values["error"]='Error: ' .  $codeNode . ' - ' .  $message;
	 }

	#####################################	
	
	
	# If error, return the error message in the value below
	//$values["error"] = $error;
	return $values;
}

function papaki_GetEPPCode($params) {  
    $username ='';
	$password = '';
	$apikey = encodetolatin($params["APIkey"]);
	$posturl = encodetolatin($params["PostUrl"]);
	$testmode = encodetolatin($params["TestMode"]);
	$tld = encodetolatin($params["tld"]);
	$sld = encodetolatin($params["sld"]);
    # Put your code to request the EPP code here - if the API returns it, pass back as below - otherwise return no value and it will assume code is emailed
	########################
	
	
	$json = new Services_JSON();
	$jsonarray=array("request"=>array("do"=>'getauthocode',"username"=>$username,"password"=>$password,"apiKey"=>$apikey,"domainname"=>$sld.".".$tld,"registration_type"=>'2',"test"=>'False'));
	$Xpost = $json->encode($jsonarray);
	$Xpost=latintogreek($Xpost);
	
	
	
	 
	
	$headers = array('Content-type: application/x-www-form-urlencoded');
					 
	$pageContents = HttpClient::quickPost($params["PostUrl"],  array(
    'message' => $Xpost
));	
 
	
		$responsearray = $json->decode($pageContents);
		$codeNode = $responsearray->response->code;
		$message = $responsearray->response->message;
		
		
	 
	$eppcode=$responsearray->response->eppcode;
 
		
    if ($codeNode!="1000" ){
       
	  $values["error"]='Error: ' .  $codeNode . ' - ' .  $message;
	 }

	#####################################	
	
	
    $values["eppcode"] = $eppcode;
    # If error, return the error message in the value below
   // $values["error"] = $error;
    return $values;
}

function papaki_RegisterNameserver($params) { 
    $username ='';
	$password = '';
	$apikey = encodetolatin($params["APIkey"]);
	$posturl = encodetolatin($params["PostUrl"]);
	$testmode = encodetolatin($params["TestMode"]);
	$tld = encodetolatin($params["tld"]);
	$sld = encodetolatin($params["sld"]);
    $nameserver = encodetolatin($params["nameserver"]);
    $ipaddress = encodetolatin($params["ipaddress"]);
    # Put your code to register the nameserver here
	
	########################
	$json = new Services_JSON();
	$jsonarray=array("request"=>array("do"=>'registerns',"username"=>$username,"password"=>$password,"apiKey"=>$apikey,"domainname"=>$sld.".".$tld,"registration_type"=>'2',"test"=>'False',"ns"=>$nameserver,"ip"=>$ipaddress));
	$Xpost = $json->encode($jsonarray);
	$Xpost=latintogreek($Xpost);
	 
	
	$headers = array('Content-type: application/x-www-form-urlencoded');
					 
	$pageContents = HttpClient::quickPost($params["PostUrl"],  array(
    'message' => $Xpost
));	
	
	 
		$responsearray = $json->decode($pageContents);
		$codeNode = $responsearray->response->code;
		$message = $responsearray->response->message;
		
		
	 
	 

		
    if ($codeNode!="1000" ){
       
	  $values["error"]='Error: ' .  $codeNode . ' - ' .  $message;
	 }

	#####################################	
	
    # If error, return the error message in the value below
   // $values["error"] = $error;
    return $values;
}

function papaki_ModifyNameserver($params) { 
    $username ='';
	$password = '';
	$apikey = encodetolatin($params["APIkey"]);
	$posturl = encodetolatin($params["PostUrl"]);
	$testmode = encodetolatin($params["TestMode"]);
	$tld = encodetolatin($params["tld"]);
	$sld = encodetolatin($params["sld"]);
    $nameserver = encodetolatin($params["nameserver"]);
    $currentipaddress = encodetolatin($params["currentipaddress"]);
    $newipaddress = encodetolatin($params["newipaddress"]);
    # Put your code to update the nameserver here
    ########################
	
	$json = new Services_JSON();
	$jsonarray=array("request"=>array("do"=>'modifyns',"username"=>$username,"password"=>$password,"apiKey"=>$apikey,"domainname"=>$sld.".".$tld,"registration_type"=>'2',"test"=>'False',"ns"=>$nameserver,"oldip"=>$currentipaddress,"newip"=>$newipaddress));
	$Xpost = $json->encode($jsonarray);
	$Xpost=latintogreek($Xpost);
 
	
	$headers = array('Content-type: application/x-www-form-urlencoded');
					 
		$pageContents = HttpClient::quickPost($params["PostUrl"],  array(
    'message' => $Xpost
));	
	 
		$responsearray = $json->decode($pageContents);
		$codeNode = $responsearray->response->code;
		$message = $responsearray->response->message;
		
		
	 
	 
		
    if ($codeNode!="1000" ){
       
	  $values["error"]='Error: ' .  $codeNode . ' - ' .  $message;
	 }

	#####################################	
	# If error, return the error message in the value below
    //$values["error"] = $error;
    return $values;
}

function papaki_DeleteNameserver($params) {  
    $username ='';
	$password = '';
	$apikey = encodetolatin($params["APIkey"]);
	$posturl = encodetolatin($params["PostUrl"]);
	$testmode = encodetolatin($params["TestMode"]);
	$tld = encodetolatin($params["tld"]);
	$sld = encodetolatin($params["sld"]);
    $nameserver = encodetolatin($params["nameserver"]);
    # Put your code to delete the nameserver here
	    ########################
		
		$json = new Services_JSON();
	$jsonarray=array("request"=>array("do"=>'deletens',"username"=>$username,"password"=>$password,"apiKey"=>$apikey,"domainname"=>$sld.".".$tld,"registration_type"=>'2',"test"=>'False',"ns"=>$nameserver));
	$Xpost = $json->encode($jsonarray);
 $Xpost=latintogreek($Xpost);
	
	$headers = array('Content-type: application/x-www-form-urlencoded');
					 
		$pageContents = HttpClient::quickPost($params["PostUrl"],  array(
    'message' => $Xpost
));	
		$responsearray = $json->decode($pageContents);
		$codeNode = $responsearray->response->code;
		$message = $responsearray->response->message;
		
		
	 
	 
	 
	

		
   // if ($codeNode!="1000" ){
       
	 // $values["error"]='Error: ' .  $codeNode . ' - ' .  $message;
	 //}
$values["error"]='Error: Not Supported';
	#####################################
    # If error, return the error message in the value below
  //  $values["error"] = $error;
    return $values;
}

function isgrdomain($domainname){

return substr($domainname, strlen('.gr')*-1) == '.gr';
}


function iseudomain($domainname){

return substr($domainname, strlen('.eu')*-1) == '.eu';
}

function encodetolatin($mystring){
	   
	
	 $mystring=str_replace("Α","&Alpha;",$mystring);  
	 $mystring=str_replace("Β","&Beta;",$mystring);  
	 $mystring=str_replace("Γ","&Gamma;",$mystring);  
	 $mystring=str_replace("Δ","&Delta;",$mystring);  
	 $mystring=str_replace("Ε","&Epsilon;",$mystring);  
	 $mystring=str_replace("Ζ","&Zeta;",$mystring);  
	 $mystring=str_replace("Η","&Eta;",$mystring);  
	 $mystring=str_replace("Θ","&Theta;",$mystring);  
	 $mystring=str_replace("Ι","&Iota;",$mystring);  
	 $mystring=str_replace("Κ","&Kappa;",$mystring);  
	 $mystring=str_replace("Λ","&Lambda;",$mystring);  
	 $mystring=str_replace("Μ","&Mu;",$mystring);  
	 $mystring=str_replace("Ν","&Nu;",$mystring);  
	 $mystring=str_replace("Ξ","&Xi;",$mystring);  
	 $mystring=str_replace("Ο","&Omicron;",$mystring);  
	 $mystring=str_replace("Π","&Pi;",$mystring);  
	 $mystring=str_replace("Ρ","&Rho;",$mystring);  
	 $mystring=str_replace("Σ","&Sigma;",$mystring);  
	 $mystring=str_replace("Τ","&Tau;",$mystring);  
	 $mystring=str_replace("Υ","&Upsilon;",$mystring);  
	 $mystring=str_replace("Φ","&Phi;",$mystring);  
	 $mystring=str_replace("Χ","&Chi;",$mystring);  
	 $mystring=str_replace("Ψ","&Psi;",$mystring);  
	 $mystring=str_replace("Ω","&Omega;",$mystring);  
	 $mystring=str_replace("α","&alpha;",$mystring);  
	 $mystring=str_replace("β","&beta;",$mystring);  
	 $mystring=str_replace("γ","&gamma;",$mystring);  
	 $mystring=str_replace("δ","&delta;",$mystring);  
	 $mystring=str_replace("ε","&epsilon;",$mystring);  
	 $mystring=str_replace("ζ","&zeta;",$mystring);  
	 $mystring=str_replace("η","&eta;",$mystring);  
	$mystring=str_replace("θ","&theta;",$mystring);  
	$mystring=str_replace("ι","&iota;",$mystring);  
	$mystring=str_replace("κ","&kappa;",$mystring);  
	$mystring=str_replace("λ","&lambda;",$mystring);  
	$mystring=str_replace("μ","&mu;",$mystring);  
	$mystring=str_replace("ν","&nu;",$mystring);  
	$mystring=str_replace("ξ","&xi;",$mystring);  
	$mystring=str_replace("ο","&omicron;",$mystring);  
	$mystring=str_replace("π","&pi;",$mystring);  
	$mystring=str_replace("ρ","&rho;",$mystring);  
	$mystring=str_replace("σ","&sigma;",$mystring);  
	$mystring=str_replace("τ","&tau;",$mystring);  
	$mystring=str_replace("υ","&upsilon;",$mystring);  
	$mystring=str_replace("φ","&phi;",$mystring);  
	$mystring=str_replace("χ","&chi;",$mystring);  
	$mystring=str_replace("ψ","&psi;",$mystring);  
	$mystring=str_replace("ω","&omega;",$mystring);  
	$mystring=str_replace("ς","&sigmaf;",$mystring); 
	
	          
	              
	$mystring=str_replace("ά","&#940;",$mystring); 
	$mystring=str_replace("έ","&#941;",$mystring); 
	$mystring=str_replace("ώ","&#974;",$mystring); 
	$mystring=str_replace("ύ","&#973;",$mystring); 
	$mystring=str_replace("ί","&#943;",$mystring); 
	$mystring=str_replace("ό","&#972;",$mystring); 
	$mystring=str_replace("ή","&#942;",$mystring); 
	$mystring=str_replace("Ά","&#902;",$mystring); 
	$mystring=str_replace("Έ","&#904;",$mystring); 
	$mystring=str_replace("Ώ","&#911;",$mystring); 
	$mystring=str_replace("Ύ","&#910;",$mystring); 
	$mystring=str_replace("Ί","&#906;",$mystring); 
	$mystring=str_replace("Ό","&#908;",$mystring); 
	$mystring=str_replace("Ή","&#905;",$mystring); 
	
	$mystring=str_replace("ϊ","&#970;",$mystring); 
	$mystring=str_replace("ΐ","&#912;",$mystring); 
	$mystring=str_replace("ϋ","&#971;",$mystring); 
	$mystring=str_replace("ΰ","&#944;",$mystring); 
	return $mystring;
	}
	
	
	
	function latintogreek($mystring){
	   
	
	 $mystring=str_replace("&Alpha;","Α",$mystring);  
	 $mystring=str_replace("&Beta;","Β",$mystring);  
	 $mystring=str_replace("&Gamma;","Γ",$mystring);  
	 $mystring=str_replace("&Delta;","Δ",$mystring);  
	 $mystring=str_replace("&Epsilon;","Ε",$mystring);  
	 $mystring=str_replace("&Zeta;","Ζ",$mystring);  
	 $mystring=str_replace("&Eta;","Η",$mystring);  
	 $mystring=str_replace("&Theta;","Θ",$mystring);  
	 $mystring=str_replace("&Iota;","Ι",$mystring);  
	 $mystring=str_replace("&Kappa;","Κ",$mystring);  
	 $mystring=str_replace("&Lambda;","Λ",$mystring);  
	 $mystring=str_replace("&Mu;","Μ",$mystring);  
	 $mystring=str_replace("&Nu;","Ν",$mystring);  
	 $mystring=str_replace("&Xi;","Ξ",$mystring);  
	 $mystring=str_replace("&Omicron;","Ο",$mystring);  
	 $mystring=str_replace("&Pi;","Π",$mystring);  
	 $mystring=str_replace("&Rho;","Ρ",$mystring);  
	 $mystring=str_replace("&Sigma;","Σ",$mystring);  
	 $mystring=str_replace("&Tau;","Τ",$mystring);  
	 $mystring=str_replace("&Upsilon;","Υ",$mystring);  
	 $mystring=str_replace("&Phi;","Φ",$mystring);  
	 $mystring=str_replace("&Chi;","Χ",$mystring);  
	 $mystring=str_replace("&Psi;","Ψ",$mystring);  
	 $mystring=str_replace("&Omega;","Ω",$mystring);  
	 $mystring=str_replace("&alpha;","α",$mystring);  
	 $mystring=str_replace("&beta;","β",$mystring);  
	 $mystring=str_replace("&gamma;","γ",$mystring);  
	 $mystring=str_replace("&delta;","δ",$mystring);  
	 $mystring=str_replace("&epsilon;","ε",$mystring);  
	 $mystring=str_replace("&zeta;","ζ",$mystring);  
	 $mystring=str_replace("&eta;","η",$mystring);  
	$mystring=str_replace("&theta;","θ",$mystring);  
	$mystring=str_replace("&iota;","ι",$mystring);  
	$mystring=str_replace("&kappa;","κ",$mystring);  
	$mystring=str_replace("&lambda;","λ",$mystring);  
	$mystring=str_replace("&mu;","μ",$mystring);  
	$mystring=str_replace("&nu;","ν",$mystring);  
	$mystring=str_replace("&xi;","ξ",$mystring);  
	$mystring=str_replace("&omicron;","ο",$mystring);  
	$mystring=str_replace("&pi;","π",$mystring);  
	$mystring=str_replace("&rho;","ρ",$mystring);  
	$mystring=str_replace("&sigma;","σ",$mystring);  
	$mystring=str_replace("&tau;","τ",$mystring);  
	$mystring=str_replace("&upsilon;","υ",$mystring);  
	$mystring=str_replace("&phi;","φ",$mystring);  
	$mystring=str_replace("&chi;","χ",$mystring);  
	$mystring=str_replace("&psi;","ψ",$mystring);  
	$mystring=str_replace("&omega;","ω",$mystring);  
	$mystring=str_replace("&sigmaf;","ς",$mystring); 
	
	          
	              
	$mystring=str_replace("&#940;","ά",$mystring); 
	$mystring=str_replace("&#941;","έ",$mystring); 
	$mystring=str_replace("&#974;","ώ",$mystring); 
	$mystring=str_replace("&#973;","ύ",$mystring); 
	$mystring=str_replace("&#943;","ί",$mystring); 
	$mystring=str_replace("&#972;","ό",$mystring); 
	$mystring=str_replace("&#942;","ή",$mystring); 
	$mystring=str_replace("&#902;","Ά",$mystring); 
	$mystring=str_replace("&#904;","Έ",$mystring); 
	$mystring=str_replace("&#911;","Ώ",$mystring); 
	$mystring=str_replace("&#910;","Ύ",$mystring); 
	$mystring=str_replace("&#906;","Ί",$mystring); 
	$mystring=str_replace("&#908;","Ό",$mystring); 
	$mystring=str_replace("&#905;","Ή",$mystring); 
	
	$mystring=str_replace("&#970;","ϊ",$mystring); 
	$mystring=str_replace("&#912;","ΐ",$mystring); 
	$mystring=str_replace("&#971;","ϋ",$mystring); 
	$mystring=str_replace("&#944;","ΰ",$mystring); 
	return $mystring;
	}

?>
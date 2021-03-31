<?php

//require ('libs/HttpClient.class.php');
require 'HttpClient.class.php';
require_once 'json.php';
require_once 'papaki.php';

if(!class_exists('idna_convert')) {
    require_once 'idna_convert.class.php';
}

/****************************************************************************************
 *                             UsableWeb Domain Name Search
 *---------------------------------------------------------------------------------------*
 *              This is a Domain Name Search provided by Usableweb.
 * Requirments
 * In order to work this class you need the following:
 * - web server with PHP enabled    (Apache, IIS, Sambar etc)
 * - PHP4 >= 4.3.3 (it may works and with earlier versions but with no gurantee )
 * - DOM XML extension enabled
 * - CURL (Client URL) extension enabled [only if you have to use clientXML() method] by default it's not needed]
 *---------------------------------------------------------------------------------------*
 * How it works (quick reference):
 * Declare the following properties as in the "reply.php"
 * $ClassName->checkBoxPrefix - Is the prefix that should be given at the extensions
 * checkBoxes ex: ext_gr (for .gr) or ext_com (for .com.gr). (default = ext_)
 * $ClassName->password- Is the password that provided by usableweb for this service
 * $ClassName->username- Is the username that provided by usableweb for this service
 * (notice that the validation checks your server's IP address)
 * $ClassName->domainName - is the domain name we searching for.
 *---------------------------------------------------------------------------------------*
 * Methods of the class:
 * $ClassName->buildRequestXML([request_type]) - returns the appropiate XMLstring - [request_type] optional default value = _TYPE_DS(constant for domainSearch)
 * $ClassName->grubResponse() - Send the XML and returns the response [default method]
 * $ClassName->clientXML() - Send the XML and returns the response [on demand if $ClassName->use_curl = true; then u r using this method instead $ClassName->grubResponse()
 * [0] $ClassName->arrayAvDomains - Contais the available domain names
 * [1] $ClassName->arrayNotAvDomains - Contains the not available domain names
 * [2] $ClassName->whois_response - the reply if u have to perform a whois search.
 *---------------------------------------------------------------------------------------*
 *                     Thats All folks! Enjoy!
 *****************************************************************************************/

define("_TYPE_DS", "domainSearch");
define("_TYPE_WHOIS", "whois");

class PapakiDomainNameSearch
{
    //Property declaration
    public $requestURL;
    public $checkBoxPrefix;
    public $password;
    public $username;
    public $apikey;
    public $lang;
    public $type;
    public $test;
    public $domainName;
    public $extensions; //seperated by commas.
    public $requestXML;
    public $responseXML;
    public $responsearray;

    public $version = '1.2-Active Net';

    public $use_curl; //There are two method for sending the request and grubing the response by default the class using 'grabResponse()' method if u want to usa 'clientXML()' method set this variable to true.

    public $arrayAvDomains;
    public $arrayNotAvDomains;
    public $whois_response;
    public $tld = '';
    public $hasError = false;
    public $errorMessage = '';
    public $IDN;

    public function __construct($domainName, $ext, $lang = "el", $test = "False")
    {
        $this->IDN = new idna_convert(array('idn_version' => 2008));
        $this->use_curl = false;
        $this->requestURL = "http://api.papaki.com/register_url2.aspx";
        $this->checkBoxPrefix = $ext;
        $this->lang = $lang;
        //$this->type = $type;
        $this->test = $test;
        $this->domainName = $domainName;
        $this->arrayAvDomains = array();
        $this->arrayNotAvDomains = array();
        $this->tld = $ext;
    }
    //Takes 2 optional arguments $type: is the type of the request, we want to perform - domain name search or whois search
    //$use_get_extenssions_func:(works only with $type = _TYPE_DS) boolean if we use the '$this->getExtensions()' function or passes the
    //extension by the $this->extensions property
    public function exec_request_for($type = _TYPE_DS, $use_get_extenssions_func = true)
    {
        $this->type = $type;

        //$exts=array('.'.$this->tld);

        $json = new Services_JSON();
        $jsonarray = array("request" => array(
            "type" => $type,
            "apiKey" => encodetolatin($this->apikey),
            "username" => '',
            "password" => '',
            "domain" => encodetolatin($this->domainName),
            "lang" => 'el',
            "test" => 'False',
            'externalCall' => 'True',
            "extensions" => array(
                "ext" => array('.' . $this->tld),
            ),
        ),
        );
        if ($this->type == _TYPE_WHOIS) {
            $jsonarray = array("request" => array("type" => $type, "apiKey" => encodetolatin($this->apikey), "username" => '', "password" => '', "domain" => encodetolatin($this->domainName), "lang" => 'el', "test" => 'False'));

        }
        //print_r($jsonarray);
        $Xpost = $json->encode($jsonarray);
        $Xpost = latintogreek($Xpost);
        $headers = array('Content-type: application/x-www-form-urlencoded');
        //print '<pre>'.$Xpost.'<br /><br />'.'</pre>';
        $pageContents = HttpClient::quickPost($this->requestURL, array('message' => $Xpost));
        //print '<pre>'.$pageContents.'<br /><br />'.'</pre>';

        $this->responsearray = $json->decode($pageContents);
        //print_r($this->responsearray );
        $this->parseResponse();
        $this->domainName = $this->IDN->decode($this->domainName);
    }

    public function grabResponse()
    {
        $this->responseXML = "";
        $Xpost = $this->requestXML;
        $url = $this->requestURL . $Xpost;
        if (!$fp = fopen(trim($url), 'r')) {
            //mail("debug@papaki.gr","UDNS Error", $this->requestXML."\n".$this->responseXML,"From:info@papaki.gr");
        }
        while (!feof($fp)) {
            $this->responseXML .= fread($fp, 1024);
        }

        $this->parseResponse();
    }

    public function parseResponse($executed = true)
    {
        $codeNode = $this->responsearray->response->code;
        if ($codeNode != 1000) {
            $message = $this->responsearray->response->message;
            $this->hasError = true;
            $this->errorMessage = $message;
            return;
        }
        //print 'fdgdfsgfgsd';
        //print_r($this->responsearray);
        if ($this->type == _TYPE_DS) {
            if (isset($this->responsearray->response->availableDomains)) {
                $avDomains = json_decode('[' . $this->responsearray->response->availableDomains . ']');
                //print_r($avDomains);
                if (is_array($avDomains)) {
                    //print_r($avDomains);
                    foreach ($avDomains as $k => $v) {
                        $this->arrayAvDomains[] = $this->IDN->decode($v->domain);
                    }
                    /*for($i=0;$i < sizeof($avDomains);$i++){
                array_push($this->arrayAvDomains, $this->responsearray->response->code->availableDomains[$i]);
                }*/
                }
            }
            if (isset($this->responsearray->response->notAvailableDomains)) {
                $notAvDomains = json_decode('[' . $this->responsearray->response->notAvailableDomains . ']');
                if (is_array($notAvDomains)) {

                    foreach ($notAvDomains as $k => $v) {
                        $this->arrayNotAvDomains[] = $this->IDN->decode($v->domain);
                    }
                    /*for($i=0;$i < sizeof($notAvDomains);$i++){
                $reason = $this->responsearray->response->code->notAvailableDomains->reason;

                }*/
                }
            }
        } elseif ($this->type = _TYPE_WHOIS) {
            $body = $this->responsearray->response->whoisReply;
            $body = str_replace('&lt;![CDATA[', '', $body);
            $body = str_replace('<![CDATA[', '', $body);
            $body = str_replace(']]&gt;', '', $body);
            $body = str_replace(']]>;', '', $body);
            $body = str_replace('&lt;', '<', $body);
            $body = str_replace('&gt;', '>', $body);

            $this->whois_response = $this->StripHTML($body);

            return $this->whois_response;
        }
    }

    public function fix_spaces($str)
    {
        $s = split('[/,\]', $str);
        //print_r($s);
        for ($i = 0; $i <= count($s) - 1; $i++) {
            $return .= trim($s[$i]) . ",";
        }
        return substr($return, 0, strlen($return) - 1);
    }

    public function GrantAccess($pass)
    {
        if ($pass == $this->password) {
            return true;
        } else {
            return false;
        }
    }

    public function StripHTML($str)
    {
        $openTag = "false";
        for ($i = 0; $i < strlen($str); $i++) {
            if (substr($str, $i, 1) == "<") {
                $openTag = "true";
            } elseif (substr($str, $i, 1) == ">") {
                $openTag = "false";
            }
            if ($openTag !== "true" && substr($str, $i, 1) !== ">") {
                $return .= substr($str, $i, 1);
            }

        }
        $return = str_replace("\n", "<br />", $return);
        return $return;
    }
}

//if ($_GET['debug'] == 'true' && $_GET['version'] == 'true'){
//    $udns = new PapakiDomainNameSearch('');
//    echo ('<b>Class version:</b> ' . $udns->version);
//}

<?php
/**
 * Plugin Name: Papaki Domain Registration
 * Plugin URI: http://www.papaki.gr
 * Description: Plugin for domain search and registration using the Papaki Reseller API
 * Version: 2.1
 * Author: Papaki
 * License: Copyright (C) Papaki.gr - All Rights Reserved
 */
defined('ABSPATH') or die("");
require( plugin_dir_path(__FILE__) ."lib/usablewebLib.php"); 
$initial_tlds=array('gr','eu','com','net','org','info','mobi','com.gr','net.gr','edu.gr','org.gr','gov.gr','la','name','cc','ac','io','sh','tv','bz','ws','de','ms','gs','in','fm');
function papaki_domain_reg_init() {
    load_plugin_textdomain('papaki-domain-registration', false, dirname(plugin_basename(__FILE__)).'/languages/');
}
add_action('plugins_loaded', 'papaki_domain_reg_init');
if ( is_admin() ){
    add_action( 'admin_menu', 'papaki_domain_reg_menu' );
    add_action( 'admin_notices', 'papaki_domain_reg_admin_notices' );
    add_action( 'admin_init', 'register_papaki_domain_reg' );
}
function papaki_domain_reg_admin_notices() {
    $opt=get_option( 'api_key');
    if( empty($opt) ){
        echo "<div id='notice' class='updated fade'><p>".__('Papaki Domain Registration is not configured yet. Your API key is required.', 'papaki-domain-registration')."</p></div>\n";
    }
}


/** Step 1. */
function papaki_domain_reg_menu() {
    //add_options_page( 'Papaki Domain Registration Options', 'Domain Registration', 'manage_options', 'papaki-domain-registration.php', 'papaki_domain_reg_options' );
    $hook_suffix = add_options_page( __('Papaki Domain Registration Options','papaki-domain-registration'), __('Domain Registration','papaki-domain-registration'), 'manage_options', 'papaki-domain-registration', 
    'papaki_domain_reg_options');
    add_action( 'load-' . $hook_suffix , 'papaki_domain_reg_load_function' );
}
function papaki_domain_reg_load_function() {
    remove_action( 'admin_notices', 'papaki_domain_reg_admin_notices' );
}


function papaki_domain_reg_options() {
    global $initial_tlds;
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    echo '<div class="wrap">';
    echo '<h2>'.__('Papaki Domain Registration','papaki-domain-registration').'</h2>';
    echo '<form method="post" action="options.php"> ';
    settings_fields( 'papaki_domain_reg-group' );
    do_settings_sections( 'papaki-domain-registration' );
    submit_button();
    echo '</form>';
   /* echo '<form method="post" action="options.php"> ';   
    settings_fields( 'papaki_domain_reg-group1' );
    do_settings_sections( 'papaki-domain-registration' );
    submit_button();
    echo '</form>';*/
    echo '</div>';
}
function register_papaki_domain_reg() { // whitelist options
  register_setting( 'papaki_domain_reg-group', 'api_key' ,'papaki_domain_reg_settings_validation');
  register_setting( 'papaki_domain_reg-group', 'papaki_domain_reg_admin_email' );
  register_setting( 'papaki_domain_reg-group', 'allowed_tlds' );
  register_setting( 'papaki_domain_reg-group', 'papaki_domain_reg_search_title' );
  register_setting( 'papaki_domain_reg-group', 'papaki_domain_reg_domain_label' );
  register_setting( 'papaki_domain_reg-group', 'papaki_domain_reg_button_label' );
  register_setting( 'papaki_domain_reg-group', 'papaki_domain_reg_results_heading' );
  register_setting( 'papaki_domain_reg-group', 'papaki_domain_reg_results_button' );
  register_setting( 'papaki_domain_reg-group', 'papaki_domain_reg_page_title' );
  register_setting( 'papaki_domain_reg-group', 'papaki_domain_reg_page_btn' );
  register_setting( 'papaki_domain_reg-group', 'papaki_domain_reg_page_success' );
  register_setting( 'papaki_domain_reg-group', 'papaki_domain_reg_page_fail' );
  
  add_settings_section( 'section-one', __('General Settings','papaki-domain-registration'), 'section_one_callback', 'papaki-domain-registration' );
  add_settings_field( 'field-one', __('Papaki Reseller API Key','papaki-domain-registration'), 'field_one_callback', 'papaki-domain-registration', 'section-one' );
  add_settings_field( 'field-two', __('Papaki Admin Email','papaki-domain-registration'), 'field_two_callback', 'papaki-domain-registration', 'section-one' );
  add_settings_field( 'field-three', __('Allowed Tlds','papaki-domain-registration'), 'field_3_callback', 'papaki-domain-registration', 'section-one' );
  
  add_settings_section( 'section-two', __('Search Page','papaki-domain-registration'), 'section_one_callback', 'papaki-domain-registration' );
  add_settings_field( 'field-search-title', __('Page Heading','papaki-domain-registration'), 'papaki_domain_reg_search_title_callback', 'papaki-domain-registration', 'section-two' );
  add_settings_field( 'field-domain-label', __('Domain Label','papaki-domain-registration'), 'papaki_domain_reg_domain_label_callback', 'papaki-domain-registration', 'section-two' );
  add_settings_field( 'field-button-label', __('Button Label','papaki-domain-registration'), 'papaki_domain_reg_button_label_callback', 'papaki-domain-registration', 'section-two' );
  add_settings_field( 'field-results-heading', __('Results Heading','papaki-domain-registration'), 'papaki_domain_reg_results_heading_callback', 'papaki-domain-registration', 'section-two' );
  add_settings_field( 'field-results-button', __('Results Button','papaki-domain-registration'), 'papaki_domain_reg_results_button_callback', 'papaki-domain-registration', 'section-two' );
  
  add_settings_section( 'section-three', __('Registration Page','papaki-domain-registration'), 'section_one_callback', 'papaki-domain-registration' );
  add_settings_field( 'field-reg-page-title', __('Page Heading','papaki-domain-registration'), 'papaki_domain_reg_page_title_callback', 'papaki-domain-registration', 'section-three' );
  add_settings_field( 'field-reg-page-btn', __('Continue Button','papaki-domain-registration'), 'papaki_domain_reg_page_btn_callback', 'papaki-domain-registration', 'section-three' );
  add_settings_field( 'field-reg-page-success', __('Sucess Message','papaki-domain-registration'), 'papaki_domain_reg_page_success_callback', 'papaki-domain-registration', 'section-three' );
  add_settings_field( 'field-reg-page-fail', __('Failure Message','papaki-domain-registration'), 'papaki_domain_reg_page_fail_callback', 'papaki-domain-registration', 'section-three' );
}
function section_one_callback() {
    //echo 'Some help text goes here.';
}
function field_3_callback() {
    global $initial_tlds;
    $allowed_tlds =  get_option( 'allowed_tlds',array() );
    if($allowed_tlds==''){
        $allowed_tlds=array();
    }
    //echo "<input size='100' type='text' name='api_key' value='$setting' />";
    if(!is_array($allowed_tlds) && $allowed_tlds!=''){
        $allowed_tlds=explode(',',$allowed_tlds);
    }
    echo '<div  class="extensions">';
     foreach($initial_tlds as $tld){
        print '<span><label><input type="checkbox" name="allowed_tlds[]" value="'.$tld.'" ';
        if(in_array($tld,$allowed_tlds)){
            print 'checked="checked" ';
        }
         print '/>'.strtoupper($tld).'</label></span>';
    }
    echo '</div>';
}
function field_one_callback() {
    $setting = esc_attr( get_option( 'api_key' ) );
    echo "<input required size='100' type='text' name='api_key' value='$setting' />";
}
function field_two_callback() {
    $setting = esc_attr( get_option( 'papaki_domain_reg_admin_email' ) );
    echo "<input required size='40' maxlength='255' type='email' name='papaki_domain_reg_admin_email' value='$setting' />";
}
function papaki_domain_reg_settings_validation($input){
     $output = get_option( 'api_key' );

    if ( !empty( $input) )
        $output = $input;
    else
        add_settings_error( 'papaki_domain_reg-group', 'invalid-api-key',  __('The API Key cannot be empty.','papaki-domain-registration') );
    //die(print_r($input,1));
    return $output;
}
function papaki_domain_reg_search_title_callback(){
    $setting = esc_attr( get_option( 'papaki_domain_reg_search_title' ,'Papaki Domains Registration' ));       
    echo "<input size='40' maxlength='255' type='text' name='papaki_domain_reg_search_title' value='$setting' />";
}
function papaki_domain_reg_domain_label_callback(){
    $setting = esc_attr( get_option( 'papaki_domain_reg_domain_label' ,'Domain') );       
    if($setting=='') $setting='Domain';
    echo "<input size='20' maxlength='255' type='text' name='papaki_domain_reg_domain_label' value='$setting' />";
}
function papaki_domain_reg_button_label_callback(){
    $setting = esc_attr( get_option( 'papaki_domain_reg_button_label','Search'  ));       
    if($setting=='') $setting='Search';
    echo "<input size='20' maxlength='255' type='text' name='papaki_domain_reg_button_label' value='$setting' />";
}
function papaki_domain_reg_results_heading_callback(){
    $setting = esc_attr( get_option( 'papaki_domain_reg_results_heading','Availability Results' ) );       
    if($setting=='') $setting='Availability Results';
    echo "<input size='20' maxlength='255' type='text' name='papaki_domain_reg_results_heading' value='$setting' />";
}
function papaki_domain_reg_results_button_callback(){
    $setting = esc_attr( get_option( 'papaki_domain_reg_results_button' ,'Register' ));       
    if($setting=='') $setting='Register';
    echo "<input size='20' maxlength='255' type='text' name='papaki_domain_reg_results_button' value='$setting' />";
}
function papaki_domain_reg_page_title_callback(){
    $setting = esc_attr( get_option( 'papaki_domain_reg_page_title','Domain Names' ) );       
    if($setting=='') $setting='Domain Names';
    echo "<input size='20' maxlength='255' type='text' name='papaki_domain_reg_page_title' value='$setting' />";
}
function papaki_domain_reg_page_btn_callback(){
    $setting = esc_attr( get_option( 'papaki_domain_reg_page_btn','Continue' ) );       
    if($setting=='') $setting='Continue';
    echo "<input size='20' maxlength='255' type='text' name='papaki_domain_reg_page_btn' value='$setting' />";
}
function papaki_domain_reg_page_success_callback(){
    $setting = esc_attr( get_option( 'papaki_domain_reg_page_success','Your registration has been sent. Our representatives will soon reply to you.' ) );       
    if($setting=='') $setting='Your registration has been sent. Our representatives will soon reply to you.';
    echo "<input size='100' type='text' name='papaki_domain_reg_page_success' value='$setting' />";
}
function papaki_domain_reg_page_fail_callback(){
    $setting = esc_attr( get_option( 'papaki_domain_reg_page_fail','An error occured. Please contact our support department.' ) );       
    if($setting=='') $setting='An error occured. Please contact our support department.';
    echo "<input size='100'type='text' name='papaki_domain_reg_page_fail' value='$setting' />";
}
function pdr_load_scripts() {
 
    wp_enqueue_script('pdr-script', plugin_dir_url( __FILE__ ) . 'includes/scripts.js');
    wp_localize_script('pdr-script', 'pdr_script_vars', array(
        'admin_ajax_url' =>admin_url( 'admin-ajax.php' ),
        'nonce_searchdomains'=> wp_create_nonce( 'searchdomains' ),
        'error_msg'=>__('Search Error','papaki-domain-registration'),
        'path'=>plugins_url( 'papaki-domain-registration'),
        'search_tlds'=>get_option( 'allowed_tlds' ),
        'msg_avail'=>__('Available!','papaki-domain-registration'),
        'msg_taken'=>__('Not available for registration','papaki-domain-registration'),
        'form_error_companyname'=>__('You have not completed your fullname.','papaki-domain-registration'),
        'form_error_firstname'=>__('You have not completed your first name','papaki-domain-registration'),
        'form_error_lastname'=>__('You have not completed your last name','papaki-domain-registration'),
        'form_error_phone'=>__('You have not completed your phone','papaki-domain-registration'),
        'form_error_email'=>__('You have not completed your email','papaki-domain-registration'),
        'form_error_email2'=>__('Please enter a correct Email','papaki-domain-registration'),
        'form_error_address'=>__('You have not completed your address','papaki-domain-registration'),
        'form_error_phone2'=>__('Must contain a valid phone number','papaki-domain-registration'),
        'form_error_fax'=>__('Must contain a valid number fax','papaki-domain-registration'),
        'form_error_city'=>__('You have not completed your city','papaki-domain-registration'),
        'form_error_zip'=>__('You have not completed your zipcode','papaki-domain-registration')
        ));
    wp_register_style( 'pdr-styles', plugins_url( 'papaki-domain-registration/includes/styles.css' ) );
    wp_enqueue_style( 'pdr-styles' );
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', '//code.jquery.com/jquery-1.11.3.min.js');
    wp_enqueue_script('jquery');
    wp_register_script( 'jquery-migrate', '//code.jquery.com/jquery-migrate-1.2.1.min.js');
    wp_enqueue_script('jquery-migrate');
    
}
add_action('wp_enqueue_scripts', 'pdr_load_scripts');
add_shortcode( "domain_registration", "the_domain_page" );
function the_domain_page($atts ){
    $step=isset($_POST['step'])?$_POST['step']:'search';
    if($step=='search'){
        $html='<div id="papakidomains_search">';
        $html.='<h1>'.__(get_option( 'papaki_domain_reg_search_title' ,'Papaki Domains Registration'),'papaki-domain-registration').'</h1>';
        $html.='<div style="">
              <label><strong style="font-size:16px;">'.__(get_option( 'papaki_domain_reg_domain_label','Domain'),'papaki-domain-registration').':</strong>
                
              </label>
              <input type="text" name="domain" id="domain" value="www.'.(isset($_GET['domain'])?str_replace('www.','',$_GET['domain']):'').'"/>
              <select name="tld" id="tld">
              ';
              $allowed_tlds =  get_option( 'allowed_tlds' );
              if(is_array($allowed_tlds)){
                  foreach($allowed_tlds as $tld){
                      $html.='<option value="'.$tld.'"';
                      if(isset($_GET['tld']) && $_GET['tld']==$tld){
                          $html.=' selected="selected" ';
                      }
                      $html.='> .'.strtoupper($tld).' </option>';
                  }
              }
              $html.='
                </select>
              <button type="button" id="search_btn" onclick="Search()">'.__(get_option( 'papaki_domain_reg_button_label','Search'),'papaki-domain-registration').'</button>
            <img src="' . plugins_url( 'img/ajax-loader.gif', __FILE__ ) . '" style=" visibility:hidden" alt="Loading" id="loading" />
            </div>';
        if(isset($_GET['search']) && $_GET['search']=='true'){
            $html.='<script type="text/javascript">
                jQuery(document).ready(function() {
                      Search();
                });
            </script>';
        }
        $html.='<hr />';
        $html.=' <form  id="add_domains" action="'.get_home_url(null,'domains').'" method="post" style="display:none">
        <h2>'.__(get_option( 'papaki_domain_reg_results_heading' ,'Availability Results'),'papaki-domain-registration').'</h2>
        <ul class="available_tlds">
        </ul>
        <input id="pd-submit" style="display:none" type="submit" value="'.__(get_option( 'papaki_domain_reg_results_button' ,'Register'),'papaki-domain-registration').'" />';
        $html.='<input type="hidden" name="step" value="register"/>  </form>';
        $html.='</div>';
    }
    elseif($step=='register'){
        $html.= '<div id="domain-registration-form">';
        $html.='<h1>'.__(get_option( 'papaki_domain_reg_search_title' ,'Papaki Domains Registration'),'papaki-domain-registration').'</h1>';
        $domains=$_POST['domains'];
        if(empty($domains)){
            $html.= '<div class="error">'.__('You haven\'t selected any domains for registration. Please go back and choose the domains that you wish to register.','papaki-domain-registration').'</div>';
        }
        else{
            $form_html=FixFormHtml(file_get_contents(plugin_dir_path(__FILE__) .'includes/form.tpl.htm'),$domains);
            $html.=$form_html;
        }
        $html.= '</div>';
    }
    elseif($step=='checkout'){
        $html.= '<div id="domain-registration-form" class="checkout">';
        $html.='<h1>'.__(get_option( 'papaki_domain_reg_search_title' ,'Papaki Domains Registration'),'papaki-domain-registration').'</h1>';
        if(SendRegistration($_POST)){
            $html.='<h3>'.__(get_option( 'papaki_domain_reg_page_success','Your registration has been sent. Our representatives will soon reply to you.' ) ,'papaki-domain-registration').'</h3>';
        }
        else{
            $html.='<h3>'.__(get_option( 'papaki_domain_reg_page_fail','An error occured. Please contact our support department.' ),'papaki-domain-registration').'</h3>';
        }    
        $html.= '</div>';
    }
    return $html;
}
function SendRegistration($data){
    $admin_email = get_option('papaki_domain_reg_admin_email');
    $domainNames=$data['domainNames'];
    $subject = "Domain Request";
    $fullname  = $data['fullname'];
    $firstname = $data['firstname'];
    $lastname  = $data['lastname'];
    $emailText = $data['emailText'];
    $postcode  = $data['postcode'];
    $address1  = $data['address1'];
    $phoneNum = $data['phoneNum'];
    $mobile = $data['mobile'];
    $fax = $data['fax'];
    $stateProvince = $data['stateProvince'];
    $city = $data['city'];
    $country = $data['country'];
     
    $text = "<html><body>
    
    <table align=\"center\" border=\"0\" cellspacing=\"0\" width=\"100%\">
                                                            <tbody>
                                                         
                                                              <tr>
                                                                <td>
                                                                <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"1\" cellspacing=\"1\" bgcolor=\"#FFFFFF\" style=\"margin:1px \">";
    
    
    
       
    $text = $text."<tr align=\"left\">
      <td width=\"40%\" align=\"right\" valign=\"top\" class=\"medgray\"><div align=\"left\">&nbsp;Επωνυμία Εταιρίας/Φορέα - ή - Το Ονοματεπώνυμο σας : </div></td>
      <td valign=\"middle\">".$fullname."</td>
    </tr>
    <tr align=\"left\">
      <td width=\"40%\" align=\"right\" class=\"medgray\"><div align=\"left\">&nbsp;Όνομα : </div></td>
      <td><label>".$firstname."</label></td>
    </tr>
    <tr align=\"left\">
      <td align=\"right\"  width=\"40%\"><div align=\"left\">&nbsp;Επίθετο : </div></td>
      <td>".$lastname."</td>
    </tr>";
    
    
    $text = $text."<tr align=\"left\">
      <td align=\"right\" ><div align=\"left\">&nbsp;Email : </div></td>
      <td><label>".$emailText."</label></td>
    </tr>
    <tr align=\"left\">
      <td align=\"right\" class=\"medgray\"><div align=\"left\">&nbsp;Διεύθυνση : </div></td>
      <td><label>".$address1."</label></td>
    </tr>
    <tr align=\"left\">
      <td align=\"right\" width=\"40%\"><div align=\"left\">&nbsp;Περιοχή :</div></td>
      <td>".$stateProvince."</td>
    </tr>
    <tr align=\"left\">
      <td align=\"right\" class=\"medgray\"><div align=\"left\">&nbsp;Πόλη/Χωριό :</div></td>
      <td>". $city."</td>
    </tr>
    <tr align=\"left\">
      <td align=\"right\" class=\"medgray\"><div align=\"left\">&nbsp;Ταχ.Κώδικας : </div></td>
      <td>". $postcode."</td>
    </tr>
    <tr align=\"left\">
      <td align=\"right\" class=\"medgray\"><div align=\"left\">&nbsp;Χώρα :</div></td>
      <td class=\"style15\">".$country."</td>
    </tr>
    <tr align=\"left\">
      <td align=\"right\" class=\"medgray\"><div align=\"left\">&nbsp;Τηλέφωνο :</div></td>
      <td><label>".$phoneNum."</label></td>
    </tr>";
    if(strlen($mobile) != 0) {
    $text = $text."<tr align=\"left\">
      <td align=\"right\" ><div align=\"left\">&nbsp;Κινητό (Προαιρετικό): </div></td>
      <td>".$mobile."</td>
    </tr>";
    } 
    if(strlen($fax) != 0){
        $text = $text."<tr align=\"left\">
      <td align=\"right\"><div align=\"left\">&nbsp;Fax (Προαιρετικό): </div></td>
      <td>".$fax."</td>
    </tr>";
     }
    $text = $text."
        <tr align=\"left\">
          <td colspan=\"2\">&nbsp;</td>
        </tr>
      
      </table></td>
                                                              </tr>
                            
    <tr><td colspan=\"2\">επιθυμεί να κατοχυρώσει τα παρακάτω ονόματα χώρου </td></tr>                                                       <tr><td colspan=\"2\">".$domainNames."</td></tr> 
                                                        </tbody>
                                                    </table></body></html>";
                                                    
    $headers = "From: $fullname <$emailText>" . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    return wp_mail( $admin_email, 'Domain Request', $text, $headers);
}
function FixFormHtml($content,$domains){
    $replacements=array(
        '_FORM_HEADER_'=>__('Domain Names','papaki-domain-registration'),
        '_FORM_INSTRUCTIONS_'=>__('Complete details of the person or company that manages <b>  the domain name(s)</b> and then press <b> CONTINUE </b> at the bottom of the page. <strong> It is important the information you provide is accurate. </strong> <BR>   The data you provide during registration is strictly private and used only by our company and the domain name Registry. <br> in no case will not be disclosed or used by others.','papaki-domain-registration'),
        '_domainNames_'=>__(implode(',',$domains),'papaki-domain-registration'),
        '_FORM_COMPANYNAME_'=>__('Company Name / Organization - or - <br /> Your Name','papaki-domain-registration'),
        '_FORM_COMPANY_NAME_INFO_'=>__('It is important to provide your full name or full company name. Otherwise the domain name can not be adopted by the EU','papaki-domain-registration'),
        '_FORM_FIRSTNAME_'=>__('First Name','papaki-domain-registration'),
        '_FORM_LASTNAME_'=>__('Last Name','papaki-domain-registration'),
        '_FORM_EMAIL_'=>__('Email','papaki-domain-registration'),
        '_FORM_EMAILINFO_'=>__('Example: <u> name@yahoo.gr </u> You must enter a correct email address as this email will send you a reminder expired registration and other important information. ','papaki-domain-registration'),
        '_FORM_ADDRESS_'=>__('Address','papaki-domain-registration'),
        '_FORM_AREA_'=>__('Area','papaki-domain-registration'),
        '_FORM_CITY_'=>__('City / Town','papaki-domain-registration'),
        '_FORM_ZIP_'=>__('Zip Code','papaki-domain-registration'),
        '_FORM_COUNTRY_'=>__('Country','papaki-domain-registration'),
        '_FORM_PHONE_'=>__('Phone','papaki-domain-registration'),
        '_FORM_EG_'=>__('eg','papaki-domain-registration'),
        '_FORM_MOBILE_'=>__('Phone (Optional)','papaki-domain-registration'),
        '_FORM_FAX_'=>__('Fax (Optional)','papaki-domain-registration'),
        '_FORM_CONTINUE_'=>__(get_option( 'papaki_domain_reg_page_btn','Continue' ),'papaki-domain-registration'),
    );
    $content=str_replace(array_keys($replacements),array_values($replacements),$content);
    return $content;
}
function search_domains_ajax() {
    
    check_ajax_referer( "searchdomains" );
    $search = new PapakiDomainNameSearch($_POST['domain'],$_POST['tld']);
    $search->apikey = get_option( 'api_key' ) ;  
    $search->use_curl = true;
    $search->exec_request_for(_TYPE_DS,true);
    if($search->hasError || (count($search->arrayAvDomains)+count($search->arrayNotAvDomains))==0){
        $domains[]=array('domain'=>$search->domainName.'.'.$search->tld,'available'=>false,'error'=>true,'errorMessage'=>$search->errorMessage);
    
    }
    else{
        foreach($search->arrayAvDomains as $av){
            $domains[]=array('domain'=>$av,'available'=>true,'error'=>false);
        }
        foreach($search->arrayNotAvDomains as $av){
            $domains[]=array('domain'=>$av,'available'=>false,'error'=>false);
        }   
    }
    print json_encode($domains);
    wp_die();
}
add_action( 'wp_ajax_searchdomains', 'search_domains_ajax' );
add_action( 'wp_ajax_nopriv_searchdomains', 'search_domains_ajax' );

require( plugin_dir_path(__FILE__) ."includes/class-virtualthemedpage-bc.php"); 
$vp =  new Papaki_Virtual_Themed_Pages();
$vp->add('#/domains#i', 'papaki_domains_content');
$vp->add('#/?p=domains#i', 'papaki_domains_content');
// Must set $this->body even if empty string
function papaki_domains_content($v, $url)
{  //print $url;
    $v->title = __(get_option( 'papaki_domain_reg_page_title','Domain Names' ),'papaki-domain-registration');
    $v->body = "[domain_registration]";
    $v->template = 'page'; // optional
    //$v->subtemplate = 'billing'; // optional
}
// Creating the widget 
class papaki_domains_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
        // Base ID of your widget
        'papaki_domains_widget', 
        
        // Widget name will appear in UI
        __('Papaki Domains Widget', 'papaki_domains_widget_domain'), 
        
        // Widget description
        array( 'description' => __( 'Simple widget to add a domain search box', 'papaki_domains_widget_domain' ), ) 
        );
    }
    
    // Creating widget front-end
    // This is where the action happens
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if ( ! empty( $title ) )
        echo $args['before_title'] . $title . $args['after_title'];
        $form_action=home_url( 'domains' );
        if(!get_option('permalink_structure')){
            $form_action=home_url( '/' );
        }
        $html='<div style="">
            <form action="'.$form_action.'" method="GET">
            <input type="hidden" name="search" value="true" />';
            if(!get_option('permalink_structure')){
                $html.='<input type="hidden" name="p" value="domains" />';
            }
            $html.='
              <label><strong>'.__('Domain','papaki-domain-registration').':</strong></label>
              <input type="text" name="domain" id="wdomain" value="www.'.(isset($_GET['domain'])?str_replace('www.','',$_GET['domain']):'').'"/>
              <select name="tld" id="wtld">
              ';
              $allowed_tlds =  get_option( 'allowed_tlds' );
              if(is_array($allowed_tlds)){
                  foreach($allowed_tlds as $tld){
                      $html.='<option value="'.$tld.'"';
                      if(isset($_GET['tld']) && $_GET['tld']==$tld){
                          $html.=' selected="selected" ';
                      }
                      $html.='> .'.strtoupper($tld).' </option>';
                  }
              }
              $html.='
                </select>
              <button type="submit" id="search_btn" >'.__('Search','papaki-domain-registration').'</button>    
              </form>       
            </div>';
        echo $html;
        echo $args['after_widget'];
    }
            
    // Widget Backend 
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
        $title = $instance[ 'title' ];
        }
        else {
        $title = __( 'Domain Search', 'papaki_domains_widget_domain' );
        }
        // Widget admin form
        ?>
        <p>
        <label for="<?php echo $this -> get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this -> get_field_id('title'); ?>" name="<?php echo $this -> get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }
    
    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
} // Class wpb_widget ends here

// Register and load the widget
function papaki_domains_load_widget() {
    register_widget( 'papaki_domains_widget' );
}
add_action( 'widgets_init', 'papaki_domains_load_widget' );

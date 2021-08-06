var tlds=pdr_script_vars.search_tlds;
var total=tlds.length-1;	  
if(total>10) total=10;

function Search3() {
	jQuery(document).ready(function() {
		jQuery.ajax({
			type : "post",
			url : pdr_script_vars.admin_ajax_url,
			data : {
				action : 'searchdomains',
				_ajax_nonce : pdr_script_vars.nonce_searchdomains
			},
			beforeSend : function() {
				jQuery("#loading").show("slow");
			},
			complete : function() {
				jQuery("#loading").hide("fast");
			},
			success : function(html) {
				alert(html);
			}
		});
	});
}
 function Search(){
 	//var $=jQuery;
	var domain=jQuery('input#domain').val().replace('www.','');
	var arr=domain.split('.');
	domain=arr[0];
	var found=false;
	jQuery('.available_tlds').empty();
	jQuery('form#add_domains #pd-submit').hide();
	jQuery('form#add_domains').show();
	
	tld=jQuery('select#tld').val();
	SearchOne(domain,tld,true,true);	
}
function SearchOthers(domain){
	//var $=jQuery;
	var count=0;
	for(i=0;i<tlds.length;i++){
		if(count>8) break;
		if(tlds[i]!=tld){
			// count++;
			SearchOne(domain,tlds[i],false,false);
		}
	}
}
function SearchOne(domain,tld,checked,search_others){
//	var $=jQuery;
jQuery('img#loading').css('visibility','visible');
	theid=tld.replace('.','_');
	jQuery('form#add_domains ul.available_tlds').append('<li id="'+theid+'" class="loading"><label ><input type="checkbox" name="domains[]" value=""  disabled="disabled"/>'+domain+'.'+tld+'</label></li>');
	jQuery.post(
		   pdr_script_vars.admin_ajax_url, 
		   { 'domain':domain,'tld':tld,option:'com_papakidomains',format:'raw',action : 'searchdomains',_ajax_nonce : pdr_script_vars.nonce_searchdomains },
		    function(data){
				  theid=tld.replace('.','_');
				 if(data[0].error){
					 //$('form#add_domains ul.available_tlds li#'+theid).attr('title',pdr_script_vars.error_msg).append('<label><img src="'+pdr_script_vars.error_msg+'img/error.png"  /> '+data[0].domain+'</label>').show();
					 jQuery('form#add_domains ul.available_tlds li#'+theid+' label').append('<img src="'+pdr_script_vars.path+'/img/error.png"  /> ');
				 }
				 else{
					 if(data[0].available){
						found=true;
						if(checked){
							c=' checked="checked" ';
							jQuery('form#add_domains ul.available_tlds li#'+theid+' label input').attr('checked',true);
						}
						else c='';
						//$('form#add_domains ul.available_tlds li#'+theid).append('<label><input type="checkbox" name="domains[]" value="'+data[0].domain+'" '+c+'/>'+data[0].domain+'</label>').show();
						jQuery('form#add_domains ul.available_tlds li#'+theid+' label input').removeAttr('disabled').val(data[0].domain);//.prepend('<input type="checkbox" name="domains[]" value="'+data[0].domain+'" '+c+'/> ');
						jQuery('form#add_domains ul.available_tlds li#'+theid+'').addClass('available').append(jQuery('<span/>').text(pdr_script_vars.msg_avail).addClass('msg'));
						jQuery('form#add_domains #pd-submit').show();
					}
					else{
						jQuery('form#add_domains ul.available_tlds li#'+theid+'').addClass('taken');
						jQuery('form#add_domains ul.available_tlds li#'+theid+'').addClass('taken').append(jQuery('<span/>').text(pdr_script_vars.msg_taken).addClass('msg'));
					}
				 }
				 jQuery('form#add_domains ul.available_tlds li#'+theid+'').removeClass('loading');
				if(--total<=0){
					jQuery('img#loading').css('visibility','hidden');
				}
				if(search_others){
					SearchOthers(domain);
				}
			  },
		   'json'
	);
}
 function check_data(form)
    { 
      if (form.fullname.value.length < 1) {
	   showMessage(form.fullname, pdr_script_vars.form_error_companyname);
	   return false;
      }
	if (form.firstname.value.length < 1) {
	showMessage(form.firstname, pdr_script_vars.form_error_firstname);
	return false;
      }
	  if (form.lastname.value.length < 1) {
	showMessage(form.lastname, pdr_script_vars.form_error_lastname);
	return false;
      }
	  if (form.phoneNum.value.length < 1) {
	showMessage(form.phoneNum, pdr_script_vars.form_error_phone);
	return false;
      }
     if (form.emailText.value.length < 1) {
	   showMessage(form.emailText, pdr_script_vars.form_error_email);
	   return false;
      }
	  if (form.emailText.value.search(/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/) == -1)
	{
	    showMessage(form.emailText, pdr_script_vars.form_error_email2);
		return false;
	}
	 

	  if (form.address1.value.length < 1) {
	showMessage(form.address1,pdr_script_vars.form_error_address );
	return false;
      }
	  if (form.phoneNum.value.length > 0)
	  {
		if (form.phoneNum.value.search(/^\+[0-9]{1,3}\.[0-9]{1,14}$/) == -1){ 
			showMessage(form.phoneNum,pdr_script_vars.form_error_phone2);
			return false;
		}
	  }

	 if (form.fax.value.length > 0) 
	 {
		if (form.fax.value.search(/^\+[0-9]{1,3}\.[0-9]{1,14}$/) == -1)
		{ 
			showMessage(form.fax, pdr_script_vars.form_error_fax);
			return false;
	 	}
	}
    
	if (form.city.value.length < 1) {
	showMessage(form.city,pdr_script_vars.form_error_city);
	return false;
      }
	  if (form.postcode.value.length < 1) {
	showMessage(form.zip,pdr_script_vars.form_error_zip );
	return false;
      }
	  form.submit();		     	  
   } 
	
	function CompareStrings(a,b){
		if (a!=b)
			return false;
		else
			return true;
	}

	
function showMessage(frmObj, message)
{
	alert(message);
    if (frmObj.type == "hidden")
           return false;
	else{
          //window.focus();
	  return false;}
}

function CheckSelected(){
	var grp = form1.businessTypeRadioButton;
	
	for (var i = 0; i < grp.length - 1; i++){
		if(grp[i].checked){
			switch(grp[i].value){
				case 0:
					TrHide(true);
				break;
				case 1:
					TrHide(false);
				break;
				case 2:
					TrHide(3);
				break;
				default:
					TrHide(true);
				break;
			}
		}
	}
}

	function TrHide(bool){
		if (bool==true){			
			document.getElementById("afm_tr").style.display = 'none';
			document.getElementById("list_tr").style.display = 'none';
			document.getElementById("doy_tr").style.display = 'none';
			document.getElementById("drast_ep").style.display = 'none';
		}else if(bool==false) {
			document.getElementById("afm_tr").style.display = 'block';
			document.getElementById("list_tr").style.display = 'block';
			document.getElementById("doy_tr").style.display = 'block';
			document.getElementById("drast_ep").style.display = 'block';
		}else if(bool==3){
			document.getElementById("afm_tr").style.display = 'block';
			document.getElementById("list_tr").style.display = 'none';
			document.getElementById("doy_tr").style.display = 'block';
			document.getElementById("drast_ep").style.display = 'none';
		}
	}
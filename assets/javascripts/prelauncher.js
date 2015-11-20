var Prelauncher = function(company_id, token, development){

	if (development){
		var rootUrl = "http://api.prelauncher.io:3000/companies/" + company_id + "/clients/";
	} else {
		var rootUrl = "http://api.prelauncher.info/companies/" + company_id + "/clients/";
	}
	
	var websiteURL;

	this.buildFirstPage = function(){

		buildPage(rootUrl + "new", function(data){
			generatePage(data["constructor"], function(){
				addHiddenTags();
				onSubmitFormHandler();
			});
		});
	}

	function onSubmitFormHandler(){
		jQuery("form").on("submit", function(e){
			e.preventDefault();
			jQuery.ajax({
       			type: "POST",
       			url: rootUrl,
       			dataType: "json",
       			'beforeSend' : function(xhr){
					xhr.setRequestHeader("Accept", "application/json")
				}, 
       			data: jQuery(this).serialize() + "&token=" + token,
       			success: function(data) {
           			createClientCallback(data, true);
       			},
       			error: function(data) {
           			errorMesage(data["responseText"]);
       			}
     		});
		});		
	}

	function generatePage(constructor, callback){

		jQuery("body").html(constructor).promise().done(function(){
			
			jQuery('html').attr('style', function(i,s) { return (s || '') + 'margin-top: 0 !important;' })
			callback();
		});
	}

	function GetURLParameter(sParam) {
    	var sPageURL = window.location.search.substring(1);
    	var sURLVariables = sPageURL.split('&');
    	for (var i = 0; i < sURLVariables.length; i++) {
       		var sParameterName = sURLVariables[i].split('=');
        	if (sParameterName[0] == sParam) {
            	return sParameterName[1];
        	}
    	}
	}

	this.addHiddenTags = function(){
		addHiddenTags();
	}

	function addHiddenTags(){
		var referralId;
		if (referralId = GetURLParameter("ref")){
			addHiddenTag("referral_id", referralId);
			if (document.referrer){
				addHiddenTag("traffic_source", document.referrer);
			}
		}
	}

	function addHiddenTag(name, value) {
		jQuery('<input>').attr('type','hidden').attr('name', "client[" + name + "]").attr('value', value).appendTo('form');
	}

	function errorMesage(message){
		if (jQuery(".alert-danger").length > 0){
			jQuery(".alert-danger").text(message)
		} else {
			jQuery("body").prepend("<div class='alert alert-danger'>" + message + "</div>" );
		}
	}

	function createClientCallback(data, wordpress){
		if (wordpress !== undefined){
			var url = websiteUrl + "/?u=" + data["client"]["referral_code"];
		} else {
			var url = websiteUrl + "/clients/" + data["client"]["referral_code"];
		}
		window.location.replace(url);
	}

	function buildPage(url, callback){
		jQuery.ajax({
			url: url, 
			cache: false, 
			contentType: "application/json; charset=utf-8",
			dataType: "json",
			'beforeSend' : function(xhr){
				xhr.setRequestHeader("Accept", "application/json")
			}, 
			data: {token: token}, 
			success: function(data) {
				websiteUrl = data["website_url"];
				callback(data);
			}, error: function(data) {
				console.log(data);
			}
		});
	}


	function referralLink(referralCode){
		return websiteUrl + "/?ref=" + referralCode;
	}

	this.buildSecondPage = function(referralCode){
		buildPage(rootUrl + referralCode, function(data){
			generatePage(data["constructor"], function(){
				if (data["prize_id"]){
					jQuery(".prize[data-prize_id=" + data["prize_id"] + "]").addClass("active-prize")
				}
				
				jQuery("strong > a").text(data["number_of_referrals"] + " friends ").attr("href", websiteUrl + "/clients/" + referralCode + "/referrals");
				jQuery("[name=referral_link]").val(referralLink(referralCode));
				modifySocialLinks(referralCode);
				jQuery(".progressbar").css("width", data['progress'] * 100 + "%");				
			});
		});
	}

	function modifySocialLinks(referralCode){
		jQuery(".foo_social").not(".ico-mail").each(function() {
			var service = jQuery(this).data("service");
			jQuery(this).attr("href", rootUrl + referralCode + "/shares?share%5Bsocial_network%5D=" + service);
		});		
		jQuery(".ico-mail").attr("href", jQuery(".ico-mail").attr("href") + " " + referralLink(referralCode));
	}


}

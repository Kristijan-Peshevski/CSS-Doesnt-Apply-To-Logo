(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
function matchCustom(params, data) {
    // If there are no search terms, return all of the data
    if ($.trim(params.term) === '') {
      return data;
    }

    // Do not display the item if there is no 'text' property
    if (typeof data.text === 'undefined') {
      return null;
    }

    // `params.term` should be the term that is used for searching
    // `data.text` is the text that is displayed for the data object
    if (data.text.indexOf(params.term) > -1) {
      var modifiedData = $.extend({}, data, true);
      modifiedData.text += ' (matched)';

      // You can return modified objects from here
      // This includes matching the `children` how you want in nested data sets
      return modifiedData;
    }

    // Return `null` if the term should not be displayed
    return null;
}



$(document).on("click", ".export-btn", function(e){
	e.preventDefault();
	var page_id = $('#export_pages').val();
	var replace_urls = $('#replace_all_url').is(':checked') ? true : false;
	var skip_image_src_url = $('#skip_image_src_url').is(':checked') ? true : false;

	if(page_id !== null){
	 var datas = {
	  'action': 'rc_export_wp_page_to_static_html',
	  'rc_nonce': rcewpp.nonce,
	  'page_id': page_id,
	  'replace_urls': replace_urls,
	  'skip_image_src_url': skip_image_src_url,
	};

	$('.logs_list').html('');
	$('.spinner_x').removeClass('hide_spin');
	$('.download-btn').addClass('hide');
	$('.logs').show();


	var myVar = setInterval(myTimer, 1000);

	function myTimer() {
	   var id = $('.logs_list .log').length;
	   var datas2 = {
	    'action': 'get_exporting_logs',
	    'rc_nonce': rcewpp.nonce,
	    'log_id': id,
	  };
	  
	  $.ajax({
	      url: rcewpp.ajax_url,
	      data: datas2,
	      type: 'post',
	      dataType: 'json',
	  
	      beforeSend: function(){
	      },
	      success: function(r){
	        if(r.success == 'true'){
	        	if (r.response !== null) {

	        		$.each(r.response, function(i, v){
	        			var type = "";
		        		if (v.type == "copying") {
		        			type = '<span class="copying log_type">Copying</span>';
		        		} 
		        		if(v.type == "reading") {
		        			type = '<span class="reading log_type">Reading</span>';
		        		}
		        		if(v.type == "creating") {
		        			type = '<span class="creating log_type">Creating</span>';
		        		}
		        		if(v.type == "creating_last_file") {
		        			type = '<span class="creating log_type">Creating</span>';
		        		}
		        		if(v.type == "creating_zip_file") {
		        			type = '<span class="creating log_type">Creating</span>';
		        		}
		        		if(v.type == "created_zip_file") {
		        			type = '<span class="created log_type">Created</span>';
		        		}
		        		if(v.type == "replacing") {
		        			type = '<span class="replacing log_type">Replacing</span>';
		        		}

		        		var log_text = '<span class="path">' + v.path + '</span>';

		        		var comment = '<span class="comment">' + v.comment + '</span>';

		        		var log = '<div class="log" type="'+v.type+'" id="'+v.id+'">'+type+' ' +log_text+ ' ' + comment + '</div>';

						if ($('#'+v.id).length < 1) {
			        		$('.logs_list').prepend(log);

			        		/*if(v.type == "created_zip_file") {
			        			rc_html_export_stop_interval();
			        		}*/
						}
	        		})
	        		
				}

	          } else {
	            //alert('Something went wrong, please try again!');
				$('.spinner_x').addClass('hide_spin');
	          }
	      	
	      }, error: function(){
				$('.spinner_x').addClass('hide_spin');
	      	
	    }
	  });
	}

	function rc_html_export_stop_interval(error = false) {
		clearInterval(myVar);

		if (!error) {
			var log = '<div class="log" id="created"><span class="log_type creating_main_file">Success</span><span> the main html file has been created!</span></div>';
			if ($('.creating_main_file').length < 1) {
				$('.logs_list').prepend(log);
			}
			setTimeout(function() {
				var log = '<div class="log" id="creating_zip_file"><span class="log_type creating creating_zip_file">Creating</span><span> zip file.</span></div>';
				if ($('.creating_zip_file').length < 1) {
					$('.logs_list').prepend(log);	
				}
			}, 50);
		}

	} 
	

	$.ajax({
	    url: rcewpp.ajax_url,
	    data: datas,
	    type: 'post',
	    dataType: 'json',
	
	    beforeSend: function(){
			
	    },
	    success: function(r){
	      if(r.success == 'true'){
	      	console.log(r);
	      	if (r.response) {

/*	      		setTimeout(function() {

	       		var datas = {
				  'action': 'create_the_zip_file',
				  'rc_nonce': rcewpp.nonce,
				  'page_id': page_id,
				};
				
				$.ajax({
				    url: rcewpp.ajax_url,
				    data: datas,
				    type: 'post',
				    dataType: 'json',
				
				    beforeSend: function(){
						
				    },
				    success: function(r){

				      if(r.success == 'true' && r.response !== false ){
				        
				        console.log(r.response);
						setTimeout(function() {
							rc_html_export_stop_interval();
							var log = '<div class="log" id="created_zip_file"><span class="log_type created_zip_file">Success</span><span> the zip file has been created!</span></div>';
							$('.logs_list').prepend(log);

							setTimeout(function() {
								$('.spinner_x').addClass('hide_spin');
								var log = '<div class="log" id="ready_to_download"><span class="log_type ready_to_download"></span><span> The file is ready to download.</span></div>';
								$('.logs_list').prepend(log);

								$('.download-btn').attr('href', r.response).removeClass('hide');

							}, 1000);
						}, 1500);
				        
				        } else {
				          alert('Something went wrong, please try again!');
							$('.spinner_x').addClass('hide_spin');
				        }
				    	
				    }, error: function(){
							$('.spinner_x').addClass('hide_spin');
				    	
				  }
				});

	      	}, 1000);*/

	      	}
	
	        
	        } else {
	        	rc_html_export_stop_interval(true);
	          alert('Something went wrong, please try again!');
				$('.spinner_x').addClass('hide_spin');
	        }
	    	
	    }, error: function(){
	    	rc_html_export_stop_interval(true);
			$('.spinner_x').addClass('hide_spin');
	  }
	});
	
	}
});


$(document).on("select2:selecting", function(e){
	if ( $(e.target).is('#export_pages') ) {
		if (!$('#posts_list').length) {
			$('#export_pages').append('<option id="posts_list" disabled="disabled">Posts</option>').change();
		}
	}	
})

$(document).on("select2:select", function(e){

	if ($(e.target).is('#export_pages')) {

		var data = e.params.data;
		var pageID = data.id;
		var pageTitle = data.text;
		var permalink = (data.permalink != undefined) ? data.permalink : $(data.element).attr('permalink');

		var home_url = rcewpp.home_url;
		var url = permalink.replace(home_url, '');
		url = url.replace(/\/\s*$/, "");

		if ($('.homepage-badge').is(":visible")) {
			var homepage_badge = '<div class="diagonal badge green homepage-badge">Homepage</div>';
		} else {
			var homepage_badge = '<div class="diagonal badge green homepage-badge">Homepage</div>';
		}

		if ($('.pages_list').find('[page_id="'+pageID+'"]').length < 1) {
			if(pageID == "home_page"){

				var default_file_name = $('.single_page').find('.homepage-badge:visible').closest('.single_page').find('.contenteditable').attr('default');
				$('.single_page').find('.homepage-badge:visible').closest('.single_page').find('.contenteditable').text(default_file_name);
				$('.single_page').find('.homepage-badge:visible').addClass("hidden");
				
				$('.pages_list').prepend('<li class="single_page" page_id="'+pageID+'" page_title="'+pageTitle+'" permalink="'+home_url+'"><div class="page-title">'+pageTitle+homepage_badge+'</div> <div class="page-right"> <div class="permalink btn_ page-right-item"><small class="contenteditable" contenteditable="true" default="homepage">index</small><small>.html</small></div><div class="child-pages btn_ page-right-item"><input type="checkbox" id="full_site_page_id"> <label for="full_site_page_id">Full site</label></div> <div class="child-pages btn_ page-right-item"><input type="checkbox" id="page_id_home_page" class="all_links_checkbox"> <label for="page_id_home_page">All links</label></div><div class="page_settings"><ul><li class="set_as_homepage">Set as homepage</li></ul></div><div class="gear page-right-item"></div> <div class="close page-right-item"></div> </div><div class="clear"></div></li>');
			} else {
				$('.pages_list').append('<li class="single_page" page_id="'+pageID+'" page_title="'+pageTitle+'" permalink="'+permalink+'"><div class="page-title">'+pageTitle+'<div class="diagonal badge green homepage-badge hidden">Homepage</div></div> <div class="page-right"> <div class="permalink btn_ page-right-item"><small class="contenteditable" contenteditable="true" default="'+url+'">'+url+'</small><small>.html</small></div><div class="child-pages btn_ page-right-item"><input type="checkbox" id="page_id_'+pageID+'" class="all_links_checkbox"> <label for="page_id_'+pageID+'">All links</label></div><div class="page_settings"><ul><li class="set_as_homepage">Set as homepage</li></ul></div><div class="gear page-right-item"></div>  <div class="close page-right-item"></div> </div><div class="clear"></div></li>');
			}		
		}
	}
});


$(document).on("click", ".single_page .gear", function(){
	if ($(this).siblings('.page_settings').is(":visible")) {
		$(this).siblings('.page_settings').fadeOut(300);
	} else {
		$('.page_settings').hide();
		$(this).siblings('.page_settings').fadeIn(300);
	}

});

$(document).on("click", ".set_as_homepage", function(){
	var default_file_name = $('.single_page').find('.homepage-badge:visible').closest('.single_page').find('.contenteditable').attr('default');
	$('.single_page').find('.homepage-badge:visible').closest('.single_page').find('.contenteditable').text(default_file_name);
	$('.single_page').find('.homepage-badge:visible').addClass("hidden");
	$(this).closest('.single_page').find('.homepage-badge').removeClass('hidden');
	$(this).closest('.single_page').find('.contenteditable').text('index');
	$('.page_settings').hide();
});

$(document).on("click", ".pages_list .single_page .close", function(){
	$(this).closest('.single_page').addClass('delete_bg');

	var this_ = $(this);
	setTimeout(function() {
		this_.closest('.single_page').remove();
	}, 200);
});

$(document).on("click", ".select2-selection__choice__remove", function(){
  var data = $('#export_pages').val();

  if (data == null) {
  	$('.select_multi_pages').show();
  }
});

$(document).on("click", ".select_multi_pages", function(){
	$('.select2-selection__rendered').click();
});

$(document).on("click", "#full_site_page_id", function(){
	if ($(this).is(":checked")) {
		var total_count = $('.pages_list .single_page');
		for (var i = total_count.length; i > 0; i--) {

			$('.pages_list .single_page').each(function(){
				var this_ = $(this);
				if (this_.attr('page_id') !== "home_page") {
					//setTimeout(function() {
						this_.hide();
					//}, 1000);
				}
			});
		}
	}	


	if (!$(this).is(":checked")) {
		var total_count = $('.pages_list .single_page');
		for (var i = total_count.length; i > 0; i--) {

			$('.pages_list .single_page').each(function(){
				var this_ = $(this);
				if (this_.attr('page_id') !== "home_page") {
					//setTimeout(function() {
						this_.show();
					//}, 1000);
				}
			});
		}
	}
});

$(document).on("click", ".static_html_settings .nav-item .nav-link", function(e){
	e.preventDefault();

	$('.static_html_settings .nav-item .nav-link').removeClass('active');
	$('.static_html_settings .tab-pane').removeClass('active');
	$(this).addClass('active');

	var link = $(this).attr('href');
	$(link).addClass('active');

});

/*
$(document).on("keyup", ".static_html_settings .select2-search__field", function(){

	var this_value = $(this).val();
		var this_ = $(this);
	if ($(this).val().length >= 3) {


		 var datas = {
		  'action': 'rc_search_posts',
		  'rc_nonce': rcewpp.nonce,
		  'value': this_value,
		};

		
		$.ajax({
		    url: rcewpp.ajax_url,
		    data: datas,
		    type: 'post',
		    dataType: 'json',
		
		    beforeSend: function(){
				this_.parent().prepend('<div class="lds-ellipsis post_searching"><div></div><div></div><div></div><div></div></div>');
		    },
		    success: function(r){
				$('.lds-ellipsis.post_searching').remove();
		      	if(r.success == 'true' && r.response !== ""){

		      		var options = "";
					$.each( r.response, function( key, value ) {
						if (!$('[value="'+value.post_id+'"]').length) {
							  options += '<option value="'+value.post_id+'" permalink="'+value.permalink+'">'+value.post_title+'</option>';
						}

					});

						console.log(options);
					if (options !== "") { 
						$('#export_pages').append(options).change();


						var lists = "";
						$.each( r.response, function( key, value ) {
							if (!$('#select2-export_pages-result-gh'+value.post_id+'-'+value.post_id).length) {
								  lists += '<li class="select2-results__option newly-added-list" id="#select2-export_pages-result-gh'+value.post_id+'-'+value.post_id+'" value="'+value.post_id+'" role="treeitem" aria-selected="false"><span permalink="undefined">'+value.post_title+'</span></li>';
							}

						});

						$('#select2-export_pages-results').append(lists);
					}

		        } else {
		          console.log('Something went wrong, please try again!');
		        }
		    	
		    }, error: function(){
				$('.lds-ellipsis.post_searching').remove();
		  }
		});
	}

});
*/

$(document).on("mouseenter", ".newly-added-list", function(){
	$(this).addClass('select2-results__option--highlighted');
});

$(document).on("click", ".newly-added-list", function(){
	var page_id = $(this).attr('value');

	 $('#export_pages').val(page_id).change();
});

function rc_ajax_select2(){

	$('#export_pages').select2({
			minimumInputLength: 3,
		  ajax: {
		    url: rcewpp.ajax_url, // AJAX URL is predefined in WordPress admin
    			dataType: 'json',
    			delay: 250, // delay in ms while typing when to perform a AJAX search
    			data: function (params) {
      				return {
        				value: params.term, // search query
        				action: 'rc_search_posts' // AJAX action for admin-ajax.php
      				};
    			}
		  }, 
            templateResult: function (idioma) {
                var permalink = $(idioma.element).attr('permalink');
                var $span = $("<span permalink='"+idioma.permalink+"'>" + idioma.text + "</span>");
                return $span;
            }
	});	
}
$(document).on("change", "#search_posts_to_select2", function(e){
	if ($(this).is(":checked")) {
		rc_ajax_select2();
	} else {
		rc_select2_is_not_ajax();
	}
});



var ftpUploadingCountAutoRefresh;
$(document).on("click", ".export-btn2", function(e){
	e.preventDefault();

	var page_list = $('.select_pages_to_export .pages_list .single_page');
	var replace_urls = $('#replace_all_url').is(':checked') ? true : false;
	var skip_image_src_url = $('#skip_image_src_url').is(':checked') ? true : false;
	var receive_email = $('#email_notification').is(':checked') ? true : false;
	var email_lists = $('#receive_notification_email').val();

	

	var export_data = [];
	$(page_list).each(function(){

		var permalink = $(this).attr('permalink');
		var title = $(this).attr('page_title');
		var page_id = $(this).attr('page_id');
		var post_name = $(this).find('.contenteditable').text();
		var is_homepage = false;

			if ($(this).find('.homepage-badge:visible').length) {
				is_homepage = true;
			}

		var is_full_site = false;

			if ($(this).find('#full_site_page_id').is(":checked")) {
				is_full_site = true;
			}

		var is_all_links = false;

			if ($(this).find('.all_links_checkbox').is(":checked")) {
				is_all_links = true;
			}


		var page_data = {};
			page_data['permalink'] = permalink;
			page_data['title'] = title;
			page_data['page_id'] = page_id;
			page_data['post_name'] = post_name;
			page_data['is_homepage'] = is_homepage;
			page_data['is_full_site'] = is_full_site;
			page_data['is_all_links'] = is_all_links;

		export_data.push(page_data);

		//export_data[] = page_data;
	});

	//console.log(export_data.length);
	if (export_data.length > 0) {

		$('.logs_list').html('');
		$('.spinner_x').removeClass('hide_spin');
		$('.download-btn').addClass('hide');
		$('.logs').show();
		$('.cancel_rc_html_export_process').show();	
		$('#cancel_ftp_process').val('false');

		var ftp = false;
		var path = '';
		var ftp_data = {};
		if ($('#upload_to_ftp').is(":checked")) {
			ftp = true;

			if ($('#ftp_path').val() !== "") {
				path = $('#ftp_path').val();
			}
		}
		
		 var datas = {
		  'action': 'add_cron_job_to_start_html_exporting',
		  'rc_nonce': rcewpp.nonce,
		  'export_data': JSON.stringify(export_data),
		  'replace_urls': replace_urls,
		  'skip_image_src_url': skip_image_src_url,
		  'ftp': ftp,
		  'path': path,
		  'receive_email': receive_email,
		  'email_lists': email_lists
		};

		var myVar = setInterval(myTimer, 500);

		function myTimer() {
			/*if ($('.logs_list .log.main_log').length == 0) {

			}*/
		   var id = $('.logs_list .log.main_log').first().attr('id');
		   var datas2 = {
		    'action': 'get_exporting_logs',
		    'rc_nonce': rcewpp.nonce,
		    'log_id': id,
		  };
		  
		  $.ajax({
		      url: rcewpp.ajax_url,
		      data: datas2,
		      type: 'post',
		      dataType: 'json',
		  
		      beforeSend: function(){
		      },
		      success: function(r){
		        if(r.success == 'true'){
		        	if (r.response !== null) {

		        		$.each(r.response, function(i, v){
		        			var type = "";
		        			var comment = v.comment;
		        			var path = v.path;
		        			if (v.type == 'something_went_wrong') {
		        				some_error_appears();
		        			}
		        			else if(v.type == 'cancel_export_process'){
		        				cancel_export_process();
		        			}
		        			else if(v.type == 'login_failed_to_ftp'){
		        				rc_ftp_failed();
		        			}
		        			else {
			        			if(v.type !== 'file_uploaded_to_ftp'){

				        		if (v.type == "copying") {
				        			type = '<span class="copying log_type">Copying</span>';
				        		} 
				        		if(v.type == "reading") {
				        			type = '<span class="reading log_type">Reading</span>';
				        		}
				        		if(v.type == "creating") {
				        			type = '<span class="creating log_type">Creating</span>';
				        		}
				        		if(v.type == "creating_zip_file") {
				        			type = '<span class="creating log_type">Creating</span>';
				        		}
				        		if(v.type == "creating_html_file") {
				        			type = '<span class="creating log_type">Creating</span>';
				        		}
				        		if(v.type == "created_html_file") {
				        			type = '<span class="success log_type">Created</span>';
				        		}
				        		if(v.type == "uploading_to_ftp") {
				        			type = '<span class="creating log_type">Uploading</span>';
				        			path = '';
				        			comment = 'files to ftp server';
				        		}

				        		/*if(v.type == "created_zip_file") {
				        			type = '<span class="success log_type">Created</span>';
				        		}*/
				        		if(v.type == "replacing") {
				        			type = '<span class="replacing log_type">Replacing</span>';
				        		}



				        		var log_text = '<span class="path">' + path + '</span>';
				        		var comment = '<span class="comment">' + comment + '</span>';

				        		if ( /*!ftp && */v.type == "created_zip_file") {
				        			log_text = '';
				        			comment = "";
				        		}

				        		var log = '<div class="log main_log" id="'+v.id+'">'+type+' ' +log_text+ ' ' + comment + '</div>';
						

								if(v.type == "uploading_to_ftp") {
					        		setTimeout(function() {
					        			type = '<span class="uploading_to_ftp log_type">Uploading</span>';
					        			path = '';
					        			comment = '<span class="ftp_uploaded_count">0</span> of <span class="total_files_count">0</span> files';

					        			log = '<div class="log main_log" id="'+v.id+'">'+type+' ' +log_text+ ' ' + comment + '</div>'+log;
										$('.logs_list').prepend(log);
										ftpUploadingCountAutoRefresh = setInterval(ftpUploadingCountRcAutoRefresh, 3000);
				        			}, 1000);

									clearInterval(myVar);
				        		}

								if ($('#'+v.id).length < 1 && !$('#cancel_export_process').length && v.type !=='uploading_to_ftp') {
					        		
									$('.logs_list').prepend(log);
					        		if( v.type == "created_zip_file" ) {
					        			rc_html_export_stop_interval(false, v.path, v.type);
					        		}

					        		if(ftp && (v.type == 'login_failed_to_ftp' || v.type == 'uploaded_to_ftp')){
										ftp_uploading_completed();
									}	
									
								}
							}

		        			}
		        		
		        		})
					}

		          } else {
		            alert('Something went wrong, please try again!');
					$('.spinner_x').addClass('hide_spin');
					$('.cancel_rc_html_export_process').hide();
		          }
		      	
		      }, error: function(){
					$('.spinner_x').addClass('hide_spin');
					$('.cancel_rc_html_export_process').hide();
		      	
		    }
		  });
		}

		function ftp_uploading_completed() {
			clearInterval(ftpUploadingCountAutoRefresh);
			clearInterval(myVar);
			$('.spinner_x').addClass('hide_spin');
			$('.cancel_rc_html_export_process').hide();
		}

		function cancel_export_process() {
			clearInterval(ftpUploadingCountAutoRefresh);
			clearInterval(myVar);
			$('.spinner_x').addClass('hide_spin');
			$('.cancel_rc_html_export_process').hide();
			var log = '<div class="log" id="cancel_export_process"><span class="log_type alert">Alert</span><span> Export process has been canceled!</span></div>';
			$('.logs_list').prepend(log);
		}

		function rc_ftp_failed() {
			clearInterval(ftpUploadingCountAutoRefresh);
			clearInterval(myVar);
			$('.spinner_x').addClass('hide_spin');
			$('.cancel_rc_html_export_process').hide();
			var log = '<div class="log" id="cancel_export_process"><span class="log_type alert">Alert</span><span> FTP login failed!</span></div>';
			$('.logs_list').prepend(log);
		}

		function some_error_appears() {
			clearInterval(ftpUploadingCountAutoRefresh);
			clearInterval(myVar);
			$('.spinner_x').addClass('hide_spin');
			$('.cancel_rc_html_export_process').hide();
			var log = '<div class="log" id="cancel_export_process"><span class="log_type alert">Alert</span><span>Something went wrong! Please try again later or contact us.</span></div>';
			$('.logs_list').prepend(log);
		}

		function rc_html_export_stop_interval(error = false, zipUrl = "", type = "") {

			if (!ftp && type == 'created_zip_file') {
				clearInterval(ftpUploadingCountAutoRefresh);
				clearInterval(myVar);
				$('.spinner_x').addClass('hide_spin');
				$('.cancel_rc_html_export_process').hide();
			}

			var log = '<div class="log" id="created_zip_file"><span class="log_type created_zip_file">Success</span><span> the zip file has been created!</span></div>';
			$('.logs_list').prepend(log);

				var log = '<div class="log" id="ready_to_download" style="color: #1c6f1c;"><span class="log_type ready_to_download"></span><span> The file is ready to download.</span></div>';
				$('.logs_list').prepend(log);

				$('.download-btn').attr('href', rcewpp.home_url + '?rc_exported_zip_file=' + encodeURIComponent(zipUrl)).removeClass('hide').text('Download the file');

			/*if (!error) {
				var log = '<div class="log" id="created"><span class="log_type creating_main_file">Success</span><span> the main html file has been created!</span></div>';
				if ($('.creating_main_file').length < 1) {
					$('.logs_list').prepend(log);
				}
				setTimeout(function() {
					var log = '<div class="log" id="creating_zip_file"><span class="log_type creating creating_zip_file">Creating</span><span> zip file.</span></div>';
					if ($('.creating_zip_file').length < 1) {
						$('.logs_list').prepend(log);	
					}
				}, 50);
			}*/

		} 

		function ftp_exporting_log(argument) {
			clearInterval(myVar);

			var log = '<div class="log" id="created_zip_file"><span class="log_type created_zip_file">Success</span><span> the zip file has been created!</span></div>';
			$('.logs_list').prepend(log);

			$('.spinner_x').addClass('hide_spin');
			$('.cancel_rc_html_export_process').hide();
			var log = '<div class="log" id="ready_to_download" style="color: #1c6f1c;"><span class="log_type ready_to_download"></span><span> The file is ready to download.</span></div>';
			$('.logs_list').prepend(log);

			$('.download-btn').attr('href', rcewpp.home_url + '?rc_exported_zip_file=' + encodeURIComponent(zipUrl)).removeClass('hide').text('Download the file');
		}

		
		$.ajax({
		    url: rcewpp.ajax_url,
		    data: datas,
		    type: 'post',
		    //async: false,
		    dataType: 'json',
		
		    beforeSend: function(){
		
		    },
		    success: function(r){
		        console.log(r);
		      if(r.success == 'true'){
				
		        
		        } else {
		          console.log('Something went wrong, please try again!');
		        }
		    	
		    }, error: function(){
		    	
		  }
		});
	}
	else{
		alert('Please select a page');
	}


});

function rc_html_export_log(id_count) {
	
	$('.spinner_x').removeClass('hide_spin');
	$('.download-btn').addClass('hide');
	$('.logs').show();

	var myVar = setInterval(myTimer, 1000);

		function myTimer() {
		   var id = $('.logs_list .log.main_log').first().attr('id');
		   var datas2 = {
		    'action': 'get_exporting_logs',
		    'rc_nonce': rcewpp.nonce,
		    'log_id': id,
		  };
		  
		  $.ajax({
		      url: rcewpp.ajax_url,
		      data: datas2,
		      type: 'post',
		      dataType: 'json',
		  
		      beforeSend: function(){
		      },
		      success: function(r){
		        if(r.success == 'true'){
		        	if (r.response !== null) {

		        		$.each(r.response, function(i, v){
		        			if(v.type == 'cancel_export_process'){
							 cancel_export_process();	
							}
		        			else if(v.type == 'something_went_wrong'){
							 some_error_appears();	
							}
		        			else if(v.type == 'login_failed_to_ftp'){
							 rc_ftp_failed();	
							}
							else{
							
		        			var type = "";
		        			var comment = v.comment;
		        			var path = v.path;

			        		if (v.type == "copying") {
			        			type = '<span class="copying log_type">Copying</span>';
			        		} 
			        		if(v.type == "reading") {
			        			type = '<span class="reading log_type">Reading</span>';
			        		}
			        		if(v.type == "creating") {
			        			type = '<span class="creating log_type">Creating</span>';
			        		}
			        		if(v.type == "creating_zip_file") {
			        			type = '<span class="creating log_type">Creating</span>';
			        		}
			        		if(v.type == "creating_html_file") {
			        			type = '<span class="creating log_type">Creating</span>';
			        		}
			        		if(v.type == "created_html_file") {
			        			type = '<span class="success log_type">Created</span>';
			        		}
			        		if(v.type == "uploading_to_ftp") {
			        			type = '<span class="creating log_type">Uploading</span>';
			        			path = '';
			        			comment = 'files to ftp server';
			        		}

			        		if(v.type == "uploaded_to_ftp") {
			        			type = '<span class="success log_type">Successfully uploaded all files to ftp server</span>';
			        			path = '';
			        			comment = '';
			        		}
			        		/*if(v.type == "created_zip_file") {
			        			type = '<span class="success log_type">Created</span>';
			        		}*/
			        		if(v.type == "replacing") {
			        			type = '<span class="replacing log_type">Replacing</span>';
			        		}



			        		var log_text = '<span class="path">' + path + '</span>';
			        		var comment = '<span class="comment">' + comment + '</span>';

			        		if ( /*!ftp && */v.type == "created_zip_file") {
			        			log_text = '';
			        			comment = "";
			        		}

			        		var log = '<div class="log main_log" id="'+v.id+'">'+type+' ' +log_text+ ' ' + comment + '</div>';

			        		if(v.type == "uploading_to_ftp") {
			        			type = '<span class="uploading_to_ftp log_type">Uploading</span>';
			        			path = '';
			        			comment = '<span class="ftp_uploaded_count">-</span> of <span class="total_files_count">-</span> files.';

			        			log = '<div class="log" id="'+v.id+'">'+type+' ' +log_text+ ' ' + comment + '</div>'+log;

								$('.logs_list').prepend(log);
								ftpUploadingCountAutoRefresh = setInterval(ftpUploadingCountRcAutoRefresh, 1000);
								clearInterval(myVar);
				        	}
							if ($('#'+v.id).length < 1  && !$('#cancel_export_process').length && v.type !=='uploading_to_ftp' && v.type !=='file_uploaded_to_ftp') {
				        		$('.logs_list').prepend(log);

				        		//console.log(v.type);
				        		if(v.type == "created_zip_file") {
				        			rc_html_export_stop_interval(false, v.path);
				        		}

				        		if( v.type == 'uploaded_to_ftp' ){
									ftp_uploading_completed();
								}
							}
						}
		        		})
		        		
					}

		          } else {
		            alert('Something went wrong, please try again!');
					$('.spinner_x').addClass('hide_spin');
					$('.cancel_rc_html_export_process').hide();
		          }
		      	
		      }, error: function(){
					$('.spinner_x').addClass('hide_spin');
					$('.cancel_rc_html_export_process').hide();
		      	
		    }
		
		  });
		}
		 function cancel_export_process() {
			clearInterval(myVar);
			$('.spinner_x').addClass('hide_spin');
			$('.cancel_rc_html_export_process').hide();
			var log = '<div class="log" id="cancel_export_process"><span class="log_type alert">Alert</span><span> Export process has been canceled!</span></div>';
			$('.logs_list').prepend(log);
		}
		 function some_error_appears() {
			clearInterval(myVar);
			$('.spinner_x').addClass('hide_spin');
			$('.cancel_rc_html_export_process').hide();
			var log = '<div class="log" id="cancel_export_process"><span class="log_type alert">Alert</span><span>Something went wrong! Please try again later or contact us.</span></div>';
			$('.logs_list').prepend(log);
		}
		 function rc_ftp_failed() {
			clearInterval(myVar);
			$('.spinner_x').addClass('hide_spin');
			$('.cancel_rc_html_export_process').hide();
			var log = '<div class="log" id="cancel_export_process"><span class="log_type alert">Alert</span><span>FTP login failed!</span></div>';
			$('.logs_list').prepend(log);
		}
		function ftp_uploading_completed() {
			clearInterval(myVar);
			$('.spinner_x').addClass('hide_spin');
		}
		function rc_html_export_stop_interval(error = false, zipUrl = "") {
			clearInterval(myVar);

			var log = '<div class="log" id="created_zip_file"><span class="log_type created_zip_file">Success</span><span> the zip file has been created!</span></div>';
			$('.logs_list').prepend(log);

				$('.spinner_x').addClass('hide_spin');
				$('.cancel_rc_html_export_process').hide();
				var log = '<div class="log" id="ready_to_download" style="color: #1c6f1c;"><span class="log_type ready_to_download"></span><span> The file is ready to download.</span></div>';
				$('.logs_list').prepend(log);

				$('.download-btn').attr('href', zipUrl).removeClass('hide');



			/*if (!error) {
				var log = '<div class="log" id="created"><span class="log_type creating_main_file">Success</span><span> the main html file has been created!</span></div>';
				if ($('.creating_main_file').length < 1) {
					$('.logs_list').prepend(log);
				}
				setTimeout(function() {
					var log = '<div class="log" id="creating_zip_file"><span class="log_type creating creating_zip_file">Creating</span><span> zip file.</span></div>';
					if ($('.creating_zip_file').length < 1) {
						$('.logs_list').prepend(log);	
					}
				}, 50);
			}*/

		} 
}
function ftpUploadingCountRcAutoRefresh() {

	if ($('#cancel_ftp_process').attr('value') !== 'true') {
		 var datas = {
		  'action': 'rc_get_ftp_uploading_file_count',
		  'rc_nonce': rcewpp.nonce,
		  'post2': '',
		};
		
		$.ajax({
		    url: rcewpp.ajax_url,
		    data: datas,
		    type: 'post',
		    dataType: 'json',
		
		    beforeSend: function(){
		
		    },
		    success: function(r){
		      if(r.success == 'true'){
		        
					var uploaded = r.uploaded;
					var total_to_uploaded = r.total_to_uploaded;

					if (parseInt(uploaded) >= parseInt(total_to_uploaded)) {
						uploaded = total_to_uploaded;

		        		clearInterval(ftpUploadingCountAutoRefresh);
						$('.spinner_x').addClass('hide_spin');
						$('.cancel_rc_html_export_process').hide();

						//setTimeout(function() {
							if ( $('.successfully_uploaded_to_ftp').length < 1  ) {
								var log = '<span class="success log_type successfully_uploaded_to_ftp">Successfully uploaded all files to ftp server</span>';
			        			$('.logs_list').prepend(log);
							}

						//}, 500);

					}
						
					$('.ftp_uploaded_count').text(uploaded+' ');
					$('.total_files_count').text(' '+total_to_uploaded);
					
		        	
		        } else {
		          console.log('Something went wrong, please try again!');
		        }
		    	
		    }, error: function(){
		    	
		  }
		});
	}
	else {
		$('#cancel_ftp_process').attr('value', 'false');
		clearInterval(ftpUploadingCountAutoRefresh);
		$('.spinner_x').addClass('hide_spin');
		$('.cancel_rc_html_export_process').hide();
		var log = '<span class="alert log_type cancel_export_process">Canceled ftp uploading process</span>';
		$('.logs_list').prepend(log);
	}
}

$(document).each(function(){
	 var datas = {
	  'action': 'if_is_running_html_exporting_process',
	  'rc_nonce': rcewpp.nonce,
	  'post2': '',
	};
	
	$.ajax({
	    url: rcewpp.ajax_url,
	    data: datas,
	    type: 'post',
	    dataType: 'json',
	
	    beforeSend: function(){
	
	    },
	    success: function(r){
		    if(r.success == 'true'){
		    	if (r.export_process.length && r.export_process == 'running' || r.export_process == 'uploading_to_ftp') {
		    		$('.logs').show();
		    		$('.cancel_rc_html_export_process').show();
		    		rc_html_export_log(r.log_id);

		    		console.log(r.log_id);
		    	}
		    	/*if (r.export_process.length && r.export_process == 'uploading_to_ftp') {
		    		$('.logs').show();
		    		var type = '<span class="creating log_type">Uploading</span>';
        			var path = '';
        			var comment = 'files to ftp server';
        			var log_text = '<span class="path">' + path + '</span>';
			        var comment = '<span class="comment">' + comment + '</span>';
			        var log = '<div class="log" id="uploading_to_ftp">'+type+' ' +log_text+ ' ' + comment + '</div>';
					
					$('.logs_list').prepend(log);
		    	}*/
		    	if (r.is_zip_downloaded.length && r.export_process == 'ftp_completed' && r.is_zip_downloaded == 'no') {
		    		var type = '<span class="success log_type">Successfully uploaded all files to ftp server</span>';
        			var path = '';
        			var comment = '';
        			var log_text = '<span class="path">' + path + '</span>';
			        var comment = '<span class="comment">' + comment + '</span>';
			        var log = '<div class="log" id="uploading_to_ftp">'+type+' ' +log_text+ ' ' + comment + '</div>';
					
					$('.logs_list').prepend(log);
		    	}
		    	if (r.is_zip_downloaded.length && r.export_process == 'completed' && r.is_zip_downloaded == 'no') {
		    		if (r.zip_file_link.length) {
		    			$('.download-btn').attr('href', rcewpp.home_url + '?rc_exported_zip_file=' + encodeURIComponent(r.zip_file_link)).removeClass('hide').text("Download the last exported file");
		    		}
		    	}
	        } else {
	          	console.log('Something went wrong, please try again!');
	        }
	    }, error: function(){
	    	
	  }
	});
});




$(document).on("click", ".download-btn", function(){
	console.log('asdasd');
});

$(document).on("change", "#upload_to_ftp", function(){
	if ($(this).is(':checked')) {
		$('.ftp_Settings_section').slideDown();
	} else {
		$('.ftp_Settings_section').slideUp();
	}
});

$(document).on("change", "#upload_to_ftp2", function(){
	if ($(this).is(':checked')) {
		$('.ftp_Settings_section2').slideDown();
	} else {
		$('.ftp_Settings_section2').slideUp();
	}
});

$(document).on("change", "#email_notification", function(){
	if ($(this).is(":checked")) {
		$('.email_settings_item').slideDown();
	} else {
		$('.email_settings_item').slideUp();
	}
});
function removeHtmlZipFile() {
  var txt;
  var r = confirm("Are you sure you would like to remove the file?");
  if (r == true) {
    return true;
  } else {
    return false;
  }
}
$(document).on("click", ".delete_zip_file", function(){
	var this_ = $(this);
	var file_name = this_.attr('file_name');
	if (removeHtmlZipFile()) {
		 var datas = {
		  'action': 'delete_exported_zip_file',
		  'rc_nonce': rcewpp.nonce,
		  'file_name': file_name,
		};
		

		$.ajax({
		    url: rcewpp.ajax_url,
		    data: datas,
		    type: 'post',
		    dataType: 'json',
		
		    beforeSend: function(){
		
		    },
		    success: function(r){
		      if(r.success == 'true'){
		        
		        this_.closest('.exported_zip_file').remove();
		
		        
		        } else {
		          console.log('Something went wrong, please try again!');
		        }
		    	
		    }, error: function(){
		    	
		  }
		});
	}
});

$(document).on("click", ".export-btn3", function(e){
	e.preventDefault();

	var custom_link = $('.custom_link_section input').val();
	var replace_all_url = false;
	var skip_image_src_url = false;
	var full_site = false;
	var receive_email = $('#email_notification2').is(':checked') ? true : false;
	var email_lists = $('#receive_notification_email2').val();

	if ($('#replace_all_url2').is(":checked")) {
		replace_all_url = true;
	}

	if ($('#skip_image_src_url').is(":checked")) {
		skip_image_src_url = true;
	}
	
	if ($('#full_site2').is(":checked")) {
		full_site = true;
	}
	
	if (custom_link.length > 0) {
		$('.logs_list').html('');
		$('.spinner_x').removeClass('hide_spin');
		$('.download-btn').addClass('hide');
		$('.logs').show();
		$('#cancel_ftp_process').val('false');

		var ftp = false;
		var path = '';
		var ftp_data = {};
		if ($('#upload_to_ftp2').is(":checked")) {
			ftp = true;

			if ($('#ftp_path2').val() !== "") {
				path = $('#ftp_path2').val();
			}
		}

		 var datas = {
			'action': 'export_custom_url',
			'rc_nonce': rcewpp.nonce,
			'custom_link': custom_link,
			'replace_all_url': replace_all_url,
			'skip_image_src_url': skip_image_src_url,
			'full_site': full_site,
			'ftp': ftp,
			'path': path,
			'receive_email': receive_email,
			'email_lists': email_lists,
		};
		
		$.ajax({
		    url: rcewpp.ajax_url,
		    data: datas,
		    type: 'post',
		    dataType: 'json',
		
		    beforeSend: function(){
		
		    },
		    success: function(r){
		      if(r.success == 'true'){
		        console.log(r.response);
					var myVar = setInterval(myTimer, 1000);

			function myTimer() {
			   var id = $('.logs_list .log.main_log').first().attr('id');
			   
			   var datas2 = {
			    'action': 'get_exporting_logs',
			    'rc_nonce': rcewpp.nonce,
			    'log_id': id,
			  };
			  
			  $.ajax({
			      url: rcewpp.ajax_url,
			      data: datas2,
			      type: 'post',
			      dataType: 'json',
			  
			      beforeSend: function(){
			      },
			      success: function(r){
			        if(r.success == 'true'){
			        	if (r.response !== null) {

			        		$.each(r.response, function(i, v){
			        			if(v.type == 'cancel_export_process'){
								 cancel_export_process();	
								}
								else if (v.type == 'something_went_wrong') {
									some_error_appears();
								}
								else if (v.type == 'login_failed_to_ftp') {
									rc_ftp_failed();
								}
								else{
			        			var type = "";
			        			var comment = v.comment;
			        			var path = v.path;

				        		if (v.type == "copying") {
				        			type = '<span class="copying log_type">Copying</span>';
				        		} 
				        		if(v.type == "reading") {
				        			type = '<span class="reading log_type">Reading</span>';
				        		}
				        		if(v.type == "creating") {
				        			type = '<span class="creating log_type">Creating</span>';
				        		}
				        		if(v.type == "creating_zip_file") {
				        			type = '<span class="creating log_type">Creating</span>';
				        		}
				        		if(v.type == "creating_html_file") {
				        			type = '<span class="creating log_type">Creating</span>';
				        		}
				        		if(v.type == "created_html_file") {
				        			type = '<span class="success log_type">Created</span>';
				        		}
				        		if(v.type == "uploading_to_ftp") {
				        			type = '<span class="creating log_type">Uploading</span>';
				        			path = '';
				        			comment = 'files to ftp server';
				        		}

				        		if(v.type == "uploaded_to_ftp") {
				        			type = '<span class="success log_type">Successfully uploaded all files to ftp server</span>';
				        			path = '';
				        			comment = '';
				        		}
				        		/*if(v.type == "created_zip_file") {
				        			type = '<span class="success log_type">Created</span>';
				        		}*/
				        		if(v.type == "replacing") {
				        			type = '<span class="replacing log_type">Replacing</span>';
				        		}



				        		var log_text = '<span class="path">' + path + '</span>';
				        		var comment = '<span class="comment">' + comment + '</span>';

				        		if ( /*!ftp && */v.type == "created_zip_file") {
				        			log_text = '';
				        			comment = "";
				        		}

				        		var log = '<div class="log" id="'+v.id+'">'+type+' ' +log_text+ ' ' + comment + '</div>';

								if(v.type == "uploading_to_ftp") {
				        			type = '<span class="uploading_to_ftp log_type">Uploading</span>';
				        			path = '';
				        			comment = '<span class="ftp_uploaded_count">-</span> of <span class="total_files_count">-</span> files';

				        			log = '<div class="log" id="'+v.id+'">'+type+' ' +log_text+ ' ' + comment + '</div>'+log;

				        			if ($('.uploading_to_ftp').length < 1) {
										$('.logs_list').prepend(log);
									}
									ftpUploadingCountAutoRefresh = setInterval(ftpUploadingCountRcAutoRefresh, 1000);
									clearInterval(myVar);
					        	}
								if ($('#'+v.id).length < 1  && !$('#cancel_export_process').length && v.type !=='uploading_to_ftp' && v.type !=='file_uploaded_to_ftp') {
					        		$('.logs_list').prepend(log);

					        		//console.log(v.type);
					        		if(v.type == "created_zip_file") {
					        			rc_html_export_stop_interval(false, v.path);
					        		}

					        		if( v.type == 'uploaded_to_ftp' ){
										ftp_uploading_completed();
									}
								}

								}

			        		})
			        		
						}

			          } else {
			            alert('Something went wrong, please try again!');
						$('.spinner_x').addClass('hide_spin');
						$('.cancel_rc_html_export_process').hide();
			          }
			      	
			      }, error: function(){
						$('.spinner_x').addClass('hide_spin');
						$('.cancel_rc_html_export_process').hide();
			      	
			    }
			  });

			}

			function rc_html_export_stop_interval(error = false, zipUrl = "") {
			clearInterval(myVar);

			var log = '<div class="log" id="created_zip_file"><span class="log_type created_zip_file">Success</span><span> the zip file has been created!</span></div>';
			$('.logs_list').prepend(log);

				$('.spinner_x').addClass('hide_spin');
				$('.cancel_rc_html_export_process').hide();
				var log = '<div class="log" id="ready_to_download" style="color: #1c6f1c;"><span class="log_type ready_to_download"></span><span> The file is ready to download.</span></div>';
				$('.logs_list').prepend(log);

				$('.download-btn').attr('href', zipUrl).removeClass('hide');



			/*if (!error) {
				var log = '<div class="log" id="created"><span class="log_type creating_main_file">Success</span><span> the main html file has been created!</span></div>';
				if ($('.creating_main_file').length < 1) {
					$('.logs_list').prepend(log);
				}
				setTimeout(function() {
					var log = '<div class="log" id="creating_zip_file"><span class="log_type creating creating_zip_file">Creating</span><span> zip file.</span></div>';
					if ($('.creating_zip_file').length < 1) {
						$('.logs_list').prepend(log);	
					}
				}, 50);
			}*/

		} 
			 function cancel_export_process() {
				clearInterval(myVar);
				$('.spinner_x').addClass('hide_spin');
				$('.cancel_rc_html_export_process').hide();

				if ($('#cancel_export_process').length < 1) {
					var log = '<div class="log" id="cancel_export_process"><span class="log_type alert">Alert</span><span> Export process has been canceled!</span></div>';
					$('.logs_list').prepend(log);
				}
			}
			 function some_error_appears() {
				clearInterval(myVar);
				$('.spinner_x').addClass('hide_spin');
				$('.cancel_rc_html_export_process').hide();
				if ($('#cancel_export_process').length < 1) {
					var log = '<div class="log" id="cancel_export_process"><span class="log_type alert">Alert</span><span> Export process has been canceled!</span></div>';
					$('.logs_list').prepend(log);
				}
			}
			 function rc_ftp_failed() {
				clearInterval(myVar);
				$('.spinner_x').addClass('hide_spin');
				$('.cancel_rc_html_export_process').hide();

				if ($('#cancel_export_process').length < 1) {
					var log = '<div class="log" id="cancel_export_process"><span class="log_type alert">Alert</span><span> FTP login failed!</span></div>';
					$('.logs_list').prepend(log);
				}
			}
			function rc_suddenly_stop(argument) {
				clearInterval(myVar);
				$('.spinner_x').addClass('hide_spin');
				$('.cancel_rc_html_export_process').hide();
			}

			function ftp_uploading_completed() {
				clearInterval(myVar);
				$('.spinner_x').addClass('hide_spin');
				$('.cancel_rc_html_export_process').hide();
			}
		        
		        } else {
		          console.log('Something went wrong, please try again!');
		        }
		    	
		    }, error: function(){
		    	
		  }
		});
	}
	else{
		alert('Please Enter a url');
	}


});

$(document).on("click", "#test_ftp_connection", function(e){
	e.preventDefault();

	var ftp_data = {};
	if ($('#ftp_host2').val() !== "") {
		ftp_data['host'] = $('#ftp_host3').val();
	}

	if ($('#ftp_user2').val() !== "") {
		ftp_data['user'] = $('#ftp_user3').val();
	}

	if ($('#ftp_pass2').val() !== "") {
		ftp_data['pass'] = $('#ftp_pass3').val();
	}

	if ($('#ftp_path2').val() !== "") {
		ftp_data['path'] = $('#ftp_path3').val();
	}

	var ftp_data = JSON.stringify(ftp_data)
	
	 var datas = {
	  'action': 'rc_check_ftp_connection_status',
	  'rc_nonce': rcewpp.nonce,
	  'ftp_data': ftp_data,
	};
	
	$.ajax({
	    url: rcewpp.ajax_url,
	    data: datas,
	    type: 'post',
	    dataType: 'json',
	
	    beforeSend: function(){
	
	    },
	    success: function(r){
	      	if(r.success == 'true'){
	        	console.log(r.response);
				if (r.response) {
					$('.tab_ftp_status').addClass('connected').removeClass('not_connected');
					$('.ftp_status .ftp_connected').show();
					$('.ftp_status .ftp_not_connected').hide();
					$('.ftp_authentication_failed').hide();
					$('.ftp_upload_checkbox').removeClass('ftp_disabled');
					$('.ftp_upload_checkbox').find('input').attr('disabled', 'disabled');
					setTimeout(function() {
						window.location.reload();
					}, 3000);
				}
				else{
					$('.tab_ftp_status').removeClass('connected').addClass('not_connected');
					$('.ftp_status .ftp_connected').hide();
					$('.ftp_status .ftp_not_connected').text('Authentication failed').show();
					$('.ftp_authentication_failed').show();
					$('.ftp_upload_checkbox').addClass('ftp_disabled');
					$('.ftp_upload_checkbox').find('input').removeAttr('disabled');
				}
	        
	        } else {
	          console.log('Something went wrong, please try again!');
	        }
	    	
	    }, error: function(){
	    	
	  }
	});
});

$(document).on("click", ".ftp_dark_blur", function(){
	$(this).fadeOut(300);
	$('.ftp_path_select').fadeOut(300);
});

$(document).on("click", ".ftp_path_browse1", function(e){
	e.preventDefault();
	$(".ftp_dark_blur").fadeIn(300);
	$('.ftp_path_select').fadeIn(300);

	 var datas = {
	  'action': 'rc_html_export_get_dir_path',
	  'rc_nonce': rcewpp.nonce,
	  'path': $('#ftp_path').val(),
	};
	
	$.ajax({
	    url: rcewpp.ajax_url,
	    data: datas,
	    type: 'post',
	    dataType: 'json',
	
	    beforeSend: function(){
			$('.loading_section').show();
			$('.ftp_path_select .spinner_x').removeClass('hide_spin');
			$('.ftp_path_select .list-group').addClass('blurry');

	    },
	    success: function(r){
	      if(r.success == 'true'){
	       // console.log(r.response);
			$('.ftp_dir_lists').html(r.response);
	        
	        } else {
	          console.log('Something went wrong, please try again!');
	        }

			$('.loading_section').hide();
			$('.ftp_path_select .list-group').removeClass('blurry');
	    	
	    }, error: function(){
	    	
	  }
	});
});

$(document).on("click", ".ftp_path_select .list-group-item", function(){
	var path = $(this).attr('dir_path');
	 var datas = {
	  'action': 'rc_html_export_get_dir_path',
	  'rc_nonce': rcewpp.nonce,
	  'path': path,
	};
	
	$.ajax({
	    url: rcewpp.ajax_url,
	    data: datas,
	    type: 'post',
	    dataType: 'json',
	
	    beforeSend: function(){
			$('.loading_section').show();
			$('.ftp_path_select .spinner_x').removeClass('hide_spin');
			$('.ftp_path_select .list-group').addClass('blurry');
	    },
	    success: function(r){
	      if(r.success == 'true'){
	        //console.log(r.response);
	
	        $('.ftp_dir_lists').html(r.response);
	        } else {
	          console.log('Something went wrong, please try again!');
	        }
			$('.loading_section').hide();
			$('.ftp_path_select .list-group').removeClass('blurry');
	    	
	    }, error: function(){
	    	
	  }
	});
});

$(document).on("click", ".ftp_select_path", function(e){
	e.preventDefault();
	var current_path = $('.ftp_current_path').text();
	$('#ftp_path, #ftp_path2').val(current_path);
	$('.ftp_dark_blur').click();
});

$(document).on("click", ".ftp_disabled", function(){
	alert("Please connect to the ftp server first from the \"FTP Settings\" tab");
});

$(document).on("click", ".cancel_rc_html_export_process", function(e){
	e.preventDefault();

	$('#cancel_ftp_process').val('true');

	var datas = {
	  'action': 'cancel_rc_html_export_process',
	  'rc_nonce': rcewpp.nonce,
	  'post2': '',
	};
	
	$.ajax({
	    url: rcewpp.ajax_url,
	    data: datas,
	    type: 'post',
	    dataType: 'json',
	
	    beforeSend: function(){
	
	    },
	    success: function(r){
	      	if(r.success == 'true'){
	        	console.log(r.response);
	        } else {
	          console.log('Something went wrong, please try again!');
	        }
	    	
	    }, error: function(){
	    	
	  	}
	});
});

})( jQuery );


function ajaxImageFileUpload(upload_id, process_url, after_upload_url, upload_to, error_file_choose) {
	if ($("#" + upload_id + "_browse").val() != '') {
		ajax_fn = 'uploading';
		$("#loading").ajaxStart(function() {
			if (ajax_fn == 'uploading') {
				$(this).show();
				$("#img_div_border").hide();
				$("#img_wrapper").hide();
			}
		})
		.ajaxComplete(function() {
			if (ajax_fn == 'uploading') {
				$(this).hide();
				$("#img_wrapper").show();
				ajax_fn = '';
			}
		});

		$.ajaxFileUpload( {
			url: process_url,
			secureuri: false,
			fileElementId: upload_id + '_browse',
			dataType: 'json',
			success: function (data, status) {
				if(typeof(data.error_msg) != 'undefined') {
					if(data.error_msg != '') {
						alert(data.error_msg);
						$("#loading").hide();
					} else {
						ajax_fn = '';
						$.post(after_upload_url, {uploaded_file:data.uploaded_file, crop:$("input[name=crop]").attr('checked')}, function(data) {
							if (data.error_msg == '') {
								$("#img").attr('src', upload_to + data.file_name);
								$("#img_div_border").show();
								$("#img_wrapper").show();
								$("#" + upload_id).val(data.file_name);
							} else {
								alert(data.error_msg);
							}
							$("#loading").hide();
						}, 'json')
						.error(function() {
							alert('Unknown error!');
						});
					}
				} else {
					$("#loading").hide();
				}
			},
			error: function (data, status, e) {
				alert(e);
				$("#loading").hide();
			}
		})
		return false;
	} else {
		alert(error_file_choose);
	}
}

function ajaxImageFileUploadToGallery(file_title, upload_id, process_url, after_upload_url, upload_to, error_file_choose) {
	if ($("#" + upload_id + "_browse").val() != '') {
		ajax_fn = 'uploading';
		$("#loading").ajaxStart(function() {
			if (ajax_fn == 'uploading') {
				$(this).show();
				$("#upload_wrapper").hide();
			}
		})
		.ajaxComplete(function() {
			if (ajax_fn == 'uploading') {
				$(this).hide();
				$("#upload_wrapper").show();
				ajax_fn = '';
			}
		});

		$.ajaxFileUpload( {
			url: process_url,
			secureuri: false,
			fileElementId: upload_id + '_browse',
			dataType: 'json',
			success: function (data, status) {
				if(typeof(data.error_msg) != 'undefined') {
					if(data.error_msg != '') {
						alert(data.error_msg);
						$("#loading").hide();
					} else {
						ajax_fn = '';
						$.post(after_upload_url, {image_title:file_title, uploaded_file:data.uploaded_file}, function(data) {
							if (data.error_msg == '') {
								if (appendRowToTable(data))
									$("#upload_wrapper").show();
								$("#title").val('');
							} else {
								alert(data.error_msg);
								$("#title").val('');
								$("#loading").hide();
							}
							$("#loading").hide();
						}, 'json')
						.error(function() {
							alert('Unknown error!');
						});
					}
				} else {
					$("#loading").hide();
				}
			},
			error: function (data, status, e) {
				alert(e);
				$("#title").val('');
				$("#loading").hide();
			}
		})
		return false;
	} else {
		alert(error_file_choose);
	}
}

function ajaxFileUploadToStorage(file_title, file_types_folder, upload_id, process_url, after_upload_url, upload_to, error_file_choose) {
	if ($("#" + upload_id + "_browse").val() != '') {
		ajax_fn = 'uploading';
		$("#loading").ajaxStart(function() {
			if (ajax_fn == 'uploading') {
				$(this).show();
				$("#upload_wrapper").hide();
			}
		})
		.ajaxComplete(function() {
			if (ajax_fn == 'uploading') {
				$(this).hide();
				$("#upload_wrapper").show();
				ajax_fn = '';
			}
		});

		$.ajaxFileUpload( {
			url: process_url,
			secureuri: false,
			fileElementId: upload_id + '_browse',
			dataType: 'json',
			success: function (data, status) {
				if(typeof(data.error_msg) != 'undefined') {
					if(data.error_msg != '') {
						alert(data.error_msg);
						$("#loading").hide();
					} else {
						ajax_fn = '';
						$.post(after_upload_url, {file_title:file_title, uploaded_file:data.uploaded_file/*, file_name:data.uploaded_file*/}, function(data) {
							if (data.error_msg == '') {
								if (appendRowToTable(data))
									$("#upload_wrapper").show();
								$("#title").val('');
							} else {
								alert(data.error_msg);
								$("#title").val('');
							}
							$("#loading").hide();
						}, 'json')
						.error(function() {
							alert('Unknown error!');
						});
					}
				} else {
					$("#loading").hide();
				}
			},
			error: function (data, status, e) {
				alert(e);
				$("#loading").hide();
			}
		})
		return false;
	} else {
		alert(error_file_choose);
	}
}

function ajaxBannerFileUpload(upload_id, process_url, after_upload_url, upload_to, error_file_choose, width, height) {
	if ($("#" + upload_id + "_browse").val() != '') {
		ajax_fn = 'uploading';
		$("#loading").ajaxStart(function() {
			if (ajax_fn == 'uploading') {
				$(this).show();
				$("#img_div_border").hide();
				$("#img_wrapper").hide();
			}
		})
		.ajaxComplete(function() {
			if (ajax_fn == 'uploading') {
				$(this).hide();
				$("#img_wrapper").show();
				ajax_fn = '';
			}
		});

		$.ajaxFileUpload( {
			url: process_url,
			secureuri: false,
			fileElementId: upload_id + '_browse',
			dataType: 'json',
			success: function (data, status) {
				if(typeof(data.error_msg) != 'undefined') {
					if(data.error_msg != '') {
						alert(data.error_msg);
						$("#loading").hide();
					} else {
						ajax_fn = '';
						$.post(after_upload_url, {uploaded_file:data.uploaded_file}, function(data) {
							if (data.error_msg == '') {
								if (data.flash) {
									$("#img_div_border").hide();
									$("#flash_div_border").show();
									swfobject.embedSWF(upload_to + data.file_name, "flash_banner", width, height, "9.0.0");
									$("#is_uploaded_flash").val(1);
								} else {
									$("#img_div_border").show();
									$("#flash_div_border").hide();
									$("#img").attr('src', upload_to + data.file_name);
									$("#is_uploaded_flash").val(0);
								}
								$("#img_wrapper").show();
								$("#" + upload_id).val(data.file_name);
							} else {
								alert(data.error_msg);
							}
							$("#loading").hide();
						}, 'json')
						.error(function() {
							alert('Unknown error!');
						});
					}
				} else {
					$("#loading").hide();
				}
			},
			error: function (data, status, e) {
				alert(e);
				$("#loading").hide();
			}
		})
		return false;
	} else {
		alert(error_file_choose);
	}
}



jQuery.extend({
	

    createUploadIframe: function(id, uri)
	{
			//create frame
            var frameId = 'jUploadFrame' + id;
            
            if(window.ActiveXObject) {
            	if ($.browser.msie && $.browser.version=="9.0") {
            		var io = document.createElement("iframe");
	                io.setAttribute("id", frameId);
	                io.setAttribute("name", frameId);
            	} else {
                	var io = document.createElement('<iframe id="' + frameId + '" name="' + frameId + '" />');
            	}

                if(typeof uri== 'boolean'){
                    io.src = 'javascript:false';
                }
                else if(typeof uri== 'string'){
                    io.src = uri;
                }
            }
            else {
                var io = document.createElement('iframe');
                io.id = frameId;
                io.name = frameId;
            }
            io.style.position = 'absolute';
            io.style.top = '-1000px';
            io.style.left = '-1000px';

            document.body.appendChild(io);

            return io			
    },
    createUploadForm: function(id, fileElementId, serverPath, allowed_types)
	{
		//create form	
		var formId = 'jUploadForm' + id;
		var fileId = 'jUploadFile' + id;
		var form = $('<form  action="" method="POST" name="' + formId + '" id="' + formId + '" enctype="multipart/form-data"></form>');	
		var oldElement = $('#' + fileElementId);
		var newElement = $(oldElement).clone();
		$(oldElement).attr('id', fileId);
		$(oldElement).before(newElement);
		$(oldElement).appendTo(form);
		$('<input type=hidden name="server_path" value="' + serverPath + '">').appendTo(form);
		$('<input type=hidden name="allowed_types" value="' + allowed_types + '">').appendTo(form);
		//set attributes
		$(form).css('position', 'absolute');
		$(form).css('top', '-1200px');
		$(form).css('left', '-1200px');
		$(form).appendTo('body');		
		return form;
    },

    ajaxFileUpload: function(s) {
        // TODO introduce global settings, allowing the client to modify them for all requests, not only timeout		
        s = jQuery.extend({}, jQuery.ajaxSettings, s);
        var id = new Date().getTime()        
		var form = jQuery.createUploadForm(id, s.fileElementId, s.serverPath, s.allowed_types);
		var io = jQuery.createUploadIframe(id, s.secureuri);
		var frameId = 'jUploadFrame' + id;
		var formId = 'jUploadForm' + id;		
        // Watch for a new set of requests
        if ( s.global && ! jQuery.active++ )
		{
			jQuery.event.trigger( "ajaxStart" );
		}            
        var requestDone = false;
        // Create the request object
        var xml = {}   
        if ( s.global )
            jQuery.event.trigger("ajaxSend", [xml, s]);
        // Wait for a response to come back
        var uploadCallback = function(isTimeout)
		{			
			var io = document.getElementById(frameId);
            try 
			{				
				if(io.contentWindow)
				{
					 xml.responseText = io.contentWindow.document.body?io.contentWindow.document.body.innerHTML:null;
                	 xml.responseXML = io.contentWindow.document.XMLDocument?io.contentWindow.document.XMLDocument:io.contentWindow.document;
					 
				}else if(io.contentDocument)
				{
					 xml.responseText = io.contentDocument.document.body?io.contentDocument.document.body.innerHTML:null;
                	xml.responseXML = io.contentDocument.document.XMLDocument?io.contentDocument.document.XMLDocument:io.contentDocument.document;
				}						
            }catch(e)
			{
				jQuery.handleError(s, xml, null, e);
			}
            if ( xml || isTimeout == "timeout") 
			{				
                requestDone = true;
                var status;
                try {
                    status = isTimeout != "timeout" ? "success" : "error";
                    // Make sure that the request was successful or notmodified
                    if ( status != "error" )
					{
                        // process the data (runs the xml through httpData regardless of callback)
                        var data = jQuery.uploadHttpData( xml, s.dataType );    
                        // If a local callback was specified, fire it and pass it the data
                        if ( s.success )
                            s.success( data, status );
    
                        // Fire the global callback
                        if( s.global )
                            jQuery.event.trigger( "ajaxSuccess", [xml, s] );
                    } else
                        jQuery.handleError(s, xml, status);
                } catch(e) 
				{
                    status = "error";
                    jQuery.handleError(s, xml, status, e);
                }

                // The request was completed
                if( s.global )
                    jQuery.event.trigger( "ajaxComplete", [xml, s] );

                // Handle the global AJAX counter
                if ( s.global && ! --jQuery.active )
                    jQuery.event.trigger( "ajaxStop" );

                // Process result
                if ( s.complete )
                    s.complete(xml, status);

                jQuery(io).unbind()

                setTimeout(function()
									{	try 
										{
											$(io).remove();
											$(form).remove();	
											
										} catch(e) 
										{
											jQuery.handleError(s, xml, null, e);
										}									

									}, 100)

                xml = null

            }
        }
        // Timeout checker
        if ( s.timeout > 0 ) 
		{
            setTimeout(function(){
                // Check to see if the request is still happening
                if( !requestDone ) uploadCallback( "timeout" );
            }, s.timeout);
        }
        try 
		{
           // var io = $('#' + frameId);
			var form = $('#' + formId);
			$(form).attr('action', s.url);
			$(form).attr('method', 'POST');
			$(form).attr('target', frameId);
            if(form.encoding)
			{
                form.encoding = 'multipart/form-data';				
            }
            else
			{				
                form.enctype = 'multipart/form-data';
            }			
            $(form).submit();

        } catch(e) 
		{			
            jQuery.handleError(s, xml, null, e);
        }
        if(window.attachEvent){
            document.getElementById(frameId).attachEvent('onload', uploadCallback);
        }
        else{
            document.getElementById(frameId).addEventListener('load', uploadCallback, false);
        } 		
        return {abort: function () {}};	

    },

    uploadHttpData: function( r, type ) {
        var data = !type;
        data = type == "xml" || data ? r.responseXML : r.responseText;
        // If the type is "script", eval it in global context
        if ( type == "script" )
            jQuery.globalEval( data );
        // Get the JavaScript object, if JSON is used.
        if ( type == "json" )
            eval( "data = " + data );
        // evaluate scripts within html
        if ( type == "html" )
            jQuery("<div>").html(data).evalScripts();
			//alert($('param', data).each(function(){alert($(this).attr('value'));}));
        return data;
    }
});


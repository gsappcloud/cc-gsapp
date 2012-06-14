$(document).ready(function(){
	
	if ($("#fetcher-body")[0] && CKEDITOR!=null) {
		CKEDITOR.replace( 'fetcher-body',{
			 toolbar : 'Basic'
		} );
	}
	
	
	
	$('#link-info .imgs img').live("click",function(){

		if($(this).hasClass('active')) {
			$(this).removeClass('active');

			imagenum = $(this).attr('class');

			$('input#'+imagenum).remove();

		} else {

			imagesrc = $(this).attr('src');
			imagenum = $(this).attr('class');

			$(this).remove();

			var image = '<tr class="draggable odd"> \
				<td class="content-multiple-drag"> \
					<a class="tabledrag-handle" href="#" title="Drag to re-order"><div class="handle">&nbsp;</div></a> \
				</td> \
\
				<td> \
					<div id="edit-' + imagenum + '-ahah-wrapper"> \
						<div id="image1" class="form-item edit-field-images-upload"> \
 							<div class="filefield-element clear-block"> \
								<div class="widget-preview"> \
									<div class="imagefield-preview"> \
										<img alt="Image preview" title="peace9.png" src="' + imagesrc + '" width="105"> \
									</div> \
								</div> \
\
								<div class="widget-edit"> \
									<input type="hidden" value="99" id="edit-field-images-0-fid" name="field_images[0][fid]"> \
\
									<div id="edit-field-images-0-data-alt-wrapper" class="form-item edit-field-images-data-alt"> \
 										<label for="edit-field-images-0-data-alt">Alternate Text </label> \
 										<div class="description">This text will be used by screen readers, search engines, or when the image cannot be loaded.</div> \
 										<input type="text" class="form-text imagefield-text" value="" size="60" id="edit-field-images-0-data-alt" name="imagealt[]" maxlength="80"> \
									</div> \
									<div id="edit-field-images-0-data-title-wrapper" class="form-item edit-field-images-data-title"> \
 										<label for="edit-field-images-0-data-title">Title </label> \
 										<div class="description">The title is used as a tool tip when the user hovers the mouse over the image.</div> \
 										<input type="text" class="form-text imagefield-text" value="" size="60" id="edit-field-images-0-data-title" name="imagetitle[]" maxlength="500"> \
									</div> \
\
									<input type="hidden" value="' + imagesrc + '" name="imagesrc[]" id="' + imagenum +'" /> \
									<input type="submit" class="form-submit ahah-processed" value="Remove" id="' + imagenum + '_filefield_remove"> \
								</div> \
							</div> \
						</div> <!--/image--> \
					</div> \
				</td> \
			</tr>';

			$('#field_images_values').append(image);

			$('#' + imagenum + '_filefield_remove').live("click",function(){
				$(this).parents('tr').remove();
				return false;
			});
		}							

	});
	
	
	$("#fetch-data").click(function(){
		var loading = '<div class="clearfix loading"><img src="/sites/all/themes/gsapp/images/ajax-loader.gif" /></div>';
		$('#fetch-wrapper').append(loading);   
		
		var val = $("#share-field").val();
		
		$('#source_url').attr('value', val);
		
		var imageCount = 0;
		if (isUrl(val)) {
			$.get("?u="+val,function(doc){
				//parse title
				$doc = $("<d>"+doc+"</d>");				
				var title = $doc.find("title").text();
				if (title) title = $("<div/>").html($.trim(title)).text();
				else {
					title = $("h1,h2,h3",doc).filter(filterTitle).eq(0).text();
					title = $.trim(title);
				}
				if (title) {
					$('#title').attr('value', title);
					$('#source_title').attr('value', title);
					$("#text textarea").val(title);
				}					
				else { $("#title").eq(0).text(val); }
								
				
				desc = $doc.find("meta[name='description']").attr("content");
				if (desc!=null) {
					if (CKEDITOR.instances["fetcher-body"]!=null) {
						CKEDITOR.instances["fetcher-body"].setData(desc);
					}else $('#fetcher-body').val(desc);
				}

				key = $doc.find("meta[name='keywords']").attr("content");
				$('#tags').text(key);
				
				//images
				$("#link-info .imgs").html("");
				$("#note-form").show();
				
				urlpath = val.replace(/http[s]?\:\/\//g,"").split('/');
				urlbase = urlpath[0];
				host = window.location.hostname;
				port = window.location.port;
				
				$("img",$doc).each(function(i,el){
					if (el.src.indexOf(host)!=-1) {
						var re = new RegExp(host,"g");
						newimg = el.src.replace(re, urlbase);
					} else {
						newimg = el.src;
					}
					if (port!=80) newimg = newimg.replace(/\:\d+/,"");
					
					//if (el.width>50 && el.height>50) {
						imageCount++;
						$("<img src='"+newimg+"' class='image" + imageCount + "'>").appendTo($("#link-info .imgs"));
						
					//}
				});
				
				$('div.loading').remove();
				
				$('#submit').show();
				
				
			});
		}
		
		return false;
	});
	

	// error messaging
	$('#submit').click(function(){
		
		if ($('#field_images_values tbody').children().length==0) {
			$("<img src='http://ccgsapp.org/sites/all/themes/gsapp/images/blu.png' class='image1'>")
			.appendTo($("#link-info .imgs"))
			.click();
		}
		
		// check required fields
		$('textarea.required, input.required').each(function(){
			
			// Add/remove error class
			if($(this).val() == false) {
				$(this).addClass('error');
			} else {
				$(this).removeClass('error');
			}
			
			// only add the error message once
			if($('#messages').length == 0) {
				$('<div id="messages"><div class="messages">Some required fields are empty.</div></div>').insertAfter('#header');
			}
			
			// scroll to the top of the page so the user can see the error message
			$.scrollTo( '#header', 0 );
			
		});
		
		// if there are is no more missing data, remove error message box
		if($('.error').length == 0 && $('.edit-field-images-upload').length > 0) {
			$('#messages').remove();
		}
		
		// if the error message box is there, don't submit the form
		if($('#messages').length > 0) {
			return false;
		}
		
		$("#admin-title h1").text("SAVING...");
		
	});
});


function filterTitle(ind) {
	var titlePattern = /^(?!blog).*title.*$/m;
	if (this.className.match(titlePattern)) return true;
	else if (this.id.match(titlePattern)) return true;
	else if ($(this).children().filter(filterTitle).length>0) return true;
	else return false;
}

function isUrl(string) {
	var ret = false;
	var pattern = /((mailto\:|(news|(ht|f)tp(s?))\:\/\/){1}\S+)/;
	if (string.match(pattern)) ret = true;
	else if (string.match(/\w+.\w+/)) ret = true;
	
	return ret;
}
<?php

require 'curl.php';

if (isset($_GET["u"])) {
	$curl = new Curl();
	$url = $_GET["u"];
	$html = $curl->get($url);
	echo $html;
	exit;
}
$ra = rand();

?>
	
		<div id="fetch-wrapper" class="form-item fetch">
		 <label for="fetch">Url to fetch data from</label>
		 <input type="text" class="form-text" value="" size="60" id="share-field" name="fetch"> <a href="#" class="outlink" id="fetch-data">Fetch</a>
		</div>
	
		<?php global $base_url; ?>
		<form method="post" action="<?php echo $base_url; ?>/create-note/submit" id="note-form">
			
			<div id="edit-title-wrapper" class="form-item edit-title">
			 <label for="title">Title <span title="This field is required." class="form-required">*</span></label>
			 <input type="text" class="form-text required" value="" size="60" id="title" name="title" maxlength="255">
			</div>
			
			
			<div id="text" class="form-item text">
			 <label for="edit-field-excerpt-0-value">Excerpt <span title="This field is required." class="form-required">*</span></label>
			 <div class="description">This will be used in the "Excerpt view"</div>
			 <div class="resizable-textarea"><span><textarea class="form-textarea resizable required ckeditor-mod textarea-processed" name="text" rows="5" cols="60" style=""></textarea></span></div>
			</div>
			
			
			<div id="tags-wrapper" class="form-item edit-tags">
			 <label for="tags">Tags <span title="This field is required." class="form-required">*</span></label>
			 <div class="description">A comma-separated list of terms describing this content. Example: funny, bungee jumping, "Company, Inc.".</div>
			 <input type="text" class="form-text required" value="" size="60" id="tags" name="tags" maxlength="1024">
			</div>
			
			<div class="link-field-subrow clear-block">
				<div class="link-field-title link-field-column">
					<div id="edit-field-source-0-title-wrapper" class="form-item edit-field-source-title">
			 			<label for="source_title">Source Title </label>
			 			<input type="text" class="form-text" value="" size="60" id="source_title" name="source_title" maxlength="255">
					</div>
				</div>
				<div class="link-field-url link-field-column">
					<div id="edit-field-source-0-url-wrapper" class="form-item edit-field-source-url">
			 			<label for="source_url">Source URL </label>
			 			<input type="text" class="form-text" value="" size="60" id="source_url" name="source_url" maxlength="2048">
					</div>
				</div>
			</div>
			
			<div id="body" class="form-item body">
			 <label for="body">Body <span title="This field is required." class="form-required">*</span></label>
			 <div class="resizable-textarea"><span><textarea class="form-textarea resizable ckeditor-mod" id="fetcher-body" name="body" rows="5" cols="60" style=""></textarea></span></div>
			</div>
			
			<div id='link-info' class="form-item">
				<label>Select images for this note</label>
				<div class="imgs"></div>
			</div>
			
			
			<div id="field-images-items">
				<table class="sticky-header">
					<thead>
						<tr>
							<th colspan="2">Images: </th>
						</tr>
					</thead>
				</table>
				
				<table class="content-multiple-table sticky-enabled tabledrag-processed sticky-table" id="field_images_values">
					
					<tbody>
			 			
					</tbody>
				</table>
			</div> <!--/field-images-items-->
			
			<br><br>
			<input type="submit" id="submit" value="Save" class="clearfix">
		</form>
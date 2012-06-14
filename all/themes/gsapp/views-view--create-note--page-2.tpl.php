		<?php
		
		$node = new stdClass();
		$node->type = 'notes'; //This can be any node type
		
		// node_object_prepare($node);
		
		//Main Node Fields
		$node->name = $_POST['title'];
		$node->title = $node->name;
		$node->field_excerpt[0]['value'] = $_POST['text'];
		
		$node->field_source[0]['url'] = $_POST['source_url'];
		$node->field_source[0]['title'] = $_POST['source_title'];
		
		$tags = $_POST['tags'];
		
		$node->taxonomy = array('tags' => array('1' => $tags));
		
		$node->body = $_POST['body'];
		$node->created = time();
		$node->changed = $node->created;
		$node->name = $GLOBALS['user']->name;
		$node->promote = 0; // Display on front page ? 1 : 0
		$node->sticky = 0;  // Display top of page ? 1 : 0
		$node->format = 1;  // 1:Filtered HTML, 2: Full HTML
		$node->status = 1;   // Published ? 1 : 0
		$node->twitter[post] = 1;
		$node->facebook[post] = 1;
		$node->disqus_status = 1;

	  $mime = 'image/jpeg';		
	
		$x = 0;
		foreach($_POST['imagesrc'] as $img) {
			$temp = $img;

			save_remote($temp);

			$filename = 'sites/default/files/notes/' . basename($temp);
		
		  $file = new stdClass();
		  $file->filename = basename($filename);
		  $file->filepath = $filename;
		  $file->filemime = $mime;
		  $file->filesize = filesize($filename);

		  $file->uid = $uid;
		  $file->status = FILE_STATUS_PERMANENT;
		  $file->timestamp = time();
		
		  drupal_write_record('files', $file);


			$node->field_images[$x] = array(
	      'fid' => $file->fid,
	      'title' => basename($file->filename),
	      'filename' => $file->filename,
	      'filepath' => $file->filepath,
	      'filesize' => $file->filesize,
	      'mimetype' => $mime,
	      'description' => basename($file->filename),
	      'list' => 1,
				'data' => array(
					'alt' => $_POST['imagealt'][$x],
					'title' => $_POST['imagetitle'][$x]
				)
		  ); 
			
			$x++; 
		} // end foreach
		
		
		
		if ($node = node_submit($node)) {
				node_save($node);
				
				drupal_set_message(t("Node <a href='/" . $node->path . "'>" . $node->title . "</a> added correctly"));
				
				drupal_goto('create-note');
			} else {
				drupal_set_message(t("Node ".$node->title." added incorrectly"), "error");
			}
		

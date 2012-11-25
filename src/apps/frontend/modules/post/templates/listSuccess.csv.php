<?php
foreach ($posts as $post) {
	$outstream = fopen("php://temp", 'w');
	$postData = array(html_entity_decode($post->track_author), html_entity_decode($post->track_title), sprintf('%s/tracks/%s', $sf_request->getUriPrefix(), $post->track_filename), url_for('@post_show?slug='.$post->slug, true), html_entity_decode($post->getSfGuardUser()->username));
	fputcsv($outstream, $postData, ',', '"');
	rewind($outstream);
	$csv = fgets($outstream);
	echo $csv;
	fclose($outstream);
}

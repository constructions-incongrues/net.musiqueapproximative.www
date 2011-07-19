<?php
foreach ($posts as $post) {
	$outstream = fopen("php://temp", 'w');
	$postData = array($post->track_author, $post->track_title, sprintf('%s/tracks/%s', $sf_request->getUriPrefix(), $post->track_filename), url_for('@post_show?slug='.$post->slug, true));
	fputcsv($outstream, $postData, ',', '"');
	rewind($outstream);
	$csv = fgets($outstream);
	echo $csv;
	fclose($outstream);
}

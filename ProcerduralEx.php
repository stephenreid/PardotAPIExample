<?php 
// This is an example of hitting the pardot API with extremely procedureal code
// This will be a bit of a pain to write, functions ftw.

//authenticate to get an api key for an hour
$userKey ='UserKeyCanBeFoundByClickingOnYourEmailAddressInTheTopRightOfPardot';
$context = stream_context_create(array(
	'http'	=> array(
		'method'	=> 'POST',
		'header'	=> 'Content-type: application/x-www-form-urlencoded',
		'content'	=> http_build_query(array('username'=>'test@pardot.com','password'=>'123abc','user_key'=>$userKey)),
	)	
));
$res = file_get_contents('https://pi.pardot.com/api/login/version/3',false,$context);
$res = simplexml_load_string($res);
$apiKey = $res->api_key;

//create a new prospect (upsert is often safer), for newProspect@pardot.com
$context = stream_context_create(array(
	'http'	=> array(
		'method'	=> 'POST',
		'header'	=> 'Content-type: application/x-www-form-urlencoded',
		'content'	=> http_build_query(array('user_key'=>$userKey,'api_key'=>$apiKey,'email'=>'newProspect@pardot.com')),
	)	
));
$res = file_get_contents('https://pi.pardot.com/api/prospect/version/3/do/create',false,$context);

//update a prospect's (newProspect@pardot.com) company to Pardot
$context = stream_context_create(array(
	'http'	=> array(
		'method'	=> 'POST',
		'header'	=> 'Content-type: application/x-www-form-urlencoded',
		'content'	=> http_build_query(array('user_key'=>$userKey,'api_key'=>$apiKey,'company'=>'Pardot')),
	)	
));
$res = file_get_contents('https://pi.pardot.com/api/prospect/version/3/do/update/email/newProspect@pardot.com',false,$context);

//update a prospect's custom field
$context = stream_context_create(array(
	'http'	=> array(
		'method'	=> 'POST',
		'header'	=> 'Content-type: application/x-www-form-urlencoded',
		'content'	=> http_build_query(array('user_key'=>$userKey,'api_key'=>$apiKey,'custom_field_name'=>'custom_field_value')),
	)	
));
$res = file_get_contents('https://pi.pardot.com/api/prospect/version/3/do/update/email/newProspect@pardot.com',false,$context);

//subscribe a prospect to a list (id= 8)
$context = stream_context_create(array(
	'http'	=> array(
		'method'	=> 'POST',
		'header'	=> 'Content-type: application/x-www-form-urlencoded',
		'content'	=> http_build_query(array('user_key'=>$userKey,'api_key'=>$apiKey,'list_8'=>'1')),
	)	
));
$res = file_get_contents('https://pi.pardot.com/api/prospect/version/3/do/update/email/newProspect@pardot.com',false,$context);

//now unsubscribe them from that same list
$context = stream_context_create(array(
	'http'	=> array(
		'method'	=> 'POST',
		'header'	=> 'Content-type: application/x-www-form-urlencoded',
		'content'	=> http_build_query(array('user_key'=>$userKey,'api_key'=>$apiKey,'list_8'=>'0')),
	)	
));
$res = file_get_contents('https://pi.pardot.com/api/prospect/version/3/do/update/email/newProspect@pardot.com',false,$context);


//query all prospects updated recently
$context = stream_context_create(array(
	'http'	=> array(
		'method'	=> 'POST',
		'header'	=> 'Content-type: application/x-www-form-urlencoded',
		'content'	=> http_build_query(array('user_key'=>$userKey,'api_key'=>$apiKey)),
	)	
));
$res = file_get_contents('https://pi.pardot.com/api/prospect/version/3/do/query/updated_after/yesterday',false,$context);




?>
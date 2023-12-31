<?php
session_start();

header('Content-type:application/json;charset=utf-8');

try {
    if (
        !isset($_FILES['file']['error']) ||
        is_array($_FILES['file']['error'])
    ) {
        throw new RuntimeException('Invalid parameters.');
    }

    switch ($_FILES['file']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    //$filepath = sprintf('files/%s_%s', uniqid(), $_FILES['file']['name']);

	$path_parts = pathinfo($_FILES["file"]["name"]);
	$extension = $path_parts['extension'];
	
	$filename=uniqid().".".$extension; //nome provvisorio

	
	//Upload documenti:
	//$from=="allegati" -> da lente o cliccando sui servizi
	//$from=="allegati_cantieri" -> 
	
	$from=$_POST['from'];
	//from=="1" da button etichetta  
	//from=="7" da button documentazione tecnica
	if ($from=="1" || $from=="4" || $from=="7" || $from=="8") {
		$id_pns=$_POST['id_pns'];
		$filename=$id_pns.".".$extension;
		$sub="allegati/$id_pns";
		@mkdir($sub);
		if ($from=="1") 
			$sub="allegati/$id_pns/etic";
		if ($from=="4") 
			$sub="allegati/$id_pns/cert";
		if ($from=="7") 
			$sub="allegati/$id_pns/doc_tecnici";
		if ($from=="8") 
			$sub="allegati/$id_pns/ft";		
		@mkdir($sub);
	}	
	

	
	$filepath = "$sub/".$filename;
    if (!move_uploaded_file(
        $_FILES['file']['tmp_name'],
        $filepath
    )) {
        throw new RuntimeException('Failed to move uploaded file.');
    }


	
    // All good, send the response
    echo json_encode([
        'status' => 'ok',
        'path' => $filepath,
		'filename' =>$filename,
		'from' =>$from
	]);

} catch (RuntimeException $e) {
	// Something went wrong, send the err message as JSON
	http_response_code(400);

	echo json_encode([
		'status' => 'error',
		'message' => $e->getMessage()
	]);
}
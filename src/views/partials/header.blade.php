<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="um_url" content="{{ url('upload-manager') }}">
	<title>Upload Manager for Laravel</title>
	<link rel="stylesheet" href="https://unpkg.com/filepond/dist/filepond.css" >
	<link rel="stylesheet" href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css">
	<link rel="stylesheet" href="{{ asset('uploadManager/css/uploadManager.css?v=1.'.rand(0,9999)) }}">
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script>
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});
	</script>
</head>
<body>
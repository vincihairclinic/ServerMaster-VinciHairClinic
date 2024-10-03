<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width,maximum-scale=1,user-scalable=no,minimal-ui">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/404.css') }}">
    <title>	404 Not found </title>
</head>
<body>

<div class="stars"></div>

<div class="sun-moon">
    <div class="sun"></div>
    <div class="moon"></div>
</div>

<div id="js-hills" class="background hills"></div>
<div id="js-country" class="background country"></div>
<div id="js-foreground" class="background foreground"></div>

<div class="error-content">
    Sorry, that page never returned
</div>

<?php
try {
    $url = route('home');
}catch (Exception $e){
    $url = config('app.url');
}
?>
<a href="{{ $url }}" class="button-home">@lang('web.home')</a>

<div class="code">
    <span>4</span>
    <span>0</span>
    <span>4</span>
</div>


</body>
</html>
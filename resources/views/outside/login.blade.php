<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<form id="logout-form" action="{{ route('outside.oauthLogin') }}" method="POST">
    @csrf
    <button type="submit">ログイン</button>
</form>
</body>
</html>

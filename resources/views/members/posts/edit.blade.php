<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>タイトル</title>
</head>
<body>
@if ($errors->any())
    <ul class="error">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

@if (session('status'))
    <p class="status">{{ session('status') }}</p>
@endif

<form method="post">
    @csrf
    本文:<textarea name="body">{{ data_get($data, 'body') }}</textarea>
    <br>
    公開する：<input type="text" name="status" value="{{ data_get($data, 'status') }}">

    <input type="submit" value="更新する">
</form>
</body>
</html>

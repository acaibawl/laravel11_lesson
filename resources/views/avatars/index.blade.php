<!DOCTYPE html>
<html>
<head>
    <meta charset="utf8">
    <title>タイトル</title>
</head>
<body>
    @if($errors->any())
        @foreach($errors->all() as $error)
            ・ {{ $error }}<br>
        @endforeach
    @endif

    <form method="post" enctype="multipart/form-data">
        @csrf
        アバター：<input type="file" name="img">
        <br>
        <input type="submit" value="送信する">
    </form>
</body>
</html>

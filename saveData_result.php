<?php
date_default_timezone_set('Asia/Tokyo');//タイムゾーンの設定

//文字コードの設定
mb_language("Japanese");
mb_internal_encoding("UTF-8");






print<<<EOF
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,  user-scalable=no" >
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" type="text/css" href="./map.css">
<title>マッピング　パーティー</title>
</head>
<body>
<div id="main">
<h2>投稿ありがとうございました</h2>

<button type="button" onClick="location.href='./map.php?Latitude=$latitude&Longitude=$longitude'">入力画面に戻る</button>
<button type="button" onClick="location.href='http://pluscreative.sakura.ne.jp/suga/mapresult/'">結果表示のサイトへ</button>
<br><br><br><br>
</div>
</body>
</html>
EOF;



?>
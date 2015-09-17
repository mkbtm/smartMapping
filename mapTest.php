<?php

/*
geo.phpで書き込まれた情報を表示するためのPHP
./data/data.txtを読み込んで画面にリスト表示。
data.txtには
緯度,経度,タイトル,コメント,画像ファイル
投稿時刻も。
2015. 07 .17
makabe, tomo
*/

//文字コードの設定
mb_language("Japanese");
mb_internal_encoding("UTF-8");

//./data/data.txtを読み込む
$file_path = "./data/data.txt";
//ファイルの読み込み
$fp = fopen($file_path, 'r');//ファイルのオープン。

//ファイルの読み込み。読み込んだ値は後で使うので配列に入れる。
$i = 0;
//ファイルを最後まで１行づつ読み込む。
//緯度と経度の最小と最大を求める。
$min_lati = 999.9;
$max_lati = 0.0;
$min_long = 999.9;
$max_long = 0.0;
if ($fp){
	 while (!feof($fp)) {//ファイルが終わりでなければ
		$buffer = fgets($fp);
		//文字コードの変換　行末の改行コードも削除
		$buffer_utf = rtrim(mb_convert_encoding($buffer,"UTF-8","auto"));
		if (mb_strlen($buffer_utf) != 0){//空白行は読み飛ばす
			$data_array = explode(",", $buffer_utf);//カンマで区切ってあるデータを取り出す。
			
			$latitude[$i] = $data_array[0];
			$longitude[$i]  = $data_array[1];
			$dateString = $data_array[2];
			$title[$i]  = $data_array[3];
			$name[$i]=  $data_array[4];
			$abstruct[$i]  = $data_array[5];
			$category[$i]  = $data_array[6];
			$fileName[$i]  = $data_array[7];
			$fileURL[$i]  = "./images/" . $fileName[$i];
			
			//最小、最大のチェック
			if ($min_lati > $latitude[$i]) {$min_lati = $latitude[$i];}
			if ($max_lati < $latitude[$i]){ $max_lati = $latitude[$i];}
			
			if ($min_long > $longitude[$i]) {$min_long = $longitude[$i];}
			if ($max_long < $longitude[$i]){ $max_long= $longitude[$i];}
			
			$i++;
		}//if (mb_strlen($buffer_utf) != 0){//空白行は読み飛ばす
	}// while (!feof($fp)) {
}//if ($fp){

/*echo $min_lati."<br>";
echo $max_lati."<br>";
echo $min_long."<br>";
echo $max_long."<br>";
*/
$maxi = $i - 1;//データの個数
//マップ中心を求める。
$centerLati = ($max_lati - $min_lati)/2.0 + $min_lati;
$centerLong = ($max_long - $min_long)/2.0 + $min_long;
//echo $centerLati."<br>";
//echo $centerLong."<br>";

//webページの出力
//ヘッダー
print<<<EOF
<html>
<head>
<meta charset="UTF-8">
<title>マッピングパーティー C4NGT</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,  user-scalable=no" >
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" type="text/css" href="./mapTest.css">
<script type="text/javascript" src="./JS/jquery-1.7.1.min.js"></script>
<!--Google API用のスクリプト-->
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBETqAOqvo7enTri7QN2P7BYxkMkWBtHAo&sensor=true"></script>

<script language="JavaScript">


function addMarker(){
	markers.push(
		new google.maps.Marker({
			position: latlngs[iterator],
			map:map,
			animation: google.maps.Animation.DROP
		 })
	);
	if(iterator==latlngs.length-1){
		clearTimeout(timer);
		for(var i=0; i<markers.length; i++){
			markers[i].setAnimation(google.maps.Animation.BOUNCE);
		}
	}
	iterator++;
}



function initialize() {   
	var latlng = new google.maps.LatLng($centerLati, $centerLong);
	var options = {
		zoom: 15,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		draggable: true
	};
	var map = new google.maps.Map(document.getElementById("map"), options);
	/*var marker = new google.maps.Marker({
		position: latlng,map: map,title: "現在地"
	});*/
	// 南西
	var sw = new google.maps.LatLng($max_lati, $min_long);
	// 北東
	var ne = new google.maps.LatLng($min_lati, $max_long);
	var bounds = new google.maps.LatLngBounds(sw, ne);
	map.fitBounds(bounds);

EOF;


	 //まとめて複数のマーカーを追加
	 for ($i = 0 ; $i <= $maxi ; $i++){
print<<<EOF

		var latlng=new google.maps.LatLng($latitude[$i], $longitude[$i] );
		var marker = new google.maps.Marker({
			position: latlng, // マーカーを立てる場所の緯度・経度 
			map: map, //マーカーを配置する地図オブジェクト 
			title: '$title[$i]'
		});

EOF;
	}
	

print<<<EOF

	
}//endo of function initialize()

</script>

</head>
<body onload="initialize()">
<div id="main">
<!--google mapを表示-->
<div id="map"></div>
<!--<div id="message"></div>-->

EOF;

for ($i = 0 ; $i <= $maxi ; $i++){
$filePath = $fileURL[$i];
print<<<EOF
	<p>$latitude[$i],$longitude[$i],$dateString,$title[$i],$name[$i],$abstruct[$i],$category[$i]<a href="$filePath">$fileName[$i]</a></p>
	<hr>
EOF;
}

print<<<EOF
</div></body></html>
EOF;


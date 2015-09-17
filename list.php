<?php

/*
geo.phpで書き込まれた情報を表示するためのPHP
./data/data.txtを読み込んで画面にリスト表示。
data.txtには
緯度,経度,タイトル,コメント,画像ファイル
このあとに投稿者がつく（たぶん）
投稿IDも必要？
投稿時刻も。
2015. 07 .10
makabe, tomo
*/

//文字コードの設定
mb_language("Japanese");
mb_internal_encoding("UTF-8");

//./data/data.txtを読み込む
$file_path = "./data/data.txt";
//ファイルの読み込み
$fp = fopen($file_path, 'r');//ファイルのオープン。

//google Mapのセンター 37.91805753757115, 139.05690443146955 万代テレコムビル
$myLatitude = 37.91805753757115;
$myLongitude = 139.05690443146955;

//ファイルの読み込み。読み込んだ値は後で使うので配列に入れる。
$i = 0;
//ファイルを最後まで１行づつ読み込む。
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
			$i++;	
		}//if (mb_strlen($buffer_utf) != 0){//空白行は読み飛ばす
	}// while (!feof($fp)) {
}//if ($fp){
$maxi = $i - 1;//データの個数


//webページの出力
//ヘッダー
print<<<EOF
<html>
<head>
<meta charset="UTF-8">
<title>マッピングパーティー C4NGT</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,  user-scalable=no" >
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" type="text/css" href="./map.css">
<script type="text/javascript" src="./JS/jquery-1.7.1.min.js"></script>
<!--Google API用のスクリプト-->
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBETqAOqvo7enTri7QN2P7BYxkMkWBtHAo&sensor=true"></script>

<script language="JavaScript">
/*
 function showMarkers(){
	$.ajax({
		type:"get",
		cache:false,
		url:"include/ajax/gmapv3/markers.xml",
		success:function(xml){
			var s="";
			$(xml).find("marker").each(function(){
				var _lat=parseFloat($(this).eq(0).attr("lat"));
				var _lng=parseFloat($(this).eq(0).attr("lng"));
				var _latlng= new google.maps.LatLng(_lat, _lng);
				
				latlngs.push(_latlng);
			});
			for(var i=0; i<latlngs.length; i++){
				timer=setTimeout(addMarker, i*230);
			}
		},
		error:function(XMLHttpRequest, textStatus, errorThrown){
			//alert(textStatus);
		}
	 });
}///end of showMarkers()
*/
/* 地図にマーカー追加 */

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
	var latlng = new google.maps.LatLng($myLatitude, $myLongitude);
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
<div id="message"></div>

EOF;

for ($i = 0 ; $i <= $maxi ; $i++){
$filePath = $fileURL[$i];
print<<<EOF2
	<p>$latitude[$i],$longitude[$i],$dateString,$title[$i],$name[$i],$abstruct[$i],$category[$i]<a href="$filePath">$fileName[$i]</a></p>
	<hr>
EOF2;
}
//ファイルを最後まで１行づつ読み込む。
/*
if ($fp){

	 while (!feof($fp)) {//ファイルが終わりでなければ
	 
		$buffer = fgets($fp);
		
		//文字コードの変換　行末の改行コードも削除
		$buffer_utf = rtrim(mb_convert_encoding($buffer,"UTF-8","auto"));
		
		if (mb_strlen($buffer_utf) != 0){//空白行は読み飛ばす
			
			//echo  $buffer_utf . "<br>";
			$data_array = explode(",", $buffer_utf);//カンマで区切ってあるデータを取り出す。

			$latitude = $data_array[0];
			$longitude = $data_array[1];
			$title = $data_array[2];
			$comment = $data_array[3];
			$fileName = $data_array[4];
			$fileURL = "./images/" . $fileName;
			
			//htmlを出力
			
print<<<EOF2
<p>$latitude ,$longitude,$title,$comment,<a href="$fileURL">$fileName</a></p>
EOF2;
			
			
		}//if (mb_strlen($buffer_utf) != 0){//空白行は読み飛ばす
	}// while (!feof($fp)) {
	
}//if ($fp){

*/
print<<<EOF3
</div></body></html>
EOF3;


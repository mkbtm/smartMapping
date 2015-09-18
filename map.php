<?php

/*
smartMapping map.php 
 * Copyright (c) 2015 makabe, tomo, mkbtm1968@gmail.com, http://mkbtm.jp/
 * Licensed under the MPL License [http://www.nihilogic.dk/licenses/mpl-license.txt]
 */

date_default_timezone_set('Asia/Tokyo');//タイムゾーンの設定

mb_language("Japanese");//文字コードの設定
mb_internal_encoding("UTF-8");

printhtmlHeader();//htmlのヘッダー部分を書き出すサブルーチン

//受け取ったデータに緯度、経度が入っているか確認。無い場合には最初に開いた時。
if (isset($_GET["Latitude"]) && isset($_GET["Longitude"])) {
	//緯度経度がある場合。
	secondTimehtml();
} else {
	//緯度経度が無い場合最初に開いた時。
	firstTimehtml();
}




//サブルーチン
//ヘッダー部分を書き出す。
function printhtmlHeader(){
print<<<EOF
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,  user-scalable=no" >
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" type="text/css" href="./map.css">
<title>マッピング　パーティー</title>
<script src="./geoLocation.js"></script>
<!--Google API用のスクリプト-->
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBETqAOqvo7enTri7QN2P7BYxkMkWBtHAo&sensor=true"></script>
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/jquery.exif.js"></script>
<script src="js/jquery.canvasResize.js"></script>
<!--<script src="js/canvasResize.js"></script>-->
EOF;
}



//最初に開いた時の処理
function firstTimehtml(){
	print<<<EOF
	</head>
	<body onload = "getGeoLocation();">
	<p>データ送受信中…</p>
	<p id="result">しばらくお待ちください。</p>
	</body>
	</html>
EOF;
}



//緯度経度の情報がはいっている場合の処理。2回目以降の処理
function secondTimehtml(){
	$myLatitude = htmlspecialchars($_GET["Latitude"]);
	$myLongitude = htmlspecialchars($_GET["Longitude"]);
	
	//スマホから送られた緯度経度　とりあえずダミー 中山５丁目 デバッグ用のダミー
	//$myLatitude = 37.915878;
	//$myLongitude = 139.084496;
	
	//webページの出力
	print<<<EOF

<script language="JavaScript">
	var myPhoto=new Image();//リサイズした画像データ
	
	function sendData(){
		//入力項目を取り出し
		var title = document.getElementById("title").value;
		var name = document.getElementById("name").value;
		var category = document.getElementById("category").value;
		//var abstruct = document.getElementById("abstruct");
		var abstruct =document.getElementById('abstruct').value;
		//var abstruct =document.main.abstruct.value;
		var latitude = document.getElementById("latitude").value;
		var longitude = document.getElementById("longitude").value;
		
		var php_url = "saveData.php";
		
		//フォームの準備
		var form = document.createElement('form');
		document.body.appendChild( form );
		 
		 //title
		 var elm1 = document.createElement('input');
		elm1.setAttribute('type', 'hidden');
		elm1.setAttribute('name' ,'title');
		elm1.setAttribute('value',  title);
		form.appendChild( elm1 );

		 //画像
		var elm2 = document.createElement('input');
		elm2.setAttribute('type', 'hidden');
		elm2.setAttribute('name' ,'acceptImage');
		elm2.setAttribute('value', myPhoto );
		form.appendChild( elm2 );
		
		 //name
		 var elm3 = document.createElement('input');
		elm3.setAttribute('type', 'hidden');
		elm3.setAttribute('name' ,'name');
		elm3.setAttribute('value',  name);
		form.appendChild( elm3 );
		
		 //category
		 var elm4 = document.createElement('input');
		elm4.setAttribute('type', 'hidden');
		elm4.setAttribute('name' ,'category');
		elm4.setAttribute('value',  category);
		form.appendChild( elm4 );
		
		//abstruct
		 var elm5 = document.createElement('input');
		elm5.setAttribute('type', 'hidden');
		elm5.setAttribute('name' ,'abstruct');
		elm5.setAttribute('value',  abstruct);
		form.appendChild( elm5 );
		
		//latitude
		 var elm6 = document.createElement('input');
		elm6.setAttribute('type', 'hidden');
		elm6.setAttribute('name' ,'latitude');
		elm6.setAttribute('value',  latitude);
		form.appendChild( elm6 );
		
		//longitude
		 var elm7 = document.createElement('input');
		elm7.setAttribute('type', 'hidden');
		elm7.setAttribute('name' ,'longitude');
		elm7.setAttribute('value',  longitude);
		form.appendChild( elm7 );
		
		//cookie値の処理。登録者名を保存するためのもの
		//最初にcookieを空にする。
		var date1 = new Date();
		date1.setTime(0);
		//有効期限を過去にして書き込む
		document.cookie = "name=;expires="+date1.toGMTString();
		//これで消去完了
		//alert("write cookie:" + encodeURIComponent(name));
		//以下でnameを書き込む　有効期限は無し。
		document.cookie = "name=" + encodeURIComponent(name);
		
		//送信
		form.setAttribute('action' ,php_url);
		form.setAttribute('method', 'post' );
		form.submit();
	}// end of  function sendData()
			
							
	$().ready(function() {
		//画像を選択した時の処理
		$('input[name=photo]').change(function(e) {
			var file = e.target.files[0];//ファイルの取り出し
			//画像処理中の描画をする。
			var imgProcess = document.getElementById('img_process');
			imgProcess.style.display = "block";
			//プレビュー画像を消去する。（以前に選択した画像がある場合への対応）
			$("#image").empty();
			
			//画像のリサイズ
		 	$.canvasResize(file, {
				width: 1000,
				height: 0,
				crop: false,
				quality: 100,
				callback: function(imgData, width, height) {
					//リサイズした画像を画面に表示。
					$('<img>').load(function() {
						$(this).css({
							 'width': width/4,
						 	'height': height/4
						}).appendTo('#image');
					}).attr('src', imgData);
					myPhoto = imgData;
					//画像処理中の表示を消す。
					//var imgProcess = document.getElementById('img_process');
					imgProcess.style.display = "none";
					
					//送信ボタンを表示する。
					var sndButton = document.getElementById('sendButton');
					sndButton.style.display = "block";
		 		}
			});
		});
	});
</script>


<script language="JavaScript">
//画面の初期化手順　マップを現在位置を中心に表示
function initialize() {

	var lat_obj = new Number($myLatitude);
	var long_obj = new Number($myLatitude);
   document.getElementById("position").textContent= + String($myLatitude.toFixed(5)) + ", " + String($myLatitude.toFixed(5));
   
	var latlng = new google.maps.LatLng($myLatitude, $myLongitude);
	var options = {
		zoom: 15,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		draggable: true
	};
	var map = new google.maps.Map(document.getElementById("map"), options);
	var marker = new google.maps.Marker({
		position: latlng,map: map,title: "現在地"
	});
	
	//地図をドラッグして移動したとき。
	google.maps.event.addListener(map, 'center_changed', function(){
		var pos = map.getCenter();
		//posから緯度経度を取り出してhtmlに表示する。
		var lat = pos.lat();
		var lng = pos.lng();
		//マップ下に表示している緯度と経度を書き換える。
		//document.getElementById("position").textContent= String(lat) + ", " + String(lng) ;
		document.getElementById("position").textContent= + String(lat.toFixed(5)) + ", " + String(lng.toFixed(5));
		//送信データを書き換える。
		document.getElementById("latitude").value=String(lat) ;
		document.getElementById("longitude").value=String(lng) ;
		//マーカーの表示
		marker.setPosition(pos);
		marker.setTitle('map center: ' + pos);
		//画面上の赤い十字マークはcssの#map_curssor{で表示している。画面幅の中央、高さ方向はマップ高さの1/2に表示。
		
		
		
	});
	
	//ユーザー名用のcookieの処理
	var cookieValue = document.cookie;
	//取得したcookieにname=の文字が入っているか確認する。name=があれば値がある
	//alert(cookieValue);
	var result = cookieValue.indexOf( "name=" );
	//alert(result);
	if (result == -1){
		//cookieが空の時（初回）何もしない。
	} else {
		//cookieに値が入っている場合。
		//<input type="text" id="name">のvalueに値を入れる。
		//name=以降を取り出す。
		cookieNameString = cookieValue.substring(result);
		//alert(cookieNameString);
		userNameArray = cookieNameString.split('=');
		//alert("userName row=" + userNameArray[1]);
		userName = decodeURIComponent(userNameArray[1]);
		//alert("userName=" + userName);
		document.getElementById("name").value=userName;
		
	}
	//送信ボタンを隠す
		var sndButton = document.getElementById('sendButton');
		sndButton.style.display = "none";
}


</script>


</head>
<body onload="initialize()">
<div id="main">
<!--google mapを表示-->
<div id="map"></div>
<div id="message"></div>
<div id="reloadButtonDiv">

<div id="reloadButton"><a href="javascript:void(0)" onclick="getGeoLocation()"><img src="gps.png" width="40" height="40"></a></div>
<p id="position"></p>
</div>
<!--画面中央の赤い十字マーク-->
<img id ="map_curssor" src="./red_cross.png">


<!-- 入力フォーム-->
<div id="input_form">


<input id="latitude" type="hidden" name="latitude" value="$myLatitude"><div class="clearLeft"></div>
<input id="longitude" type="hidden" name="longitude" value="$myLongitude"><div class="clearLeft"></div>
<div><div class="input_marker"></div><input type="text" id="title" placeholder="タイトル"><div class="clearLeft"></div></div>
<div><div class="input_marker2"></div><textarea id="abstruct" name="abstruct" rows="4" cols="25"  placeholder="説明文"></textarea></div>
<div>
<div class="input_marker"></div><select name="example" id="category">
<option value=" ">カテゴリー</option>
<option value="トマソン">トマソン</option>
<option value="がっかり">がっかり</option>
<option value="なんでやねん！">なんでやねん！</option>
</select>
<div class="clearLeft"></div>
</div>
<div><div class="input_marker"></div><input type="text" id="name"  value="" placeholder="投稿者名"><div class="clearLeft"></div></div>


</div>
<div id="area">
	<!-- 画像を選択するボタン-->
	<div class="file">写真を選ぶ<input name="photo" type="file"></div>
</div>
<div id="image">&nbsp;</div>
<div id="img_process">写真を圧縮中...</div>

<!-- 送信ボタン-->
<div id="sendButton">
<button type="button" onClick="sendData()">送信する</button>
</div>
<br><br>
</div>
</body>
</html>
	
EOF;
}

?>
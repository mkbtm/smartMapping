//このJavaScriptがindex.phpから呼び出される。
//緯度と経度を取得して、それをパラメータとしてindex.phpに渡す。
//index.phpではhtmlを生成してブラウザに表示

function getGeoLocation(){
	//ユーザーの端末がGeoLocation APIに対応しているかの判定
	//対応している場合
	if(navigator.geolocation){
		//現在地を取得
		navigator.geolocation.getCurrentPosition(

			//[第1引数] 取得に成功した場合の関数
			function(position){

				//取得したデータの整理
				var data = position.coords;

				//データの整理
				var lat = data.latitude;
				var lng = data.longitude;
				var alt = data.altitude;
				var accLatlng = data.accuracy;
				var accAlt = data.altitudeAccuracy;
				var heading = data.heading;			//0=北,90=東,180=南,270=西
				var speed = data.speed;

				//アラート表示
				//alert("あなたの現在位置は、\n["+lat+","+lng+"]\nです。");
				
				url = "./map.php?Latitude=" + lat + "&Longitude=" + lng;
				location.href = url;
			},

			//[第2引数] 取得に失敗した場合の関数
			function(error){
				//エラーコード(error.code)の番号
				// 0:UNKNOWN_ERROR				原因不明のエラー
				// 1:PERMISSION_DENIED			利用者が位置情報の取得を許可しなかった
				// 2:POSITION_UNAVAILABLE		電波状況などで位置情報が取得できなかった
				// 3:TIMEOUT					位置情報の取得に時間がかかり過ぎた…

				//エラー番号に対応したメッセージ
				var errorInfo = [
					"原因不明のエラーで位置情報が取得できませんでした。",
					"このサイトは位置情報の使用を許可してお使いください。",
					"通信エラーで位置情報が取得できませんでした。",
					"タイムアウトエラー。"
				];

				//エラー番号
				var errorNo = error.code;

				//エラーメッセージ
				var errorMessage = "[エラー番号: "+errorNo+"]\n" + errorInfo[errorNo];

				//アラート表示
				alert(errorMessage);

				//HTMLに書き出し
				document.getElementById("result").innerHTML = errorMessage;
			},

			//[第3引数] オプション
			{
				"enableHighAccuracy": false,
				"timeout": 8000,
				"maximumAge": 2000,
			}

		);

	//対応していない場合
	} else {
		//エラーメッセージ
		var errorMessage = "お使いの端末は、GeoLacation APIに対応していません。";

		//アラート表示
		alert(errorMessage);

		//HTMLに書き出し
		document.getElementById("result").innerHTML = errorMessage;
	}

}
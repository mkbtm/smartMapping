//

function getGeoLocation(){
	if(navigator.geolocation){ //端末がGeoLocationに対応しているか確認。

		navigator.geolocation.getCurrentPosition(//現在地の取得

			function(position){// 取得に成功した場合の関数

				var data = position.coords;	//取得したデータの整理

				var lat = data.latitude;
				var lng = data.longitude;
				var alt = data.altitude;
				var accLatlng = data.accuracy;
				var accAlt = data.altitudeAccuracy;
				var heading = data.heading;			//0=北,90=東,180=南,270=西
				var speed = data.speed;

				url = "./map.php?Latitude=" + lat + "&Longitude=" + lng;
				location.href = url;
			},

			function(error){//取得に失敗した場合の関数
				//エラーコード(error.code)
				// 0:UNKNOWN_ERROR	  1:PERMISSION_DENIED		 2:POSITION_UNAVAILABLE		 3:TIMEOUT

				var errorInfo = [
					"原因不明のエラーが発生しました。",
					"位置情報の使用を許可してください。",
					"位置情報が取得できませんでした。",
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
		var errorMessage = "端末がGeoLacation APIに対応していません。";

		//アラート表示
		alert(errorMessage);

		//HTMLに書き出し
		document.getElementById("result").innerHTML = errorMessage;
	}

}

/*音乐切换*/
function musicPlay() {
	var music = document.getElementById('globalAudioPlayer');
	var music_img = document.getElementById('globalAudio');
	var status = music.paused;
	if (status) {
		music_img.className = "ga-active";
		music.play();
	} else {
		music_img.className = "";
		music.pause();
	}
};

/* 获取参数 */
function getQueryString(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
	var r = window.location.search.substr(1).match(reg);
	if (r != null)
		return unescape(r[2]);
	return null;
}

function urlEncode(str) {
	if(null ==str|| "" == str)
		return null;
	var ret = "";
	var strSpecial = "!\"#$%&'()*+,/:;<=>?[]^`{|}~%";
	for ( var i = 0; i < str.length; i++) {
		var chr = str.charAt(i);
		var c = str2asc(chr);
		tt += chr + ":" + c + "n";
		if (parseInt("0x" + c) > 0x7f) {
			ret += "%" + c.slice(0, 2) + "%" + c.slice(-2);
		} else {
			if (chr == " ")
				ret += "+";
			else if (strSpecial.indexOf(chr) != -1)
				ret += "%" + c.toString(16);
			else
				ret += chr;
		}
	}
	return ret;
}
// UrlDecode函数：
function urlDecode(str) {
	if(null ==str|| "" == str)
		return null;
	var ret = "";
	for ( var i = 0; i < str.length; i++) {
		var chr = str.charAt(i);
		if (chr == "+") {
			ret += " ";
		} else if (chr == "%") {
			var asc = str.substring(i + 1, i + 3);
			if (parseInt("0x" + asc) > 0x7f) {
				ret += asc2str(parseInt("0x" + asc
						+ str.substring(i + 4, i + 6)));
				i += 5;
			} else {
				ret += asc2str(parseInt("0x" + asc));
				i += 2;
			}
		} else {
			ret += chr;
		}
	}
	return ret;
}


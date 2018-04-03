	function getCookie(name) {
		var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
		if (arr != null) {
			return unescape(arr[2])
		}
		return ''
	}
	function setCookie(name, value, n) {
		var expdate = new Date;
		expdate.setTime(expdate.getTime() + n * 1000);
		document.cookie = name + ("=" + escape(value) + ";expires=" + expdate.toGMTString() + ";path=/;")
	}
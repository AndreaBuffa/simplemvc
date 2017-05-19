<!DOCTYPE html>
<html>
<head>
	<script type="text/Javascript">
		var myForm = document.getElementById("form");
		myForm.addEventListener("click", checkForm, false);
		myForm.addEventListener("mouseup", checkForm, false);
		myForm.addEventListener("focusin", checkForm, false);
		myForm.addEventListener("change", checkForm, false);
		myForm.addEventListener("keyup", checkForm, false);
		function checkName(name) {
			if (!name || (typeof name !== "string")) {
				return false;
			}
			var leadingBlanks = name.match(/^\s+/);
			if (leadingBlanks != null) {
				return false;
			}
			var trailingBlanks = document.form.firstname.value.match(/\s+$/);
			if (trailingBlanks != null) {
				return false;
			}
			var words = name.split(" ");
			for(var i=0; i < words.length; i++) {
				if (!words[i].match(/^[a-zA-Z\u00C0-\u00F6\u00F8-\u00FF']$/)) {
					return false;
				}
			}
			return true;
		}

		function checkEmail(address) {
			if (!address || (typeof address !== "string")) {
				return false;
			}
			// use a library... it's better
			if (address.match(/(\w)+@(\w)\.([a-z]{2,})/) !=null) {
				return false
			}
			return true;
		}

		function checkForm() {
			var valid = false;
			document.form.firstname.className = "invalid";
			document.form.lastname.className = "invalid";
			document.form.email.className = "invalid";
			if (checkName(document.form.firstname.value)) {
				document.form.firstname.className = "";
				if (checkName(document.form.lastname.value)) {
					document.form.lastname.className = "";
					if (checkName(document.form.email.value)) {
						document.form.email.className = "";
						valid = true;
					}
				}
			}
			document.form.submit.disabled = !valid;
		}
		
		function checkData() {
			var ret = false;
			xmlhttp.onreadystatechange = function() {
  				if (xmlhttp.readyState === 4) { 
  					if (xmlhttp.status === 200) {
  						if (xmlhttp.responseText === "success") {
  							ret = true;
  						} else {
  							document.getElementById("err").innerHTML = "Questa email risulta già presente.....";
  						}
  					}
    			}
  			}
			xmlhttp.open("POST", "subscription/checkdata", true);
			xmlhttp.send("email=" + document.form.email.value);
			return ret;
		}
	</script>
</head>
<body>
<form action="subscription/sendSubscription">
    <input type="text" id="firstname" value="" />
    <input type="text" id="lastname" value="" />
    <input type="text" id="email" value="" />
    <input type="submit" name="submit" value="Submit" onclick="return checkData();" />
    <div id="err" />
</form>
</body>
</html>

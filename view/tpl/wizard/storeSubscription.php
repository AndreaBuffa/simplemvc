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
		function checkForm() {
			var checkBox = document.getElementsByName(checkBoxName);
			var valid = true;
			if (checkBox) {
				var j = 0;
				for(var i = 0; i < checkBox.length; i++) {
					if (!checkBox[i].checked) {
						valid = false;
					}
				}
			}
			document.form.submit.disabled = !valid;
		}
	</script>
</head>
<body>
<form id="form" action="subscription/storeSubscription">
    <label for="firstnameCheck"><?php echo $view_firstname; ?></label>
    <input type="checkbox" name="firstnameCheck" value="1" checked />
    <label for="lastnameCheck"><?php echo $view_firstname; ?></label>
    <input type="checkbox" name="lastnameCheck" value="1" checked />
    <label for="emailCheck"><?php echo $view_email; ?></label> />
    <input type="checkbox" name="emailCheck" value="1" checked />
    <input type="submit" name="submit" value="Submit" />
</form>
</body>
</html>

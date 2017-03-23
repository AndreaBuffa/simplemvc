<?php

class ControllerSubscription extends ControllerBase {

	public function start() {
        if ($_POST['start']) {
            // should implementent a state-machine pattern
            $_SESSION['subscriptionStep'] = "sendSubscription"; 
            header('Location: /subscription/sendSubscription');
        } else {
            return $this->view->fetch();
        }
	}

	public function sendSubscription() {
        if ($_SESSION['subscriptionStep'] !== "sendSubscription") {
            header('Location: /subscription/start');
        }
        if ($_POST['submit']) {
            if (preg_match('^[a-zA-Z\'\xC0-\xFF ]+', $_POST['firstname'])) {
                if (preg_match('^[a-zA-Z\'\xC0-\xFF]+', $_POST['firstname'])) {
                    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                        $_SESSION['firstname'] = $_POST['firstname'];
                        $_SESSION['lastname'] = $_POST['lastname'];
                        $_SESSION['email'] = $_POST['email'];
                        $_SESSION['subscriptionStep'] = "storeSubscription"; 
                        header('Location: /subscription/storeSubscription');
                    }
                }
            }
            //menage error like redirect to some error page
        } else {
            return $this->view->fetch();
        }
	}

    public function storeSubscription() {
        if ($_SESSION['subscriptionStep'] !== "storeSubscription") {
            header('Location: /subscription/start');
        }
        if ($_POST['submit']) {
        	if ($_SESSION['firstnameCheckBox'] === 
        			$_SESSION['lastnameCheckBox'] ===
        		 		$_SESSION['emailCheckBox'] === '1') {
            	require_once(__DIR__.'/models/modeluser.php');
            	$myModel = Modeluser::getInstance();
            	$myModel->campo__firstname = $_SESSION['firstname'];
            	$myModel->campo__lastname = $_SESSION['lastname'];
            	$myModel->campo__email = $_SESSION['email'];
            	$ret = $myModel->save();
            	if ($ret === -1) {
              	  //menage error;
              	  header('Location: /subscription/enderror');
            	}
            	$_SESSION['subscriptionStep'] = "end";
            	header('Location: /subscription/end');
        	} else {
				header('Location: /subscription/storeSubscription');
        	}
        } else {
            $this->view->assign('firstname', $_POST['firstname']);
            $this->view->assign('firstname', $_POST['lastname']);
            $this->view->assign('firstname', $_POST['email']);
            return $this->view->fetch();
        }
	}

    public function end() {
        if ($_SESSION['subscriptionStep'] !== "end") {
            header('Location: /subscription/start');
        }
        $_SESSION['subscriptionStep'] = "";
        return $this->view->fetch();
    }
    
    public function enderror() {
        if ($_SESSION['subscriptionStep'] !== "enderror") {
            header('Location: /subscription/start');
        }
        $_SESSION['subscriptionStep'] = "";
        return $this->view->fetch();
    }
    
    public function checkdata() {
        if ($_SESSION['subscriptionStep'] !== "sendSubscription") {
        	return "";
        } else {
        	if ($this->checkExist($_SESSION['email'])) {
        		return "fail";
        	} else {
        		return "success";
        	}
        }
    }
    
    private function checkExist($email) {
    	if (!$email) return false;
    	$myModel = Modeluser::getInstance();
    	$ret = $myModel->select("email", $email);
    	if (count($retArray) > 0) {
    		return true;
    	}
    	return false;
    }
}

?>

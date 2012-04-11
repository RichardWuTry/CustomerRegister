<?php
class RegisterAction extends Action {
	function __construct() {		
		parent::__construct(); //call parent construct
		require_once COMMON_PATH.'/emailactivate.php'; //load emailactivate functions
		require_once COMMON_PATH.'/mail.php'; //load mail functions
	}

	public function index() {
		$this->display();
	}
	
	public function addCustomer() {
		if (isset($_POST['submit'])) {
			$Customer = M('Customer');
			$msg = '';		
			if ($Customer->create()) {
				$email = $Customer->email;
				$mobile = $Customer->mobile;
				if ($cust_id = $Customer->add()) {					
					$subject = 'Activate your account.';
					//Log::write('cust_id:'.$cust_id, Log::ERR);
					//require_once COMMON_PATH.'/emailactivate.php';
					$encryptStr = encryptUserInfo($cust_id, $mobile);					
					$activateLink = 'http://'.$_SERVER['SERVER_NAME'].__URL__.'/activateCustomer/id/'.$encryptStr;
					$body = "Please click following link to activate your account.<br />
					<a href=\"$activateLink\">$activateLink</a>";
					
					//Log::write('email:'.$email, Log::ERR);
					//require_once COMMON_PATH.'/mail.php';
					if (sendMail(array($email), $subject, $body)) {
						$msg = 'Record succeed. Please receive email and activate account.';
					} else {
						$msg = 'Send activation email failed.';
					}					
				} else {
					$msg = 'Record failed.';
				}
			} else {
				$msg = 'Form input error.';
			}
			
			$this->assign('msg', $msg);
			$this->display();
		} else {
			only_redirect(__URL__.'/index');
		}
	}
	
	public function activateCustomer() {
		$id = $_GET['id'];	
		if(empty($id)) {
			only_redirect(__URL__.'/index');
		} else {
			$arrUserInfo = decryptUserInfo($id);
			$cust_id = $arrUserInfo['user_id'];
			$mobile = $arrUserInfo['mobile'];
			$time = $arrUserInfo['time'];
			//Log::write('user_id:'.$cust_id, Log::ERR);
			//Log::write('mobile:'.$mobile, Log::ERR);
			//Log::write('time:'.$time, Log::ERR);
			if(empty($cust_id) || empty($mobile) || empty($time)) {
				only_redirect(__URL__.'/index');
			}
		}
		
		//检查该用户存在
		$Customer = M('Customer');
		if($CurrCust = $Customer
						->where("cust_id=$cust_id and mobile='$mobile'")
						->field("cust_id, activate_at")
						->find()){
			if (empty($Customer->activate_at)) {
				$Customer->activate_at = date('Y-m-d H:i:s', time());
				$Customer->save();
				$content = "<p>Activate account succeed.</p>
							<p>Redirecting to home page...</p>";
			} else {
				$content = "<p>Your account has been activated.</p>
							<p>Redirecting to home page...</p>";
			}			
		} else {
			only_redirect(__URL__.'/index', 5);
		}
		
		$this->assign('content', $content);
		$this->display();
		
		only_redirect(__URL__.'/index', 5);
	}
}


?>
<?php
class RegisterAction extends Action {
	public function index() {
		$this->display();
	}
	
	public function addCustomer() {
		if (isset($_POST['submit'])) {
			$Model = M('Customer');
			$msg = '';		
			if ($Model->create()) {
				if ($Model->add()) {					
					require_once COMMON_PATH.'/mail.php';
					if (sendMail(array('wu.chen@chinapay.com'), 
						'Please activate your account.',
						'Please click following link to activate your account.')) {
						$msg = '信息录入成功，请激活您的帐户';
					} else {
						$msg = '邮件发送失败';
					}					
				} else {
					$msg = '写入数据表出错';
				}
			} else {
				$msg = '表单填写错误';
			}
			
			$this->assign('msg', $msg);
			$this->display();
		} else {
			redirect(__URL__.'/index');
		}
	}
}


?>
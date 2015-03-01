<?php

class UserController extends BaseController {
	
	public function index(){
		$this->requireAdmin();

					
		$users = User::All(false,'UserLevel');
		if ($users==false){
			echo '<p> error </p>';
			return ;
		}
		
		//print_r($users);
		
		require(BASE_URI.'views/user/user_index.inc.html');
		
	}
	public function register(){
		if (User::isLogin()){
			header('Location: /');	
			exit();
		}
		include(BASE_URI.'views/user/register.inc.html');

	}
	public function add(){
		$this->requireAdmin();
		$grades = UserLevel::All();
		
		require(BASE_URI.'views/user/add.inc.html');
	
	}
	public function edit($id){
		$this->requireAdmin();
		
		if (!ctype_digit($id)){
			return;
		}
		$grades = UserLevel::All();
		
		require(BASE_URI.'views/user/edit.inc.html');
	
	}
	public function login(){
		if (User::isLogin()){
			header('Location: /');	
			exit();
		}
		else {
			require(BASE_URI.'views/user/login.inc.html');
		}

	}	
	public function logout(){
		User::logout();
		ob_end_clean();
		FlashMessage::setMsg('You have logged out');
		header('Location: /');		
		exit();
	}	
	//===============post=================
	public function create(){
		$inputs = $_POST['object'];
		if(!$this->requireAdmin(true)){
			if(isset($inputs['user_levels_id'])){
				unset($inputs['user_levels_id']);
			}
		}
		if (!$this->verifyInputs($inputs)){
			FlashMessage::setMsg('inputs invalid');
		//	header("Location: /user/register"); 
			exit;
		}
		$inputs['user_name'] = htmlentities($inputs['user_name']);
		$inputs['nickname'] = htmlentities($inputs['nickname']);
		$inputs['password'] = password_hash($inputs['password'], PASSWORD_DEFAULT);
		if (!$this->uniqueName($inputs)){
			FlashMessage::setMsg('User Name has been used');
			header("Location: /user/register");
			exit;
		}
		print_r("@@@@");	
		
		$user = new User($inputs);


		if ($user->save()==true){
			
			FlashMessage::setMsg("Added {$inputs['user_name']}");	
			unset($user);
			header('Location: /');
			exit;
		}
		unset($user);
		FlashMessage::setMsg('Fail to add');
		header('Location: /user/register');
		exit;
	}
	public function update($id){
		$this->requireAdmin();
		if (!ctype_digit($id)){
			return;
		}
		$inputs = $_POST['object'];
		if (!$this->verifyInputs($inputs)){
			
			echo '<p> verify error </p>';
			exit;
		}
		$inputs['user_name'] = htmlentities($inputs['user_name']);
		$inputs['nickname'] = htmlentities($inputs['nickname']);
		$inputs['password'] = password_hash($inputs['password'], PASSWORD_DEFAULT);
		$inputs['id']=$id;
		$user = new User($inputs);
		if ($user->save()==true){
			
			
			unset($user);
			header('Location: http://'.USER_URL."user");
			exit;
		}
		//sth error
		require(BASE_URI.'views/error.inc.html');
		unset($user);
	}
	
	
	public function delete(){
		$this->requireAdmin();
		ob_clean();
		if(!($user = User::find($_POST['id']))){
			echo '<h3> not found </h3>';
			ob_end_flush();
			exit();
		}
		
		if ($user->delete()){			
			echo '<h3> Deleted </h3>';			
		}else{
			echo '<h3> Fail </h3>';
		}
		unset($user);
		$this->index();
		ob_end_flush();
		exit();
	
	
	}
	public function p_login(){
		$inputs = $_POST['object'];
	//	print_r($inputs);
		$pwd = $inputs['password'];
		if(empty($pwd) || empty(trim($inputs['user_name']))){
			FlashMessage::setMsg('Empty/invalid');
			header('Location: /user/login');
		}		
		
		$para = array( 'user_name' => htmlentities($inputs['user_name']));
		//print_r ($para);
		$user_obj = User::findBy($para);	
		if ($user_obj == false){
			require(BASE_URI.'views/error.inc.html');
			return ;


		}	
		//print_r ($user_obj->getObjectData('password'));
		if( password_verify($pwd,$user_obj->getObjectData('password'))){
			$user_obj->setObjectData('password','');
			User::setSessionUser($user_obj);
			FlashMessage::setMsg('You have logged in');
			ob_end_clean();
			header('Location: /');
			exit;
		}else{
			Flash::setMsg( 'Password wrong'); 
			header('Location: /user/login');
		}	
	}
//===============private =====================	
	private function verifyInputs($inputs){
		global $DEBUG;
		if ($inputs['password'] != $inputs['re_password']){
			//error	
			if ($DEBUG){
				echo 'pasword not equal';
			}
			//header('Location: '.BASE_URL.'user/add');
			return false;
		}
		//not allow all space 
		trim($inputs['user_name']);
		trim($inputs['nickname']);
		if (empty($inputs['user']) && empty($inputs['user_name'])){
			//error 
			//header('Location: '.BASE_URL.'user/add');
			if ($DEBUG){
				echo 'pasword not equal';
			}
			return false;
		}
		return true;
	}
	private function uniqueName($inputs){
		$result= User::findBy(array('user_name'=>$inputs['user_name']));
		if ($result!=null){
			return false;
		}
		return true;
	}
}

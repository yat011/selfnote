<?php
	class NoteController extends BaseController {
		// display all related articles
		public function index(){
			//get all(or limit) articles
			$articles = Note::All();
		}
		public function add(){
			include(BASE_URI."views/note/add.inc.html");
		}


		//post
		//
		public function create(){
			global $s_user,$DEBUG;
			$this->requireUser();

			$inputs= $_POST['object'];
			if (!$this->checkFields($inputs)){
				if ($DEBUG){
					print_r($inputs);
					echo '<p> check fail </p>';
					exit;
				}
				header('Location: /note/add');
				exit;
			}
			$inputs['users_id']=$s_user->getObjectData('id');
			$note = new Note($inputs);
			if (!$note->save()){
				if ($DEBUG){
					print_r($inputs);
					exit;
				}	

			}
			FlashMessage::setMsg('sucessfully added');
			header('Location: /note/add');
			exit;
		}

		private function checkFields(& $inputs){
			global $DEBUG;
			if (!isset($inputs['title']) ||	!isset($inputs['excerpt'])|| !isset($inputs['content'])){
				if($DEBUG) echo '<p>isset</p>';
				return false;
			}
			$inputs['title']=trim($inputs['title']);
			$inputs['excerpt']=trim($inputs['excerpt']);
			$inputs['content']=trim($inputs['content']);

			if (empty($inputs['title']) || empty ($inputs['content'])){
				if ($DEBUG) echo '<p>empty</p>';
				return false;
				
			}
			$inputs['title']=htmlentities($inputs['title']);
			$inputs['content']=htmlentities($inputs['title']);
			$inputs['excerpt']=htmlentities($inputs['excerpt']);

			return true;

		}
	}
?>

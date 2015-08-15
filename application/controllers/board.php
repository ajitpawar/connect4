<?php

class Board extends CI_Controller {
     
    function __construct() {
    		// Call the Controller constructor
	    	parent::__construct();
	    	session_start();
    } 
          
    public function _remap($method, $params = array()) {
	    	// enforce access control to protected functions	
    		
    		if (!isset($_SESSION['user']))
   			redirect('account/loginForm', 'refresh'); //Then we redirect to the index page again
 	    	
	    	return call_user_func_array(array($this, $method), $params);
    }
    
    
    function index() {
		$user = $_SESSION['user'];
    		    	
    	$this->load->model('user_model');
    	$this->load->model('invite_model');
    	$this->load->model('match_model');
    	
    	$user = $this->user_model->get($user->login);

    	$invite = $this->invite_model->get($user->invite_id);
    	
    	if ($user->user_status_id == User::WAITING) {
    		$invite = $this->invite_model->get($user->invite_id);
    		$otherUser = $this->user_model->getFromId($invite->user2_id);
    	}
    	else if ($user->user_status_id == User::PLAYING) {
    		$match = $this->match_model->get($user->match_id);
    		if ($match->user1_id == $user->id)
    			$otherUser = $this->user_model->getFromId($match->user2_id);
    		else
    			$otherUser = $this->user_model->getFromId($match->user1_id);
    	}
    	
    	$data['user']=$user;
    	$data['otherUser']=$otherUser;
    	
    	switch($user->user_status_id) {
    		case User::PLAYING:	
    			$data['status'] = 'playing';
    			$data['turn'] = 'their-turn';
    			break;
    		case User::WAITING:
    			$data['status'] = 'waiting';
    			$data['turn'] = 'waiting';
    			break;
    	}
	    	
		$this->load->view('match/board',$data);
    }

    function startMatch() {
    	$this->input->post("match_id");

    }

 	function postMsg() {
 		$this->load->library('form_validation');
 		$this->form_validation->set_rules('msg', 'Message', 'required');
 		
 		if ($this->form_validation->run() == TRUE) {
 			$this->load->model('user_model');
 			$this->load->model('match_model');

 			$user = $_SESSION['user'];
 			 
 			$user = $this->user_model->getExclusive($user->login);
 			if ($user->user_status_id != User::PLAYING) {	
				$errormsg="Not in PLAYING state";
 				goto error;
 			}
 			
 			$match = $this->match_model->get($user->match_id);			
 			
 			$msg = $this->input->post('msg');
 			
 			if ($match->user1_id == $user->id)  {
 				$msg = $match->u1_msg == ''? $msg :  $match->u1_msg . "\n" . $msg;
 				$this->match_model->updateMsgU1($match->id, $msg);
 			}
 			else {
 				$msg = $match->u2_msg == ''? $msg :  $match->u2_msg . "\n" . $msg;
 				$this->match_model->updateMsgU2($match->id, $msg);
 			}
 				
 			echo json_encode(array('status'=>'success'));
 			 
 			return;
 		}
		
 		$errormsg="Missing argument";
 		
		error:
			echo json_encode(array('status'=>'failure','message'=>$errormsg));
 	}
 
	function getMsg() {
 		$this->load->model('user_model');
 		$this->load->model('match_model');
 			
 		$user = $_SESSION['user'];
 		 
 		$user = $this->user_model->get($user->login);
 		if ($user->user_status_id != User::PLAYING) {	
 			$errormsg="Not in PLAYING state";
 			goto error;
 		}
 		// start transactional mode  
 		$this->db->trans_begin();
 			
 		$match = $this->match_model->getExclusive($user->match_id);			
 			
 		if ($match->user1_id == $user->id) {
			$msg = $match->u2_msg;
 			$this->match_model->updateMsgU2($match->id,"");
 		}
 		else {
 			$msg = $match->u1_msg;
 			$this->match_model->updateMsgU1($match->id,"");
 		}

 		if ($this->db->trans_status() === FALSE) {
 			$errormsg = "Transaction error";
 			goto transactionerror;
 		}
 		
 		// if all went well commit changes
 		$this->db->trans_commit();
 		
 		echo json_encode(array('status'=>'success','message'=>$msg));
		return;
		
		transactionerror:
		$this->db->trans_rollback();
		
		error:
		echo json_encode(array('status'=>'failure','message'=>$errormsg));
 	}

 	function getMatchState(){
 		$this->load->model('user_model');
 		$this->load->model('match_model');
 			
 		$user = $_SESSION['user'];
 		 
 		$user = $this->user_model->get($user->login);

 		$match = $this->match_model->get($user->match_id);
 		$match_board = $this->match_model->getMatchState($match->id);

 		echo(json_encode(unserialize($match_board->board_state)));
 	}

 	function updateMatchState(){
 		$this->load->model('user_model');
 		$this->load->model('match_model');

 		$user = $_SESSION['user'];
 		 
 		$user = $this->user_model->get($user->login);

 		$matchState = json_decode($this->input->post("matchState"), true);

 		if ($user->user_status_id != User::PLAYING) {	
 			echo("NOTPLAYING");
 		}
 			
 		$match = $this->match_model->get($user->match_id);

 		$this->db->trans_begin();

 		$match_winner = $matchState['state'];

 		if($match_winner == "blue"){
 			$this->match_model->updateStatus($match->id,Match::U1WON);
 			$u1 = $this->user_model->getFromId($match->user1_id);
	    	$u2 = $this->user_model->getFromId($match->user2_id);

 			$this->user_model->updateStatus($u1->id,User::AVAILABLE);
	    	$this->user_model->updateStatus($u2->id,User::AVAILABLE);
 		}
 		else if ($match_winner == "red"){
 			$this->match_model->updateStatus($match->id,Match::U2WON);
 			$u1 = $this->user_model->getFromId($match->user1_id);
	    	$u2 = $this->user_model->getFromId($match->user2_id);

 			$this->user_model->updateStatus($u1->id,User::AVAILABLE);
	    	$this->user_model->updateStatus($u2->id,User::AVAILABLE);
 		}
 		else if ($match_winner == "tie"){
 			$this->match_model->updateStatus($match->id,Match::TIE);
 			$u1 = $this->user_model->getFromId($match->user1_id);
	    	$u2 = $this->user_model->getFromId($match->user2_id);

 			$this->user_model->updateStatus($u1->id,User::AVAILABLE);
	    	$this->user_model->updateStatus($u2->id,User::AVAILABLE);
 		}
 		$serialized = serialize($matchState);
 		$match_state = $this->match_model->updateMatchState($match->id,$serialized);

 		$this->db->trans_commit();

 	}

 	function sendArray(){
 		$in_array = $this->input->post("matchStatus");
 		$data["data"] = json_decode($in_array);
 		$this->load->view('match/getHTML.php',$data);
 	}

 	function getArray(){
 		$array = array(
 			array(2,4,6),
 			array(8,10,12),
 			array(14,16,18)
 		);
 		echo json_encode($array);
 	}
 	
 }


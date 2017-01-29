<?php

/*
This will have general info about a user's session.
There should be one instance of this class per user session
(singleton pattern). Could just store all this in session
vars but intuition tells me that's bad practice. Think of
this as holding all the CA* variables (CACOMP, CAWHSE, etc).
Need a session_start() call. Maybe that and all this initialization
goes with login logic? And generate a random session ID? The ID
would be useful for logging, too.
*/

class Info {

	private $customer;
	
	
	public Info($customer){
		$this->customer = $customer;
	}

	public function getCustomer() {
		return $this->customer;
	}
	
	
	public function getInstance($sessionId){
		$thisInfo = $_SESSION[$sessionId];
		if ($thisInfo == null){
			$thisInfo = new Info();
			$_SESSION[$sessionId] = $thisInfo;
		}
		
		return $thisInfo;
	}
}

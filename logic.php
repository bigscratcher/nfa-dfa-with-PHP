<?php

//Get initial parameters before the conversion starts
function get_initial($nfa){
	$start = $end = $alphabet = $all_keys = $dfa_possible = [];

	if(isset($nfa['finish'])) $end = $nfa['finish'];
	$nfa = $nfa['graph'];

	foreach ($nfa as $key => $connected) {
		$key_el = explode(".", $key);

		//Get real key
		$key_real = $key_el[count($key_el)-2];
		if(!in_array($key_el[count($key_el)-1], $alphabet)) $alphabet[] = $key_el[count($key_el)-1];
		
		//Check what type of key is 
		switch(count($key_el)){		
			case '3':
					//Starting or Finishing element					
					if($key_el[0]=='S' && !in_array($key_el[1], $start))
						$start[] = $key_el[1];				
					elseif ($key_el[0]=='F' && !in_array($key_el[1], $end))
						$end[] = $key_el[1];
											
					break;
			case '4':
					if(!in_array($key_el[2], $start))
						$start[] = $key_el[2];
					
					if(!in_array($key_el[2], $end))
						$end[] = $key_el[2];
																
					break;
		}
		//Get all keys from nfa
		if(!in_array($key_real, $all_keys))
			$all_keys[] = $key_real;

		//Get the first element from nfa
		if(count($dfa_possible) == 0){
			$dfa_possible[] = $key_real;	
			$i = true;	
		}
	}

	return array($start, $end, $alphabet, $all_keys, $dfa_possible, $i);
}

//Remove esylon branches if exist
function remove_epsylon($nfa){
	$start = $end = $alphabet = $all_keys = $dfa_possible = [];

	list($start, $end, $alphabet, $all_keys, $dfa_possible, $i) = get_initial($nfa);
	$nfa = $nfa['graph'];

	//If exist any element with EP value
	if(in_array('EP', $alphabet)){
		foreach ($nfa as $key => $value) {
			$key_el = explode(".", $key);
			//Get real key
			$key_real = $key_el[count($key_el)-2];
			$key_alpha = $key_el[count($key_el)-1];

			//This is the element with EP value
			if($key_alpha=='EP') {
				//If it is starting position, advance further and connect with the next 
				if(in_array($key_real, $start)){
					
					//Add S of F if key_real is start or end sign (S - default)
					if(in_array($key_real, $end)) $key_real = 'F.'.$key_real;
					$key_real = 'S.'.$key_real;

					//Go through every values that are connected with EP sign
					foreach($value as $val){
						//Attach every alphabet sign and S or F sign and check for souch existence in nfa
						foreach ($alphabet as $alpha) {
							$mod_key_real = $key_real.'.'.$alpha;
							$mod_val = $val.'.'.$alpha;
							//Add S or F if it is start or end sign
							if(in_array($val, $end)) $val = 'F.'.$val;
							if(in_array($val, $start)) $val = 'S.'.$val;							
							//Go through nfa and get everything that it is connected with
							foreach($nfa as $n_key=>$n_val){								
								if($n_key==$mod_val){
									//Marker if souch item exist, merge if exist
									$not_exist = true;
									foreach ($nfa as $o_key => $o_value) {
										//If already exist souch an item										
										if($o_key == $mod_key_real){
											$not_exist = false;
											$nfa[$mod_key_real] = array_unique(array_merge($n_val, $o_value));
										}
									}
									//Add new if doesn't exist souch
									if($not_exist){
										$nfa[$mod_key_real] = $n_val;
									}
								}
							}
						}
					}					
				}else{					
					//If it is not the starting item, go back and connect the next filed with a real branch					
					foreach ($nfa as $n_key => &$n_value) {
						if(in_array($key_real, $n_value)){
							$n_value = array_merge($n_value, $value);
						}
					}					
				}

				//Remove the EP branch from the start field
				unset($nfa[$key]);
			}
		}

		//Modify alphabet to remove epsylon(EP) from it
		if(($key = array_search("EP", $alphabet)) !== false) {
		    unset($alphabet[$key]);
		}
	}		
	return array($nfa, $start, $end, $alphabet, $all_keys, $dfa_possible, $i);
}

//Add branches to W field if missing
function add_missing_branches($dfa, $alphabet, $start, $end, $w_field){
	$passed = [];
	//Add first start fields
	foreach ($start as $st) {
		//Add pre symbols for finish and start field
		if(in_array($st, $end))
			$st = 'F.'.$st;			
		$st = 'S.'.$st;

		foreach ($alphabet as $alpha) {
			$m_st = $st;
			$m_st = $m_st.'.'.$alpha;
			if(!isset($dfa[$m_st]))
				$dfa[$m_st] = 'W';
		}
	} 
	foreach ($dfa as $key => $connected) {
		if(!in_array($connected, $passed)){
			$passed[]=$connected;
			if($connected!='W'){

				$is_start = $is_finish = false;
				$conn_vals = explode(',', $connected);

				foreach ($conn_vals as $conn_v) {
					//Add S it is start field
					if(in_array($conn_v, $start)){
						$is_start = true;
						break;
					} 			
				}
				foreach ($conn_vals as $conn_v) {
					//Add F if it is end field
					if(in_array($conn_v, $end)){
						$is_finish = true;
						break;
					} 			
				}
				if($is_finish) $connected = 'F.'.$connected;
				if($is_start)  $connected = 'S.'.$connected;			

				$m_conn_vals = $connected;
				foreach ($alphabet as $alpha) {
					$m_conn_vals = $connected.'.'.$alpha;
					if(!isset($dfa[$m_conn_vals])){
						$dfa[$m_conn_vals] = 'W';	
						$w_field = true;
					}					
				}	
			}		
		}
	}		
	return ['dfa'=>$dfa, 'w_field'=>$w_field];
}

//Conversion function from nfa to dfa
function nfa_to_dfa($nfa){

	$dfa = $start = $end = $alphabet = $all_keys = $dfa_possible = $dfa_history = [];
	$w_field = false; //Well level if exist  - where all unfunctional strands goes in it
	
	//Transform/Remove epsylon falues to functional alphabet branches
	//Get initial alements start/finish/alphabet/dfa_possible - lookup and $i - marker if we got the first element
	list($nfa, $start, $end, $alphabet, $all_keys, $dfa_possible, $i) = remove_epsylon($nfa);
	
	//Make dfa from nfa	
	while($i){
		//Split complex positions to simple positions ex. 1,2,3 to 1 2 and 3		
		$poss_arr = array();
		//New lookup has been selected		
		$look_element = array_shift($dfa_possible);
		
		//Keep dfa_history of every member looked before so we don't duplicate and infinite check for elements
		if(!in_array($look_element, $dfa_history)) $dfa_history[] = $look_element;
		
		//Get subfields, make array if complex one or take single element as an array
		if(strpos($look_element, ',') > 0){
			$poss_arr = explode(",", $look_element);
		}else{
			$poss_arr[0] = $look_element;
		}		

		foreach($poss_arr as $simple){
			foreach ($nfa as $key => $connected) {
				$key_el = explode(".", $key);
				//Get real key
				$key_real = $key_el[count($key_el)-2];
				$key_alpha = $key_el[count($key_el)-1];
				
				if($key_real.".".$key_alpha == $simple.".".$key_alpha){
					
					$dfa_key = $look_element.".".$key_alpha;					
					
					//Check to see if element is start or finish element					
					foreach($poss_arr as $elF){
						if(in_array($elF, $end)){
							$dfa_key = "F.".$dfa_key;
							break;	
						}					
					}
					foreach($poss_arr as $elS){
						if(in_array($elS, $start)){
							$dfa_key = "S.".$dfa_key;
							break;	
						}			
					}					
					
					$dfa_value = implode(',', $connected)!='' ? implode(',', $connected) : 'W';	

					//Possible values to be observed in the next event
					if(!in_array($dfa_value, $dfa_possible) && 
					   !in_array($dfa_value, $dfa_history) && 
					   $dfa_value!='W') 
							$dfa_possible[] = $dfa_value;															
					
					//Group according to the key and adjust the end value					
					$add_new = true;															
					foreach($dfa as $key=>$value){
						if($key == $dfa_key){
							if($value == 'W') 			$dfa[$key] = $dfa_value;
							elseif($dfa_value == 'W')	$dfa[$key] = $value;
							elseif($value != $dfa_value) {
								//Merge duplicates into one value
								$value = strpos($value, ',') > -1 ? explode(',', $value) : (array)$value;
								$dfa_value = strpos($dfa_value, ',') > -1 ? explode(',', $dfa_value) : (array)$dfa_value;
								$merged_value = implode(array_unique(array_merge($value, $dfa_value)),',');								
								
								//Update the result for the value
								$dfa[$key] = $merged_value;

								//Possible values to be observed in the next event
								if(!in_array($merged_value, $dfa_possible) && 
								   !in_array($merged_value, $dfa_history)) 
										$dfa_possible[] = $merged_value;
							}

							$add_new = false;
							break;
						}
					}

					//Add new if none like it exist					
					if($add_new) {
						if($dfa_value == 'W') $w_field = true;
						$dfa[$dfa_key] = $dfa_value;
					}										
				}
			}
		}

		if(count($dfa_possible)	== 0)	$i = false;
	}		
	
	//Check for the finished DFA and add branches if some alphabet branches are missing
	$return_missing = add_missing_branches($dfa, $alphabet, $start, $end, $w_field);
	$dfa = $return_missing['dfa'];
	$w_field = $return_missing['w_field'];

	//Add W level if exist
	if($w_field){
		foreach ($alphabet as $alpha) {
			$dfa['W.'.$alpha] = 'W';			
		}		
	}

	return $dfa;
}
?>
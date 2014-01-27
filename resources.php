<?php

$EP = 'epsylon';

//Examples for NFA to DFA conversion graphs
function examples(){
	/**
	* Explanation example: 
	*		Positions: 0, 1, 2, 3, 4
	*		Alphabet:  a, b
	* 
	*	  ex.   "0.b" => [] --------------------- From the position 0 with "b" go to empty
	*	  ex.	"0.a" => [1,2]  ----------------- From the position 0 with "a" go to 1 AND 2 - Can go to multiple position
	*	  ex.   "S.0.a" => [1,2] ---------------- Field 0 is a "start" field and with "a" go to positions 1 AND 2
	*	  ex.   "S.F.0.a" => [1] ---------------- Field 0 is a "start" and "finish" field in the same time and with "a" go to position 1
	*     ex.   "1,2,3.a" => [5] ---------------- From the complex field 1,2,3 with "a" go to 5
	*/

	//Examples
	$nfa0['graph'] = ["S.F.0.a"=>[1,2],					  
					  "F.1.a"=>[1,2],					  					  
					  "2.b"=>[1,3],
					  "3.a"=>[1,2],					  
					];
	
	$nfa1['graph'] = ["S.0.a"=>[1,2,3],
					  "S.0.b"=>[2,3],
					  "F.1.a"=>[1,2],
					  "F.1.b"=>[2,3],					  
					  "2.b"=>[2,3,4],
					  "3.a"=>[4],
					  "3.b"=>[2,3,4],					  
					];

	$nfa2['graph'] = ["S.0.a"=>[2,4],
					 	"S.0.b"=>[1,2,4],
					 	"1.a"=>[0,3],
					 	"1.b"=>[0,3],					 	
					 	"2.b"=>[0,3],
					 	"3.a"=>[2,4],
					 	"3.b"=>[1,2,4],					 	
					];

	/*
			Specific example of graph where finishing field have no alphabet branches in NFA graph, so you must 
			initialy state which finishing positions are.
	*/
	$nfa3['graph'] = ["S.A.0"=>['B','G'],
						"S.A.EP"=>['C'],
					    "S.A.1"=>['D'],
					    "B.EP"=>['C'], 
					    "C.1"=>['B'],
					    "C.0"=>['F'],
					    "D.1"=>['A'],
					    "D.EP"=>['F'],
					    "G.1"=>['F']
					];
	$nfa3['finish'] = ['F'];

	//All examples
	$nfa = [$nfa0, $nfa1, $nfa2, $nfa3];

	return $nfa;
}
function show_simple($nfa){
	$show_nfa = [];
	foreach ($nfa as $key => $value) {
		if(!isset($show_nfa[$key])){
			$show_nfa[$key] = implode(',',$value);
		}
	}
	return $show_nfa;
}
?>
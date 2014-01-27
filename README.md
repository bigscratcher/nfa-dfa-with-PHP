nfa-dfa-with-PHP
================

NFA to DFA Graph conversation with PHP


Explanation example: 
		Positions: 0, 1, 2, 3, 4
		Alphabet:  a, b
 
ex. "0.b" => [] ------- From the position 0 with "b" go to empty - not necessary to work
ex.	"0.a" => [1,2]  ----From the position 0 with "a" go to 1 AND 2 - Can go to multiple 
						position
ex. "S.0.a" => [1,2] ---Field 0 is a "start" field and with "a" go to positions 1 AND 2
ex. "S.F.0.a" => [1] ---Field 0 is a "start" and "finish" field in the same time and 
						with "a" go to position 1
ex. "1,2,3.a" => [5] ---From the complex field 1,2,3 with "a" go to 5

* Specific example of graph where finishing field have no alphabet branches in NFA graph, 
you must initialy state which finishing positions are.
Is added additional ["finish"] to every $nfa["graph"]

For better explanation of how NFA graph should be represented go to resources.php and check 
real-world examples

Demo:
http://nfa-dfa.webatu.com/
	
This code can be used under MIT licence
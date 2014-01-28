nfa-dfa-with-PHP
================

NFA to DFA Graph conversation with PHP


Explanation example: 
		Positions: 0, 1, 2, 3, 4
		Alphabet:  a, b
 
ex. "0.b" => [] ------- From the position 0 with "b" go to empty - not necessary to work <br>
ex.	"0.a" => [1,2]  ----From the position 0 with "a" go to 1 AND 2 - Can go to multiple <br>
						position <br>
ex. "S.0.a" => [1,2] ---Field 0 is a "start" field and with "a" go to positions 1 AND 2 <br>
ex. "S.F.0.a" => [1] ---Field 0 is a "start" and "finish" field in the same time and <br>
						with "a" go to position 1 <br>
ex. "1,2,3.a" => [5] ---From the complex field 1,2,3 with "a" go to 5 <br>

* Specific example of graph where finishing field have no alphabet branches in NFA graph, 
you must initialy state which finishing positions are.
Is added additional ["finish"] to every $nfa["graph"]

For better explanation of how NFA graph should be represented go to resources.php and check 
real-world examples

Demo:
http://nfa-dfa.webatu.com/
	
License

The MIT License (MIT)

Copyright (c) 2014 Goce Dimkovski dimkovskigoce@gmail.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
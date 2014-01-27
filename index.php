<?php
include 'resources.php';
include 'logic.php';

$ex = examples();

//Select graph that you want to work with 0-3 from resources
$i = 0;

//Post logic here
if($_POST){
	$i = $_POST['resource'];
	$nfa = $ex[$i];
	$return['nfa'] = $nfa['graph'];
	$return['dfa'] = nfa_to_dfa($nfa);
	die(json_encode($return));
}

$nfa = $ex[$i];
$dfa = nfa_to_dfa($nfa);

?> 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>NFA to DFA conversion example</title>
  <link type="text/css" rel="stylesheet" href="css/style.css" />    
</head>
<body>
	<p>Select graph from resources:</p>
	<select id="change">
		<option value="0">Example 1</option>
		<option value="1">Example 2</option>
		<option value="2">Example 3</option>
		<option value="3">Example 4</option>
	</select>
	<p>Initial Graph before conversion looks like: </p>
	<ul class="display_list" id="show_nfa">
		<li><?php 
				$nfa = show_simple($nfa['graph']);
				echo '<ul class="graph">';
				foreach ($nfa as $key => $value) {
					echo '<li>'.$key.'</li><li>	=> '.$value.'</li>';
				}
				echo '</ul>';
			?>
		</li>
		<li><img src="images/nfa<?php echo $i ?>.jpg"></li>
	</ul>
    <br>
    <p>After conversion Graph looks like: </p>
    <ul class="display_list" id="show_dfa">
    	<li><?php 				
				echo '<ul class="graph">';
				foreach ($dfa as $key => $value) {
					echo '<li>'.$key.'</li><li>	=> '.$value.'</li>';
				}
				echo '</ul>';
			?>
    	</li>
    	<li><img src="images/dfa<?php echo $i ?>.jpg"></li>
    </ul>

    <script src="lib/jquery-2.1.0.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>

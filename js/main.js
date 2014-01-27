$(function(){
	$("#change").change(function(){
		var i = $(this).val();
		$.post('index.php',
			{
				resource: i
			}, 
			function(graphs){
				graphs = JSON.parse(graphs)	
				var graph_nfa = '<ul class="graph">'
				$.each(graphs.nfa, function(key, value){
					graph_nfa += '<li>' + key + '</li><li>	=> ' + value.toString() + '</li>'
				}) 
				graph_nfa += '</ul>';

				var graph_dfa = '<ul class="graph">'
				$.each(graphs.dfa, function(key, value){
					graph_dfa += '<li>' + key + '</li><li>	=> ' + value.toString() + '</li>'
				}) 
				graph_dfa += '</ul>';						
				$("#show_nfa li:first").html(graph_nfa)
				$("#show_nfa li:last img").attr('src','images/nfa' + i + '.jpg')

				$("#show_dfa li:first").html(graph_dfa)
				$("#show_dfa li:last img").attr('src','images/dfa' + i + '.jpg')
		})
	})
})
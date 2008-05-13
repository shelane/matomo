$(document).ready(function(){
	var url = $("#edit-url").val();
	var page = $("#edit-page").val();
	$.getJSON(url,
	function(data){
		var header = "<table class='dataTable' cellspacing='0'><tr><th>label</th>";
		if (page == "websites")
			header += "<th>nb_uniq_visitors</th>";
		if (page == "actions")
			header += "<th>nb_uniq_visitors</th><th>nb_hits</th>";
		if (page == "search")
			header += "<th>nb_visits</th>";
		header += "</tr>";
		var content = "";
		var footer = "</table>"
		$.each(data, function(i,item){
			content += "<tr><td>"+item["label"]+"</td>";
			if (page == "actions")
				content += "<td>"+item["nb_uniq_visitors"]+"</td><td>"+item["nb_hits"]+"</td>";
			if (page == "websites")
				content += "<td>"+item["nb_unique_visitors"]+"</td>";
			if (page == "search")
				content += "<td>"+item["nb_visits"]+"</td>";
			content += "</tr>";
		});
		$("#pagestable").html(header+content+footer);
	});
});
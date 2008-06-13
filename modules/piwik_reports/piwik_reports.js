$(document).ready(function(){
  var url = $("#edit-url").val();
  var page = $("#edit-page").val();

  // Build HTML for data table.
  var columns = 2;
  var header = "<table class='sticky-enabled sticky-table'><thead class='tableHeader-processed'>";
  header += "<tr><th>" + Drupal.t('Label') + "</th>";
  if (page == "websites") {
    header += "<th>" + Drupal.t('Unique visitors') + "</th>";
  }
  if (page == "actions") {
    header += "<th>" + Drupal.t('Unique visitors') + "</th><th>" + Drupal.t('Hits') + "</th>";
    columns = 3;
  }
  if (page == "search") {
    header += "<th>" + Drupal.t('Visits') + "</th>";
  }
  header += "</tr></thead><tbody></tbody></table>";

  // Add the table and show "Loading data..." status message for long running requests.
  $("#pagestable").html(header);
  $("#pagestable > table > tbody").html("<tr class='odd'><td colspan='" + columns + "'>" + Drupal.t('Loading data...') + "</td></tr>");

  // Get data from remote Piwik server.
  $.getJSON(url, function(data){
    var content = "";
    var tr_class = "even";

    $.each(data, function(i,item){
      if (tr_class == "odd") { item_class = "even"; } else { item_class = "odd"; }
      tr_class = item_class;

      content += "<tr class='" + item_class + "'><td>" + item["label"] + "</td>";
      if (page == "actions") {
        content += "<td>" + item["nb_uniq_visitors"] + "</td><td>" + item["nb_hits"] + "</td>";
      }
      if (page == "websites") {
        content += "<td>" + item["nb_uniq_visitors"] + "</td>";
      }
      if (page == "search") {
        content += "<td>" + item["nb_visits"] + "</td>";
      }
      content += "</tr>";
    });

    // Push data into table and replace "Loading data..." status message.
    $("#pagestable > table > tbody").html(content);
  });

});
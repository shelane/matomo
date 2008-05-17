$(document).ready(function(){
  var url = $("#edit-url").val();
  var page = $("#edit-page").val();
  $.getJSON(url,
    function(data){
      var header = "<table class='sticky-enabled sticky-table'><tr><th>" + Drupal.t('Label') + "</th>";
      if (page == "websites") {
        header += "<th>" + Drupal.t('Visitors') + "</th>";
      }
      if (page == "actions") {
        header += "<th>" + Drupal.t('Visitors') + "</th><th>" + Drupal.t('Hits') + "</th>";
      }
      if (page == "search") {
        header += "<th>" + Drupal.t('Visits') + "</th>";
      }
      header += "</tr>";

      var content = "";
      var footer = "</table>";
      var tr_class = "even";
      $.each(data, function(i,item){
        if (tr_class == "odd") { item_class = "even"; } else { item_class = "odd"; }      
        tr_class = item_class;
        content += "<tr class='" + item_class + "'><td>" + item["label"] + "</td>";
        if (page == "actions") {
          content += "<td>" + item["nb_uniq_visitors"] + "</td><td>" + item["nb_hits"] + "</td>";
        }
        if (page == "websites") {
          content += "<td>" + item["nb_unique_visitors"] + "</td>";
        }
        if (page == "search") {
          content += "<td>" + item["nb_visits"] + "</td>";
        }
        content += "</tr>";
      });
      $("#pagestable").html('<br />' + header + content + footer);
    });
});
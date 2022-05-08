function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}

$(document).ready(function()
{
    $(".btn-group .btn").click(function()
    {
        var inputValue  =   $(this).find("input").val();
        if(inputValue   != 'Toti')
        {
            var target  =   $('table tr[data-status="' + inputValue + '"]');
            $("table tbody tr").not(target).hide();
            target.fadeIn();
        }
        else
        {
            $("table tbody tr").fadeIn();
        } 
    });
    // Changeing the class of status label to support bootstrap 4
    var bs  =  $.fn.tooltip.Constructor.VERSION;
    var support =   bs.split(".");
    if(str[0]   ==  4)
    {
        $(".label").each(function()
        {
            var classStr    =   $(this).attr("class");
            var newClassStr =   classStr.replace(/label/g, "badge");
            $(this).removeAttr("class").addClass(newClassStr);
        });
    }
});
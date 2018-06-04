$(document).on("submit", '.checkIn', function(e) { 
    td = $(this).parent();
    nextTd = td.next();
    studentId = $(this).find('input[name=id]').val();
    token = $(this).find('input[name=_token]').val();

    checkOutHtml = '<form class="checkOut" method="post" action="/student/checkOut"><input type="hidden" name="_token" value="'+token+'"><input type="hidden" name="id" value="'+studentId+'"><button type="submit" class="btn btn-warning btn-sm">Check-Out</button></form>'

    $.ajax({
        type: "POST",
        url: "/student/checkIn",
        data: $(this).serialize(), // serializes the form's elements.
        success: function (data) {
            td.html(data);
            nextTd.html(checkOutHtml);
        }
    });

    e.preventDefault(); // avoid to execute the actual submit of the form.
});

$(document).on("submit", '.checkOut', function(e) { 
    td = $(this).parent();

    $.ajax({
        type: "POST",
        url: "/student/checkOut",
        data: $(this).serialize(), // serializes the form's elements.
        success: function (data) {
            td.html(data);
        }
    });

    e.preventDefault(); // avoid to execute the actual submit of the form.
});

$(".action").click(function(e) {
    str = table.$('input:checkbox[name="students[]"]:checked').serialize();
    window.location = '' + $(this).attr('id') + '?' + str;
});

$(".chosen").chosen();


table.$('.clickable').click(function(){
    var input = table.$(this).parent().children().find( "input" ).first();
    var tr = table.$(this).parent();
    if(input.is(':checked'))
    {
        input.attr('checked', false);
        tr.attr('class', '');
    }
    else
    {
        input.attr('checked', true);
        tr.attr('class', 'table-primary');
    }
});

table.$('td[data-toggle="tooltip"]').tooltip({
    animated: 'fade',
    placement: 'bottom',
    html: true
});
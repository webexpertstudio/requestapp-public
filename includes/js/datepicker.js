$(function() {
$( ".from" ).datepicker({
defaultDate: "+1w",
changeMonth: true,
changeYear: true,
numberOfMonths: 1,
yearRange: "-1:+1",
showOn: "button",
buttonImage: "/includes/js/images/dateicon.png",
buttonImageOnly: true,
onClose: function( selectedDate ) {
$( ".to" ).datepicker( "option", "minDate", selectedDate );
}
});
$( ".to" ).datepicker({
defaultDate: "+1w",
changeMonth: true,
changeYear: true,
numberOfMonths: 1,
yearRange: "-1:+1",
showOn: "button",
buttonImage: "/includes/js/images/dateicon.png",
buttonImageOnly: true,
onClose: function( selectedDate ) {
$( ".from" ).datepicker( "option", "maxDate", selectedDate );
}
});
});
jQuery(document).ready(function() {
	$( "#startdate" ).datepicker();
	$("#daysnumber").keydown(function (e) {
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    $("#submit-cal").on('click',function(){
    	var startdate = $( "#startdate" ).datepicker( "getDate" );
    	var days = $("#daysnumber").val();
    	var countrycode = $("#countrycode").val();

    	var isValid = validateForm({date:startdate,totaldays:days,cc:countrycode});
    	if(isValid.valid){
            $.ajax({
                type: "POST",
                url: "./php/drawCalendar.php",
                cache: false,
                data: {
                    starttime:startdate.getTime()/1000,
                    totaldays:days,
                    countrycode:countrycode
                },
                success: function(result,status,xhr){
                    $('#result').html(result);
                },
                error: function(jqXHR, textStatus, errorThrown){
                    console.log(errorThrown);
                }
            });
    	}else{
    		alert(isValid.message);
    	}
    	
    });

    function validateForm(pParams){
    	var isValid = true;
    	var message = '';
    	if(pParams.date === null){
    		isValid = false;
    		message='Please select a date';
    	}else if(pParams.totaldays.trim() === '' || pParams.totaldays.trim() === "0"){
    		isValid = false;
    		message='Please select how many days add to calendar';
    	}else if(pParams.cc.trim() === ''){
    		isValid = false;
    		message='Please type a Country Code';
    	}
    	return {valid:isValid,message:message};
    }
});
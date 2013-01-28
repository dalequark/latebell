	var classIDlength = 5;
	var maxNetID = 20;
	
	function validateForm(){
		var classID=document.forms["myForm"]["classID"].value;
		var netid=document.forms["myForm"]["netid"].value;
		if (classID == "" || isNaN(Number(classID)) || classID.length != classIDlength){
			alert("Not a valid classID !");
			return false;
		 }
		 
		 if(netid == "" || !alphanumeric(netid) || netid.length > maxNetID){
		 	alert("Not a valid netid !");
		 	return false;
		 }
	}
	
	//hooray for regular expressions!
	function alphanumeric(inputtxt) {   
	    var letters = /^[0-9a-zA-Z]+$/;  
	    if(inputtxt.match(letters)){   
	    	return true;  
	    }  
	    else{  
	    	return false;  
	    }  
	}  
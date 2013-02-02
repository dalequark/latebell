//keeps track of total rows in table.
var totalRows = 0;

//test netid
var netid;
casLogin();

//copy of each json string
var jsonArray = new Array();
//the current uniqueID selected for each table row
var uniqueIDs = new Array();
var uniqueIDs2 = new Array();

//load saved user course data
loadData(netid);

//takes a VALID classID, makes an AJAX call to scraper.php,
function tableMaker(id) {
	$.post("scripts/scraper.php", {
		classID : id
	}, function(data) {
		//checks if classID is in database.
		if (data == 0) {
			popMessage("Could not find classID :(", 0);
			return;
		} else if (data == 1) {
			popMessage("Could not reach Princeton Registrar : ( ", 1);
			return;
		}

		//data is a json string, so parse it and save it as json.
		var json = jQuery.parseJSON(data);

		//check to see if any of the sections are open
		var enrollments = json.enrollments;
		var open = true;
		for (var i = 0; i < enrollments.length; i++) {
			if (!isOpen(enrollments[i])) {
				open = false;
			}
		}

		if (open) {
			popMessage("Class is already open!");
		} else if (!open) {
			//add json to jsonArray[totalRows];

			jsonArray[totalRows] = json;

			//Create a new html table row and fill it with data (most importantly dropdown menu)
			var tr = document.createElement('tr');
			var td = document.createElement('td');
			td.appendChild(document.createTextNode(json.className));
			tr.appendChild(td);
			var dropdown = document.createElement('select');
			var attribute = document.createAttribute("onchange");
			attribute.nodeValue = "getEnrollments(this)";
			dropdown.setAttributeNode(attribute);
			attribute = document.createAttribute("id");
			attribute.nodeValue = "dropdown" + totalRows;
			dropdown.setAttributeNode(attribute);

			for (var i = 0; i < json.times.length; i++) {
				if (!isOpen(json.enrollments[i])) {
					var option = document.createElement('option');
					var attribute = document.createAttribute("value");
					attribute.nodeValue = i;
					option.setAttributeNode(attribute);
					var tds = json.sections[i] + " " + json.days[i] + " " + json.times[i];
					option.appendChild(document.createTextNode(tds));
					dropdown.appendChild(option);
				}
			}
			
			td = document.createElement('td');
			td.appendChild(dropdown);
			tr.appendChild(td);
			td = document.createElement('td');
			td.appendChild(document.createTextNode(json.enrollments[dropdown.value]));
			attribute = document.createAttribute("id");
			attribute.nodeValue = "enrollments" + totalRows;
			td.setAttributeNode(attribute);
			
			//set default uniqueIDs to the dropdown value uniqueID.
			uniqueIDs[totalRows] = json.uniqueIDs[dropdown.value];
			
			tr.appendChild(td);
		
			td = document.createElement('td');
			//add a delete button!
			var xButton = document.createElement("button");
			attribute = document.createAttribute("type");
			attribute.nodeValue = 'button';
			xButton.setAttributeNode(attribute);

			//class for bootstrap formatting
			attribute = document.createAttribute("class");
			attribute.nodeValue = "close";
			xButton.setAttributeNode(attribute);

			//set onclick to delete
			attribute = document.createAttribute("onclick");
			attribute.nodeValue = "remove(this)";
			xButton.setAttributeNode(attribute);

			xButton.appendChild(document.createTextNode('x'));
			td.appendChild(xButton);
			tr.appendChild(td);
			
			var theTable = document.getElementById('classTable');
			theTable.appendChild(tr);

			totalRows++;

			popMessage("Class Added!", 2);
		}
	});
}

var classIDlength = 5;

function validateID(id) {
	if (id == "" || isNaN(Number(id)) || id.length != classIDlength) {
		return false;
	} else {
		return true;
	}
}


$('#classID').keypress(function(event) {
	if (event.which == 13) {
		var classID = document.getElementById('classID').value;
		document.getElementById('classID').value = "";
		if (!validateID(classID)) {
			popMessage("Not a valid classID, you dolt!", 'error');
		} else {
			tableMaker(classID);
		}
	}
});

function popMessage(errormessage, type) {
	$color = "\'alert alert-info\'";
	switch(type) {
		case 2:
			$color = "\'alert alert-success\'";
			break;
		default:
			$color = "\'alert alert-error\'";
			break;
	}

	document.getElementById('errormessage').outerHTML = "<div class=" + $color + " id='errormessage'>" + errormessage 
	+ "<button type='button' class='close' onclick='hidealert()' data-dismiss='alert'>&times;</button></div>";
	$('#classID').keydown(function(event) {
		hidealert();
	});
	$('#classID').focus(function(event) {
		hidealert();
	});
}

function hidealert() {
	document.getElementById('errormessage').outerHTML = "<div class='alert alert-info' id='errormessage'>Enter classID to start watching classes!</div>";
}

function isOpen(data) {
	data = String(data);
	var enrolled = data.substring(9, data.search("Limit:") - 2);
	var limit = data.substring(data.search("t:") + 2);
	if (Number(enrolled) < Number(limit)) {
		return true;
	} else {
		return false;
	}
}

function getEnrollments(obj) {
	var whichRow = obj.id.substring(8);
	var whichEnrollment = 'enrollments' + whichRow;
	var whichSelection = obj.value;
	document.getElementById(whichEnrollment).innerHTML = jsonArray[whichRow].enrollments[whichSelection];

	//set the uniqueID at index whichRow to the json.uniqueID.
	uniqueIDs[whichRow] = jsonArray[whichRow].uniqueIDs[whichSelection];
	alert(uniqueIDs[whichRow]);
}

function submit() {
	var hasClass = false;
	var j = 0;
	for(var i = 0; i<uniqueIDs.length; i++){
		if(uniqueIDs[i] != null){
			hasClass = true;
			uniqueIDs2[j] = uniqueIDs[i];
			j++;
		}
	}
	
	if(!hasClass){
		popMessage("No classes selected! :O");
		return;
	}
	
	$.post("scripts/submit.php", {
		netid : netid,
		uniqueIDs : uniqueIDs2
	}, function(data) {
		if (data) {
			popMessage("Error, could not contact database");
		} else {
			popMessage("Success! You are now hunting classes", 2);
		}
	});
}

//ajax request for data from server php script, takes json string of classIDs and runs rowMaker
function loadData(netid) {
	$.post("scripts/loaddata.php", {
		netid : netid
	}, function(data) {
		if (data != 'noClasses') {
			var json = jQuery.parseJSON(data);
			for (var i = 0; i < json.urls.length; i++) {
				rowMaker(json.urls[i]);
			}
		}
	});
}

function rowMaker(url) {

	$.post("scripts/load_scraper.php", {
		url : url
	}, function(data) {
		//data is a json string, so parse it and save it as json.
		var json = jQuery.parseJSON(data);

		//add json to jsonArray[totalRows];
		jsonArray[totalRows] = json;

		//Create a new html table row and fill it with data (most importantly dropdown menu)
		var tr = document.createElement('tr');
		var td = document.createElement('td');
		td.appendChild(document.createTextNode(json.className));
		tr.appendChild(td);
		var dropdown = document.createElement('select');
		var attribute = document.createAttribute("onchange");
		attribute.nodeValue = "getEnrollments(this)";
		dropdown.setAttributeNode(attribute);
		attribute = document.createAttribute("id");
		attribute.nodeValue = "dropdown" + totalRows;
		dropdown.setAttributeNode(attribute);

		for (var i = 0; i < json.times.length; i++) {
			if (!isOpen(json.enrollments[i])) {
				var option = document.createElement('option');
				var attribute = document.createAttribute("value");
				attribute.nodeValue = i;
				option.setAttributeNode(attribute);
				var tds = json.sections[i] + " " + json.days[i] + " " + json.times[i];
				option.appendChild(document.createTextNode(tds));
				dropdown.appendChild(option);
			}
		}
		td = document.createElement('td');
		td.appendChild(dropdown);
		tr.appendChild(td);
		td = document.createElement('td');
		td.appendChild(document.createTextNode(json.enrollments[dropdown.value]));
		attribute = document.createAttribute("id");
		attribute.nodeValue = "enrollments" + totalRows;
		td.setAttributeNode(attribute);
		//set default uniqueIDs to the dropdown value uniqueID.
		uniqueIDs[totalRows] = json.uniqueIDs[dropdown.value];
		tr.appendChild(td);
		
		td = document.createElement('td');
		//add a delete button!
		var xButton = document.createElement("button");
		attribute = document.createAttribute("type");
		attribute.nodeValue = 'button';
		xButton.setAttributeNode(attribute);

		//class for bootstrap formatting
		attribute = document.createAttribute("class");
		attribute.nodeValue = "close";
		xButton.setAttributeNode(attribute);

		//set onclick to delete
		attribute = document.createAttribute("onclick");
		attribute.nodeValue = "remove(this)";
		xButton.setAttributeNode(attribute);

		xButton.appendChild(document.createTextNode('x'));

		td.appendChild(xButton);

		tr.appendChild(td);
		var theTable = document.getElementById('classTable');
		theTable.appendChild(tr);

		totalRows++;

	});
}

function remove(clss) {
	var rowIndex = Number( clss.parentNode.parentNode.childNodes[2].id.substring(11) );
	uniqueIDs[rowIndex] = null;
	attribute = document.createAttribute("id");
	attribute.nodeValue = "dale";
	clss.parentNode.parentNode.setAttributeNode(attribute);
	$('#dale').remove();
	
}

function casLogin(){
	$.post("scripts/validate.php",
	 function(data) {
	 	netid = data;
	 	document.getElementById('netid').innerHTML = netid;
	 });
}

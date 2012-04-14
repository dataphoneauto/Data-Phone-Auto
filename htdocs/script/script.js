function afficher(obj){
        document.getElementById(obj).style.display = "block";
}

function cacher(obj){
        document.getElementById(obj).style.display = "none";
}


function signIn() {
	/* Autre facon */
	//if ((document.forms['test'].elements['pseudo'].value) == "") {
	var pseudoValue = document.getElementById('pseudo').value;
	var passValue = document.getElementById('pass').value;
	var valid;
	if (pseudoValue == "") {
		valid = false;
		document.getElementById('pseudo').className = 'incorrect';
	}
	else	document.getElementById('pseudo').className = 'correct';
	if (passValue == "") {
		valid = false;
		document.getElementById('pass').className = 'incorrect';
	}
	else	document.getElementById('pass').className = 'correct';
	if (valid == false)
		return false
	else	return true;
}

function validateForm() {
	var emailValue = document.getElementById('email').value;
	var pseudoValue = document.getElementById('pseudo').value;
	var passValue = document.getElementById('pass').value;
	var valid;
	if (emailValue.search("@") != -1)
		document.getElementById('email').className = 'correct';
	else {
		valid = false;
		document.getElementById('email').className = 'incorrect';
	}
	if (pseudoValue == "") {
		valid = false;
		document.getElementById('pseudo').className = 'incorrect';
	}
	else	document.getElementById('pseudo').className = 'correct';
	if (passValue == "" || passValue.length < 5) {
		valid = false;
		document.getElementById('pass').className = 'incorrect';
	}
	else 	document.getElementById('pass').className = 'correct';
	if (valid == false)
		return false
	else	return true;
}

function showHelp(arg) {
	switch(arg) {
		case 'email':	document.getElementById('message').innerHTML = "L'email doit passer un '@'";
				break;
		case 'pseudo':	document.getElementById('message').innerHTML = "Le pseudo ne doit pas être nul";
				break;
		case 'pass':	document.getElementById('message').innerHTML = "Le pass doit contenir au moins 4 caractères";
				break;
	
	}
}

function hideHelp(arg) {
	switch(arg) {
		case 'email':	document.getElementById('message').innerHTML = "";
				break;
		case 'pseudo':	document.getElementById('message').innerHTML = "";
				break;
		case 'pass':	document.getElementById('message').innerHTML = "";
				break;
	
	}
}

function login() {
	if (document.getElementById('log').innerHTML == "")
		document.getElementById('log').innerHTML = '<br /><br /><br /><form method=\"post\" action=\"../index\" name=\"formSignIn\" onsubmit=\"return signIn();\"><table id=\"login\"><tr><td><label>Pseudo :</label></td><td><input id=\"pseudo\" class=\"pseudo\" name=\"pseudo\" type=\"text\" /></td><td><label>Pass :</label></td><td><input id=\"pass\" class=\"pass\" name=\"pass\" type=\"password\" /></td><input id=\"login\" class=\"boutons\" type=\"submit\" value=\"Connexion\" /></tr></table></form><br />';
	else	document.getElementById('log').innerHTML = "";
	if (document.getElementById('try_connect').innerHTML != "")
		document.getElementById('try_connect').innerHTML = "";
}

function updateWall() {
	//var date = arg;
	$("#update").load("/../profile/graph", function() {
                  alert('Load was performed.');
        });
}

function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Name');
        data.addColumn('string', 'Manager');
        data.addColumn('string', 'ToolTip');
        data.addRows([
          [{v:'Geoffroy de Guillebon', f:'<a href="mailto:deguille@ece.fr">Geoffroy de Guillebon</a><img src="../../images/equipe/degu.jpg" alt="degu" height="127,5" width="95,25" ><div style="color:red; font-style:italic">Chef de Projet</div>'}, '', 'Chef de Projet'],
          [{v:'Quentin Delmas', f:'<a href="mailto:delmas@ece.fr">Quentin Delmas</a><img src="../../images/equipe/delm.jpg" alt="delm" height="127,5" width="95,25" ><div style="color:green; font-style:italic">Responsable qualité</div>'}, 'Geoffroy de Guillebon', 'RQ'],
          [{v:'Guillaume Jolivet', f:'<a href="mailto:jolivet@ece.fr">Guillaume Jolivet</a><img src="../../images/equipe/joli.jpg" alt="joli" height="127,5" width="95,25" ><div style="color:green; font-style:italic">Ingénieur T&R</div>'}, 'Geoffroy de Guillebon', 'I-TR'],
          [{v:'Antoine Perrier', f:'<a href="mailto:perrier@ece.fr">Antoine Perrier</a><img src="../../images/equipe/perr.jpg" alt="perr" height="127,5" width="95,25" ><div style="color:green; font-style:italic">Ingénieur T&R</div>'}, 'Geoffroy de Guillebon', 'I-TR'],
          [{v:'Christian Trung', f:'<a href="mailto:trung@ece.fr">Christian Trung</a><img src="../../images/equipe/trun.jpg" alt="trun" height="127,5" width="95,25" ><div style="color:green; font-style:italic">Responsable SI</div>'}, 'Geoffroy de Guillebon', 'R-SI'],
        ]);
        var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
        chart.draw(data, {allowHtml:true});
}

function drawCarChart() {
	var data = new Array();
        //var data1 = new google.visualization.DataTable();
        //var data2 = new google.visualization.DataTable();
	//alert(dump(allGraphData));
	var nbData = nbAllGraphData.length;
	var carData = new Array();
	// allGraphData
	// allGraphInfos
	// allGraphDate
	for (var pid in allGraphData) {
		carData[pid] = new Array();
		for (var i = 0; i < allGraphData[pid].length; i++) {
			carData[pid][i] = parseFloat(allGraphData[pid][i]);
		}
        }
        //alert(dump(carData));
	/*for (var i = 0; i < carData.length; i++) {
		for (var j = 0; j < carData[i].length; j++) {
			alert(carData[i][j]);
		}
	}*/

        //data.addColumn('string', 'Date');
        //data.addColumn('number', 'Valeur');
	//alert(dump(allGraphInfos['0104']['description']));	
	
	var count = 1;
	for (var pid in allGraphData) {
		var tmp = new google.visualization.DataTable();
		data[count] = tmp;
		data[count].addColumn('string', 'Date');
        	data[count].addColumn('number', 'Valeur');
		for (var k = 0; k < carData[pid].length; k++) {
			data[count].addRow([allGraphDate[k], carData[pid][k]]);
		}

		var options = {
			title: allGraphInfos[pid]['description'],
			legend: 'none'
		};
		
		var divName = 'chart_div' + count.toString();
		//alert(divName);
		var chart = new google.visualization.LineChart(document.getElementById(divName));
		chart.draw(data[count], options);
		count++;
	}
		
	/*for (var k = 0; k < carData['0104'].length; k++) {
		data1.addRow([allGraphDate[k], carData['0104'][k]]);
	}

	var options = {
		title: allGraphInfos['0104']['description'],
		legend: 'bottom'
	};

	var chart2 = new google.visualization.LineChart(document.getElementById('chart_div1'));
	chart2.draw(data1, options);
	
        data2.addColumn('string', 'Year');
        data2.addColumn('number', 'Sales');

	for (var k = 0; k < carData['010c'].length; k++) {
		data2.addRow([allGraphDate[k], carData['010c'][k]]);
	}

	var options = {
		title: allGraphInfos['010c']['description'],
		legend: 'bottom'
	};

	var chart3 = new google.visualization.LineChart(document.getElementById('chart_div2'));
	chart3.draw(data2, options);*/
//}

/*        data.addRows([
                ['15/12/1875 20:35', 1000],
                ['15/12/1875 20:35', 1170],
                ['15/12/1875 20:35',  860],
                ['15/12/1875 20:35', 1000],
                ['15/12/1875 20:35', 1170],
                ['15/12/1875 20:35', 1000],
                ['15/12/1875 20:35', 1170],
                ['15/12/1875 20:35', 1000],
                ['15/12/1875 20:35', 1170],
                ['15/12/1875 20:35', 1000],
                ['15/12/1875 20:35', 1170],
                ['15/12/1875 20:35', 1000],
                ['15/12/1875 20:35', 1170],
                ['15/12/1875 20:35', 1000],
                ['15/12/1875 20:35', 1170],
                ['15/12/1875 20:35',  860],
                ['15/12/1875 20:35', 1000],
                ['15/12/1875 20:35', 1170],
                ['15/12/1875 20:35', 1000],
                ['15/12/1875 20:35', 1170],
                ['15/12/1875 20:35', 1000],
                ['15/12/1875 20:35', 1170],
                ['15/12/1875 20:35', 1000],
                ['15/12/1875 20:35', 1170],
                ['15/12/1875 20:35', 1000],
                ['15/12/1875 20:35', 1170],
                ['15/12/1875 20:35', 1170],
                ['15/12/1875 20:35', 1000],
                ['15/12/1875 20:35', 1170],
                ['15/12/1875 20:35', 1030]
                ]);*/

       /* var options = {
        //title: 'Company Performance'
//        title: "HOLEEEEE",
	title: allGraphInfos['0104']['description'],
        legend: 'bottom'
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);*/
}

function dump(arr,level) {
	var dumped_text = "";
	if(!level) level = 0;
	
	//The padding given at the beginning of the line.
	var level_padding = "";
	for(var j=0;j<level+1;j++) level_padding += "    ";
	
	if(typeof(arr) == 'object') { //Array/Hashes/Objects 
		for(var item in arr) {
			var value = arr[item];
			
			if(typeof(value) == 'object') { //If it is an array,
				dumped_text += level_padding + "'" + item + "' ...\n";
				dumped_text += dump(value,level+1);
			} else {
				dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
			}
		}
	} else { //Stings/Chars/Numbers etc.
		dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
	}
	return dumped_text;
}

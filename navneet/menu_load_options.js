var lang = ["Hindi","Gondi"];
var lang_url = ["hindi","gondi","pujabi"];

var states = ["Madhya Pradesh","Chhattisgarh","Odisha","Jharkhand"];
var states_url = ["MP","CG","Odisha","Jharkhand"];

var dis = [
			["Rewa", "Mandla", "Dindori", "Satna", "Panna", "Sidhi"],
			["Kawardha", "Rajnandgaon", "Surajpur", "Balrampur", "Surguja", "Korba", "Janjgir", "Sukma", "Bijapur", "Bastar"],
			["Malkangiri", "Koraput", "Rayagada", "Sundergarh", "Deogarh", "Mayurbhanj", "Bargarh", "Deogarh"],
			["Chatra", "Palamu", "Bokaro"],
		];
var dis_url = [
			["Rewa", "Mandla", "Dindori", "Satna", "Panna", "Sidhi"],
			["Kawardha", "Rajnandgaon", "Surajpur", "Balrampur", "Surguja", "Korba", "Janjgir", "Sukma", "Bijapur", "Bastar"],
			["Malkangiri", "Koraput", "Rayagada", "Sundergarh", "Deogarh", "Mayurbhanj", "Bargarh", "Deogarh"],
			["Chatra", "Palamu", "Bokaro"],
		];


var subject = ["NREGA","Forest","Land"];
var subject_url = ["nrega","forest","land"];


var def_loaction = "http://cgnetswara.org/navneet/index.php"; 


function lang_load_options()
{

	dl = document.getElementById("language-option");
	for(i=0;i<lang.length;i++)
	{
		var op = document.createElement("a");
		op.id="a_lang_cat";
		op.innerHTML = lang[i];
		op.href = def_loaction+"?tag="+lang_url[i];
		dl.appendChild(op);
	}
}

function subject_load_options()
{
	dl = document.getElementById("subjects-option");
	
	for(i=0;i<subject.length;i++)
	{
		var op = document.createElement("a");
		op.id="a_subject_cat";
		op.innerHTML = subject[i];
		op.href = def_loaction+"?tag="+subject_url[i];
		dl.appendChild(op);
	}
}

function states_load_options()
{
	dl = document.getElementById("state-option");
	var div_st_1 = "<div id =\"";
	var div_st_2 = "\" class=\"hover_cat\" onmouseover=\"show_submenu('states','NREGA','"
	var div_st_3 = "','state-option');\">";
	var div_end = "</div>\n"

	var whole_menu = "\n";

	for(i=0;i<states.length;i++)
	{
		whole_menu = whole_menu+div_st_1+states_url[i]+div_st_2+(i+1)+div_st_3+'<a href ="'+def_loaction+"?tag="+states_url[i]+'">'+states[i]+'</a>'+div_end;
	}


	dl.innerHTML = whole_menu;
}

function dis_load_options(index)
{
	index = index-1;


		
	for(i=0;i<states.length;i++)
	{
		var col = document.getElementById(states_url[i]);
		if(col!=null)
		{
			col.style.backgroundColor="#29120c";
		}
	}


	col = document.getElementById(states_url[index]);
	col.style.backgroundColor="#db572f";

	dl = document.getElementById("NREGA");
	var div_st = "<div id=\"hover_sub_cat\" class=\"subcat\" onmouseover=\"disp_menu('state-option'); \"  onmousemove=\"setit();\">";	
	var div_end = "</div>\n";
	var whole_menu="\n";

	for(i=0;i<dis[index].length;i++)
	{
		whole_menu = whole_menu+div_st+'<a id="sa" href ="'+def_loaction+"?tag="+dis_url[index][i]+'">'+dis[index][i]+'</a>'+div_end;
	}
	dl.innerHTML = whole_menu;
}


lang_load_options();
subject_load_options();
states_load_options();

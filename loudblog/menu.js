var state = ["sta","stb","stc"];
var dist = [
		["dis1","dis2","dis3"],
		["dis4","dis5"],
		["dis6","dis7","dis8","dis9"],
	];
var block = [
		[
			["b1","b2","b3"],
			["b4","b5"],
			["b6","b7","b8"],
		],		
		[
			["b9"],
			["b10"],
		],
		[
			["b11","b12"],
			["b13","b14"],
			["b15"],
			["b16","b17"],
		],
	];
var subject = ["s1","s2","s3","s4"];

var i1 = 0;
var i2 = 0;
var i3 = 0;
var i3_3 = 0;
var i4 = 0;


function ine()
{

d1 = document.getElementById("state");
d2 = document.getElementById("dis");
d3 = document.getElementById("block");
d4 = document.getElementById("subject");

var st = "";
for(i=0;i<state.length;i++)
{
	st = st + "<option>"+state[i]+"</option>";
}
d1.innerHTML = st;

var di = "";
for(i=0;i<dist[0].length;i++)
{
	di = di + "<option>"+dist[0][i]+"</option>";
}
d2.innerHTML = di;

var bl = "";
for(i=0;i<block[0][0].length;i++)
{
	bl = bl + "<option>"+block[0][0][i]+"</option>";
}
d3.innerHTML = bl;

var su = "";
for(i=0;i<subject.length;i++)
{
	su = su + "<option>"+subject[i]+"</option>";
}
d4.innerHTML = su;
}




function f1()
{

	var d1 = document.getElementById("state");
	var d2 = document.getElementById("dis");
	var d3 = document.getElementById("block");

	var st = d1.value;
	var ind = state.indexOf(st);

	var di = "";

	for(i=0;i<dist[ind].length;i++)
	{
		di = di + "<option>"+dist[ind][i]+"</option>";
	}

	d2.innerHTML = di;



	var bl = "";
	for(i=0;i<block[ind][0].length;i++)
	{
		bl = bl + "<option>"+block[ind][0][i]+"</option>";
	}
	d3.innerHTML = bl;

}
function f2()
{
	var d1 = document.getElementById("state");
	var d2 = document.getElementById("dis");
	var d3 = document.getElementById("block");

	var st = d1.value;
	var dt = d2.value;

	var ind1 = state.indexOf(st);
	var ind2 = dist[ind1].indexOf(dt);
	
	var len = block[ind1][ind2].length;

	var bl = "";
	for(i=0;i<len;i++)
	{
		bl = bl + "<option>"+block[ind1][ind2][i]+"</option>";
	}
	d3.innerHTML = bl;

}


var rem;
var set=1;

function disp_menu_1(get_val)
{

	au = document.getElementsByClassName("aud");
	i=0;
	while(au[i]!=null)
	{
		au[i].style.display = "none";
		i=i+1;
	}


	set =0;
	col = document.getElementById("states");
	col.style.backgroundColor="#79431f";
		
	if(get_val=="language-option")
	{
		col = document.getElementById("lang");
		col.style.backgroundColor="#29120c";
	}

	if(get_val=="subjects-option")
	{
		col = document.getElementById("sub");
		col.style.backgroundColor="#29120c";
	}

	document.getElementById('state-option').style.display="none";
	document.getElementById('NREGA').style.display="none";


	var d1 = document.getElementById(get_val);
	d1.style.display = 'block';
}

function disp_menu(get_val)
{

        au = document.getElementsByClassName("aud");
        i=0;
        while(au[i]!=null)
        {
                au[i].style.display = "none";
                i=i+1;
        }



	set =0;

	if(get_val=="state-option")
	{
		col = document.getElementById("states");
		col.style.backgroundColor="#29120c";
	}
		
	var d1 = document.getElementById(get_val);
	d1.style.display = 'block';
}
function hide_menu(get_val)
{


        au = document.getElementsByClassName("aud");
        i=0;
        while(au[i]!=null)
        {
                au[i].style.display = "block";
                i=i+1;
        }



	col = document.getElementById("lang");
	col.style.backgroundColor="#79431f";

	col = document.getElementById("sub");
	col.style.backgroundColor="#79431f";

	col = document.getElementById("states");
	col.style.backgroundColor="#79431f";


	var d1 = document.getElementById(get_val);
	d1.style.display="none";
}


function show_submenu(s1,g1,mul,orig)
{
	s2 = document.getElementById(s1);
	g2 = document.getElementById(g1);
	dis_load_options(mul);
	o2 = document.getElementById(orig);
	o2.style.display = "block";

	mul = parseInt(mul);
	index = mul-1;
	var g1 = mul*45;
	if(mul>1)
	{
		g1 = g1-(2*(mul));
		g1 = g1-mul+4;
	}
	var rect = s2.getBoundingClientRect();
	console.log(rect.top, rect.right, rect.bottom, rect.left);
	g2.style.left = 182+s2.offsetLeft+"px";
	g2.style.top = g1+s2.offsetTop+"px";
	g2.style.display = "block";
}



function hide_all_menu(get_val1,get_val2)
{

	if(set == 1)
	
{
        au = document.getElementsByClassName("aud");
        i=0;
        while(au[i]!=null)
        {
                au[i].style.display = "block";
                i=i+1;
        }


		col = document.getElementById("states");
		col.style.backgroundColor="#79431f";

		var d1 = document.getElementById(get_val1);
		d1.style.display="none";
		var d2 = document.getElementById(get_val2);
		d2.style.display="none";
	}
}

	function setit(i)
	{
		if(i==0)
		{
			col = document.getElementById("lang");
			col.style.backgroundColor="#29120c";
		}
		if(i==1)
		{
			col = document.getElementById("states");
			col.style.backgroundColor="#29120c";
		}
		if(i==2)
		{
			col = document.getElementById("sub");
			col.style.backgroundColor="#29120c";
		}
		set = 0;
	}
function delay_remove(ele1,ele2)
{
	setTimeout(hide_all_menu,10,ele1,ele2);
	set =1;
}

function keep_alive(d1,d2)
{
	set =0;
	d1 = document.getElementById(d1);
	d2 = document.getElementById(d2);
	d1.style.display="block";
	d1.style.disply="block";
}


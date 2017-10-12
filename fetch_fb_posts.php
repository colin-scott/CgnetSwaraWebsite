<!DOCTYPE html>
<?php
	
?>
<html>
<head>
<style>
div{
	border: 1px solid;
}
</style>
<script>
ids = new Array();

function init(){
var unlimitedAccessToken = 'EAASYhfO68sgBAHUBAqHx5oOL7FzfbZCv9fhOtzQLnBtli4WWuEgIB5HZALszSmfBZBY40DBxxc73kK3P5oaMqbPs84Vp1eBvEb9cwYqbqPUOgPRTtSQJl0ZABk9tttXONvUR6MPBFYvCWNmhGiG9rr6n6IFskiHJ2hbDEXuZCdQZDZD';
FB.api(
    "/108247265894675/feed?fields=message, link, permalink_url",
	'GET', {

          "access_token": unlimitedAccessToken ,
	  "limit": 100
	},
    function (response) {
      if (response && !response.error) {
        /* handle the result */
	
	console.log(response.data.length);
	for(i=0;i<response.data.length;i++){
		console.log(response.data[i].link);
		var id = response.data[i].id;
		var message = response.data[i].message;
		if (!message)
			message = response.data[i].link;
	
		var postLink = response.data[i].permalink_url;

		var elem = document.createElement("div");
		var txt = document.createTextNode(message);
		elem.appendChild(txt);
		
		getcomments(id, elem, unlimitedAccessToken);
		document.getElementsByTagName('body')[0].appendChild(elem);

		var divelem = document.createElement("div");
		divelem.setAttribute('class', 'fb-comments');
		divelem.setAttribute('data-href', postLink);
		divelem.setAttribute('data-numposts', '5');
		document.getElementsByTagName('body')[0].appendChild(divelem);		
		
	}
      }
    }
);

//console.log(ids);
//for(i=0;i<ids.length;i++)
function getcomments(id, elem, unlimitedAccessToken)
{
	FB.api(
    			"/"+id+"/comments",
    			{
				"access_token": unlimitedAccessToken,
        			"summary": true,
        			"order": 'reverse_chronological'
    			},
    			function (response1) {
      				if (response1 && !response1.error) {
        				/* handle the result */
					
					if (response1.data.length>0){
					//alert(id);
					comdiv = document.createElement("p");
					for(j=0;j<response1.data.length;j++){
						//console.log(response1.data[j].message+'     ' +id);						
						com = "                             "+response1.data[j].message;
						comtxt = document.createTextNode(com);
						comdiv.appendChild(comtxt);
						elem.appendChild(comtxt);
						//document.getElementsByTagName('div')[i].innerHTML+="\n"+response1.data[j].message;
					}
					
					}
      				}
    			}
	);
}
}


	window.fbAsyncInit = function() {
    FB.init({
      appId      : '1293600994030280',
      xfbml      : true,
      version    : 'v2.8'
    });
	init();
    FB.AppEvents.logPageView();
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

</script>
</head>
<body>
	<div id="fb_posts"></div>
	<!--
	<div class="fb-post" data-href="https://www.facebook.com/CGNetSwara/posts/1431723376880384"></div>
	<div class="fb-comments" data-href="http://cgnetswara.org/index.php?id=106716" data-numposts="5"></div>
	<div class="fb-comment-embed" data-href="https://www.facebook.com/CGNetSwara/posts/1435578433161545?comment_id=1435627759823279&amp;comment_tracking=%7B%22tn%22%3A%22R%22%7D" data-width="560" data-include-parent="false"></div>
	-->
</body>
<!--
/*every post
		FB.api(
    			"/"+id+"/comments",
    			{
				"access_token": 'EAASYhfO68sgBAMoTojZASwf5pYKEJYy9uQ0LmTyryfsKbDjy13rONxGxhEiAraGxlXvSXZCaMZCENREZBZBNYxlCi04szdH7C00PuuOXlOAcnlIcPoKZBoaZByNv5Qy0fRtDHLLMWgOXGMaiUW1Vo4eaiDBrcUZBjAax548UNnmzvN0ZAmCXYbMSH0CrPgj9reLYZD',
        			"summary": true,
        			"order": 'reverse_chronological'
    			},
    			function (response1) {
      				if (response1 && !response1.error) {
        				/* handle the result */
					alert(id);
					if (response1.data.length>0){
					//comdiv = document.createElement("p");
					for(j=0;j<response1.data.length;j++){
						//alert(response1.data[j].message);						
						//com = response1.data[j].message;
						comtxt = document.createTextNode("        "+response1.data[j].message);
						//comdiv.appendChild(comtxt);
						elem.appendChild(comtxt);
					}
					
					}
      				}
    			}
		);
		
		/*************************/
--></html>

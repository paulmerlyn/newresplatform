/*
Cross browser Marquee script- © Dynamic Drive http://www.dynamicdrive.com/dynamicindex2/cmarquee.htm
For full source code, 100's more DHTML scripts, and Terms Of Use, visit http://www.dynamicdrive.com
Credit MUST stay intact.
(Note: I added the striptags() function below.)
*/

//Specify the marquee's width (in pixels)
var marqueewidth="750px"
//Specify the marquee's height
var marqueeheight="30px"
//Specify the marquee's marquee speed (larger is faster 1-10)
var marqueespeed=3;
//configure background color:
// var marqueebgcolor="#DEFDD9"   Note: I've now removed any background color. Add one back in by amending the line below st it has background-color as shown: write('<div style="position:absolute;width:'+marqueewidth+';height:'+marqueeheight+';background-color:'+marqueebgcolor+'" onMouseover="copyspeed=pausespeed" onMouseout="copyspeed=marqueespeed">') AND write('<ilayer width='+marqueewidth+' height='+marqueeheight+' name="ns_marquee" bgColor='+marqueebgcolor+'>')
//Pause marquee onMousever (0=no. 1=yes)?
var pauseit=0;

function striptags() // Source: http://webdevtips.co.uk/webdevtips/js/striptag.shtml. Note: Javascript doesn't have a built-in function for this.
{
var re= /<\S[^>]*>/g; 
marqueecontent = marqueecontent.replace(re,""); 
//alert('marqueecontent after tags stripped by striptags function is: ' + marqueecontent); 
return marqueecontent;
} 

//Specify the marquee's content (to ensure content scrolls on a single line, don't delete <nobr> tag)
//Keep all content on ONE line, and backslash any single quotations (ie: that\'s great):

var marqueecontent;
marqueecontent = document.getElementById('locationscroll').innerHTML;  // div locationscroll is defined in index.shtml.
//alert('Raw marqueecontent straight from the SSI is: ' + marqueecontent);
marqueecontent = marqueecontent.replace("Locale", ""); // Strip these from the table's header row
marqueecontent = marqueecontent.replace(/Locations\<\/td\>/i, ""); // Strip these from the table's header row - use i flag to denote case-insensitivity b/c IE capitalizes the <TD> tag.
marqueecontent = marqueecontent.replace(/[\n\r\f]/g,"");  // Strip ALL new lines for IE.
marqueecontent = marqueecontent.replace(/&nbsp;&nbsp;/gi, "");
//alert('marqueecontent after stripping Locale, Locations, and line feeds is: ' + marqueecontent);
marqueecontent = marqueecontent.replace(/\:/g, " ("); // note the global g flag indicates replacement of ALL colon instances with 'open parenthesis + space'
marqueecontent = marqueecontent.replace(/<\/td><\/tr><tr>/gi, ")&nbsp;&nbsp;&ndash;&nbsp;&nbsp;"); // Replace all '<\td><\tr><tr>' with a ') - ' at the end of all except the final set of locations (need cae insensitivy b/c IE uses caps whereas FF doesn't).
marqueecontent = marqueecontent.replace(/<\/table>/i,")"); // Append a ')' to the final set of locations.
//alert('marqueecontent after adding parentheses is: ' + marqueecontent);
marqueecontent = striptags(marqueecontent);
marqueecontent = "<nobr>Now serving the following communities:&nbsp;" + marqueecontent + "</nobr>";
//alert('marqueecontent after addition of nobrs is: ' + marqueecontent);

////NO NEED TO EDIT BELOW THIS LINE////////////
marqueespeed=(document.all)? marqueespeed : Math.max(1, marqueespeed-1) //slow speed down by 1 for NS
var copyspeed=marqueespeed
var pausespeed=(pauseit==0)? copyspeed: 0
var iedom=document.all||document.getElementById
if (iedom)
document.write('<span id="temp" style="visibility:hidden;position:absolute;top:-100px;left:-9000px">'+marqueecontent+'</span>')
var actualwidth=''
var cross_marquee, ns_marquee

function populate(){
if (iedom){
cross_marquee=document.getElementById? document.getElementById("iemarquee") : document.all.iemarquee
cross_marquee.style.left=parseInt(marqueewidth)+8+"px"
cross_marquee.innerHTML=marqueecontent
actualwidth=document.all? temp.offsetWidth : document.getElementById("temp").offsetWidth
}
else if (document.layers){
ns_marquee=document.ns_marquee.document.ns_marquee2
ns_marquee.left=parseInt(marqueewidth)+8
ns_marquee.document.write(marqueecontent)
ns_marquee.document.close()
actualwidth=ns_marquee.document.width
}
lefttime=setInterval("scrollmarquee()",20)
}
window.onload=populate

function scrollmarquee(){
if (iedom){
if (parseInt(cross_marquee.style.left)>(actualwidth*(-1)+8))
cross_marquee.style.left=parseInt(cross_marquee.style.left)-copyspeed+"px"
else
cross_marquee.style.left=parseInt(marqueewidth)+8+"px"

}
else if (document.layers){
if (ns_marquee.left>(actualwidth*(-1)+8))
ns_marquee.left-=copyspeed
else
ns_marquee.left=parseInt(marqueewidth)+8
}
}

if (iedom||document.layers){
with (document){
document.write('<table border="0" cellspacing="0" cellpadding="0"><td>')
if (iedom){
write('<div style="position:relative;width:'+marqueewidth+';height:'+marqueeheight+';overflow:hidden">')
write('<div style="position:absolute;width:'+marqueewidth+';height:'+marqueeheight+';" onMouseover="copyspeed=pausespeed" onMouseout="copyspeed=marqueespeed">')
write('<div id="iemarquee" style="position:absolute;left:0px;top:0px"></div>')
write('</div></div>')
}
else if (document.layers){
write('<ilayer width='+marqueewidth+' height='+marqueeheight+' name="ns_marquee">')
write('<layer name="ns_marquee2" left=0 top=0 onMouseover="copyspeed=pausespeed" onMouseout="copyspeed=marqueespeed"></layer>')
write('</ilayer>')
}
document.write('</td></table>')
}
}

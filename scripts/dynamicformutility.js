// Copyright © 2000 by Apple Computer, Inc., All Rights Reserved.
//
// You may incorporate this Apple sample code into your own code
// without restriction. This Apple sample code has been provided "AS IS"
// and the responsibility for its operation is yours. You may redistribute
// this code, but you are not permitted to redistribute it as
// "Apple sample code" after having made changes.
// Reference http://developer.apple.com/internet/webcontent/dynamicforms.html

function hideAll(elementID)
{
	var elementID;
	changeDiv(elementID, "none");
}

//Function to show (i.e. set the display style property to 'block') of an element (e.g. a div that is referenced by its ID). This function is called by ShowHideDivDirectorStates to show the content that resides inside the specified div (e.g. state-specific content while content for other states has its display style property set to 'none').
function showAll(elementID)
{
	var elementID;
	changeDiv(elementID, "block");
}

function changeDiv(the_div,the_change)
{
  var the_style = getStyleObject(the_div);
  if (the_style != false)
  {
    the_style.display = the_change;
  }
}

function getStyleObject(objectId) {
  if (document.getElementById && document.getElementById(objectId)) {
    return document.getElementById(objectId).style;
  } else if (document.all && document.all(objectId)) {
    return document.all(objectId).style;
  } else {
    return false;
  }
}

//Function to hide all state div elements. Function works by going through the states array, whose values (e.g. "CA", "AZ", "NM", etc.) are also the IDs of div elements. This function also hides the instruction for the user to specify a state.
function HideStateDivs()
{
var count;
for (count=0; count< numberOfStates; count++)
	{
	hideAll(states[count][0]);
	}
hideAll("instructions");
return;
}



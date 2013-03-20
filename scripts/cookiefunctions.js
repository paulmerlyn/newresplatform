// JavaScript Document

// Function to set the name and value pair of a cookie. User can also set a cookie path and expiration date or leave these values blank by setting them to "" and "". The default path is the directory of the HTML page in which the function is called. The default date is six months from the current date.
function SetCookie(cookieName, cookieValue, cookiePath, cookieExpires)
{
var index, id, thestate;
cookieValue = escape(cookieValue);
if (cookieExpires == "")
	{
	var nowDate = new Date();
	nowDate.setMonth(nowDate.getMonth() + 6);
	cookieExpires = nowDate.toGMTString();
	}
if (cookiePath != "")
	{
	cookiePath = ";Path=" + cookiePath;
	}
else
	{
	cookiePath = ";Path=/";
	}
document.cookie = cookieName + "=" + cookieValue + ";expires=" + cookieExpires + cookiePath;

// Also set the 'selectedstate' cookie whenever the SetCookie function is called if the 'selectedmediator' cookie was set to a value other than '' or null.
if ((cookieName == 'selectedmediator') && (cookieValue != '') && (cookieValue != null))
	{
	id = GetCookieValue("selectedmediator"); // Obtain the ID of the selectedmediator via the selectedmediator cookie.
	thestate = NewResolution.mediators[id].getMediatorState();
	document.cookie = 'selectedstate=' + thestate + ";expires=" + cookieExpires + cookiePath;
	}

// ... And override the value that may have just been previously set just above for the 'selectedstate' cookie if the 'selectedlocalelabel' cookie was set to a value other than '' or null.
if ((cookieName == 'selectedlocalelabel') && (cookieValue != '') && (cookieValue != null))
	{
	for (index in uniqueLocaleLabel)
		{
		if (escape(uniqueLocaleLabel[index]) == cookieValue) break; // Determine the index of the (escaped) uniqueLocale that matches the (already-escaped) cookieValue (which will be a locale if the 'if condition' is met)...
		}
// ... and use that index to retrieve the corresponding two-letter state abbrevn in uniqueLocaleLabelState[index] for setting the selectedstate cookie.
document.cookie = 'selectedstate=' + uniqueLocaleLabelState[index] + ";expires=" + cookieExpires + cookiePath;
	}

}

// Function to retrieve the value of a cookie, given the cookie's name.
function GetCookieValue(cookieName)
{
	var cookieValue = document.cookie;
	var cookieStartsAt = cookieValue.indexOf(" " + cookieName + "=");
	if (cookieStartsAt == -1)
	{
		cookieStartsAt = cookieValue.indexOf(cookieName + "=");
	}
	if (cookieStartsAt == -1)
	{
		cookieValue = null;
	}
	else
	{
		cookieStartsAt = cookieValue.indexOf("=",cookieStartsAt) + 1;
		var cookieEndsAt = cookieValue.indexOf(";", cookieStartsAt);
		if (cookieEndsAt == -1)
		{
			cookieEndsAt = cookieValue.length;
		}
		cookieValue = unescape(cookieValue.substring(cookieStartsAt,cookieEndsAt));
	}
	return cookieValue;
}
	
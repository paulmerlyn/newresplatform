// Declare or initialize global variables
var locationsflag;  // Set this value to false if you don't want the chosen mediator's locations to display
var mediatorspageflag = false;
var contactpageflag = false;
var feespageflag = false;
var customtitleflag = false; // Used for custom titles on pages such as support.shtml, which uses different words for spousal support.


// Define the constructor function for the mediator custom object (i.e. a class that is a template for mediators)
function mediator (mediatorID, mediatorName, mediatorCred, mediatorLocale, mediatorLocaleLabel, mediatorLocations, mediatorEmail, mediatorEntityName, mediatorPrincipalStreet, mediatorPrincipalAddressOther, mediatorCity, mediatorState, mediatorZip, mediatorTelephone, mediatorFax, mediatorProfile, mediatorHourlyRate, mediatorAdminCharge, mediatorAdminChargeDetails, mediatorLocationCharge, mediatorPackages, mediatorSlidingScale, mediatorIncrement, mediatorConsultationPolicy, mediatorCancellationPolicy, mediatorTelephoneMediations, mediatorVideoConf, mediatorCardPolicy, mediatorServiceLevel)
{
	this.mediatorID = mediatorID;
	this.mediatorName = mediatorName;
	this.mediatorCred = mediatorCred;
	this.mediatorLocale = mediatorLocale;
	this.mediatorLocaleLabel = mediatorLocaleLabel;
	this.mediatorLocations = mediatorLocations;
	this.mediatorEmail = mediatorEmail;
	this.mediatorEntityName = mediatorEntityName;
	this.mediatorPrincipalStreet = mediatorPrincipalStreet;
	this.mediatorPrincipalAddressOther = mediatorPrincipalAddressOther;
	this.mediatorCity = mediatorCity;
	this.mediatorState = mediatorState;
	this.mediatorZip = mediatorZip;
	this.mediatorTelephone = mediatorTelephone;
	this.mediatorFax = mediatorFax;
	this.mediatorProfile = mediatorProfile;
	this.mediatorHourlyRate = mediatorHourlyRate;
	this.mediatorAdminCharge = mediatorAdminCharge;
	this.mediatorAdminChargeDetails = mediatorAdminChargeDetails;
	this.mediatorLocationCharge = mediatorLocationCharge;
	this.mediatorPackages = mediatorPackages;
	this.mediatorSlidingScale = mediatorSlidingScale;
	this.mediatorIncrement = mediatorIncrement;
	this.mediatorConsultationPolicy = mediatorConsultationPolicy;
	this.mediatorCancellationPolicy = mediatorCancellationPolicy;
	this.mediatorTelephoneMediations = mediatorTelephoneMediations;
	this.mediatorVideoConf = mediatorVideoConf;
	this.mediatorCardPolicy = mediatorCardPolicy;
	this.mediatorServiceLevel = mediatorServiceLevel;
}

// Set and get methods for accessing the properties of the mediator custom object.
// Note: I don't think I'll need the set methods, but I'll keep them in case I want to use them later to change the value of one of the properties of a particular mediator object instance.
mediator.prototype.getMediatorID = function()
{
	return this.mediatorID;
}

mediator.prototype.setMediatorID = function(mediatorID)
{
	this.mediatorID = mediatorID;
}

mediator.prototype.getMediatorName = function()
{
	return this.mediatorName;
}

mediator.prototype.setMediatorName = function(mediatorName)
{
	this.mediatorName = mediatorName;
}

mediator.prototype.getMediatorCred = function()
{
	return this.mediatorCred;
}

mediator.prototype.setMediatorCred = function(mediatorCred)
{
	this.mediatorCred = mediatorCred;
}

mediator.prototype.getMediatorLocale = function()
{
	return this.mediatorLocale;
}

mediator.prototype.setMediatorLocale = function(mediatorLocale)
{
	this.mediatorLocale = mediatorLocale;
}

mediator.prototype.getMediatorLocaleLabel = function()
{
	return this.mediatorLocaleLabel;
}

mediator.prototype.setMediatorLocaleLabel = function(mediatorLocaleLabel)
{
	this.mediatorLocaleLabel = mediatorLocaleLabel;
}

mediator.prototype.getMediatorLocations = function()
{
	return this.mediatorLocations;
}

mediator.prototype.setMediatorLocations = function(mediatorLocations)
{
	this.mediatorLocations = mediatorLocations;
}

mediator.prototype.getMediatorEmail = function()
{
	return this.mediatorEmail;
}

mediator.prototype.setMediatorEmail = function(mediatorEmail)
{
	this.mediatorEmail = mediatorEmail;
}

mediator.prototype.getMediatorEntityName = function()
{
	return this.mediatorEntityName;
}

mediator.prototype.setMediatorEntityName = function(mediatorEntityName)
{
	this.mediatorEntityName = mediatorEntityName;
}

mediator.prototype.getMediatorPrincipalStreet = function()
{
	return this.mediatorPrincipalStreet;
}

mediator.prototype.setMediatorPrincipalStreet = function(mediatorPrincipalStreet)
{
	this.mediatorPrincipalStreet = mediatorPrincipalStreet;
}

mediator.prototype.getMediatorPrincipalAddressOther = function()
{
	return this.mediatorPrincipalAddressOther;
}

mediator.prototype.setMediatorPrincipalAddressOther = function(mediatorPrincipalAddressOther)
{
	this.mediatorPrincipalAddressOther = mediatorPrincipalAddressOther;
}

mediator.prototype.getMediatorCity = function()
{
	return this.mediatorCity;
}

mediator.prototype.setMediatorCity = function(mediatorCity)
{
	this.mediatorCity = mediatorCity;
}

mediator.prototype.getMediatorState = function()
{
	return this.mediatorState;
}

mediator.prototype.setMediatorState = function(mediatorState)
{
	this.mediatorState = mediatorState;
}

mediator.prototype.getMediatorZip = function()
{
	return this.mediatorZip;
}

mediator.prototype.setMediatorZip = function(mediatorZip)
{
	this.mediatorZip = mediatorZip;
}

mediator.prototype.getMediatorTelephone = function()
{
	return this.mediatorTelephone;
}

mediator.prototype.setMediatorTelephone = function(mediatorTelephone)
{
	this.mediatorTelephone = mediatorTelephone;
}

mediator.prototype.getMediatorFax = function()
{
	return this.mediatorFax;
}

mediator.prototype.setMediatorFax = function(mediatorFax)
{
	this.mediatorFax = mediatorFax;
}

mediator.prototype.getMediatorProfile = function()
{
	return this.mediatorProfile;
}

mediator.prototype.setMediatorProfile = function(mediatorProfile)
{
	this.mediatorProfile = mediatorProfile;
}

mediator.prototype.getMediatorHourlyRate = function()
{
	return this.mediatorHourlyRate;
}

mediator.prototype.setMediatorHourlyRate = function(mediatorHourlyRate)
{
	this.mediatorHourlyRate = mediatorHourlyRate;
}

mediator.prototype.getMediatorAdminCharge = function()
{
	return this.mediatorAdminCharge;
}

mediator.prototype.setMediatorAdminCharge = function(mediatorAdminCharge)
{
	this.mediatorAdminCharge = mediatorAdminCharge;
}

mediator.prototype.getMediatorAdminChargeDetails = function()
{
	return this.mediatorAdminChargeDetails;
}

mediator.prototype.setMediatorAdminChargeDetails = function(mediatorAdminChargeDetails)
{
	this.mediatorAdminChargeDetails = mediatorAdminChargeDetails;
}

mediator.prototype.getMediatorLocationCharge = function()
{
	return this.mediatorLocationCharge;
}

mediator.prototype.setMediatorLocationCharge = function(mediatorLocationCharge)
{
	this.mediatorLocationCharge = mediatorLocationCharge;
}

mediator.prototype.getMediatorPackages = function()
{
	return this.mediatorPackages;
}

mediator.prototype.setMediatorPackages = function(mediatorPackages)
{
	this.mediatorPackages = mediatorPackages;
}

mediator.prototype.getMediatorSlidingScale = function()
{
	return this.mediatorSlidingScale;
}

mediator.prototype.setMediatorSlidingScale = function(mediatorSlidingScale)
{
	this.mediatorSlidingScale = mediatorSlidingScale;
}

mediator.prototype.getMediatorIncrement = function()
{
	return this.mediatorIncrement;
}

mediator.prototype.setMediatorIncrement = function(mediatorIncrement)
{
	this.mediatorIncrement = mediatorIncrement;
}

mediator.prototype.getMediatorConsultationPolicy = function()
{
	return this.mediatorConsultationPolicy;
}

mediator.prototype.setMediatorConsultationPolicy = function(mediatorConsultationPolicy)
{
	this.mediatorConsultationPolicy = mediatorConsultationPolicy;
}

mediator.prototype.getMediatorCancellationPolicy = function()
{
	return this.mediatorCancellationPolicy;
}

mediator.prototype.setMediatorCancellationPolicy = function(mediatorCancellationPolicy)
{
	this.mediatorCancellationPolicy = mediatorCancellationPolicy;
}

mediator.prototype.getMediatorTelephoneMediations = function()
{
	return this.mediatorTelephoneMediations;
}

mediator.prototype.setMediatorTelephoneMediations = function(mediatorTelephoneMediations)
{
	this.mediatorTelephoneMediations = mediatorTelephoneMediations;
}

mediator.prototype.getMediatorVideoConf = function()
{
	return this.mediatorVideoConf;
}

mediator.prototype.setMediatorVideoConf = function(mediatorVideoConf)
{
	this.mediatorVideoConf = mediatorVideoConf;
}

mediator.prototype.getMediatorCardPolicy = function()
{
	return this.mediatorCardPolicy;
}

mediator.prototype.setMediatorCardPolicy = function(mediatorCardPolicy)
{
	this.mediatorCardPolicy = mediatorCardPolicy;
}

mediator.prototype.getMediatorServiceLevel = function()
{
	return this.mediatorServiceLevel;
}

mediator.prototype.setMediatorServiceLevel = function(mediatorServiceLevel)
{
	this.mediatorServiceLevel = mediatorServiceLevel;
}

// Define the constructor for the franchisor custom object class. Note that franchisor object instances have just one property, which is an array called mediators. Note also that in utilitymediatorcode.js, I define a variable called NewResolution as an instance of the franchisor object class.
function franchisor()
{
	this.mediators = new Array(); //Initialize the mediators property, which will subsequently hold all the mediator class objects after I've called franchisor's prototype-level addMediator method.
}

// Method to add a mediator to the franchisor class
franchisor.prototype.addMediator = function(mediatorID, mediatorName, mediatorCred, mediatorLocale, mediatorLocaleLabel, mediatorLocations, mediatorEmail, mediatorEntityName, mediatorPrincipalStreet, mediatorPrincipalAddressOther, mediatorCity, mediatorState, mediatorZip, mediatorTelephone, mediatorFax, mediatorProfile, mediatorHourlyRate, mediatorAdminCharge, mediatorAdminChargeDetails, mediatorLocationCharge, mediatorPackages, mediatorSlidingScale, mediatorIncrement, mediatorConsultationPolicy, mediatorCancellationPolicy, mediatorTelephoneMediations, mediatorVideoConf, mediatorCardPolicy, mediatorServiceLevel)
{
	this.mediators[mediatorID] = new mediator(mediatorID, mediatorName, mediatorCred, mediatorLocale, mediatorLocaleLabel, mediatorLocations, mediatorEmail, mediatorEntityName, mediatorPrincipalStreet, mediatorPrincipalAddressOther, mediatorCity, mediatorState, mediatorZip, mediatorTelephone, mediatorFax, mediatorProfile, mediatorHourlyRate, mediatorAdminCharge, mediatorAdminChargeDetails, mediatorLocationCharge, mediatorPackages, mediatorSlidingScale, mediatorIncrement, mediatorConsultationPolicy, mediatorCancellationPolicy, mediatorTelephoneMediations, mediatorVideoConf, mediatorCardPolicy, mediatorServiceLevel);
}



// Create NewResolution, an instance of the franchisor custom object (class), to hold all the New Resolution mediators
var NewResolution = new franchisor();

// Set ("upload") the property values of each instance of the custom mediator object into the NewResolution array
for (i=0; i < mediatorID.length; i++)
{
	NewResolution.addMediator(mediatorID[i], mediatorName[i], mediatorCred[i], mediatorLocale[i], mediatorLocaleLabel[i], mediatorLocations[i], mediatorEmail[i], mediatorEntityName[i], mediatorPrincipalStreet[i], mediatorPrincipalAddressOther[i], mediatorCity[i], mediatorState[i], mediatorZip[i], mediatorTelephone[i], mediatorFax[i], mediatorProfile[i], mediatorHourlyRate[i], mediatorAdminCharge[i], mediatorAdminChargeDetails[i], mediatorLocationCharge[i], mediatorPackages[i], mediatorSlidingScale[i], mediatorIncrement[i], mediatorConsultationPolicy[i], mediatorCancellationPolicy[i], mediatorTelephoneMediations[i], mediatorVideoConf[i], mediatorCardPolicy[i], mediatorServiceLevel[i]);
}
					  
// Create new array uniqueLocale
// Do this by (i) scrubbing mediatorLocale (for undefineds), then (ii) sorting, then (iii) sifting (i.e. removing duplicates):
// Step 1: Delete any undefined elements in mediatorLocale that may be lurking
var omitUndefineds = new Array(); 
var uniqueLocale = new Array();
var index;
for (index in mediatorLocale) 
	{
	if(mediatorLocale[index]) omitUndefineds.push(mediatorLocale[index]);
	}
// Step 2: Sort the omitUndefineds array in preparation for easy removal of duplicates that may be necessary
omitUndefineds = omitUndefineds.sort(); 

// Step 3: Remove duplicates from the sorted mediatorLocale array by calling the RemoveDuplicates function
uniqueLocale = RemoveDuplicates(omitUndefineds);


// Create a 2D array mediatorIDsForUniqueLocale[row][col] whose columns contain the ID(s) of every mediator who has the same locale as the locale in array uniqueLocale[row]. Note that the array that contains all mediator locales is mediatorLocale.
var index, counter, ID;
var mediatorIDsForUniqueLocale = new Array(uniqueLocale.length);
// Dimension/set the number of rows in the mediatorIDsForUniqueLocale to equal the number of elements in the uniqueLocale array. Note: the number of columns (i.e. items) for each row of the 2D mediatorIDsForUniqueLocale is as yet unknown.
for (index in uniqueLocale)
	{
	mediatorIDsForUniqueLocale[index] = new Array();
	for (counter in mediatorLocale)
		{
		if (mediatorLocale[counter] == uniqueLocale[index])
			{
			ID = mediatorID[counter]; // Grab the ID for this mediatorLocale that matches an element in the uniqueLocale array
			mediatorIDsForUniqueLocale[index].push(ID);
			}
		}
	}


// Create new array uniqueState. This procedure is a sister to the procedure for creating the uniqueLocale array.
// Do this by (i) scrubbing mediatorState (for undefineds), then (ii) sorting, then (iii) sifting (i.e. removing duplicates):
// Step 1: Delete any undefined elements in mediatorState that may be lurking
var omitUndefineds = new Array(); 
var uniqueState = new Array();
var index;
for (index in mediatorState) 
	{
	if(mediatorState[index]) omitUndefineds.push(mediatorState[index]);
	}

// Step 2: Sort the omitUndefineds array in preparation for easy removal of duplicates that may be necessary
omitUndefineds = omitUndefineds.sort(); 

// Step 3: Remove duplicates from the sorted mediatorLocale array by calling the RemoveDuplicates function
uniqueState = RemoveDuplicates(omitUndefineds);


/* Match items in the uniqueState array (generated above) with items in the 2-D allStates "look-up table"  array (specified manually below) to create a 2-D 'states' array. Each row of the 'states' array contains a two-letter state abbreviation (which is used as the name of a div and as the value of an option in the 'statesdrop' drop-down menu) and the full name of the state (which is used as the visible text in the 'statesdrop' drop-down menu). The 'statesdrop' drop-down menu gets used on pages where state-specific content is provided e.g. d_support.shtml and d_property.shtml.
	I originally considered specifying the items in 'states' manually b/c the automatically generated array  may not include states that I want included (e.g. a mediator in Gary, IN, might legitimately demand inclusion of content for Illinois, but there may be no mediator at all in the IL locale and hence no inclusion of IL content in the 'statesdrop' drop-down menu). However, I decided that pros of automatic generation outweigh cons of manual specification. If I ever want to add content (e.g. IL) when there is no mediator in that locale, I can just manually write a line of JavaScript in the code below that uses the push method to append, say, "Illinois,"IL" to the 'states' array.
Ensure that states are ordered alphabetically by full name i.e. New Mexico (NM) comes before North Dakota (ND).
*/
var allStates = new Array();
var states = new Array();
var numberOfStates;
var count, index;
var int = 0;
// I thought carefully about the order of the nesting in the for loops below to ensure that "US"/"Your State Here" always appears at the end of the drop-down menus that are generated from the states array by function GenerateStatesDrop(), even after later-alphabet states like WY and UT.
for (index in allStates) 
	{ 
	allStates[index] = new Array(3); // Increase this array dimension to increase the number of columns in allStates[row][col] if adding more custom titles. The third column is for the custom title on the support.shtml page.
	}
for (index in states) 
	{ 
	states[index] = new Array(3); // Increase this array dimension to increase the number of columns in states[row][col] if adding more custom titles. The third column is for the custom title on the support.shtml page.
	}
allStates[0] = ["AL","Alabama",""];
allStates[1] = ["AK","Alaska",""];
allStates[2] = ["AZ","Arizona","Child Support and Spousal Maintenance"];
allStates[3] = ["AR","Arkansas",""];
allStates[4] = ["CA","California","Child and Spousal Support"];
allStates[5] = ["CO","Colorado",""];
allStates[6] = ["CT","Connecticut",""];
allStates[7] = ["DE","Delaware",""];
allStates[8] = ["DC","District of Columbia","Child Support and Alimony"];
allStates[9] = ["FL","Florida","Child Support and Alimony"];
allStates[10] = ["GA","Georgia","Child Support and Alimony"];
allStates[11] = ["HI","Hawaii",""];
allStates[12] = ["ID","Idaho",""];
allStates[13] = ["IL","Illinois","Child Support and Spousal Maintenance"];
allStates[14] = ["IN","Indiana","Child Support and Maintenance/Alimony"];
allStates[15] = ["IA","Iowa",""];
allStates[16] = ["KS","Kansas",""];
allStates[17] = ["KY","Kentucky",""];
allStates[18] = ["LA","Louisiana",""];
allStates[19] = ["ME","Maine",""];
allStates[20] = ["MD","Maryland","Child Support and Alimony"];
allStates[21] = ["MA","Massachusetts","Child Support and Alimony"];
allStates[22] = ["MI","Michigan","Child and Spousal Support"];
allStates[23] = ["MN","Minnesota",""];
allStates[24] = ["MS","Mississippi",""];
allStates[25] = ["MO","Missouri","Child Support and Maintenance/Alimony"];
allStates[26] = ["MT","Montana",""];
allStates[27] = ["NE","Nebraska",""];
allStates[28] = ["NV","Nevada",""];
allStates[29] = ["NH","New Hampshire",""];
allStates[30] = ["NJ","New Jersey","Child Support and Alimony/Maintenance"];
allStates[31] = ["NM","New Mexico","Child and Spousal Support"];
allStates[32] = ["NY","New York","Child Support and Maintenance"];
allStates[33] = ["NC","North Carolina","Child Support and Alimony"];
allStates[34] = ["ND","North Dakota",""];
allStates[35] = ["OH","Ohio","Child Support and Spousal Support"];
allStates[36] = ["OK","Oklahoma",""];
allStates[37] = ["OR","Oregon",""];
allStates[38] = ["PA","Pennsylvania","Child Support, Alimony, and Spousal Support"];
allStates[39] = ["RI","Rhode Island",""];
allStates[40] = ["SC","South Carolina",""];
allStates[41] = ["SD","South Dakota",""];
allStates[42] = ["TN","Tennessee",""];
allStates[43] = ["TX","Texas","Child Support and Maintenance"];
allStates[44] = ["UT","Utah",""];
allStates[45] = ["VT","Vermont",""];
allStates[46] = ["VA","Virginia","Child Support and Maintenance/Alimony"];
allStates[47] = ["WA","Washington","Child Support and Spousal Maintenance",""];
allStates[48] = ["WV","West Virginia",""];
allStates[49] = ["WI","Wisconsin",""];
allStates[50] = ["WY","Wyoming",""];
allStates[51] = ["US","Your State Here","Child and Spousal Support"]; // This is the special generic state of Anytown
for (count in allStates)
{
	for (index in uniqueState)
	{
		if (allStates[count][0] == uniqueState[index])
		{
			states[int] = allStates[count];
			int = int + 1;
			break;
		}
	}
}
numberOfStates = states.length;


// Create new 2-D array uniqueLocaleLabel[row][col]. The row elements in uniqueLocaleLabel[row][col] are the unique mediatorLocaleLabels with an underscore-plus-state appendage (e.g. "_TX"), analogous to the elements in the (1-D) array uniqueLocale[index]. The column element in uniqueLocaleLabel[row][col] for each array row item is the index value of the uniqueLocale[index] array that relates to the mediatorLocaleLabel.
// Example: suppose uniqueLocale[1] = ‘Dallas-Fort Worth-Arlington_TX’; suppose mediatorLocaleLabel[2] = ‘Fort Worth’ and mediatorLocaleLabel[8] = ‘Arlington’; then uniqueLocaleLabel[][] would be [‘Fort Worth_TX’][1] and uniqueLocaleLabel[][] would be [‘Arlington_TX’][1]
// Create uniqueLocaleLabel[row][col] by (i) scrubbing mediatorLocaleLabel (for undefineds), then (ii) sorting, then (iii) sifting (i.e. removing duplicates), then, for each row, (iv) identifying the ‘count’ for which uniqueLocaleLabel[row] matches mediatorLocaleLabel[count], then (v) identifying the index for which mediatorLocale[count] matches uniqueLocale[index], and finally (vi) assigning this index to the second (i.e. the column) dimension of uniqueLocaleLabel[row][col].
// Step 1: Delete any undefined elements in mediatorLocaleLabel that may be lurking
var omitUndefineds = new Array();
var omitUndefinedsAndDups = new Array();
var uniqueLocaleLabel = new Array();
var i, index, row, count;
for (index in mediatorLocaleLabel) 
	{
	if(mediatorLocaleLabel[index]) omitUndefineds.push(mediatorLocaleLabel[index] + '_' + mediatorState[index]);
	}
// Step 2: Sort the omitUndefineds array in preparation for easy removal of duplicates that may be necessary
omitUndefineds = omitUndefineds.sort(); 

// Step 3: Remove duplicates from the sorted omitUndefineds array by calling the RemoveDuplicates function ... then declare the array uniqueLocaleLabel (which has 2 columns) and dump the value of omitUndefinedsAndDups[x] into uniqueLocaleLabel[x][0].
omitUndefinedsAndDups = RemoveDuplicates(omitUndefineds);
var uniqueLocaleLabel = new Array(omitUndefinedsAndDups.length);
for (i in omitUndefinedsAndDups)
{
	uniqueLocaleLabel[i] = new Array(2);
	uniqueLocaleLabel[i][0] = omitUndefinedsAndDups[i];
}
// Steps 4, 5, and 6: Identify the ‘count’ for which uniqueLocaleLabel[row] matches mediatorLocaleLabel[count] (with the underscore-plus-state appended); identifying the index for which mediatorLocale[count] matches uniqueLocale[index]; and assign to the second (i.e. the column) dimension of uniqueLocaleLabel[row][col] this index
for (row=0; row < uniqueLocaleLabel.length; row++)
	{
	for (count in mediatorLocaleLabel)
		{
		if (uniqueLocaleLabel[row][0] == mediatorLocaleLabel[count] + '_' + mediatorState[count])
			{
			for (index in uniqueLocale)
				{
				if (mediatorLocale[count] == uniqueLocale[index]) 
					{
					uniqueLocaleLabel[row][1] = index;
					break;
					}
				}
			break;
			}
		}
	}


// Create a 2D array mediatorIDsForUniqueLocaleLabel[row][col] whose columns contain the ID(s) of every mediator who has the same locale label as the locale label in array uniqueLocaleLabel[row][0]. Note that the array that contains all mediator locale labels is mediatorLocaleLabel[].
var index, counter, ID;
var mediatorIDsForUniqueLocaleLabel = new Array(uniqueLocaleLabel.length);
// Dimension/set the number of rows in the mediatorIDsForUniqueLocaleLabel to equal the number of elements in the uniqueLocaleLabel array. Note: the number of columns (i.e. items) for each row of the 2D mediatorIDsForUniqueLocaleLabel is as yet unknown.
for (index in uniqueLocaleLabel)
	{
	mediatorIDsForUniqueLocaleLabel[index] = new Array();
	for (counter in mediatorLocaleLabel)
		{
		if (mediatorLocaleLabel[counter] == uniqueLocaleLabel[index][0])
			{
			ID = mediatorID[counter]; // Grab the ID for this mediatorLocaleLabel that matches an element in the uniqueLocaleLabel array
			mediatorIDsForUniqueLocaleLabel[index].push(ID);
			}
		}
	}


// Create 1-D array uniqueLocaleLabelState[row], whose rows mirror the row index of the uniqueLocaleLabel 2-D array. The value of each element in the uniqueLocaleLabelState array is the two-letter state abbreviation for that unique locale label. This array associates a state with each uniqueLocaleLabel. Note that multiple states may associate with each uniqueLocale (e.g. when the uniqueLocale is 'New York-Newark-White Plains_NY_NJ')
var uniqueLocaleLabelState = new Array();
var row, trunc;
for (row in uniqueLocaleLabel)
{
	trunc = uniqueLocaleLabel[row][0].split('_'); trunc = trunc[1];
	uniqueLocaleLabelState[row] = trunc;
}

// Create uniqueLocaleLabelAssocStub (a sister array to uniqueLocaleLabel) for use in creating .shtml file names in areas of the site such as the "our mediator" pages and the fees pages, such that each unique locale has its own page. So the page for mediator(s) in the "San Francisco-Oakland-Fremont_CA" locale would be mediatorSanFrancisco-Oakland-Fremont.shtml. uniqueLocaleLabelAssocStub will contain the value "SanFranciscoOaklandFremont i.e. the stub of the uniqueLocale value associated with the uniqueLocaleLabel. Stubs are created by removing any spaces and special characters (e.g. apostrophe in Coeur d'Alene_ID or tilde in Canon City_CO) and truncating that part of the string beyond the underscore. We create the uniqueLocaleLabelAssocStub array by scrolling through uniqueLocaleLabel[index][] for each row, then taking the stub of uniqueLocale[uniqueLocaleLabel[index][1]]
var uniqueLocaleLabelAssocStub = new Array();
var index, cleanstub;
for (index in uniqueLocaleLabel) 
	{
	cleanstub = uniqueLocale[uniqueLocaleLabel[index][1]];
	cleanstub = cleanstub.replace(/ /g, ""); // Remove spaces
	cleanstub = cleanstub.replace(/&ntilde;/g, "n"); // Replace n-tilde with a plain n (in Canon City_CO only)
	cleanstub = cleanstub.replace(/&#039;/g, ""); // Remove apostrophe special character (in Coeur d'Alene_ID only)
	cleanstub = cleanstub.split('_')[0]; // Take just the part of the string up to (excluding) the underscore.
	uniqueLocaleLabelAssocStub[index] = cleanstub;
//	alert('The value of my uniqueLocaleLabelAssocStub['+index+'] is: '+ uniqueLocaleLabelAssocStub[index]);
}


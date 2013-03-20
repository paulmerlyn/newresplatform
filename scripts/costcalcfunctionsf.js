// Functions for use with my mediation and litigation cost calculator

// var hourlyRate;
// var hourlyRate = 200; // Paul's hourly rate
// var attyHourlyRate = 350; // Rate for an average attorney
// var attyMSAfee = 520; // Fee charged by an attorney for preparing MSA - varies by state.
var litigDivorceLevel1 = 5000; // Cost of litigated divorce per spouse: friendly divorce
var litigDivorceLevel2 = 12000; // Cost of litigated divorce per spouse: low-conflict
var litigDivorceLevel3 = 24000; // Cost of litigated divorce per spouse: medium-conflict
var litigDivorceLevel4 = 44000; // Cost of litigated divorce per spouse: high-conflict
var litigDivorceLevel5 = 110000; // Cost of litigated divorce per spouse: fully contested


function AddCommas(nStr)
// This function converts a number from, say, 12000 to 12,000. See http://www.mredkj.com/javascript/nfbasic.html for more info. Note that I modified it slightly to ensure it only inserts commas for numbers that are at least 10000. I did this by ANDing the (nStr >= 10000) expression in the while loop.
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1) && (nStr >= 10000)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function SessionsCalc(numberSessions, SessionLength, hourlyRate)
{
	var numberSessions, SessionLength, hourlyRate, costOfSessions;
	costOfSessions = numberSessions * SessionLength * hourlyRate;
	return costOfSessions
}

function BasicHrsRateCalc(numberHours, rate)
{
	var numberHours, rate, cost;
	cost = numberHours * rate;
	return cost;
}

function TotalMedCostPerSpouseCalc()
{
	var totalMedCost=0;
	// The first component in the total cost is the cost of the mediation sessions 
	totalMedCost = 0.5*SessionsCalc(document.medcostcalc.NumberSessions.value, document.medcostcalc.SessionLength.value, document.medcostcalc.HourlyRate.value); 
	// Second component in the total cost is the cost of the mediator's admin time to prepare the agreement, etc. 
	totalMedCost += 0.5*BasicHrsRateCalc(document.medcostcalc.NumberMedAdminHrs.value, document.medcostcalc.HourlyRate1.value);
	// Third component is the (optional) attorney consultations, which are only added if the check box is checked
	if (document.medcostcalc.AttyConsult.checked) 
		{
		totalMedCost += BasicHrsRateCalc(document.medcostcalc.NumberAttyConsultHrs.value, document.medcostcalc.AttyConsultRate.value);
		}
	// Fourth component is the (optional) professional fees, which are only added if the check box is checked
	if (document.medcostcalc.MiscProf.checked) 
		{
		totalMedCost += 0.5*BasicHrsRateCalc(document.medcostcalc.NumberMiscProfHrs.value, document.medcostcalc.MiscProfRate.value);
		}
// Fifth component is the filing fees
	if (!isNaN(document.medcostcalc.FilingFee.value)) totalMedCost += 1*document.medcostcalc.FilingFee.value; // Only add the filing fee to the running total if its value is a number. Also note that the multiplication by '1' is key to preventing NaN errors i.e. it converts a string to a number!
// Sixth component is the preparation of the MSA by either an attorney (radio button index = 0) or a paralegal/LDA (radio button index = 1)
	if (document.medcostcalc.AttyMSA.checked) 
		{
		totalMedCost += 0.5*BasicHrsRateCalc(document.medcostcalc.NumberAttyMSAHrs.value, document.medcostcalc.AttyMSARate.value);
		}
	return totalMedCost;
}

function TotalMedCostBothSpousesCalc()
{
	var totalMedCost=0;
	// The first component in the total cost is the cost of the mediation sessions 
	totalMedCost = SessionsCalc(document.medcostcalc.NumberSessions.value, document.medcostcalc.SessionLength.value, document.medcostcalc.HourlyRate.value); 
	// Second component in the total cost is the cost of the mediator's admin time to prepare the agreement, etc. 
	totalMedCost += BasicHrsRateCalc(document.medcostcalc.NumberMedAdminHrs.value, document.medcostcalc.HourlyRate1.value);
	// Third component is the (optional) attorney consultations, which are only added if the check box is checked
	if (document.medcostcalc.AttyConsult.checked) 
		{
		totalMedCost += 2*BasicHrsRateCalc(document.medcostcalc.NumberAttyConsultHrs.value, document.medcostcalc.AttyConsultRate.value);
		}
	// Fourth component is the (optional) professional fees, which are only added if the check box is checked
	if (document.medcostcalc.MiscProf.checked) 
		{
		totalMedCost += BasicHrsRateCalc(document.medcostcalc.NumberMiscProfHrs.value, document.medcostcalc.MiscProfRate.value);
		}
	// Fifth component is the filing fees
	if (!isNaN(document.medcostcalc.FilingFee.value)) totalMedCost += 2*document.medcostcalc.FilingFee.value; // Only add the filing fee to the running total if its value is a number	
	// Sixth component is the preparation of the MSA by either an attorney (radio button index = 0) or a paralegal/LDA (radio button index = 1)
	if (document.medcostcalc.AttyMSA.checked) 
		{
		totalMedCost += BasicHrsRateCalc(document.medcostcalc.NumberAttyMSAHrs.value, document.medcostcalc.AttyMSARate.value);
		}
	return totalMedCost;
}

function UpdateMedSavings()
// This function calculates the savings when using mediation rather than litigation. It subtracts totalMedCost (whose value is determined dynamically by user selections in the table on d_costcalculator.shtml) from the five number literals litigDivorceLevel1, litigDivorceLevel2, ..., litigDivorceLevel5 (which correspond to estimates for the cost of divorce litigation in five various scenarios).
{
	document.getElementById('holding_pen13').innerHTML = AddCommas(litigDivorceLevel1 - TotalMedCostPerSpouseCalc());
	document.getElementById('holding_pen14').innerHTML = AddCommas(2*litigDivorceLevel1 - TotalMedCostBothSpousesCalc());  // Note the need to multiply litigDivorceLevel1 by 2 when populating the "both spouses" holding pen
	document.getElementById('holding_pen15').innerHTML = AddCommas(litigDivorceLevel2 - TotalMedCostPerSpouseCalc());
	document.getElementById('holding_pen16').innerHTML = AddCommas(2*litigDivorceLevel2 - TotalMedCostBothSpousesCalc());  // Note the need to multiply litigDivorceLevel1 by 2 when populating the "both spouses" holding pen
	document.getElementById('holding_pen17').innerHTML = AddCommas(litigDivorceLevel3 - TotalMedCostPerSpouseCalc());
	document.getElementById('holding_pen18').innerHTML = AddCommas(2*litigDivorceLevel3 - TotalMedCostBothSpousesCalc());  // Note the need to multiply litigDivorceLevel1 by 2 when populating the "both spouses" holding pen
	document.getElementById('holding_pen19').innerHTML = AddCommas(litigDivorceLevel4 - TotalMedCostPerSpouseCalc());
	document.getElementById('holding_pen20').innerHTML = AddCommas(2*litigDivorceLevel4 - TotalMedCostBothSpousesCalc());  // Note the need to multiply litigDivorceLevel1 by 2 when populating the "both spouses" holding pen
	document.getElementById('holding_pen21').innerHTML = AddCommas(litigDivorceLevel5 - TotalMedCostPerSpouseCalc());
	document.getElementById('holding_pen22').innerHTML = AddCommas(2*litigDivorceLevel5 - TotalMedCostBothSpousesCalc());  // Note the need to multiply litigDivorceLevel1 by 2 when populating the "both spouses" holding pen
}

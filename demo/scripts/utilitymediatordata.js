var mediatorID = new Array();
mediatorID[0] = 2;
mediatorID[1] = 3;
mediatorID[2] = 4;
mediatorID[3] = 5;
mediatorID[4] = 6;
mediatorID[5] = 7;
mediatorID[6] = 8;
mediatorID[7] = 9;
mediatorID[8] = 47;
mediatorID[9] = 58;

var mediatorName = new Array(mediatorID.length);
mediatorName[0] = "Bob J. Mahoney";
mediatorName[1] = "Ted Jones";
mediatorName[2] = "Paul Merlyn";
mediatorName[3] = "Elaine Yee";
mediatorName[4] = "Janet Norman";
mediatorName[5] = "Sarah Myers";
mediatorName[6] = "Your Name";
mediatorName[7] = "Eliza Riley";
mediatorName[8] = "Charlotte Norman";
mediatorName[9] = "Cary Lawless";

var mediatorCred = new Array(mediatorID.length);
mediatorCred[0] = "M.A., L.C.S.W.";
mediatorCred[1] = "J.D.";
mediatorCred[2] = "M.A.";
mediatorCred[3] = "Psy.D.";
mediatorCred[4] = "Ph.D.";
mediatorCred[5] = "Esq.";
mediatorCred[6] = "";
mediatorCred[7] = "Esq.";
mediatorCred[8] = "M.S.W., J.D.";
mediatorCred[9] = "Psy.D.";

var mediatorLocale = new Array(mediatorID.length);
mediatorLocale[0] = "Miami-Fort Lauderdale-Pompano Beach_FL";
mediatorLocale[1] = "Dallas-Fort Worth-Arlington_TX";
mediatorLocale[2] = "San Francisco-Oakland-Fremont_CA";
mediatorLocale[3] = "Phoenix-Mesa-Glendale_AZ";
mediatorLocale[4] = "Albuquerque_NM";
mediatorLocale[5] = "Seattle-Tacoma-Bellevue_WA";
mediatorLocale[6] = "Your Locale Here";
mediatorLocale[7] = "Dallas-Fort Worth-Arlington_TX";
mediatorLocale[8] = "New York-White Plains-Wayne_NY-NJ";
mediatorLocale[9] = "Coeur d&#039;Alene_ID";

var mediatorLocaleLabel = new Array(mediatorID.length);
mediatorLocaleLabel[0] = "Miami";
mediatorLocaleLabel[1] = "Fort Worth";
mediatorLocaleLabel[2] = "San Francisco/East Bay";
mediatorLocaleLabel[3] = "Scottsdale";
mediatorLocaleLabel[4] = "Albuquerque";
mediatorLocaleLabel[5] = "Seattle";
mediatorLocaleLabel[6] = "Your Locale Here";
mediatorLocaleLabel[7] = "Dallas";
mediatorLocaleLabel[8] = "New York City";
mediatorLocaleLabel[9] = "Coeur d&#039;Alene";

var mediatorLocations = new Array(mediatorID.length);
var index;
for (index in mediatorID)
{
mediatorLocations[index] = new Array()
}
mediatorLocations[0] = new Array("South Beach","Little Havana","Florida City","Coconut Grove","Miami Shores","Pine Crest");
mediatorLocations[1] = new Array("Fort Worth");
mediatorLocations[2] = new Array("San Francisco","Palo Alto","San Ramon","Walnut Creek","San Mateo");
mediatorLocations[3] = new Array("Scottsdale","Phoenix");
mediatorLocations[4] = new Array("Bernalillo","Rio Rancho");
mediatorLocations[5] = new Array("Bellevue","Redmond","Everett");
mediatorLocations[6] = new Array("Main Location","Satellite Location 1","Satellite Location 2");
mediatorLocations[7] = new Array("Dallas","Fort Worth","Irving");
mediatorLocations[8] = new Array("Upper East Side","Astoria");
mediatorLocations[9] = new Array("Coeur d&#039;Alene");

var mediatorEmail = new Array(mediatorID.length);
mediatorEmail[0] = "bob@newresolutionmediation.com";
mediatorEmail[1] = "ted@joneslaw.com";
mediatorEmail[2] = "paul@newresolutionmediation.com";
mediatorEmail[3] = "elaine@newresolutionmediation.com";
mediatorEmail[4] = "janetnorman@gmail.com";
mediatorEmail[5] = "sarahm@myerslawbellevue.net";
mediatorEmail[6] = "you@newresolutionmediation.com";
mediatorEmail[7] = "eliza@newresolutionmediation.com";
mediatorEmail[8] = "cnorman@newresolutionmediation.com";
mediatorEmail[9] = "cary12@hotmail.com";

var mediatorEntityName = new Array(mediatorID.length);
mediatorEntityName[0] = "";
mediatorEntityName[1] = "Mediation and Law Office of Ted Jones";
mediatorEntityName[2] = "";
mediatorEntityName[3] = "";
mediatorEntityName[4] = "";
mediatorEntityName[5] = "";
mediatorEntityName[6] = "";
mediatorEntityName[7] = "";
mediatorEntityName[8] = "";
mediatorEntityName[9] = "";

var mediatorPrincipalStreet = new Array(mediatorID.length);
mediatorPrincipalStreet[0] = "8 S. Biscayne Blvd.";
mediatorPrincipalStreet[1] = "110 Main Street";
mediatorPrincipalStreet[2] = "844 California Street";
mediatorPrincipalStreet[3] = "8010 East Cactus Road";
mediatorPrincipalStreet[4] = "209 S. Camino Del Pueblo";
mediatorPrincipalStreet[5] = "15500 SE 30th Place";
mediatorPrincipalStreet[6] = "377 Bridge Street";
mediatorPrincipalStreet[7] = "1401 Elm Street, Suite 1620";
mediatorPrincipalStreet[8] = "584 Lexington Avenue";
mediatorPrincipalStreet[9] = "";

var mediatorPrincipalAddressOther = new Array(mediatorID.length);
mediatorPrincipalAddressOther[0] = "Suite 270";
mediatorPrincipalAddressOther[1] = "";
mediatorPrincipalAddressOther[2] = "";
mediatorPrincipalAddressOther[3] = "";
mediatorPrincipalAddressOther[4] = "";
mediatorPrincipalAddressOther[5] = "";
mediatorPrincipalAddressOther[6] = "Suite 102";
mediatorPrincipalAddressOther[7] = "";
mediatorPrincipalAddressOther[8] = "";
mediatorPrincipalAddressOther[9] = "";

var mediatorCity = new Array(mediatorID.length);
mediatorCity[0] = "Miami";
mediatorCity[1] = "Fort Worth";
mediatorCity[2] = "San Francisco";
mediatorCity[3] = "Scottsdale";
mediatorCity[4] = "Bernalillo";
mediatorCity[5] = "Bellevue";
mediatorCity[6] = "Anytown";
mediatorCity[7] = "Dallas";
mediatorCity[8] = "New York";
mediatorCity[9] = "Coeur d'Alene";

var mediatorState = new Array(mediatorID.length);
mediatorState[0] = "FL";
mediatorState[1] = "TX";
mediatorState[2] = "CA";
mediatorState[3] = "AZ";
mediatorState[4] = "NM";
mediatorState[5] = "WA";
mediatorState[6] = "US";
mediatorState[7] = "TX";
mediatorState[8] = "NY";
mediatorState[9] = "ID";

var mediatorZip = new Array(mediatorID.length);
mediatorZip[0] = "33131";
mediatorZip[1] = "76102";
mediatorZip[2] = "94108";
mediatorZip[3] = "85260";
mediatorZip[4] = "87004";
mediatorZip[5] = "98007-6347";
mediatorZip[6] = "10038";
mediatorZip[7] = "75202";
mediatorZip[8] = "10154";
mediatorZip[9] = "78754";

var mediatorTelephone = new Array(mediatorID.length);
mediatorTelephone[0] = "305.297.7903";
mediatorTelephone[1] = "817.338.7790";
mediatorTelephone[2] = "415.378.7003";
mediatorTelephone[3] = "480.368.3421";
mediatorTelephone[4] = "505.395.2574";
mediatorTelephone[5] = "425.644.3418";
mediatorTelephone[6] = "[your number here]";
mediatorTelephone[7] = "214.646.4311";
mediatorTelephone[8] = "212.355.6845";
mediatorTelephone[9] = "565.787.8786";

var mediatorFax = new Array(mediatorID.length);
mediatorFax[0] = "305.297.6487";
mediatorFax[1] = "817.338.7630";
mediatorFax[2] = "415.366.3005";
mediatorFax[3] = "480.368.3422";
mediatorFax[4] = "505.395.2755";
mediatorFax[5] = "425.644.3419";
mediatorFax[6] = "";
mediatorFax[7] = "214.646.4317";
mediatorFax[8] = "212.355.6846";
mediatorFax[9] = "";

var mediatorProfile = new Array(mediatorID.length);
mediatorProfile[0] = "Sunt in culpa lorem ipsum dolor sit amet, consectetur adipisicing elit. In reprehenderit in voluptate velit esse cillum dolore duis aute irure dolor. Excepteur sint occaecat qui officia deserunt sunt in culpa. Ut enim ad minim veniam, in reprehenderit in voluptate ut aliquip ex ea commodo consequat. Velit esse cillum dolore duis aute irure dolor excepteur sint occaecat. Eu fugiat nulla pariatur. Ut enim ad minim veniam, cupidatat non proident, lorem ipsum dolor sit amet. Sed do eiusmod tempor incididunt consectetur adipisicing elit, ut labore et dolore magna aliqua. Mollit anim id est laborum. Cupidatat non proident, in reprehenderit in voluptate sunt in culpa. Eu fugiat nulla pariatur. Quis nostrud exercitation lorem ipsum dolor sit amet. Eu fugiat nulla pariatur. Excepteur sint occaecat duis aute irure dolor ut aliquip ex ea commodo consequat.";
mediatorProfile[1] = "Sunt in culpa lorem ipsum dolor sit amet, consectetur adipisicing elit. In reprehenderit in voluptate velit esse cillum dolore duis aute irure dolor. Excepteur sint occaecat qui officia deserunt sunt in culpa. Ut enim ad minim veniam, in reprehenderit in voluptate ut aliquip ex ea commodo consequat. Velit esse cillum dolore duis aute irure dolor excepteur sint occaecat. Eu fugiat nulla pariatur. Ut enim ad minim veniam, cupidatat non proident, lorem ipsum dolor sit amet. Sed do eiusmod tempor incididunt consectetur adipisicing elit, ut labore et dolore magna aliqua. Mollit anim id est laborum. Cupidatat non proident, in reprehenderit in voluptate sunt in culpa. Eu fugiat nulla pariatur. Quis nostrud exercitation lorem ipsum dolor sit amet. Eu fugiat nulla pariatur. Excepteur sint occaecat duis aute irure dolor ut aliquip ex ea commodo consequat.";
mediatorProfile[2] = "Paul Merlyn completed a graduate degree in International Relations with focus on dispute resolution from Lancaster University in 1993. He has also completed advanced training in parent-teen mediation, custody mediation, and victim-offender mediation and has mediated more than 200 divorce, family, and relational disputes. In addition to his work with New Resolution, he is a mediation coach for Foothill De Anza College&#039;s mediation certification program, a meetings facilitator for the City of San Francisco and the City of San Mateo, and a former director of the Association for Dispute Resolution of Northern California. He is also an approved mediator for civil disputes by the Superior Court of San Francisco.";
mediatorProfile[3] = "Sunt in culpa lorem ipsum dolor sit amet, consectetur adipisicing elit. In reprehenderit in voluptate velit esse cillum dolore duis aute irure dolor. Excepteur sint occaecat qui officia deserunt sunt in culpa. Ut enim ad minim veniam, in reprehenderit in voluptate ut aliquip ex ea commodo consequat. Velit esse cillum dolore duis aute irure dolor excepteur sint occaecat. Eu fugiat nulla pariatur. Ut enim ad minim veniam, cupidatat non proident, lorem ipsum dolor sit amet. Sed do eiusmod tempor incididunt consectetur adipisicing elit, ut labore et dolore magna aliqua. Mollit anim id est laborum. Cupidatat non proident, in reprehenderit in voluptate sunt in culpa. Eu fugiat nulla pariatur. Quis nostrud exercitation lorem ipsum dolor sit amet. Eu fugiat nulla pariatur. Excepteur sint occaecat duis aute irure dolor ut aliquip ex ea commodo consequat.";
mediatorProfile[4] = "Sunt in culpa lorem ipsum dolor sit amet, consectetur adipisicing elit. In reprehenderit in voluptate velit esse cillum dolore duis aute irure dolor. Excepteur sint occaecat qui officia deserunt sunt in culpa. Ut enim ad minim veniam, in reprehenderit in voluptate ut aliquip ex ea commodo consequat. Velit esse cillum dolore duis aute irure dolor excepteur sint occaecat. Eu fugiat nulla pariatur. Ut enim ad minim veniam, cupidatat non proident, lorem ipsum dolor sit amet. Sed do eiusmod tempor incididunt consectetur adipisicing elit, ut labore et dolore magna aliqua. Mollit anim id est laborum. Cupidatat non proident, in reprehenderit in voluptate sunt in culpa. Eu fugiat nulla pariatur. Quis nostrud exercitation lorem ipsum dolor sit amet. Eu fugiat nulla pariatur. Excepteur sint occaecat duis aute irure dolor ut aliquip ex ea commodo consequat.";
mediatorProfile[5] = "Sunt in culpa lorem ipsum dolor sit amet, consectetur adipisicing elit. In reprehenderit in voluptate velit esse cillum dolore duis aute irure dolor. Excepteur sint occaecat qui officia deserunt sunt in culpa. Ut enim ad minim veniam, in reprehenderit in voluptate ut aliquip ex ea commodo consequat. Velit esse cillum dolore duis aute irure dolor excepteur sint occaecat. Eu fugiat nulla pariatur. Ut enim ad minim veniam, cupidatat non proident, lorem ipsum dolor sit amet. Sed do eiusmod tempor incididunt consectetur adipisicing elit, ut labore et dolore magna aliqua. Mollit anim id est laborum. Cupidatat non proident, in reprehenderit in voluptate sunt in culpa. Eu fugiat nulla pariatur. Quis nostrud exercitation lorem ipsum dolor sit amet. Eu fugiat nulla pariatur. Excepteur sint occaecat duis aute irure dolor ut aliquip ex ea commodo consequat.";
mediatorProfile[6] = "Sunt in culpa lorem ipsum dolor sit amet, consectetur adipisicing elit. In reprehenderit in voluptate velit esse cillum dolore duis aute irure dolor. Excepteur sint occaecat qui officia deserunt sunt in culpa. Ut enim ad minim veniam, in reprehenderit in voluptate ut aliquip ex ea commodo consequat. Velit esse cillum dolore duis aute irure dolor excepteur sint occaecat. Eu fugiat nulla pariatur. Ut enim ad minim veniam, cupidatat non proident, lorem ipsum dolor sit amet. Sed do eiusmod tempor incididunt consectetur adipisicing elit, ut labore et dolore magna aliqua. Mollit anim id est laborum. Cupidatat non proident, in reprehenderit in voluptate sunt in culpa. Eu fugiat nulla pariatur. Quis nostrud exercitation lorem ipsum dolor sit amet. Eu fugiat nulla pariatur. Excepteur sint occaecat duis aute irure dolor ut aliquip ex ea commodo consequat.";
mediatorProfile[7] = "Sunt in culpa lorem ipsum dolor sit amet, consectetur adipisicing elit. In reprehenderit in voluptate velit esse cillum dolore duis aute irure dolor. Excepteur sint occaecat qui officia deserunt sunt in culpa. Ut enim ad minim veniam, in reprehenderit in voluptate ut aliquip ex ea commodo consequat. Velit esse cillum dolore duis aute irure dolor excepteur sint occaecat. Eu fugiat nulla pariatur. Ut enim ad minim veniam, cupidatat non proident, lorem ipsum dolor sit amet. Sed do eiusmod tempor incididunt consectetur adipisicing elit, ut labore et dolore magna aliqua. Mollit anim id est laborum. Cupidatat non proident, in reprehenderit in voluptate sunt in culpa. Eu fugiat nulla pariatur. Quis nostrud exercitation lorem ipsum dolor sit amet. Eu fugiat nulla pariatur. Excepteur sint occaecat duis aute irure dolor ut aliquip ex ea commodo consequat.";
mediatorProfile[8] = "Sunt in culpa lorem ipsum dolor sit amet, consectetur adipisicing elit. In reprehenderit in voluptate velit esse cillum dolore duis aute irure dolor. Excepteur sint occaecat qui officia deserunt sunt in culpa. Ut enim ad minim veniam, in reprehenderit in voluptate ut aliquip ex ea commodo consequat. Velit esse cillum dolore duis aute irure dolor excepteur sint occaecat. Eu fugiat nulla pariatur. Ut enim ad minim veniam, cupidatat non proident, lorem ipsum dolor sit amet. Sed do eiusmod tempor incididunt consectetur adipisicing elit, ut labore et dolore magna aliqua. Mollit anim id est laborum. Cupidatat non proident, in reprehenderit in voluptate sunt in culpa. Eu fugiat nulla pariatur. Quis nostrud exercitation lorem ipsum dolor sit amet. Eu fugiat nulla pariatur. Excepteur sint occaecat duis aute irure dolor ut aliquip ex ea commodo consequat.";
mediatorProfile[9] = "Cary has mediated ... She is ...Cary has mediated ... She is ...Cary has mediated ... She is ...Cary has mediated ... She is ...Cary has mediated ... She is ...Cary has mediated ... She is ...Cary has mediated ... She is ...Cary has mediated ... She is ...Cary has mediated ... She is ...Cary has mediated ... She is ...Cary has mediated ... She is ...Cary has mediated ... She is ...Cary has mediated ... She is ...Cary has mediated ... She is ...Cary has mediated ... She is ...Cary has mediated ... She is ...";

var mediatorHourlyRate = new Array(mediatorID.length);
var index;
for (index in mediatorID)
{
mediatorHourlyRate[index] = new Array(2)
}
mediatorHourlyRate[0] = new Array(true,150);
mediatorHourlyRate[1] = new Array(true,185);
mediatorHourlyRate[2] = new Array(true,225);
mediatorHourlyRate[3] = new Array(true,320);
mediatorHourlyRate[4] = new Array(true,200);
mediatorHourlyRate[5] = new Array(true,275);
mediatorHourlyRate[6] = new Array(true,220);
mediatorHourlyRate[7] = new Array(false,'');
mediatorHourlyRate[8] = new Array(true,300);
mediatorHourlyRate[9] = new Array(false,'');

var mediatorAdminCharge = new Array(mediatorID.length);
mediatorAdminCharge[0] = true;
mediatorAdminCharge[1] = true;
mediatorAdminCharge[2] = false;
mediatorAdminCharge[3] = false;
mediatorAdminCharge[4] = true;
mediatorAdminCharge[5] = true;
mediatorAdminCharge[6] = true;
mediatorAdminCharge[7] = true;
mediatorAdminCharge[8] = false;
mediatorAdminCharge[9] = false;

var mediatorAdminChargeDetails = new Array(mediatorID.length);
var index;
for (index in mediatorID)
{
mediatorAdminChargeDetails[index] = new Array(2)
}
mediatorAdminChargeDetails[0] = new Array(false,'');
mediatorAdminChargeDetails[1] = new Array(false,'');
mediatorAdminChargeDetails[2] = new Array(true,100);
mediatorAdminChargeDetails[3] = new Array(false,'');
mediatorAdminChargeDetails[4] = new Array(true,100);
mediatorAdminChargeDetails[5] = new Array(true,200);
mediatorAdminChargeDetails[6] = new Array(false,'');
mediatorAdminChargeDetails[7] = new Array(true,150);
mediatorAdminChargeDetails[8] = new Array(false,'');
mediatorAdminChargeDetails[9] = new Array(false,'');

var mediatorLocationCharge = new Array(mediatorID.length);
var index;
for (index in mediatorID)
{
mediatorLocationCharge[index] = new Array(3)
}
mediatorLocationCharge[0] = new Array(true,80,120);
mediatorLocationCharge[1] = new Array(true,100,130);
mediatorLocationCharge[2] = new Array(false,"","");
mediatorLocationCharge[3] = new Array(false,"","");
mediatorLocationCharge[4] = new Array(true,90,90);
mediatorLocationCharge[5] = new Array(true,95,95);
mediatorLocationCharge[6] = new Array(true,90,120);
mediatorLocationCharge[7] = new Array(true,100,140);
mediatorLocationCharge[8] = new Array(false,"","");
mediatorLocationCharge[9] = new Array(false,"","");

var mediatorPackages = new Array(mediatorID.length);
var index;
for (index in mediatorID)
{
mediatorPackages[index] = new Array(9)
}
mediatorPackages[0] = new Array(true,2,610,4,1080,6,1520,2,15);
mediatorPackages[1] = new Array(true,3,1150,4,1400,5,1650,2,30);
mediatorPackages[2] = new Array(true,3,1200,5,1900,"","",2,"");
mediatorPackages[3] = new Array(true,3,1630,4,2050,"","",2,"");
mediatorPackages[4] = new Array(true,3,1200,5,1850,"","",2,20);
mediatorPackages[5] = new Array(false,"","","","","","","","");
mediatorPackages[6] = new Array(true,3,1120,4,1400,5,1650,2,"");
mediatorPackages[7] = new Array(false,"","","","","","","","");
mediatorPackages[8] = new Array(true,3,1215,6,2160,"","",1,30);
mediatorPackages[9] = new Array(false,"","","","","","","","");

var mediatorSlidingScale = new Array(mediatorID.length);
mediatorSlidingScale[0] = false;
mediatorSlidingScale[1] = true;
mediatorSlidingScale[2] = false;
mediatorSlidingScale[3] = false;
mediatorSlidingScale[4] = false;
mediatorSlidingScale[5] = true;
mediatorSlidingScale[6] = true;
mediatorSlidingScale[7] = false;
mediatorSlidingScale[8] = false;
mediatorSlidingScale[9] = false;

var mediatorIncrement = new Array(mediatorID.length);
mediatorIncrement[0] = "10";
mediatorIncrement[1] = "15";
mediatorIncrement[2] = "10";
mediatorIncrement[3] = "15";
mediatorIncrement[4] = "10";
mediatorIncrement[5] = "6";
mediatorIncrement[6] = "10";
mediatorIncrement[7] = "6";
mediatorIncrement[8] = "6";
mediatorIncrement[9] = "6";

var mediatorConsultationPolicy = new Array(mediatorID.length);
var index;
for (index in mediatorID)
{
mediatorConsultationPolicy[index] = new Array(5)
}
mediatorConsultationPolicy[0] = new Array(true,false,true,false,"");
mediatorConsultationPolicy[1] = new Array(false,false,false,true,100);
mediatorConsultationPolicy[2] = new Array(true,false,false,false,"");
mediatorConsultationPolicy[3] = new Array(true,true,false,false,"");
mediatorConsultationPolicy[4] = new Array(true,true,true,false,"");
mediatorConsultationPolicy[5] = new Array(true,false,true,true,50);
mediatorConsultationPolicy[6] = new Array(true,false,false,false,"");
mediatorConsultationPolicy[7] = new Array(false,false,true,false,"");
mediatorConsultationPolicy[8] = new Array(true,true,false,false,"");
mediatorConsultationPolicy[9] = new Array(false,false,false,false,"");

var mediatorCancellationPolicy = new Array(mediatorID.length);
var index;
for (index in mediatorID)
{
mediatorCancellationPolicy[index] = new Array(3)
}
mediatorCancellationPolicy[0] = new Array("4","business days","250");
mediatorCancellationPolicy[1] = new Array("3","business days","150");
mediatorCancellationPolicy[2] = new Array("3","business days","225");
mediatorCancellationPolicy[3] = new Array("72","hours","125");
mediatorCancellationPolicy[4] = new Array("72","hours","100");
mediatorCancellationPolicy[5] = new Array("48","hours","150");
mediatorCancellationPolicy[6] = new Array("48","hours","125");
mediatorCancellationPolicy[7] = new Array("3","business days","120");
mediatorCancellationPolicy[8] = new Array("5","business days","300");
mediatorCancellationPolicy[9] = new Array("4","business days","300");

var mediatorTelephoneMediations = new Array(mediatorID.length);
mediatorTelephoneMediations[0] = true;
mediatorTelephoneMediations[1] = true;
mediatorTelephoneMediations[2] = true;
mediatorTelephoneMediations[3] = true;
mediatorTelephoneMediations[4] = true;
mediatorTelephoneMediations[5] = true;
mediatorTelephoneMediations[6] = true;
mediatorTelephoneMediations[7] = true;
mediatorTelephoneMediations[8] = true;
mediatorTelephoneMediations[9] = true;

var mediatorVideoConf = new Array(mediatorID.length);
mediatorVideoConf[0] = true;
mediatorVideoConf[1] = false;
mediatorVideoConf[2] = true;
mediatorVideoConf[3] = true;
mediatorVideoConf[4] = true;
mediatorVideoConf[5] = true;
mediatorVideoConf[6] = true;
mediatorVideoConf[7] = true;
mediatorVideoConf[8] = true;
mediatorVideoConf[9] = true;

var mediatorCardPolicy = new Array(mediatorID.length);
var index;
for (index in mediatorID)
{
mediatorCardPolicy[index] = new Array(3)
}
mediatorCardPolicy[0] = new Array(true,false,"");
mediatorCardPolicy[1] = new Array(true,true,"1.5");
mediatorCardPolicy[2] = new Array(true,false,"");
mediatorCardPolicy[3] = new Array(false,false,"");
mediatorCardPolicy[4] = new Array(true,true,"2");
mediatorCardPolicy[5] = new Array(true,true,"3");
mediatorCardPolicy[6] = new Array(true,true,"0.5");
mediatorCardPolicy[7] = new Array(true,true,"2");
mediatorCardPolicy[8] = new Array(true,true,"2");
mediatorCardPolicy[9] = new Array(true,false,"");

var mediatorServiceLevel = new Array(mediatorID.length);
mediatorServiceLevel[0] = false;
mediatorServiceLevel[1] = true;
mediatorServiceLevel[2] = false;
mediatorServiceLevel[3] = false;
mediatorServiceLevel[4] = false;
mediatorServiceLevel[5] = true;
mediatorServiceLevel[6] = false;
mediatorServiceLevel[7] = true;
mediatorServiceLevel[8] = false;
mediatorServiceLevel[9] = false;

var mediatorID = new Array();
mediatorID[0] = 100;
mediatorID[1] = 102;
mediatorID[2] = 101;
mediatorID[3] = 200;
mediatorID[4] = 204;

var mediatorName = new Array(mediatorID.length);
mediatorName[0] = "Paul R. Merlyn";
mediatorName[1] = "Paul R. Merlyn";
mediatorName[2] = "Paul R. Merlyn";
mediatorName[3] = "Donna G. Lindsey";
mediatorName[4] = "William A. Lambos";

var mediatorCred = new Array(mediatorID.length);
mediatorCred[0] = "M.A.";
mediatorCred[1] = "MA";
mediatorCred[2] = "M.A.";
mediatorCred[3] = "MA - Dispute Resolution";
mediatorCred[4] = "Ph.D.";

var mediatorLocale = new Array(mediatorID.length);
mediatorLocale[0] = "San Francisco-Oakland-Fremont_CA";
mediatorLocale[1] = "Walnut Creek-San Ramon-East Bay_CA";
mediatorLocale[2] = "San Jose-Sunnyvale-Santa Clara_CA";
mediatorLocale[3] = "Dallas-Fort Worth-Arlington_TX";
mediatorLocale[4] = "Tampa-St. Petersburg-Clearwater_FL";

var mediatorLocaleLabel = new Array(mediatorID.length);
mediatorLocaleLabel[0] = "San Francisco/North Bay";
mediatorLocaleLabel[1] = "Walnut Creek/San Ramon";
mediatorLocaleLabel[2] = "San Mateo-Palo Alto-San Jose";
mediatorLocaleLabel[3] = "Dallas-Fort Worth-McKinney";
mediatorLocaleLabel[4] = "Greater Tampa Bay";

var mediatorLocations = new Array(mediatorID.length);
var index;
for (index in mediatorID)
{
mediatorLocations[index] = new Array()
}
mediatorLocations[0] = new Array("San Francisco","San Rafael","Petaluma");
mediatorLocations[1] = new Array("Walnut Creek","San Ramon","Pleasanton","Berkeley","Oakland","Newark/Fremont");
mediatorLocations[2] = new Array("San Mateo","Palo Alto","San Jose","Santa Clara","Mountain View","San Bruno","Campbell");
mediatorLocations[3] = new Array("Dallas","Fort Worth","Plano","McKinney","Allen","Richardson","Garland","Irving","Grapevine","Arlington");
mediatorLocations[4] = new Array("Tampa (Westshore Area)","Odessa (North of Oldsmar)");

var mediatorEmail = new Array(mediatorID.length);
mediatorEmail[0] = "paul@newresolutionmediation.com";
mediatorEmail[1] = "paul@newresolutionmediation.com";
mediatorEmail[2] = "paul@newresolutionmediation.com";
mediatorEmail[3] = "donna@newresolutionmediation.com";
mediatorEmail[4] = "walambos@mac.com";

var mediatorEntityName = new Array(mediatorID.length);
mediatorEntityName[0] = "";
mediatorEntityName[1] = "";
mediatorEntityName[2] = "";
mediatorEntityName[3] = "Lindsey Mediations";
mediatorEntityName[4] = "William Lambos";

var mediatorPrincipalStreet = new Array(mediatorID.length);
mediatorPrincipalStreet[0] = "844 California Street";
mediatorPrincipalStreet[1] = "2010 Crow Canyon Pl., Suite 100";
mediatorPrincipalStreet[2] = "1840 Gateway Drive";
mediatorPrincipalStreet[3] = "352 Rio Bravo";
mediatorPrincipalStreet[4] = "4890 W. Kennedy Blvd.";

var mediatorPrincipalAddressOther = new Array(mediatorID.length);
mediatorPrincipalAddressOther[0] = "";
mediatorPrincipalAddressOther[1] = "";
mediatorPrincipalAddressOther[2] = "Suite 200";
mediatorPrincipalAddressOther[3] = "";
mediatorPrincipalAddressOther[4] = "Suite 295";

var mediatorCity = new Array(mediatorID.length);
mediatorCity[0] = "San Francisco";
mediatorCity[1] = "San Ramon";
mediatorCity[2] = "San Mateo";
mediatorCity[3] = "Fairview";
mediatorCity[4] = "Tampa";

var mediatorState = new Array(mediatorID.length);
mediatorState[0] = "CA";
mediatorState[1] = "CA";
mediatorState[2] = "CA";
mediatorState[3] = "TX";
mediatorState[4] = "FL";

var mediatorZip = new Array(mediatorID.length);
mediatorZip[0] = "94108";
mediatorZip[1] = "94583";
mediatorZip[2] = "94404";
mediatorZip[3] = "75069";
mediatorZip[4] = "33609";

var mediatorTelephone = new Array(mediatorID.length);
mediatorTelephone[0] = "415.378.7003";
mediatorTelephone[1] = "925.235.3062";
mediatorTelephone[2] = "415.378.7003";
mediatorTelephone[3] = "972.439.0227";
mediatorTelephone[4] = "813.920.6136";

var mediatorFax = new Array(mediatorID.length);
mediatorFax[0] = "415.366.3005";
mediatorFax[1] = "";
mediatorFax[2] = "";
mediatorFax[3] = "972.432.6664";
mediatorFax[4] = "206.212.7900";

var mediatorProfile = new Array(mediatorID.length);
mediatorProfile[0] = "Paul Merlyn completed a graduate degree in International Relations with focus on dispute resolution from Lancaster University in 1993. He has also completed advanced training in parent-teen mediation, custody mediation, and victim-offender mediation and has mediated more than 400 divorce, family, and small business disputes. In addition to his work with New Resolution LLC, he is a mediation coach for Foothill De Anza College&#039;s mediation certification program, a meetings facilitator for the City of San Francisco and the City of San Mateo, and a former director of the Association for Dispute Resolution of Northern California. He is also an approved mediator for civil disputes by the Superior Court of San Francisco.";
mediatorProfile[1] = "Paul Merlyn completed a graduate degree in International Relations with focus on dispute resolution from Lancaster University in 1993. He has also completed advanced training in parent-teen mediation, custody mediation, and victim-offender mediation and has mediated more than 400 divorce, family, and small business disputes. In addition to his work with New Resolution LLC, he is a mediation coach for Foothill De Anza College&#039;s mediation certification program, a meetings facilitator for the City of San Francisco and the City of San Mateo, and a former director of the Association for Dispute Resolution of Northern California. He is also an approved mediator for civil disputes by the Superior Court of San Francisco.";
mediatorProfile[2] = "Paul Merlyn completed a graduate degree in International Relations with focus on dispute resolution from Lancaster University in 1993. He has also completed advanced training in parent-teen mediation, custody mediation, and victim-offender mediation and has mediated more than 400 divorce, family, and small business disputes. In addition to his work with New Resolution LLC, he is a mediation coach for Foothill De Anza College&#039;s mediation certification program, a meetings facilitator for the City of San Francisco and the City of San Mateo, and a former director of the Association for Dispute Resolution of Northern California. He is also an approved mediator for civil disputes by the Superior Court of San Francisco.";
mediatorProfile[3] = "Donna Lindsey received her Master of Arts degree in Conflict Resolution from Southern Methodist University in Dallas, TX.  She is a trained and certified Civil and Family mediator in the state of Texas.  She offers mediation services for a variety of disputes: divorce, custody, employment, commercial and eldercare for families. She is a member of Mediators Beyond Borders, the American Board of Neutrals Association, the Association of Conflict Resolution, the Association of Family and Conciliation Courts along with Eldercare Mediators.com LLC and the Conflict Resolution Network of North Texas.  Her practice is built on the knowledge that most conflicts can be resolved through mediation, and that most solutions resolved through mediation are quicker, less expensive and more satisfactory than those that are litigated.  Please see the web site www.lindseymediations.com for more information.";
mediatorProfile[4] = "Experienced divorce and family mediator with a proven track record. Licensed psychologist and Florida Supreme Court certified mediator. Over 10 years experience mediating. References available on request from satisfied clients. Why pay attorneys to keep you and your spouse fighting at their benefit? Settle amicably and spare the heartache and expense, along with avoiding the trauma to children and other family members. Mediation trumps litigation or family court. Call today.";

var mediatorHourlyRate = new Array(mediatorID.length);
var index;
for (index in mediatorID)
{
mediatorHourlyRate[index] = new Array(2)
}
mediatorHourlyRate[0] = new Array(true,225);
mediatorHourlyRate[1] = new Array(true,225);
mediatorHourlyRate[2] = new Array(true,225);
mediatorHourlyRate[3] = new Array(false,'');
mediatorHourlyRate[4] = new Array(true,200);

var mediatorAdminCharge = new Array(mediatorID.length);
mediatorAdminCharge[0] = true;
mediatorAdminCharge[1] = true;
mediatorAdminCharge[2] = true;
mediatorAdminCharge[3] = true;
mediatorAdminCharge[4] = false;

var mediatorAdminChargeDetails = new Array(mediatorID.length);
var index;
for (index in mediatorID)
{
mediatorAdminChargeDetails[index] = new Array(2)
}
mediatorAdminChargeDetails[0] = new Array(true,100);
mediatorAdminChargeDetails[1] = new Array(true,100);
mediatorAdminChargeDetails[2] = new Array(true,100);
mediatorAdminChargeDetails[3] = new Array(false,'');
mediatorAdminChargeDetails[4] = new Array(false,'');

var mediatorLocationCharge = new Array(mediatorID.length);
var index;
for (index in mediatorID)
{
mediatorLocationCharge[index] = new Array(3)
}
mediatorLocationCharge[0] = new Array(false,"","");
mediatorLocationCharge[1] = new Array(false,"","");
mediatorLocationCharge[2] = new Array(false,"","");
mediatorLocationCharge[3] = new Array(false,"","");
mediatorLocationCharge[4] = new Array(false,"","");

var mediatorPackages = new Array(mediatorID.length);
var index;
for (index in mediatorID)
{
mediatorPackages[index] = new Array(9)
}
mediatorPackages[0] = new Array(true,3,1200,5,1900,"","",2,"");
mediatorPackages[1] = new Array(true,3,1200,5,1900,"","",2,"");
mediatorPackages[2] = new Array(true,3,1200,5,1900,"","",2,"");
mediatorPackages[3] = new Array(false,"","","","","","","","");
mediatorPackages[4] = new Array(false,"","","","","","","","");

var mediatorSlidingScale = new Array(mediatorID.length);
mediatorSlidingScale[0] = false;
mediatorSlidingScale[1] = false;
mediatorSlidingScale[2] = false;
mediatorSlidingScale[3] = true;
mediatorSlidingScale[4] = false;

var mediatorIncrement = new Array(mediatorID.length);
mediatorIncrement[0] = "10";
mediatorIncrement[1] = "10";
mediatorIncrement[2] = "10";
mediatorIncrement[3] = "60";
mediatorIncrement[4] = "60";

var mediatorConsultationPolicy = new Array(mediatorID.length);
var index;
for (index in mediatorID)
{
mediatorConsultationPolicy[index] = new Array(5)
}
mediatorConsultationPolicy[0] = new Array(true,false,false,false,"");
mediatorConsultationPolicy[1] = new Array(true,false,false,false,"");
mediatorConsultationPolicy[2] = new Array(true,false,false,false,"");
mediatorConsultationPolicy[3] = new Array(true,false,false,false,"");
mediatorConsultationPolicy[4] = new Array(false,false,false,false,"");

var mediatorCancellationPolicy = new Array(mediatorID.length);
var index;
for (index in mediatorID)
{
mediatorCancellationPolicy[index] = new Array(3)
}
mediatorCancellationPolicy[0] = new Array("3","business days","225");
mediatorCancellationPolicy[1] = new Array("3","business days","225");
mediatorCancellationPolicy[2] = new Array("3","business days","225");
mediatorCancellationPolicy[3] = new Array("3","days","100");
mediatorCancellationPolicy[4] = new Array("2","days","200");

var mediatorTelephoneMediations = new Array(mediatorID.length);
mediatorTelephoneMediations[0] = true;
mediatorTelephoneMediations[1] = true;
mediatorTelephoneMediations[2] = true;
mediatorTelephoneMediations[3] = false;
mediatorTelephoneMediations[4] = true;

var mediatorVideoConf = new Array(mediatorID.length);
mediatorVideoConf[0] = true;
mediatorVideoConf[1] = true;
mediatorVideoConf[2] = true;
mediatorVideoConf[3] = false;
mediatorVideoConf[4] = true;

var mediatorCardPolicy = new Array(mediatorID.length);
var index;
for (index in mediatorID)
{
mediatorCardPolicy[index] = new Array(3)
}
mediatorCardPolicy[0] = new Array(true,false,"");
mediatorCardPolicy[1] = new Array(true,false,"");
mediatorCardPolicy[2] = new Array(true,false,"");
mediatorCardPolicy[3] = new Array(false,false,"");
mediatorCardPolicy[4] = new Array(true,false,"");

var mediatorServiceLevel = new Array(mediatorID.length);
mediatorServiceLevel[0] = false;
mediatorServiceLevel[1] = false;
mediatorServiceLevel[2] = false;
mediatorServiceLevel[3] = false;
mediatorServiceLevel[4] = false;

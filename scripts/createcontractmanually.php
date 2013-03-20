<?php
/*
This script (somewhat inspired by Welling/Thomson Chapter 32) is a slightly reworked version of createcontract.php. createcontractmanually.php is used by me to manually create a contract, whereas createcontract.php is invoked by the licensee (prospective licensee) upon a click of the "Review/Sign Agreement" button in licenseonramp.php, whose action script is createcontract.php, as part of a fully automated workflow.
	When AlphaTrust raised their econtracting prices, I abandoned this automated workflow and now generate contracts for new licensees or renewals/newnewals manually instead..
*/
session_start(); // For reuse of $_SESSION['custom']
ob_start(); // Used in conjunction with ob_flush() [see www.tutorialized.com/view/tutorial/PHP-redirect-page/27316], this allows me to postpone issuing output to the screen until after the header has been sent.

// Manually define contract variables. (In createcontract.php, these were instead passed by licenseonramp.php as hidden form fields.) This is the user-specific data that will be used to customize a generic version of the contract document.
$locale = 'Columbus_OH';
$licfee = 0; // In U.S. $
$licterm = 6; // In months
$date = date('F jS, Y'); // If you want to show the Effective Date as today's date (in which case, delete next line)
//$date = 'July 9th, 2012';
$_SESSION['custom'] = 'newlicensee'; // Either set this to the mediator's ID (in mediators_table) [for renewals/newnewals], or set it to 'newlicensee' [for first-time sign-ups]

/* Identify the correct value for the margin stated in the Licensing Agreement. If the Licensing Agreement is for a new licensee, then $_SESSION['custom'] will equal "newlicensee" and the correct margin will be "MarginNow" from parameters_table. If, on the other hand, it's for a renewing or newnewing mediator, $_SESSION['custom'] will be that mediator's ID and the correct margin will be the "Margin" column from mediators_table for that mediator ID. */

// Connect to mysql and select database.
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
	or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database');

if ($_SESSION['custom'] == 'newlicensee')
	{
	$query = "select MarginNow from parameters_table";
	$result = mysql_query($query) or die('The SELECT query to obtain MarginNow from parameters_table i.e. '.$query.' failed: ' . mysql_error());
	$row = mysql_fetch_assoc($result);
	$theMargin = $row['MarginNow'];
	}
else // It's an existing mediator, so set the theMargin to the value guaranteed to the mediator when/he originally became a licensee as recorded in the Margin column of the mediators_table.
	{
	$query = "SELECT Margin FROM mediators_table WHERE ID = ".$_SESSION['custom'];
	$result = mysql_query($query) or die('The SELECT query to obtain Margin from mediators_table i.e. '.$query.' failed: ' . mysql_error());
	$row = mysql_fetch_assoc($result);
	$theMargin = $row['Margin'];
	};

require('/home/paulme6/public_html/nrmedlic/freepdf/fpdf.php'); // Include the fpdf class

class PDF extends FPDF
{
var $B;
var $I;
var $U;
var $HREF;

function PDF($orientation='P',$unit='mm',$format='letter')
{
    //Call parent constructor
    $this->FPDF($orientation,$unit,$format);
    //Initialization
    $this->B=0;
    $this->I=0;
    $this->U=0;
    $this->HREF='';
}

function WriteHTML($html)
{
    //HTML parser
    $html=str_replace("\n",' ',$html);
    $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
    foreach($a as $i=>$e)
    {
        if($i%2==0)
        {
            //Text
            if($this->HREF)
                $this->PutLink($this->HREF,$e);
            else
                $this->Write(5,$e);
        }
        else
        {
            //Tag
            if($e[0]=='/')
                $this->CloseTag(strtoupper(substr($e,1)));
            else
            {
                //Extract attributes
                $a2=explode(' ',$e);
                $tag=strtoupper(array_shift($a2));
                $attr=array();
                foreach($a2 as $v)
                {
                    if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                        $attr[strtoupper($a3[1])]=$a3[2];
                }
                $this->OpenTag($tag,$attr);
            }
        }
    }
}

function OpenTag($tag,$attr)
{
    //Opening tag
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,true);
    if($tag=='A')
        $this->HREF=$attr['HREF'];
    if($tag=='BR')
        $this->Ln(5);
}

function CloseTag($tag)
{
    //Closing tag
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,false);
    if($tag=='A')
        $this->HREF='';
}

function SetStyle($tag,$enable)
{
    //Modify style and select corresponding font
    $this->$tag+=($enable ? 1 : -1);
    $style='';
    foreach(array('B','I','U') as $s)
    {
        if($this->$s>0)
            $style.=$s;
    }
    $this->SetFont('',$style);
}

function PutLink($URL,$txt)
{
    //Put a hyperlink
    $this->SetTextColor(0,0,255);
    $this->SetStyle('U',true);
    $this->Write(5,$txt,$URL);
    $this->SetStyle('U',false);
    $this->SetTextColor(0);
}
}

$html1='This INTERNET MARKETING AGREEMENT (the "<b>Agreement</b>") is made effective as of '.$date.' (the "<b>Effective Date</b>") for a term of '.$licterm.' month(s) (the "<b>Term</b>") extending from the later of the Effective Date or the expiration date of any term still in existence on the Effective Date. The Agreement entered into is between New Resolution LLC, a California limited liability company ("<b>New Resolution</b>") and the undersigned individual ("<b>Mediator</b>").';
$html2='	This Agreement sets forth the terms and conditions of Mediator\'s participation in the New Resolution Launch Platform™ program (the "<b>Platform</b>") and the obligations of each party in connection with the Platform. The purpose of the Platform is to assist mediators in attracting mediation clients through Internet marketing and other services, and to provide a means by which potential clients can locate and contact potential mediators.';
$html3='<u>1. New Resolution Obligations</u><br><br>In consideration of Mediator fulfilling all of the obligations herein set forth, New Resolution agrees, for the term of this Agreement, to include Mediator as a participating mediator in the Platform. The services provided to Mediator under the Platform are set forth in Appendix A to this Agreement.<br><br>
<u>2. New Resolution Representations and Warranties</u><br><br>
New Resolution represents and warrants to Mediator as follows:<br><br>
(a) It has the company power and authority to enter into this Agreement and carry out its obligations as contained herein; and<br><br>
(b) It is the registered owner of the New Resolution® trademark and owns all rights to logo and design connected therewith (collectively, the "<b>Trademarks</b>").<br><br>
EXCEPT FOR THE FOREGOING, NEW RESOLUTION MAKES NO OTHER WARRANTY REGARDING THE PERFORMANCE OF THE SERVICES HEREUNDER, THE PLATFORM OR THE TRADEMARKS, AND MEDIATOR SPECIFICALLY WAIVES ALL WARRANTIES, EXPRESS OR IMPLIED, ARISING OUT OF OR IN CONNECTION WITH THE SERVICES TO BE PROVIDED BY NEW RESOLUTION HEREUNDER, THE PLATFORM OR THE TRADEMARKS.<br><br>
<u>3. Mediator Obligations</u><br><br>
Mediator hereby agrees that it shall fulfill its obligations as set forth in this Agreement. Mediator understands and agrees that the Platform is intended to identify for Internet consumers mediators who are ready, willing and able to provide mediation services in a professional and ethical manner. Mediator agrees as follows:<br><br>
(a)	Mediator shall provide mediation services in accordance with the highest ethical standards;<br><br>
(b)	Mediator shall provide complete and accurate information for use in the Platform (e.g. contact information) and will update that information as appropriate;<br><br>
(c)	Mediator shall not use the Platform for any purpose other than to facilitate the growth of his or her mediation practice through Internet marketing tools;<br><br>
(d)	Mediator shall meet all legal requirements of the city, county, and state in which he or she operates. Mediator will comply with all federal, state and local laws applicable to his or her provision of mediation services; and<br><br>
(e)	During the term of this Agreement, unless granted judicial immunity, Mediator shall obtain and maintain errors and omissions insurance with coverage typical for mediators in the general location in which Mediator practices.<br><br>
<u>4. Mediator Representations and Warranties</u><br><br>
Mediator represents and warrants to New Resolution as follows:<br><br>
(a)	He or she has the power and authority to enter into this Agreement and carry out his or her obligations herein;<br><br>
(b)	Nothing contained in this Agreement restricts or limits the prospects or ability of Mediator to provide mediation services separate from the Platform; and<br><br>
(c)	The success of Mediator\'s mediation business is solely dependent on the talents and efforts of Mediator, and New Resolution is not responsible for Mediator\'s mediation business.<br><br>
<u>5. Territory of Mediator</u><br><br>
(a)	New Resolution defines geographic territories based on standards devised by the U.S. Office of Management and Budget and the U.S. Census Bureau. Territories are Core-Based Statistical Areas specified by the U.S. Census Bureau or, in the case of New York and Los Angeles, Divisions of Metropolitan Areas. Mediator\'s geographic territory ("<b>Territory</b>") is: '.$locale.'. Mediator may only use the Platform within this territory. For example purposes, and without limitation, client referrals from the Site (as defined on Appendix A hereto) may only be accepted from those potential clients located within the territory, and no materials using the Trademarks may be distributed by Mediator outside of the territory.<br><br>
(b)	If New Resolution and Mediator have agreed upon an exclusive Territory and exclusivity fee with respect to the Platform, such exclusivity is set forth on Appendix B to this Agreement. If New Resolution and Mediator have not agreed to Territory exclusivity pursuant to this Agreement, New Resolution will nonetheless allow no more than a limited number of additional mediators to participate in the Platform within Mediator\'s Territory set forth above. This limited number is calculated based on the population of the Territory such that no more than one mediator will be admitted within a Territory whose population is less than 2.5 million at the time of admittance, and no more than the lesser of five mediators or one mediator per 1.25 million population will be admitted within a Territory whose population is greater than 2.5 million at the time of admittance.<br><br>
(c)	Mediator may transfer to a new Territory during the Term without additional cost, with prior notice to New Resolution, unless either of the following conditions exist:<br><br>
        (i)	The new Territory is full, in that the prescribed number of mediators already operates on the Platform in that Territory; or<br><br>
        (ii)	The new Territory into which Mediator seeks to transfer is an exclusive territory of another mediator or is otherwise reserved.<br><br>
<u>6. Fees and Payment</u><br><br>
(a)	If there is an exclusive Territory, the exclusivity fee shall be set forth on Appendix B to this Agreement.<br><br>
(b)	Mediator shall pay to New Resolution a marketing fee of $'.$licfee.' ("<b>Marketing Fee</b>"). Upon renewal of Mediator\'s Term, the Marketing Fee may increase at an annualized percentage rate (APR). The APR will not exceed the then current annualized rate of change in the <i>Consumer Price Index – All Urban Consumers</i> plus a margin of no more than '.$theMargin.'%.<br><br>
(c)	The Marketing Fee is payable upon the Effective Date of this Agreement.<br><br>
(d)	Mediator is solely responsible for paying all taxes (local, state and federal) related to Mediator\'s provision of mediation services in connection with the Platform.<br><br>
(e)	During the initial Term of this Agreement, Mediator shall have the unconditional right to terminate this Agreement upon written notice to New Resolution within 30 days of the Effective Date. Upon such termination, New Resolution shall refund to Mediator any Marketing Fee paid pursuant to this Section and all rights and obligations of both parties shall terminate.<br><br>
<u>7. Term</u><br><br>
Unless sooner terminated according to the provisions of this Agreement, this Agreement shall be for a term of '.$licterm.' month(s) commencing on the later of the Effective Date or the expiration date of any term still in existence on the Effective Date. Upon mutual consent of New Resolution and Mediator, the Agreement may continue to be extended indefinitely through subsequent terms.<br><br>
<u>8. Title; Trademarks</u><br><br>
(a)	To the extent permitted by law, the services to be provided by New Resolution under this Agreement and any Appendices are proprietary to New Resolution, and title thereto remains in New Resolution. All proprietary title and rights extend to any renewal, amendment or modification of this Agreement.<br><br>
(b)	All applicable rights to patents, copyrights, trademarks and trade secrets in the Platform and in the Trademarks, now and in the future, belong exclusively to New Resolution. All trademarks and service marks association with New Resolution are and shall remain the exclusive property of New Resolution. Mediator is permitted to use the Trademarks only as set forth herein or only as authorized in writing by New Resolution.<br><br>
(c)	Because of the value to New Resolution of the Trademarks and the importance of maintaining high, uniform standards of quality in the Platform\'s use of the Trademarks, Mediator agrees to:<br><br>
        (i)	Affix the Trademarks to or on advertising and promotional material only according to the formats, logotypes, colors, styles and specifications used by New Resolution;<br><br>
        (ii)	Not otherwise use the Trademarks in any way except in accordance with the Platform; and<br><br>
        (iii)	Not use the Trademarks outside the designated Territory.<br><br>
(d)	New Resolution reserves the right, in its sole discretion, to revise the Trademarks from time to time. New Resolution will give Mediator thirty days\' prior written notice of any change.<br><br>
(e)	New Resolution shall have the sole right, at its expense, to defend and settle any action that may be commenced against New Resolution or Mediator alleging that the Trademarks infringe any right of any third party. Mediator shall, at the direction of New Resolution, promptly discontinue its use of the Trademarks alleged to infringe rights of such third parties, and New Resolution shall refund to Mediator the pro rata portion of the Marketing Fee for the remaining period after the discontinuation date.<br><br>
<u>9. Termination; Effect of Termination</u><br><br>
(a)	Either party may terminate this Agreement prior to the end of the term (i) if the other party breaches its obligations under this Agreement and such breach is not cured within 5 days of receipt by the breaching party of written notice of the breach from the non-breaching party; or (ii) immediately upon any action by or against the other party relating to its bankruptcy (whether voluntary or involuntary), insolvency, liquidation or dissolution.<br><br>
(b)	New Resolution may immediately terminate this Agreement if Mediator violates or is alleged to have violated any local, state or federal law.<br><br>
(c)	Upon termination of this Agreement for any reason, New Resolution shall have no further obligation to Mediator under this Agreement and Mediator\'s participation in the Platform shall immediately cease and Mediator shall not use the Trademarks, any Platform materials or resources from the date of termination. Mediator shall remain liable to New Resolution for any unpaid obligations due hereunder until paid in full.<br><br>
(d)	The following Sections shall survive termination of this Agreement for any reason: Sections 6(d), 6(e), 9, 10, 11, and 12.<br><br>
<u>10. Indemnification</u><br><br>
(a)	Mediator agrees to indemnify and hold New Resolution, its directors, officers, employees and agents harmless from all liabilities, obligations or claims arising from or connected with the mediation services provided by Mediator pursuant to the Platform or otherwise.<br><br>
(b)	New Resolution agrees to indemnify and hold Mediator harmless from all liabilities associated with any claim of infringement related to the Trademarks.<br><br>
<u>11. Limitation of Liability</u><br><br>
NEITHER PARTY HERETO SHALL BE LIABLE TO THE OTHER FOR INDIRECT, INCIDENTAL, CONSEQUENTIAL, SPECIAL, PUNITIVE OR EXEMPLARY DAMAGES, WHETHER IN CONTRACT, TORT OR OTHERWISE (EVEN IF SUCH PARTY HAS BEEN ADVISED OF THE POSSIBLITY OF SUCH DAMAGES) SUCH AS, BUT NOT LIMITED TO, LOSS OF REVENUE OR ANTICIPATED PROFITS OR LOST BUSINESS; AND IN NO EVENT SHALL TOTAL LIABILITY OF EITHER PARTY EXCEED THE AGGREGATE FEES ACTUALLY PAID BY MEDIATOR HEREUNDER.<br><br>
<u>12. General</u><br><br>
(a)	<i>No Partnership or Agency</i>. Nothing in this Agreement shall be deemed to constitute a partnership, association or joint venture between the parties hereto.<br><br>
(b)	<i>Confidentiality</i>. During the Term, Mediator may have access to and become acquainted with various trade secrets of New Resolution, including but not limited to strategies, processes, computer programs, compilations of information and contractual information, all of which are owned by New Resolution and regularly used in the operation of New Resolution\'s business. Mediator agrees to keep all such information confidential. Mediator acknowledges that the sale or unauthorized use or disclosure of New Resolution\'s trade secrets will damage New Resolution, and Mediator will use best efforts to keep any such trade secrets confidential.<br><br>
(c)	<i>Notices</i>. All notices, consents, waivers, and other communications under this Agreement must be in writing and will be deemed to have been duly given when: (a) delivered via email to the recipient\'s email address set forth below with confirmation of receipt returned electronically to the sender; (b) sent by fax to the recipient\'s fax number set forth below with confirmation of transmission received and kept by the sender; or (c) delivered to the recipient at recipient\'s address set forth below if sent by reputable delivery service by the sender with signature upon delivery required.<br><br>
(d)	<i>Waiver</i>. The rights and remedies of the parties to this Agreement are cumulative and not alternative. Neither the failure nor any delay by any party in exercising any right, power or privilege under this Agreement will operate as a waiver of such right, power or privilege, and no single or partial exercise of any such right, power or privilege will preclude any other or further exercise of such right, power or privilege or the exercise of any other right, power or privilege. To the maximum extent permitted by applicable law, (a) no claim or right arising out of this Agreement can be discharged by one party, in whole or in part, by a waiver or renunciation of the claim or right unless in writing signed by the other party; (b) no waiver that may be given by a party will be applicable except in the specific instance for which it is given; and (c) no notice to or demand on one party will be deemed to be a waiver of any obligation of such party or of the right of the party giving such notice or demand to take further action without notice or demand as provided in this Agreement.<br><br>
(e)	<i>Entire Agreement; Modification</i>. This Agreement, including its appendices, supersedes all prior agreements between the parties with respect to its subject matter and constitutes a complete and exclusive statement of the terms of the agreement between the parties with respect to its subject matter. This Agreement may not be amended except by a written amendment signed by both parties.<br><br>
(f)	<i>Assignment; Successors; Third Party Rights</i>. This Agreement is not assignable by Mediator without the prior consent of New Resolution. Subject to the preceding sentence, this Agreement will apply to, be binding in all respects upon, and inure to the benefit of the successors and permitted assigns of the parties. Nothing expressed or referred to in this Agreement will be construed to give any person other than the parties to this Agreement any legal or equitable right, remedy, or claim under or with respect to this Agreement or any provision of this Agreement, and this Agreement and all of its provisions and conditions are for the sole and exclusive benefit of the parties to this Agreement and their successors and assigns.<br><br>
(g)	<i>Severability</i>. If any provision of this Agreement is held invalid or unenforceable by any court of competent jurisdiction, the other provisions of this Agreement will remain in full force and effect. Any provision of this Agreement held invalid or unenforceable only in part or degree will remain in full force and effect to the extent not held invalid or unenforceable.<br><br>
(h)	<i>Time of the Essence</i>. With regard to all dates and time periods set forth or referred to in this Agreement, time is of the essence.<br><br>
(i)	<i>Governing Law</i>. This Agreement will be governed by the laws of the State of California, without regard to conflicts of laws principles.<br><br>
(j)	<i>Judicial Reference</i>. The parties hereby agree that any action or claim arising out of any dispute in connection with this agreement, any rights, remedies, obligations, or duties hereunder, or the performance or enforcement hereof or thereof shall be determined by judicial reference in accordance with California Code of Civil Procedure section 638. The parties intend this general reference agreement to be specifically enforceable, if necessary by motion made to the court, in accordance with section 638 and 642. The following judicial reference procedures shall apply:<br><br>
        (i) The parties agree to the appointment of a single referee located in the San Francisco Bay Area. The parties shall use their best efforts to agree promptly on the selection of the referee. If the parties are unable to agree on a referee within ten (10) calendar days of a party\'s written request to do so, either party may request the court to appoint a referee pursuant to Code of Civil Procedure section 640 and California Rule of Court 244.1. The referee shall hear and determine all issues in dispute, whether of fact or law, and shall issue a statement of decision pursuant to Code of Civil Procedure section 638(a) and 643(a).<br><br>
        (ii) The hearing shall be conducted in accordance with the California Evidence Code. Any party desiring a stenographic record shall arrange for a court reporter to attend the hearing, at its sole cost, and provide advance notice to all other Parties. Unless the parties agree otherwise, the hearing shall be completed within six months of the Courts order for reference. The court shall enter judgment on the referee\'s decision in accordance with Code of Civil Procedure section 644(a). The referee shall hear and determine any motion for new trial or motion to vacate the judgment.<br><br>
        (iii) The referee shall conduct a prehearing conference to address procedural matters, arrange for the exchange of information, obtain stipulations, and attempt to narrow the issues. The parties shall submit a proposed discovery schedule to the referee at the prehearing conference. The parties may utilize all discovery methods available to litigants under the California Civil Discovery Act (Code of Civil Procedure section 2016.010 et seq.), and all means of production available under Code of Civil Procedure section 11985 et seq., including sanctions and other remedies for noncompliance with same. The exchange of expert witness information pursuant to Code of Civil Procedure section 2034.210, et seq. shall also be available to the parties. Unless the parties agree otherwise, all discovery shall be completed not less than two months and not more than four months after the prehearing conference.<br><br>
        (iv) The parties agree to pay in advance, in equal shares, the referee\'s estimated fees and costs of the reference proceeding, as may be specified in advance by the referee.<br><br>
        (v) The foregoing procedure for resolution of disputes under Code of Civil Procedure section 638, et seq., shall not preclude any party from seeking provisional remedies from a court of appropriate jurisdiction prior to obtaining an order for reference.<br><br>
(k)	<i>Attorney\'s Fees</i>. In any court proceeding, reference proceeding or administrative proceedings or any action in any court of competent jurisdiction, brought by either party to enforce any of such party\'s rights or remedies under this Agreement, including any action for declaratory relief, or any action to collect any payment under this Agreement, the prevailing party shall be entitled to reasonable attorneys\' fees and all costs and expenses in connection with such action, including the costs of reasonable investigation, preparation, and professional or expert consultation, which sums may be included in any judgment or decree entered in such action in favor of the prevailing party.<br><br>
(l)	<i>Signatures</i>. This Agreement may be signed and delivered electronically as an alternative to hard copy.
IN WITNESS WHEREOF, the parties have executed this Internet Marketing Agreement effective as of the Effective Date:<br><br>
<br>
<b>NEW RESOLUTION LLC</b>, a California Limited Liability Company<br>
<br>
By: <br><br>
        __________________________________<br>
        Paul R. Merlyn, Authorized Officer<br>
<br>
<br>
<br>
<b>MEDIATOR:</b><br><br>
Name/Signature:<br>';

$html4='New Resolution will provide the following services to Mediator:<br><br>
1. <u>Inclusion in the web site www.newresolutionmediation.com (the "<b>Site</b>")</u><br><br>
Mediator\'s information will be included in the Site via a password-protected mediator profile. Changes may be made to the information at any time by Mediator. Upon execution of this Agreement, New Resolution will provide a link to Mediator to enable Mediator to enter his or her information on a pre-established template.<br><br>
2. <u>Use of New Resolution Trademarks for business cards, letterhead, and email signature</u><br><br>
Mediator will have access to New Resolution\'s business card, letterhead and email signature design templates for Mediator\'s use in producing these materials.<br><br>
3. <u>Email delivery of requests for consultation and mediation sessions</u><br><br>
New Resolution will automatically forward to Mediator\'s designated email address any request for consultation or mediation completed online by a prospective client. The prospective client will receive an automatic acknowledgement that states that Mediator will be in touch with the prospective client shortly.<br><br>
4. <u>New Resolution email address</u><br><br>
Mediator will have an email address account in the following format: [yourname]@newresolutionmediation.com. Mediator may send and receive messages via this account.<br><br>
5. <u>Technical support</u><br><br>
New Resolution will provide unlimited email technical support at Mediator\'s reasonable request. New Resolution will respond within 24 hours (one business day) of Mediator\'s initial request for support.<br><br>
6.	<u><i>Wires Crossed</i> email marketing format</u><br><br>
Mediator will have access to New Resolution\'s Wires Crossed e-zine template as available to all other participants in the Platform. New Resolution will provide access to content or Mediator may provide his or her own content. New Resolution owns or licenses all content provided by it and grants only a non-exclusive, non-transferable, limited license to use such content in accordance with this Agreement. Mediator shall include with the content any ownership notices, including copyright notices, as directed by New Resolution. Mediator shall otherwise have the responsibility to obtain any releases, licenses or permissions for content Mediator may include.<br><br>
7. <u>Postcard direct mailer</u><br><br>
New Resolution will provide the template for a promotional postcard direct mailer incorporating the Trademarks. Mediator may print the postcards and include additional images or text. Mediator is responsible for obtaining appropriate rights to any such text or image that is owned by a third party.<br><br>
8. <u>Search engine optimization</u>
New Resolution will use commercially reasonable efforts to optimize the Site in the Mediator\'s territory for relevant Internet searches on the three main search engines: Google, Yahoo!, Microsoft (Bing).<br><br><br>
<b>ACCESSIBILITY OF SITE: Mediator agrees that from time to time the Site may be inaccessible or inoperable for various reasons, including (i) periodic maintenance procedures or repairs that New Resolution may undertake from time to time and (ii) causes beyond the control of New Resolution or which are not reasonably foreseeable by New Resolution (collectively, "Downtime"). New Resolution shall use commercially reasonable efforts to minimize any disruption, inaccessibility and/or inoperability of the Site in connection with Downtime.</b>';

$html5='Subject to the terms and conditions of this Agreement and its Appendices, New Resolution hereby grants to Mediator the exclusive non-transferable right to use the Platform services within the following Territory:<br><br><br><br>
The fee payable by Mediator to New Resolution for such exclusivity (the<br>"<b>Exclusivity Fee</b>") shall be:<br><br>
$_______.<br><br>
The Exclusivity Fee shall be payable on the same terms as set forth in Section 6 of this Agreement.<br><br>
Nothing contained herein limits or restricts Mediator from otherwise providing mediation services in any geographic territory in the world. The Exclusivity contained herein pertains only to participation in the Platform and use of the Platform materials.';


$pdf=new PDF();
//First page
$pdf->SetTitle('New Resolution LLC Internet Marketing Agreement');
$pdf->SetAuthor('New Resolution LLC');
$pdf->SetLeftMargin(25);
$pdf->SetRightMargin(25);
$pdf->SetTopMargin(25);
$pdf->AddPage();
$pdf->SetFont('Arial','B',20);
$pdf->Cell(0,0,'Internet Marketing Agreement',0,1,'C');
$pdf->SetFont('');
$pdf->Ln(20);
$pdf->SetFontSize(13);
$pdf->WriteHTML($html1);
$pdf->SetFont('Arial','B',14);
$pdf->Ln(20);
$pdf->Cell(0,0,'RECITAL',0,1,'C');
$pdf->SetFont('');
$pdf->Ln(10);
$pdf->SetFontSize(13);
$pdf->WriteHTML($html2);
$pdf->SetFont('Arial','B',14);
$pdf->Ln(20);
$pdf->Cell(0,0,'AGREEMENT',0,1,'C');
$pdf->SetFont('');
$pdf->Ln(10);
$pdf->SetFontSize(13);
$pdf->WriteHTML($html3);
$pdf->Image('/home/paulme6/public_html/nrmedlic/images/PaulRMerlynSignature.jpg',35,56,0,0);
$pdf->AddPage();
$pdf->SetFont('Arial','BU',20);
$pdf->Cell(0,0,'APPENDIX A',0,1,'C');
$pdf->Ln(12);
$pdf->SetFont('Arial','B',20);
$pdf->Cell(0,0,'NEW RESOLUTION MEDIATION LAUNCH PLATFORM(TM)',0,1,'C');
$pdf->SetFont('');
$pdf->Ln(20);
$pdf->SetFontSize(13);
$pdf->WriteHTML($html4);
$pdf->AddPage();
$pdf->SetFont('Arial','BU',20);
$pdf->Cell(0,0,'APPENDIX B',0,1,'C');
$pdf->Ln(12);
$pdf->SetFont('Arial','B',20);
$pdf->Cell(0,0,'EXCLUSIVITY',0,1,'C');
$pdf->SetFont('');
$pdf->Ln(20);
$pdf->SetFontSize(13);
$pdf->WriteHTML($html5);
$pdf->Output('/home/paulme6/public_html/nrmedlic/LicensingAgreement.pdf', F);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Manually Create User's Bespoke Internet Marketing Agreement</title>
</head>
<body>
<!--  Having generated a PDF version of the contract customized for each particular licensee, display a confirmation on-screen. (Note that createcontract.php [i.e. the fully automated workflow] instead sent the user to the AlphaTrust site where the contract got e-signed.) -->
<h2 style="margin-top: 60px; margin-left: 100px;">The e-contract was successfully manually created for <?=$locale; ?>.</h2>
<h2 style="margin-top: 20px; margin-left: 100px;">The term is <?= $licterm; ?> months, and the fee is $<?=$licfee; ?>.</h2>
<?php
ob_flush();
?>
</noscript>
</body>
</html>

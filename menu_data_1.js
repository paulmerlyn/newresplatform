_menuCloseDelay=500;
_menuOpenDelay=150;
_subOffsetTop=2;
_subOffsetLeft=-2;


forgetClickValue="true";


with(menuStyle=new mm_style()){
bgimage="/images/tab_on_charcoal.jpg";
fontfamily="Verdana, Tahoma, Arial";
fontsize="11px"; // Was ="65%"
fontstyle="normal";
fontweight="bold";
itemheight=26;
itemwidth=79;
offcolor="#FFFFCC";
oncolor="#FF9900";
openonclick=1;
subimagepadding=2;
separatorimage="/images/tab_white_sep.gif";
separatorsize=1;
clickbgimage="/images/tab_on_tang.jpg";
}

with(submenuStyle=new mm_style()){
styleid=1;
align="left";
menubgimage="/images/tangmenubg.jpg";
fontfamily="Verdana, Tahoma, Arial";
fontsize="10px"; // Was ="65%"
fontstyle="normal";
fontweight="bold";
itemheight=19;
//offbgcolor="#FF9900";
offcolor="#2F4F4F";
oncolor="#000000";
ondecoration="none";
openonclick=1;
padding=6;
separatorimage="/images/tab_top_vert_black.gif";
separatorsize=3;
overfilter='Fade(duration=0.2);Shadow(color="#333333",Direction=180,Strength=3)';
}

with(milonic=new menuname("Main Menu")){
alwaysvisible=1;
openstyle="tab";
orientation="horizontal";
screenposition="center";
style=menuStyle;
top=102;
aI("align=center;keepalive=1;showmenu=MainMenu1;text=Mediation;");
aI("align=center;keepalive=1;showmenu=MainMenu2;text=About Us;");
aI("align=center;keepalive=1;showmenu=MainMenu3;text=Divorce;");
aI("align=center;keepalive=1;showmenu=MainMenu4;text=Business;");
aI("align=center;keepalive=1;showmenu=MainMenu5;text=Relational;");
aI("align=center;keepalive=1;showmenu=MainMenu6;text=Litigation;");
aI("align=center;itemwidth=80;keepalive=1;showmenu=MainMenu7;text=Contact Us;");
}

with(milonic=new menuname("MainMenu1")){
menualign="left";
menuwidth="750px";
orientation="horizontal";
screenposition="center";
style=submenuStyle;
aI("text=Mediation;url=m_mediation.shtml;");
aI("text=Benefits;url=m_mediationbenefits.shtml;");
aI("text=Mediation Fees;url=m_fees.shtml;");
aI("text=Divorce Mediation;url=d_whendivmed.shtml;");
aI("text=Mediators, Attorneys & Therapists;url=m_mat.shtml;");
aI("text=Mediation Preparation;url=m_preparation.shtml;");
aI("text=Books;url=r_books.shtml;");
aI("separatorsize=4;");
}

with(milonic=new menuname("MainMenu2")){
menualign="left";
menuwidth="750px";
orientation="horizontal";
screenposition="center";
style=submenuStyle;
aI("text=FAQs;url=m_questions.shtml;");
aI("text=Services;url=s_disputes.shtml;");
aI("text=Custom Services;url=s_custom.shtml;");
aI("text=Mediators;url=s_mediators.shtml;");
aI("text=Our Locations;url=locations.php;");
aI("text=Fees for Mediation;url=s_fees.shtml;");
aI("text=Client Testimonials;url=s_testimonials.shtml;");
aI("text=Articles;url=r_mediationarticles.shtml;");
aI("text=Standards;url=s_standards.shtml;");
aI("separatorsize=4;");
}

with(milonic=new menuname("MainMenu3")){
menualign="left";
menuwidth="750px";
orientation="horizontal";
screenposition="center";
style=submenuStyle;
aI("text=Divorce;url=d_divorce.shtml;");
aI("text=Mediation Benefits;url=d_divmedbenefits.shtml;");
aI("text=Settlement Agreements;url=d_msa.shtml;");
aI("text=Divorce & Separation;url=d_divandsep.shtml;");
aI("text=Alimony/Support;url=d_support.shtml;");
aI("text=Custody;url=d_children.shtml;");
aI("text=Property;url=d_property.shtml;");
aI("text=Tax;url=d_tax.shtml;");
aI("separatorsize=4;");
}

with(milonic=new menuname("MainMenu4")){
menualign="left";
menuwidth="750px";
orientation="horizontal";
screenposition="center";
style=submenuStyle;
aI("text=Business Mediation;url=r_businessmediation.shtml;");
aI("text=Benefits;url=businessmediation.shtml;");
aI("text=Dispute Types;url=workplacedisputes.shtml;");
aI("text=Litigation;url=l_litigation.shtml;");
aI("text=Our Locations;url=locations.php;");
aI("separatorsize=4;");
}

with(milonic=new menuname("MainMenu5")){
menualign="left";
menuwidth="750px";
orientation="horizontal";
screenposition="center";
style=submenuStyle;
aI("text=Coparenting;url=f_children.shtml;");
aI("text=Elder Mediation;url=f_eldermediation.shtml;");
aI("text=Inheritance & Estates;url=f_inheritance.shtml;");
aI("text=Marital Mediation;url=r_maritalmediation.shtml;");
aI("text=Same-Sex Mediation;url=r_samesexmediation.shtml;");
aI("separatorsize=4;");
}

with(milonic=new menuname("MainMenu6")){
menualign="left";
menuwidth="750px";
orientation="horizontal";
screenposition="center";
style=submenuStyle;
aI("text=Litigation;url=l_litigation.shtml;");
aI("text=Vindication;url=l_vindication.shtml;");
aI("text=Costs of Litigation;url=l_costs.shtml;");
aI("text=Empowerment;url=l_empowerment.shtml;");
aI("text=Litigation vs Mediation;url=l_litvmed.shtml;");
aI("separatorsize=4;");
}

with(milonic=new menuname("MainMenu7")){
menualign="left";
menuwidth="750px";
orientation="horizontal";
screenposition="center";
style=submenuStyle;
aI("text=Contact Us;url=c_contact.shtml;");
aI("text=Telephone Consultation;url=consultreqform.shtml;");
aI("text=In-Person Consultation;url=c_inpersonconsultreqform.shtml;");
aI("text=Schedule Mediation;url=c_schedmediationform.shtml;");
aI("text=Invite Party to Mediation;url=otherpartyrequestform.shtml;");
aI("text=Locations;url=locations.php;");
aI("separatorsize=4;");
}
drawMenus();


// *************************************************
// *             JS Form Validation                *
// *                  V 0.1.0                      *
// *                 16/01/2002                    *
// *************************************************
// *               Cédric Lecocq                   *
// *           lecocq@5emegauche.com               *
// *************************************************


// *************************************************
// *               Public function                 *
// *************************************************

// --FR-- le champ est t'il vide?
// --UK-- is the field empty?
function TextIsEmpty(champ)
{
  if (eval("formulaire."+champ+".value")=="")
  { return true;}
  else
  { return false;}
}

// --FR-- le champ contient t'il un email valide?
// --UK-- is the field contains a valid email?
function TextIsNotEmail(champ)
{
    val = eval("formulaire."+champ+".value");
	longueur = val.length;
	pos = val.indexOf("@");
	pos2 = val.lastIndexOf(".");
	if (pos==-1 || pos2==-1 || pos2<pos || pos<1 || pos2-pos<2 || longueur-pos2<2)
	{ return true;}
	else
	{ return false;}
}

// --FR-- le champ contient t'il un nombre decimal?
// --UK-- is the field contain a decimal number?
function TextIsNotDecimal(champ)
{
  if (TextIsEmpty(champ))
  { return true;}
  else
  {
    val = eval("formulaire."+champ+".value");
    if (IsDecimal(val))
    { return false;}
    else
    { return true;}
  }
}

// --FR-- le champ contient t'il un nombre entier?
// --UK-- is the field contain a integer?
function TextIsNotNumeric(champ)
{
  if (TextIsEmpty(champ))
  { return true;}
  else
  {
    val = eval("formulaire."+champ+".value");
    if (IsNumeric(val))
    { return false;}
    else
    { return true;}
  }
}

// --FR-- le champ contient t'il que des lettres?
// --UK-- is the field just contain alpha chars?
function TextIsNotAlpha(champ)
{
  if (TextIsEmpty(champ))
  { return true;}
  else
  {
    val = eval("formulaire."+champ+".value");
    if (IsAlpha(val))
    { return false;}
    else
    { return true;}
  }
}

// --FR-- le champ contient t'il que des chiffres ou des lettres? (pas de caracteres speciaux)
// --UK-- is the field just contain alphanumeric chars? (no special chars)
function TextIsNotAlphaNum(champ)
{
  if (TextIsEmpty(champ))
  { return true;}
  else
  {
    val = eval("formulaire."+champ+".value");
    if (IsAlphaNum(val))
    { return false;}
    else
    { return true;}
  }
}

// --FR-- le champ contient t'il un entier compris entre ces deux valeurs?
// --UK-- is the field contain an integer included between these two values?
function TextIsNotBetween(champ, minVal, maxVal)
{
  val = eval("formulaire."+champ+".value");
  if (IsNumeric(val))
  {
    if (val>minVal && val<maxVal)
	{ return false;}
	else
	{ return true;}
  }
  else { return true;}
}

// --FR-- les 2 champs sont t'il egaux?
// --UK-- are these 2 fields equals?
function TextIsNotEquals(champ1, champ2)
{
  if (TextIsEmpty(champ1)||TextIsEmpty(champ2))
  { return true;}
  else
  {
    if (eval("formulaire."+champ1+".value")!=eval("formulaire."+champ2+".value"))
    { return true;}
    else
    { return false;}
  }
}

// --FR-- a t'on selectionné au moins 1 entrée dans le select multiligne?
//        (select non multiligne: l'option visible est toujours selectionné)
// --UK-- have we selected at least 1 entry in the multiline select?
//        (non-multiline select: visible option is always selected)
function SelectIsEmpty(champ)
{
  if (eval("formulaire."+champ+".selectedIndex")==-1)
  { return true;}
  else
  { return false;}
}

// --FR-- la date est t'elle valide? (utilise 3 select)
// --UK-- is the date valid? (use 3 select)
function SelectIsNotDate(champannee, champmois, champjour)
{
  annee = eval("formulaire."+champannee+".value");
  mois = eval("formulaire."+champmois+".value");
  jour = eval("formulaire."+champjour+".value");
  if (IsDateValide(annee, mois, jour))
  { return false;}
  else
  { return true;}
}

// --FR-- retourne le numero du jour de la semaine (0:dimanche -> 6:samedi)
// --UK-- return day of week (0:sunday -> 6:saturday)
function GetDayOfWeek(champannee, champmois, champjour)
{
  annee = eval("formulaire."+champannee+".value");
  mois = eval("formulaire."+champmois+".value");
  jour = eval("formulaire."+champjour+".value");

  var myDate = new Date(annee,mois-1,jour); // mois-1 for 0:jan -> 11:dec
  return myDate.getDay();
}

// --FR-- le bouton radio est t'il coché?
// --UK-- is the radio button checked?
function RadioIsNotChecked(champ)
{
  noncoche=true;
  nb=eval("formulaire."+champ+".length");
    for (i=0; i<nb; i++)
    {
      if (eval("formulaire."+champ+"["+i+"].checked")) noncoche=false;
    }
  return noncoche;
}

// --FR-- combien de checkbox sont cochées dans la serie?
// --UK-- how many checkbox are checked in the serie?
function BoxHowChecked(racinechamp, totalchamp)
{
  nb = 0;
  for (i=1; i<=totalchamp; i++)
  {
    if (eval("formulaire."+racinechamp+i+".checked") == true)  nb=nb+1;
  }
  return nb;
}


// *************************************************
// *               Private function                *
// *************************************************

// --FR-- la date est-elle valide?
// --UK-- is the date valid?
function IsDateValide(year, month, day)
{
  bi = (year%400==0 || (year%4==0 && year%100!=0));
  if ( ( month == 1 || month == 3 || month == 5 || month == 7 || month == 8 || month == 10 || month == 12 ) && day >= 1 && day <= 31)
    { correct = true;}
    else
    {
	  if ( ( month == 4 || month == 6 || month == 9 || month == 11 ) && day >= 1 && day <= 30)
	  { correct = true;}
	  else
	  {
		if (bi)
		{ correct = ( day >= 1 && day <= 29 && month == 2);}
		else
		{ correct = ( day >= 1 && day <= 28 && month == 2);}
	  }
	}
  return correct;  
}


AlphaChars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
NumChars = "0123456789";
AlphaNumChars = AlphaChars + NumChars;
HexaChars = "ABCDEFabcdef";
// --FR-- la chaine contient t'elle un entier?
// --UK-- is the string contain an integer?
		function IsNumeric(Data) {
			var It = true;
			var c;
			for (var x = 0; x < Data.length; x++)
				if (It) {
					c = Data.charAt(x);
					It = ( NumChars.indexOf(c) != -1);
				}
			return It;
		}
// --FR-- la chaine ne contient t'elle que des caractères alphanumeriques?
// --UK-- is the string contain only alphanumeric chars?
		function IsAlphaNum(Data) {
			var It = true;
			var c;
			for (var x = 0; x < Data.length; x++)
				if (It) {
					c = Data.charAt(x);
					It = ( AlphaNumChars.indexOf(c) != -1);
				}
			return It;
		}
// --FR-- la chaine contient t'elle un nombre decimal?
// --UK-- is the string contain an decimal number?
		function IsDecimal(Data) {
			var It = true;
			var virgule = false;
			var c;
			c = Data.charAt(0);	
			It = ( (NumChars.indexOf(c) != -1) || (c == "+") || (c=="-") );
			for (var x = 1; x < Data.length; x++)
				if (It) {
					c = Data.charAt(x);
					if ( (c==".") || (c == ",") )
						if (virgule) 
							It = false;
						else {
							It = true;
							virgule = true;
						}
					else
						It = (NumChars.indexOf(c) != -1);
				}
			return It;
		}
// --FR-- la chaine ne contient t'elle que des lettres?
// --UK-- is the string contain only alpha chars?
		function IsAlpha(Data) {
			var It = true;
			var c;
			for (var x = 0; x < Data.length; x++)
				if (It) {
					c = Data.charAt(x);
					It = ( AlphaChars.indexOf(c) != -1);
				}
			return It;
		}



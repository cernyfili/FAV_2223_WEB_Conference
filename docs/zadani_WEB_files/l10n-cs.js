// X3: pozor, otestujte si v lokalizacnich blocich, zda lokalizovane objekty opravdu existuji,
// jinak to rve chyby pokud lokalizujete neco co na strance neni...
// carney: tady ma by lokalizace pouze toho, co je vsude (v COMBINED.js)!

// Lokalizace naseptavace
if (typeof(Suggest) == "object") {
  Suggest.prototype.katedraTitle = 'Dohledání pracovišť';
  Suggest.prototype.predmetTitle = 'Dohledání předmětů';
  Suggest.prototype.mistnostTitle = 'Dohledání místností';
  Suggest.prototype.ucitelTitle = 'Dohledání vyučujících';
  Suggest.prototype.literaturaTitle = 'Dohledání literatury';
  Suggest.prototype.studentTitle = 'Dohledání studentů';
  Suggest.prototype.krouzekTitle = 'Dohledání studijních kroužků';
  Suggest.prototype.programTitle = 'Dohledání studijních kroužků';
  Suggest.prototype.oborTitle = 'Dohledání studijních kroužků';
  Suggest.prototype.zkrKombinaceTitle = 'Dohledání studijních kroužků';
  Suggest.prototype.timeoutLabel = 'Vypršel čas pro volání (20 vteřin)';
  Suggest.prototype.errorLabel = 'Chyba na straně serveru';
  Suggest.prototype.errorDiv = 'Omlouváme se, ale nastala chyba při spouštění dohledávače: ';
  Suggest.prototype.loadingLabel = '<div class="xg_msgFromServerDefault"><img src=\"/zdroje/images/icons/spinner.gif\" alt=\"Nahrávám data...\">Nahrávám data...</div>';
  Suggest.prototype.enterDetails = '<div class="xg_msgFromServerError">Zadejte podrobnější parametry vyhledávání</div>';
}


// Lokalizace validace
if (jQuery.validator) {
  jQuery.extend(jQuery.validator.messages, {
	required: "Tento údaj je povinný.",
	remote: "Prosím, opravte tento údaj.",
	email: "Tato položka může obsahovat pouze platný e-mail.",
	url: "Tato položka může obsahovat pouze URL.",
	date: "Tato položka může obsahovat pouze platné datum.",
	dateISO: "Tato položka může obsahovat pouze platné datum (ISO).",
	number: "Tato položka může obsahovat pouze číslo.",
	digits: "Tato položka může obsahovat pouze číslice.",
	creditcard: "Tato položka může obsahovat číslo kreditní karty.",
	equalTo: "Zadejte znovu stejnou hodnotu.",
	accept: "Zadejte soubor se správnou příponou.",
	maxlength: jQuery.validator.format("Prosím, zadejte nejvíce {0} znaků."),
	minlength: jQuery.validator.format("Prosím, zadejte nejméně {0} znaků."),
	rangelength: jQuery.validator.format("Prosím, zadejte od {0} do {1} znaků."),
	range: jQuery.validator.format("Prosím, zadejte hodnotu od {0} do {1}."),
	max: jQuery.validator.format("Prosím, zadejte hodnotu menší nebo rovnu {0}."),
	min: jQuery.validator.format("Prosím, zadejte hodnotu větší nebo rovnu {0}."),
  	"validate-date-cz" : "Pou\u017Eijte tento form\u00E1t: dd.mm.rrrr, dd.mm.rr nebo %. Nap\u0159\u00EDklad 17.03.2006 pro 17. b\u0159ezna 2006."
  });
}

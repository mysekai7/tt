<?php

$lang = array (
  'api_doc_page_title' => 'API-dokumentasjon - #SITE_TITLE#',
  'api_doc_menu_title' => 'API-dokumentasjon',
  'api_doc_cat_num' => '16',
  'api_doc_cat_1' => 'Komme igang',
  'api_doc_cat_2' => 'OAuth FAQ',
  'api_doc_cat_3' => 'Statusmetoder',
  'api_doc_cat_4' => 'Brukermetoder',
  'api_doc_cat_5' => 'Direktemeldinger',
  'api_doc_cat_6' => 'Vennskapsmetoder',
  'api_doc_cat_7' => 'Sosialemetoder',
  'api_doc_cat_8' => 'Kontometoder',
  'api_doc_cat_9' => 'Gruppemetoder',
  'api_doc_cat_10' => 'Favorittmetoder',
  'api_doc_cat_11' => 'Lagrede søk',
  'api_doc_cat_12' => 'OAuth metoder',
  'api_doc_cat_13' => 'Bruker brukerens data',
  'api_doc_cat_14' => 'Test',
  'api_doc_cat_15' => 'Søk',
  'api_doc_cat_16' => 'Trender',
  'api_doc_cant_understand' => 'Fremdeles noe du ikke forstår? Du kan alltid spørre vår supportavdeling.',
  'api_doc_contact_us' => 'Kontakt oss',
  'api_doc_cat_1_post_num' => '8',
  'api_doc_cat_1_post_1_title' => '#SITE_TITLE# API Oversikt',
  'api_doc_cat_1_post_1_text' => 'De [b] #SITE_TITLE API#[/b] er inspirert av [b]Twitters API[/b], og gir deg en enkel og strukturert måte å få tilgang til de kraftige egenskapene til [b]#SITE_TITLE#[/b]. APIen lør deg programmatisk søke, hente, opprette og slette autentiserte medlemsprofiler, grupper og andre data fra vårt nettsted. Våre API-egenskaper gjør at du dynamisk kan legge til nytt innhold så vel som statusoppdateringer, kommentarer på eksisterende status, administrere brukerprofiler, grupper, direktemeldinger, favoritter, trender og sanntidssøk i [b]#SITE_TITLE# [/ b] database. APIen er basert på åpne standarder som XML, RSS, JSON og Atom.',
  'api_doc_cat_1_post_2_title' => 'Autentisering',
  'api_doc_cat_1_post_2_text' => 'Ikke alle funksjonaliteten som tilbys av vår API krever autentisering. Funksjonaliteten med fri tilgang, er de som alle kan få tilgang til fritt og dette inkluderer ikke endringsmuligheter, som for eksempel slette, redigere, legge til data og lesing av personlige opplysninger fra våre brukere.

APIen har noen få metoder som ville kreve at brukeren må autentiseres for å få tilgang til og/eller publisere data. For eksempel funksjonalitet på [b]#SITE_TITLE#[/b] er tilgjengelig for påloggede brukere, som for eksempel å poste oppdateringer eller laste ned direktemeldinger, er tilsvarende beskyttet via APIen. For å beskytte våre brukerdata anvender vi også OAuth protokollen for autentisering av applikasjoner som gir tilgang til våre brukerdata.',
  'api_doc_cat_1_post_3_title' => 'OAuth',
  'api_doc_cat_1_post_3_text' => '[Url = http://apiwiki.twitter.com/Authentication]http://apiwiki.twitter.com/Authentication[/url]

OAuth er en symbol-kontrollerende mekanisme som tillater brukerne å kontrollere hvilke programmer som har tilgang til deres data uten å gi bort passordene sine. Mer informasjon om OAuth spesifikasjonen finner du på [url = http://www.oauth.net]oauth.net[/url] eller i den utmerkede Beginner\'s Guide to OAuth fra [url = http://hueniverse.com/ oauth /]Hueniverse[/url].

Registrering av OAuth applikasjoner for [b]#SITE_TITLE#[/b] er tilgjengelig på [url=#SITE_URL#api]#SITE_URL#api[/url].

Tilgangssymboler bør aldri gå ut på dato. En brukers tilgangssymbol vil bli gjort ugyldig hvis brukeren eksplisitt tilbakekaller programmets autorisasjon eller om [b]#SITE_TITLE#[/b] blokkerer din applikasjon. Hvis autorisasjonen din er blokkert vil det bli et notat på applikasjonssiden din som forteller at den har blitt blokkert. I begge tilfeller bør applikasjonen være i stand til å håndtere symbol-tilbakekallelse på en elegant måte.

Mange brukere stoler på et program for å lese informasjon, men ikke nødvendigvis for å endre  deres navn eller legge inn nye statuser. OAuth tillatelser er delt inn i nivåer, slik at applikasjoner og brukere kan bli enige om skrivebeskyttelse eller lese-og skrivetilgang. Oppdatering av informasjon via API, det være seg navn, sted eller oppdatering av statuser, krever en HTTP POST. Enhver API-metode som krever en HTTP POST er ansett som en skrive-metode og krever lese-og skrivetilgang.',
  'api_doc_cat_1_post_4_title' => 'Skriverbordsklienter',
  'api_doc_cat_1_post_4_text' => '[Url = http://apiwiki.twitter.com/Authentication]http://apiwiki.twitter.com/Authentication[/url]

Den tradisjonelle OAuth flyt for skriverbordsklienter kan være tungvint. Vi har laget en bekreftelsesbasert opplevelse for skrivebordsklienter  som bruker følgende flyt:

1.Applikasjonen bruker [b]oauth/request_token[/b] for å få et forespørselssymbol fra [b]#SITE_TITLE#[/b].
2.Applikasjonen leder brukeren til [b]oauth/autorize[/b] på [b]#SITE_TITLE#[/b].
3.Etter å ha innhentet godkjenning fra brukeren, vil en melding på [b]#SITE_TITLE#[/b] vise en 7-sifret kontrollkode.
4.Brukeren blir instruert til å kopiere kontrollkoden og gå tilbake til applikasjonen.
5.Applikasjonen spør brukeren om å skrive inn kontrollkoden fra trinn 4.
6.Applikasjonen bruker kontrollkoden som verdi for [b]oauth_verifier[/b] parameter i et kall til [b]oauth/access_token[/b] som vil sjekke kontrollkoden og utveksle en [b]request_token[/b] for en [b]access_token[/b].
7.Twitter vil returnere en [b]access_token[/b] for at applikasjonen skal generere etterfølgende OAuth signaturer.',
  'api_doc_cat_1_post_5_title' => 'Kapasitetstsbegrensninger',
  'api_doc_cat_1_post_5_text' => '[Url=http://apiwiki.twitter.com/Rate-limiting]http://apiwiki.twitter.com/Rate-limiting[/url]

Standard grense for kall til API er 150 forespørsler per time. APIen gjør konto-og IP-baserte kapasitetsbegrensninger. Godkjente API-kall belastes den autentiserende brukerens grense mens uautentiserte API-kall trekkes fra den kallende IP-adressens tildeling.

Kapasitetsbegrensning gjelder bare for metoder som ber om informasjon med HTTP GET-kommandoen. API metoder som bruker HTTP POST for å sende data til [b]#SITE_TITLE#[/b], for eksempel [b]statuser/oppdatering[/b]  påvirker ikke kapasitetsgrensen. I tillegg blir forespørsler til [b]-konto/rate_limit_status[/b], og noen av OAuth-endepunktene ikke belastet en kapasitetsgrense. Disse ubegrenset metodene er fortsatt underlagt daglig oppdateringer og følgegrenser for å fremme sikker bruk og motvirke søppelpost.

Din applikasjon bør gjenkjenne at den blir kapasitetsbegrenset av APIen dersom den begynner å motta HTTP 400 svarkoder. Det er best praksis for applikasjoner for å overvåke deres gjeldende kapasitets-status og forespørsel om dynamisk regulering om nødvendig. APIen tilbyr en måte å observere denne statusen:

[b]-konto/rate_limit_status[/b] metoden. Å kalle på denne metoden, regnes ikke mot den forespurte API\'s kapasitetsbegrensning. Responsen inkluderer:

[b]&lt hash&gt
&lt remaining&gt 150 &lt/remaining&gt
&lt hourly-limit>150 &lt/hourly-limit&gt
&lt reset-time&gt 7 &lt/reset-time&gt
&lt /hash&gt[/b]',
  'api_doc_cat_1_post_6_title' => 'HTTP status- og feilkoder',
  'api_doc_cat_1_post_6_text' => '[Url=http://apiwiki.twitter.com/HTTP-Response-Codes-and-Errors]http://apiwiki.twitter.com/HTTP-Response-Codes-and-Errors[/url]

APIen forsøker å returnere aktuelle HTTP statuskoder for hver forespørsel. Det er mulig å undertrykke svarkoder for APIen.

[B]200 OK[/b]: Vellykket!
[B]304 Ikke endret[/b]: Det var ingen nye data å returnere.
[B]400 Ugyldig forespørsel[/b]: Forespørselen var ugyldig. En medfølgende feilmelding vil forklare hvorfor. Dette er statuskoden som gies når kapasiteten blir begrenset.
[B]401 Uautorisert[/b]: Autentiseringsdata mangler eller er feil.
[B]403 Ulovlig [/b]: Forespørselen er forstått, men det har blitt nektet. En medfølgende feilmelding vil forklare hvorfor. Denne koden brukes når forespørsler blir avvist på grunn oppdateringsbegrensninger.
[B]404 Ikke funnet[/b]: Den forespurte URI, er ugyldig eller ressursen som er forespurt, for eksempel en bruker, eksisterer ikke.
[B]500 Intern serverfeil[/b]: Noe er ødelagt. Vennligst skriv en post til gruppen slik at Sharetronix teamet kan undersøke.',
  'api_doc_cat_1_post_7_title' => 'Koder for tilbakekall og undertrykking av respons',
  'api_doc_cat_1_post_7_text' => '[Url=http://apiwiki.twitter.com/Things-Every-Developer-Should-Know]http://apiwiki.twitter.com/Things-Every-Developer-Should-Know[/url]

Det er to spesielle parametre i [b]#SITE_TITLE#[/b] API:

[B]tilbakekall[/b]: Brukes bare når du ber om JSON formatert svar, wraps denne parameteren pakker inn svaret ditt i den tilbakkekallsmetoden du velger. For eksempel, legger du til [b]&callback=myFancyFunction[/b] til forespørselen vil det resultere i et svar fra: myFancyFunction (...). Tilbakekall kan bare inneholde alfanumeriske tegn og understreking; eventuelle ugyldige tegn vil bli fjernet.

[b]suppress_response_codes[/b]: Hvis denne parameteren er til stede, vil alle svar bli returnert med en 200 OK statuskode - selv feil. Denne parameteren finnes for å imøtekomme Flash og JavaScript programmer som kjører i nettlesere som fanger opp alle ikke-200 svar. Hvis brukt, er det da jobben til klienten å finne feiltilstanden ved å analysere responsdata. Brukes med forsiktighet, ettersom feilmeldingene kan endre seg.

Hvor bemerket, noen API-metoder vil gi ulike resultater basert på HTTP-hoder sendt av klienten. Der hvor den samme atferd kan kontrolleres av både en parameter og et HTTP-hode, vil parameteren ha forrang.',
  'api_doc_cat_1_post_8_title' => 'Feilmeldinger',
  'api_doc_cat_1_post_8_text' => 'Når [b#SITE_TITLE#[/b] API returnerer feilmeldinger, gjør den det i ditt valgte format. For eksempel kan en feil fra en XML-metode se slik ut:

[b]&lt&#63xml version="1.0" encoding="UTF-8" &#63&gt
&lt hash&gt
&lt request&gt /direct_messages/destroy/456.xml &lt/request&gt
&lt error&gt No direct message with that ID found. &lt/error&gt
&lt/hash&gt[/b]',
  'api_doc_cat_2_post_num' => '7',
  'api_doc_cat_2_post_1_title' => 'Hva er OAuth?',
  'api_doc_cat_2_post_1_text' => '[Url=http://apiwiki.twitter.com/OAuth-FAQ#WhatisOAuth]http://apiwiki.twitter.com/OAuth-FAQ#WhatisOAuth[/url]

OAuth er en godkjenningsprotokoll som tillater brukere å godkjenne applikasjoner til å opptre på deres vegne uten å dele passordet sitt. Mer informasjon finnes på [url=http://www.oauth.net]oauth.net[/url] eller i det utmerkede Beginner\'s Guide to OAuth fra [url=http://hueniverse.com/oauth/]Hueniverse[/url].',
  'api_doc_cat_2_post_2_title' => 'Hvor oppretter jeg en applikasjon?',
  'api_doc_cat_2_post_2_text' => '#SITE_URL#api',
  'api_doc_cat_2_post_3_title' => 'Hvor lenge varer en tilgangskode?',
  'api_doc_cat_2_post_3_text' => '[url=http://apiwiki.twitter.com/OAuth-FAQ#Howlongdoesanaccesstokenlast]http://apiwiki.twitter.com/OAuth-FAQ#Howlongdoesanaccesstokenlast[/url]

for tiden er det ingen utløpstid på tilgangskoder. Din tilgangskode vil være ugyldig hvis en bruker eksplisitt avviser din applikasjon fra sine innstillinger, eller hvis en [b]#SITE_TITLE#[/b] administrator blokkerer din applikasjon. Hvis applikasjonen din er blokkert vil det bli et notat på applikasjonssiden din som sier at den har blitt blokkert.',
  'api_doc_cat_2_post_4_title' => 'Applikasjonens registreringsside spør om lese/skrive-tilgang. Hva konstituerer en skrivehandling?',
  'api_doc_cat_2_post_4_text' => 'http://apiwiki.twitter.com/OAuth-FAQ#Theapplicationregistrationpageasksaboutread/writeaccessWhatconstitutesawrite

Mange brukere stoler på en applikasjon for å lese informasjon, men ikke nødvendigvis til å endre navn eller legge inn nye statuser. Oppdatering av informasjon via [b]#SITE_TITLE#[/b] API - det være seg navn, sted eller legge til en ny status - krever en HTTP POST. Vi holdt oss til samme begrensning ved implementering av dette. Enhver API-metode som krever en HTTP POST er ansett som en skrivemetode og krever lese-og skrivetilgang.',
  'api_doc_cat_2_post_5_title' => 'Hvilken krypteringsalgoritmer bruker du?',
  'api_doc_cat_2_post_5_text' => 'For å kryptere forespørsler til en gitt ressurs kan du kun bruke [b]HMAC-SHA1[/b] algoritmen. Vi støtter ikke HTTPS-protokollen for overføring av data i [b]#SITE_TITLE#[/b], noe som betyr at vi ikke støtter rene tekst signaturer. Alle forespørsler må være kryptert av signaturer som er opprettet med en [b]HMAC-SHA1[/b] algoritme.',
  'api_doc_cat_2_post_6_title' => 'Hvilken prosess brukes for å opprette en sidesignatur?',
  'api_doc_cat_2_post_6_text' => 'For å opprette en sidesignatur, vennligst følger den opprinnelige algoritmen presentert i [url=http://www.oauth.net]oauth.net[/url], du kan lese mer her [url=http://oauth.net/ core/1.0 /#signing_process]http://oauth.net/core/1.0/#signing_process[/url].

De viktigste ting å se opp for er:

1.Hver parameter må være [b]UTF8[/b]-format og [b]urlencoded[/b].
2.Parametrene må være i [b]alfabetisk rekkefølge[/b].
3.[b]Oauth_signature[/b] parameteren er [b]ikke inkludert[/b] med de andre parameterne når signaturen er opprettet.
4.Tidsstempel er uttrykt i antall sekunder siden 1 januar 1970 00:00:00 GMT. Tidsstempelverdien [b]må[/b] være et positivt heltall og [b]må[/b] være lik eller større enn tidsstempelet brukt i tidligere forespørsler.
5.Nonce [b]må[/b] være unik for alle forespørsler med dette tidsstempelet. En nonce er en tilfeldig streng, unikt genereret for hver forespørsel.',
  'api_doc_cat_2_post_7_title' => 'Er det nødvendig å bruke OAuth og opprette signatur for hver eneste side?',
  'api_doc_cat_2_post_7_text' => 'Det er åpen tilgang til sider som alle kan bruke uten autentisering til API. Disse sidene viser kun generell informasjon tilgjengelig for alle brukere. For eksempel kan du besøke nyeste innleggene i ditt nettverk uten autentisering.',
  'api_doc_cat_2_post_8_title' => 'Hvordan bruke API med tredjepartsapplikasjoner',
  'api_doc_cat_2_post_8_text' => 'For url base bruk den følgende adressen: #SITE_URL#1',
  'api_doc_cat_3_post_num' => '15',
  'api_doc_cat_3_post_1_title' => 'statuses/public_timeline',
  'api_doc_cat_3_post_1_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-public_timeline]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-public_timeline[/url]

Siste 20 statuser fra ikke-beskyttede brukere som har angitt et egendefinert brukerikon.

[i]Bruk[/i]: #SITE_URL#1/statuses/public_timeline.format
[i]Metode[/i]: GET
[i]Støttede formater[/i]: XML, RSS, JSON og Atom
[i]Krever autentifikasjon[/i]: false (Hvis du er autentisert, vil du også få status fra private grupper, hvor du er medlem)
[i]API kapasitetbegrenset[/i]: true
[i]Parametre[/i]: none
[i]Respons[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-public_timeline]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-public_timeline[/url]

[b]Eksempel[/b]: #SITE_URL#1/statuses/public_timeline.json',
  'api_doc_cat_3_post_2_title' => 'statuses/user_timeline',
  'api_doc_cat_3_post_2_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-user_timeline]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-user_timeline[/url]

Siste 20 statuser postet av en autentisert bruker. Du kan be om en annen brukers tidslinje via id-parameteren også.

[i]Bruk[/i]: #SITE_URL#1/statuses/user_timeline.format
[i]Metode[/i]: GET
[i]Støttede formater[/i]: XML, RSS, JSON og Atom
[i]Krever Autentifikasjon[/i]: false (Hvis du er autentisert, vil du også få status fra private grupper, hvor du er medlem)
[i]API kapasitetsbegrensning[/i]: true
[i]Parametere[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-user_timeline]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-user_timeline[/url]
[i]Respons[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-user_timeline]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-user_timeline[/url]

[b]Eksempel[/b]: #SITE_URL#1/statuses/user_timeline/1234.json?suppress_response_codes',
  'api_doc_cat_3_post_3_title' => 'statuses/mentions',
  'api_doc_cat_3_post_3_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-mentions]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-mentions[/url]

Siste 20 nevnelser (poster som inneholder @brukernavn) for en autentisert bruker.

[i]Bruk[/i]: #SITE_URL#1/statuses/mentions.format
[i]Metode[/i]: GET
[i]Støttede formater[/i]: XML, RSS, JSON og Atom
[i]Krever autentisering[/i]: true
[i]API kapasitetsbegrensning[/i]: true
[i]Parametre[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-mentions]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-mentions[/url]
[i]Respons[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-mentions]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-mentions[/url]

[b]Eksempel[/b]: #SITE_URL#1/statuses/mentions.xml',
  'api_doc_cat_3_post_4_title' => 'statuses/private_mentions',
  'api_doc_cat_3_post_4_text' => 'Siste 20 nevnelser i private poster (poster som inneholder @brukernavn) for en autentisert bruker.

[i]Bruk[/i]: #SITE_URL#1/statuses/private_mentions.format
[i]Metode[/i]: GET
[i]Støttede formater[/i]: XML, RSS, JSON og Atom
[i]Krever autentisering[/i]: true
[i]API kapasitetsbegrensning[/i]: true
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-mentions]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-mentions[/url]
[i]Respons[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-mentions]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-mentions[/url]

[b]Eksempel[/b]: #SITE_URL#1/statuses/private_mentions.xml',
  'api_doc_cat_3_post_5_title' => 'statuses/update',
  'api_doc_cat_3_post_5_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses%C2%A0update] apiwiki.twitter.com/statuses update [/url]

[i]Bruk[/i]: #SITE_URL#1/statuses/update.format
[i]Metode[/i]: POST
[i]Støttede formater[/i]: XML, JSON
[i]Krever autentisering[/i]: true
[i]API kapasitetsbegrensning[/i]: false
[i]Parametre[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses%C2%A0update]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-mentions[/url]
[i]Tilleggsparametre som kan vedlegges din post[/i]: link(valid url), video|file|image(raw multipart data, not a URL to a file|image|video)
[i]Ustøttede parametre[/i]: lat, long, place_id, display_coordinates
[i]Respons[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses%C2%A0update]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-mentions[/url]

[b]Eksempel[/b]: #SITE_URL#1/statuses/update.xml?status=hello
[b]Eksempel[/b]: #SITE_URL#1/statuses/update.xml?status=hello&in_reply_to_status_id=1234
[b]Eksempel[/b]: #SITE_URL#1/statuses/update.xml?status=hello&link=your_link
[b]Eksempel[/b]: #SITE_URL#1/statuses/update.xml?status=hello&video=
[b]Eksempel[/b]: #SITE_URL#1/statuses/update.xml?status=hello&file=',
  'api_doc_cat_3_post_6_title' => 'statuses/group_update',
  'api_doc_cat_3_post_6_text' => 'Updates the authenticating user\'s status in specific group.

[i]Usage[/i]: #SITE_URL#1/statuses/group_update.format
[i]Method[/i]: POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false

[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses%C2%A0update]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-update[/url] and specify the group id: #SITE_URL#1/statuses/group_update/group_id.format
or group name: #SITE_URL#1/statuses/group_update/group_name.format

[i]Unsupported Parameters[/i]: lat, long, place_id, display_coordinates
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses%C2%A0update]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-update[/url]

[b]Example[/b]: #SITE_URL#1/statuses/group_update.xml?status=hello
[b]Example[/b]: #SITE_URL#1/statuses/group_update.xml?status=hello&in_reply_to_status_id=1234',
  'api_doc_cat_3_post_7_title' => 'statuses/destroy',
  'api_doc_cat_3_post_7_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses%C2%A0destroy] apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-destroy [/url]

Destroys the post specified by the required ID parameter.  The authenticating user must be the author of the specified post.

[i]Usage[/i]: #SITE_URL#1/statuses/destroy/id.format
[i]Method[/i]: POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: id of the status
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses%C2%A0destroy]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-destroy[/url]

[b]Example[/b]: #SITE_URL#1/statuses/destroy/1234.xml',
  'api_doc_cat_3_post_8_title' => 'statuses/private_destroy',
  'api_doc_cat_3_post_8_text' => 'Destroys the private post specified by the required ID parameter.  The authenticating user must be the author of the specified private post.

[i]Usage[/i]: #SITE_URL#1/statuses/private_destroy/id.format
[i]Method[/i]: POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: id of the private status
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses%C2%A0destroy]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-destroy[/url]

[b]Example[/b]: #SITE_URL#1/statuses/private_destroy/1234.xml',
  'api_doc_cat_3_post_9_title' => 'statuses/friends',
  'api_doc_cat_3_post_9_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses%C2%A0friends] apiwiki.twitter.com/Twitter-REST-API-Method:-statuses friends[/url]

Returns a user\'s friends, each with current status inline. It\'s also possible to request another user\'s friends list via the id, screen_name or user_id parameter.

[i]Usage[/i]: #SITE_URL#1/statuses/friends.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: false
[i]API rate limited[/i]: false
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses%C2%A0friends]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-friends[/url]
[i]Unsupported Parameters[/i]: cursor
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses%C2%A0friends]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-friends[/url]

[b]Example[/b]: #SITE_URL#1/statuses/friends.json?user_id=1234',
  'api_doc_cat_3_post_10_title' => 'statuses/followers',
  'api_doc_cat_3_post_10_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses%C2%A0followers] apiwiki.twitter.com/Twitter-REST-API-Method:-statuses followers[/url]

Returns a user\'s followers, each with current status inline. It\'s also possible to request another user\'s followers list via the id, screen_name or user_id parameter.

[i]Usage[/i]: #SITE_URL#1/statuses/followers.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: false
[i]API rate limited[/i]: false
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses%C2%A0followers]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-followers[/url]
[i]Unsupported Parameters[/i]: cursor
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses%C2%A0followers]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-followers[/url]

[b]Example[/b]: #SITE_URL#1/statuses/followers.json?user_id=1234',
  'api_doc_cat_3_post_11_title' => 'statuses/friends_timeline',
  'api_doc_cat_3_post_11_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-friends_timeline] apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-friends_timeline[/url]

Latest 20 statuses posted by the authenticating user and that user\'s friends.

[i]Usage[/i]: #SITE_URL#1/statuses/friends_timeline.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON, Atom, RSS
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: true
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-friends_timeline]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-friends_timeline[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-friends_timeline]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-friends_timeline[/url]

[b]Example[/b]: #SITE_URL#1/statuses/friends_timeline.json',
  'api_doc_cat_3_post_12_title' => 'statuses/show',
  'api_doc_cat_3_post_12_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses%C2%A0show] apiwiki.twitter.com/Twitter-REST-API-Method:-statuses show[/url]

[i]Usage[/i]: #SITE_URL#1/statuses/show/id.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON, Atom, RSS
[i]Requires Authentication[/i]: false (If you are authorized, you could get a status from private groups, where you are a member)
[i]API rate limited[/i]: true
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses%C2%A0show]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-show[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses%C2%A0show]http://apiwiki.twitter.com/Twitter-REST-API-Method:-statuses-show[/url]

[b]Example[/b]: #SITE_URL#1/statuses/show/1234.json',
  'api_doc_cat_3_post_13_title' => 'statuses/commented',
  'api_doc_cat_3_post_13_text' => 'Returns all post by the user that have comments.

[i]Usage[/i]: #SITE_URL#1/statuses/commented.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: true
[i]Parameters[/i]: type: public or private statuses
[i]Response[/i]:
[b]&lt statuses&gt
&lt post&gt
&lt id&gt post_id &lt/id&gt
&lt/post&gt
&lt/statuses&gt[/b]

[b]Example[/b]: #SITE_URL#1/statuses/commented.json?type=public',
  'api_doc_cat_3_post_14_title' => 'statuses/comments',
  'api_doc_cat_3_post_14_text' => 'Returns all comments to a selected post.

[i]Usage[/i]: #SITE_URL#1/statuses/comments.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: true
[i]Parameters[/i]: post_id: #SITE_URL#1/statuses/comments.format?post_id=123
[i]Response[/i]:
[b]&lt comments&gt
&lt comment&gt
&lt id&gt comment_id &lt/id&gt
&lt text&gt comment_text &lt/text&gt
&lt/comment&gt
&lt/comments&gt[/b]

[b]Example[/b]: #SITE_URL#1/statuses/comments.xml?post_id=123',
  'api_doc_cat_3_post_15_title' => 'statuses/private_comments',
  'api_doc_cat_3_post_15_text' => 'Returns all comments to a selected private post.

[i]Usage[/i]: #SITE_URL#1/statuses/private_comments.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: true
[i]Parameters[/i]: post_id: #SITE_URL#1/statuses/private_comments.format?post_id=123
[b]&lt comments&gt
&lt comment&gt
&lt id&gt comment_id &lt/id&gt
&lt text&gt comment_text &lt/text&gt
&lt/comment&gt
&lt/comments&gt[/b]

[b]Example[/b]: #SITE_URL#1/statuses/private_comments.xml?post_id=123',
  'api_doc_cat_4_post_num' => '4',
  'api_doc_cat_4_post_1_title' => 'users/show',
  'api_doc_cat_4_post_1_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-users%C2%A0show] apiwiki.twitter.com/Twitter-REST-API-Method:-users show[/url]

[i]Usage[/i]: #SITE_URL#1/users/show.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: false
[i]API rate limited[/i]: true
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-users%C2%A0show]http://apiwiki.twitter.com/Twitter-REST-API-Method:-users-show[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-users%C2%A0show]http://apiwiki.twitter.com/Twitter-REST-API-Method:-users-show[/url]

[b]Example[/b]: #SITE_URL#1/users/show/1234.xml',
  'api_doc_cat_4_post_2_title' => 'users/lookup',
  'api_doc_cat_4_post_2_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-users-lookup]http://apiwiki.twitter.com/Twitter-REST-API-Method:-users-lookup[/url]

[i]Usage[/i]: #SITE_URL#1/users/lookup.format
[i]Method[/i]: GET, POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: true
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-users-lookup]http://apiwiki.twitter.com/Twitter-REST-API-Method:-users-lookup[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-users-lookup]http://apiwiki.twitter.com/Twitter-REST-API-Method:-users-lookup[/url]

[b]Example[/b]: #SITE_URL#1/users/lookup.json?user=1234,5678&screen_name=chuck,david',
  'api_doc_cat_4_post_3_title' => 'users/search',
  'api_doc_cat_4_post_3_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-users-search]http://apiwiki.twitter.com/Twitter-REST-API-Method:-users-search[/url]

Returns all users that has been found by certain criteria

[i]Usage[/i]: #SITE_URL#1/users/search.format
[i]Method[/i]: GET, POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: true
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-users-search]http://apiwiki.twitter.com/Twitter-REST-API-Method:-users-search[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-users-search]http://apiwiki.twitter.com/Twitter-REST-API-Method:-users-search[/url]

[b]Example[/b]: #SITE_URL#1/users/search.json?q=david',
  'api_doc_cat_4_post_4_title' => 'users/groups',
  'api_doc_cat_4_post_4_text' => 'Returns all public groups where a user is a member.

[i]Usage[/i]: #SITE_URL#1/users/groups.format
[i]Method[/i]: GET, POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: true
[i]Parameters[/i]: id or screen_name, #SITE_URL#1/users/groups/user_id.format or  #SITE_URL#1/users/groups/screen_name.format
[i]Response[/i]:
[b]&lt groups&gt
&lt group&gt
&lt id&gt group_id &lt/id&gt
&lt name&gt group name &lt/name&gt
&lt/group&gt
&lt/groups&gt[/b]

[b]Example[/b]: #SITE_URL#1/users/groups/1234.xml',
  'api_doc_cat_5_post_num' => '4',
  'api_doc_cat_5_post_1_title' => 'direct_messages',
  'api_doc_cat_5_post_1_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages]http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages[/url]

[i]Usage[/i]: #SITE_URL#1/direct_messages.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: true
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages]http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages]http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages[/url]

[b]Example[/b]: #SITE_URL#1/direct_messages.xml',
  'api_doc_cat_5_post_2_title' => 'direct_messages/sent',
  'api_doc_cat_5_post_2_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages%C2%A0sent]http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages-sent[/url]

[i]Usage[/i]: #SITE_URL#1/direct_messages/sent.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: true
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages%C2%A0sent]http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages-sent[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages%C2%A0sent]http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages-sent[/url]

[b]Example[/b]: #SITE_URL#1/direct_messages/sent.xml',
  'api_doc_cat_5_post_3_title' => 'direct_messages/new',
  'api_doc_cat_5_post_3_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages%C2%A0new]http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages-new[/url]

[i]Usage[/i]: #SITE_URL#1/direct_messages/new.format
[i]Method[/i]: POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages%C2%A0new]http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages-new[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages%C2%A0new]http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages-new[/url]

[b]Example[/b]: #SITE_URL#1/direct_messages/sent.xml?user_id=1234&text=hello',
  'api_doc_cat_5_post_4_title' => 'direct_messages/destroy',
  'api_doc_cat_5_post_4_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages%C2%A0destroy]http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages-destroy[/url]

[i]Usage[/i]: #SITE_URL#1/direct_messages/destroy/id.format
[i]Method[/i]: POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages%C2%A0destroy]http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages-destroy[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages%C2%A0destroy]http://apiwiki.twitter.com/Twitter-REST-API-Method:-direct_messages-destroy[/url]

[b]Example[/b]: #SITE_URL#1/direct_messages/destroy/1234.json',
  'api_doc_cat_6_post_num' => '4',
  'api_doc_cat_6_post_1_title' => 'frienships/create',
  'api_doc_cat_6_post_1_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships%C2%A0create]http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships-create[/url]

[i]Usage[/i]: #SITE_URL#1/friendships/create/id.format
[i]Method[/i]: POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships%C2%A0create]http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships-create[/url]
[i]Unsupported Parameters[/i]: follow
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships%C2%A0create]http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships-create[/url]

[b]Example[/b]: #SITE_URL#1/frienships/create/1234.json',
  'api_doc_cat_6_post_2_title' => 'friendships/destroy',
  'api_doc_cat_6_post_2_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships%C2%A0destroy]http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships-destroy[/url]

[i]Usage[/i]: #SITE_URL#1/friendships/destroy/id.format
[i]Method[/i]: POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships%C2%A0destroy]http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships-destroy[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships%C2%A0destroy]http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships-destroy[/url]

[b]Example[/b]: #SITE_URL#1/frienships/destroy/1234.json',
  'api_doc_cat_6_post_3_title' => 'friendships/exists',
  'api_doc_cat_6_post_3_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships-exists]http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships-exists[/url]

[i]Usage[/i]: #SITE_URL#1/friendships/exists.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: false
[i]API rate limited[/i]: true
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships-exists]http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships-exists[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships-exists]http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships-exists[/url]

[b]Example[/b]: #SITE_URL#1/friendships/exists.json?user_a=1234&user_b=5678',
  'api_doc_cat_6_post_4_title' => 'friendships/show',
  'api_doc_cat_6_post_4_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships-show]http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships-show[/url]

[i]Usage[/i]: #SITE_URL#1/frienships/show.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: false
[i]API rate limited[/i]: true
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships-show]http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships-show[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships-show]http://apiwiki.twitter.com/Twitter-REST-API-Method:-friendships-show[/url]

[b]Example[/b]: #SITE_URL#1/friendships/show.json?source_id=1234&target_id=5678',
  'api_doc_cat_7_post_num' => '2',
  'api_doc_cat_7_post_1_title' => 'friends/ids',
  'api_doc_cat_7_post_1_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-friends%C2%A0ids]http://apiwiki.twitter.com/Twitter-REST-API-Method:-friends-ids[/url]

[i]Usage[/i]: #SITE_URL#1/friends/ids.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: false
[i]API rate limited[/i]: true
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-friends%C2%A0ids]http://apiwiki.twitter.com/Twitter-REST-API-Method:-friends-ids[/url]
[i]Unsupported Parameters[/i]: cursor
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-friends%C2%A0ids]http://apiwiki.twitter.com/Twitter-REST-API-Method:-friends-ids[/url]

[b]Example[/b]: #SITE_URL#1/friends/ids.json?user_id=1234',
  'api_doc_cat_7_post_2_title' => 'followers/ids',
  'api_doc_cat_7_post_2_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-followers%C2%A0ids]http://apiwiki.twitter.com/Twitter-REST-API-Method:-followers-ids[/url]

[i]Usage[/i]: #SITE_URL#1/followers/ids.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: false
[i]API rate limited[/i]: true
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-followers%C2%A0ids]http://apiwiki.twitter.com/Twitter-REST-API-Method:-followers-ids[/url]
[i]Unsupported Parameters[/i]: cursor
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-followers%C2%A0ids]http://apiwiki.twitter.com/Twitter-REST-API-Method:-followers-ids[/url]

[b]Example[/b]: #SITE_URL#1/friends/ids.json?user_id=1234',
  'api_doc_cat_8_post_num' => '6',
  'api_doc_cat_8_post_1_title' => 'account/verify_credentials',
  'api_doc_cat_8_post_1_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-account%C2%A0verify_credentials]http://apiwiki.twitter.com/Twitter-REST-API-Method:-account-verify_credentials[/url]

[i]Usage[/i]: #SITE_URL#1/account/verify_credentials.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: true
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-account%C2%A0verify_credentials]http://apiwiki.twitter.com/Twitter-REST-API-Method:-account-verify_credentials[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-account%C2%A0verify_credentials]http://apiwiki.twitter.com/Twitter-REST-API-Method:-account-verify_credentials[/url]

[b]Example[/b]: #SITE_URL#1/account/verify_credentials.xml',
  'api_doc_cat_8_post_2_title' => 'account/rate_limit_status',
  'api_doc_cat_8_post_2_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-account%C2%A0rate_limit_status]http://apiwiki.twitter.com/Twitter-REST-API-Method:-account-rate_limit_status[/url]

[i]Usage[/i]: #SITE_URL#1/account/rate_limit_status.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true (to determine a user\'s rate limit status), false (to determine the requesting IP\'s rate limit status)
[i]API rate limited[/i]: false
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-account%C2%A0rate_limit_status]http://apiwiki.twitter.com/Twitter-REST-API-Method:-account-rate_limit_status[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-account%C2%A0rate_limit_status]http://apiwiki.twitter.com/Twitter-REST-API-Method:-account-rate_limit_status[/url]

[b]Example[/b]: #SITE_URL#1/account/rate_limit_status.xml',
  'api_doc_cat_8_post_3_title' => 'account/update_profile_image',
  'api_doc_cat_8_post_3_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-account%C2%A0update_profile_image]http://apiwiki.twitter.com/Twitter-REST-API-Method:-account-update_profile_image[/url]

[i]Usage[/i]: #SITE_URL#1/account/update_profile_image.format
[i]Method[/i]: POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-account%C2%A0update_profile_image]http://apiwiki.twitter.com/Twitter-REST-API-Method:-account-update_profile_image[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-account%C2%A0update_profile_image]http://apiwiki.twitter.com/Twitter-REST-API-Method:-account-update_profile_image[/url]

[b]Example[/b]: #SITE_URL#1/account/update_profile_image.xml?image=',
  'api_doc_cat_8_post_4_title' => 'account/update_profile',
  'api_doc_cat_8_post_4_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-account%C2%A0update_profile]http://apiwiki.twitter.com/Twitter-REST-API-Method:-account-update_profile[/url]

Sets values that users are able to set under the "settings/profile" tab of their settings page. Only the parameters specified will be updated.

[i]Usage[/i]: #SITE_URL#1/account/update_profile.format
[i]Method[/i]: POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-account%C2%A0update_profile]http://apiwiki.twitter.com/Twitter-REST-API-Method:-account-update_profile[/url]
[i]Additional Parameters[/i]: birthdate(format: YYYY-DD-MM), gender(m or f), tags(comma separated strings)
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-account%C2%A0update_profile]http://apiwiki.twitter.com/Twitter-REST-API-Method:-account-update_profile[/url]

[b]Example[/b]: #SITE_URL#1/account/update_profile.xml?birthdate=2000-12-12',
  'api_doc_cat_8_post_5_title' => 'account/add_feed',
  'api_doc_cat_8_post_5_text' => 'Adding an rss feed to the authenticated user\'s profile.

[i]Usage[/i]: #SITE_URL#1/account/add_feed.format
[i]Method[/i]: POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: url(valid, urlencoded url), filter(keywords to filter (comma separated))
[i]Response[/i]:
[b]&lt feed&gt
feed parameters:id, feed_url, feed_title, filter_keywords
&lt user&gt user parameters: &lt/user&gt
&lt/feed&gt[/b]

[b]Example[/b]: #SITE_URL#1/account/add_feed.xml?url=link_to_rss_feed&filter=business,money',
  'api_doc_cat_8_post_6_title' => 'account/delete_feed',
  'api_doc_cat_8_post_6_text' => 'Deleting an rss feed from the authenticated user\'s profile.

[i]Usage[/i]: #SITE_URL#1/account/delete_feed/id.format
[i]Method[/i]: POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: feed_id
[i]Response[/i]:
[b]&lt feed&gt
feed parameters:id, feed_url, feed_title, filter_keywords
&lt user&gt user parameters: &lt/user&gt
&lt/feed&gt[/b]

[b]Example[/b]: #SITE_URL#1/account/delete_feed/1234.xml',
  'api_doc_cat_9_post_num' => '6',
  'api_doc_cat_9_post_1_title' => 'groups/follow',
  'api_doc_cat_9_post_1_text' => 'Follow selected group of users

[i]Usage[/i]: #SITE_URL#1/groups/follow/id.format
[i]Method[/i]: POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: group_id, groupname
[i]Response[/i]:
[b]&lt user&gt user parameters: &lt/user&gt[/b]

[b]Example[/b]: #SITE_URL#1/groups/follow/1234.xml
[b]Example[/b]: #SITE_URL#1/groups/follow/my_group.xml',
  'api_doc_cat_9_post_2_title' => 'groups/unfollow',
  'api_doc_cat_9_post_2_text' => 'Unfollow selected group of users

[i]Usage[/i]: #SITE_URL#1/groups/unfollow/id.format
[i]Method[/i]: POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: group_id, groupname
[i]Response[/i]:
[b]&lt user&gt user parameters: &lt/user&gt[/b]

[b]Example[/b]: #SITE_URL#1/groups/unfollow/1234.xml
[b]Example[/b]: #SITE_URL#1/groups/unfollow/my_group.xml',
  'api_doc_cat_9_post_3_title' => 'groups/membership',
  'api_doc_cat_9_post_3_text' => 'List with all members of a group

[i]Usage[/i]: #SITE_URL#1/groups/membership/id.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: group_id, groupname
[i]Response[/i]:
[b]&lt users&gt user parameters: &lt/users&gt[/b]

[b]Example[/b]: #SITE_URL#1/groups/membership/1234.xml
[b]Example[/b]: #SITE_URL#1/groups/membership/my_group.xml',
  'api_doc_cat_9_post_4_title' => 'groups/all_groups',
  'api_doc_cat_9_post_4_text' => 'List with the titles of all public groups in the network

[i]Usage[/i]: #SITE_URL#1/groups/all_groups.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: none
[i]Response[/i]:
[b]&lt groups&gt
&lt id&gt group_id &lt/id&gt
&lt name&gt group_name &lt/name&gt
user parameters:
&lt/groups&gt[/b]

[b]Example[/b]: #SITE_URL#1/groups/all_groups.json',
  'api_doc_cat_9_post_5_title' => 'groups/create',
  'api_doc_cat_9_post_5_text' => 'Authenticated user creates a group.

[i]Usage[/i]: #SITE_URL#1/groups/create.format
[i]Method[/i]: POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: title, groupname(3,30 chars), description, type(public, private)
[i]Response[/i]:
[b]&lt groups&gt
&lt id&gt group_id &lt/id&gt
&lt name&gt group_name &lt/name&gt
user parameters:
&lt/groups&gt[/b]

[b]Example[/b]: #SITE_URL#1/groups/create.json?title=my%20group&groupname=group&description=fancy%20group&type=public',
  'api_doc_cat_9_post_6_title' => 'groups/destroy',
  'api_doc_cat_9_post_6_text' => 'Authenticated user destroys a group.

[i]Usage[/i]: #SITE_URL#1/groups/destroy/id.format
[i]Method[/i]: POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: group_id, groupname
[i]Response[/i]:
[b]&lt groups&gt
&lt id&gt group_id &lt/id&gt
&lt name&gt group_name &lt/name&gt
user parameters:
&lt/groups&gt[/b]

[b]Example[/b]: #SITE_URL#1/groups/destroy/my_group_name.json',
  'api_doc_cat_10_post_num' => '3',
  'api_doc_cat_10_post_1_title' => 'Favoritter',
  'api_doc_cat_10_post_1_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-favorites]http://apiwiki.twitter.com/Twitter-REST-API-Method:-favorites[/url]

Latest 20 favorite statuses for the authenticating user parameter.

[i]Usage[/i]: #SITE_URL#1/favorites.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-favorites]http://apiwiki.twitter.com/Twitter-REST-API-Method:-favorites[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-favorites]http://apiwiki.twitter.com/Twitter-REST-API-Method:-favorites[/url]

[b]Example[/b]: #SITE_URL#1/favorites.xml',
  'api_doc_cat_10_post_2_title' => 'favorites/create',
  'api_doc_cat_10_post_2_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-favorites%C2%A0create]http://apiwiki.twitter.com/Twitter-REST-API-Method:-favorites-create[/url]

Latest 20 favorite statuses for the authenticating user parameter.

[i]Usage[/i]: #SITE_URL#1/favorites/create/id.format
[i]Method[/i]: POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-favorites%C2%A0create]http://apiwiki.twitter.com/Twitter-REST-API-Method:-favorites-create[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-favorites%C2%A0create]http://apiwiki.twitter.com/Twitter-REST-API-Method:-favorites-create[/url]

[b]Example[/b]: #SITE_URL#1/favorites/create/1234.xml',
  'api_doc_cat_10_post_3_title' => 'favorites/destroy',
  'api_doc_cat_10_post_3_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-favorites%C2%A0destroy]http://apiwiki.twitter.com/Twitter-REST-API-Method:-favorites-destroy[/url]

[i]Usage[/i]: #SITE_URL#1/favorites/destroy/id.format
[i]Method[/i]: POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-favorites%C2%A0destroy]http://apiwiki.twitter.com/Twitter-REST-API-Method:-favorites-destroy[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-favorites%C2%A0destroy]http://apiwiki.twitter.com/Twitter-REST-API-Method:-favorites-destroy[/url]

[b]Example[/b]: #SITE_URL#1/favorites/destroy/1234.xml',
  'api_doc_cat_11_post_num' => '4',
  'api_doc_cat_11_post_1_title' => 'saved_searches',
  'api_doc_cat_11_post_1_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches]http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches[/url]

Returns the authenticated user\'s saved search queries.

[i]Usage[/i]: #SITE_URL#1/saved_searches.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches]http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches]http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches[/url]

[b]Example[/b]: #SITE_URL#1/saved_searches.xml',
  'api_doc_cat_11_post_2_title' => 'saved_searches/show',
  'api_doc_cat_11_post_2_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches-show]http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches-show[/url]

Retrieves the data for a saved search by the authenticated user specified by the given id.

[i]Usage[/i]: #SITE_URL#1/saved_searches/show/id.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: true
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches-show]http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches-show[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches-show]http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches-show[/url]

[b]Example[/b]: #SITE_URL#1/saved_searches/show/1234.xml',
  'api_doc_cat_11_post_3_title' => 'saved_searches/create',
  'api_doc_cat_11_post_3_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches-create]http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches-create[/url]

Creates a saved search for the authenticated user.

[i]Usage[/i]: #SITE_URL#1/saved_searches/create.format
[i]Method[/i]: POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches-create]http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches-create[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches-create]http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches-create[/url]

[b]Example[/b]: #SITE_URL#1/saved_searches/create.xml?query=my%20search',
  'api_doc_cat_11_post_4_title' => 'saved_searches/destroy',
  'api_doc_cat_11_post_4_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches-destroy]http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches-destroy[/url]

Destroys a saved search for the authenticated user. The search specified by id must be owned by the authenticating user.

[i]Usage[/i]: #SITE_URL#1/saved_searches/destroy/id.format
[i]Method[/i]: POST
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: true
[i]API rate limited[/i]: false
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches-destroy]http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches-destroy[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches-destroy]http://apiwiki.twitter.com/Twitter-REST-API-Method:-saved_searches-destroy[/url]

[b]Example[/b]: #SITE_URL#1/saved_searches/destroy/id.xml',
  'api_doc_cat_12_post_num' => '3',
  'api_doc_cat_12_post_1_title' => 'oauth/request_token',
  'api_doc_cat_12_post_1_text' => 'You can request a token by following this procedure [url=http://oauth.net/core/1.0/#auth_step1]http://oauth.net/core/1.0/#auth_step1[/url]

In [b]#SITE_TITLE#[/b] we don\'t offer the HTTPS protocol for data transfer, which means all access requests to a given resource has to be encrypted via [b]HMAC-SHA1[/b] and you need a signature for every page.

[i]Method[/i]: GET

Parameters to be submitted:
[b]oauth_consumer_key,
oauth_nonce,
oauth_signature_method,
oauth_timestamp,
oauth_version(OPTIONAL)[/b]

Example how to implement the code of the page request_token (In PHP):

-------------------------------------
[i]
$request_url = "http://example.com/oauth/request_token";

$oauth_timestamp = time();
$nonce = md5(rand().time().rand());

$parameters = "oauth_consumer_key=".urlencode(utf8_encode($oauth_consumer_key))
$parameters .="&oauth_nonce=".urlencode(utf8_encode($nonce));
$parameters .= "&oauth_signature_method=".urlencode(utf8_encode("HMAC-SHA1"));
$parameters .="&oauth_timestamp=".urlencode(utf8_encode($oauth_timestamp));
$parameters .= "&oauth_version=".urlencode(utf8_encode("1.0"));

$resource_string = "GET&".urlencode(utf8_encode($request_url))."&".urlencode(utf8_encode($parameters));

$oauth_signature =  base64_encode(hash_hmac("sha1", $resource_string, $oauth_consumer_secret."&", true));

$request_body = $request_url."?oauth_nonce=".$nonce."&oauth_timestamp=".$oauth_timestamp;
$request_body .="&oauth_consumer_key=".$oauth_consumer_key;
$request_body .= "&oauth_signature_method=".urlencode(utf8_encode("HMAC-SHA1"));
$request_body .="&oauth_signature=".$oauth_signature."&oauth_version=1.0";

$my_request = curl_init();
curl_setopt($my_request, CURLOPT_URL, $request_body);
curl_setopt($my_request, CURLOPT_RETURNTRANSFER, TRUE);
$request_result = curl_exec($my_request);
curl_close($my_request);
[/i]
-------------------------------------

For successful request server will return:

[i]oauth_token_secret=GENERATED_TOKEN_SECRET&oauth_token=GENERATED_REQUEST_TOKEN
&oauth_callback_confirmed=true[/i]',
  'api_doc_cat_12_post_2_title' => 'oauth/authenticate',
  'api_doc_cat_12_post_2_text' => 'You can authorize a token by following this procedure [url=http://oauth.net/core/1.0/#auth_step2]http://oauth.net/core/1.0/#auth_step2[/url]

[i]Method[/i]: GET

Once you have received the data from [b]oauth/request_token[/b] use [b]oauth_token[/b] as GET parameter and transfer to the [b]oauth/request_token[/b] page. Here the user must give authorization that your application may use his personal data. The authorization is made by manually entering the username and password from the user, or if he is still logged into the system by pressing [b]Allow[/b].

If the user successfully authorizes your application then:

* If [b]you use callback[/b] #SITE_TITLE API will automatically redirect user to the address specified for the callback.
* If not then the screen will display the [b]code verifier[/b], which the user has to submit to your application.

If you use the callback url the GET parameter will be submitted as [b]oauth_verifier[/b].',
  'api_doc_cat_12_post_3_title' => 'oauth/access_token',
  'api_doc_cat_12_post_3_text' => 'You can get an access token by following this procedure [url=http://oauth.net/core/1.0/#anchor29]http://oauth.net/core/1.0/#anchor29[/url].

In [b]#SITE_TITLE#[/b] we don\'t offer the HTTPS protocol for data transfer, which means all access requests to a given resource has to be encrypted via [b]HMAC-SHA1[/b] and you need a signature for every page.

[i]Method[/i]: POST

Parameters to be submitted:
[b]oauth_consumer_key,
oauth_nonce,
oauth_signature_method,
oauth_timestamp,
oauth_token(The Request Token from oauth/request_token),
oauth_version(OPTIONAL)[/b]

Example how to implement the code of the page access_token:

-------------------------------------
[i]
$request_url = "http://example.com/oauth/access_token";

$oauth_timestamp = time();
$nonce = md5(rand().time().rand());
$t_secret = TOKEN_SECRET_RECEIVED_FROM_oauth/request_token;

$parameters = "oauth_consumer_key=".urlencode(utf8_encode($oauth_consumer_key));
$parameters .="&oauth_nonce=".urlencode(utf8_encode($nonce));
$parameters .= "&oauth_signature_method=".urlencode(utf8_encode("HMAC-SHA1"));
$parameters .="&oauth_timestamp=".urlencode(utf8_encode($oauth_timestamp));
$parameters .= "&oauth_token=".urlencode(utf8_encode($_GET["oauth_token"]));
$parameters .="&oauth_version=".urlencode(utf8_encode("1.0"));

$base = "POST&".urlencode(utf8_encode($request_url))."&".urlencode(utf8_encode($parameters));

$oauth_signature =  base64_encode(hash_hmac("sha1", $base, urlencode($oauth_consumer_secret)."&".urlencode($t_secret), true));

$request_body = "oauth_nonce=".$nonce."&oauth_timestamp=".$oauth_timestamp;
$request_body .="&oauth_consumer_key=".$oauth_consumer_key;
$request_body .= "&oauth_signature_method=".urlencode(utf8_encode("HMAC-SHA1"));
$request_body .="&oauth_signature=".$oauth_signature."&oauth_version=1.0";
$request_body .= "&oauth_verifier=".urlencode(utf8_encode($_GET["oauth_verifier"]));
$request_body .="&oauth_token=".urlencode(utf8_encode($_GET["oauth_token"]));

//$_GET["oauth_verifier"] = oauth_verifier_received_from_oauth/authenticate

$my_request = curl_init();
curl_setopt($my_request, CURLOPT_URL, $request_url);
curl_setopt($my_request, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($my_request, CURLOPT_POST, 1);
curl_setopt($my_request, CURLOPT_POSTFIELDS, $request_body);
$access_request_result = curl_exec($my_request);
curl_close($my_request);
[/i]
-------------------------------------

For successful application server will return:

[i]oauth_token_secret=YOUR_TOKEN_SECRET&oauth_token=GENERATED_ACCESS_TOKEN[/i]

When you obtain an access token you could start [url=#SITE_URL#api/documentation/show:13]using user\'s data[/url]',
  'api_doc_cat_12_post_4_title' => 'Oauth 1.0a updates',
  'api_doc_cat_12_post_4_text' => 'In regard with the sharetronix transition into OAuth 1.0a, when the OAuth protocol is used for authentication, there were some changes made for generating a resource signature on the third step(access token) of the OAuth process. Now it is mandatory to include the verifier received on the second step in the signature\'s body and the parameter\'s name should be oauth_verifier. This is how the parameters order should look like in this step of the process.

$parameters = \'oauth_consumer_key=\'.urlencode(utf8_encode($oauth_consumer_key));
$parameters .= \'&oauth_nonce=\'.urlencode(utf8_encode($nonce));
$parameters .= \'&oauth_signature_method=\'.urlencode(utf8_encode("HMAC-SHA1"));
$parameters .= \'&oauth_timestamp=\'.urlencode(utf8_encode($oauth_timestamp));
$parameters .= \'&oauth_token=\'.urlencode(utf8_encode($_GET[\'oauth_token\']));
$parameters .= \'&oauth_verifier=\'.urlencode(utf8_encode($_GET[\'oauth_verifier\']));
$parameters .= \'&oauth_version=\'.urlencode(utf8_encode(\'1.0\'));

$resource_string = \'POST&\'.urlencode(utf8_encode($request_url)).\'&\'.urlencode(utf8_encode($parameters));
$oauth_signature =  base64_encode(hash_hmac(\'sha1\', $resource_string, urlencode($oauth_consumer_secret).\'&\'.urlencode($t_secret), true));',
  'api_doc_cat_13_post_num' => '1',
  'api_doc_cat_13_post_1_title' => 'Bruker brukerens data',
  'api_doc_cat_13_post_1_text' => 'Protected data needs authorization (to get autorization use OAuth Methods) by its owner to be accessed. Example: statuses/update, statuses/destroy, groups/create etc.

When there is a request to your application for a given resource you can use the same scheme as for [b]request_token[/b], [b]access_token[/b], with the difference that all parameters must be located in the [b]Authorization Header[/b].  More information is available here: [url=http://oauth.net/core/1.0/#auth_header]http://oauth.net/core/1.0/#auth_header[/url]

[i]realm[/i] is not a mandatory parameter.

[i]Method[/i]: POST or GET

Parameters to be submitted:
[b]oauth_consumer_key,
oauth_nonce,
oauth_signature_method,
oauth_timestamp,
oauth_token(The Access Token from oauth/access_token),
oauth_version(OPTIONAL)[/b]

Example how to implement the code of the page access_token:

-------------------------------------
[i]
$request_url = "http://example.com/1/statuses/update.xml";
//for base string use the method name {/action}.{data_format}:
//http://example.com/1/groups {/action}.{data_format}
//http://example.com/1/saved_searches {/action}.{data_format}
//http://example.com/1/favorites {/action}.{data_format}
//http://example.com/1/users {/action}.{data_format}
//etc.

$oauth_timestamp = time();
$nonce = md5(rand().time().rand());

$parameters = "oauth_consumer_key=".urlencode(utf8_encode($oauth_consumer_key));
$parameters .="&oauth_nonce=".urlencode(utf8_encode($nonce));
$parameters .= "&oauth_signature_method=".urlencode(utf8_encode("HMAC-SHA1"));
$parameters .="&oauth_timestamp=".urlencode(utf8_encode($oauth_timestamp));
$parameters .= "&oauth_token=".urlencode(utf8_encode($res[1]));
$parameters .="&oauth_version=".urlencode(utf8_encode("1.0"));

$resource_string = "POST&".urlencode(utf8_encode($request_url))."&".urlencode(utf8_encode($parameters));

$oauth_signature = $oauth_consumer_secret;
$sig =  base64_encode(hash_hmac("sha1", $resource_string,
urlencode($oauth_signature)."&".urlencode($t_secret), true));

$headers = array(
	"Authorization: OAuth
	realm=\\"api.example.com\\",
	oauth_consumer_key=\\"".urlencode($oauth_consumer_key)."\\",
	oauth_nonce=\\"".urlencode($nonce)."\\",
	oauth_signature_method=\\"".urlencode("HMAC-SHA1")."\\",
	oauth_timestamp=\\"".urlencode($oauth_timestamp)."\\",
	oauth_token=\\"".urlencode(utf8_encode($res[1]))."\\",
	oauth_version=\\"".urlencode("1.0")."\\",
	oauth_signature=\\"".urlencode(utf8_encode($sig))."\\""
	);

$my_request = curl_init();
curl_setopt($my_request, CURLOPT_URL, $request_url);
curl_setopt($my_request, CURLOPT_HTTPHEADER, $headers);
curl_setopt($my_request, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($my_request, CURLOPT_POST, 1);
curl_setopt($my_request, CURLOPT_POSTFIELDS, "suppress_response_codes=1&status=let%20us%20see%20it%20again");
$contacts_request_result = curl_exec($my_request);
curl_close($my_request);
[/i]
-------------------------------------',
  'api_doc_cat_14_post_num' => '1',
  'api_doc_cat_14_post_1_title' => 'help/test',
  'api_doc_cat_14_post_1_text' => '[url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-help%C2%A0test]http://apiwiki.twitter.com/Twitter-REST-API-Method:-help-test[/url]

Returns the string OK in the requested format with a 200 OK HTTP status code.

[i]Usage[/i]: #SITE_URL#1/help/test.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: false
[i]API rate limited[/i]: false
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-help%C2%A0test]http://apiwiki.twitter.com/Twitter-REST-API-Method:-help-test[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-REST-API-Method:-help%C2%A0test]http://apiwiki.twitter.com/Twitter-REST-API-Method:-help-test[/url]

[b]Example[/b]: #SITE_URL#1/help/test.xml',
  'api_doc_cat_15_post_num' => '1',
  'api_doc_cat_15_post_1_title' => 'Søk',
  'api_doc_cat_15_post_1_text' => '[url=http://apiwiki.twitter.com/Twitter-Search-API-Method:-search]http://apiwiki.twitter.com/Twitter-Search-API-Method:-search[/url]

Returns posts that match a specified query.

[i]Usage[/i]: #SITE_URL#1/search.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: false
[i]API rate limited[/i]: true
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-Search-API-Method:-search]http://apiwiki.twitter.com/Twitter-Search-API-Method:-search[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-Search-API-Method:-search]http://apiwiki.twitter.com/Twitter-Search-API-Method:-search[/url]

[b]Example[/b]: #SITE_URL#1/search.json?q=text',
  'api_doc_cat_16_post_num' => '1',
  'api_doc_cat_16_post_1_title' => 'trends/current and trends/top10',
  'api_doc_cat_16_post_1_text' => '[url=http://apiwiki.twitter.com/Twitter-Search-API-Method:-trends]http://apiwiki.twitter.com/Twitter-Search-API-Method:-trends[/url]

Latest 10 topics that are currently trending on [b]#SITE_TITLE#[/b].  It includes the time of the request, the name of each trend, and the url to the Search results page for that topic.

[i]Usage[/i]: #SITE_URL#1/trends.format
[i]Method[/i]: GET
[i]Supported Formats[/i]: XML, JSON
[i]Requires Authentication[/i]: false
[i]API rate limited[/i]: true
[i]Parameters[/i]: [url=http://apiwiki.twitter.com/Twitter-Search-API-Method:-trends]http://apiwiki.twitter.com/Twitter-Search-API-Method:-trends[/url]
[i]Response[/i]: [url=http://apiwiki.twitter.com/Twitter-Search-API-Method:-trends]http://apiwiki.twitter.com/Twitter-Search-API-Method:-trends[/url]

[b]Example[/b]: #SITE_URL#1/trends/top10.json',
  'api_doc_params_to_subm' => 'Parametre so vil bli sendt inn',
)

?>
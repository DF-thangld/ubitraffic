var parking_places = [	{name:'Technopolis',latitude:'65.007913000000002',longitude:'25.469227'},
						{name:'Raksilan Marketit',latitude:'65.010668999999993',longitude:'25.490770000000001'},
						{name:'Sairaalaparkki',latitude:'65.008556999999996',longitude:'25.514351999999999'},
						{name:'Torinranta',latitude:'65.013969000000003',longitude:'25.465364000000001'},
						{name:'Autotori',latitude:'65.010959',longitude:'25.465707999999999'},
						{name:'Autoheikki',latitude:'65.015576999999993',longitude:'25.477681'},
						{name:'Autosaari',latitude:'65.010270000000006',longitude:'25.472788999999999'},
						{name:'Stockmann',latitude:'65.011720999999994',longitude:'25.469011999999999'},
						{name:'Ouluhalli',latitude:'65.007260000000002',longitude:'25.502528999999999'},
						{name:'Linja-autoasema',latitude:'65.009834999999995',longitude:'25.484933999999999'}];

var weather_places = [	{name:'Tie 4 Ouluntulli',latitude:'64.947356998755623',longitude:'25.53207547182285'},
						{name:'Tie 5 Petäjälampi',latitude:'66.013451220580365',longitude:'29.144628882916795'},
						{name:'Tie 5 Kuusamo, Kitka',latitude:'66.305259393760707',longitude:'28.898879297665871'},
						{name:'Tie 8 Himanka',latitude:'64.042050328011939',longitude:'23.638821090807184'},
						{name:'Tie 8 Pattijoki',latitude:'64.686314758675564',longitude:'24.561608342332406'},
						{name:'Tie 8 Siikajoki, Revonlahti',latitude:'64.717002739659407',longitude:'24.950989601753509'},
						{name:'Tie 4 Liminka',latitude:'64.784827429792173',longitude:'25.358431768606128'},
						{name:'Tie 5 Kajaani, Hatulanmäki',latitude:'63.99568865260408',longitude:'27.40539536209511'},
						{name:'Tie 5 Ristijärvi, Roukajok',latitude:'64.576410775038411',longitude:'28.403668914252787'},
						{name:'Tie 5 Suomussalmi, Palovaa',latitude:'65.146769374138913',longitude:'28.939514862424772'},
						{name:'Tie 5 Paltamo, Rytivaara',latitude:'64.31575268294884',longitude:'28.038640047305641'},
						{name:'Tie 5 Pisto',latitude:'65.4258024225025',longitude:'29.037922320415728'},
						{name:'Tie 4 Liminka, Haaransilta',latitude:'64.815729149548801',longitude:'25.486265414499083'},
						{name:'Tie 8 Kalajoki',latitude:'64.337239484665346',longitude:'23.958609159437742'},
						{name:'Tie 8 Parhalahti',latitude:'64.489751409875524',longitude:'24.325249007469051'},
						{name:'Tie 20 Taivalkoski',latitude:'65.592331000153337',longitude:'28.300531898450437'},
						{name:'Tie 27 Oksava',latitude:'63.813318107685163',longitude:'25.237209543416757'},
						{name:'Tie 78 Pudasjärvi, Kokkoky',latitude:'65.69036975133767',longitude:'26.676508496192788'},
						{name:'Tie 86 Oulainen, Piipsjärv',latitude:'64.309458454659747',longitude:'24.911645404804897'},
						{name:'Tie 88 Pyhäntä, Ahokylä',latitude:'63.989229597595624',longitude:'26.552213887209426'},
						{name:'Tie 816 Hailuoto',latitude:'65.034328565427316',longitude:'24.776133954478833'},
						{name:'Tie 5 Kajaani, Loikkala',latitude:'64.288993592224401',longitude:'27.94717785752189'},
						{name:'Tie 4 Oulu, Intiö',latitude:'65.020590752699931',longitude:'25.506091752368626'},
						{name:'Tie 4 Haukipudas',latitude:'65.174476849579051',longitude:'25.425040172773517'},
						{name:'Tie 20 Ylikiiminki, Arkala',latitude:'65.189332134200313',longitude:'26.191431296822977'},
						{name:'Tie 20 Pudasjärvi, Pintamo',latitude:'65.449344584757995',longitude:'27.623231996447181'},
						{name:'Tie 22 Vaala, Liminpuro',latitude:'64.533616219559349',longitude:'27.241153196991487'},
						{name:'Tie 78 Puolanka, Paasikosk',latitude:'65.033631800344679',longitude:'27.701685999371211'},
						{name:'Tie 8 Pattijoki',latitude:'64.686314758675564',longitude:'24.561608342332406'},
						{name:'Tie 4 Oulu, Kuivasjärvi',latitude:'65.078228962868536',longitude:'25.440024378581025'},
						{name:'Tie 5 Kajaani, Nuottijärvi',latitude:'64.162706569840125',longitude:'27.524570781260174'},
						{name:'Tie 5 Suomussalmi',latitude:'64.786712695707735',longitude:'28.843624600681316'},
						{name:'Tie 4 Haukipudas',latitude:'65.174476849579051',longitude:'25.425040172773517'},
						{name:'Tie 4 Ii, Olhava',latitude:'65.412365486654068',longitude:'25.385405818125886'},
						{name:'Tie 4 Kuivaniemi',latitude:'65.547272046415898',longitude:'25.241782352957692'},
						{name:'Tie 5 Petäjälampi',latitude:'66.013451220580365',longitude:'29.144628882916795'},
						{name:'Tie 20 Hönttämäki',latitude:'65.05234715152487',longitude:'25.598655238682046'},
						{name:'Tie 20 Hönttämäki',latitude:'65.05234715152487',longitude:'25.598655238682046'},
						{name:'Tie 20 Taivalkoski',latitude:'65.592331000153337',longitude:'28.300531898450437'},
						{name:'Tie 20 Kuusamo, Kuolio',latitude:'65.82612628487712',longitude:'28.815893417469209'},
						{name:'Tie 22 Pikkarala',latitude:'64.884113706878807',longitude:'25.840457365144498'},
						{name:'Tie 22 Pikkarala',latitude:'64.884113706878807',longitude:'25.840457365144498'},
						{name:'Tie 22 Utajärvi, Sotka',latitude:'64.781171234445196',longitude:'26.267424129635568'},
						{name:'Tie 27 Ylivieska, Raudasky',latitude:'64.002907707484468',longitude:'24.744202226272176'},
						{name:'Tie 6 Sotkamo, Korholanmäk',latitude:'64.142282875979134',longitude:'27.997047758612464'},
						{name:'Tie 28 Kokkosuo',latitude:'64.113975649083983',longitude:'26.961784283406526'},
						{name:'Tie 76 Kuhmo, Tervasalmi',latitude:'64.084934138806943',longitude:'29.10483881147427'},
						{name:'Tie 8 Himanka',latitude:'64.042050328011939',longitude:'23.638821090807184'},
						{name:'Tie 4 Temmes',latitude:'64.657307541180174',longitude:'25.615993934480251'},
						{name:'Tie 4 Liminka, Haaransilta',latitude:'64.815729149548801',longitude:'25.486265414499083'},
						{name:'Tie 89 Kuhmo, Kuusamonkylä',latitude:'64.433481777456478',longitude:'29.334533104387514'},
						{name:'Tie 4 Kärsämäki',latitude:'64.006449886020633',longitude:'25.755651057073624'},
						{name:'Tie 4 Pulkkila, Hyttikoski',latitude:'64.395210460409274',longitude:'25.797560896386319'},
						{name:'Tie 4 Temmes',latitude:'64.657307541180174',longitude:'25.615993934480251'},
						{name:'Tie 4 Kontinkangas',latitude:'65.004511033041481',longitude:'25.507541679398013'},
						{name:'Tie 4 Oulu, Intiö',latitude:'65.020590752699931',longitude:'25.506091752368626'},
						{name:'Tie 4 Pyhäjärvi, Vaskikell',latitude:'63.711424964880905',longitude:'25.917544267601226'},
						{name:'Tie 4 Pulkkila',latitude:'64.281583470313038',longitude:'25.875745200278921'},
						{name:'Tie 4 Kempele, Ouluntulli',latitude:'64.947356998755623',longitude:'25.53207547182285'},
						{name:'Tie 4 Pudasjärvi',latitude:'65.275586497438809',longitude:'26.847335542817554'},
						{name:'Tie 86 Ruukki, Paavola',latitude:'64.605690024596896',longitude:'25.209807047805608'},
						{name:'Tie 4 Oulu, Kuivasjärvi',latitude:'65.078228962868536',longitude:'25.440024378581025'},
						{name:'Tie 5 Jokivaara',latitude:'65.721322200694942',longitude:'29.151568426907801'}];

var camera_places = [	{name:'Tie 849 Yli-Ii, Tannila (Yli-Iihin)',latitude:'65.482777999999996',longitude:'25.975166999999999'},
							{name:'Tie 4 Oulu, Intiö',latitude:'65.012014620000002',longitude:'25.50644685'},
							{name:'Tie 4 Oulu, Laanila (Jyväskylään)',latitude:'65.02703262',longitude:'25.507368490000001'},
							{name:'Tie 20 Hönttämäki (Kuusamoon)',latitude:'65.052443999999994',longitude:'25.598721999999999'},
							{name:'Tie 22 Oulu, Maikkula (Ouluun)',latitude:'64.988221999999993',longitude:'25.542860999999998'},
							{name:'Tie 22 Muhos, Hyrkäs (Kajaaniin)',latitude:'64.791911999999996',longitude:'26.077438999999998'},
							{name:'Tie 20 Kiiminki, Välikylä (Kuusamoon)',latitude:'65.069610999999995',longitude:'25.652083000000001'},
							{name:'Tie 20 Oulu, Hintta (Kuusamoon)',latitude:'65.029750000000007',longitude:'25.527277999999999'},
							{name:'Tie 4 Oulu, Kontinkangas (Jyväskylään)',latitude:'65.004507520000004',longitude:'25.507523679999998'},
							{name:'Tie 20 Ylikiiminki, Arkala (Ouluun)',latitude:'65.189333000000005',longitude:'26.191417000000001'},
							{name:'Tie 4 Oulu, Kiviniemi',latitude:'64.963250000000002',longitude:'25.514472000000001'},
							{name:'Tie 4 Liminka, Haaransilta (Jyväskylään / Kokkolaan)',latitude:'64.815721999999994',longitude:'25.486249999999998'},
							{name:'Tie 4 Kempele, Zeppelin',latitude:'64.905582999999993',longitude:'25.536611000000001'},
							{name:'Tie 20 Korvensuora (Kuusamoon)',latitude:'65.045721999999998',longitude:'25.578527999999999'},
							{name:'Tie 816 Oulunsalo, Lentokentäntie (Ouluun)',latitude:'64.937832999999998',longitude:'25.432721999999998'},
							{name:'Tie 4 Ii (Kemiin)',latitude:'65.338639000000001',longitude:'25.367583'},
							{name:'Tie 4 Alatemmes (Jyväskylään)',latitude:'64.737611000000001',longitude:'25.568694000000001'},
							{name:'Tie 4 Liminka, Tupos',latitude:'64.864444000000006',longitude:'25.518388999999999'},
							{name:'Tie 4 Kuivaniemi (Kemiin)',latitude:'65.607917',longitude:'25.180111'},
							{name:'Tie 815 Limingantie (Tie 847 Ouluun)',latitude:'64.955962',longitude:'25.504702999999999'},
							{name:'Tie 816 Oulunsalo, lautta (Oulunsalon Lautalle)',latitude:'65.006083000000004',longitude:'25.203582999999998'},
							{name:'Tie 4 Oulu, Linnanmaa',latitude:'65.055333000000005',longitude:'25.449027999999998'},
							{name:'Tie 20 Oulu, Rusko (Kuusamoon)',latitude:'65.037610999999998',longitude:'25.553111000000001'},
							{name:'Tie 4 Oulu, Lintula (Ouluun)',latitude:'64.986000000000004',longitude:'25.505193999999999'}];

var traffic_info_places = [	{id:'1225',name:'Tie 78 Pudasjärvi',latitude:'65.669089',longitude:'26.689723999999998'},
							{id:'1230',name:'Tie 20 Pudasjärvi, Rankkil',latitude:'65.265730000000005',longitude:'26.730778999999998'},
							{id:'1253',name:'Tie 816 Hailuoto',latitude:'65.041117999999997',longitude:'25.054483999999999'},
							{id:'1321',name:'Tie 5 Hatulanmäki',latitude:'63.995545',longitude:'27.404399000000002'},
							{id:'1324',name:'Tie 22 Törmäkylä',latitude:'64.604788999999997',longitude:'26.649813000000002'},
							{id:'1325',name:'Tie 89 Vartius',latitude:'64.493798999999996',longitude:'29.900607000000001'},
							{id:'1327',name:'Tie 78 Arska',latitude:'65.046685999999994',longitude:'27.711946000000001'},
							{id:'1328',name:'Tie 5 Tapiola',latitude:'65.365616000000003',longitude:'29.030241'},
							{id:'1204',name:'Tie 8 Raahe',latitude:'64.686288219334557',longitude:'24.561631692986893'},
							{id:'1241',name:'Tie 22 Maikkula',latitude:'64.988709',longitude:'25.541627999999999'},
							{id:'1303',name:'Tie 75 Kankivaara',latitude:'63.994613000000001',longitude:'29.636963000000002'},
							{id:'1205',name:'Tie 86 Liminka',latitude:'64.710191124471407',longitude:'25.380470114787094'},
							{id:'1223',name:'Tie 20 Kiiminki',latitude:'65.065201965547189',longitude:'25.637468024141842'},
							{id:'1227',name:'Tie 22 Muhos',latitude:'64.854489997300007',longitude:'25.88702503830908'},
							{id:'1233',name:'Tie 88 Vihanti, Alpua',latitude:'64.453850118236687',longitude:'25.149824712393407'},
							{id:'1234',name:'Tie 5 Kuusamo, Toranki',latitude:'65.947399392494759',longitude:'29.164357690545724'},
							{id:'1251',name:'Tie 4 Oulu, Lintula',latitude:'64.968237119771317',longitude:'25.504502351750212'},
							{id:'1237',name:'Tie 4 Oulu, Intiö',latitude:'65.021101000000002',longitude:'25.508303000000002'},
							{id:'1231',name:'Tie 4 Kello',latitude:'65.123012000000003',longitude:'25.430520000000001'},
							{id:'1247',name:'Tie 847 Oulunlahti',latitude:'64.972211000000001',longitude:'25.490000999999999'},
							{id:'1103',name:'Tie 28 Sievi',latitude:'63.872090999999998',longitude:'24.735395'},
							{id:'1224',name:'Tie 20 Pudasjärvi, Pintamo',latitude:'65.440391000000005',longitude:'27.606117000000001'},
							{id:'1329',name:'Tie 22 Paltamo, Mieslahti',latitude:'64.391463217983002',longitude:'27.953234562442049'},
							{id:'1248',name:'Tie 815 Oulunsalo',latitude:'64.935876946666426',longitude:'25.413282330417491'},
							{id:'1249',name:'Tie 815 Lunki',latitude:'64.93367735340729',longitude:'25.398036246000736'},
							{id:'1229',name:'Tie 4 Ii, Kuivaniemi',latitude:'65.545678592240037',longitude:'25.238209886965389'},
							{id:'1232',name:'Tie 20 Kettumäki',latitude:'65.416476533090261',longitude:'27.422359359494411'},
							{id:'1243',name:'Tie 4 Luhasto',latitude:'64.842757617018933',longitude:'25.503759009627256'},
							{id:'1226',name:'Tie 4 Kempele',latitude:'64.912485000000004',longitude:'25.536391999999999'},
							{id:'1239',name:'Tie 815 Oulunsalo',latitude:'64.950441095438748',longitude:'25.49142215700838'},
							{id:'1104',name:'Tie 27 Alavieska',latitude:'64.12481126446626',longitude:'24.351927075550066'},
							{id:'1105',name:'Tie 27 Haapajärvi',latitude:'63.744337014041577',longitude:'25.605659752656535'},
							{id:'1121',name:'Tie 4 Kärsämäki',latitude:'63.946910124972888',longitude:'25.803216600373588'},
							{id:'1123',name:'Tie 86 Oulainen',latitude:'64.242832749204169',longitude:'24.830053135063313'},
							{id:'1228',name:'Tie 847 Haukipudas',latitude:'65.118739912662733',longitude:'25.373075175243365'},
							{id:'1252',name:'Tie 866 Kuusamo',latitude:'65.772866260295658',longitude:'30.082569401964015'},
							{id:'1254',name:'Tie 20 Oulu, Rusko',latitude:'65.054718853463584',longitude:'25.607665954021662'},
							{id:'1301',name:'Tie 5 Rytivaara',latitude:'64.317762118114885',longitude:'28.04117587271001'},
							{id:'1302',name:'Tie 5 Ristijärvi',latitude:'64.486622873780604',longitude:'28.176603175968552'},
							{id:'1322',name:'Tie 5 Nuottijärvi',latitude:'64.159351333864393',longitude:'27.524651335195017'},
							{id:'1323',name:'Tie 6 Sotkamo, Korholanmäk',latitude:'64.138162949336717',longitude:'27.994743366124339'},
							{id:'1326',name:'Tie 28 Vuottolahti',latitude:'64.115075996915337',longitude:'27.265371381091366'},
							{id:'1246',name:'Tie 4 Oulu, Kuivasjärvi',latitude:'65.073907000000005',longitude:'25.442171999999999'},
							{id:'1250',name:'Tie 4 Välkkylä',latitude:'65.011107999999993',longitude:'25.5063'},
							{id:'1238',name:'Tie 4 Linnanmaa',latitude:'65.045568000000003',longitude:'25.464423'},
							{id:'1124',name:'Tie 8 Kalajoki, Rahvo',latitude:'64.280483020150314',longitude:'23.952239205745276'},
							{id:'1202',name:'Tie 4 Ii',latitude:'65.301768676489232',longitude:'25.372772186669145'},
							{id:'1203',name:'Tie 5 Kuusamo',latitude:'66.060390162962292',longitude:'29.144872176191363'},
							{id:'1221',name:'Tie 4 Rantsila',latitude:'64.384854736903279',longitude:'25.853224664097695'},
							{id:'1222',name:'Tie 4 Liminka, Tupos',latitude:'64.830648563470476',longitude:'25.490673507351321'},
							{id:'1236',name:'Tie 4 Alatemmes',latitude:'64.774349000000001',longitude:'25.54813'},
							{id:'1244',name:'Tie 4 Oulu, Laanila',latitude:'65.031058999999999',longitude:'25.498117000000001'},
							{id:'1201',name:'Tie 4 Oulu, Mäntylä',latitude:'64.978492000000003',longitude:'25.509356'},
							{id:'1235',name:'Tie 8 Liminka',latitude:'64.782898000000003',longitude:'25.349432'},
							{id:'1052',name:'Tie 8 Himanka',latitude:'64.049461029793463',longitude:'23.641637398184091'},
							{id:'1101',name:'Tie 4 Pyhäjärvi',latitude:'63.621959415614022',longitude:'25.788432747189624'}];
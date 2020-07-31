function setJogosFull(){
    var jogos = [], opcao = $("#opcao").val(), d = new Date(), date = "", ano, mes, dia;

    ano = d.getFullYear().toString();
    mes = d.getMonth() + 1;
    dia = d.getDate();
    date = ano + "-" + ((mes > 10) ? mes : ("0" + mes)) + "-" + ((dia > 10) ? dia : ("0" + dia));

    date = (opcao == 1) ? $("#date").val() : date;

    var liga      = $(".td_league"),
        tempo     = $(".match_status"),
        casa      = $(".match_home"),
        placar    = $(".match_goal"),
        visitante = $(".match_away"),
        cantos    = $(".match_corner"),
        ataques   = $(".match_attach"),
        chutes    = $(".match_shoot"),
        links     = $(".td_analysis"),
        tr        = $("#tbd_match > tr");

    for (i=0; i<liga.length; i++){
        var t = $(tempo[i]).text().trim();

        if (t != "" && t != undefined){        
            var hora = $(tr[i]).children('td')[2];

            var split = $(cantos[i]).text().trim().split("\n");

            var ft = split[0].replace(" ", "").replace(" ", ""),
                ht = split[1].replace("(", "").replace(")", "");

            var cantos_ht = ht.split('-'), cantos_ft = ft.split('-');

            var a = $(links[i]).children('a').get(0);
                id = $(a).attr('href').split('/')[3].trim();

            var attacks = $(ataques[i]).text().trim().split('\n');   

            var id_home = $(casa[i]).children('a').attr('href').split('/')[3],
                id_away = $(visitante[i]).children('a').attr('href').split('/')[3],
                id_liga = $(liga[i]).children('div').children('a').attr('href').split('/')[3];

            var home = $(casa[i]).text().trim().split(/[\[\]\d\n]+/), away = $(visitante[i]).text().trim().split(/[\[\]\d\n]+/);

            jogos.push({
                liga:      $(liga[i]).text().trim(),
                data:      date + " " + $(hora).text().trim(),
                hora:      $(hora).text().trim(),
                tempo:     $(tempo[i]).text().trim(),
                casa:      (home[0] == "") ? home[1].trim() : home[0].trim(),
                placar:    $(placar[i]).text().trim(),
                visitante: (away[0] == "") ? away[1].trim() : away[0].trim(),
                cantos:    $(cantos[i]).text().trim(),
                ht:        ht,
                ft:        (ht != null) ? ft : " - ",
                total:     (ft != null) ? (parseInt(cantos_ft[0]) + parseInt(cantos_ft[1])) : (parseInt(cantos_ht[0]) + parseInt(cantos_ht[1])),
                ataques:   $(ataques[i]).text().trim(),
                attht:     attacks[1],
                attft:     attacks[0],
                chutes:    $(chutes[i]).text().trim(),
                chutes_ht: $(chutes[i]).text().trim().split('\n')[1],
                chutes_ft: $(chutes[i]).text().trim().split('\n')[0],
                game:      id,
                idhome:    id_home,
                idaway:    id_away,
                idliga:    id_liga,
            });
        }
    }

    return jogos;
}

function inicializarJogos(){
    games = setJogos();
    setTable(games);
}



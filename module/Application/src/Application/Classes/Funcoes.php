<?php

namespace Application\Classes;

use Exception;
use Zend\Session\Container;

class Funcoes {
    protected $em;
    protected $objeto;
    protected $tradutor;

    protected function getEntityManager($connector = "orm_default") {
        $this->em = $this->objeto->getServiceLocator()->get('doctrine.entitymanager.' . $connector);

        return $this->em;
    }

    public function setEm($em) {
        $this->em = $em;
    }

    public function alertBasic($message = 'Access Denied', $close = false, $redirect = false, $type = 'warning', $titulo = 'Atenção!') {
        header('Content-type: text/html; charset=CP1252');

        echo "<script src=\"/js/sweetalert.min.js\"></script>";
        echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"/css/sweetalert.css\">";
        echo "<body><script>swal({title:'$titulo',text:'$message', type:'$type', html: true}, function(){" . ($close ? "window.close(); " : ($redirect ? "location.href='$redirect';" : 'history.back();')) . "});</script></body>";
        exit;
    }

    public function __construct($objeto = null) {
        $this->objeto = $objeto;
        
        if ($objeto != null) {
            $tradutor = $this->objeto->getServiceLocator()->get('translator');
            $this->tradutor = $tradutor;
        }
    }
    
    //Retorna a data e hora atual dependendo do padrão
    public function atual($pattern = 'sql'){
        if ($pattern == 'sql'){
            $atual = strftime("%Y-%m-%d %H:%M");            
        } else {
            $atual = strftime("%d/%m/%y %H:%M");
        }
        
        return $atual;
    }
    
    //função copiada da internet por Robson para ordenar os dados do resultSet 
    function array_sort($array, $on, $order = SORT_ASC) {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }
        return $new_array;
    }

    function converterArrayUTF8($array) {
        array_walk(
                $array, function (&$entry) {
            array_walk($entry, function(&$e) {
                $e = trim(utf8_encode($e));
            });
        });

        return $array;
    }

    //função para converter caracteres que vieram do banco em ISO-8859-1 para utf-8
    function utf8_converter($array = array(), $opcao = 'all', $tipo = 'encode') {
        $array2 = array();
        if ($opcao == 'all') {
            foreach ($array as $row) {
                foreach ($row as $key => $value) {
                    $row[strtolower($key)] = $value;
//                    CASO O VALOR FIQUE COM CARACTERES DESCONHECIDOS DESCOMENTE
//                    $row[$key] = utf8_encode($value);
                }
                $array2[] = $row;
            }
        } else {
            if ($array) {
                foreach ($array as $key => $value) {
                    $array2[strtolower($key)] = $value;
                }
            }
        }
        return $array2;
    }

    //Função para retornar o último ID de uma inserção no banco de dados
    public function getLastId($query = '', $params = array(), $tipo = 'all', $connector = "orm_default") {
        try {
            $conn = $this->getEntityManager($connector)->getConnection();
            $parametros = $this->utf8_converter($params, '', 'decode');
            $stmt = $conn->executeQuery($query, $parametros);

            $stmt = $conn->lastInsertId();

            return $stmt;
        } catch (Exception $e) {
            $sql = $e->getTrace()[1]['args'][0];
            $params = $e->getTrace()[1]['args'][1];

            $bindado = $this->getSQLBinded($sql, $params);

            throw new Exception(sprintf(("Erro de Banco de Dados SQL:  \n\n <i style='background-color: yellow'>$bindado</i> \n\n%s"), $e->getMessage()));
        }
    }

    public function executarSQL($query = '', $params = array(), $tipo = 'all', $connector = "orm_default") {
        $start_time = microtime(TRUE);

        try {
            $conn = $this->getEntityManager($connector)->getConnection();
            $parametros = $this->utf8_converter($params, '', 'decode');
            $stmt = $conn->executeQuery($query, $parametros);
            
            if ($tipo == 'all') {
                $dados = $this->utf8_converter($stmt->fetchAll(), $tipo);
            } else if ($tipo == 'stmt') {
                return $stmt;
            } else {
                $dados = $this->utf8_converter($stmt->fetch(), $tipo);
            }
            
            $end_time = microtime(TRUE);
            
            /*
            echo "<pre>";
            echo "<b style='color:blue'>TIME: " . ($end_time - $start_time) . "</b>";
            echo "</pre>";
            */
            
            return $dados;
        } catch (Exception $e) {
            $sql = $e->getTrace()[1]['args'][0];
            $params = $e->getTrace()[1]['args'][1];

            $bindado = $this->getSQLBinded($sql, $params);

            throw new Exception(sprintf(("Erro de Banco de Dados SQL:  \n\n <i style='background-color: yellow'>$bindado</i> \n\n%s"), $e->getMessage()));
        }
    }

    public function arrayToObject(&$array) {
        foreach ($array as $k => $v) {
            $array[$k] = (object) $v;
        }
    }

    public function getSQLBinded($sql, $params) {
        $indexed = $params == array_values($params);

        foreach ($params as $k => $v) {
            $k = strtolower($k);

            if (is_string($v))
                $v = "'$v'";

            if ($indexed)
                $sql = preg_replace('/\?/', $v, $sql, 1);
            else
                $sql = str_replace(":$k", $v, $sql);
        }
        return $sql;
    }

    public function paises() {
        $paises = array(
            'Brasil',
            'Afeganistão',
            'África do Sul',
            'Akrotiri',
            'Albânia',
            'Alemanha',
            'Andorra',
            'Angola',
            'Anguila',
            'Antárctida',
            'Antígua e Barbuda',
            'Antilhas Neerlandesas',
            'Arábia Saudita',
            'Arctic Ocean',
            'Argélia',
            'Argentina',
            'Arménia',
            'Aruba',
            'Ashmore and Cartier Islands',
            'Atlantic Ocean',
            'Austrália',
            'Áustria',
            'Azerbaijão',
            'Baamas',
            'Bangladeche',
            'Barbados',
            'Barém',
            'Bélgica',
            'Belize',
            'Benim',
            'Bermudas',
            'Bielorrússia',
            'Birmânia',
            'Bolívia',
            'Bósnia e Herzegovina',
            'Botsuana',
            'Brunei',
            'Bulgária',
            'Burquina Faso',
            'Burúndi', 'Butão', 'Cabo Verde', 'Camarões', 'Camboja', 'Canadá', 'Catar', 'Cazaquistão', 'Chade', 'Chile', 'China', 'Chipre', 'Clipperton Island', 'Colômbia', 'Comores', 'Congo-Brazzaville', 'Congo-Kinshasa', 'Coral Sea Islands', 'Coreia do Norte', 'Coreia do Sul', 'Costa do Marfim', 'Costa Rica', 'Croácia', 'Cuba', 'Dhekelia', 'Dinamarca', 'Domínica', 'Egipto', 'Emiratos Árabes Unidos', 'Equador', 'Eritreia', 'Eslováquia', 'Eslovénia', 'Espanha', 'Estados Unidos', 'Estônia', 'Etiópia', 'Faroé', 'Fiji', 'Filipinas', 'Finlândia', 'França', 'Gabão', 'Gâmbia', 'Gana', 'Gaza Strip', 'Geórgia', 'Geórgia do Sul e Sandwich do Sul', 'Gibraltar', 'Granada', 'Grécia', 'Gronelândia', 'Guame', 'Guatemala', 'Guernsey', 'Guiana', 'Guiné', 'Guiné Equatorial', 'Guiné-Bissau', 'Haiti', 'Honduras', 'Hong Kong', 'Hungria', 'Iémen', 'Ilha Bouvet', 'Ilha do Natal', 'Ilha Norfolk', 'Ilhas Caimão', 'Ilhas Cook', 'Ilhas dos Cocos', 'Ilhas Falkland', 'Ilhas Heard e McDonald', 'Ilhas Marshall', 'Ilhas Salomão', 'Ilhas Turcas e Caicos', 'Ilhas Virgens Americanas', 'Ilhas Virgens Britânicas', 'Índia', 'Indian Ocean', 'Indonésia', 'Irão', 'Iraque', 'Irlanda', 'Islândia', 'Israel', 'Itália', 'Jamaica', 'Jan Mayen', 'Japão', 'Jersey', 'Jibuti', 'Jordânia', 'Kuwait', 'Laos', 'Lesoto', 'Letónia', 'Líbano', 'Libéria', 'Líbia', 'Listenstaine', 'Lituânia', 'Luxemburgo', 'Macau', 'Macedónia', 'Madagáscar', 'Malásia', 'Malávi', 'Maldivas', 'Mali', 'Malta', 'Man', ' Isle of', 'Marianas do Norte', 'Marrocos', 'Maurícia', 'Mauritânia', 'Mayotte', 'México', 'Micronésia', 'Moçambique', 'Moldávia', 'Mónaco', 'Mongólia', 'Monserrate', 'Montenegro', 'Mundo', 'Namíbia', 'Nauru', 'Navassa Island', 'Nepal', 'Nicarágua', 'Níger', 'Nigéria', 'Niue', 'Noruega', 'Nova Caledónia', 'Nova Zelândia', 'Omã', 'Pacific Ocean', 'Países Baixos', 'Palau', 'Panamá', 'Papua-Nova Guiné', 'Paquistão', 'Paracel Islands', 'Paraguai', 'Peru', 'Pitcairn', 'Polinésia Francesa', 'Polónia', 'Porto Rico', 'Portugal', 'Quénia', 'Quirguizistão', 'Quiribáti', 'Reino Unido', 'República Centro-Africana', 'República Checa', 'República Dominicana', 'Roménia', 'Ruanda', 'Rússia', 'Salvador', 'Samoa', 'Samoa Americana', 'Santa Helena', 'Santa Lúcia', 'São Cristóvão e Neves', 'São Marinho', 'São Pedro e Miquelon', 'São Tomé e Príncipe', 'São Vicente e Granadinas', 'Sara Ocidental', 'Seicheles', 'Senegal', 'Serra Leoa', 'Sérvia', 'Singapura', 'Síria', 'Somália', 'Southern Ocean', 'Spratly Islands', 'Sri Lanca', 'Suazilândia', 'Sudão', 'Suécia', 'Suíça', 'Suriname', 'Svalbard e Jan Mayen', 'Tailândia', 'Taiwan', 'Tajiquistão', 'Tanzânia', 'Território Britânico do Oceano Índico', 'Territórios Austrais Franceses', 'Timor Leste', 'Togo', 'Tokelau', 'Tonga', 'Trindade e Tobago', 'Tunísia', 'Turquemenistão', 'Turquia', 'Tuvalu', 'Ucrânia', 'Uganda', 'União Europeia', 'Uruguai', 'Usbequistão', 'Vanuatu', 'Vaticano', 'Venezuela', 'Vietname', 'Wake Island', 'Wallis e Futuna', 'West Bank', 'Zâmbia',
            'Zimbabué'
        );

        return $paises;
    }

    public function fusoHorario() {
        $fusohorario = (array(
            '1' => '(GMT -12:00) Pacific/Kwajalein - Eniwetok, Kwajalein',
            '2' => '(GMT -11:00) Pacific/Samoa - Midway Island, Samoa',
            '3' => '(GMT -10:00) Pacific/Honolulu - Hawaii',
            '4' => '(GMT -9:00) America/Anchorage - Alaska',
            '5' => '(GMT -8:00) America/Los_Angeles - Pacific Time (US & Canada) Los Angeles, Seattle',
            '6' => '(GMT -7:00) America/Denver - Mountain Time (US & Canada) Denver',
            '7' => '(GMT -6:00) America/Chicago - Central Time (US & Canada), Chicago, Mexico City',
            '8' => '(GMT -5:00) America/New_York - Eastern Time (US & Canada), New York, Bogota, Lima',
            '9' => '(GMT -4:00) Atlantic/Bermuda - Atlantic Time (Canada), Caracas, La Paz',
            '10' => '(GMT -3:30) Canada/Newfoundland - Newfoundland',
            '11' => '(GMT -3:00) Brazil/East - Brazil, Buenos Aires, Georgetown',
            '12' => '(GMT -2:00) Atlantic/Azores - Mid-Atlantic',
            '13' => '(GMT -1:00) Atlantic/Cape_Verde - Azores, Cape Verde Islands',
            '14' => '(GMT) Europe/London - Western Europe Time, London, Lisbon, Casablanca',
            '15' => '(GMT +1:00) Europe/Brussels - Brussels, Copenhagen, Madrid, Paris',
            '16' => '(GMT +2:00) Europe/Helsinki - Kaliningrad, South Africa',
            '17' => '(GMT +3:00) Asia/Baghdad - Baghdad, Riyadh, Moscow, St. Petersburg',
            '18' => '(GMT +3:30) Asia/Tehran - Tehran',
            '19' => '(GMT +4:00) Asia/Baku - Abu Dhabi, Muscat, Baku, Tbilisi',
            '20' => '(GMT +4:30) Asia/Kabul - Kabul',
            '21' => '(GMT +5:00) Asia/Karachi - Ekaterinburg, Islamabad, Karachi, Tashkent',
            '22' => '(GMT +5:30) Asia/Calcutta - Bombay, Calcutta, Madras, New Delhi',
            '23' => '(GMT +6:00) Asia/Dhaka - Almaty, Dhaka, Colombo',
            '24' => '(GMT +7:00) Asia/Bangkok - Bangkok, Hanoi, Jakarta',
            '25' => '(GMT +8:00) Asia/Hong_Kong - Beijing, Perth, Singapore, Hong Kong',
            '26' => '(GMT +9:00) Asia/Tokyo - Tokyo, Seoul, Osaka, Sapporo, Yakutsk',
            '27' => '(GMT +9:30) Australia/Adelaide - Adelaide, Darwin',
            '28' => '(GMT +10:00) Pacific/Guam - Eastern Australia, Guam, Vladivostok',
            '29' => '(GMT +11:00) Asia/Magadan - Magadan, Solomon Islands, New Caledonia',
            '30' => '(GMT +12:00) Pacific/Fiji - Auckland, Wellington, Fiji, Kamchatka'
        ));

        return $fusohorario;
    }

    //Retorna a data do banco de dados no padrão brasileiro
    public function getData($data) {
        $data = explode("-", substr($data, 0, 10));
        $date = $data[2] . '/' . $data[1] . '/' . $data[0];

        return $date;
    }

    public function getHora($hora) {
        $hora = explode(":", $hora);
        $hf = $hora[0] . ':' . $hora[1];

        return $hf;
    }
    
    public function getDataHora($valor){
        $data = explode("-", substr($valor, 0, 10));
        $hora = explode(":", substr($valor, 11));
        
        $result = $data[2] . '/' . $data[1] . '/' . $data[0] . ' ' . $hora[0] . ':' . $hora[1];
        
        return $result;
    }

    //Converte a data e a hora passadas para o padrão do banco de dados e retorna
    public function setDataHora($data = "01/01/2017", $hora = "00:00") {
        $dh = str_replace('/', '-', $data);
        $dh = substr($dh, 6) . '-' . substr($dh, 3, 2) . '-' . substr($dh, 0, 2) . ' ' . $hora;

        return $dh;
    }
    
    public function setData($data){
        $final = str_replace('/', '-', $data);
        $final = substr($final, 6) . '-' . substr($final, 3, 2) . '-' . substr($final, 0, 2);

        return $final;        
    }
    
    public function mes_atual() {
        $meses = array(
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro'
        );

        return $meses[date('m')];
    }
}
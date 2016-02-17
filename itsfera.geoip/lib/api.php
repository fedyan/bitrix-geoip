<?php
namespace Itsfera\Geoip;
class Api {
    protected $DB;

    function __construct()
    {
        $this->DB = &$GLOBALS['DB'];

        if ( !$this->checkTables() ) {
            echo "Tables not exists";
            //Throw New \Exception("Tables not exists");
            return false;
        }

    }

    public function getCityByIp()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        //$ip = '95.129.56.55';
        return $this->findCity($ip);

    }

    protected function checkTables()
    {

        foreach(array("net_city","net_city_ip","net_country","net_country_ip","net_euro","net_ru") as $sTableName) {
            $res = $this->DB->Query("SHOW TABLES LIKE '".$sTableName."'");
            if ( !$res->Fetch() ) {
                return false;
            }
        }
        return true;

    }


    protected function findCity($ip)
    {

        // IP-адрес, который нужно проверить
        //$ip = "79.134.219.2";

// Преобразуем IP в число
        $int = sprintf("%u", ip2long($ip));

        $country_name = "";
        $country_id = 0;

        $city_name = "";
        $city_id = 0;

// Ищем по российским и украинским городам
        $sql = "select * from (select * from net_ru where begin_ip<=$int order by begin_ip desc limit 1) as t where end_ip>=$int";
        $results = $this->DB->Query( $sql );

        $name_array=array();
        //создаем пустой массив, но можно эту строчку исключить

        if ($row = $results->Fetch()) {
            $city_id = $row['city_id'];
            $sql = "select * from net_city where id='$city_id'";
            $results = $this->DB->Query( $sql );
            if ($row = $results->Fetch()) {
                $city_name = $row['name_ru'];
                $country_id = $row['country_id'];
            } else {
                $city_id = 0;
            }
        }

// Если не нашли - ищем страну и город по всему миру
        if (!$city_id) {
            // Ищем европейскую страну
            $sql = "select * from (select * from net_euro where begin_ip<=$int order by begin_ip desc limit 1) as t where end_ip>=$int";
            $results = $this->DB->Query( $sql );

            if ($results->SelectedRowsCount() == 0) {
                // Ищем страну в мире
                $sql = "select * from (select * from net_country_ip where begin_ip<=$int order by begin_ip desc limit 1) as t where end_ip>=$int";
                $results = $this->DB->Query( $sql );
            }
            if ($row = $results->Fetch() ){
                $country_id = $row['country_id'];
            }

            // Ищем город
            $city_name = "";
            $city_id = 0;
            // Ищем город в глобальной базе
            $sql = "select * from (select * from net_city_ip where begin_ip<=$int order by begin_ip desc limit 1) as t where end_ip>=$int";
            $results = $this->DB->Query( $sql );
            if ($row = $results->Fetch()) {
                $city_id = $row['city_id'];
                $sql = "select * from net_city where id='$city_id'";
                $results = $this->DB->Query( $sql );
                if ($row = $results->Fetch() ) {
                    $city_name = $row['name_ru'];
                    $country_id = $row['country_id'];
                }
            }
        }

// Выводим результат поиска
        $country_name = "";
        if ($country_id !== 0) {
            // Название страны
            $sql = "select * from net_country where id='$country_id'";
            $results = $this->DB->Query( $sql );
            if ($row = $results->Fetch() ) {
                $country_name = $row['name_ru'];
            }
        }

        return array(
            'city_name'=>$city_name,
            'country_name'=>$country_name,
            'ip'=>$ip
        );

    }

}
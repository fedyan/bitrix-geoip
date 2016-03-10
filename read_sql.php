<?php

//ini_set("memory_limit", "512M");

$arStartSkipSymbols = ["--","/*"];


try {
    $dbh = new PDO('mysql:host=localhost;dbname=mytest', "root", "");


    // Read in entire file
    $handle = @fopen("geo_city_2013_09.sql", "r");
    if ($handle) {
        $c = 0;
        $sql = "";
        while (($line = fgets($handle, 4096)) !== false) {

            if (empty(trim($line)) || in_array(substr($line,0,2),$arStartSkipSymbols) ) continue;

            $sql.= $line;
            //формируем запросы из строк
            if ( substr(trim($line),-1)==';'){
                //echo '['.$sql.']<br>';

                //if ($c++>30) break;

                $res = $dbh->exec( $sql );
                echo ++$c.' - '.$res.'<br>'; 
                $sql = "";

            }



        }
        //if (!feof($handle)) {
          //  echo "Error: unexpected fgets() fail\n";
        //}
        unset($dbh);
        fclose($handle);
    }

} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

?>

<?php
    require_once('appConfig.php');
    
    //rotina de backup
    try {
        include_once('appConnection.php');

        $return = '';
        $tables = array();
        $sql = $pdo->query("SHOW TABLES");

            while($row = $sql->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }

            //cycle through each table and format the data
            foreach($tables as $table) {
                $sql2 = $pdo->query("SELECT * FROM ".$table);
                $num_fields = $sql2->columnCount();

                $sql3 = $pdo->query("SHOW CREATE TABLE ".$table);
                $row2 = $sql3->fetch(PDO::FETCH_NUM);
                $return.= "\n".$row2[1].";\n";

                    for($i = 0; $i < $num_fields; $i++) {
                        while($row3 = $sql2->fetch(PDO::FETCH_NUM)) {
                            $return.= 'INSERT INTO '.$table.' VALUES(';

                                for($j = 0; $j < $num_fields; $j++) {
                                    $row3[$j] = addslashes($row3[$j]);
                                    $row3[$j] = str_replace("\n","\\n",$row3[$j]);
                                    #$row3[$j] = ereg_replace("\n","\\n",$row3[$j]);
                                    #$row3[$j] = preg_replace("\n","\\n",$row3[$j]);

                                        if(isset($row3[$j])) {
                                            $return.= '"'.$row3[$j].'"';
                                        }
                                        else {
                                            $return.= '""';
                                        }

                                        if ($j < ($num_fields - 1)) {
                                            $return.= ',';
                                        }
                                } //for

                            $return.= ");\n";
                        } //while
                    }// for

                $return.="";
            } //foreach

        //save the file
        #$file = 'db/'.time().'-'.(md5(implode(',',$tables))).'.sql';
        $file = 'db/'.md5(time()).'.sql';
        $handle = fopen($file,'w+');
        fwrite($handle,$return);
        fclose($handle);

        unset($file,$handle,$return,$tables,$sql,$sql2,$row,$table,$num_fields,$row2,$row3,$sql2,$sql3,$j);
    }
    catch(PDOException $e) {
        echo 'Erro ao conectar o servidor '.$e->getMessage();
    }

    unset($pdo,$e,$cfg);

    session_unset();
    session_destroy();

    header('location: ./');
?>
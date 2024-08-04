<a href="query.php">Pick another query</a>

 <?php
        include 'get-parameters.php';
        //Query for childhood mortality rate
        $sql = "SELECT name, mortalityunder5 FROM countrydata_final;";
        //$conn = new mysqli($_SESSION['ep'], $_SESSION['un'], $_SESSION['pw'], $_SESSION['db']);
        $conn = new mysqli($ep, $un, $pw, $db);

        if ($conn->connect_error) {
            error_log('Connection error: ' . $conn->connect_error);
            var_dump('Connection error: ' . $conn->connect_error);
        }
        else {

            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
              //echo "success";
              echo '<table style="width: 80%">';
              echo '<tr>';
              echo '<th style="text-align:left">Country Name</th>';
              echo '<th style="text-align:left">Childhood Mortality</th>';
              echo '</tr>';

              while($row = $result->fetch_assoc()) {
              //echo '<p>';

                echo '<tr>';
                    echo '<td>';
                        echo $row["name"];
                        echo '&nbsp';
                    echo '</td>';
                    echo '<td>';
                        echo $row["mortalityunder5"];
                        echo '&nbsp';
                    echo '</td>';
                echo '<br>';
                echo '</tr>';
              }
              echo '</table>';
            } else {
                echo "There is no records";
            }
        }

    ?>

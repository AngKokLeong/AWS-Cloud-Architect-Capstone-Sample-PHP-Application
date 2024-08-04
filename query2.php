<html>
     <body>
          <?php
               require 'get-parameters.php';
               
               $_pick = filter_input(INPUT_POST, 'selection', FILTER_SANITIZE_STRING);
               
               echo $_pick;
               switch ($_pick) {
                    case "Q1":
                         require 'mobile.php';
                         break;
                    case "Q2":
                         require 'population.php';
                         break;
                    case "Q3":
                         require 'lifeexpectancy.php';
                         break;

                    case "Q4":
                         require 'gdp.php';
                         break;

                    case "Q5":
                         require 'mortality.php';
                         break;
               }
          ?>

          <div id="Copyright" class="center">
               <h5>&copy; 2020, Amazon Web Services, Inc. or its Affiliates. All rights reserved.</h5>
          </div>
     </body>
</html>

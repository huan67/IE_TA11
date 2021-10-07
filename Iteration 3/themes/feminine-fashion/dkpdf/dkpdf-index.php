<html>

<head>
    <style>
        #test {
            background-image: url('https://www.ta11dhvpa.tk/wp-content/uploads/2021/09/截屏2021-09-14-下午6.03.14.png');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
    </style>
</head>
<div id="test">
    <br><br>
    <?php
    function getIPAddress()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    $ip = getIPAddress();
    global $wpdb;
    $food1 = "";
    $type1 = "";
    $s1 = "";
    $count = 0;
    $vars = $wpdb->get_results(" SELECT * FROM `Sample` ORDER BY `Id` DESC", ARRAY_A);
    $vars3 = $wpdb->get_results(" SELECT * FROM `frd`", ARRAY_A);
    $vars4 = $wpdb->get_results(" SELECT * FROM `meat`", ARRAY_A);
    foreach ($vars as $var) {
        if ($ip == $var["UIP"]) {
            $food1 = $var["Food"];
            $type1 = $var["Type"];
            $s1 = $var["Storage"];
            break;
        }
    }
    echo "<div><h1 align=\"center\">Ingredient: " . ucfirst($food1) . "</h1></div>";
   foreach ($vars4 as $var4) {
       if ($s1 == "yes"  &&  ($food1 == $var4["Type"] || ucfirst($food1) == $var4["Type"])) {
                   if (empty($var4["Cooking Advice"])) {
                       echo "<div ><h3 align=\"center\"> Storage Tips </h3></div>";
                       echo "<div style=\"border: 1px solid black;height: 100%;
                                      width: 75%; box-shadow: 5px 10px 8px #888888;
                                      background-color: white; margin: auto;\">
                                      <h4 align=\"center\"> Storage time</h4>
                                      <div style = \" padding-right:100px;padding-left:100px;\">" . $var4["Storage time"] .
                           "</div><div> <h4 align=\"center\">Advice for storage </h4>
                                      <div style = \"padding-right:100px;padding-left:100px;\">" . $var4["Advice"] .
                           "</div><br></div>
                    <br>
                                        <hr style= \"border:solid 1px black; width: 96%; color: #000000; height: 1px; margin: auto;\">
                                        <div style=\"padding-right:25px;padding-left:25px; font-size: 12px;\"> Source: " . $var4["Source"] . "</div></div><br>";
                   } else {
                       echo "<div ><h3 align=\"center\"> Storage Tips </h3></div>";
                       echo "<div style=\"border: 1px solid black;height: 100%;
                                      width: 75%; box-shadow: 5px 10px 8px #888888;
                                      background-color: white; margin: auto;\">
                                      <h4 align=\"center\"> Storage time</h4>
                                      <div style = \" padding-right:100px;padding-left:100px;\">" . $var4["Storage time"] .
                           "</div><div> <h4 align=\"center\">Advice for storage </h4>
                                      <div style = \"padding-right:100px;padding-left:100px;\">" . $var4["Advice"] .
                           "</div></div><div><h4 align=\"center\">Cooking Advice </h4>
                                      <div style = \" padding-right:100px; padding-left:100px;\">" . $var4["Cooking Advice"] .
                           "</div></div>
                    <br>
                                        <hr style= \"border:solid 1px black; width: 96%; color: #000000; height: 1px; margin: auto;\">
                                        <div style=\"padding-right:25px;padding-left:25px; font-size: 12px;\"> Source: " . $var4["Source"] . "</div></div><br>";
                   }
               }
   }

   echo "<div ><h3 align=\"center\"> Recipe </h3></div>";

   foreach ($vars3 as $var3) {
       $Ingredients = explode(",", $var3["Ingredients Required"]);
       foreach ($Ingredients as $Ingredient) {
           if (strstr($Ingredient, $food1) || strstr($Ingredient, ucfirst($food1))) {
               if (empty($type1) || $var3["Recipe Type"] == $type1) {
                   $count++;
                   echo "<div style=\"border: 1px solid black;height: 100%;
                             width: 75%; box-shadow: 5px 10px 8px #888888;
                             background-color: white; margin: auto;\">
                             <h3 align=\"center\">" . $var3["Recipe Name"] .
                             "</h3><br>
                             <div align=\"center\" style=\"width:650px;height:350px;\"><img src=\" " . $var3["Image"] . " \" alt=\"home Run\" style=\"width:75%;height:450px; object-fit:cover;\"> </div>
                             <br> <div align=\"center\" > <h4 align=\"center\"> Recipe Type </h4> <span style = \"font-family: 'Brush Script MT'; padding-right:25px;padding-left:25px; \">". $var3["Recipe Type"] . "</span></div>
                             <div> <h4 align=\"center\">Ingredients Required </h4></div><div style = \"font-family: 'Brush Script MT';padding-right:125px;padding-left:175px;\">" . $var3["Ingredients Required"]. "</div>
                             <h4 align=\"center\"> Cook Details </h4>
                             <div style = \"font-family: 'Brush Script MT';padding-right:50px;padding-left:50px;\"> " . $var3["Description"] . "</div>
                              <br> <hr style= \"border:solid 1px black; width: 96%; color: #000000; height: 1px; margin: auto;\">
                             <div style = \"padding-right:25px;padding-left:25px; font-size: 12px;\"> " . $var3["Source"] . "</div></div><br>
                        ";
               }
           }
       }
   }


    if ($count == 0) {
        echo "Sorry we don't have related recipes";
    }
    $wpdb->delete("Sample", array('UIP' => $ip));
    ?>
</div>
</body>

</html>

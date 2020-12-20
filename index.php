<?php
include 'back_indicateur1.php' ;
?>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
              integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="assets/style.css">
        <meta charset="utf-8">
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

        <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            var rowstabnom = <?php echo json_encode($tabnom,JSON_NUMERIC_CHECK)?>;

            function drawChart() {

                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Date');
                data.addColumn('number', 'Nombre d\'interactions avec le système');
                data.addColumn('number', 'Nombre moyen d\'interactions avec le système\n');

                data.addRows(rowstabnom);
                console.log(rowstabnom);

                var options = {
                    title: 'La motivation de l\'utilisateur par rapport à la moyenne\n',
                    curveType: 'function',
                    legend: { position: 'bottom' }
                };

                var chart = new google.visualization.AreaChart(document.getElementById('curve_chart'));

                chart.draw(data, options);
            }
        </script>
        <script type="text/javascript">
            google.charts.load('current', {'packages':['bar']});
            google.charts.setOnLoadCallback(drawChart);
            var rows = <?php echo json_encode($tabresult,JSON_NUMERIC_CHECK)?>;

            function drawChart() {
                var data = new google.visualization.DataTable();
                data.addColumn('number', 'Heure de la journée\n');
                data.addColumn('number', 'Nombre moyen d\'interactions avec le système');

                console.log(rows);
                data.addRows(rows);

                var options = {
                    title: 'Heure du point',

                    bars: 'horizontal' // Required for Material Bar Charts.
                };

                var chart = new google.charts.Bar(document.getElementById('barchart_material'));

                chart.draw(data, google.charts.Bar.convertOptions(options));
            }
        </script>
    </head>
    <body>
    <nav class="navbar navbar-expand-lg navbar-app">
        <div class="container-fluid" style="width: 97%">
            <a class="navbar-brand" href="#">
                <!--<img src="assets/logo.svg" width="120px"/>-->
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText"
                    aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Binôme : Aymane Rizke & Mohamed Abdellahi el Waghf</a>
                    </li>
                </ul>
            </div>

        </div>
    </nav>
    <div class="container-fluid container-app">
        <div class="row justify-content-around">
            <div class="col-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title" style="margin-block-end: revert;">Filter</h4>
                        <div class="btn-group-toggle" data-toggle="buttons" style="text-align-last: center;text-align: center;width: 100%;">
                            <form method="post" action="view_indicateur1.php">
                                <label for="user">Veuillez choisir un utilisateur</label>
                                <br/>
                                <select id="user" name="user" style="margin-block-end: inherit;">
                                    <?php
                                    $querry2 = "SELECT DISTINCT Utilisateur From transition";
                                    $result2 = $conn->query($querry2);
                                    while($r = $result2 -> fetch_row()){
                                        if ($_POST["user"] == $r[0])
                                        { echo "<option id='".$r[0]."' name = 'user' value ='".$r[0]."' selected>".$r[0]."</option>"; }
                                        else
                                        { echo "<option id='".$r[0]."' name = 'user' value ='".$r[0]."'>".$r[0]."</option>"; }

                                    }
                                    ?>
                                </select>
                                <br/>
                                <label for="forum">Veuillez choisir un forum</label>
                                <br/>
                                <select id="forum" name="forum" style="margin-block-end: inherit;">
                                    <?php
                                    $querryforum = "SELECT SUBSTRING_INDEX(`Attribut`, ',', 1) AS forum FROM transition WHERE `Attribut` LIKE '%IDForum%' GROUP BY forum";
                                    $resultforum = $conn->query($querryforum);
                                    echo "<option id='nan' name = 'forum' value =''>Tous les forums</option>";
                                    while($r = $resultforum -> fetch_row()){
                                        $nbforum=preg_split("/[\s=]+/", $r[0])[1];
                                        if ($_POST["forum"] == $nbforum)
                                            echo "<option id='".$nbforum."' name = 'forum' value ='".$nbforum."' selected>".$nbforum."</option>";
                                        else
                                            echo "<option id='".$nbforum."' name = 'forum' value ='".$nbforum."' >".$nbforum."</option>";

                                    }
                                    mysqli_close($conn);
                                    ?>
                                </select>
                                <br/>
                                <label for="datestart">Veuillez choisir la date de début</label>
                                <br/>
                                <input type="date" id="datestart" name="datestart"  style="margin-block-end: inherit;"
                                       value="<?php  if(array_key_exists("datestart",$_POST) && $_POST["datestart"] != null) {echo $datestart;} else {echo "2009-02-01";}  ?>" min="2009-02-01" max="2009-05-31">
                                <br/>
                                <label for="dateend">Veuillez choisir la date de fin</label>
                                <br/>
                                <input type="date" id="dateend" name="dateend" style="margin-block-end: inherit;"
                                       value="<?php  if(array_key_exists("dateend",$_POST) && $_POST["dateend"] != null) {echo $dateend;} else {echo "2009-05-31";}  ?>" min="2009-02-01" max="2009-05-31">
                                <br/>
                                <input type="submit" class="btn btn-app" >
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div class="card " style="align-items: center;">
                    <div class="card-body" style="width: 100%">
                        <h2>Indicateur 1 : <b>Degré de motivation d'une personne</b></h2>
                        <p>Le premier indicateur est calculé à partir du total d'interaction journalière avec le système qu'<b>un utilisateur</b> a fait dans <b>un forum</b> pendant <b>une période</b>.</p>
                        <p><i>L'indicateur permet au professeur de mieux organiser la répartition de la charge du travail (le type du travail demandé), il permet aussi l’administration de répartir le programme de l’année (Volume horaire), prennent en compte le degré de motivation des étudiants
                            </i></p>
                        <div id="curve_chart"  style="margin: 10px;width: 90%; height:500px;"></div>
                        <h1>Indicateur 2: <b>Heure de pointe</b></h1>
                        <p>Le deuxiéme indicateur est calculé à partir de la moyenne d'interaction pour chaque heure que les utilisateurs avaient fait dans <b>un forum</b> pendant <b>une période</b>.</p>
                        <p><i>L'indicateur permet de savoir le temps parfait pour faire une réunion ou de lancer un message atteignable</i></p>
                        <div id="barchart_material"   style="margin: 10px;width: 90%; height: 600px;" ></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>

<?php require_once __DIR__ . '/vendor/autoload.php'; ?>
<!DOCTYPE html>
<html lang="en" id="wholepage">
    <head> 
        <title>RoK Alliance Manager</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
        <link rel="icon" type="image/png" href="../assets/img/favicon.png">

        <!-- jQuery-->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>   

        <!-- DataTables-->
        <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> -->


        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- TABULATOR -->
        <script src="https://unpkg.com/tabulator-tables@5.4.3/dist/js/tabulator.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/tabulator-tables@5.4.3/dist/css/tabulator.min.css">



        <!--     Fonts and icons     -->
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
        <!-- Nucleo Icons -->
        <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
        <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
        <!-- Font Awesome Icons 
        <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script> -->
        <!-- Material Icons -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
        <!-- CSS Files -->
        <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />

    </head>
    <body>

  <!--**********************************
              Sidebar start
    ***********************************-->
		<?php include 'elements/sidebar.php'; ?>
        <!--**********************************
            Sidebar end
        ***********************************-->

      <!--**********************************
          Nav header start
      ***********************************-->
		<?php include 'elements/nav-header.php'; ?>
      <!--**********************************
          Nav header end
      ***********************************-->



      <!--**********************************
            Content body start
        ***********************************-->
        
<!--FORM PER CARICAMENTO FILE-->
        <form id="uploadForm" method="POST" enctype="multipart/form-data"> <!--form upload file-->
            <div class="mb-3"> <!--input file-->
                <input class="form-control" type="file" name="zip" accept=".zip" id="zip" required>
            </div>
            <button id="uplpadfile" type="submit" class="btn btn-primary">Upload File</button>
            <div class="mt-2" id="file-info">No file</div>
            <input type="hidden" name="source" value="zip">
        </form>
        


          <!-- Dati generali caricati da upload.php
             vedi ajax riga: 426 -->
            <div class="row mb-3 chart-wrapper" >
                <div class="col-md-2">
                    <div class="data-box">
                        <h5>Data report</h5>
                        <div id="last_update">-</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="data-box">
                        <h5>Total players</h5>
                        <div><span id="total_players">-</span></div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="data-box">
                        <h5>Total Power</h5>
                        <div id="total_power">-</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="data-box">
                        <h5>Total Helps</h5>
                        <div id="total_merits">-</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="data-box">
                        <h5>Average Power</h5>
                        <div id="average_power">-</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="data-box">
                        <h5>Average helps</h5>
                        <div id="average_merits">-</div>
                    </div>
                </div>
            </div>



            <!-- GRAFICI PLAYERS, COMING SOON -->

        <!-- TABELLA TOP PLAYERS ALLY -->
        <div id="top_players"></div>





































<script>



//GESTIONE upload file
$(document).ready(function() {
    // Gestione dell'upload del file
    $('#uploadForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: 'upload.php',
            type: 'POST',
            contentType: 'application/json',
            async: false,
            data: formData,
            processData: false,
            contentType: false,
            
            success: function(response) {
            try {
               /* const r = (typeof response === "string") ? JSON.parse(response) : response;
                average_power = r.average_power;
                total_players = r.total_players;
                total_power = r.total_power;
                total_merits = r.total_merits;
                average_merits = r.average_merits;
                average_power = r.average_power;
                last_update = r.last_update;
                // Converti i dati stringificati in array di oggetti
                const tableData = convertArrayToObjects(r.data);



                $("#last_update").text(last_update)
                $("#total_players").text(total_players)
                $("#total_power").text(total_power)
                $("#total_merits").text(total_merits)
                $("#average_merits").text(average_merits)
                $("#average_power").text(average_power)
                

                // i dati arrivano come array di array, vengono convertiti in array di oggetti 
                function convertArrayToObjects(jsonString) {
                    const rawData = JSON.parse(jsonString); // parse string to array
                    const headers = rawData[0]; // first row is the header
                    const rows = rawData.slice(1); // remaining rows are data

                    return rows.map(row => {
                        const obj = {};
                        headers.forEach((header, i) => {
                            obj[header] = row[i];
                        });
                        return obj;
                    });
                }
                
                var dateEditor = function(cell, onRendered, success, cancel){
                //cell - the cell component for the editable cell
                //onRendered - function to call when the editor has been rendered
                //success - function to call to pass thesuccessfully updated value to Tabulator
                //cancel - function to call to abort the edit and return to a normal cell

                //create and style input
                var cellValue = luxon.DateTime.fromFormat(cell.getValue(), "dd/MM/yyyy").toFormat("yyyy-MM-dd"),
                input = document.createElement("input");

                input.setAttribute("type", "date");

                input.style.padding = "4px";
                input.style.width = "100%";
                input.style.boxSizing = "border-box";

                input.value = cellValue;

                onRendered(function(){
                    input.focus();
                    input.style.height = "100%";
                });

                function onChange(){
                    if(input.value != cellValue){
                        success(luxon.DateTime.fromFormat(input.value, "yyyy-MM-dd").toFormat("dd/MM/yyyy"));
                    }else{
                        cancel();
                    }
                }

                //submit new value on blur or change
                input.addEventListener("blur", onChange);

                //submit new value on enter
                input.addEventListener("keydown", function(e){
                    if(e.keyCode == 13){
                        onChange();
                    }

                    if(e.keyCode == 27){
                        cancel();
                    }
                });

                return input;
            };

                console.log(tableData);
                // GESTIONE TABELLA
                //1: Deve apririsi solo quando viene cliccata la tab "player_table"
                //2: i dati vengono da una richiesta ajax;
                //3: Capire come funziona la richiesta ajax, come riceverla in upload.php e rimandarla indietro per elaborarla
                var table = new Tabulator("#top_players", {
                data: tableData,
                layout:"fitDataTable", // default: fitColumns | fitDataTable : usa solo spazio necessario | fitDataFill | fitDataStretch : riempe pagina
                resizableColumns:false,
                responsiveLayout: "collapse", 
                placeholder:"No Data Available",
                columns:[
                    {title:"id", field:"id", hozAlign:"right", width:50},
                    {//Player general info
                        title:"Player Info",
                        columns:[
                        {title:"name", field:"name", hozAlign:"right", sorter:"number", width:120, editor:"input"},
                        {title:"power", field:"power", hozAlign:"right", width:100, editor:"input"},
                        {title:"merits", field:"merits", hozAlign:"right", width:100, editor:"input"},
                        ],
                    },
                    {//Alleanza e regno
                        title:"Alliance and Kingdom",
                        columns:[
                        {title:"alliance", field:"alliance", hozAlign:"right", sorter:"number", width:120, editor:"input"},
                        {title:"kingdom", field:"kingdom", hozAlign:"center", width:120, editor:"input"},
                        ],
                    },
                    {//Army
                        title:"Alliance and Kingdom",
                        columns:[
                        {title:"troops type", field:"troops_type", hozAlign:"right", sorter:"number", width:120, editor:"input"},
                        {title:"troops tier", field:"troops_tier", hozAlign:"center", width:120, editor:"input"},
                        ],
                    },
                ],
            });

*/
            } catch(e) {
                alert('Errore nel parsing JSON: ' + e.message);
                console.error(e);
            }
        }
    
        });

        });


   

    }); 

// Mostra il nome del file selezionato
$('#zip').change(function() {
    var fileName = $(this).val().split('\\').pop();
    $('#file-info').text(fileName || 'Nessun file selezionato');


});


// GESTIONE MODALE AGGIUNGI UTENTI:

                    // var myModal = new bootstrap.Modal(document.getElementById('myModal'));
                    // myModal.show();










        </script>
    </body>
</html>
<?php require_once __DIR__ . '/vendor/autoload.php'; ?>
<!DOCTYPE html>
<html lang="en" id="wholepage">
    <head> 
    <!-- jQuery-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>   
        <!-- DataTables-->
        <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> -->
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- TABELLA BELLA -->

<script src="https://unpkg.com/tabulator-tables@5.4.3/dist/js/tabulator.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/tabulator-tables@5.4.3/dist/css/tabulator.min.css">


    </head>
    <body>


<div id="example-table"></div>




<script>
// import {Tabulator} from 'tabulator-tables';
//GESTIONE upload file




//Create Date Editor
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


//Build Tabulator
var table = new Tabulator("#example-table", {
    height:"311px",
    columns:[
        {title:"Name", field:"name", width:150, editor:"input"},
        {title:"Location", field:"location", width:130, editor:"list", editorParams:{autocomplete:"true", allowEmpty:true,listOnEmpty:true, valuesLookup:true}},
        {title:"Progress", field:"progress", sorter:"number", hozAlign:"left", formatter:"progress", width:140, editor:true},
        {title:"Gender", field:"gender", editor:"list", editorParams:{values:{"male":"Male", "female":"Female", "unknown":"Unknown"}}},
        {title:"Rating", field:"rating",  formatter:"star", hozAlign:"center", width:100, editor:true},
        {title:"Date Of Birth", field:"dob", hozAlign:"center", sorter:"date", width:140, editor:dateEditor},
        {title:"Driver", field:"car", hozAlign:"center", editor:true, formatter:"tickCross"},
    ],
});
























// GESTIONE TABELLA
/*
    var table = new Tabulator("#example-table", {
    height:"300px",
    width:"300px",
    pagination: "local",
    paginationSize: 10,
    columns:[
    {title:"id", field:"id"},
    {title:"name", field:"name"},
    {title:"power", field:"power"},
    {title:"merits", field:"merits"},
        ],
    data:[
        {id: 101, name: "VirTitus", power: 12481000, merits: 858700},
        {id: 101, name: "VirTitus", power: 12481000, merits: 858700},
        {id: 101, name: "VirTitus", power: 12481000, merits: 858700},
        {id: 101, name: "VirTitus", power: 12481000, merits: 858700},
        {id: 101, name: "VirTitus", power: 12481000, merits: 858700},
    ]  
});
*/
/*
    var table = new Tabulator("#example-table", {
    height:"311px",
    pagination: "local",
    paginationSize: 10,
    columns:[
    {title:"id", field:"id"},
    {title: "Progress", field: "progress", sorter: "number"},
    {title:"Gender", field:"gender"},
    {title:"Rating", field:"rating"},
    {title:"Favourite Color", field:"col"},
    {title:"Date Of Birth", field:"dob", hozAlign:"center"} 
        ],
    data:[
        {name: "Alice", progress: 80, gender: "Female", rating: 5, col: "Red", dob: "1992-07-12" },
        { name: "Bob", progress: 45, gender: "Male", rating: 3, col: "Green", dob: "1988-04-05" }
    ]  
});

*/




        </script>
    </body>
</html>
#!/usr/bin/php -q
<?php

function start () {
  
    $option =  null;
    echo "1 - Add a record\n";
    echo "2 - Edit a record\n";
    echo "3 - Delete a record\n";
    echo "4 - Print list of records for specific date\n";
    echo "5 - Exit\n";

    $option = (int)fread(STDIN, 80);
    
    
    if (!is_numeric($option) || $option > 5  || $option < 1)
    {
    echo "invalid command\n";
    start();
    }
    else if ($option == "1"){
        add_record();
    }
    else if ($option == "2"){
        edit_record();
    }
    else if ($option == "3"){
        delete_record();
    }
    else if ($option == "4"){
        sort_by_date();
    }
    else if ($option == "5"){
        return 0;
    }


}

function add_record() {
    $last_id = find_last_id();
    $filename = './record.csv';
    $f = fopen($filename, 'a');
    if ($f === false) {
	echo 'Cannot open the file ', $filename;
    start();
    }
    else{
        $data = array();
        $data[0] = $last_id;
        echo "Enter your name\n";
        $data[1] = fread(STDIN, 80);
        echo "Enter your e-mail\n";
        $data[2] = fread(STDIN, 80);
        echo "Enter your phone number\n";
        $data[3] = fread(STDIN, 80);
        echo "Enter your apartament adress\n";
        $data[4] = fread(STDIN, 80);
        echo "Enter month mm\n";
        $data[5] = fread(STDIN, 80);
        echo "Enter day dd\n";
        $data[6] = fread(STDIN, 80);
        echo "Enter time 24h \n";
        $data[7] = fread(STDIN, 80);
        fputcsv($f, $data);
    }
    echo "Record added!\n";
    start();
}

function find_last_id (){
    $row = 1;
    if (($handle = fopen("record.csv", "r+")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        $row++;
    }
    fclose($handle);
    }
    return $row;
}


function edit_record () {
    echo "Enter ID of a record you want to edit\n";
    $id = fread(STDIN, 10);
    $data = find_record($id);
    if(!isset($data)){
        echo "Does not exist.\n";
        start();
    }
    echo "1 - Name: ", $data[1],"\n", "2 - Email: ", 
    $data[2],"\n", "3 - Phone number: ", $data[3],"\n", "4 - Adress: ", 
    $data[4],"\n", "5 - Month: ", $data[5], "\n","6 - Day: ", $data[6], 
    "\n","7 - Time: ", $data[0], "\n"; 

    echo "What do you want to edit?\n";
    $edit = fread(STDIN, 10);
    if(!is_numeric($edit) || $edit > '7' || $edit < '1' ){
        echo "invalid\n";
        start();
    }
    $edit = (int) $edit;
    echo "Enter new value: \n";
    $new_field = fread(STDIN, 80);
    $data[$edit] = $new_field;
    overwrite($data);
    echo "Edited!";
    start();
    foreach($data as $value)
    {
        echo $value; 
    }
}

function find_record ($id){
    $row = 1;
    if (($handle = fopen("record.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        if($data[0] == $id){
            return $data;
        }
        $row++;
        for ($c=0; $c < $num; $c++) {
        }
    }
    fclose($handle);
    }
}

function overwrite ($duom){
    $viskas = array();
    $row = 1;
if (($handle = fopen("record.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        $row++;
            if($data[0] == $duom[0]){
                array_push($viskas, $duom);
        }else{
            array_push($viskas, $data);
        }
    }
    fclose($handle);
    $changed =  fopen("record.csv", 'w');
    foreach($viskas as $value)
    {
        fputcsv($changed, $value);
    }
    fclose($changed);
    echo "Entry has been changed!\n";
    start();
       
}}

function delete_record(){
    echo "Enter ID of a record you want to delete\n";
    $id = fread(STDIN, 10);
    $viskas = array();
    $row = 1;
if (($handle = fopen("record.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        $row++;
            if($id != $data[0]){
                array_push($viskas, $data);
        }
    }
    fclose($handle);
    $deleted =  fopen("record.csv", 'w');
    foreach($viskas as $value)
    {
        fputcsv($deleted, $value);
    }
    fclose($deleted);
    echo "Entry has been deleted!\n";
    start();

}}

function sort_by_time($viskas){
    $times = count($viskas);
    $bigest_time = 24;
    $save = 0;
    $sorted = array();
    
    $i = 0;
    $save_i = 0;
    foreach($viskas as $key=>$value){
        if($value[7] < $bigest_time){
            $bigest_time = $value[7];
            $save= $value;
            $save_i = $key;
        }
    }
    echo ":::::::::::::::\n";
    echo "Name: ", $save[1], "Email: ",$save[2], "Phone: ",$save[3],"Adress: ", $save[4],"Month: ", $save[5], "Day: ",$save[6], "Time: ",$save[7];

    unset($viskas[$save_i]);
    if(count($viskas) != 0){
        sort_by_time($viskas);
    }

    return $sorted;

}
function sort_by_date(){
    echo "Enter number of month\n";
    $month = fread(STDIN, 10);
    echo "Enter number of day\n";
    $day = fread(STDIN, 10);

    $viskas = array();
    $row = 1;
if (($handle = fopen("record.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        $row++;
            if($month == $data[5] && $day == $data[6]){
                array_push($viskas, $data);
        }
    }
    fclose($handle);

    sort_by_time($viskas);

    start();
}
}


start();







?>
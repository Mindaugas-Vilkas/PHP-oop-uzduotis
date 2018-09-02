<?php
    $manual = "\n\n
    Create - Create a new entry in the database.
    Expects: first_name;last_name;email;primary_phone_number;secondary_phone_number;comments
    
    Show by name - Displays the data for a particular entry
    Expects: first_name;last_name
    
    Show by email - Displays the data for a particular entry
    Expects: email
    
    Update - Matches the entry by name and last name and updates the other fields to given values.
    Expects: first_name;last_name;email;primary_phone_number;secondary_phone_number;comments
    
    Delete - Deletes the entry that matches the name and last name.
    Expects: first_name;last_name
    
    Delete id - Deletes the entry with the provided ID. Use show to find the IDs from this program.
    Expects: id
    
    Import - Imports a file of new employees as long as it is properly formatted.
    Expects: DriveLetter://Path/To/File or relative path to file.
    
    Exit - Ends the program.
    ";

    $example = 'first_name;last_name;email;primary_phone_number;secondary_phone_number;comments';
    $exampleName = 'first_name;last_name';
    $exampleEmail = 'mailname@mailserver.ext';

    $test = 'C://xampp/htdocs/phpoop/MOCK_UP.csv';
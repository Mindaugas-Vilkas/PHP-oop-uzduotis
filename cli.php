<?php

/*Example:
Steve;Guy;steve.guy@buddy.fr;+312548745;+245154545;He's not my buddy, guy!
Poe;Tato;potato@badjoke.ha;+325487548;+365784541;Delicious boiled, with dill and a cream sauce on the side.
*/
class CLI
{
    public function create($csvString, $connection){

        $validatedArray = CLI::validateSingle($csvString);

        if ($validatedArray == null)
        {
            //Error has occured, we're noping out.
            return null;
        }

        $dbResponse = CLI::findIdByName($validatedArray[0], $validatedArray[1]);

        if($dbResponse == null) {
            $connection->query("INSERT INTO employees(
					first_name,
					last_name,
					email,
					primary_phone_number,
					secondary_phone_number,
					comments) 
					VALUES('"
                . mysqli_real_escape_string($connection, $validatedArray[0]) ."','"
                . mysqli_real_escape_string($connection, $validatedArray[1]) ."','"
                . mysqli_real_escape_string($connection, $validatedArray[2]) ."','"
                . mysqli_real_escape_string($connection, $validatedArray[3]) ."','"
                . mysqli_real_escape_string($connection, $validatedArray[4]) ."','"
                . mysqli_real_escape_string($connection, $validatedArray[5]) ."')");

            echo$validatedArray[0] . " " . $validatedArray[1] ." was successfully added as a new entry.\n";
        }
        elseif (is_string($dbResponse))
        {
            // If we found an ID matching that name lastname then this is a duplicate.
            echo$validatedArray[0] . " " . $validatedArray[1] ." is already in the database, consider updating their information instead. Or delete their previous entry.\n";
        }
        elseif (is_array($dbResponse))
        {
            echo$validatedArray[0] . " " . $validatedArray[1] ." is repeated multiple times in the database. Please delete one or all the multiples before updating or recreating their data.\n";
        }
    }

    public function showByName($csvString, $connection)
    {
        $validatedArray = CLI::validateName($csvString);

        if ($validatedArray == null)
        {
            //Error has occured, we're noping out.
            return null;
        }

        $dbResponse = CLI::findIdByName($validatedArray[0], $validatedArray[1]);

        CLI::showLogic($dbResponse);
    }

    public function showByEmail($email, $connection)
    {
        $validatedEmail = CLI::validateEmail($email);

        if ($validatedEmail == null)
        {
            //Error has occured, we're noping out.
            return null;
        }

        $dbResponse = CLI::findIdByEmail($validatedEmail);

        CLI::showLogic($dbResponse);
    }

    public function update($csvString, $connection)
    {
        $validatedArray = CLI::validateSingle($csvString);

        if ($validatedArray == null)
        {
            //Error has occured, we're noping out.
            return null;
        }

        $dbResponse = CLI::findIdByName($validatedArray[0], $validatedArray[1]);

        if($dbResponse == null)
        {
            echo 'Could not find entry to update';
        }
        elseif (is_string($dbResponse))
        {
            $connection->query("UPDATE employees
					SET 
					email='". mysqli_real_escape_string($connection, $validatedArray[2]) ."',
					primary_phone_number='". mysqli_real_escape_string($connection, $validatedArray[3]) ."',
					secondary_phone_number='". mysqli_real_escape_string($connection, $validatedArray[4]) ."',
					comments='". mysqli_real_escape_string($connection, $validatedArray[5]) ."'
					WHERE first_name='". mysqli_real_escape_string($connection, $validatedArray[0])
                ."' AND last_name='". mysqli_real_escape_string($connection, $validatedArray[1]) ."'");
        }
        elseif (is_array($dbResponse))
        {
            echo 'Found multiple entries for the provided person. Delete one before updating.';
        }
    }

    public function delete($csvString, $connection)
    {
        $validatedArray = CLI::validateName($csvString);

        if ($validatedArray == null)
        {
            //Error has occured, we're noping out.
            return null;
        }

        $dbResponse = CLI::findIdByName($validatedArray[0], $validatedArray[1]);

        if($dbResponse == null) {
            echo"No entry found that matched.\n\n";
        }
        elseif (is_string($dbResponse))
        {
            // If only one ID retrieved it's a safe and simple delete.
            $connection->query("DELETE FROM employees WHERE(first_name='"
                . mysqli_real_escape_string($connection, $validatedArray[0]) ."' AND last_name='"
                . mysqli_real_escape_string($connection, $validatedArray[1]) ."')");
            echo"Removed " . $validatedArray[0] . " " . $validatedArray[1] . " from database\n";

        }
        elseif (is_array($dbResponse))
        {
            echo"Multiple entries matches the provided data. Please use Delete Id to delete one specific entry \n";
            foreach($dbResponse as $d)
            {
                echo"ID: {$d}\n";
            }
        }

    }

    public function deleteId($id, $connection)
    {
        $connection->query("DELETE FROM employees WHERE(id='"
            . mysqli_real_escape_string($connection, $id) . "')");

        echo "Entry deleted\n";
    }

    public function import($file, $connection)
    {
        $stringArray =[];
        if(($csvFile = fopen($file, 'r')) !== false)
        {
            $row = 0;
            while(($data = fgetcsv($csvFile, 1000, ',')) !== false)
            {
                $num = count($data);
                echo $row . "\n";

                print_r($data);
                $stringArray[$row] = '';
                foreach ($data as $d) {
                    $stringArray[$row] .= $d . ',';
                }
                $row++;
            }
            fclose($csvFile);
        }
        print_r($stringArray);

        foreach ($stringArray as $key => $csvString) {
            CLI::create($csvString, $connection);
        }
    }

    private function findIdByEmail($email)
    {
        $connection = new mysqli('localhost', 'root', '', 'phpoop');

        // Test connection
        if ($connection->connect_error)
        {
            return $error = 'There was an issue connecting to the database.';
        }
        else {
            $queryResult = $connection->query('SELECT id FROM employees WHERE email="' . mysqli_real_escape_string($connection, $email) . '"');

            $returnedRows = $queryResult->num_rows;

            if ($returnedRows == 0/*null rows*/) {
                $connection->close();
                return false;
            } elseif ($returnedRows == 1/*one row*/) {
                $connection->close();
                return $queryResult->fetch_object()->id;
            } else {
                $data = [];
                while ($row = $queryResult->fetch_array()) {
                    array_push($data, $row['id']);
                }
                $connection->close();
                return $data;
            }
        }
    }

    private function findIdByName($name, $lastName)
    {
        $connection = new mysqli('localhost', 'root', '', 'phpoop');

        // Test connection
        if ($connection->connect_error)
        {
            return $error = 'There was an issue connecting to the database.';
        }
        else
        {
            $queryResult = $connection->query('SELECT id FROM employees WHERE first_name="'. mysqli_real_escape_string($connection, $name) .'" AND last_name="'. mysqli_real_escape_string($connection, $lastName) .'"');

            $returnedRows = $queryResult->num_rows;

            if($returnedRows == 0/*null rows*/)
            {
                $connection->close();
                return false;
            }

            elseif ($returnedRows == 1/*one row*/)
            {
                $connection->close();
                return $queryResult->fetch_object()->id;
            }
            else
            {
                $data = [];
                while($row = $queryResult->fetch_array())
                {
                    array_push($data, $row['id']);
                }
                $connection->close();
                return $data;
            }
        }
    }

    public function validateSingle($csvString)
    {
        $array = explode(';', $csvString);

        if (sizeof($array) > 7)
        {
            print_r('The data has too many fields, please double check the input.');
            return null;
        }

        // Check for the member resulting from trailing semi-colon
        // and unset if true.
        if (sizeof($array) > 6 && substr($csvString, -1) == ';')
        {
            unset($array[sizeof($array)-1]);
        }
        if (sizeof($array) < 6)
        {
            print_r('There are fields missing in the data, please double check the input.');
            return null;
        }

        // Validate email
        if ( filter_var($array[2], FILTER_VALIDATE_EMAIL) === false)
        {
            print_r("Please double check the email you\'ve provided.\n\r");
            return null;
        }
        return $array;
    }

    public function validateName($csvString)
    {
        $array = explode(';', $csvString);

        if (sizeof($array) > 3)
        {
            print_r('The data has too many fields, please double check the input.');
            return null;
        }

        // Check for the member resulting from trailing semi-colon
        // and unset if true.
        if (substr($csvString, -1) == ';')
        {
            unset($array[sizeof($array)-1]);
        }

        if (sizeof($array) < 2)
        {
            print_r('There are fields missing in the data, please double check the input.');
            return null;
        }
        return $array;
    }

    public function validateEmail($email)
    {
        // Validate email
        if ( filter_var($email, FILTER_VALIDATE_EMAIL) === false)
        {
            print_r("Please double check the email you\'ve provided.\n\r");
            return null;
        }

        return $email;
    }

    private function showLogic($dbResponse)
    {
        if($dbResponse == null) {
            echo'No entry found that matched.';
        }
        elseif (is_string($dbResponse))
        {
            $connection = new mysqli('localhost', 'root', '', 'phpoop');
            $result = mysqli_fetch_assoc($connection->query("SELECT * FROM employees WHERE(id='"
                . $dbResponse ."')"));

            // Outputs the result.
            echo"\n\r";
            foreach ($result as $key => $val)
            {
                echo"{$key}: {$val}, \n";
            }
        }
        elseif(is_array($dbResponse))
        {
            $connection = new mysqli('localhost', 'root', '', 'phpoop');
            $result = [];
            foreach ($dbResponse as $d)
            {
                $res = $connection->query("SELECT * FROM employees WHERE(id='"
                    . $d . "')");
                array_push($result, mysqli_fetch_assoc($res));
            }

            echo"\n\n\r";

            //Outputs multiple results. Could be nicer but output is secondary importance.
            foreach($result as $r)
            {
                echo"\n\r";
                foreach ($r as $key => $val)
                {
                    echo"{$key}: {$val}, \n";
                }
                echo"\n";
            }

        }
    }

    public function connectToDb($credentials)
    {
        echo"Testing database credentials.";
        $connection = new mysqli($credentials['host'], $credentials['username'], $credentials['password'], $credentials['database']);

        // Test connection
        if ($connection->connect_error)
        {
            return $error = 'There was an issue connecting to the database.';
        }
        else
        {
            echo"Success.\n\n";
        }

        return $connection;
    }
}
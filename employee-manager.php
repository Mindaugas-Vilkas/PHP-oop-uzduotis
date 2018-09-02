<?php
    
    require 'cli.php';
    require 'strings.php';

    echo"\n\n\n       Welcome to the employee manager.\n";

    $handle = fopen ("php://stdin","r");
    $cli = new CLI;

    $credentials['host'] = '';
    $credentials['username'] = '';
    $credentials['password'] = '';
    $credentials['database'] = '';

    //TODO: Login to database.
    do
    {
        foreach($credentials as $key => $cred){
            echo"\n";
            $command = trim(readline("Enter {$key}: "));
            readline_add_history($command);
            $credentials[$key] = $command;
        }
        break;
    } while ($credentials['host'] != false
    || $credentials['username'] != false
    || $credentials['password'] != false
    || $credentials['database'] != false);

    $connection = $cli->connectToDb($credentials);

    do
    {
        echo"\nTo check the manual enter -h.\n";
        $command = trim(strtolower(readline("Enter Command: ")));
        readline_add_history($command);
        switch ($command) {
            case "-h":
                echo $manual;

                break;

            case 'create':
                echo"Enter text as \n'{$example}'\n";
                $command = trim(readline("Input: "));
                $cli->create($command, $connection);
                break;

            case 'show by name':
                echo"Enter text as \n'{$exampleName}'\n";
                $command = trim(readline("Input: "));
                $cli->showByName($command, $connection);
                break;

            case 'show by email':
                echo"Enter text as \n'{$exampleEmail}'\n";
                $command = trim(readline("Input: "));
                $cli->showByEmail($command, $connection);
                break;

            case 'update':
                echo"Enter text as \n'{$example}'\n";
                $command = trim(readline("Input: "));
                $cli->update($command, $connection);
                break;

            case 'delete':
                echo"Enter text as \n'{$exampleName}'\n";
                $command = trim(readline("Input: "));
                $cli->delete($command, $connection);
                break;

            case 'delete id':
                $command = trim(readline("Enter ID: "));
                $cli->deleteId($command, $connection);
                break;

            case 'import':
                echo"Enter file location from disk ex:'C://file/location'\n";
                $command = trim(readline("Input: "));
                $cli->import($command, $connection);
                break;

            case 'exit':
                $connection->close();
                break;

            default: print "Use '-h' for the command manual. Use 'exit' to turn the script off.";
        }
    } while ($command!='exit');


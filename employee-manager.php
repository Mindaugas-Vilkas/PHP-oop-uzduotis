<?php
    require 'cli.php';
    require 'strings.php';

    echo"\n\n\n       Welcome to the employee manager.\n";

    $handle = fopen ("php://stdin","r");
    $cli = new CLI;

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
                readline_add_history($command);
                $cli->create($command);
                break;

            case 'show by name':
                echo"Enter text as \n'{$exampleName}'\n";
                $command = trim(readline("Input: "));
                readline_add_history($command);
                $cli->showByName($command);
                break;

            case 'show by email':
                echo"Enter text as \n'{$exampleEmail}'\n";
                $command = trim(readline("Input: "));
                readline_add_history($command);
                $cli->showByEmail($command);
                break;

            case 'update':
                echo"Enter text as \n'{$example}'\n";
                $command = trim(readline("Input: "));
                readline_add_history($command);
                $cli->update($command);
                break;

            case 'delete':
                echo"Enter text as \n'{$exampleName}'\n";
                $command = trim(readline("Input: "));
                readline_add_history($command);
                $cli->delete($command);
                break;

            case 'delete id':
                $command = trim(readline("Enter ID: "));
                readline_add_history($command);
                $cli->deleteId($command);
                break;

            case 'import':
                echo"Enter file location from disk ex:'C://file/location'\n";
                $command = trim(readline("Input: "));
                readline_add_history($command);
                $cli->import($command);
                break;

            case 'exit':
                echo"Shutting down.\n";
                break;

            default: print "Use '-h' for the command manual. Use 'exit' to turn the script off.";
        }
    } while ($command!='exit');




<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=todolist', 'root', '');

}


catch (PDOException $e) {
    echo "Error!: " . $e->getMessage() . "<br/>";
    exit();
}


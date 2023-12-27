<?php

require_once "$_SERVER[DOCUMENT_ROOT]/RichRicasso/globals.php";

$productsController = $controller->getProductsController();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $post = $_POST;

    // SECOND PARAM IS TO SAY WHICH COLUMNS TO RETURN, BY DEFAULT IT RETURNS ALL COLUMNS
    $products = $productsController->filter($post, ['name', 'type', 'color', 'price', 'description']);

    // PRINTING THE RESULT OF FILTERING
    foreach ($products as $product) {
        print("<br>");
        print_r($product);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<body>
    <form action="#" method="POST">
        <select name="type" id="type"> 
            <option value="none">none</option>
            <option value="Tie">Tie</option> 
            <option value="Shirt">Shirt</option> 
        </select>

        <select name="color" id="color"> 
            <option value="none">none</option>
            <option value="Pink">Pink</option> 
            <option value="Blue">Blue</option> 
            <option value="Purple">Purple</option> 
            <option value="Fuschia">Fuschia</option> 
        </select>

        <select name="gender" id="gender"> 
            <option value="none">none</option>
            <option value="M">M</option> 
            <option value="F">F</option> 
            <option value="U">U</option> 
        </select>

        <label for="minprice">minPrice:</label>
        <input type="range" id="minprice" name="minprice" min="0" max="2000" step="500" value="3">

        <label for="maxprice">maxPrice:</label>
        <input type="range" id="maxprice" name="maxprice" min="0" max="1000000" step="500" value="1000000">

        <input type="submit" value="Send Request" />
    </form>
</body>
</html>
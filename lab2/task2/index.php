<?php
session_start();
$dom = new DOMDocument('1.0');
$filename = 'employees.xml';
$xmlDocument = file_get_contents($filename);
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($xmlDocument);

if ($_GET["action"] === "next" && intval($_SESSION["index"]) + 1 < $dom->documentElement->childNodes->length) {

  $_SESSION["index"] += 1;
}

if ($_GET["action"] === "prev" && $_SESSION["index"] > 0) {

  $_SESSION["index"] -= 1;
}

$index = $_SESSION["index"] ?? 0;

$employees = $dom->documentElement;

$employee = $employees->childNodes[$index];

$name = $employee->childNodes[0]->nodeValue;

$phones = $employee->childNodes[1];
$phone = $phones->childNodes[0]->nodeValue;

$addresses = $employee->childNodes[2];
$address = $addresses->childNodes[0];

$street = $address->childNodes[0]->nodeValue;
$buildingNumber = $address->childNodes[1]->nodeValue;
$region = $address->childNodes[2]->nodeValue;
$city = $address->childNodes[3]->nodeValue;
$address = "$street - $buildingNumber - $region - $city";
$email = $employee->childNodes[3]->nodeValue;

if ($_GET["action"] === "insert") {

  $_SESSION["index"] += 1;
  $employees = $dom->documentElement;

  $newEmployee = $dom->createElement("employee");


  $name = $dom->createElement("name", $_GET["name"]);
  $newEmployee->appendChild($name);


  $phones = $dom->createElement("phones");
  $phone = $dom->createElement("phone", $_GET["phone"]);
  $phones->appendChild($phone);

  $newEmployee->appendChild($phones);


  $addresses = $dom->createElement("addresses");


  $addressArray = explode(" - ", $_GET["address"]);

  $address = $dom->createElement("address");
  $street = $dom->createElement("street", $addressArray[0]);
  $buildingNumber = $dom->createElement("buildingNumber", $addressArray[1]);
  $region = $dom->createElement("region", $addressArray[2]);
  $city = $dom->createElement("city", $addressArray[3]);

  $address->appendChild($street);
  $address->appendChild($buildingNumber);
  $address->appendChild($region);
  $address->appendChild($city);

  $addresses->appendChild($address);

  $newEmployee->appendChild($addresses);



  $email = $dom->createElement("email", $_GET["email"]);
  $newEmployee->appendChild($email);

  $employees->appendChild($newEmployee);

  // $dom->appendChild($newEmployee);


  echo $dom->saveXML();

  file_put_contents($filename, $dom->saveXML());

  header("location: /");
}


if ($_GET["action"] === "delete") {

  $removedChild = $dom->documentElement->childNodes[$_SESSION["index"]];

  $dom->documentElement->removeChild($removedChild);

  // var_dump($removeChild);

  file_put_contents($filename, $dom->saveXML());
  header("location: /");

  $_SESSION["index"] -= 1;

  // $dom->documentElement->removeChild()
}

if ($_GET["action"] === "update") {

  $index = $_SESSION["index"] or die("no element selected");

  $employees = $dom->documentElement;

  $employee = $employees->childNodes[$index];

  $name = $employee->childNodes[0]->nodeValue = $_GET["name"];


  $phones = $employee->childNodes[1];

  $phone = $phones->childNodes[0]->nodeValue = $_GET["phone"];




  $addresses = $employee->childNodes[2];

  $address = $addresses->childNodes[0];

  $addressArray = explode(" - ", $_GET["address"]);


  $street = $address->childNodes[0]->nodeValue = $addressArray[0];
  $buildingNumber = $address->childNodes[1]->nodeValue = $addressArray[1];
  $region = $address->childNodes[2]->nodeValue = $addressArray[2];
  $city = $address->childNodes[3]->nodeValue = $addressArray[3];

  $email = $employee->childNodes[3]->nodeValue = $_GET["email"];


  file_put_contents($filename, $dom->saveXML());
  header("location: /");
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

</head>

<body>

  <div class="container mt-5">
    <form action="index.php">

      <div class="mb-3">
        <label for="name" class="form-label">name</label>
        <input type="text" class="form-control" name="name" id="name" value="<?php echo $name ?>">
      </div>

      <div class="mb-3">
        <label for="phone" class="form-label">phone</label>
        <input type="text" class="form-control" name="phone" id="phone" value="<?php echo $phone ?>">
      </div>

      <div class="mb-3">
        <label for="address" class="form-label">address</label>
        <input type="text" class="form-control" name="address" id="address" value="<?php echo "$address" ?>">
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" name="email" id="email" value="<?php echo $email ?>">
      </div>


      <div class="mb-3">
        <input type="submit" class="btn btn-primary" name="action" value="prev">
        <input type="submit" class="btn btn-primary" name="action" value="next">
        <input type="submit" class="btn btn-primary" name="action" value="insert">
        <input type="submit" class="btn btn-primary" name="action" value="update">
        <input type="submit" class="btn btn-primary" name="action" value="delete">
      </div>


    </form>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

</body>

</html>
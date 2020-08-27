<?php
$message = "";
if(isset($_POST['SubmitButton'])){ //check if form was submitted
  $input = $_POST['inputText']; //get input text
  //$message = "Success! You entered: ".$input; // Is used to test input to see whether SteamID64 was successfully received by POST Request
}    
?>

<?php
$xml_link = "https://steamcommunity.com/profiles/" . $input . "/?xml=1";
$result = simplexml_load_file($xml_link) or die("Error: Cannot create ProfileVACCheck Object");

$SteamID64 = $result->steamID64;

$url = "http://api.steampowered.com/ISteamUser/GetPlayerBans/v1/?key=" . $SteamAPIKey . "&steamids=" . $SteamID64 . "/";


//Make Connection

$json = json_decode(file_get_contents($url), true);

// Dump JSON
$profileVAC = $result->vacBanned;
$APIVacBans = $json["players"][0]['VACBanned'];
$DaysSinceLastBan = $json["players"][0]['DaysSinceLastBan'];
$NumberOfVACBans = $json["players"][0]['NumberOfVACBans'];

if ($profileVAC == 0){
    $profileVACTest = "No VAC on Profile";
}
else{
    $profileVACTest == "VAC on Profile";
}

if ($profileVAC == 0 and $APIVacBans == 1){
    $APIVACCheck = "YES";
    $Assumption = "This user seems to be griefing banned";
}
else{
    $APIVACCheck = "NO";
    $Assumption = "This user is not griefing banned";
}

echo "ProfileBan Status: " . $profileVACTest;
echo "<br> APIBan Status: " . $APIVacBans;
echo "<br> Days Since Last Ban: " . $DaysSinceLastBan;
echo "<br> Number of VAC Bans: " . $NumberOfVACBans;
echo "<br> Is Valid for Griefing: " . $APIVACCheck;
echo "<br>";
echo "<br>";
echo "Assumption: " . $Assumption; // Just make a sentence with whether its a griefing ban or not


?>

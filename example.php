<?php
define('API_KEY', 'XYZAPIKEYABC');//Set Steam API key

if (isset($_POST['SubmitButton']) && !empty($_POST['SubmitButton'])) { //Form was submitted and not empty
    $input = $_POST['inputText']; //get input text
} else {
    echo "Did not come from form or input was empty";
    exit;
}

$profile_XML_data = simplexml_load_file("https://steamcommunity.com/profiles/{$input}/?xml=1") or die("Error: Cannot create ProfileVACCheck Object");
$SteamID64 = $profile_XML_data->steamID64;

//Make API call
$api_call = json_decode(file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerBans/v1/?key=" . API_KEY . "&steamids={$SteamID64}"), true);

$ban_data = $api_call['players'][0];
$api_profile_is_vac_banned = $ban_data['VACBanned'];
$days_since_last_ban = $ban_data['DaysSinceLastBan'];
$VAC_bans = $ban_data['NumberOfVACBans'];
$game_bans = $ban_data['NumberOfGameBans'];

if ($profile_XML_data->vacBanned === 0) {
    $profileVACTest = "No VAC on Profile";
    if ($api_profile_is_vac_banned === 1) {
        $APIVACCheck = "YES";
        $Assumption = "This user seems to be griefing banned";
    }
} else {
    $profileVACTest = "VAC on Profile";
    $APIVACCheck = "NO";
    $Assumption = "This user is not griefing banned";
}

echo "ProfileBan Status: {$profileVACTest}<br>";
echo "APIBan Status: {$api_profile_is_vac_banned}<br>";
echo "Days Since Last Ban: {$days_since_last_ban}<br>";
echo "Number of VAC Bans: {$VAC_bans}<br>";
echo "Number of Game Bans: {$game_bans}<br>";
echo "Is Valid for Griefing: {$APIVACCheck}<br>";
echo "<br><br>";
echo "Assumption: {$Assumption}"; // Just make a sentence with whether its a griefing ban or not
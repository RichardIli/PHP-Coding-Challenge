<?php

$filePath = 'php-coding-challenge-main/sample-log.txt';

if (file_exists($filePath)) {
    $fileContent = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $data = [];

    foreach ($fileContent as $line) {

        $line = trim($line);

        $id = substr($line, 0, 12);          // ID: Position 1, Length 12
        $userID = substr($line, 12, 6);      // UserID: Position 13, Length 6
        $BytesTX = substr($line, 18, 8);     // BytesTX: Position 19, Length 8
        $BytesRX = substr($line, 26, 8);     // BytesRX: Position 27, Length 8
        $DateTime = substr($line, 34, 17);   // DateTime: Position 35, Length 17

        $data[] = [
            'ID' => trim($id),
            'UserID' => trim($userID),
            'BytesTX' => trim($BytesTX),
            'BytesRX' => trim($BytesRX),
            'DateTime' => trim($DateTime),
        ];
    }

    $outputContent = "";

    foreach ($data as $entry) {
        $formatedBytesTX = number_format($entry['BytesTX']);
        $formatedBytesRX = number_format($entry['BytesRX']);

        $dateString = $entry['DateTime'];
        $formatedDateData = formatedDate($dateString);

        // add data to the ouytputContent
        $outputContent .= "{$entry['UserID']}|{$formatedBytesTX}|{$formatedBytesRX}|{$formatedDateData}|{$entry['ID']}\n";

    }

    // create a space between ouputs
    $outputContent .= "\n\n";

    // i dont understand what is the proper sorting based on the instruction
    $sortedIds = sortedId($data);
    foreach ($sortedIds as $ID) {
        $outputContent.="$ID\n";
    }

    $outputContent .= "\n\n";

    $sortedUserIds = sortedUniqueUserIDs($data);
    foreach ($sortedUserIds as $userID) {
        $outputContent.= "$userID\n";
    }

    // Specify the output file path
    $outputFilePath = 'output.txt';
    // Write the output content to the file
    file_put_contents($outputFilePath, $outputContent);


} else {
    echo "File does not exist";
    print_r("File does not exist");
}

function formatedDate($dateString)
{
    $date = new DateTime($dateString);

    $formattedDate = $date->format('D, F d Y');

    return $formattedDate;
}

function sortedId($data)
{
    $ids = [];

    foreach ($data as $entry) {
        if (isset($entry['ID'])) {
            $ids[] = $entry['ID'];
        }
    }

    sort($ids);

    return $ids;
}

function sortedUniqueUserIDs($data)
{
    $userIDs = [];

    foreach ($data as $entry) {
        if (isset($entry['UserID'])) {
            $userIDs[] = $entry['UserID'];
        }
    }

    $uniqueUserIDs = array_unique($userIDs);

    sort($uniqueUserIDs);

    $formattedUserIDs = [];
    foreach ($uniqueUserIDs as $index => $userID) {
        $formattedUserIDs[] = "[" . ($index + 1) . "] $userID ";
    }

    return $formattedUserIDs;
}


?>
<?php
function AutoID($connect, $tableName, $fieldName, $prefix, $noOfLeadingZeros)
{
    $newID = "";
    $value = 1;

    $sql = "SELECT $fieldName FROM $tableName ORDER BY $fieldName DESC LIMIT 1";
    $result = mysqli_query($connect, $sql);
    $noOfRow = mysqli_num_rows($result);

    if ($noOfRow < 1) {
        return $prefix . str_pad(1, $noOfLeadingZeros, "0", STR_PAD_LEFT);
    } else {
        $row = mysqli_fetch_array($result);
        $oldID = str_replace($prefix, "", $row[$fieldName]);
        $value = (int)$oldID + 1;
        $newID = $prefix . str_pad($value, $noOfLeadingZeros, "0", STR_PAD_LEFT);
        return $newID;
    }
}


function NumberFormatter($number,$n) 
{	
	return str_pad((int) $number,$n,"0",STR_PAD_LEFT);
}
?>

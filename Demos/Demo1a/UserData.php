<?
    $age = 41;

    function MakeArray($quantity) {
        $stars = array(); //creating an array

        for($i=0; $i < $quantity; ++$i) {
            $stars[] = "*";
        }

        return $stars;
    }

    function PrintStars($collection) {
        $list = "<ul>";
        foreach ($collection as $key => $value) {
            $list .= "<li> $key : $value </li>";
        }
        $list .= "</ul>";
        return $list;
    }
?>
<?
    function GenerateNumbers() {
        //array of numbers
        $nums = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
        
        //randomize order of the array
        shuffle($nums);

        //return the shuffled array
        return $nums;
    }

    function MakeList($array) {
        //start the ordered list
        echo "<ol>";

        //display each number in the array in an ordered list
        for($i=0; $i<count($array); ++$i) {
            echo "<li>$array[$i]</li>";
        }

        //end the ordered list
        echo "</ol>";
    }
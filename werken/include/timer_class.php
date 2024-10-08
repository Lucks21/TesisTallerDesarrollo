<?php
class timer {
	var $t = NULL;
	var $count = 0;

	function set($mark='') {
		if ($mark=='') {
			$count++;
			$mark = "marca_$count";
		}
		$t[$mark] = $this->getmicrotime();
	}

	function show($t, $precision=4) {
		echo "<table border=1 class='texto_usmall'>\n";
		echo "<tr><td></td>\n";
		foreach (array_keys($t) as $h) { echo "<td>$h</td>"; }
		echo "</tr>\n";
		foreach (array_keys($t) as $k1) {
			echo "<tr><td>$k1</td>\n";
			foreach (array_keys($t) as $k2) {
				echo "<td>";
				$diff = $t[$k2] - $t[$k1];
				echo abs(round($diff, $precision));
				echo "</td>";
			}
			echo "</tr>\n";
		}
		echo "</table>\n";
	}

	function getmicrotime(){
		list($usec, $sec) = explode(" ",microtime());
		return ((float)$usec + (float)$sec);
	}
}
?>
		

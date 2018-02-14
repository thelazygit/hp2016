<?php
	session_start();
	include "header.php";
	if(!isset($_SESSION['username'])) {
		echo $notloggedin;
	} else {
		echo "
			<h3>ARC Hardware Product List</h3>
				<table class=st1 cellspacing=0 cellpadding=5>
				<tr><th width=350 height=15>Option</th><th>Description</th><th>Installment</th></tr>
				<tr><td>Laptop1			</td><td>	</td><td>R 700.00	</td></tr>
				<tr><td>Laptop2			</td><td>	</td><td>R 1070.00	</td></tr>
				<tr><td>Laptop3			</td><td>	</td><td>R 860.00	</td></tr>
				<tr><td>Laptop4			</td><td>	</td><td>R 1050.00	</td></tr>
				<tr><td>Option1			</td><td>	</td><td>R 488.00	</td></tr>
				<tr><td>Option2			</td><td>	</td><td>R 766.00	</td></tr>
				<tr><td>Printer1 (M402dn)		</td><td>	</td><td>R 130.00	</td></tr>
				<tr><td>Printer2 (M242dw - Color)		</td><td>	</td><td>R 125.00	</td></tr>
				<tr><td>Printer3 (M750dn - Color, A3)		</td><td>	</td><td>R 1300.00	</td></tr>
				<tr><td>Printer4 (M277dw MFP)		</td><td>	</td><td>R 170.00	</td></tr>
				<tr><td>Printer5 (M477fdn)		</td><td>	</td><td>R 240.00	</td></tr>
				<tr><td>Scanner1		</td><td>	</td><td>R 57.00	</td></tr>
				<tr><td>Dataprojector1	</td><td>	</td><td>R 850.00	</td></tr>
				<tr><td>LED-Extra	</td><td>	</td><td>R 98.00	</td></tr>
				</table>
				<table><tr><td><form action=\"index.php\" method=\"post\"><input type=\"submit\" value=\"HOME\"></form></td></tr></table>";
	}
	include "footer.php";
?>
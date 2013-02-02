<html>
	<head>
		<div id="tag_ora"></div>
		<script type="text/javascript">
			<!--
			// Clock script server-time,   http://coursesweb.net

			// use php to get the server time
			var hello = <?php
				mysql_connect("localhost", "root", "root");
				mysql_select_db("latebell");
	
				$result = mysql_query("SELECT * FROM classes");
				$array = mysql_fetch_array($result);
				echo json_encode($array);?>;
			var serverdate = new Date(<?php echo date('y,n,j,G,i,s'); ?>);

			var ore = serverdate.getHours();       // hour
			var minute = serverdate.getMinutes();     // minutes
			var secunde = serverdate.getSeconds();     // seconds


			var output = "<font size='4'><b><font size='1'>Ora server</font><br />"+ore+":"+minute+":"+secunde+"</b></font>"

			document.getElementById("tag_ora").innerHTML = hello[0];
			
			-->
		</script>
	</head>
	<body>
		<p id="text"></p>
		<button type="button" onclick="testfunction()"></button>
	</body>
</html>

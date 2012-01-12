<?php include 'index.sc.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link type="text/css" rel="stylesheet" href="css/styles.css" />
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/main.js"></script>
        <title>SQLServer Client</title>
    </head>
    <body>
        
        <div id="container">
			
			<form id="frm-sql" action="?">
				<textarea id="sql" name="sql"></textarea>
				<input type="hidden" id="save-on-history" value="">
				
				<div class="bottom-panel">
					<button type="submit">SUBMIT</button>
				</div>
			</form>
			
			<div id="main">
				
				<div id="explorer">
                    
					<div id="explorer-filter">
						<label>
							Filtro: 
							<input type="text" id="tables-filter" value="" accesskey="f" />
						</label>
					</div>
					
					<div id="explorer-tables"></div>
				</div>
				
				<div id="result">
					<div id="result-table"></div>
				</div>
			</div>
			
		</div>
		
	</body>
</html>
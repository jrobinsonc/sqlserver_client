<?php
error_reporting(0);

//---------------------------------------------------

define('ODBCINSTINI', dirname(__FILE__) . '/includes/odbcinst.ini');
define('ODBCINI', dirname(__FILE__) . '/includes/odbc.ini');

if (!is_file(ODBCINSTINI) || !is_file(ODBCINI)) die('ERROR: Debes crear los archivos de configuración (odbcinst.ini y odbc.ini) dentro del directorio "./includes/".');

putenv('ODBCINSTINI=' . ODBCINSTINI);
putenv('ODBCINI=' . ODBCINI);

//---------------------------------------------------

$ini_data = parse_ini_file(ODBCINI, TRUE) or die('ERROR: Hay un error en el archivo "odbc.ini".');

if (!isset($_GET['dns'])) 
{
    echo '<h2>Selecciona el DNS que deseas usar:</h2>';
    
    echo '<ul>';
    foreach ($ini_data as $dns_name => $dns_data)
    {
        printf('<li><a href="?dns=%1$s">%s</a> (%s)</li>', $dns_name, $dns_data['Description']);
    }
    echo '</ul>';
    
    exit;
}

if (!array_key_exists($_GET['dns'], $ini_data)) die('ERROR: Bad DNS.');

define('DNS_NAME', $_GET['dns']);

if (!isset($ini_data[DNS_NAME]['LogonID']) || !isset($ini_data[DNS_NAME]['pwd'])) die('ERROR: Hay un error en el archivo "odbc.ini".');

define('DNS_USER', $ini_data[DNS_NAME]['LogonID']);
define('DNS_PASSWORD', $ini_data[DNS_NAME]['pwd']);

//---------------------------------------------------

if (isset($_POST['fnctodo']))
{
    $odbc = @odbc_connect(DNS_NAME, DNS_USER, DNS_PASSWORD);
    
    if ($odbc === FALSE) die('ERROR: Hubo un error conectandose a MSSQL.');

	switch ($_POST['fnctodo'])
	{
		case '1':
			$sql = $_POST['sql']; 
			
            $result = @odbc_exec($odbc, $sql);
            
            if ($result === FALSE) die(odbc_error($odbc) .' - '. odbc_errormsg($odbc));
            
			$html = '<table>'; 

			// print fields names
			$fields_num = odbc_num_fields($result); 
			
			$html .= "<thead><tr>"; 
			$html .= "<th>&nbsp;</th>"; 
				
			for ($j=1; $j<= $fields_num; $j++)
			{  
				$html .= "<th>"; 
				$html .= odbc_field_name ($result, $j); 
				$html .= "</th>"; 
			}
            
			$html .= "</tr></thead>"; 
			
			// fetch tha data from the database 
			$html .= "<tbody>"; 
			$num = 1;
            
			while(odbc_fetch_row($result))
			{
				$html .= "\n<tr>"; 
				$html .= "<td>$num</td>";
                
				for ($j=1; $j<= $fields_num; $j++)
				{
					$html .= "<td>"; 
					$html .= utf8_encode(odbc_result($result, $j)); 
					$html .= "</td>";
				}
				$html .= "</tr>"; 
				
				$num++;
			}
			
			$html .= "\n</tbody>"; 
			
			$html .= "</table>"; 
			
			exit($html);
			
			break;
			
		case '2':
			
			$result = odbc_tables($odbc);

			while (odbc_fetch_row($result))
			{
				if(in_array(odbc_result($result,"TABLE_TYPE"), array('TABLE', 'VIEW')))
				{
					$table_name = odbc_result($result,"TABLE_NAME");
                    $table_type = odbc_result($result,"TABLE_TYPE");
                    
					echo sprintf('<a href="javascript:;" class="%s db-object" rel="%s">%s</a>', strtolower($table_type), strtolower($table_name), $table_name);
				}
			}

			exit;
			break;
	}

}

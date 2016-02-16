<?php
Class MySQL_DB
{
	private $host;
	private $user;
	private $pass;
	private $dbname;
	private $con;
	private $db;
	private $encoding; 
	private $results;
	private $lastID;

	public function MySQL_DB($host,$user,$pass,$dbname,$encoding)
	{

		$this->host = $host;	
		$this->user = $user;
		$this->pass = $pass;
		$this->dbname = $dbname;
		$this->encoding = $encoding;

//--------------connection mysql------------------------------------------

		$this->con = mysql_connect($this->host,$this->user,$this->pass);
		$this->db = mysql_select_db($this->dbname);
		mysql_query("SET CHARSET".$this->encoding);
//------------------------------------------------------------------------	
	}

/////////////////////mina///////////////////

/*class dbConnect 
{



private $_connection;
private static $_instance; //The single instance
private $_host = "localhost";
private $_username = "root";
private $_password = "";
private $_database = "blog_db";

public static function getInstance() {
if(!self::$_instance) { 
self::$_instance = new self();
}
return self::$_instance;
}
// Constructor
private function __construct() {
$this->_connection = new mysqli($this->_host, $this->_username, 
$this->_password, $this->_database);

// Error handling
if(mysqli_connect_error()) {
trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(),
E_USER_ERROR);
}
}
// Magic method clone is empty to prevent duplication of connection
private function __clone() { }
// Get mysqli connection
public function getConnection() {
return $this->_connection;
}

} */

//////////////////////////////////////////////////////////////////////////

	public function get_pk($table)
	{
		mysql_select_db($this->dbname);
		$q = "select column name from KEY_COLUMN_USAGE
			  where	table_schema='".$this->dbname."'
			  and table_name='".$table."'
			  and CONSTRAINT_NAME='primary'";

		  $r = mysql_query($q) or die(mysql_error());
		  $row = mysql_fetch_assoc($r);
		  mysql_select_db($this->dbname);
		  return $row['column_name'];	
		}






//function to select one row-------
	public function get_one($info)
	{

		$q = "select".$info['fields']."from".$info['table']."where".$info['where'];	
		$r = mysql_query($q) or die(mysql_error());
		$row = mysql_fetch_assoc($r);
		return $row;	
	}


	public function get_last($table)
	{
		$q = "select * from ".$table."order by ".$this->get_pk($table)." desc limit 1";
		$r = mysql_query($q) or die(mysql_error());
		$row = mysql_fetch_assoc($r);
		return $row;	


	}


//get first row ID--------------------

public function get_first($table)
	{
		$q = "select * from ".$table."limit 1";
		$r = mysql_query($q) or die(mysql_error());
		$row = mysql_fetch_assoc($r);
		return $row;	
	}



//------------------select function------------------

	public function get ($info)
	{

		$q = "select".$info['fields']."from".$info['table'];

		//-----------------------------------

		if( isset ($info['where']))
		{
			$q.="where".$info['where'];		

		}

		if( isset ($info['limit']))
		{

			$q.="limit".$info['limit'];						

		}

		$this->results = mysql_query($q);
		return $this->results;

	} 

//---------------insert function--------------------

	public function set ($info)
	{
	/////////////////////////////////////////
		/*$db = dbConnect::getInstance();
		$mysqli = $db->getConnection();*/
		//--------------------------------------------
		$q = " insert into ".$info['table']." set ";
		foreach ($info['fields'] as $k => $v) {
			$q .= $k."='".$v."',";
		}

		//--------------------------------mysql connection--------------

		$this->results = mysql_query(rtrim($q,','));
		$this->lastID = mysql_insert_id();
		return $this->results;

	}	

//get last ID----------------

	public function get_lastID()
	{
		return $this->lastID;

	}


//---------Delete Function----------------------

	public function del ($info)
	{
		$q = "DELETE FROM".$info['table']."WHERE".$info['where'];
		$this->results = mysql_query($q);
		return $this->results;
//$res = $mysqli->query($query);
	}


//------------------get results------------------

	public function get_results()
	{
		$results = array();
		while ($row = mysql_fetch_assoc($this->results))
		{
			$results[] = $row;
		}

		return $results;
	}

//----------------show columns------------------

	public function get_cols($table)
	{
		$q = "SHOW COLUMNS FROM ".$table;
		$r = mysql_query($q);
		$names = array();
		while ( $row = mysql_fetch_assoc($r) )
		{
			$names[] = $row['fields'];
 		}
 		return $names;
	}

//****//video 10 minute 15:56

//----------------Show Table------------------------	
	public function show_table($table,$fields , $file_fields , $form_fields)
	{
	//get cols
		if($fields == '*')
		{
			$cols = $this->get_cols($table);
		}else
			{
				$cols = explode(',', $fields);
			}

		
		
		$msgs='';

		//Delete Record
		if( isset($_GET['del']))
		{
			$r = $this->del(array('table'=>$table,'where'=>$cols[0].'='.$_GET['id']));
			if($r)
			{
				$_SESSION['msg'] = 'Delete Done'; 
				header("Location :".$SERVER['PHP_SELF']."?success");
			}
		}

		if( isset($_SESSION['msg']))
		{

			echo '<p align="center">'.$_SESSION['msg'].'</p>';
		}

		if(isset($_GET['view']))
		{
			//get selected row
			$row = $this->get_one( array('table'=>$table , 'fields'=>'*' , 'where'=>$cols[0].'='.$_GET['id']));
			echo '<table class="myTable" border="1">';
			foreach ($row as $k => $v) 
			{
				echo '<tr>
						<th>'.$k.' : </th>
						<td>';
					if (array_key_exists( $k, $file_fields))
						{
								echo '<img src="'.$file_fields[$k].$v.'" width="200">';
						}else
							{
								echo $v;
							}	


						echo '</td>
					  </tr>';
			}

				echo '</table>';
				echo '<a href="'.$_SERVER['PHP_SELF'].'">Back</a>';

		}
		else if(isset($_GET['new']))
		{
		//add new record 
			
			echo $this->form($form_fields);
			echo '<a href="'.$_SERVER['PHP_SELF'].'">Back</a>';

		}
		else if(isset($_GET['edit']))
		{
			$row = $this->get_one( array( 'table'=>$table, 'fields'=>'*', 'where'=>"id=".$_GET['id']));
			foreach ($form_fields as $k => $v)
			{
				$form_fields[$k]['value'] = $row[$k];
			}

		//add new record 
			
			echo $this->form($form_fields);
			echo '<a href="'.$_SERVER['PHP_SELF'].'">Back</a>';

		
		}else

		{
			if( isset($_POST['addBTN']))
			{


				foreach ($_FILES['upfiles']['name'] as $k => $v) 
				{
					$fileName = $v;
					$tempFile = $_FILES['upfiles']['tmp_name'][$k];
					$upPath = "images/subjects/".$fileName; // path of image
					move_uploaded_file($tempFile, $upPath);
				}
				unset( $_POST['addBTN']);
				$r = $this->set( array( 'table'=>$table, 'fields'=>$_POST ));
				if( $r)
				{
					echo '<p>Added Done.</p>';
				}
			}
			//get rows
			$this->get( array('table'=>$table , 'fields'=>$fields));
			$rows = $this->get_results();

			//show table
			echo '<a href="?new">Add New Record</a><br>';
			echo'<table border="1" cellpadding="10"><tr>';
			foreach($cols as $col_name)
			{
				echo '<th>'.$col_name.'</th>';
			}

			echo '<th>Tasks</th>';
			echo '</tr>';
			foreach($rows as $row)
			{
				echo '<tr>';
				foreach($row as $field)
				{
					echo '<td>'.$field.'</td>';
				} 
				echo '<td>
						<a href="?view&id='. $row[$cols[0]].'">Display</a> |
						<a href="?edit&id='. $row[$cols[0]].'">Edit</a> |
						<a href="?del&id='. $row[$cols[0]].'">Delete</a>
						</td>'; 
				echo '</tr>';
			}
				
			echo '</table>';

		}
	}

	public function form($fields)
	{

		$output = '';
		$output .= '<form method="post" action="'.$_SERVER[PHP_SELF].'" enctype="multipart/form-data" class="myForm">';
			$output .='<table>';
			foreach ($fields as $name => $info)
			{
			$output .= '<tr>';
			$output .= '<td>'.$info['label'].'</td>';

			switch ( $info['type']) 
			{
						case 'text':
							$output .= '<td><input type="text" name="'.$name.'"';
							if( isset( $info['value']))
								{
									$output .= ' value="'.$info['value'].'"';	
								}
							$output .= '></td>';
							break;
						
						case 'area':
							$output .= '<td><textarea name="'.$name.'">';
							if( isset( $info['value']))
								{
									$output .= $info['value'];	
								}	
							$output .= '</textarea></td>';
							break;

						case 'pass':
							$output .= '<td><input type="password" name="'.$name.'"></td>';
							break;	

						case 'file':
							$output .= '<td><input type="file" name="upfiles['.$name.']">';
							if( isset( $info['value']))
								{
									$output .= '<br><img src="'.$info['value'].'" width="100">';	
								}	
							$output .= '</td>';
							break;		

						case 'radio':
							$output .='<td>';
							foreach ($info['opts'] as $label => $value) 
								{
								$output .= '<input type="radio" name="'.$name.'" value="'.$value.'"';
								if( isset( $info['value']))
								{
									if( $info['value'] == $value)
									{
										$output .= ' checked';
									}
								}
								$output .= '>'.$label;
							}
							$output .= '</td>';
							break;

						case 'checkbox':
							$output .='<td>';
							foreach ($info['opts'] as $label => $value) 
								{
									$output .= '<input type="checkbox" name="'.$name.'[]" value="'.$value.'"';
									if( isset( $info['value']))
									{
										if( in_array($value, $info['value']))
										{
											$output .= ' checked';
										}
									}
									$output .= '>'.$label;
								}
								$output .= '</td>';
								break;

						case 'select':
							$output .= '<td>';	
							$output .= '<select name="'.$name.'">';
							foreach( $info['opts'] as $label=>$value)
							{
								$output .= '<option value="'.$value.'"';
								if( isset( $info['value']))
								{
									if( $info['value'] == $value)
									{
										$output .= ' selected';
									}
								}
								$output .= '>'.$label.'</option>';
							}
							$output .= '</select>';
							$output .= '</td>';	
							break;
			}
						
							
							
			$output .= '</tr>';
		}

		$output .= '<tr><td></td>
					<td><input type="submit" value="Add Record" name="addBTN"></td>
					</tr>';
		$output .= '</table>';
		$output .= '</form>';
		return $output;

	}	




	//---------------close connection---------------------

	/*public function __destruct()
	{

	mysql_close ($this->con);

	}*/


}
	
$data =new MySQL_DB('localhost','root','iti','cafteria','utf_8');
var_dump($data->show_table("users","user_id","",""));
$data=$data->show_table("users","user_id","","");
while ($row= mysql_fetch_array($data)) {
	echo $row['name'];
}

?>
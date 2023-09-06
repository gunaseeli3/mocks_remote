<?php
class DBManager{
    private $conn;
    function __construct(){
        $this->conn=new MySQLi;
    }
    function __destruct(){
        if(!$this->conn->connect_error){
            $this->conn->close();
        }
    }

    function connect($host,$uname,$upass,$dbname){
        @$this->conn->connect($host,$uname,$upass,$dbname);
        if($this->conn->connect_errno){
            return FALSE;
        }
        else{
            return TRUE;
        }
    }

    function mysqli_error(){
        return $this->conn->error;
    }

    function select_db($new_dbname){
        @$this->conn->select_db($new_dbname);
        if($this->conn->error){
            return FALSE;
        }
        else{
            return TRUE;
        }
    }


    function select_query($sql){
        $res=$this->conn->query($sql);
        if($res->num_rows>0){
            if(method_exists($this->conn,"fetch_all")){
                $values = $res->fetch_all(MYSQLI_ASSOC);
                return $values;
            }
            else{
                $rows=array();
                while($row=$res->fetch_assoc()){
                    $rows[]=$row;
                }
                return $rows;
            }
        }
        else{
            return FALSE;

        }
    }

    function select_query_with_row($sql){
        $res=$this->conn->query($sql);
        if($res->num_rows==1){
            $values = $res->fetch_assoc();
            return $values;
        }
        else{
            return FALSE;

        }
    }

    function select_query_with_rows($sql){
        $res=$this->conn->query($sql);
        if($res->num_rows>0){
            $rows=array();
            while($row=$res->fetch_assoc()){
                $rows[]=$row;
            }
            return $rows;
        }
        else{
            return FALSE;

        }
    }

    function select_query_with_no_rows($sql){
        $res=$this->conn->query($sql);
        if($res->num_rows>0){
            return $res->num_rows;
        }
        else{
            return FALSE;
        }
    }

    function multi_query($sql){
        $res=$this->conn->multi_query($sql);
        if($res){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }

    function free_multi_query_result(){
        do {
            if ($result = $this->conn->store_result()) {
                while ($row = $result->fetch_row()) {
                }
                $result->free();
            }
            if ($this->conn->more_results()) {
            }
        } while ($this->conn->next_result());
    }



    function insert_query($sql){
        $res=$this->conn->query($sql);
        if($res){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }

    function update_query($sql){
        $res=$this->conn->query($sql);
        if($res){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
	
	 function add_query($field,$table){
			
			$key_value = implode(",", array_keys($field));
			$org_value = "'" . implode("','", array_values($field)) . "'" ;			
			
			  $sql="insert into $table($key_value) values ($org_value)";   
			$res=$this->conn->query($sql);
            if($res){
                return TRUE;
            }
            else{
                return FALSE;
            }
		}
		
		function add_query1($field,$table){
			
			$key_value = implode(",", array_keys($field));
			$org_value = "'" . implode("','", array_values($field)) . "'" ;			
			
			$sql="insert into $table($key_value) values ($org_value)"; 
			$res=$this->conn->query($sql);
            if($res){
                return $this->conn->insert_id;
            }
            else{
                return FALSE;
            }
		}
		
		function update_query_new($field,$table,$where){
            $update = '';
			$cn=1; $cnt_field=count($field);
			foreach($field as $key => $val)
			{
				if($cn!=$cnt_field)$comma= ", ";else $comma='';				
				$update.=$key."='".$val."'".$comma; 
				$cn++;
			}		
			
		 	$sql="Update $table set $update $where";   
			$res=$this->conn->query($sql);
			if($res){
            return TRUE;
            }
            else{
                return FALSE;
            }
		}

    function delete_query($sql){
        $res=$this->conn->query($sql);
        if($res){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }

    function removeQuote($input){
        $input=str_replace('"','\"',$input);
        $input=str_replace("'","\'",$input);
        return $input;
    }

    function count_query($sql){
        $res=$this->conn->query($sql);
        $values=$res->num_rows;
        return $values;

    }

    function count_query_new($sql){
        $res = $this->conn->query($sql);
        if ($res) {
            $row = $res->fetch_assoc(); // Fetch the result row
            return isset($row['cnt']) ? (int)$row['cnt'] : 0;
        } else {
            return 0; // Return 0 if the query failed
        }
    }

    function insert_query_last($sql){

        $res=$this->conn->query($sql);
        if($res){
            $test=$this->conn->insert_id;
            return $test;
        }
        else{
            return FALSE;
        }
    }

    function sort_query($table, $posid, $web_id)
    {
        $posid=$posid;
        $trackid=$web_id;
        $s="select * from $table order by web_id asc";
        $r=$this->conn->query($s);
        if(method_exists($this->conn,"fetch_all")){
            $fetchrow=$r->fetch_all(MYSQLI_ASSOC);
        }
        else{
            $fetchrow=array();
            while($r1=$r->fetch_assoc()){
                $fetchrow[]=$r1;
            }
        }

        foreach($fetchrow as $fetchrow)
        {
            $tid=$fetchrow['web_id'];

            if($trackid==$tid)
            {
                $oldsortid=$fetchrow['web_display_order'];
                $oldid=$fetchrow['web_id'];

                $r2="select * from $table where web_display_order='$posid' order by web_display_order asc";
                $fetchrow2c=$this->conn->query($r2);
                if(method_exists($this->conn,"fetch_all")){
                    $fetchrow2=$fetchrow2c->fetch_all(MYSQLI_ASSOC);
                }
                else{
                    $fetchrow2=array();
                    while($fetchrow2c2=$fetchrow2c->fetch_assoc()){
                        $fetchrow2[]=$fetchrow2c2;
                    }
                }

                $newpos=$fetchrow2[0]['web_display_order'];
                $newid=$fetchrow2[0]['web_id'];

                $upquery=$this->conn->query("update $table set web_display_order='$oldsortid' where web_id='$newid' ");
                $upquery1=$this->conn->query("update $table set web_display_order='$newpos' where web_id='$trackid' ");
                return true;
            }
        }
    }

    //   LOgin

    function WebAdminLogin($user,$pass){
        $sql="SELECT * FROM `".DB_PREFIX."_admin` WHERE (`username`='".$user."' AND `password`='".$pass."' AND `usertype`='admin')";
        $res=$this->conn->query($sql);
        if($res->num_rows==1){
            $row=$res->fetch_assoc();
            $_SESSION['inDooRgSeSs_id']=$row['id'];
            $_SESSION['inDooRgSeSs_username']=$row['username'];
			$_SESSION['adm_first_wel']=$row['id'];
			return TRUE;
        }
        else{
            return FALSE;
        }
    }
    
      function WebUserLogin($user,$pass){
        $sql="SELECT * FROM `".DB_PREFIX."_admin` WHERE (`username`='".$user."' AND `password`='".$pass."' AND `usertype`='user')";
        $res=$this->conn->query($sql);
        if($res->num_rows==1){
            $row=$res->fetch_assoc();
            $_SESSION['inDooRgSeSs_id']=$row['id'];
            $_SESSION['inDooRgSeSs_username']=$row['username'];
            $_SESSION['user_wel']=$row['id'];
            return TRUE;
        }
        else{
            return FALSE;
        }
    }


    function chkAuth($user,$pass){
        $sql="SELECT * FROM `".DB_PREFIX."_admin` WHERE (`username`='".$user."' AND `password`='".$pass."')";
        $res=$this->conn->query($sql);
        if($res->num_rows==1){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }

    function email_header(){
        $header_content="<html lang='en-US'>
        <head>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
        <title>Mindstory</title>
        </head>
        <body leftmargin='0' marginwidth='0' topmargin='0' marginheight='0' offset='0'>
            <div id='wrapper' dir='ltr' style='background-color: #f7f7f7; margin: 0; padding: 70px 0 70px 0; -webkit-text-size-adjust: none !important; width: 100%;'>
            <table border='0' cellpadding='0' cellspacing='0' height='100%' width='100%'><tr>
            <td align='center' valign='top'>
            <div id='template_header_image'></div>
                <table border='0' cellpadding='0' cellspacing='0' width='600' id='template_container' style='box-shadow: 0 1px 4px rgba(0,0,0,0.1) !important; background-color: #ffffff; border: 1px solid #dedede; border-radius: 3px !important;'>
                    <tr>
                            <td align='center' valign='top' style='text-align: center;'>
                            <!-- Header -->
                                <table border='0' cellpadding='0' cellspacing='0' width='600' id='template_header' style='background-color: #fff;padding: 10px;'>
                                    <tr>
                                        <td id='header_wrapper'>
                                            <img src='http://development.nskfix.com/mindstory/images/logo.png' alt='Mindstory Logo' border='0'>
                                        </td>
                                    </tr>
                                </table>
                            <!-- End Header -->
                            </td>
                    </tr>";
        return $header_content;
    }

    function email_footer(){

        $footer_content = "<tr>
                    <td align='center' valign='top' style='text-align: center;color:#fff;'>
                    <!-- footer -->
                        <table border='0' cellpadding='0' cellspacing='0' width='600' id='template_header' style='background-color: #fff;padding: 40px;font-size: 16px;color:#333'>
                            <tr>
                                <td id='header_wrappers'>
                                    Copyright © 2018 MindStory.com. All Rights Reserved
                                </td>
                            </tr>
                        </table>
                    <!-- End footer -->
                    </td>
            </tr>
            </table>
    </td>
    </table>
    </div>
</body>
</html>";
return $footer_content;

    }
	
	function sendphpmail($to_emailid,$bodytext,$subject){
     $site_email = $this->site_email();
     $message = $this->email_header();
     $message .= $bodytext;
     $message .= $this->email_footer();
     $toemail =$to_emailid;

        $headers = "From: ".$site_email."\r\n"."X-Mailer: PHP/" . phpversion();
        $headers .= "Reply-To: ".$toemail."\r\n"; 
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $headers .= "Bcc: fathima.zerosoft@gmail.com";
    

        mail($toemail,$subject,$message,$headers);
    }

    function site_email(){
        $sql="SELECT * FROM `".DB_PREFIX."_site_settings` WHERE id='1'";
        $res=$this->conn->query($sql);
        $row=$res->fetch_assoc();
        return $row['site_email'];
    }

  
	
}

$searchReplaceArray = array(
    '$' => '%24',
    '&' => '%26',
    '+'=>'%2B',
    ','=>'%2C',
    '/'=>'%2F',
    ':'=>'%3A',
    ';'=>'%3B',
    '='=>'%3D',
    '?'=>'%3F',
    "\'"=>'%27',
    "'"=>'%27',
    '"'=>'%93',
    '‘'=>'%91',
    '”'=>'%94',
    '’'=>'%92',
    '<'=>'%3C',
    '>'=>'%3E'
);

$ReplaceArray = array(
    '%24' => '$',
    '%26' => '&',
    '%2B'=>'+',
    '%2C'=>',',
    '%2F'=>'/',
    '%3A'=>':',
    '%3B'=>';',
    '%3D'=>'=',
    '%3F'=>'?',
    '%27'=>"\'",
    '%27'=>"'",
    '%93'=>'"',
    '%91'=>'‘',
    '%94'=>'”',
    '%92'=>'’',
    '%3C'=>'<',
    '%3E'=>'>'
);

function get_symbol($symbol)
{
    global $ReplaceArray; global $searchReplaceArray;
    return $rslt=str_replace(array_keys($ReplaceArray),array_values($ReplaceArray),$symbol);
}

function get_entity($symbol)
{
    global $ReplaceArray; global $searchReplaceArray;
    return $rslt=str_replace(array_keys($searchReplaceArray),array_values($searchReplaceArray),$symbol);
}

function validate_image($upload){
    $array_image=array("image/jpeg","image/jpg","image/png","image/gif");
    $array_img_ext=array(".jpeg",".jpg",".png",".gif");
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $original_extension = (false === $pos = strrpos($upload["name"], '.')) ? '' : strtolower(substr($upload["name"], $pos));
    $type = $finfo->file($upload["tmp_name"]);

    if (in_array($type, $array_image) && in_array($original_extension, $array_img_ext))
    {
        return true;
    }
    else{
        return false;
    }
}



function getBaseURL() {
    // Get the current protocol (http or https)
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";

    // Get the current host (domain)
    $host = $_SERVER['HTTP_HOST'];

    // Get the current directory (folder)
    $directory = rtrim(dirname($_SERVER['PHP_SELF']), '/');

    // Combine the protocol, host, and directory to form the base URL
    return "$protocol://$host$directory";
}

 


?>
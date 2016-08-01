<?php
header('Access-Control-Allow-Origin: *');
	require_once("Rest.inc.php");
	class API extends REST {
		public $data = "";
		const DB_SERVER = "localhost";
		const DB_USER = "root";
		// const DB_USER = "goapps";
		const DB_PASSWORD = "";
		// const DB_PASSWORD = "goapps123";
	  const DB = "omc_123";
		// adding table names
		const usersTable = "user_tbl";
		const designation = "designation_master";
		const district = "district_master";
		const ulb_tbl = "ulb_master";
		const employee_table = "employee_table";
		private $db = NULL;
		private $proxy = NULL;
		private $storeApiLogin = false;
		public $errorCodes = array(
				"200" => "The client request has succeeded",
				"201" => "Created",
				"202" => "Accepted",
				"203" => 	"Non-authoritative information.",
				"204" => 	"No content",
				"205" => 	"Reset content",
				"206" => 	"Partial content",
				"302" => "Object moved",
				"304" => "Not modified",
				"307" => "Temporary redirect",
				"400" => "Bad request",
				"401" => "Access denied",
				"402" => "Payment Required",
				"403" => "Forbidden",
				"404" => "Not found",
				"405" => "HTTP verb used to access this page is not allowed",
				"406" => "Client browser does not accept the MIME type of the requested page",
				"407" => "Proxy authentication required",
				"412" => "Precondition failed",
				"413" => "Request entity too large",
				"414" => "Request-URL too long",
				"415" => "Unsupported media type",
				"416" => "Requested range not satisfiable",
				"417" => "Execution failed",
				"423" => "Locked error",
				"500" => "Internal server error",
				"501" => "Header values specify a configuration that is not implemented",
				"502" => "Bad Gateway",
				"503" => "Service unavailable",
				"504" => "Gateway timeout",
				"505" => "HTTP version not supported"
		);
		public $messages = array(
				"operationNotDefined" => "Operation not Defined",
				"dataFetched" => "data fetched success",
				"userCreated" => "Employee added successfully",
				"deleted" => "data deleted successfully",
				"userUpdated" => "data updated successfully",
				"loginSuccess" => "Successfully Logedin",
				"userLogout" => "Successfully log out",
				"changedPassword" => "Successfully Changed your password",
				"dataSaved" => "Data saved successfully"
		);
		public function __construct(){
			parent::__construct();
			$this->dbConnect();
		}
		private function dbConnect(){
			$this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
			if($this->db)
          mysql_select_db(self::DB, $this->db) or die('ERRROR:'.mysql_error());
			else
				echo "db not exists";
		}
		public function processApi(){
			$func = '';
			if(isset($_REQUEST['service']))
				$func = strtolower(trim(str_replace("/", "", $_REQUEST['service'])));
			else if(isset($_REQUEST['reqmethod']))
				$func = strtolower(trim(str_replace("/", "", $_REQUEST['reqmethod'])));
			if($func){
				if (method_exists($this, $func)) {
					$this->$func();
				} else{
					$this->log('invalid service:'.$func." at ".date("Y-m-d H:i:s"));
					$this->response('invalid service', 406);
				}
			}
			else
				echo "invalid function";
		}
		/*
		* This is used to make a log of the error in the server end
		* (Yet to implemented) A log class that will create logs in the different files according to the type of the logs
		* like for logs for the server errors , information logs will be store in the different files
		*/
		public function log($logText,$type = 3 ,$destFile= 'error_log.txt'){
			error_log("\n".$logText,$type,$destFile);
		}
		public function json($data){
        if(is_array($data)){
            $formatted = json_encode($data);
            return $this->formatJson($formatted);
        }
				else {
					return $data;
				}
    }
    private function formatJson($jsonData){
        $formatted = $jsonData;
        $formatted = str_replace('"{', '{', $formatted);
        $formatted = str_replace('}"', '}', $formatted);
        $formatted = str_replace('\\', '', $formatted);
        return $formatted;
    }
		public function generateRandomString($length = 60) {
		    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		    $charactersLength = strlen($characters);
		    $randomString = '';
		    for ($i = 0; $i < $length; $i++) {
		        $randomString .= $characters[rand(0, $charactersLength - 1)];
		    }
		    return $randomString;
		}
		public function executeGenericDQLQuery($query){
			try{
				if(!$this->db){
					$this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
				}
				$result = mysql_query($query, $this->db);
				$rows = array();
				while($row = mysql_fetch_array($result)){
					array_push($rows,$row);
				}
				return $rows;
			}
			catch(Exception $e){
				$response = array();
				$response['status'] = false;
				$response['message'] = $e->getMessage();
				$this->response($this->json($response), 200);
			}
		}
    public function executeGenericDMLQuery($query){
      try{
        $result = mysql_query($query, $this->db);
        if(mysql_errno($this->db) != 0){
            throw new Exception("Error   :".mysql_errno($this->db)."   :  ".mysql_error($this->db));
        }
				return mysql_affected_rows(); // return the affected rows
      }
      catch(Exception $e){
        $response = array();
        $response['status'] = false;
        $response['message'] = $e->getMessage();
        $this->response($this->json($response), 200);
      }
    }
    public function executeGenericInsertQuery($query){
        try{
            $result = mysql_query($query, $this->db);
            if(mysql_errno($this->db) != 0){
                throw new Exception("Error   :".mysql_errno($this->db)."   :  ".mysql_error($this->db));
            }
            return mysql_insert_id($this->db);
        }
        catch(Exception $e){
            $response = array();
            $response['status'] = false;
            $response['message'] = $e->getMessage();
            $this->response($this->json($response), 200);
        }
    }
		public function sendResponse($statusCode,$message = null ,$data = null){
			$response = array();
			$response['statusCode'] = $statusCode;
			$response['message'] = $message;
			$response['data'] = $data;
			$this->response($this->json($response), 200);
    }
		public function login() {
			if(!isset($this->_request['user_name']) || !isset($this->_request['password']))
				$this->sendResponse(202,"Invalid user name or password");
			$user_name = $this->_request['user_name'];
			$password = md5($this->_request['password']);
			$token = $this->generateRandomString();
			$sql = "update ".self::usersTable." set user_token='$token' where user_name='$user_name'";
			$result = $this->executeGenericDMLQuery($sql);
			if($result){
				$sql = "select * from ".self::usersTable." where user_name = '$user_name' and password = '$password' limit 1";
				$rows = $this->executeGenericDQLQuery($sql);
				if(sizeof($rows)){
						$users = array();
						$users['id'] = $rows[0]['user_id'];
						$users['user_name'] = $rows[0]['user_name'];
						$users['email'] = $rows[0]['email'];
						$users['roll_id'] = $rows[0]['roll_id'];
						$users['ulb_id'] = $rows[0]['ulb_id'];
						$users['token'] = $rows[0]['user_token'];
						$users['district_id'] = $rows[0]['district_id'];
						$this->sendResponse(200,$this->messages['loginSuccess'],$users);
				}
				else {
					$this->sendResponse(201,"failure","fail");
				}
			}
			else{
				$this->sendResponse(202,"Invalid user name or password");
			}
    }
		public function logout(){
			$headers = apache_request_headers(); // to get all the headers
			$accessToken = $headers['Accesstoken'];
			if($accessToken){
				$sql = "update ".self::usersTable." set user_token='' where user_token='$accessToken'";
				$result = $this->executeGenericDMLQuery($sql);
				if($result){
					$this->sendResponse(200,$this->messages['userLogout']);
				}
			}
		}
		public function changePassword(){
			if(isset($this->_request['token'])){
				$token = $this->_request['token'];
				$password = md5($this->_request['password']);
				$sql = "update ".self::usersTable." set password='$password' where token='$token'";
				$result = $this->executeGenericDMLQuery($sql);
				if($result){
					$this->sendResponse2(200,$this->messages['changedPassword']);
				}
			}
		}
		public function getDesignationList(){
			$sql = "select * from ".self::designation;
			$rows = $this->executeGenericDQLQuery($sql);
			$desig = array();
			for($i=0;$i<sizeof($rows);$i++){
				$desig[$i]['id'] = $rows[$i]['designation_id'];
				$desig[$i]['designation'] = $rows[$i]['designation'];
			}
			$this->sendResponse(200,$this->messages['dataFetched'],$desig);
		}
		public function getDistrictList(){
			$sql = "select * from ".self::district;
			$rows = $this->executeGenericDQLQuery($sql);
			$desig = array();
			for($i=0;$i<sizeof($rows);$i++){
				$desig[$i]['id'] = $rows[$i]['district_id'];
				$desig[$i]['name'] = $rows[$i]['district_name'];
			}
			$this->sendResponse(200,$this->messages['dataFetched'],$desig);
		}
		public function getULBList(){
			$district_id = $this->_request['district_id'];
			$sql = "select * from ".self::ulb_tbl." where district_id = ".$district_id;
			$rows = $this->executeGenericDQLQuery($sql);
			$desig = array();
			for($i=0;$i<sizeof($rows);$i++){
				$desig[$i]['id'] = $rows[$i]['ulb_id'];
				$desig[$i]['name'] = $rows[$i]['ulb_name'];
			}
			$this->sendResponse(200,$this->messages['dataFetched'],$desig);
		}
		public function employee(){
			if(!isset($this->_request['operation']))
				$this->sendResponse(400,$this->messages['operationNotDefined']);
			$sql = null;
			$headers = apache_request_headers(); // to get all the headers
			$accessToken = $headers['Accesstoken'];
			$sql = "select * from ".self::usersTable." where user_token = '$accessToken'";
			$rows = $this->executeGenericDQLQuery($sql);
			$user_id = $rows[0]['user_id'];
			switch ($this->_request['operation']) {
				case 'create':
					$employee_data = $this->_request['employee_data'];
					$name = $employee_data['name'];
					$desingnation = $employee_data['desingnation'];
					$village = $employee_data['village'];
					$city = $employee_data['city'];
					$post = $employee_data['post'];
					$ps = $employee_data['ps'];
					$district = $employee_data['district'];
					$pin = $employee_data['pin'];
					$mobile = $employee_data['mobile'];
					$email = $employee_data['email'];
					$ulb_id = $employee_data['ulb_id'];
					$dob = $employee_data['dob'];
					$doj = $employee_data['doj'];
					$dor = $employee_data['dor'];
					$status = $employee_data['status'];
					$created = new Date();
					$isDeleted = 0;
					$sql = "insert into ".self::employee_table."(name,designation_id,villege_town,city,post,police_station,district_id,pin,mobile,email,ulb_id,dob,doj,dor,emp_status,createdDate,isDeleted,created_by) values('$name','$desingnation','$village','$city','$post','$ps','$district','$pin','$mobile','$email','$ulb_id','$dob','$doj','$dor','$status','$created','$isDeleted','$user_id')";
					$rows = $this->executeGenericDMLQuery($sql);
					$this->sendResponse(200,$this->messages['userCreated']);
					break;
				case 'get':
					$employee_data = isset($this->_request['employee_data']) ? $this->_request['employee_data'] : $this->_request;
					$sql = "SELECT a.emp_id, a.name, a.villege_town, a.city, a.post, a.police_station, a.pin, a.mobile, a.email, a.dob, a.doj, a.dor, a.emp_status, a.createdDate, a.modifiedDate, a.isDeleted, b.district_name, c.designation, d.ulb_name FROM employee_table a INNER JOIN district_master b ON a.district_id = b.district_id INNER JOIN designation_master c ON a.designation_id = c.designation_id INNER JOIN ulb_master d ON a.ulb_id = d.ulb_id where a.isDeleted = 0";
					if(isset($employee_data['id']))
						$sql .= " AND a.emp_id=".$employee_data['id'];
					$rows = $this->executeGenericDQLQuery($sql);
					$employee = array();
					for($i = 0; $i < sizeof($rows); $i++) {
						$employee[$i]['id'] = $rows[$i]['emp_id'];
						$employee[$i]['name'] = $rows[$i]['name'];
						$employee[$i]['designation'] = $rows[$i]['designation'];
						$employee[$i]['village'] = $rows[$i]['villege_town'];
						$employee[$i]['city'] = $rows[$i]['city'];
						$employee[$i]['post'] = $rows[$i]['post'];
						$employee[$i]['police_station'] = $rows[$i]['police_station'];
						$employee[$i]['district'] = $rows[$i]['district_name'];
						$employee[$i]['pin'] = $rows[$i]['pin'];
						$employee[$i]['mobile'] = $rows[$i]['mobile'];
						$employee[$i]['email'] = $rows[$i]['email'];
						$employee[$i]['dob'] = $rows[$i]['dob'];
						$employee[$i]['doj'] = $rows[$i]['doj'];
						$employee[$i]['dor'] = $rows[$i]['dor'];
						$employee[$i]['ulb'] = $rows[$i]['ulb_name'];
						$employee[$i]['emp_status'] = $rows[$i]['emp_status'];
						$employee[$i]['createdDate'] = $rows[$i]['createdDate'];
						$employee[$i]['isDeleted'] = $rows[$i]['isDeleted'];
					}
					$this->sendResponse(200,$this->messages['dataFetched'],$employee);
					break;
			}
		}
	}
	$api = new API;
	$api->processApi();
?>

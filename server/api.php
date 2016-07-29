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
				"userCreated" => "User created successfully",
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
						$users[0]['id'] = $rows[0]['user_id'];
						$users[0]['user_name'] = $rows[0]['user_name'];
						$users[0]['email'] = $rows[0]['email'];
						$users[0]['roll_id'] = $rows[0]['roll_id'];
						$users[0]['ulb_id'] = $rows[0]['ulb_id'];
						$users[0]['token'] = $rows[0]['user_token'];
						$users[0]['district_id'] = $rows[0]['district_id'];
						$this->sendResponse(200,$this->messages['loginSuccess'],$users);
				}
				else {
					$this->sendResponse(201,"failure","fail");
				}
			}
			else{
				$this->sendResponse(202,"validation Error","Invalid user name or password");
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
		public function updateProfile(){
			$user_data = isset($this->_request['user_data']) ? $this->_request['user_data'] : $this->_request;
			$email = isset($user_data['email']) ? $user_data['email'] : '';
			$first_name = isset($user_data['first_name']) ? $user_data['first_name'] : '';
			$last_name = isset($user_data['last_name']) ? $user_data['last_name'] : '';
			$mobile = isset($user_data['mobile']) ? $user_data['mobile'] : '';
			$token = $user_data['token'];
					$previous = false;
					$sql = "update ".self::usersTable." set ";
					if(isset($user_data['email'])){
						$sql .="email ='$email'";
						$previous = true;
					}
					if(isset($user_data['first_name'])){
						$comma = ($previous) ? ',' : '';
						$sql .="$comma first_name ='$first_name'";
						$previous = true;
					}
					if(isset($user_data['last_name'])){
						$comma = ($previous) ? ',' : '';
						$sql .="$comma last_name ='$last_name'";
						$previous = true;
					}
					if(isset($user_data['mobile'])){
						$comma = ($previous) ? ',' : '';
						$sql .="$comma mobile ='$mobile'";
						$previous = true;
					}
					$sql .= " where token='$token'";
					$result = $this->executeGenericDMLQuery($sql);
					if($result){
						$this->sendResponse2(200,$this->messages['userUpdated']);
					}
		}
		public function checkPassword(){
			if(isset($this->_request['password']) && isset($this->_request['token'])){
				$cpass = $this->_request['password'];
				$token = $this->_request['token'];
				$sql = "SELECT password FROM ".self::usersTable." where token='$token'";
				$rows = $this->executeGenericDQLQuery($sql);
				$users = array();
				if($rows[0]['password'] == md5($cpass)){
					$this->sendResponse(200,"success","ok");
				}
				else{
					$this->sendResponse(201,"failure","fail");
				}
			}
		}
		public function user() {
				if(!isset($this->_request['operation']))
					$this->sendResponse2(400,$this->messages['operationNotDefined']);
				$sql = null;
				switch ($this->_request['operation']) {
					case 'create':
						$user_data = $this->_request['user_data'];
						$user_name = $user_data['user_name'];
						$password = md5($user_data['password']);
						$email = $user_data['email'];
						$first_name = $user_data['first_name'];
						$last_name = $user_data['last_name'];
						$mobile = $user_data['mobile'];
						$user_type = $user_data['user_type'];
						$status = 0; //0 for inactive and 1 for active
						$sql = "INSERT INTO ".self::usersTable."(user_type, user_name, mobile, password, email, first_name, last_name,status) VALUES ('$user_type','$user_name','$mobile','$password','$email','$first_name','$last_name','$status')";
						// echo $sql;
						$rows = $this->executeGenericDMLQuery($sql);
						$this->sendResponse2(200,$this->messages['userCreated']);
						break;
					case 'update':
					$user_data = isset($this->_request['user_data']) ? $this->_request['user_data'] : $this->_request;
					$user_name = isset($user_data['user_name']) ? $user_data['user_name'] : '';
					$password = isset($user_data['password']) ? md5($user_data['password']) : '';
					$email = isset($user_data['email']) ? $user_data['email'] : '';
					$first_name = isset($user_data['first_name']) ? $user_data['first_name'] : '';
					$last_name = isset($user_data['last_name']) ? $user_data['last_name'] : '';
					$mobile = isset($user_data['mobile']) ? $user_data['mobile'] : '';
					$status = isset($user_data['status']) ? $user_data['status'] : '';
							$previous = false;
							$sql = "update ".self::usersTable." set ";
							if(isset($user_data['user_name'])){
								$previous = true;
								$sql .="user_name ='$user_name'";
							}
							if(isset($user_data['password'])){
								$comma = ($previous) ? ',' : '';
								$sql .="$comma password ='$password' ";
								$previous = true;
							}
							if(isset($user_data['email'])){
								$comma = ($previous) ? ',' : '';
								$sql .="$comma email ='$email'";
								$previous = true;
							}
							if(isset($user_data['first_name'])){
								$comma = ($previous) ? ',' : '';
								$sql .="$comma first_name ='$first_name'";
								$previous = true;
							}
							if(isset($user_data['last_name'])){
								$comma = ($previous) ? ',' : '';
								$sql .="$comma last_name ='$last_name'";
								$previous = true;
							}
							if(isset($user_data['mobile'])){
								$comma = ($previous) ? ',' : '';
								$sql .="$comma mobile ='$mobile'";
								$previous = true;
							}
							if(isset($user_data['status'])){
								$comma = ($previous) ? ',' : '';
								$sql .="$comma status = $status";
							}
							$sql .= " where id=".$user_data['id'];
							// $user_type = $user_data['user_type'];
							// $status = 0; //0 for inactive and 1 for active
							// echo $sql;
							$result = $this->executeGenericDMLQuery($sql);
							if($result){
								$this->sendResponse2(200,$this->messages['userUpdated']);
							}
						break;
					case 'delete':
					$user_data = isset($this->_request['user_data']) ? $this->_request['user_data'] : $this->_request;
					  $sql = "delete from ".self::usersTable. " where id=".$user_data['id'];
						$result = $this->executeGenericDMLQuery($sql);
						if($result){
							$this->sendResponse2(200,$this->messages['deleted']);
						}
						break;
					// this will fetche the all the users and the single user data if the id is mention for the user
					case 'get':
						$user_data = isset($this->_request['user_data']) ? $this->_request['user_data'] : $this->_request;
						$sql = "SELECT * FROM ".self::usersTable;
						if(isset($user_data['id']))
							$sql .= " where id=".$user_data['id'];
						$rows = $this->executeGenericDQLQuery($sql);
						$users = array();
						for($i=0;$i<sizeof($rows);$i++)
						{
							$users[$i]['id'] = $rows[$i]['id'];
							$users[$i]['user_type'] = $rows[$i]['user_type'];
							$users[$i]['user_name'] = $rows[$i]['user_name'];
							$users[$i]['mobile'] = $rows[$i]['mobile'];
							$users[$i]['email'] = $rows[$i]['email'];
							$users[$i]['first_name'] = $rows[$i]['first_name'];
							$users[$i]['last_name'] = $rows[$i]['last_name'];
							$users[$i]['token'] = $rows[$i]['token'];
							$users[$i]['status'] = $rows[$i]['status'];
						}
						$this->sendResponse2(200,$this->messages['dataFetched'],$users);
						break;
					default:
							$this->sendResponse2(400,$this->messages['operationNotDefined']);
				}
		}
	}
	$api = new API;
	$api->processApi();
?>

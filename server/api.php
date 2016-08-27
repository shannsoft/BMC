<?php
header('Access-Control-Allow-Origin: *');
	require_once("Rest.inc.php");
	require_once('./mail/PHPMailerAutoload.php');
	require_once ('./mail/PHPMailerAutoload.php');
  require_once ( './mail/class.phpmailer.php' ); //
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
		const document_tbl = "document_master";
		const pension_tbl = "retirement_pension";
		const pension_history = "pension_history";
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
				"otpSentSuccess" => "OTP sent successfully",
				"otpSentError" => "Error in sending OTP",
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
				$sql = "select a.user_id,a.user_name,a.email,a.address,a.mobile,a.roll_id,a.ulb_id,a.user_token,a.district_id,b.rolle_type,c.ulb_name,d.district_name	FROM user_tbl a INNER JOIN role_master b ON a.roll_id = b.roll_id	INNER JOIN ulb_master c ON a.ulb_id = c.ulb_id INNER JOIN district_master d ON a.district_id = d.district_id where a.user_name = '$user_name' and a.password = '$password' limit 1";
				$rows = $this->executeGenericDQLQuery($sql);
				if(sizeof($rows)){
						$users = array();
						$users['id'] = $rows[0]['user_id'];
						$users['user_name'] = $rows[0]['user_name'];
						$users['email'] = $rows[0]['email'];
						$users['address'] = $rows[0]['address'];
						$users['mobile'] = $rows[0]['mobile'];
						$users['roll_id'] = $rows[0]['roll_id'];
						$users['roll_type'] = $rows[0]['rolle_type'];
						$users['ulb_id'] = $rows[0]['ulb_id'];
						$users['ulb_name'] = $rows[0]['ulb_name'];
						$users['token'] = $rows[0]['user_token'];
						$users['district_id'] = $rows[0]['district_id'];
						$users['district'] = $rows[0]['district_name'];
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
			$headers = apache_request_headers();
			$accessToken = $headers['Accesstoken'];
			$password = md5($this->_request['password']);
			$sql = "update ".self::usersTable." set password='$password' where user_token='$accessToken'";
			$result = $this->executeGenericDMLQuery($sql);
			if($result){
				$this->sendResponse(200,'Successfully Changed Your Password');
			}
		}
	 	public function checkPassword(){
		    	$headers = apache_request_headers();
		    	$accessToken = $headers['Accesstoken'];
					if(isset($this->_request['password'])){
						$cpass = $this->_request['password'];
						$sql = "SELECT password FROM ".self::usersTable." where user_token='$accessToken'";
						$rows = $this->executeGenericDQLQuery($sql);
						$users = array();
						if($rows[0]['password'] == md5($cpass)){
							$this->sendResponse(200,"success");
						}
						else{
							$this->sendResponse(201,"failure");
						}
					}
		}
		public function updateProfile(){
			$headers = apache_request_headers();
			$accessToken = $headers['Accesstoken'];
			$user_data = $this->_request['user_data'];
			$user_name = $user_data['user_name'];
			$email = $user_data['email'];
			$mobile = $user_data['mobile'];
			$address = $user_data['address'];
			$sql = "update ".self::usersTable." set user_name='$user_name', mobile='$mobile', address='$address', email='$email' where user_token='$accessToken'";
			$result = $this->executeGenericDMLQuery($sql);
			if($result){
				$this->sendResponse(200,'Profile Updated Successfully');
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
			$sql = "select * from ".self::ulb_tbl;
			if(isset($this->_request['district_id'])){
				$district_id = $this->_request['district_id'];
				$sql .= " where district_id = ".$district_id;
			}
			$rows = $this->executeGenericDQLQuery($sql);
			$desig = array();
			for($i=0;$i<sizeof($rows);$i++){
				$desig[$i]['id'] = $rows[$i]['ulb_id'];
				$desig[$i]['name'] = $rows[$i]['ulb_name'];
			}
			$this->sendResponse(200,$this->messages['dataFetched'],$desig);
		}
		public function getEmployee(){
			$headers = apache_request_headers();
			$accessToken = $headers['Accesstoken'];
			$sql = "select * from ".self::usersTable." where user_token = '$accessToken'";
			$rows = $this->executeGenericDQLQuery($sql);
			$user_id = $rows[0]['user_id'];
			$roll_id = $rows[0]['roll_id'];
			$status = $this->_request['status'];
			$sql = "SELECT a.emp_id, a.district_id,a.ulb_id,a.designation_id, a.name, a.villege_town, a.city, a.post, a.police_station,
			 a.pin, a.mobile, a.email, a.dob, a.doj, a.dor, a.emp_status, a.createdDate, a.modifiedDate, a.isDeleted, b.district_name,
			  c.designation, d.ulb_name, e.pension_id,e.pending_at FROM employee_table a
				INNER JOIN district_master b ON a.district_id = b.district_id
				INNER JOIN designation_master c ON a.designation_id = c.designation_id
				INNER JOIN ulb_master d ON a.ulb_id = d.ulb_id
				INNER JOIN retirement_pension e ON a.emp_id = e.emp_id
				where a.isDeleted = 0 AND a.emp_status='$status'";
			if($roll_id == 3){
				$sql .= " AND a.created_by=".$user_id;
			}
			$rows = $this->executeGenericDQLQuery($sql);
			$employee = array();
			for($i = 0; $i < sizeof($rows); $i++) {
				$employee[$i]['id'] = $rows[$i]['emp_id'];
				$employee[$i]['name'] = $rows[$i]['name'];
				$employee[$i]['designation'] = $rows[$i]['designation'];
				$employee[$i]['designation_id'] = $rows[$i]['designation_id'];
				$employee[$i]['village'] = $rows[$i]['villege_town'];
				$employee[$i]['city'] = $rows[$i]['city'];
				$employee[$i]['post'] = $rows[$i]['post'];
				$employee[$i]['police_station'] = $rows[$i]['police_station'];
				$employee[$i]['district'] = $rows[$i]['district_name'];
				$employee[$i]['district_id'] = $rows[$i]['district_id'];
				$employee[$i]['pin'] = $rows[$i]['pin'];
				$employee[$i]['mobile'] = $rows[$i]['mobile'];
				$employee[$i]['email'] = $rows[$i]['email'];
				$employee[$i]['dob'] = $rows[$i]['dob'];
				$employee[$i]['doj'] = $rows[$i]['doj'];
				$employee[$i]['dor'] = $rows[$i]['dor'];
				$employee[$i]['ulb'] = $rows[$i]['ulb_name'];
				$employee[$i]['ulb_id'] = $rows[$i]['ulb_id'];
				$employee[$i]['emp_status'] = $rows[$i]['emp_status'];
				$employee[$i]['createdDate'] = $rows[$i]['createdDate'];
				$employee[$i]['isDeleted'] = $rows[$i]['isDeleted'];
				$employee[$i]['pension_id'] = $rows[$i]['pension_id'];
				$employee[$i]['pending_at'] = $rows[$i]['pending_at'];
			}
			$this->sendResponse(200,$this->messages['dataFetched'],$employee);
		}
		public function acceptRetirement(){
			$headers = apache_request_headers();
			$accessToken = $headers['Accesstoken'];
			$emp_id = $this->_request['id'];
			if($accessToken)
			$sql = "update ".self::employee_table." set emp_status='Retired' where emp_id=".$emp_id;
			$result = $this->executeGenericDMLQuery($sql);
			if($result){
				$this->sendResponse(200,'Successfully Updated');
			}
		}
		public function getEmployeeDocument(){
			$headers = apache_request_headers();
			$accessToken = $headers['Accesstoken'];
			$emp_id = $this->_request['id'];
			$sql = "select * from ".self::pension_tbl." where emp_id=".$emp_id;
			$rows = $this->executeGenericDQLQuery($sql);
			$documents = array();
			for($i = 0; $i < sizeof($rows); $i++) {
				$documents[$i]['id'] = $rows[$i]['pension_id'];
				$documents[$i]['emp_id'] = $rows[$i]['emp_id'];
				$documents[$i]['pension_type'] = $rows[$i]['pension_type'];
				$documents[$i]['pension_category'] = $rows[$i]['pension_category'];
				$documents[$i]['documents'] = $rows[$i]['documents'];
				$documents[$i]['pending_at'] = $rows[$i]['pending_at'];
				$documents[$i]['file_no'] = $rows[$i]['file_no'];
				$documents[$i]['dod'] = $rows[$i]['dod'];
				$documents[$i]['remarks'] = $rows[$i]['remarks'];
				$documents[$i]['nominee'] = $rows[$i]['nominee'];
				$documents[$i]['relation'] = $rows[$i]['relation'];
			}
			$this->sendResponse(200,$this->messages['dataFetched'],$documents);
		}
		public function retiredDocuments(){
			$headers = apache_request_headers();
			$accessToken = $headers['Accesstoken'];
			$pension_id = $this->_request['pension_id'];
			$sql = "SELECT a.name, a.villege_town, a.city, a.post, a.police_station, a.pin, a.mobile, a.email,
			a.dob, a.doj, a.dor, b.district_name, c.designation, d.ulb_name,e.pension_id,e.pension_type,e.file_no,
			e.pension_category,e.documents,e.remarks,e.nominee,e.relation,e.dod
			FROM employee_table a
			INNER JOIN district_master b ON a.district_id = b.district_id
			INNER JOIN designation_master c ON a.designation_id = c.designation_id
			INNER JOIN ulb_master d ON a.ulb_id = d.ulb_id
			INNER JOIN retirement_pension e ON a.emp_id = e.emp_id
			where a.isDeleted = 0 AND e.pension_id=".$pension_id;
			$rows = $this->executeGenericDQLQuery($sql);
			$employee = array();
			for($i = 0; $i < sizeof($rows); $i++) {
				$employee['name'] = $rows[0]['name'];
				$employee['designation'] = $rows[0]['designation'];
				$employee['village'] = $rows[0]['villege_town'];
				$employee['city'] = $rows[0]['city'];
				$employee['post'] = $rows[0]['post'];
				$employee['police_station'] = $rows[0]['police_station'];
				$employee['district'] = $rows[0]['district_name'];
				$employee['pin'] = $rows[0]['pin'];
				$employee['mobile'] = $rows[0]['mobile'];
				$employee['email'] = $rows[0]['email'];
				$employee['dob'] = $rows[0]['dob'];
				$employee['doj'] = $rows[0]['doj'];
				$employee['dor'] = $rows[0]['dor'];
				$employee['ulb'] = $rows[0]['ulb_name'];
				$employee['pension_id'] = $rows[0]['pension_id'];
        $employee['pension_type'] = $rows[0]['pension_type'];
        $employee['pension_category'] = $rows[0]['pension_category'];
        $employee['documents'] = $rows[0]['documents'];
        $employee['remarks'] = $rows[0]['remarks'];
        $employee['nominee'] = $rows[0]['nominee'];
        $employee['relation'] = $rows[0]['relation'];
        $employee['file_no'] = $rows[0]['file_no'];
        $employee['dod'] = $rows[0]['dod'];
			}
			$sql = "select * from ".self::pension_history." where pension_id=".$pension_id;
			$rows = $this->executeGenericDQLQuery($sql);
			if($rows){
				$index = sizeof($rows)-1;
				$employee['history_id'] = $rows[$index]['history_id'];
				$employee['ulb_ref_no'] = $rows[$index]['ulb_ref_no'];
				$employee['ulb_ref_date'] = $rows[$index]['ulb_ref_date'];
				$employee['department_ref_no'] = $rows[$index]['department_ref_no'];
				$employee['department_ref_date'] = $rows[$index]['department_ref_date'];
				$employee['section_ref_no'] = $rows[$index]['section_ref_no'];
				$employee['section_ref_date'] = $rows[$index]['section_ref_date'];
			}
			$this->sendResponse(200,$this->messages['dataFetched'],$employee);
		}
		public function updateEmployeeDocument(){
      // printf("Last inserted record has id %d\n", mysql_insert_id());
			$headers = apache_request_headers();
			$accessToken = $headers['Accesstoken'];
			$document_data = $this->_request['documents_data'];
      $pension_id = $document_data['pension_id'];
      $emp_id = $document_data['emp_id'];
      $pension_type = $document_data['pension_type'];
      $category = $document_data['category'];
      $documents = $document_data['documents'];
      $ref_no = $document_data['ref_no'];
      $ref_date = $document_data['ref_date'];
      $dod = ($document_data['dod']) ? $document_data['dod'] : null;
      $remarks = $document_data['remarks'];
      $nominee = $document_data['nominee'];
      $relation = $document_data['relation'];
      $pending_at = 'ULB';
      if($pension_id){
        $sql = "update ".self::pension_tbl." set pension_type='$pension_type', pension_category='$category', documents='$documents', dod='$dod', remarks='$remarks', pending_at='$pending_at'";
				if(isset($document_data['nominee'])){
					$sql .=", nominee ='$nominee', relation='$relation'";
				}
				$sql .=" where pension_id=".$pension_id;;
        $result = $this->executeGenericDMLQuery($sql);
        if($result){
          $sql = "insert into ".self::pension_history."(pension_id,ulb_ref_no,ulb_ref_date) values('$pension_id','$ref_no','$ref_date')";
          $rows = $this->executeGenericDMLQuery($sql);
        }
      }
      else{
        $sql = "insert into ".self::pension_tbl."(emp_id,pension_type,pension_category,documents,pending_at,dod,remarks,nominee,relation) values('$emp_id','$pension_type','$category','$documents','$pending_at','$dod','$remarks','$nominee','$relation')";
        $rows = $this->executeGenericDMLQuery($sql);
        if($rows){
          $pension_id = mysql_insert_id();
          $sql = "insert into ".self::pension_history."(pension_id,ulb_ref_no,ulb_ref_date) values('$pension_id','$ref_no','$ref_date')";
          $rows = $this->executeGenericDMLQuery($sql);
        }
      }
      $sql = "SELECT a.name, a.villege_town, a.city, a.post, a.police_station, a.pin, a.mobile, a.email,
      a.dob, a.doj, a.dor, b.district_name, c.designation, d.ulb_name,e.pension_id,e.pension_type,e.pension_category,e.documents,e.remarks,e.nominee,e.relation
      FROM employee_table a
      INNER JOIN district_master b ON a.district_id = b.district_id
      INNER JOIN designation_master c ON a.designation_id = c.designation_id
      INNER JOIN ulb_master d ON a.ulb_id = d.ulb_id
      INNER JOIN retirement_pension e ON a.emp_id = e.emp_id
      where a.isDeleted = 0 AND e.pension_id=".$pension_id;
      $rows = $this->executeGenericDQLQuery($sql);
			$employee = array();
			for($i = 0; $i < sizeof($rows); $i++) {
				$employee['name'] = $rows[0]['name'];
				$employee['designation'] = $rows[0]['designation'];
				$employee['village'] = $rows[0]['villege_town'];
				$employee['city'] = $rows[0]['city'];
				$employee['post'] = $rows[0]['post'];
				$employee['police_station'] = $rows[0]['police_station'];
				$employee['district'] = $rows[0]['district_name'];
				$employee['pin'] = $rows[0]['pin'];
				$employee['mobile'] = $rows[0]['mobile'];
				$employee['email'] = $rows[0]['email'];
				$employee['dob'] = $rows[0]['dob'];
				$employee['doj'] = $rows[0]['doj'];
				$employee['dor'] = $rows[0]['dor'];
				$employee['ulb'] = $rows[0]['ulb_name'];
				$employee['pension_id'] = $rows[0]['pension_id'];
        $employee['pension_type'] = $rows[0]['pension_type'];
        $employee['pension_category'] = $rows[0]['pension_category'];
        $employee['documents'] = $rows[0]['documents'];
        $employee['remarks'] = $rows[0]['remarks'];
        $employee['nominee'] = $rows[0]['nominee'];
        $employee['relation'] = $rows[0]['relation'];
			}
			$this->sendResponse(200,$this->messages['dataFetched'],$employee);

		}
		public function employeeList() {
			$headers = apache_request_headers();
			$accessToken = $headers['Accesstoken'];
			$status = $this->_request['status'];
			if($accessToken){
				$sql = "select * from ".self::usersTable." where user_token = '$accessToken'";
				$rows = $this->executeGenericDQLQuery($sql);
				$userId = $rows[0]['user_id'];
				$roll_id = $rows[0]['roll_id'];
			}
			$sql = "SELECT a.emp_id, a.district_id,a.ulb_id,a.designation_id, a.name, a.villege_town, a.city, a.post, a.police_station,
			a.pin, a.mobile, a.email, a.dob, a.doj, a.dor, a.emp_status, a.createdDate, a.modifiedDate, b.district_name, c.designation,
			d.ulb_name FROM employee_table a
			INNER JOIN district_master b ON a.district_id = b.district_id
			INNER JOIN designation_master c ON a.designation_id = c.designation_id
			INNER JOIN ulb_master d ON a.ulb_id = d.ulb_id
			where a.isDeleted = 0";
			if($roll_id && $roll_id == 3)
        $sql .=" AND a.created_by=".$userId;
			if($status == "Retired"){
				$sql .=" AND a.dor <= DATE(NOW())";
			}
			if($status == "All"){
				$sql .=" AND a.emp_status != 'Retired'";
			}
			if($status == "tobeRetired"){
				$current_date = date("Y-m-d");
	      $effectiveDate = date('Y-m-d', strtotime("+6 months", strtotime($current_date)));
				$sql .=" AND dor > '$current_date' AND dor <= '$effectiveDate'";
			}
			$rows = $this->executeGenericDQLQuery($sql);
			$employee = array();
			for($i = 0; $i < sizeof($rows); $i++) {
				$employee[$i]['id'] = $rows[$i]['emp_id'];
				$employee[$i]['name'] = $rows[$i]['name'];
				$employee[$i]['designation'] = $rows[$i]['designation'];
				$employee[$i]['designation_id'] = $rows[$i]['designation_id'];
				$employee[$i]['village'] = $rows[$i]['villege_town'];
				$employee[$i]['city'] = $rows[$i]['city'];
				$employee[$i]['post'] = $rows[$i]['post'];
				$employee[$i]['police_station'] = $rows[$i]['police_station'];
				$employee[$i]['district'] = $rows[$i]['district_name'];
				$employee[$i]['district_id'] = $rows[$i]['district_id'];
				$employee[$i]['pin'] = $rows[$i]['pin'];
				$employee[$i]['mobile'] = $rows[$i]['mobile'];
				$employee[$i]['email'] = $rows[$i]['email'];
				$employee[$i]['dob'] = $rows[$i]['dob'];
				$employee[$i]['doj'] = $rows[$i]['doj'];
				$employee[$i]['dor'] = $rows[$i]['dor'];
				$employee[$i]['ulb'] = $rows[$i]['ulb_name'];
				$employee[$i]['ulb_id'] = $rows[$i]['ulb_id'];
				$employee[$i]['emp_status'] = $rows[$i]['emp_status'];
			}
			$this->sendResponse(200,$this->messages['dataFetched'],$employee);
		}
    public function employeeCount() {
      $headers = apache_request_headers();
      $accessToken = $headers['Accesstoken'];
      if($accessToken){
        $sql = "select * from ".self::usersTable." where user_token = '$accessToken'";
        $rows = $this->executeGenericDQLQuery($sql);
        $userId = $rows[0]['user_id'];
        $roll_id = $rows[0]['roll_id'];
      }
      $employeeCount = array();
      $sql = "SELECT * FROM ".self::employee_table." where isDeleted = 0 AND emp_status != 'Retired'";
      if($roll_id && $roll_id == 3)
        $sql .=" AND created_by=".$userId;
      $result = $this->executeGenericDQLQuery($sql);
      $employeeCount['total_emp'] = sizeof($result);

      $sql = "SELECT * FROM `employee_table` WHERE dor <= DATE(NOW()) AND isDeleted = 0";
      if($roll_id && $roll_id == 3)
        $sql .=" AND created_by=".$userId;
      $result = $this->executeGenericDQLQuery($sql);
      $employeeCount['retired_emp'] = sizeof($result);

      $current_date = date("Y-m-d");
      $effectiveDate = date('Y-m-d', strtotime("+6 months", strtotime($current_date)));
      $sql = "SELECT * FROM `employee_table` WHERE dor > '$current_date' AND dor <= '$effectiveDate' AND isDeleted = 0";
      if($roll_id && $roll_id == 3)
        $sql .=" AND created_by=".$userId;
      $result = $this->executeGenericDQLQuery($sql);
      $employeeCount['tobe_retired'] = sizeof($result);
			if($roll_id && $roll_id == 1){
					$sql = "SELECT * FROM `retirement_pension` WHERE pending_at = 'ULB'";
					$result = $this->executeGenericDQLQuery($sql);
		      $employeeCount['at_ulb'] = sizeof($result);

					$sql = "SELECT * FROM `retirement_pension` WHERE pending_at = 'Central Office'";
					$result = $this->executeGenericDQLQuery($sql);
		      $employeeCount['at_central_office'] = sizeof($result);

					$sql = "SELECT * FROM `retirement_pension` WHERE pending_at = 'Audit'";
					$result = $this->executeGenericDQLQuery($sql);
		      $employeeCount['at_audit'] = sizeof($result);
			}
      $this->sendResponse(200,'No.of count on all employee List',$employeeCount);
    }
		public function sectionData(){
			$headers = apache_request_headers();
      $accessToken = $headers['Accesstoken'];
      if($accessToken){
				$pending_at = $this->_request['section'];
				$sql = "SELECT a.name,a.district_id,a.ulb_id, a.villege_town, a.city, a.post, a.police_station, a.pin, a.mobile, a.email,
				a.dob, a.doj, a.dor, b.district_name, c.designation, d.ulb_name,e.pension_id
				FROM employee_table a
				INNER JOIN district_master b ON a.district_id = b.district_id
				INNER JOIN designation_master c ON a.designation_id = c.designation_id
				INNER JOIN ulb_master d ON a.ulb_id = d.ulb_id
				INNER JOIN retirement_pension e ON a.emp_id = e.emp_id
				where a.isDeleted = 0 AND e.pending_at='$pending_at'";
				$rows = $this->executeGenericDQLQuery($sql);
				$employee = array();
				for($i = 0; $i < sizeof($rows); $i++) {
					$employee[$i]['name'] = $rows[$i]['name'];
					$employee[$i]['designation'] = $rows[$i]['designation'];
					$employee[$i]['village'] = $rows[$i]['villege_town'];
					$employee[$i]['city'] = $rows[$i]['city'];
					$employee[$i]['post'] = $rows[$i]['post'];
					$employee[$i]['police_station'] = $rows[$i]['police_station'];
					$employee[$i]['district'] = $rows[$i]['district_name'];
					$employee[$i]['district_id'] = $rows[$i]['district_id'];
					$employee[$i]['pin'] = $rows[$i]['pin'];
					$employee[$i]['mobile'] = $rows[$i]['mobile'];
					$employee[$i]['email'] = $rows[$i]['email'];
					$employee[$i]['dob'] = $rows[$i]['dob'];
					$employee[$i]['doj'] = $rows[$i]['doj'];
					$employee[$i]['dor'] = $rows[$i]['dor'];
					$employee[$i]['ulb'] = $rows[$i]['ulb_name'];
					$employee[$i]['ulb_id'] = $rows[$i]['ulb_id'];
					$employee[$i]['pension_id'] = $rows[$i]['pension_id'];
				}
				$this->sendResponse(200,$this->messages['dataFetched'],$employee);
			}
		}
		public function ulbPensionCount(){
			$sql = "SELECT ulb_master.ulb_id,ulb_master.ulb_name,employee_table.emp_id ,rp.pending_at ,COUNT(*) as count FROM employee_table INNER JOIN retirement_pension rp ON employee_table.emp_id = rp.emp_id RIGHT JOIN ulb_master ON employee_table.ulb_id = ulb_master.ulb_id GROUP BY ulb_master.ulb_name ORDER BY ulb_master.ulb_name";
			$rows = $this->executeGenericDQLQuery($sql);
			$document = array();
			for($i=0;$i<sizeof($rows);$i++){
				$document[$i]['id'] = $rows[$i]['ulb_id'];
				$document[$i]['ulb'] = $rows[$i]['ulb_name'];
				$document[$i]['is_data'] = $rows[$i]['pending_at'];
				$document[$i]['count'] = $rows[$i]['count'];
			}
			$this->sendResponse(200,$this->messages['dataFetched'],$document);
		}
		public function getEmployeeByULB(){
			$ulb_id = $this->_request['id'];
			$sql = "SELECT a.name,a.district_id,a.ulb_id, a.villege_town, a.city, a.post, a.police_station, a.pin, a.mobile, a.email,
			a.dob, a.doj, a.dor, b.district_name, c.designation, d.ulb_name,e.pension_id
			FROM employee_table a
			INNER JOIN district_master b ON a.district_id = b.district_id
			INNER JOIN designation_master c ON a.designation_id = c.designation_id
			INNER JOIN ulb_master d ON a.ulb_id = d.ulb_id
			INNER JOIN retirement_pension e ON a.emp_id = e.emp_id
			where a.isDeleted = 0 AND e.pending_at='ULB' AND a.ulb_id=".$ulb_id;
			$rows = $this->executeGenericDQLQuery($sql);
			$employee = array();
			for($i = 0; $i < sizeof($rows); $i++) {
				$employee[$i]['name'] = $rows[$i]['name'];
				$employee[$i]['designation'] = $rows[$i]['designation'];
				$employee[$i]['village'] = $rows[$i]['villege_town'];
				$employee[$i]['city'] = $rows[$i]['city'];
				$employee[$i]['post'] = $rows[$i]['post'];
				$employee[$i]['police_station'] = $rows[$i]['police_station'];
				$employee[$i]['district'] = $rows[$i]['district_name'];
				$employee[$i]['district_id'] = $rows[$i]['district_id'];
				$employee[$i]['pin'] = $rows[$i]['pin'];
				$employee[$i]['mobile'] = $rows[$i]['mobile'];
				$employee[$i]['email'] = $rows[$i]['email'];
				$employee[$i]['dob'] = $rows[$i]['dob'];
				$employee[$i]['doj'] = $rows[$i]['doj'];
				$employee[$i]['dor'] = $rows[$i]['dor'];
				$employee[$i]['ulb'] = $rows[$i]['ulb_name'];
				$employee[$i]['ulb_id'] = $rows[$i]['ulb_id'];
				$employee[$i]['pension_id'] = $rows[$i]['pension_id'];
			}
			$this->sendResponse(200,$this->messages['dataFetched'],$employee);
		}
		public function receiveDocument(){
			$headers = apache_request_headers();
      $accessToken = $headers['Accesstoken'];
			$pension_id = $this->_request['pension_id'];
			if($accessToken){
				$sql = "update ".self::pension_tbl." set pending_at='Central Office' where pension_id=".$pension_id;
				$result = $this->executeGenericDMLQuery($sql);
				if($result){
					$this->sendResponse(200,'Successfully Updated');
				}
			}
			else{
				$this->sendResponse(201,'Not a authorised user');
			}
		}
		public function updateDocument(){
			$headers = apache_request_headers();
      $accessToken = $headers['Accesstoken'];
			$document_data = $this->_request['document_data'];
			$pension_id = $document_data['pension_id'];
			$history_id = $document_data['history_id'];
			$pending = $document_data['send_to'];
			$dep_ref_no = $document_data['dep_ref_no'];
			$dep_ref_date = $document_data['dep_ref_date'];
			$sec_ref_no = $document_data['sec_ref_no'];
			$sec_ref_date = $document_data['sec_ref_date'];
			$file_no = $document_data['file_no'];
			$remarks = $document_data['remarks'];
			if($accessToken){
				$sql = "update ".self::pension_tbl." set pending_at='$pending',file_no='$file_no',remarks='$remarks' where pension_id=".$pension_id;
				$result1 = $this->executeGenericDMLQuery($sql);
				$sql = "update ".self::pension_history." set department_ref_no='$dep_ref_no', department_ref_date='$dep_ref_date', section_ref_no='$sec_ref_no', section_ref_date='$sec_ref_date' where history_id=".$history_id;
				$result = $this->executeGenericDMLQuery($sql);
				if($result && $result1){
					$this->sendResponse(200,'Successfully Updated');
				}
				else if($result){
					$this->sendResponse(200,'Successfully Updated');
				}
				else if($result1){
					$this->sendResponse(200,'Successfully Updated');
				}
				else{
					$this->sendResponse(200,'You are trying to update the same data');
				}
			}
			else{
				$this->sendResponse(201,'Not a authorised user');
			}
		}
    public function getDocumentList(){
			$sql = "select * from ".self::document_tbl;
			$rows = $this->executeGenericDQLQuery($sql);
			$document = array();
			for($i=0;$i<sizeof($rows);$i++){
				$document[$i]['id'] = $rows[$i]['document_id'];
				$document[$i]['document_name'] = $rows[$i]['document_name'];
			}
			$this->sendResponse(200,$this->messages['dataFetched'],$document);
		}

		public function sendOtp(){
				// creatin OTP
				$otp = mt_rand(100000,999999);
				$toEmail = $this->_request['toEmail'];
				$sql = "update user_tbl set otp= '$otp' where email='$toEmail'";
				$affectedRows = $this->executeGenericDMLQuery($sql);
				if(intval($affectedRows) == 0)
					$this->sendResponse(402,"Invalid email given");
				$ToName  = 'BMC Corporation';
				$MessageHTML = "<h3> Reset password  <h3>
											<p>Dear User ,</p>
											<p> The OTP for the password reset is <b>$otp</b></p>";
					$Mail = new PHPMailer();
				  $Mail->IsSMTP(); // Use SMTP
				  $Mail->Host        = "localhost"; // Sets SMTP serve
				  $Mail->Subject     = 'Forgot Password';
				  $Mail->ContentType = 'text/html; charset=utf-8\r\n';
				  $Mail->From        = 'Info@ulbpension.com';
				  $Mail->FromName    = 'Odisha e-Municipality';
				  $Mail->WordWrap    = 900; // RFC 2822 Compliant for Max 998 characters per line
				  $Mail->AddAddress( $toEmail ); // To:
				  $Mail->isHTML( TRUE );
				  $Mail->Body = $MessageHTML;
				  $Mail->Send();
				  $Mail->SmtpClose();
				  if ( $Mail->IsError() ) {
						return $this->sendResponse(201,$this->messages['otpSentError']);
				  }
				  else {
						return $this->sendResponse(200,$this->messages['otpSentSuccess']);
				  }

				// $Mail = new PHPMailer();
			  // $Mail->IsSMTP(); // Use SMTP
			  // $Mail->Host        = "smtp.gmail.com"; // Sets SMTP server
			  // $Mail->SMTPDebug   = 2; // 2 to enable SMTP debug information
			  // $Mail->SMTPAuth    = TRUE; // enable SMTP authentication
			  // $Mail->SMTPSecure  = "tls"; //Secure conection
			  // $Mail->Port        = 587; // set the SMTP port
			  // $Mail->Username    = 'santoshmajhi99@gmail.com'; // SMTP account username
			  // $Mail->Password    = 'mahisantu'; // SMTP account password
			  // $Mail->Priority    = 1; // Highest priority - Email priority (1 = High, 3 = Normal, 5 = low)
			  // $Mail->CharSet     = 'UTF-8';
			  // $Mail->Encoding    = '8bit';
			  // $Mail->Subject     = 'Forgot Password';
			  // $Mail->ContentType = 'text/html; charset=utf-8\r\n';
			  // $Mail->From        = 'santoshmajhi99@gmail.com';
			  // $Mail->FromName    = 'Odisha e-Municipality';
			  // $Mail->WordWrap    = 900; // RFC 2822 Compliant for Max 998 characters per line
				//
			  // $Mail->AddAddress( $toEmail ); // To:
			  // $Mail->isHTML( TRUE );
			  // $Mail->Body    = $MessageHTML;
			  // // $Mail->AltBody = $MessageTEXT;
			  // $Mail->Send();
			  // $Mail->SmtpClose();
				//
			  // if ( $Mail->IsError() ) { // ADDED - This error checking was missing
			  //   // return FALSE;
				// 	return $this->sendResponse(201,$this->messages['otpSentError']);
			  // }
			  // else {
				// 	return $this->sendResponse(200,$this->messages['otpSentSuccess']);
			  //   // return TRUE;
			  // }
		}
		public function forgotPassword(){
			$email = $this->_request['email'];
			$password = md5($this->_request['password']);
			$otp = $this->_request['otp'];
			if(isset($this->_request['otp'])){
				$sql = "update ".self::usersTable." set password='$password', otp=null where otp='$otp' AND email='$email'";
				$result = $this->executeGenericDMLQuery($sql);
				if($result){
					$this->sendResponse(200,'Change Password Successfully');
				}
				else{
					$this->sendResponse(201,'You have entered wrong otp');
				}
			}
			else{
				$this->sendResponse(201,'You have entered wrong otp');
			}
		}
		public function employee(){
			if(!isset($this->_request['operation']))
				$this->sendResponse(400,$this->messages['operationNotDefined']);
			$sql = null;
			$headers = apache_request_headers(); // to get all the headers
			$accessToken = $headers['Accesstoken'];
			$sql = "select * from ".self::usersTable." where user_token = '$accessToken'";
			$rows = $this->executeGenericDQLQuery($sql);
			$roll_id = $rows[0]['roll_id'];
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
					$created = date("Y-m-d");
					$isDeleted = 0;
					$sql = "insert into ".self::employee_table."(name,designation_id,villege_town,city,post,police_station,district_id,pin,mobile,email,ulb_id,dob,doj,dor,emp_status,createdDate,isDeleted,created_by) values('$name','$desingnation','$village','$city','$post','$ps','$district','$pin','$mobile','$email','$ulb_id','$dob','$doj','$dor','$status','$created','$isDeleted','$user_id')";
					$rows = $this->executeGenericDMLQuery($sql);
					$this->sendResponse(200,$this->messages['userCreated']);
					break;
				case 'get':
					$employee_data = isset($this->_request['employee_data']) ? $this->_request['employee_data'] : $this->_request;
					$sql = "SELECT a.emp_id, a.district_id,a.ulb_id,a.designation_id, a.name, a.villege_town, a.city, a.post, a.police_station, a.pin, a.mobile, a.email, a.dob, a.doj, a.dor, a.emp_status, a.createdDate, a.modifiedDate, a.isDeleted, b.district_name, c.designation, d.ulb_name FROM employee_table a INNER JOIN district_master b ON a.district_id = b.district_id INNER JOIN designation_master c ON a.designation_id = c.designation_id INNER JOIN ulb_master d ON a.ulb_id = d.ulb_id where a.isDeleted = 0";
					if(isset($employee_data['id']))
						$sql .= " AND a.emp_id=".$employee_data['id'];
					if($roll_id && $roll_id == 3)
			      $sql .=" AND created_by=".$user_id;
					$rows = $this->executeGenericDQLQuery($sql);
					$employee = array();
					for($i = 0; $i < sizeof($rows); $i++) {
						$employee[$i]['id'] = $rows[$i]['emp_id'];
						$employee[$i]['name'] = $rows[$i]['name'];
						$employee[$i]['designation'] = $rows[$i]['designation'];
						$employee[$i]['designation_id'] = $rows[$i]['designation_id'];
						$employee[$i]['village'] = $rows[$i]['villege_town'];
						$employee[$i]['city'] = $rows[$i]['city'];
						$employee[$i]['post'] = $rows[$i]['post'];
						$employee[$i]['police_station'] = $rows[$i]['police_station'];
						$employee[$i]['district'] = $rows[$i]['district_name'];
						$employee[$i]['district_id'] = $rows[$i]['district_id'];
						$employee[$i]['pin'] = $rows[$i]['pin'];
						$employee[$i]['mobile'] = $rows[$i]['mobile'];
						$employee[$i]['email'] = $rows[$i]['email'];
						$employee[$i]['dob'] = $rows[$i]['dob'];
						$employee[$i]['doj'] = $rows[$i]['doj'];
						$employee[$i]['dor'] = $rows[$i]['dor'];
						$employee[$i]['ulb'] = $rows[$i]['ulb_name'];
						$employee[$i]['ulb_id'] = $rows[$i]['ulb_id'];
						$employee[$i]['emp_status'] = $rows[$i]['emp_status'];
						$employee[$i]['createdDate'] = $rows[$i]['createdDate'];
						$employee[$i]['isDeleted'] = $rows[$i]['isDeleted'];
					}
					$this->sendResponse(200,$this->messages['dataFetched'],$employee);
					break;
				case 'delete':
					$employee_data = isset($this->_request['employee_data']) ? $this->_request['employee_data'] : $this->_request;
					$sql = "update ".self::employee_table." set isDeleted=1 where emp_id=".$employee_data['id'];
					$result = $this->executeGenericDMLQuery($sql);
					if($result){
						$this->sendResponse(200,$this->messages['deleted']);
					}
					break;
				case 'update':
					$employee_data = $this->_request['employee_data'];
					$emp_id = $employee_data['id'];
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
					$modified = date("Y-m-d");
					$sql = "update ".self::employee_table." set name='$name'";
					if(isset($employee_data['desingnation'])){
						$sql .=", designation_id = ".$desingnation;
					}
					if(isset($employee_data['village'])){
						$sql .=", villege_town = '$village'";
					}
					if(isset($employee_data['city'])){
						$sql .=", city = '$city'";
					}
					if(isset($employee_data['post'])){
						$sql .=", post = '$post'";
					}
					if(isset($employee_data['ps'])){
						$sql .=", police_station = '$ps'";
					}
					if(isset($employee_data['district'])){
						$sql .=", district_id =".$district;
					}
					if(isset($employee_data['pin'])){
						$sql .=", pin =".$pin;
					}
					if(isset($employee_data['mobile'])){
						$sql .=", mobile ='$mobile'";
					}
					if(isset($employee_data['mobile'])){
						$sql .=", email ='$email'";
					}
					if(isset($employee_data['ulb_id'])){
						$sql .=", ulb_id =".$ulb_id;
					}
					if(isset($employee_data['dob'])){
						$sql .=", dob ='$dob'";
					}
					if(isset($employee_data['doj'])){
						$sql .=", doj ='$doj'";
					}
					if(isset($employee_data['dor'])){
						$sql .=", dor ='$dor'";
					}
					if(isset($employee_data['status'])){
						$sql .=", emp_status ='$status'";
					}
					if($modified){
						$sql .=", modifiedDate ='$modified'";
					}
					$sql .= " where emp_id=".$emp_id;
					$result = $this->executeGenericDMLQuery($sql);
					if($result){
						$this->sendResponse(200,$this->messages['userUpdated']);
					}
					break;
			}
		}
	}
	$api = new API;
	$api->processApi();
?>

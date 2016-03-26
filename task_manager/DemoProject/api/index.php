<?php
class URLrequest
{
    //Action requested
    protected $verb=null;
    protected $inputs=[];

    public function __construct($request)
    {
        //split the URL into request elements
        $requestArr = explode("/",$request);
        //[0] is empty
        //[1] is 'api'
        //Verb to be performed on data.
        $this->verb = $requestArr[2];
        //input data for verb
        for($i = 0; $i < count($requestArr);$i+=1)
        {
            $requestArr[$i] = urldecode($requestArr[$i]);
        }


        $this->inputs = $requestArr;
    
    }
    //Types of queries
    //Get all
    //Set new
    //Delete
    public function GetOutput()
    {
        $ret = "";
        if($this->verb == "getall")
        {
            $ret = $this->GetAll();
        }
        else if($this->verb == "delete")
        {
            $ret = $this->DoDelete();
        }
        else if($this->verb == "create")
        {
            $ret = $this->doCreate();
        }

        return $ret;
    }
    private function doCreate()
    {
        //A bit of validation
        if(isset($this->inputs[3]) && isset($_POST["description"]))
        {
            $name = $this->inputs[3];
            $description = $_POST["description"];
            //Do some validation

            $query = "INSERT INTO `tasks`.`tasks` (`id`, `name`, `description`) VALUES (NULL, '$name', '$description');";
            $this->DoQuery($query);
        }
        else if(isset($this->inputs[3]))
        {
            $name = $this->inputs[3];
            $query = "INSERT INTO `tasks`.`tasks` (`id`, `name`, `description`) VALUES (NULL, '$name', '');";
            echo $query;
            $this->DoQuery($query);     
        }
        else
        {
            return "Malformed Request";
        }
        
        return "";
    }
    //Delete specified row.
    private function DoDelete()
    {
        //A bit of validation
        if(isset($this->inputs[3]) && isset($this->inputs[4]))
        {
            $column = $this->inputs[3];
            $value = $this->inputs[4];
        }
        else
        {
            return "Malformed Request";
        }
        //Do some validation
        $query = "delete FROM `tasks` where $column = $value";
        $this->DoQuery($query);
        
        return "";
    }
    private function GetAll()
    {
        $query = "SELECT * FROM `tasks`";
        $result = $this->DoQuery($query);
        $ret = [];
        while($row = mysql_fetch_array($result))
        {
            array_push($ret,$row);
        }
        return json_encode($ret);
    }
    private function DoQuery($query)
    {
        $host="localhost"; // host name
        $username="taskadmin"; // Mysql username
        $password = "simple_english"; // password
        $db_name = "tasks";
        $con = mysql_connect($host,$username,$password);
        $ret = [];
        
        if (!$con)
        {
            die('Could not connect: ' . mysql_error());
        }

        mysql_select_db($db_name)or die("Unable to select store database");

        $ret = mysql_query($query) or die(mysql_error());
        
        return $ret;
    }
}
$myRequest = new URLrequest($_SERVER['REQUEST_URI']);
echo $myRequest->GetOutput();

?>

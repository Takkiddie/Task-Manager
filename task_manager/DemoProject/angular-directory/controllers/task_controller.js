angular.module('taskmanager').controller('TasksController', function ($scope, $http) {

    $scope.tasks = [];
    $scope.description = undefined;
    $scope.name = undefined;

    $scope.getTasks = function()
    {
            var promise = $http.get("/api/getall").
            success(function (data, status, headers, config)
            {
                console.log(data);
                $scope.tasks = data;
            }).
            error(function (data, status, headers, config) {
                console.log("Error did not contact to server.");
            });

            return promise;
    }
    $scope.removeTask = function (id)
    {

        $http.get("/api/delete/id/" + id).
        success(function (data, status, headers, config) {
            console.log(data);
            $scope.getTasks();
        }).
        error(function (data, status, headers, config) {
            console.log("Error did not contact to server.");
        });
    }
    //Adds a task to the list
    $scope.addTask = function (){
        //Post Data for submit outside of URL
        var data = {};
        //Description and name as set by the form.
        data["description"] = $scope.description;
        var name = $scope.description;
        var url = "/api/create/"+name+"/";
        //Clear the form
        $scope.description = "";
        $scope.name = "";

        //Send the task's data to the server to be processed.
        $http({
            method: 'POST',
            url: url,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: $.param(data)
        })
        .success(function (response) {
            $scope.getTasks();
        });
    }
    function init(){
        $scope.getTasks();
    }
    init();
});
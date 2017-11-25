app.controller('nachTragenSelectTagCtrl', function($scope, $http,loginService,dataExchangeService)
{
		$scope.tag= new Date();
		$scope.setDatum = function(){
			var tmp = $scope.tag;

			$http({
				method: 'Post',
				url:	'/app/data/nachTragenTag.php',
				data:	{datum : $scope.tag} //gesendete Daten
			})
				.then(function successCallback(response)
				{
					$scope.stdPlan=response.data;	  //erhalten Daten
					$scope.status =response.status;
				},

				function errorCallback(response) {
					$scope.data = response.data || "Request failed";
					$scope.status = response.status;
				});
		};

		$scope.nachtragen = function(item)
		{
			$scope.callToSetStdID = dataExchangeService.setStdId($scope.stdPlan[item].id);
			loginService.anwesend();
		};

		$scope.home=function()
		{
			loginService.home();
		};
});

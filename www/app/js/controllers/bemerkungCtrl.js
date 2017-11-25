app.controller('BemerkungCtrl', function($scope,$http,$location,loginService,dataExchangeService)
{
	$scope.stdId=dataExchangeService.getStdId();

	//Lese alle Bemerkungen einer Klasse alle.

	$http({
		method:	 'Post',
		url:		'/app/data/bemerkung.php',
		data:		{stdId : $scope.stdId} //gesendete Daten
	})
		.then
		(
			function successCallback(response)
			{
				$scope.bemKlasse = response.data;	//erhaltene Daten von echo
				$scope.status=response.status;
			},

			function errorCallback(response)
			{
				$scope.data = response.data || "Request failed";
				$scope.status = response.status;
			}
		);

	$scope.save= function()
		{

			$http({
				method:	'Post',
				url:	'/app/data/bemerkungEintragen.php',
				data: {"Bemerkung": $scope.neueBemerkung, "Id":$scope.stdId}

			})

				.then(function successCallback(response)
				{
					$scope.status = response.status;
			    	$scope.data = response.data;
					$scope.result = response.data;
				},

				function errorCallback(response)
				{
					$scope.data = response.data || "Request failed";
					$scope.status = response.status;
				});
		};

	$scope.logout=function()
	{
		loginService.logout();
	};

	$scope.home=function()
	{
		loginService.home();
	};

	$scope.anwesend=function()
	{
		loginService.anwesend();
	};

	$scope.bemerkung=function()
	{
		loginService.bemerkung();
	};

	$scope.unterricht=function()
	{
		loginService.unterricht();
	};
});

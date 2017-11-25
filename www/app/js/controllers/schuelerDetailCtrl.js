app.controller('SchuelerDetailCtrl', function($scope,$http,$location,loginService,dataExchangeService)
{
	$scope.stdId = dataExchangeService.getStdId();
	$scope.schuelerId = dataExchangeService.getSchuelerId();
	$scope.klasse=dataExchangeService.getKlasse();
	$scope.fach=dataExchangeService.getFach();


	//Lese alle Bemerkungen einer Klasse alle.

	$http({
		method:	'Post',
		url:		'/app/data/schuelerDetail.php',
		data:		{	schuelerId : $scope.schuelerId,
							stdId : $scope.stdId,
							fach: $scope.fach
						} //gesendete Daten
	})
		.then
		(
			function successCallback(response)
			{
				$scope.schuelerDetail = response.data;	//erhaltene Daten von echo
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
				url:	'/app/data/KlassenLehrer/entschuldigen.php',
				data: {"Entschuldigen" : $scope.schuelerDetail.fzt }
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
				}
			);
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
});

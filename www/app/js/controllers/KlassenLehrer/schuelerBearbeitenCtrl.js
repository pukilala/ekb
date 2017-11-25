app.controller('SchuelerBearbeitenCtrl', function($scope,$http,$location,loginService,dataExchangeService)
{
	$scope.schuelerId = dataExchangeService.getSchuelerId();

	$http({
		method:	'Post',
		url:		'/app/data/KlassenLehrer/schuelerDetail.php',
		data:		{schuelerId : $scope.schuelerId} //gesendete Daten
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
        url:		'/app/data/KlassenLehrer/schuelerBearbeiten.php',
        data: 	{"Schueler": $scope.schuelerDetail}
    })
        .then(
          function successCallback(response)
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


	$scope.klasseSelected=function(klasse)
	{
		$scope.schuelerDetail.klasse=klasse;
	};

	$scope.logout=function()
	{
		loginService.logout();
	};

	$scope.home=function()
	{
		loginService.home();
	};

	$scope.klasseBearbeiten=function()
	{
		loginService.klasseBearbeiten();
	};
});

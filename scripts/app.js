/*
	migaleriafotos.com
	
	You're Welcome to my code, I prefer migaleriafotoss!!!

*/

var app = angular.module('app', ['ngMaterial', 'ngRoute', 'angularGrid'])
						.config(function($mdThemingProvider) {
								$mdThemingProvider.theme('default')
								 .primaryPalette('purple')
								.accentPalette('deep-purple'); 
						});

		
// Configuración de las rutas

app.config(function($routeProvider, $locationProvider, $httpProvider) {
		
		// use the HTML5 History API
        $locationProvider.html5Mode(true);
	
        $routeProvider
				.when('/', {	 
					controller: "MainCtrl", 
					templateUrl: "grid.html" , 
					reloadOnSearch : false
				})
				.when('/fotos/:folder', {	 
					controller: "showingphotoCtrl", 
					templateUrl: "showing-photo.html", 
					reloadOnSearch : false
					})
				.when('/foto/:folder', {
					controller: "showingphotoCtrl", 
					templateUrl: "showing-photo.html" , 
					reloadOnSearch : false
					});

		// Expose XHR requests to server
		$httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';				
		

    });
				


// Controller: showingphotoCtrl
app.controller('showingphotoCtrl', function($scope, $http, $routeParams) {
		$http.get('showing-imgs-folder.php?folder='+$routeParams.folder)
					.success(function(data){
							$scope.photos = data[0];
					});
    });				
				
				
// Controller: Main
app.controller('MainCtrl', function($scope, $rootScope, $http, $q, $location, $anchorScroll, $mdDialog, $routeParams) {
		var vm = this;
		var shotsTmp;
		var search;
		  vm.search = search;
		  vm.page = 0;
		  vm.shots = [];
		  vm.loadingMore = false;
		  $rootScope.current_tag = null;

		// Route Url by folder: http://migaleriafotos.com/#/foto/una-foto
		// Route Url by folder: http://migaleriafotos.com/#/fotos/una-carpeta-de-fotos
		if( $routeParams.folder){
				$routeParams.name = $routeParams.folder.replace(/-/g, ' ');
				$scope.showCardDes( $routeParams );
		}
					
					



		// ng-click searchTag	
		$scope.searchTag = function(tag) {
			
			console.log($rootScope.current_tag);
			if( !tag ){ 
				vm.shots = shotsTmp;
				$rootScope.current_tag  = "";
				$location.url("/");
			}
			  search = tag;
			  vm.loadMoreShots();
		}
		   
		  
		// search by url:  #?q=avia
		search = $location.search()
		if( search.q ){
		search = $location.search();
		search = search.q;
		}else search ='';
		  
			
		// Dialog open card "directory" pictures	
		$scope.showCardDes = function(card) {
		  var theFolder = card.folder,
		  name = card.name,
		  parentEl = angular.element(document.body);
		  
		  $mdDialog.show({
			clickOutsideToClose : true,
			parent: parentEl,
			locals : { carddata : name},
			controller: ['$scope', 'carddata', function($scope, carddata) {
																$scope.name = carddata;
																$scope.closeDialog = function() {
																	$mdDialog.hide();
																		}
													}],
			templateUrl : 'showing-modalwindow-imgs-folder.php?folder='+theFolder
		  });
		};
		//end dialog


		  


		  vm.loadMoreShots = function( ) {
			if(vm.loadingMore && !search) return;
			vm.loadingMore = true;
			var promise = $http.get('folders-json.php?page='+vm.page+'&q='+search);
			vm.page++;

			promise.then(function(obj) {
				if(search) {
					$rootScope.current_tag = "#"+search;
					delete vm.shots;
					$scope.vm.shots = obj.data;
					vm.loadingMore = false;
					$location.search('q', search); // set url: #?q=xxxx

					if( obj.data.length < 4 ){ //scroll top page when click tag and result is low than 2
						$location.hash('top');
						$anchorScroll();
					}else {	
						$location.hash('');
						$anchorScroll();
					}

				} else {
						shotsTmp = angular.copy(vm.shots);
						shotsTmp = shotsTmp.concat(obj.data);
						vm.shots = shotsTmp;
						vm.loadingMore = false;
				}
				
				
			}, function() {
			  vm.loadingMore = false;
			});

			return promise;			

		  };

		  vm.loadMoreShots();

		 
		  
		  
		});
		
app.filter('unsafe', function($sce) { return $sce.trustAsHtml; });




var routeLoadingIndicator = function($rootScope){
	  return {
		restrict:'E',
		template:"<h1 ng-if='isRouteLoading'>Loading...</h1>",
		link:function(scope, elem, attrs){
		  scope.isRouteLoading = false;

		  $rootScope.$on('$routeChangeStart', function(){
			scope.isRouteLoading = true;
		  });

		  $rootScope.$on('$routeChangeSuccess', function(){
			scope.isRouteLoading = false;
		  });
		}
	  };
};

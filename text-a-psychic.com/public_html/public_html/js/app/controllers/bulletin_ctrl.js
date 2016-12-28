(function() {
    'use strict';
         angular
            .module('txtapsy')
            .controller('BulletinBoardCtrl', BulletinBoardCtrl);

    function BulletinBoardCtrl($scope, TXTAPSY_API) {
    	$scope.messages = [];
 		$scope.itemPerPage = 10;
        $scope.currentPage = 1; 
        $scope.totalPage;
        $scope.message = [];
    	
    	var socket = io('http://localhost:3000');	

		socket.on('message_recieved', function (data) {
			if (data) {
				$.ajax({
				    url: BASE_URL + TXTAPSY_API.AVAILABLE_INBOUND_MSG,
				    dataType: 'json',
				    success: function(response){
				       	$scope.messages = response.data;
	 					$scope.$digest($scope.messages);
				    }
				});

			}
			
		});


		socket.on('message_accepted', function (data) {
		   	if (data.status == 1) {
		   		$('#msg-' + data.id).addClass('active');
		   		$('#msg-' + data.id + ' .send-message').attr('disabled', 'disabled');
		   	}
		});

		socket.on('message_declined', function (data) {
		    $('#msg-' + data.id).removeClass('active');
		});


    	$scope.init = function () {
    		$.ajax({
			    url: BASE_URL + TXTAPSY_API.AVAILABLE_INBOUND_MSG,
			    dataType: 'json',
			    success: function(response){
			    	$scope.messages = response.data;
 					for (var key in $scope.messages) {
						if ($scope.messages[key].status == 1) {
							$('#msg-' + key).addClass('active');
						}
					}
 					$scope.$digest($scope.messages);

			    }
			});
		}

		$scope.formatDate = function (date) {
			return new Date(date);
		}

		$scope.send_message = function (message) {
			if ( message.replied_message ) {	
				$.ajax({
				    url: BASE_URL + TXTAPSY_API.SEND_MESSAGE,
				    dataType: 'json',
				    data: {
				    	ref_message_id: message.id,
				    	message: message.replied_message,
				    	sender_id: 1
				    },
				    type: 'POST',
				    success: function(response){
				    	alert('successfully replied message.')
				    	$('#sms-answer').modal('hide');
				    },
				    error: function(response){
		                alert("Error.");
		                $('#sms-answer').modal('hide');
		            }
				});
			} 
		}

		$scope.accept_message = function (message) {
			$scope.message = message;
			$.ajax({
			    url: BASE_URL + TXTAPSY_API.ACCEPT_INBOUND_MSG,
			    dataType: 'json',
			    data: {
			    	message_id: message.id,
			    	psychic_id: 1,
			    },
			    type: 'POST',
			    success: function(){
					$('#sms-answer').modal('show');
			    }, error: function () {

			    }
			});
		}


		$scope.decline_message = function (message) {
			$.ajax({
			    url: BASE_URL + TXTAPSY_API.DECLINE_INBOUND_MSG,
			    dataType: 'json',
			    data: {
			    	message_id: message.id
			    },
			    type: 'POST',
			    success: function(){

			    }, error: function () {

			    }
			}); 
		}

		$scope.get_page = function (idx) {
			idx = idx + 1;
            $scope.totalPage = Math.ceil($scope.messages.length / $scope.itemPerPage);
            return Math.ceil(idx / $scope.itemPerPage) == $scope.currentPage;
		}

		$scope.prev_page = function (e) {
			$scope.currentPage--;
		}

		$scope.next_page = function (e) {
            $scope.currentPage++;
		}

		$scope.init();
    }

})();








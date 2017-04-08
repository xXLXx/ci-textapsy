(function() {
    'use strict';
         angular
            .module('txtapsy')
            .controller('BulletinBoardCtrl', BulletinBoardCtrl);

    function BulletinBoardCtrl($scope, TXTAPSY_API, $timeout, toastr) {
    	
        $scope.currentLogin = [];

        $scope.messages = [];
        $scope.message = [];
        $scope.resolved_messages = [];
		$scope.psychic = [];
		$scope.psychics = [];
		$scope.errors = [];
        // Pagination
        $scope.itemPerPage = 10;
        $scope.currentPage = 1; 
        $scope.totalPage;

        $scope.itemPerPageResolved = 10;
        $scope.currentPageResolved = 1; 
        $scope.totalPageResolved;

        $scope.hasErr = false;
        $scope.errType = 'danger';

   		$scope.psychic_id  = $('#bulletin').data('psychicid');
    	
        var socket = io(BASE_URL.replace(/\/$/, '') + ':3001');
        var isDeclining = 0;
        var charsPerMessage = 160;

		socket.on('message_recieved', function (data) {
			if (data) {
				// $scope.message_recieved_lists();
				var now = new Date();
				data.sent_at = getDateStr([
					now.getUTCFullYear(), '-', now.getUTCMonth() + 1, '-', now.getUTCDate(), ' ',
					now.getUTCHours(), ':', now.getUTCMinutes(), ':', now.getUTCSeconds()
				]);
				data.isPending = false;
				$scope.messages.push(data);
				$scope.$digest($scope.messages);
				$('.audio.message_receive')[0].play();
			}
		});

		socket.on('message_accepted', function (data) {
		   	if (data.status == 1) {
		   		$('#msg-' + data.id).addClass('active');
				$('.accept-' + data.id).removeClass('btn-success');
				$('.accept-' + data.id).addClass('btn-default');
				$('.accept-' + data.id).attr('disabled', 'disabled');
		   	}
		});

		socket.on('message_declined', function (data) {
			$('#msg-' + data.id).removeClass('active');
			$('.accept-' + data.id).addClass('btn-success');
			$('.accept-' + data.id).removeClass('btn-default');
			$('.accept-' + data.id).removeAttr('disabled');
		});


		socket.on('message_resolved', function (data) {
			if (data) {
				$('.accept-' + data.id).css('display', 'none');

				if (data.sender_id == $scope.psychic_id) {
					var now = new Date();
					data.sent_at = getDateStr([
						now.getUTCFullYear(), '-', now.getUTCMonth() + 1, '-', now.getUTCDate(), ' ',
						now.getUTCHours(), ':', now.getUTCMinutes(), ':', now.getUTCSeconds()
					]);
					$scope.resolved_messages.push(data);
					$scope.$digest($scope.resolved_messages);
				}
			}
		});


    	$scope.init = function () {
    		$scope.message_recieved_lists();
			$scope.resolved_messages_fn();

			$('#sms-answer')
				.on('hide.bs.modal', function (e) {
					if (isDeclining == 0) {
						$scope.decline_message($scope.message);
					}
					if (isDeclining == 1) {
						e.preventDefault();
					}
					if (isDeclining == 2) {
						isDeclining = 0;
					}
				})
				.on('show.bs.modal', function(e) {
					$scope.hasErr = false;
					$scope.errors  = [];
				})
				.on('shown.bs.modal', function(e) {
					$(this).find('.message').focus();
				})
				.on('keypress', function (e) {
					if (e.which == 13 && $('#allow_button').is(':checked')) {
						$('.send-message').click();
						e.preventDefault();
					}
				})
				.on('keyup', '.message', function () {
					var charLength = $(this).val().length;
					$('.char-count').html(charLength + ' (' + (Math.floor(charLength / charsPerMessage) + 1) + ')');
				});
		}


		$scope.message_recieved_lists = function () {
			$.ajax({
			    url: BASE_URL + TXTAPSY_API.ALL_INBOUND_MSG,
			    dataType: 'json',
			    success: function(response){
			    	var msg_all = [];

 					for (var key in response.data) {
						if (response.data[key].status == 1) {
							$scope.message = response.data[key];
							$('#sms-answer').modal('show');
							response.data[key].isPending = true;
							$('#msg-' + response.data[key].id).addClass('warning');
							$('.accept-' + response.data[key].id).attr('disabled', 'disabled');
						} else {
							response.data[key].isPending = false;
						}
						msg_all.push(response.data[key]);
					}
					$scope.messages = msg_all;
					$scope.$digest($scope.messages);
			    }
			});
		}

		$scope.get_psychic_all = function () {
			$.ajax({
			    url: BASE_URL + TXTAPSY_API.ALL_PSYCHIC + $scope.psychic_id,
			    dataType: 'json',
			    type: 'GET',
			    success: function(response){
					var psychics = [];
			    	for (var key in response.data) {
			    		if (!psychics[response.data[key].id]) {
							psychics[response.data[key].id] = [];
						} 
						response.data[key].name =  response.data[key].fname + ' ' +  response.data[key].lname;
			    		psychics[response.data[key].id] = response.data[key];
			    	}

					$scope.psychics = psychics;
					$scope.$digest($scope.psychics);
			    }
			});
		}


		$scope.get_psychic = function () {
			$.ajax({
			    url: BASE_URL + TXTAPSY_API.ALL_PSYCHIC + $scope.psychic_id,
			    dataType: 'json',
			    type: 'GET',
			    success: function(response){
					var psychic = [];
			    	for (var key in response.data) {
			    		psychic.push(response.data[key]);
			    	}

					$scope.psychic = psychic;
					$scope.$digest($scope.psychic);
			    }
			});
		}

		$scope.responded_by_get = function (psychicid, callback) {
			$.ajax({
			    url: BASE_URL + TXTAPSY_API.ALL_PSYCHIC + psychicid,
			    dataType: 'json',
			    type: 'GET',
			    success: function(response){
					var psychic = [];
			    	for (var key in response.data) {
			    		psychic.push(response.data[key]);
			    	}
			    	return callback(psychic);
			    }
			});
		}


		
		$scope.responded_by = function (psychicid) {
			// var psychic_name;
			// $scope.responded_by_get(psychicid, function (response) {
			// 	psychic_name = response[0].dna
			// });
		}

		$scope.resolved_messages_fn = function () {
			$.ajax({
			    url: BASE_URL + TXTAPSY_API.RESOLVED_MSG + $scope.psychic_id,
			    dataType: 'json',
			    type: 'GET',
			    success: function(response){
			    	var resolved_msg = [];
			    	$scope.resolved_messages = [];
			    	for (var key in response.data) {
			    		$('.accept-' + response.data[key].id).css('display', 'none');
			    		resolved_msg.push(response.data[key]);
			    	}
 					$scope.resolved_messages = resolved_msg;
 					$scope.$digest($scope.resolved_messages);
			    }
			});
		}

		$scope.showRespondedMsgDetails = function (message_details) {
			$scope.message = message_details;
			$('#sms-details').modal('show');
		}

		$scope.formatDate = function (date) {

			var replaced = date.replace(/\-/g, '/');
			return new Date(replaced);
		}

		$scope.send_message = function (message) {
			if ( message.replied_message ) {	
				$.ajax({
				    url: BASE_URL + TXTAPSY_API.SEND_MESSAGE,
				    dataType: 'json',
				    data: {
				    	ref_message_id: message.id,
				    	message: message.replied_message,
				    	sender_id: $scope.psychic_id
				    },
				    type: 'POST',
				    success: function(response) {
				    	$scope.errType = 'danger';

				    	if (response.success) {
				    		$scope.hasErr = true;
				    		$scope.errType = 'success';
				    		$scope.errors = ['Successfully replied message.'];
				    		$scope.$digest($scope.errors);
		                	$('#sms-answer').modal('hide');
		                	toastr.success($scope.errors[0], 'Success');
				    	} else {
				    		$scope.hasErr = true;
							var err_arr = [];
							for (var err in response.errors) {
								err_arr.push(response.errors[err]);
							}

							$scope.errors = err_arr;
							$scope.$digest($scope.errors);
							$('#sms-answer').modal('hide');
							$scope.hasErr = false;
							for(var error in $scope.errors) {
								toastr.error($scope.errors[error], 'Error');
							}
						}
				    },
				    error: function(response){

		                $scope.hasErr = true;
						var err_arr = [];
						for (var err in response.errors) {
							err_arr.push(response.errors[err]);
						}

						$scope.errors = err_arr;
						$scope.$digest($scope.errors);
						$('#sms-answer').modal('hide');
						for(var error in $scope.errors) {
							toastr.error($scope.errors[error], 'Error');
						}

		            }
				});
				 $scope.hasErr = false;
			}  else {
				$scope.hasErr = true;
				var err_arr = ['Please fill in the field. Answer is required.'];
				$scope.errors = err_arr;

			}
		}

		$scope.accept_message = function (message, psychic) {
			$scope.psych = psychic;
			$scope.message = message;
		   	$('#msg-' + message.id).addClass('active');
			$('.accept-' + message.id).removeClass('btn-success');
			$('.accept-' + message.id).addClass('btn-default');
			$('.accept-btn').attr('disabled', 'disabled');
			$.ajax({
			    url: BASE_URL + TXTAPSY_API.ACCEPT_INBOUND_MSG,
			    dataType: 'json',
			    data: {
			    	message_id: message.id,
			    	psychic_id: $scope.psychic_id,
			    },
			    type: 'POST',
			    success: function(response){
			    	if (response.success) {
						$('#sms-answer').modal('show');

						$('#msg-' + message.id).addClass('active');
						$('.accept-' + message.id).removeClass('btn-success');
						$('.accept-' + message.id).addClass('btn-default');
						$('.accept-' + message.id).attr('disabled', 'disabled');

					} else {
						for (var err in response.errors) {
							alert(response.errors[err]);
						}
					}
			    }, error: function (response) {
			    	$scope.hasErr = true;
					var err_arr = [];
					for (var err in response.errors) {
						err_arr.push(response.errors[err]);
					}
					$scope.errors = err_arr;
					$scope.$digest($scope.errors);
					for(var error in $scope.errors) {
						toastr.error($scope.errors[error], 'Error');
					}
			    }
			});
		}


		$scope.decline_message = function (message) {
			isDeclining = 1;
			$.ajax({
			    url: BASE_URL + TXTAPSY_API.DECLINE_INBOUND_MSG,
			    dataType: 'json',
			    data: {
			    	message_id: message.id
			    },
			    type: 'POST',
			    success: function(){
			    	$('#msg-' + message.id).removeClass('active');
					$('.accept-' + message.id).addClass('btn-success');
					$('.accept-' + message.id).removeClass('btn-default');
					$('.accept-btn').removeAttr('disabled');

					isDeclining = 2;
					$('#sms-answer').modal('hide');
			    }, error: function () {

			    }
			}); 
		}

		$scope.get_page = function (idx) {
			idx = idx + 1;
            $scope.totalPage = Math.ceil($scope.messages.length / $scope.itemPerPage);
            return Math.ceil(idx / $scope.itemPerPage) == $scope.currentPage;
		}

		$scope.get_page_resolve = function (idx) {
			idx = idx + 1;
            $scope.totalPageResolved = Math.ceil($scope.resolved_messages.length / $scope.itemPerPageResolved);
            return Math.ceil(idx / $scope.itemPerPageResolved) == $scope.currentPageResolved;
		}
		
		$scope.prev_page = function (e) {
			$scope.currentPage--;
		}

		$scope.prev_page_resolved = function (e) {
			$scope.currentPageResolved--;
		}
		

		$scope.next_page = function (e) {
            $scope.currentPage++;
		}

		$scope.next_page_resolved = function (e) {
            $scope.currentPageResolved++;
		}


		$scope.encrypt_number = function (number) {
			var regexPattern = /(\d+)(\d{4})/;
			var match = regexPattern.exec(number);
			if(match) {
				var number_len = match[1];
				var encrypt = number_len.replace(/\d/g, "*");

				return encrypt + match[2];
			} else {
				return number;
			}
		}

		var getDateStr = function (dateArray) {
			var dateStr = '';

			$.each(dateArray, function (key, value) {
				if (typeof value == 'string') {
					dateStr += value;
				} else if (value < 10) {
					dateStr += '0' + value;
				} else {
					dateStr += value;
				}
			});

			return dateStr;
		}

		$scope.init();
		$scope.get_psychic();
		$scope.get_psychic_all();
    }

})();









	var win=null;
	var member_id = chat_object.member_id;
	var current_title;
	var soundIV;
	var inReview = 0;
	
	if(member_id)
	{
	
		var socket = new RampNode('http://imyouranswer.rampnode.com/');
		
		socket.on('message', function(object)
		{
		
			// Notification to rate an expert (Clients Only)
			if(object.type == 'rate_expert' && member_id == object.client_id)
			{
			
				if(inReview==0)
				{
			
					inReview = 1;
					
					var ratingConfirm = confirm("We want to know how your experience was. Would you please leave a review?");
					
					if(ratingConfirm)
					{
					
						var session_id = object.session_id;
					
						window.location = '/client/experts/leave_review/chat/' + session_id;
					
					}
				
				}
			
			}
		
			// For new chats (Experts Only)
			if(object.type == 'new_chat' && member_id == object.expert_id)
			{
			
				$('body').append("<div id='modal'><div class='cont'><b>\""+object.username+"\" wants to chat with you.<br />Do you want to accept?</b><div style='margin:25px 0 0;' align='center'><a href='/' id='startChat' class='blue-button'><span>Start Chatting</span></a> &nbsp; <a id='closeChat' href='/' class='blue-button'><span>Not At This Time</span></a></div></div></div><div id='modal_bg'></div>");
				
				// Center modal
				var leftPos = ($(window).width()/2)-($('#modal').width()/2);
				var topPos = ($(window).height()/2)-($('#modal').height()/2);
				
				$('#modal').css('left',leftPos);
				$('#modal').css('top',topPos);
				
				// Play sound every 4 seconds
				playsound();
				soundIV = setInterval(playsound, 4000);
				
				// If chat accepted
				$('#startChat').click(function(e)
				{
				
					e.preventDefault();
				
					remove_modal();
					NewWindow('/chat/index/' +object.expert_id + '/' + object.session_id, 'chatwindow', 500, 500, true, 'center');
				
				});
				
				// If Chat Denied
				$('#closeChat').click(function(e)
				{
				
					// Send a busy notification to the client
					e.preventDefault();
					
					var sockObject = 
					{
						'type' : 'expert_busy',
						'expert_id' : object.expert_id,
						'session_id' : object.session_id
					};
					
					socket.send(sockObject);
					
					setTimeout(function()
					{
					
						remove_modal();
					
					}, 500);
				
				});
		
			}
		
		});
	
	}
	
	function remove_modal()
	{
		
		clearInterval(soundIV);
		$('#modal').remove();
		$('#modal_bg').remove();
		
	}
	
	function playsound()
	{
	
		$('.playSound').remove();
		$.playSound("/media/new.mp3");
	
	}
	
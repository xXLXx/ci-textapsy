
	var timerInterval;
	var socket = new RampNode('http://imyouranswer.rampnode.com/');
	var active_chat = 0;
	var detectClose = 1;
	
	socket.on('message', function(data)
	{
	
		if(data.session_id == session_id)
		{
	
			switch(data.type)
			{
			
				case "message":
				
					var msg = data.message;
					$('#response_div').prepend(msg);
				
				break;
				
				case "start_chat":
				
					var msg = "<div style='color:red;'>Expert has joined the chat. The timer has started.</div>";
					
					active_chat = 1;
					$('#response_div').prepend(msg);
					timerInterval = setInterval(timer, 1000);
				
				break;
				
				case "end_chat":
				
					// Record Chat Length
					record_chat_length();
					send_review_message();
					
					var postObject = { 'session_id' : session_id, 'length' : time };
					$.post("/chat/record/", postObject);
					
					var msg = "<div style='color:red;'>Chat Ended. Window will close in 5 seconds.</div>";
					active_chat = 0;
					$('#response_div').prepend(msg);
					clearInterval(timerInterval);
					
					setTimeout(function()
					{
					
						window.close();
					
					}, 5000);
				
				break;
				
				case "expert_busy":
				
					active_chat = 0;
					
					$('#sendButton').attr('disabled', 'true');
					$('#chatbox').attr('disabled', 'true');
					
					var msg = "<div style='color:red;'>This expert is unavailable to take your chat at this time. Please try again later. This window will close in 10 seconds.</div>";
					$('#response_div').prepend(msg);
					
					clearInterval(timerInterval);
					
					setTimeout(function()
					{
					
						window.close();
					
					}, 10000);
				
				break;
				
				case "block_user":
				
					// Record Chat Length
					if(expert_id == member_id)
					{
					
						record_chat_length(1);
						
						var msg = "<div style='color:red;'>The expert has blocked you. The chat has ended.</div>";
						active_chat = 0;
						$('#response_div').prepend(msg);
						clearInterval(timerInterval);
					
					}
				
				break;
				
				case "end_chat_disconnect":
				
					record_chat_length();
					send_review_message();
					
					var msg = "<div style='color:red;'>User Disconnected, Chat Ended. This window will close in 10 seconds.</div>";
					active_chat = 0;
					$('#response_div').prepend(msg);
					clearInterval(timerInterval);
					
					setTimeout(function()
					{
					
						window.close();
					
					}, 10000);
				
				break;
				
				case "refill_account":
				
					// Disable controls
					$('#sendButton').attr('disabled', 'true');
					$('#chatbox').attr('disabled', 'true');
					
					detectClose = 0;
				
					// Record chat length in case they leave at this point :-)
					record_chat_length();
					
					active_chat = 0;
					
					var msg = "<div style='color:red;'>User is refilling account. You will be notified when the user is ready to chat again. This window will close in 10 seconds.</div>";
					$('#response_div').prepend(msg);
					clearInterval(timerInterval);
					
					if(member_id != expert_id)
					{
						
						window.location = "/chat/fund_from_chat/"+session_id;
						
					}
					else
					{
					
						setTimeout(function()
						{
						
							window.close();
						
						}, 10000);
					
					}
					
				break;
				
				case "pause_chat":
				
					record_chat_length();
					
					var msg = "<div style='color:red;'>Chat & Timer Paused.</div>";
					active_chat = 0;
					$('#response_div').prepend(msg);
					clearInterval(timerInterval);
				
				break;
				
				case "resume_chat":
				
					var msg = "<div style='color:red;'>Chat & Timer Resumed.</div>";
					active_chat = 1;
					$('#response_div').prepend(msg);
					timerInterval = setInterval(timer, 1000);
				
				break;
			
			}
			
			// Update transcripts
			if(member_id == expert_id || data.type == 'end_chat_disconnect')
			{

				var postObject = 
				{
					'member_id' : member_id,
					'expert_id' : expert_id,
					'session_id' : session_id,
					'message' : msg
				};
				
				$.post("/chat/record/", postObject);
			
			}
		
		}
	
	});
	
	function send_review_message()
	{
	
		// Only expert sends this message
		// What if expert disconnects? rate_expert
		
		var object = 
		{
			'type' : 'rate_expert',
			'expert_id' : expert_id,
			'client_id' : client_id,
			'session_id' : session_id
		};
		
		socket.send(object);
	
	}
	
	function record_chat_length(block_user)
	{

		if(member_id == expert_id)
		{

			var postObject = { 'session_id' : session_id, 'length' : (time_available-time), 'block_user' : block_user };
			$.post("/chat/record_chat_length/", postObject);
		
		}
	
	}
	
	socket.on('disconnect', function(){ });
	
	window.onbeforeunload = function()
	{
	
		if(member_id == expert_id)
		{
		
			record_chat_length();
		
		}

		if(detectClose)
		{

			var object = 
			{
				'type' : 'end_chat_disconnect',
				'expert_id' : expert_id,
				'session_id' : session_id
			};
			
			socket.send(object);
		
		}
		
	}
	
	$(document).ready(function()
	{
	
		$('.fund_chat').live('click', function(e)
		{
		
			e.preventDefault();
			
			var object = 
			{
				'type' : 'refill_account',
				'expert_id' : expert_id,
				'session_id' : session_id
			};
			
			socket.send(object);
		
		});
	
		$('.pause_timer').click(function(e)
		{
		
			e.preventDefault();
		
			if(member_id == expert_id)
			{
			
				if(active_chat == 1)
				{
				
					var object = 
					{
						'type' : 'pause_chat',
						'expert_id' : expert_id,
						'session_id' : session_id
					};
					
					socket.send(object);
				
				}
				else
				{
				
					var object = 
					{
						'type' : 'resume_chat',
						'expert_id' : expert_id,
						'session_id' : session_id
					};
					
					socket.send(object);
				
				}
			
			}
		
		});
		
		$('.end_chat').click(function(e)
		{
		
			e.preventDefault();
			
			if(confirm('Are you sure you want to end this chat?'))
			{
		
				var object = 
				{
					'type' : 'end_chat',
					'expert_id' : expert_id,
					'session_id' : session_id
				};
				
				socket.send(object);
			
			}
		
		});
		
		$('.block_user').click(function(e)
		{
		
			e.preventDefault();
			
			if(confirm('Are you sure you want to block this client from chatting with you?'))
			{
		
				if(member_id == expert_id)
				{
				
					var object = 
					{
						'type' : 'block_user',
						'expert_id' : expert_id,
						'session_id' : session_id
					};
					
					socket.send(object);
			
				}
			
			}
		
		});
	
		$("#chatbox").keypress(function(e)
		{
		
			var code = (e.keyCode ? e.keyCode : e.which);
			
			if (code == 13)
			{
			
				$('#chat_area').submit();
				
			}
			
		});
	
		if(member_id != expert_id)
		{
		
			var object = 
			{
				'type' : 'new_chat',
				'expert_id' : expert_id,
				'session_id' : session_id,
				'username' : username
			};
			
			socket.send(object);
		
		}
		else
		{
		
			var object = 
			{
				'type' : 'start_chat',
				'expert_id' : expert_id,
				'session_id' : session_id
			};
			
			socket.send(object);
			
		}
		
		$('#chat_area').submit(function(e)
		{
		
			e.preventDefault();
			
			var chatValue = escapeHtmlEntities( $("textarea[name='message']").val() );
			
			var message = "<div class='"+(member_id == expert_id ? "expert_post" : "member_post") +"'>"+username + ': ' + chatValue+"</div>";
			
			var object = 
			{
				'type' : 'message',
				'expert_id' : expert_id,
				'session_id' : session_id,
				'message' : message
			};
			
			socket.send(object);
			
			setTimeout(function()
			{
			
				$("#chatbox").val('');
			
			}, 100);
		
		});
	
	});
	
	function timer()
	{
	
		time=(time-1);
		
		// 2 Minute warning
		if(time==120 && expert_id == member_id)
		{
		
			// 2 minute warning
			var object = 
			{
				'type' : 'message',
				'expert_id' : expert_id,
				'session_id' : session_id,
				'message' : "<div style='font-weight:bold;color:red;'>** 2 minutes of chat time left. <a class='fund_chat' href='/chat/fund_from_chat/"+session_id+"'>Click here</a> to fund your account.</div>"
			};
			
			socket.send(object);
		
		}
		
		// 1 Minute Warning
		else if(time==60 && expert_id == member_id)
		{
		
			// 2 minute warning
			var object = 
			{
				'type' : 'message',
				'expert_id' : expert_id,
				'session_id' : session_id,
				'message' : "<div style='font-weight:bold;color:red;'>** 1 minute of chat time left. <a class='fund_chat' href='/chat/fund_from_chat/"+session_id+"'>Click here</a> to fund your account.</div>"
			};
			
			socket.send(object);
		
		}

		// Redirect at no more time
		if(time==0)
		{
		
			var object = 
			{
				'type' : 'refill_account',
				'expert_id' : expert_id,
				'session_id' : session_id
			};
			
			socket.send(object);
		
		}
		
		// Parse Time
		var minutes = pad(parseInt(time/60));
		var seconds = pad(time%60);
		
		// Output into timer
		$('#timer').html("Chat Time: " + minutes + ":" + seconds);
	
	}

    function recordChat()
    {



    }
	
	function pad(val)
	{
	
		var valString = val + "";
	
		if(valString.length < 2)
		{
			return "0" + valString;
		}
		else
		{
			return valString;
		}
		
	}
	
	if(typeof escapeHtmlEntities == 'undefined') {
        escapeHtmlEntities = function (text) {
            return text.replace(/[\u00A0-\u2666<>\&]/g, function(c) {
                return '&' + 
                (escapeHtmlEntities.entityTable[c.charCodeAt(0)] || '#'+c.charCodeAt(0)) + ';';
            });
        };

        // all HTML4 entities as defined here: http://www.w3.org/TR/html4/sgml/entities.html
        // added: amp, lt, gt, quot and apos
        escapeHtmlEntities.entityTable = {
            34 : 'quot', 
            38 : 'amp', 
            39 : 'apos', 
            60 : 'lt', 
            62 : 'gt', 
            160 : 'nbsp', 
            161 : 'iexcl', 
            162 : 'cent', 
            163 : 'pound', 
            164 : 'curren', 
            165 : 'yen', 
            166 : 'brvbar', 
            167 : 'sect', 
            168 : 'uml', 
            169 : 'copy', 
            170 : 'ordf', 
            171 : 'laquo', 
            172 : 'not', 
            173 : 'shy', 
            174 : 'reg', 
            175 : 'macr', 
            176 : 'deg', 
            177 : 'plusmn', 
            178 : 'sup2', 
            179 : 'sup3', 
            180 : 'acute', 
            181 : 'micro', 
            182 : 'para', 
            183 : 'middot', 
            184 : 'cedil', 
            185 : 'sup1', 
            186 : 'ordm', 
            187 : 'raquo', 
            188 : 'frac14', 
            189 : 'frac12', 
            190 : 'frac34', 
            191 : 'iquest', 
            192 : 'Agrave', 
            193 : 'Aacute', 
            194 : 'Acirc', 
            195 : 'Atilde', 
            196 : 'Auml', 
            197 : 'Aring', 
            198 : 'AElig', 
            199 : 'Ccedil', 
            200 : 'Egrave', 
            201 : 'Eacute', 
            202 : 'Ecirc', 
            203 : 'Euml', 
            204 : 'Igrave', 
            205 : 'Iacute', 
            206 : 'Icirc', 
            207 : 'Iuml', 
            208 : 'ETH', 
            209 : 'Ntilde', 
            210 : 'Ograve', 
            211 : 'Oacute', 
            212 : 'Ocirc', 
            213 : 'Otilde', 
            214 : 'Ouml', 
            215 : 'times', 
            216 : 'Oslash', 
            217 : 'Ugrave', 
            218 : 'Uacute', 
            219 : 'Ucirc', 
            220 : 'Uuml', 
            221 : 'Yacute', 
            222 : 'THORN', 
            223 : 'szlig', 
            224 : 'agrave', 
            225 : 'aacute', 
            226 : 'acirc', 
            227 : 'atilde', 
            228 : 'auml', 
            229 : 'aring', 
            230 : 'aelig', 
            231 : 'ccedil', 
            232 : 'egrave', 
            233 : 'eacute', 
            234 : 'ecirc', 
            235 : 'euml', 
            236 : 'igrave', 
            237 : 'iacute', 
            238 : 'icirc', 
            239 : 'iuml', 
            240 : 'eth', 
            241 : 'ntilde', 
            242 : 'ograve', 
            243 : 'oacute', 
            244 : 'ocirc', 
            245 : 'otilde', 
            246 : 'ouml', 
            247 : 'divide', 
            248 : 'oslash', 
            249 : 'ugrave', 
            250 : 'uacute', 
            251 : 'ucirc', 
            252 : 'uuml', 
            253 : 'yacute', 
            254 : 'thorn', 
            255 : 'yuml', 
            402 : 'fnof', 
            913 : 'Alpha', 
            914 : 'Beta', 
            915 : 'Gamma', 
            916 : 'Delta', 
            917 : 'Epsilon', 
            918 : 'Zeta', 
            919 : 'Eta', 
            920 : 'Theta', 
            921 : 'Iota', 
            922 : 'Kappa', 
            923 : 'Lambda', 
            924 : 'Mu', 
            925 : 'Nu', 
            926 : 'Xi', 
            927 : 'Omicron', 
            928 : 'Pi', 
            929 : 'Rho', 
            931 : 'Sigma', 
            932 : 'Tau', 
            933 : 'Upsilon', 
            934 : 'Phi', 
            935 : 'Chi', 
            936 : 'Psi', 
            937 : 'Omega', 
            945 : 'alpha', 
            946 : 'beta', 
            947 : 'gamma', 
            948 : 'delta', 
            949 : 'epsilon', 
            950 : 'zeta', 
            951 : 'eta', 
            952 : 'theta', 
            953 : 'iota', 
            954 : 'kappa', 
            955 : 'lambda', 
            956 : 'mu', 
            957 : 'nu', 
            958 : 'xi', 
            959 : 'omicron', 
            960 : 'pi', 
            961 : 'rho', 
            962 : 'sigmaf', 
            963 : 'sigma', 
            964 : 'tau', 
            965 : 'upsilon', 
            966 : 'phi', 
            967 : 'chi', 
            968 : 'psi', 
            969 : 'omega', 
            977 : 'thetasym', 
            978 : 'upsih', 
            982 : 'piv', 
            8226 : 'bull', 
            8230 : 'hellip', 
            8242 : 'prime', 
            8243 : 'Prime', 
            8254 : 'oline', 
            8260 : 'frasl', 
            8472 : 'weierp', 
            8465 : 'image', 
            8476 : 'real', 
            8482 : 'trade', 
            8501 : 'alefsym', 
            8592 : 'larr', 
            8593 : 'uarr', 
            8594 : 'rarr', 
            8595 : 'darr', 
            8596 : 'harr', 
            8629 : 'crarr', 
            8656 : 'lArr', 
            8657 : 'uArr', 
            8658 : 'rArr', 
            8659 : 'dArr', 
            8660 : 'hArr', 
            8704 : 'forall', 
            8706 : 'part', 
            8707 : 'exist', 
            8709 : 'empty', 
            8711 : 'nabla', 
            8712 : 'isin', 
            8713 : 'notin', 
            8715 : 'ni', 
            8719 : 'prod', 
            8721 : 'sum', 
            8722 : 'minus', 
            8727 : 'lowast', 
            8730 : 'radic', 
            8733 : 'prop', 
            8734 : 'infin', 
            8736 : 'ang', 
            8743 : 'and', 
            8744 : 'or', 
            8745 : 'cap', 
            8746 : 'cup', 
            8747 : 'int', 
            8756 : 'there4', 
            8764 : 'sim', 
            8773 : 'cong', 
            8776 : 'asymp', 
            8800 : 'ne', 
            8801 : 'equiv', 
            8804 : 'le', 
            8805 : 'ge', 
            8834 : 'sub', 
            8835 : 'sup', 
            8836 : 'nsub', 
            8838 : 'sube', 
            8839 : 'supe', 
            8853 : 'oplus', 
            8855 : 'otimes', 
            8869 : 'perp', 
            8901 : 'sdot', 
            8968 : 'lceil', 
            8969 : 'rceil', 
            8970 : 'lfloor', 
            8971 : 'rfloor', 
            9001 : 'lang', 
            9002 : 'rang', 
            9674 : 'loz', 
            9824 : 'spades', 
            9827 : 'clubs', 
            9829 : 'hearts', 
            9830 : 'diams', 
            338 : 'OElig', 
            339 : 'oelig', 
            352 : 'Scaron', 
            353 : 'scaron', 
            376 : 'Yuml', 
            710 : 'circ', 
            732 : 'tilde', 
            8194 : 'ensp', 
            8195 : 'emsp', 
            8201 : 'thinsp', 
            8204 : 'zwnj', 
            8205 : 'zwj', 
            8206 : 'lrm', 
            8207 : 'rlm', 
            8211 : 'ndash', 
            8212 : 'mdash', 
            8216 : 'lsquo', 
            8217 : 'rsquo', 
            8218 : 'sbquo', 
            8220 : 'ldquo', 
            8221 : 'rdquo', 
            8222 : 'bdquo', 
            8224 : 'dagger', 
            8225 : 'Dagger', 
            8240 : 'permil', 
            8249 : 'lsaquo', 
            8250 : 'rsaquo', 
            8364 : 'euro'
        };
    }
	
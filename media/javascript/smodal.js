
	var modal_close_callback = function(){};
	
	function open_loading()
	{
	
		$('body').append("<div id='smodal_bg'></div><div id='smodal'><div class='top'></div><div class='middle'><div align='center' style='padding:35px 0 0;'><img src='/media/images/load.gif' border='0' /></div></div><div class='bottom'></div></div>");
		
		// Position modal
		centerModal();
		
		$('#smodal').fadeIn('fast');
		$('#smodal_bg').css({ 'position':'absolute', 'z-index' : '998', 'opacity' : 0, 'background-color':'#000', 'height' : $(document).height(), 'width' : $(window).width() }).animate({ opacity : 0.8 });
	
	}
	
	function open_modal(URL,title)
	{
	
		$('body').append("<div id='smodal_bg'></div><div id='smodal'><div class='top'><a href='/' class='close_smodal'><span>Close</span></a></div><div class='middle'></div><div class='bottom'></div></div>");
		
		// Position modal
		centerModal();
		
		$('.close_smodal').click(function(e){ e.preventDefault(); closeModal(); });
		
		$('#smodal').fadeIn('fast');
		$('#smodal_bg').css({ 'position':'absolute', 'z-index' : '998', 'opacity' : 0, 'background-color':'#000', 'height' : $(document).height(), 'width' : $(window).width() }).animate({ opacity : 0.8 }, function()
		{
		
			$('#smodal_bg').click(function(){ closeModal(); });
			
			ajax_data(URL);
			
		});
	
	}
	
	function ajax_data(URL)
	{
	
		$('#smodal .middle').html("<div align='center' style='padding:10px 0 0;'><img src='/media/images/load.gif' border='0' /></div>");
	
		$.get(URL, function(data)
		{
		
			if(data=='logged')
			{
			
				window.location = '/invite';
			
			}
			else
			{
		
				setTimeout(function()
				{
				
					$('#smodal .middle').html(data);
					
					// $('#smodal .middle .content').css({ 'max-height':($(window).height()-200) + 'px', 'overflow' : 'auto' });
					
					$('.inner_modal').click(function(e){ e.preventDefault(); ajax_data($(this).attr('href')); });
					
					// Bug Fix: Nov 9, 2012: RCK
					// If the smodal container (+ the 100 margin spacing from top) was longer than document height
					// Then the modal_bg would stop and make everything look crappy.
					
					var smodalOuterHeight = $('#smodal').height() + 100;
					var documentHeight = $(document).height();
					
					if(smodalOuterHeight > documentHeight)
					{
					
						// Invrease the smodal_bg to a higher height
						$('#smodal_bg').width('100%');
						$('#smodal_bg').height((smodalOuterHeight-documentHeight)+(documentHeight));
					
					}
					
					// End bug fix
					
					$('#smodal .middle form').submit(function(e)
					{
					
						var ajaxAttrib = 'on';
						if(typeof $(this).attr('ajax') != 'undefined') ajaxAttrib = $(this).attr('ajax');
						
						if(ajaxAttrib=='on')
						{
						
							e.preventDefault();
						
							var form_action = $(this).attr('action');
							
							$.ajaxFileUpload(
							{
								'url' : form_action,
								'data' : $(this).serializeArray(),
								'secureuri' : false,
								'fileElementId' : 'file',
								'dataType' : 'json',
								'success' : function(response)
								{
								
									if(response.error == '1')
									{
									
										alert(response.message);
									
									}
									else
									{
									
										if(typeof response.inline_redirect != 'undefined')
										{
										
											ajax_data(response.inline_redirect);
										
										}
										else
										{
										
											window.location = response.redirect;
										
										}
									
									}
								
								},
								'error' : function()
								{
								
									alert('something went wrong...');
								
								}
							});
						
						}
					
					});
				
				}, 250);
			
			}
		
		});
	
	}
	
	function closeModal()
	{
	
		$('#smodal').fadeOut('fast');
		$('#smodal_bg').fadeOut('fast', function()
		{
		
			$('#smodal').remove();
			$('#smodal_bg').remove();
		
			modal_close_callback();
		
		});
	
	}
	
	function centerModal()
	{
	
		var top_position = 50 + $(window).scrollTop();
	
		var left = ($(window).width() - $('#smodal').width()) / 2;
		$('#smodal').css({'left' : left + 'px', 'top' : top_position+'px'});
		
		$('#modal_bg').css({'height' : $(document).height(), 'width' : $(window).width()});
	
	}
	
	$(document).ready(function()
	{
	
		$('.smodal').on('click', function(e)
		{
		
			e.preventDefault();
			open_modal($(this).attr('href'),$(this).attr('title'));
		
		});
	
	});

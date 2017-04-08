(function($)
{

	$.extend(
	{
	
		playSound: function()
		{
		
			$("<embed src='"+arguments[0]+"' hidden='true' autostart='true' loop='false' class='playSound'>").appendTo('body');
			
		}
	
	});

})(jQuery);
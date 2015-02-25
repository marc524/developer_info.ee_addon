jQuery(document).ready(function() {
	$('#channel_scroll_id').change(function() {
		target = $('#' + $('#channel_scroll_id').val());
		if (target.length) {
			var offset = target.offset().top;
			$('html,body').animate({scrollTop: offset}, 1000);
			return false;
		}
		return true;
	});

	$("a[href='#top']").click(function() {
		$("html, body").animate({ scrollTop: 0 }, 1000);
		return false;
	});

	$('.di_categories-link').click(showCategories);
	$('.di_status-link').click(showStatus);
	$('.di_query-link').click(showEntryQuery);
	$('.di_full-query-link').click(showFullEntryQuery);
	$('.di_template-query-link').click(showTemplateQuery);

	// Show/Hide All Rows
	$('.di_collapse').click(hideAllSections);
	$('.di_expand').click(showAllSections);

	// Show/Hide Specific Rows
	$('.di_showhide').click(showHideSection);

	$('.di_link').addClass('di_hidden');

});

function showCategories() {
	$(this).text($(this).text() == 'Show Categories +' ? 'Hide Categories -' : 'Show Categories +');
	$(this).next().slideToggle('fast');
	return false;
}

function showStatus() {
	$(this).text($(this).text() == 'Show Statuses +' ? 'Hide Statuses -' : 'Show Statuses +');
	$(this).next().slideToggle('fast');
	return false;
}

function showEntryQuery() {
	$(this).text($(this).text() == 'Show Simple Channel Entry Query +' ? 'Hide Simple Channel Entry Query -' : 'Show Simple Channel Entry Query +');
	$(this).next().slideToggle('fast');
	return false;
}

function showFullEntryQuery() {
	$(this).text($(this).text() == 'Show Full Channel Entry Query +' ? 'Hide Full Channel Entry Query -' : 'Show Full Channel Entry Query +');
	$(this).next().slideToggle('fast');
	return false;
}

function showTemplateQuery() {
	$(this).text($(this).text() == 'Show Template Query +' ? 'Hide Template Query -' : 'Show Template Query +');
	$(this).next().slideToggle('fast');
	return false;
}

function initTemplateClasses() 
{
	var diList = cookieList("developer_info");
	diList.tmpls();
}

function initChannelClasses() 
{
	var diList = cookieList("developer_info");
	diList.chnls();
}

function showAllSections()
{
	var $this = $(this);
	var diList = cookieList("developer_info");

	$.each($('table.mainTable tbody.di_viewable'), function()
    {
    	$(this).removeClass('closed');
		$(this).slideDown('fast');
		$(this).addClass('open');
		var section_id = $(this).attr('id');
		var di_cookie_close = section_id + 'X';
		var di_cookie_open = section_id + 'O';

		//clear the cookie of any previous
		diList.remove( di_cookie_close );
		diList.remove( di_cookie_open );

		//write the cookie
		diList.add( di_cookie_open );
	});

	$.each($('a.di_showhide'), function()
	{
		//change the link text
		$(this).text('Hide Details -');
	});
}

function hideAllSections()
{
	var $this = $(this);
	var diList = cookieList("developer_info");

	$.each($('table.mainTable tbody.di_viewable'), function()
    {
    	$(this).removeClass('open');
		$(this).slideUp('fast');
		$(this).addClass('closed');
		var section_id = $(this).attr('id');
		var di_cookie_close = section_id + 'X';
		var di_cookie_open = section_id + 'O';

		//clear the cookie of any previous
		diList.remove( di_cookie_close );
		diList.remove( di_cookie_open );

		//write the cookie
		diList.add( di_cookie_close );
	});

	$.each($('a.di_showhide'), function()
	{
		//change the link text
		$(this).text('Show Details +');
	});
}

function showHideSection() 
{
	var $this = $(this);
	var diList = cookieList("developer_info");
	$(this).text($(this).text() == 'Hide Details -' ? 'Show Details +' : 'Hide Details -');

	var section_id = $(this).attr('title');
	var theTbody = ('#' + section_id);
	$(theTbody).slideToggle('fast',function(){
		//clear out the classes and start fresh
		$(theTbody).removeClass('closed');
		$(theTbody).removeClass('open');
		var di_cookie_close = section_id + 'X';
		var di_cookie_open = section_id + 'O';
		diList.remove( di_cookie_close );
		diList.remove( di_cookie_open );

		if ($(this).is(':hidden')) {
			//write the cookie
			diList.add( di_cookie_close );

			//add the closed class so we know what to do next time
			$(theTbody).addClass('closed');

		} else {
			//write the cookie
			diList.add( di_cookie_open );

			//add the open class so we know what to do next time
			$(theTbody).addClass('open');
		}
	});

	return false;
}
var cookieList = function(cookieName) {
	//http://stackoverflow.com/questions/1959455/how-to-store-an-array-in-jquery-cookie
    //When the cookie is saved the items will be a comma seperated string
    //So we will split the cookie by comma to get the original array
    var cookie = $.cookie(cookieName);
    //Load the items or a new array if null.
    var items = cookie ? cookie.split(/,/) : new Array();

    //Return a object that we can use to access the array.
    //while hiding direct access to the declared items array
    //this is called closures see http://www.jibbering.com/faq/faq_notes/closures.html
    return {
        "add": function(val) {
            items.push(val);
            $.cookie(cookieName, items.join(','));
        },
        "remove": function (val) { 
            indx = items.indexOf(val); 
            if(indx!=-1) items.splice(indx, 1); 
            $.cookie(cookieName, items.join(','));        
        },
        "items": function() {
            return items;
        },
        "tmpls": function() {
        	var i; 
        	for (i = 0; i < items.length; ++i) {
        		if (items[i].toString().match(/(t|l)[0-9]+[X]/g)) {
        			var htname = items[i].substring(0, items[i].length - 1);
        			$('#' + htname).removeClass('open').addClass('closed');
        			$('.' + htname).text('Show Details +');
        		}
        		if (items[i].toString().match(/(t|l)[0-9]+[O]/g)) {
        			var stname = items[i].substring(0, items[i].length - 1);
        			$('#' + stname).removeClass('closed').addClass('open');
        			$('.' + stname).text('Hide Details -');
        		}      		
        	}
        },
        "chnls": function() {
        	var i; 
        	//get only the channel row IDs
        	for (i = 0; i < items.length; ++i) {
        		if (items[i].toString().match(/c[0-9]+[X]/g)) {
        			var hcname = items[i].substring(0, items[i].length - 1);
        			$('#' + hcname).removeClass('open').addClass('closed');
        			$('.' + hcname).text('Show Details +');
        		}
        		if (items[i].toString().match(/c[0-9]+[O]/g)) {
        			var scname = items[i].substring(0, items[i].length - 1);
        			$('#' + scname).removeClass('closed').addClass('open');
        			$('.' + scname).text('Hide Details -');
        		}        		
        	}
        }
    }
}
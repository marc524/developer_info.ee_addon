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

	$(".di_link").addClass("di_hidden");
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
	$(this).text($(this).text() == 'Show Entry Query +' ? 'Hide Entry Query -' : 'Show Entry Query +');
	$(this).next().slideToggle('fast');
	return false;
}
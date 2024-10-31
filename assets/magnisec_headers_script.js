jQuery(document).ready(function($){
	
	$('.copy_btn').on('click', function(e) {
		e.preventDefault();
		var copyText = $.trim($("#code").text());
		var textarea = document.createElement("textarea");
		textarea.textContent = copyText;
		textarea.style.position = "fixed";
		document.body.appendChild(textarea);
		var text_to_copy = textarea.select();
		if (!navigator.clipboard){
			var text_to_copy = textarea.select();
			document.execCommand("copy");
				
		} else{
			navigator.clipboard.writeText(copyText);
		}    
		$(".m_sec_clipboard_note").show();
		setTimeout(function(){ $(".m_sec_clipboard_note").hide(); }, 3000);

		document.body.removeChild(textarea);
	});
	$('.copy_btn2').on('click', function(e) {
		e.preventDefault();
		var copyText = $.trim($("#code2").text());
		var textarea = document.createElement("textarea");
		textarea.textContent = copyText;
		textarea.style.position = "fixed";
		document.body.appendChild(textarea);
		var text_to_copy = textarea.select();
		if (!navigator.clipboard){
			var text_to_copy = textarea.select();
			document.execCommand("copy");
				
		} else{
			navigator.clipboard.writeText(copyText);
		}    
		$(".m_sec_clipboard_note2").show();
		setTimeout(function(){ $(".m_sec_clipboard_note2").hide(); }, 3000);

		document.body.removeChild(textarea);
	});

	$('.m_sec_permission_check').on("change", function () {
		if ($(this).is(":checked"))
		{
			$('input.sec_custom_checkbox_input_permission[type="checkbox"]').each(function () {
				$(this).prop('disabled', false);
			});
		}
		else
		{
			$('input.sec_custom_checkbox_input_permission[type="checkbox"]').each(function () {
				$(this).prop('disabled', true);
				$(this).prop('checked', false);
			});
		}
	});
	
	if ($('input.sec_custom_checkbox_input_header[type="checkbox"]').is(":checked")) {
		$('.m_sec_selectall').removeClass("m_sec_select");
		$('.m_sec_selectall').removeClass("m_sec_unselect");
		$('.m_sec_selectall').addClass("m_sec_unselect");
		$('.m_sec_selectall').html("Unselect all");
	}
	else
	{
		$('.m_sec_selectall').removeClass("m_sec_select");
		$('.m_sec_selectall').removeClass("m_sec_unselect");
		$('.m_sec_selectall').addClass("m_sec_select");
		$('.m_sec_selectall').html("Select all");
	}
	if ($('input.sec_custom_checkbox_input_cookie[type="checkbox"]').is(":checked")) {
		$('.m_sec_selectall_cookie').removeClass("m_sec_select_cookie");
		$('.m_sec_selectall_cookie').removeClass("m_sec_unselect_cookie");
		$('.m_sec_selectall_cookie').addClass("m_sec_unselect_cookie");
		$('.m_sec_selectall_cookie').html("Unselect all");
	}
	else
	{
		$('.m_sec_selectall_cookie').removeClass("m_sec_select_cookie");
		$('.m_sec_selectall_cookie').removeClass("m_sec_unselect_cookie");
		$('.m_sec_selectall_cookie').addClass("m_sec_select_cookie");
		$('.m_sec_selectall_cookie').html("Select all");
	}
	$('.m_sec_recommended').on("click", function () {
		$('input.sec_custom_checkbox_input_header[type="checkbox"]').each(function () {
			$(this).prop('checked', true);
		});
		$('input.sec_custom_checkbox_input_permission[type="checkbox"]').each(function () {
			$(this).prop('checked', true);
			$(this).prop('disabled', false);
		});
	});
	$('.m_sec_recommended_cookie').on("click", function () {
		$('input.sec_custom_checkbox_input_cookie[type="checkbox"]').each(function () {
			$(this).prop('checked', true);
		});
	});
	$('.m_sec_selectall').on("click", function () {
		if ($(this).hasClass("m_sec_unselect"))
		{
			$('input.sec_custom_checkbox_input_header[type="checkbox"]').each(function () {
				$(this).prop('checked', false);
			});
			$('input.sec_custom_checkbox_input_permission[type="checkbox"]').each(function () {
				$(this).prop('disabled', true);
				$(this).prop('checked', false);
			});
			$(this).html("Select all");
			$(this).addClass("m_sec_select");
			$(this).removeClass("m_sec_unselect");
		}
		else if ($(this).hasClass("m_sec_select"))
		{
			$('input.sec_custom_checkbox_input_header[type="checkbox"]').each(function () {
				$(this).prop('checked', true);
			});
			$('input.sec_custom_checkbox_input_permission[type="checkbox"]').each(function () {
				$(this).prop('checked', true);
				$(this).prop('disabled', false);
			});
			$(this).html("Unselect all");
			$(this).addClass("m_sec_unselect");
			$(this).removeClass("m_sec_select");
		}
	});
	$('.m_sec_selectall_cookie').on("click", function () {
		if ($(this).hasClass("m_sec_unselect_cookie"))
		{
			$('input.sec_custom_checkbox_input_cookie[type="checkbox"]').each(function () {
				$(this).prop('checked', false);
			});
			$(this).html("Select all");
			$(this).addClass("m_sec_select_cookie");
			$(this).removeClass("m_sec_unselect_cookie");
		}
		else if ($(this).hasClass("m_sec_select_cookie"))
		{
			$('input.sec_custom_checkbox_input_cookie[type="checkbox"]').each(function () {
				$(this).prop('checked', true);
			});
			$(this).html("Unselect all");
			$(this).addClass("m_sec_unselect_cookie");
			$(this).removeClass("m_sec_select_cookie");
		}
	});


});

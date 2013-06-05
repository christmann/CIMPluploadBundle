/**
 * Creates a new plupload object and starts the upload
 * @param object user_options
 *			 options
 */
(function($)
{
	$.UserUpload = {};
	$.UserUpload.Bind = function(up, params)
	{
		$('#' + up.settings.browse_button).fadeTo('slow', 1);
	};

	$.UserUpload.FilesAdded = function(up, files)
	{
		var options = up.settings;
		$('#' + options.container + ' .filelist .errormsg').remove();
		$.each(files, function(i, file)
		{
			var n = $('#' + options.container + ' .blankfile').clone();
			n.attr('id', file.id)
					.removeClass('blankfile')
					.find('.filename').text(file.name).end()
					.find('.filesize').text(plupload.formatSize(file.size)).end()
					.show();
			$('#' + options.container + ' .filelist').append(n);
		});
//		$('#' + options.container + ' .preview').hide();
		up.refresh(); // Reposition Flash/Silverlight
		up.start();
	};

	$.UserUpload.UploadProgress = function(up, file)
	{
		$('#' + file.id + ' .current').width(file.percent + "%");
	};

	$.UserUpload.Error = function(up, err)
	{
		var n = $('<div/>')
				.addClass('errormsg')
				.text("[" + err.code + "] " + err.message + " " + (err.file ? "(" + err.file.name + ")" : ""));
		$('#' + up.settings.container + ' .filelist').append(n);

		up.refresh(); // Reposition Flash/Silverlight
	};

	$.UserUpload.FileUploaded = function(up, file, response)
	{
		$('#' + file.id).addClass('finish');
		$('#' + file.id).fadeOut('slow');

		var id = '#' + up.settings.container;
		var json = $.parseJSON(response.response);
		if (!up.settings.multi_selection)
		{
			$(id + ' input[type=hidden]').remove();
			$(id + ' .upload_preview:not(.blank)').remove();
		}

		var field = $('<input type="hidden"/>')
				.attr('name', up.settings.fullName)
				.addClass('upload_id')
				.val(json.id);
		$(id).append(field);

		var img = $(id).find('.upload_preview.blank:first').clone()
				.removeClass('blank')
				.attr('rel', json.id);

		$(img).find('span:first').remove();
		$(img).find('img:first').remove();

		var item = null;
		if (json.is_image)
		{
			item = $('<img/>')
					.attr('src', json.thumb)
					.attr('alt', json.name)
					.attr('title', json.name)
			;
		}
		else
		{
			item = $('<span/>').text(json.name);

		}
		$(img).append(item).show();

		$(id).find('.upload_images').append(img);

        $(id).find('.upload_placeholder').hide();

		up.settings.uploadAction(json);
	};

	$.UserUpload.FileRemove = function()
	{
		var prev = $(this).parents('.upload_preview:first');
		var parent = $(this).parents('.upload_container:first');
		var options = $(parent).data('plupload');
		var id = $(prev).attr('rel');

		$(parent).find('.upload_id').each(function()
		{
			if ($(this).val() == id)
			{
				$(this).remove();
			}
		});
		$(prev).remove();

        parent.find('.upload_placeholder').show();

		if (!options.multi_selection)
		{
			$(parent).append($('<input type="hidden"/>').attr('name', options.fullName).addClass('upload_id'));
		}
	};

	$.fn.UserUpload = function(options)
	{
		options = $.extend({}, $.fn.UserUpload.defaults, options);
		options.container = $(this).attr('id');

		$(this).data('plupload', options);

		// create plupload object
		var uploader = new plupload.Uploader(options);

		uploader.bind('Init', $.UserUpload.Bind);
		uploader.init();
		uploader.bind('FilesAdded', $.UserUpload.FilesAdded);
		uploader.bind('UploadProgress', $.UserUpload.UploadProgress);
		uploader.bind('Error', $.UserUpload.Error);

		uploader.bind('FileUploaded', $.UserUpload.FileUploaded);
		$('#' + options.container).on('click', '.upload_remove', $.UserUpload.FileRemove);
	};

	$.fn.UserUpload.defaults = {
		runtimes: 'gears,html5,flash,silverlight,browserplus',
		browse_button: 'pickfiles',
		max_file_size: '32mb',
		url: 'upload.php',
		flash_swf_url: '/plupload/js/plupload.flash.swf',
		silverlight_xap_url: '/plupload/js/plupload.silverlight.xap',
		multi_selection: false,
		filters: [
			{title: "Bilder", extensions: "jpg,png"}
		],
		uploadAction: function(json) {},
		fullName: ""
	};

})(jQuery);
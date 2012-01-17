/**
 * Creates a new plupload object and starts the upload
 * @param object user_options
 *			 options
 */
function UserUpload(user_options)
{
	// default options
	var options = {
		runtimes:'gears,html5,flash,silverlight,browserplus',
		browse_button:'pickfiles',
		container:'container',
		max_file_size:'32mb',
		url:'upload.php',
		flash_swf_url:'/plupload/js/plupload.flash.swf',
		silverlight_xap_url:'/plupload/js/plupload.silverlight.xap',
		multi_selection:false,
		filters:[
			{title:"Bilder", extensions:"jpg,png"}
		],
		uploadAction: function(json){}
	};
	// merge user with default options
	$.extend(options, user_options);

	// create plupload object
	var uploader = new plupload.Uploader(options);
	var active = false;

	uploader.bind('Init', function(up, params)
	{
		active = true;
		$('#' + options.browse_button).fadeTo('slow', 1);
	});

	uploader.init();

	uploader.bind('FilesAdded', function(up, files)
	{
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
		$('#' + options.container + ' .preview').hide();
		up.refresh(); // Reposition Flash/Silverlight
		up.start();
	});

	uploader.bind('UploadProgress', function(up, file)
	{
		$('#' + file.id + ' .current').width(file.percent + "%");
	});

	uploader.bind('Error', function(up, err)
	{
		var n = $('<div/>')
				.addClass('errormsg')
				.text("[" + err.code + "] " + err.message + " " + (err.file ? "(" + err.file.name + ")" : ""));
		$('#' + options.container + ' .filelist').append(n);

		up.refresh(); // Reposition Flash/Silverlight
	});

	uploader.bind('FileUploaded', function(up, file, response)
	{
		$('#' + file.id).addClass('finish');
		$('#' + file.id).fadeOut('slow');

		var json = $.parseJSON(response.response);

		options.uploadAction(json);
	});
}
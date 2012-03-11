		function selectAreaTranslate(link)
		{
			lang_code = $(link).attr('title');

			if (lang_code) {
				$.jqURL.loc(translate_window_url + lang_code, {w:780,h:400,wintype:'_blank'});
			}
		}
				function  chars_limit(id, size) {
					var left;
					left = size - $("#" + id).val().length;
					if (left >= 0) {
						$("#" + id + "_symbols_left").html(String(left));
					} else {
						alert("You can not enter any more symbols!");
						text = $("#" + id).val();
						$("#" + id).val(text.substr(0, size));
					}
				}
				$(document).ready( function() {
                    $("#add_option").click(function() {
                        max_options_id = max_options_id + 1;
                        option_id = max_options_id;
                        // Заменить инкремент в js option_id на db id auto_increment
                        $('<tr id="' + option_id + '_id"><td>&nbsp;</td><td><input type="text" name="options[' + option_id + ']" size="45"></td><td><input class="button delete_button delete_field_now" type=button name="delete" onClick="delete_checkbox_option_from_table(this)" value="Delete"></td></tr>').appendTo('.standardTable');
                        $("#drag_table").tableDnDUpdate();
                        $("#serialized_order").val($("#drag_table").tableDnDSerialize());
                    });
                    $(".delete_option_now").click(function() {
                    	delete_checkbox_option_from_table(this)
                    });
				});
				
				function delete_checkbox_option_from_table(obj) {
					$(obj).parent().parent().remove();
                    $("#serialized_order").val($("#drag_table").tableDnDSerialize());
				}
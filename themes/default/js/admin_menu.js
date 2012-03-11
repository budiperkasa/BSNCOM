						function matchCurrentRouteWithControllerRoute(parts, sinonim_parts)
                     	{
					        _break = false;
					        var _args = new Array();
					        count_part_of_route = 0;
					        
					        parts_length = parts.length;
					        sinonim_parts_length = sinonim_parts.length

					        // was i<(sinonim_parts_length+1)
                         	for (i = 0; i<(sinonim_parts_length); i++) {
                         		key = i;
                         		current_url_part = parts[i];
                         		if (key == parts_length) {
					        		if (sinonim_parts_length > parts_length && sinonim_parts[key] != '%+') {
					        			_break = true;
					        		}
					        	} else {
	                         		switch (sinonim_parts[key]) {
						        		case current_url_part:
						        			break;
						        		case '%':
						        			_args[count_part_of_route] = current_url_part;
						        			break;
						        		case '%+':
						        			return !(_break);
						        			break;
						        		default:
						        			_break = true;
	                         		}
					        	}
					        	count_part_of_route++;
                         	}
					        return !(_break);
                     	}
                     	
                         $(document).ready(function()
                         {
                         	// Sinonims and urls arrays defined in 'admin_main_menu.tpl'
                         	
                         	 opened_var = 'undefined';

                         	 sinonims_length = sinonims.length;
                         	 for (j = 0; j<sinonims_length; j++) {
                         	 	if (matchCurrentRouteWithControllerRoute(route, sinonims[j])) {
                         	 		opened_var = urls[j];
                         	 	}
                         	 }
                             $("#ex1").show();
                             
                             // ---- TREE -----
                             $("#browser").treeview(
                             {
                                 persist: "location",
                                 opened: opened_var,
                                 cookieId: "navigationtree",
                                 collapsed: true,
                                 unique: false
                         	 });
                             // ---- TREE -----
                         });
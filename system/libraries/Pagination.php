<?php
class CI_Pagination
{
    protected $_num_per_page = null;               // Rows per each page
    protected $_curr_page = 0;                     // Current Page number
    protected $_pages_num = 0;                     // Total number of pages
    protected $_num_per_page_line = 10;            // Pages per each line
    protected $_curr_page_line = 0;                // Current page line number
    protected $_page_lines_num = 0;                // Total number of page lines
    protected $_num_rows = 0;                      // Total number of rows
    protected $_start_row = 0;                     // Current start row on current page
    protected $_url = '';

    protected $_from;
    protected $_result_query;
    protected $_select_query;

    public function __construct($config = null)
    {
    	if (isset($config['args']))
    		$args = $config['args'];

    	if (isset($config['args']['page'])) {
    		$page = $args['page'];
    	} else {
    		$page = 1;
    	}

        if (isset($config['num_per_page'])) {
            $this->_num_per_page = $config['num_per_page'];
        }
        if (isset($config['num_per_page_line'])) {
            $this->_num_per_page_line = $config['num_per_page_line'];
        }
        if (isset($config['url'])) {
            $this->_url = $config['url'];
            if (isset($args['orderby']) && isset($args['direction'])) {
				$this->_url .= 'orderby/' . $args['orderby'] . '/direction/' . $args['direction'] . '/';
			}
        }

		$this->_curr_page = $page;
		$this->_curr_page_line = ceil($this->_curr_page/$this->_num_per_page_line);
    }
    
    public function setUrl($url)
    {
    	$this->_url = $url;
    }
    
    public function setNumPerPage($num_per_page)
    {
    	$this->_num_per_page = $num_per_page;
    }

    /**
     * Sets the number of rows from DB count(*)
     *
     * @param integer $count_rows
     */
    public function setCount($count_rows)
    {
    	$this->_num_rows = $count_rows;
    	if ($this->_num_per_page) {
	        $this->_start_row = ($this->_curr_page-1)*$this->_num_per_page;
	        $this->_pages_num = ceil($this->_num_rows/$this->_num_per_page);
	        $this->_page_lines_num = ceil($this->_pages_num/$this->_num_per_page_line);
    	} else {
    		$this->_start_row = 0;
    	}
    }
    
    public function getResultIds($result_array)
    {
    	return array_slice($result_array, $this->_start_row, $this->_num_per_page);
    }

    public function placeLinksToHtml()
    {
        $res_str = '';

        if ($this->_pages_num > 1) {
            $link = $this->_url;
            
            if ($this->_curr_page-1 != 1)
            	$prev_link = $link . 'page/' . ($this->_curr_page - 1). '/';
            else
            	$prev_link = $link;
            $next_link = $link . 'page/' . ($this->_curr_page + 1). '/';
            if (($this->_curr_page_line - 1) * $this->_num_per_page_line != 1)
            	$prev_line_link = $link . 'page/' . (($this->_curr_page_line - 1) * $this->_num_per_page_line). '/';
            else
            	$prev_line_link = $link;
            $next_line_link = $link . 'page/' . ($this->_curr_page_line * $this->_num_per_page_line + 1). '/';

            $res_str = '<div id="paginator">';

            $res_str .= '<span class="paginator_list">';

            if ($this->_curr_page_line > 1) {
                $res_str .= '<a href="'.$prev_line_link.'" class="prev_line" title="previous list of pages">&lt;&lt; prev line</a>';
            }
            if ($this->_curr_page > 1) {
                $res_str .= '<a href="'.$prev_link.'" class="prev_page" title="previous page">&lt; prev</a>';
            }

            $start_page = ($this->_curr_page_line-1)*$this->_num_per_page_line + 1;
            $end_page = $start_page + $this->_num_per_page_line;
            for ($page_num = $start_page; (($page_num < ($start_page + $this->_num_per_page_line)) && ($page_num <= $this->_pages_num)); $page_num++) {
                if ($page_num != $this->_curr_page)
                	if ($page_num != 1)
                    	$res_str .= '<a href="'.$link.'page/' .  $page_num.'/"">'.$page_num.'</a>';
                    else 
                    	$res_str .= '<a href="'.$link.'">'.$page_num.'</a>';
                else
                    $res_str .= '<strong class="current_page">'.$page_num.'</strong>';
            }

            if ($this->_curr_page != $this->_pages_num) {
                $res_str .= '<a href="'.$next_link.'" class="next_page" title="next page">next &gt;</a>';
            }
            if ($this->_curr_page_line != $this->_page_lines_num) {
                $res_str .= '<a href="'.$next_line_link.'" class="prev_line" title="next list of pages">next line &gt;&gt;</a>';
            }

            $res_str .= '</span>';

            $res_str .= '</div>';
        }
        return $res_str;
    }
    
    public function getUrl()
    {
    	return $this->_url;
    }
    
    public function getPage()
    {
    	if ($this->_num_rows) {
    		return $this->_curr_page;
    	} else {
    		return 0;
    	}
    }
    
    public function count()
    {
    	return $this->_num_rows;
    }
    
    public function countPages()
    {
    	return $this->_pages_num;
    }
    
    public function selectPageBlock()
    {
    	if ($this->_pages_num > 1) {
	    	$result = '<script language="JavaScript" type="text/javascript">
	    		$(document).ready(function() {
	    			$("#page_num").change(function() {
	    				var url = "' . $this->_url . 'page/"+$(this).val();
	    				$("#select_page_form").attr("action", url);
	    				$("#select_page_form").submit();
	    			});
	    		});
	    	</script>
	    	<form id="select_page_form" method="get">
	    		' . LANG_PAGINATION_GOTO_PAGE . ':
	    		<select id="page_num">';
	
	    	if ($this->_pages_num>100 && $this->_pages_num<1000) {
	    		$multiplier = 10;
	    	} elseif ($this->_pages_num>1000 && $this->_pages_num<10000) {
	    		$multiplier = 100;
	    	} elseif ($this->_pages_num>10000 && $this->_pages_num<100000) {
	    		$multiplier = 1000;
	    	} else {
	    		$multiplier = 1;
	    	}
	    	
	    	$i = 1;
	    	while ($i<=$this->_pages_num) {
	    		if ($i == $this->_curr_page)
	    			$selected = 'selected';
	    		else 
	    			$selected = '';
	    		$result .= '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
	    		
	    		if ($i >= $this->_pages_num)
	    			break;
	
	    		if ($i < $this->_pages_num)
		    		if ($i == 1 && $multiplier > 1)
		    			$i = 1*$multiplier;
		    		else
		    			$i += 1*$multiplier;
	    		
	    		if ($i > $this->_pages_num)
	    			$i = $this->_pages_num;
	    	}
	    	
			$result .= '</select>
			</form>';
			return $result;
    	}
    }
}
?>
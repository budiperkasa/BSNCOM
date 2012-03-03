<?php
class levelClass
{
    public $id;
    public $type_id;
    public $name;
    public $active_years;
    public $active_months;
    public $active_days;
    public $eternal_active_period;
    public $featured;
    public $title_enabled;
    public $seo_title_enabled;
    public $meta_enabled;
    public $description_mode;
    public $description_length;
    public $categories_number;
    public $locations_number;
    public $preapproved_mode;
    public $order_num;
    public $logo_enabled;
    public $logo_size;
    public $images_count;
    public $images_size;
    public $images_thumbnail_size;
    public $video_count;
    public $video_size;
    public $files_count;
    public $maps_enabled;
    public $maps_size;
    public $option_print;
    public $option_pdf;
    public $option_quick_list;
    public $option_email_friend;
    public $option_email_owner;
    public $option_report;
    public $ratings_enabled;
    public $reviews_mode;
    public $reviews_richtext_enabled;
    public $social_bookmarks_enabled;
    public $titles_template;
    public $allow_to_edit_active_period;

    public $price_currency;
    public $price_value;

    public function __construct()
    {
        $this->id = 'new';
        $this->type_id = '';
        $this->name = '';
        $this->active_years = 0;
        $this->active_months = 0;
        $this->active_days = 7;
        $this->eternal_active_period = false;
        $this->featured = 0;
        $this->title_enabled = 1;
        $this->seo_title_enabled = 1;
        $this->meta_enabled = 1;
        $this->description_mode = 'enabled';
        $this->description_length = 500;
        $this->categories_number = 1;
        $this->locations_number = 1;
        $this->preapproved_mode = 0;
        $this->order_num = 0;
        $this->logo_enabled = 1;
        $this->logo_size = 147 . '*' . 120;
        $this->images_count = 10;
        $this->images_size = 700 . '*' . 500;
        $this->images_thumbnail_size = 140 . '*' . 140;
        $this->video_count = 10;
        $this->video_size = 524 . '*' . 350;
        $this->files_count = 10;
        $this->maps_enabled = 1;
        $this->maps_size = 524 . '*' . 300;
        $this->option_print = 1;
        $this->option_pdf = 1;
        $this->option_quick_list = 1;
        $this->option_email_friend = 1;
        $this->option_email_owner = 1;
        $this->option_report = 0;
        $this->social_bookmarks_enabled = 1;
        $this->ratings_enabled = 0;
        $this->reviews_mode = 'disabled';
        $this->reviews_richtext_enabled = 0;
        $this->titles_template = '%CUSTOM_TITLE%';
        $this->allow_to_edit_active_period = 0;
    }

    public function explodeSize($value, $part)
    {
    	if ($part === 'width')
    		$part = 0;
    	if ($part === 'height')
    		$part = 1;

        $a = explode('*', $this->$value);
        return $a[$part];
    }

    public function setLevelFromArray($array)
    {
        $this->id                           = $array['id'];
        $this->type_id                      = $array['type_id'];
        $this->name                         = $array['name'];
        $this->active_years                 = $array['active_years'];
        $this->active_months                = $array['active_months'];
        $this->active_days                  = $array['active_days'];
        if (!$this->active_years && !$this->active_months && !$this->active_days) {
        	$this->eternal_active_period = true;
        }
        $this->featured                     = $array['featured'];
        $this->title_enabled                = $array['title_enabled'];
        $this->seo_title_enabled            = $array['seo_title_enabled'];
        $this->meta_enabled                 = $array['meta_enabled'];
        $this->description_mode             = $array['description_mode'];
        $this->description_length           = $array['description_length'];
        $this->categories_number            = $array['categories_number'];
        $this->locations_number             = $array['locations_number'];
        $this->preapproved_mode             = $array['preapproved_mode'];
        $this->order_num                    = $array['order_num'];
        $this->logo_enabled                 = $array['logo_enabled'];
        $this->logo_size                    = $array['logo_size'];
        $this->images_count                 = $array['images_count'];
        $this->images_size                  = $array['images_size'];
        $this->images_thumbnail_size        = $array['images_thumbnail_size'];
        $this->video_count                  = $array['video_count'];
        $this->video_size                   = $array['video_size'];
        $this->files_count                  = $array['files_count'];
        $this->maps_enabled                 = $array['maps_enabled'];
        $this->maps_size                    = $array['maps_size'];
        $this->option_print                 = $array['option_print'];
        $this->option_pdf                   = $array['option_pdf'];
        $this->option_quick_list            = $array['option_quick_list'];
        $this->option_email_friend          = $array['option_email_friend'];
        $this->option_email_owner           = $array['option_email_owner'];
        $this->option_report                = $array['option_report'];
        $this->social_bookmarks_enabled     = $array['social_bookmarks_enabled'];
        $this->ratings_enabled              = $array['ratings_enabled'];
        $this->reviews_mode                 = $array['reviews_mode'];
        $this->reviews_richtext_enabled     = $array['reviews_richtext_enabled'];
        $this->titles_template              = $array['titles_template'];
        $this->allow_to_edit_active_period  = $array['allow_to_edit_active_period'];
    }
    
    public function getType()
    {
    	$CI = &get_instance();
    	$CI->load->model('types', 'types_levels');
    	return $CI->types->getTypeById($this->type_id);
    }
    
    public function setPrice($price_value, $price_currency = null)
    {
    	$this->price_value = $price_value;
    	$this->price_currency = $price_currency;
    }
}
?>
<?php

namespace bootstrap\views{
    
    class view_select extends view{
        
        const LARGE = 'input-lg';
        const NORMAL = '';
        const SMALL = 'input-sm';
        
        public function __construct($name = null, $options = array(), $selected_value = null){
            parent::__construct('select');
            $this->add_class('form-control');
            if (!is_null($name)) $this->attr('name', $name);
            $this->add($options);
            if (!is_null($selected_value)) $this->selected_value = $selected_value;
        }
        
        /*
         * Properties
         */
        public function pget_disabled(){
            return $this->has_attr('disabled');
        }
        
        public function pset_disabled($value){
            if ($value === true){
                $this->attr('disabled', '');
            }else{
                $this->remove_attr('disabled');
            }
        }
        
        public function pget_selected_value(){
            $selected = $this->find("option[selected='selected']");
            if (count($selected->get()) > 0){
                $item = $selected->get(0);
                if ($item->has_attr('value')){
                    return $item->attr('value');
                }else{
                    return $item->text;
                }
            }
        }
        
        public function pset_selected_value($value){
            if (!is_null($value)){
                $options = $this->find('option');
                for($i = 0; $i < count($options->get()); $i++){
                    $option = $options->get($i);
                    $option->remove_attr('selected');
                    
                    if ($option->has_attr('value')){
                        if ($option->attr('value') == $value){
                            $option->attr('selected', '');
                        }
                    }elseif($option->text == $value){
                        $option->attr('selected', '');
                    }
                }
            }
        }
        
        public function pset_size($size){
            $this->remove_class(self::LARGE);
            $this->remove_class(self::SMALL);
            
            if ($size && $size != ""){
                $this->add_class($size);
            }
        }
        
        public function pget_size(){
            if ($this->has_class(self::LARGE)){
                return self::LARGE;
            }elseif($this->has_class(self::SMALL)){
                return self::SMALL;
            }else{
                return self::NORMAL;
            }
        }
        
        /*
         * Overrides
         */
        public function add(){
            $params = func_get_args();
            
            if (count($params) > 1){
                foreach($params as $param) $this->add($param);
            }elseif(count($params) == 1){
                if (is_array($params[0])){
                    if (is_assoc($params[0])){
                        foreach($params[0] as $key => $value){
                            if (is_array($value)){
                                $group = new html_optgroup(['label' => $key]);
                                if (is_assoc($value)){
                                    foreach($value as $item_label => $item_value){
                                        $group->add(new html_option($item_label, ['value' => $item_value]));
                                    }
                                }else{
                                    foreach($value as $val){
                                        $group->add(new html_option($val));
                                    }
                                }
                                parent::add($group);
                            }else{
                                parent::add(new html_option($value, array('value' => $key)));
                            }
                        }
                    }else{
                        foreach($params[0] as $value){
                            parent::add(new html_option($value));
                        }
                    }
                }elseif($params[0] instanceof html && in_array($params[0]->tag, array('option', 'optgroup'))){
                    parent::add($params[0]);
                }elseif(is_string($params[0])){
                    parent::add(new html_option($params[0]));
                }
            }
        }
    }
    
}

?>
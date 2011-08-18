<?php
class ThickboxHelper extends AppHelper {
 
    var $helpers = array('Javascript', 'Html');
   
    /**
     * Set properties – DOM ID, Height and Width, Type of thickbox window – inline or ajax
     *
     * @param array $options
     */
    function setProperties($options = array())
    {
        if(!isset($options['type']))
        {
            $options['type'] = 'inline';
        }
        $this->options = $options;
    }
   
    function setPreviewContent($content)
    {
        $this->options['previewContent'] = $content;
    }
 
    function setMainContent($content)
    {
        $this->options['mainContent'] = $content;
    }
   
    function reset()
    {
        $this->options = array();
    }
   
    function output()
    {
        extract($this->options);
        if($type=='inline')
        {
            $href = '#TB_inline?';
            $href .= '&inlineId='.$id;
        }
		elseif($type=='iframe')
		{
			$iframeUrl = $this->Html->url($iframeUrl);
			$href = $iframeUrl . "?KeepThis=true&TB_iframe=true&height=500&width=600";
		}
        elseif($type=='ajax')
        {
            $ajaxUrl = $this->Html->url($ajaxUrl);
            $href = $ajaxUrl.'?';
        }
               
        if(isset($height))
        {
            $href .= '&height='.$height;
        }
        if(isset($width))
        {
            $href .= '&width='.$width;
        }
       
       
        $output = '<a class="thickbox" title="'.$title.'" href="'.$href.'">'.$previewContent.'</a>';
       
        if($type=='inline')
        {
            $output .= '<div id="'.$id.'" style="display:none;">'.$mainContent.'</div>';
        }
       
        unset($this->options);
       
        return $output;
    }
   
    function beforeRender()
    {
        $out = $this->Html->css('/effects/css/thickbox.css').'<script src="'.$this->Html->url('/effects/js/thickbox-compressed.js').'"></script>';
        $view =& ClassRegistry::getObject('view');
        $view->addScript('thickbox', $out);
    }
 
}
?>
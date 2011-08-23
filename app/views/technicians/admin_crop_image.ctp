<h2 class="section">Crop your image below</h2>
<p>Click and drag a corner of the box to adjust the crop area.</p>
<?php 
if(isset($javascript)):  
        echo $javascript->link('jquery.imgareaselect.min.js'); 
endif; 
?>

<?php  
echo $form->create('Technician', array('action' => 'save_image?modal=true',"enctype" => "multipart/form-data"));     
echo $form->hidden('id'); 
echo $cropimage->createJavaScript($uploaded['imageWidth'],$uploaded['imageHeight'],151,151); 
echo $cropimage->createForm($uploaded["imagePath"], 151, 151); 
echo $form->submit('Done', array("id"=>"save_thumb")); 
echo $form->end();
?>
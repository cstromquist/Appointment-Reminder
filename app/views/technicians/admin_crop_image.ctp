<h2 class="section">Crop your image below</h2>
<?php 
if(isset($javascript)):  
        echo $javascript->link('jquery.imgareaselect.min.js'); 
endif; 
?>

<?php  
echo $form->create('Technician', array('action' => 'save_image?modal=true',"enctype" => "multipart/form-data"));     
echo $form->input('id'); 
echo $form->hidden('name');
echo $cropimage->createJavaScript($uploaded['imageWidth'],$uploaded['imageHeight'],151,151); 
echo $cropimage->createForm($uploaded["imagePath"], 151, 151); 
echo $form->submit('Done', array("id"=>"save_thumb")); 
echo $form->end();
?>
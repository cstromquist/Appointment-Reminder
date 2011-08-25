<?php

define('BASE_URL', $_SERVER['DOCUMENT_ROOT']);

define('NEWS_GOTHIC_BOLD_OBL',  BASE_URL . '/app/vendors/igen/fonts/NewsGothicStd-BoldOblique.ttf');

define('MYRIAD_BOLD', BASE_URL . '/app/vendors/igen/fonts/MyriadPro-Bold.ttf');

define('MISO', BASE_URL . '/app/vendors/igen/fonts/miso-regular.ttf');
define('MISO_BOLD', BASE_URL . '/app/vendors/igen/fonts/miso-bold.ttf');

define('HELVETICA_COND', BASE_URL . '/app/vendors/igen/fonts/Sansation_Regular.ttf');
define('HELVETICA_COND_OBL', BASE_URL . '/app/vendors/igen/fonts/Sansation_Bold.ttf');

define('BULLET',utf8_encode('&#8226;'));
//define('CHECKBOX',utf8_encode('&#8344;'));

define('CANVAS_WIDTH',600);
define('CANVAS_HEIGHT',960);

define('UPSELL_LIST_COLS', 1);

function imagettfmultilinetext($image, $size, $angle, $x, $y, $color, $fontfile,  $text, $spacing=1)
{

$lines=explode("\n",$text);
for($i=0; $i< count($lines); $i++)
    {
    $newY=$y+($i * $size * $spacing);
    imagettftext($image, $size, $angle, $x, $newY, $color, $fontfile,  $lines[$i]);
    }
return null;
}

function imagettfmultilinecheckboxtext($image, $size, $angle, $x, $y, $color, $fontfile,  $text, $spacing=1)
{

//adds a checkbox to lines beginning with '[]' to allow for wrapping

$color_checkbox=explode(',',str_replace(' ','',COLOR_CHECKBOX));

$border_color = imagecolorallocate($image, $color_checkbox[0],$color_checkbox[1],$color_checkbox[2]);
$inside_color = imagecolorallocate($image, 255,255,255);

$lines=explode("\n",$text);
for($i=0; $i< count($lines); $i++)
    {
    $newY=$y+($i * $size * $spacing);
    
    //draw checkbox
    //2 px up from baseline, 6x6 px with 1px black border
	
	if(substr($lines[$i],0,2) == '[]'){
		//draw black border
		imagefilledrectangle($image, $x, $newY-10, $x+8, $newY-2, $border_color);
		//draw inside
		imagefilledrectangle($image, $x+1, $newY-9, $x+7, $newY-3,  $inside_color);
		
		$lines[$i] = substr($lines[$i],2);
    }
    
    //draw text offset by 16px
    imagettftext($image, $size, $angle, $x+16, $newY, $color, $fontfile,  $lines[$i]);
    }
return null;
}

class imageGenerator{

private $theme;
private $docroot;
private $upsellListCols;

function imageGenerator(){

	$this->docroot = BASE_URL . '/app/vendors/';
	$this->jpegQuality = 90;
	$this->outputPath = BASE_URL.'/app/webroot/img/uploads/reminders/';
	$this->fileName = md5(date('ymdhis').rand(1, 999)).'.jpg';

}

function setTheme($theme){
	//folder name of theme in the /themes/ directory
	$this->theme = $theme;
	$this->loadTheme();
}

function getTheme(){
	return $this->theme;
}

function loadTheme(){
	include 'themes/'.$this->format.'/'.$this->theme.'/config.php';
	//load theme file from disk? YAML?
}

function setPhotoPath($path){
	$this->photoPath = $path;
}

function setLogoPath($path){
	$this->logoPath = $path;
}

function setFormat($format){
	//TECH or GROUP
	$this->format = $format;
}

function setTechName($txt){
	$this->techName = $txt;
}

function setTechBio($txt){
	$this->techBio = $txt;
}

function setCustomerGreeting($txt){
	$this->customerGreeting = $txt;
}

function setCustomerMessage($txt){
	$this->customerMessage = $txt;
}

function setServiceDate($txt){
	$this->serviceDate = $txt;
}

function setServiceTime($txt){
	$this->serviceTime = $txt;
}

function setBenefitsTitle($txt){
	$this->benefitsTitle = $txt;
}

function setBenefitsList($arr){
	$this->benefitsList = $arr;
}

function setUpsellTitle($txt){
	$this->upsellTitle = $txt;
}

function setUpsellSubTitle($txt){
	$this->upsellSubTitle = $txt;
}

function setUpsellList($arr){
	$this->upsellList = $arr;
}

function setOtherServicesTitle($txt){
	$this->otherServicesTitle = $txt;
}

function setOtherServicesList($list){
	$this->otherServicesList = $list;
}

function setCancelMessage($txt){
	$this->cancelMessage = $txt;
}

function setCopyright($txt){
	$this->copyright = $txt;
}

function setMoreInfoMessage($txt){
	$this->moreInfoMessage = $txt;
}

function setWebsite($txt){
	$this->website = $txt;
}

function setPhone($txt){
	$this->phone = $txt;
}

function setUpsellListCols($num){
	$this->upsellListCols = $num;
}

function setCreditCards($arr){
	//VISA = VISA
	//MAST = MASTERCARD
	//DISC = DISCOVER
	//AMEX = AMERICAN EXPRESS
	$this->creditCards = $arr;
}

function setJpegQuality($q){
	$this->jpegQuality = $q;
}

function setReturnMode($m){
	//INLINE = returns data directly to browser
	//FILE = writes a file and returns path
	$this->returnMode = $m;
}

function setOutputPath($p){
	$this->outputPath = $p;
}

function setFileName($f){
	$this->fileName = $f;
}

function center($width){
	return round((CANVAS_WIDTH - $width)/2,0);
}

function makeTextBlock($text, $fontfile, $fontsize, $width) 
{    
    $words = explode(' ', $text); 
    $lines = array($words[0]); 
    $currentLine = 0; 
    for($i = 1; $i < count($words); $i++) 
    { 
        $lineSize = imagettfbbox($fontsize, 0, $fontfile, $lines[$currentLine] . ' ' . $words[$i]); 
        if($lineSize[2] - $lineSize[0] < $width) 
        { 
            $lines[$currentLine] .= ' ' . $words[$i]; 
        } 
        else 
        { 
            $currentLine++; 
            $lines[$currentLine] = $words[$i]; 
        } 
    } 
    
    return implode("\n", $lines); 
}

function parse($csv){
	return explode(',',str_replace(' ','',$csv));
}

function generateImage(){
	
	//load BG
	$im = imagecreatefrompng($this->docroot.'igen/themes/'.$this->format.'/'.$this->theme.'/background.png');
	
	$color_top_title = $this->parse(COLOR_TOP_TITLE);
	$color_top_text =  $this->parse(COLOR_TOP_TEXT);
	$color_title =  $this->parse(COLOR_TITLE);
	$color_text =  $this->parse(COLOR_TEXT);
	$color_copyright = $this->parse(COLOR_COPYRIGHT);
	$color_cancel = $this->parse(COLOR_CANCEL);
	$color_list = $this->parse(COLOR_LIST);
	
	//set-up colors
	$top_title_color = imagecolorallocate($im, $color_top_title[0], $color_top_title[1], $color_top_title[2]);
	$top_text_color = imagecolorallocate($im, $color_top_text[0], $color_top_text[1], $color_top_text[2]);
	
	$title_color = imagecolorallocate($im, $color_title[0], $color_title[1], $color_title[2]);
	$text_color = imagecolorallocate($im, $color_text[0], $color_text[1], $color_text[2]);

	$copyright_color = imagecolorallocate($im, $color_copyright[0], $color_copyright[1], $color_copyright[2]);
	$cancel_color = imagecolorallocate($im, $color_cancel[0], $color_cancel[1], $color_cancel[2]);

	$list_color = imagecolorallocate($im, $color_list[0], $color_list[1], $color_list[2]);

	$border_color = imagecolorallocate($im, 0,0,0);

	//place top box
	$tb = imagecreatefrompng($this->docroot.'igen/assets/common/top-box.png');
	imagecopy ( $im, $tb, TOP_BOX_X, TOP_BOX_Y, 0, 0, 545, 158 );
	imagedestroy($tb);
	
	//top text

	//place photo
	$photo_box = $this->parse(PHOTO_BOX);

	//draw black border
	imagefilledrectangle($im, $photo_box[0]-2, $photo_box[1]-2, $photo_box[0]+$photo_box[2]+2, $photo_box[1]+$photo_box[3]+2, $border_color);
	
	$pbdim = getimagesize($this->photoPath);
	
	$pb = imagecreatefromjpeg($this->photoPath);
	imagecopyresampled ( $im, $pb, $photo_box[0], $photo_box[1], 0, 0, $photo_box[2], $photo_box[3],  $pbdim[0], $pbdim[1] );
	imagedestroy($pb);
	
	//add nexstar logo
	$nl = imagecreatefrompng($this->docroot.'igen/assets/common/nexstar-logo.png');
	imagecopy ( $im, $nl, NEXSTAR_LOGO_X, NEXSTAR_LOGO_Y, 0, 0, 75, 52 );
	imagedestroy($nl);

	//add company logo
	if(REVERSE_LOGO){
		$logoPath = substr($this->logoPath,0,-4).'_rev.png';
	}else{
		$logoPath = $this->logoPath;
	}
	
	// get the logo height/width
	list($width, $height, $type, $attr) = getimagesize($logoPath);
	
	define('LOGO_WIDTH', $width);
	define('LOGO_HEIGHT', $height);

	// now figure out our Y axis based on the height of the logo
	if($height < 100) {
		$logo_block_y = LOGO_BLOCK_Y + (100 - $height);
	} else {
		$logo_block_y = LOGO_BLOCK_Y;
	}

	$lb = imagecreatefromjpeg($logoPath);
	imagecopy ( $im, $lb, LOGO_BLOCK_CENTER - (LOGO_WIDTH/2), $logo_block_y, 0, 0, LOGO_WIDTH, LOGO_HEIGHT );
	imagedestroy($lb);
	
	//add items under logo
	
	//phone
	$temp = imagettfbbox(18, 0, NEWS_GOTHIC_BOLD_OBL, $this->phone);
	imagettftext($im, 18, 0, LOGO_BLOCK_CENTER - (($temp[2] - $temp[0])/2)-5, LOGO_BLOCK_Y + 125, $text_color, NEWS_GOTHIC_BOLD_OBL, $this->phone);

	//more info
	$temp = imagettfbbox(11, 0, MISO, $this->moreInfoMessage);
	imagettftext($im, 11, 0, LOGO_BLOCK_CENTER - (($temp[2] - $temp[0])/2), LOGO_BLOCK_Y + 140, $text_color, MISO, $this->moreInfoMessage);

	//website
	$temp = imagettfbbox(21, 0, MISO, $this->website);
	imagettftext($im, 21, 0, LOGO_BLOCK_CENTER - (($temp[2] - $temp[0])/2), LOGO_BLOCK_Y + 164, $text_color, MISO_BOLD, $this->website);

	//cancel
	$size= 10.8;
	$cancelMessage = explode("\n",$this->makeTextBlock($this->cancelMessage, MISO_BOLD, $size, 270));
	
	$temp = imagettfbbox($size, 0, MISO, $cancelMessage[0]);
	imagettftext($im, $size, 0, LOGO_BLOCK_CENTER - (($temp[2] - $temp[0])/2), LOGO_BLOCK_Y + 182, $cancel_color, MISO_BOLD, $cancelMessage[0]);
	$temp = imagettfbbox($size, 0, MISO, $cancelMessage[1]);
	imagettftext($im, $size, 0, LOGO_BLOCK_CENTER - (($temp[2] - $temp[0])/2), LOGO_BLOCK_Y + 197, $cancel_color, MISO_BOLD, $cancelMessage[1]);
	
	//add credit cards
	
	$cc_width = sizeof($this->creditCards) * 36; // each is 36px wide
	
	for($x=0;$x<sizeof($this->creditCards);$x++){
	
	//echo $x .' '. $this->creditCards[$x];
	
	//open card image
	$cc = imagecreatefrompng($this->docroot.'igen/assets/common/cc-'.$this->creditCards[$x].'.png');
	
	$pos = LOGO_BLOCK_CENTER - ($cc_width/2) + ($x * 36);
	
	//place card
	imagecopy ( $im, $cc, $pos, LOGO_BLOCK_Y + 210, 0, 0, 36, 24 );
	imagedestroy($cc);
	
	}
	

	//add copyright
	imagettftext($im, 7, 0, COPYRIGHT_X, COPYRIGHT_Y, $copyright_color, MYRIAD_BOLD, $this->copyright);

	$top_box_text_width = 510;
	
	if($this->format == 'tech'){

		//add greeting
		imagettftext($im, 14, 0, TOP_BOX_X + 18, TOP_BOX_Y + 24, $top_title_color, NEWS_GOTHIC_BOLD_OBL, $this->customerGreeting);
	
		//add greeting line 1 & 2
		$customerMessageBlock = $this->makeTextBlock($this->customerMessage, NEWS_GOTHIC_BOLD_OBL, 14, $top_box_text_width);
		imagettftext($im, 14, 0, TOP_BOX_X + 18, TOP_BOX_Y + 48, $top_title_color, NEWS_GOTHIC_BOLD_OBL, $customerMessageBlock);
	
		imagettftext($im, 18, 0, TOP_BOX_X + 66, TOP_BOX_Y + 100, $top_text_color, HELVETICA_COND, 'Service Date:');
		imagettftext($im, 18, 0, TOP_BOX_X + 66, TOP_BOX_Y + 124, $top_text_color, HELVETICA_COND, 'Service Time:');	
		imagettftext($im, 18, 0, TOP_BOX_X + 66, TOP_BOX_Y + 148, $top_text_color, HELVETICA_COND, 'Your Technician:');	
	
		imagettftext($im, 18, 0, TOP_BOX_X + 284, TOP_BOX_Y + 100, $top_text_color, HELVETICA_COND, $this->serviceDate);
		imagettftext($im, 18, 0, TOP_BOX_X + 284, TOP_BOX_Y + 124, $top_text_color, HELVETICA_COND, $this->serviceTime);	
		imagettftext($im, 18, 0, TOP_BOX_X + 284, TOP_BOX_Y + 148, $top_text_color, HELVETICA_COND, $this->techName);	
	
		//add tech bio
		$bio = $this->makeTextBlock($this->techBio, MISO, 11, 330); 
		imagettfmultilinetext($im, 11, 0, LEFT_MARGIN, TOP_MARGIN + 10, $text_color, MISO, $bio, 1.5);

		//add tech name under photo
		//200
		$temp = imagettfbbox(19, 0, MISO_BOLD, $this->techName);
		$pos = $photo_box[0] + (($photo_box[2]/2) - ($temp[2] - $temp[0])/2);
		imagettftext($im, 19, 0, $pos, TOP_MARGIN + 200, $text_color, MISO_BOLD, $this->techName);
		
	}else{

		//add greeting
		imagettftext($im, 14, 0, TOP_BOX_X + 18, TOP_BOX_Y + 32, $top_title_color, NEWS_GOTHIC_BOLD_OBL, $this->customerGreeting);
	
		//add greeting line 1 & 2
		$customerMessageBlock = $this->makeTextBlock($this->customerMessage, NEWS_GOTHIC_BOLD_OBL, 14, $top_box_text_width);
		imagettftext($im, 14, 0, TOP_BOX_X + 18, TOP_BOX_Y + 56, $top_title_color, NEWS_GOTHIC_BOLD_OBL, $customerMessageBlock);
		
		//add service date/time
		imagettftext($im, 18, 0, TOP_BOX_X + 66, TOP_BOX_Y + 116, $top_text_color, HELVETICA_COND, 'Service Date:');
		imagettftext($im, 18, 0, TOP_BOX_X + 66, TOP_BOX_Y + 140, $top_text_color, HELVETICA_COND, 'Service Time:');	
	
		imagettftext($im, 18, 0, TOP_BOX_X + 284, TOP_BOX_Y + 116, $top_text_color, HELVETICA_COND, $this->serviceDate);
		imagettftext($im, 18, 0, TOP_BOX_X + 284, TOP_BOX_Y + 140, $top_text_color, HELVETICA_COND, $this->serviceTime);	
	
	}

		//add benefits title
		imagettftext($im, 24, 0, BENEFITS_X, BENEFITS_Y, $title_color, MISO_BOLD, $this->benefitsTitle);
	
		//add benefits list
		$benefitsListBlock = "";
		foreach($this->benefitsList as $item){
		
			$benefitsListBlock .= BULLET .' '. str_replace("\n", "\n   ", $this->makeTextBlock($item, MISO, 11, BENEFITS_WIDTH)) . "\n";
		
		}
		imagettfmultilinetext($im, 11, 0, BENEFITS_X, BENEFITS_Y + 24, $text_color, MISO, $benefitsListBlock, 1.5);
		
		//add upsell title
		$upsell_size = imagettfbbox(36, 0, MISO_BOLD, $this->upsellTitle);
		imagettftext($im, 36, 0, $this->center($upsell_size[2] - $upsell_size[0]), UPSELL_Y, $title_color, MISO_BOLD, $this->upsellTitle);

		//add upsell subtitle
		$subtitle_size = imagettfbbox(20, 0, MISO_BOLD, $this->upsellSubTitle);
		imagettftext($im, 20, 0, $this->center($subtitle_size[2] - $subtitle_size[0]), UPSELL_Y + 32, $text_color, MISO_BOLD, $this->upsellSubTitle);

		//add upsell list
		
		//if 2 cols, split into 2 lists and render seperately
		if(defined(UPSELL_LIST_COLS) || $this->upsellListCols == 2){

		$num = sizeof($this->upsellList);
		$leftList = floor($num/2);
		
		$upsellListLeft=array_slice($this->upsellList,0,$leftList);
		$upsellListRight=array_slice($this->upsellList,$leftList);
		$upsellList = '';
			foreach($upsellListLeft as $item){
				$upsellList .= '[]'.$this->makeTextBlock($item, MISO_BOLD, 11, UPSELL_LIST_WIDTH)."\n";
			}
			
			imagettfmultilinecheckboxtext($im, 11, 0, UPSELL_LIST_X, UPSELL_Y + 60, $list_color, MISO_BOLD, $upsellList, 2.0);

			$upsellList='';

			foreach($upsellListRight as $item){
				$upsellList .= '[]'.$this->makeTextBlock($item, MISO_BOLD, 11, UPSELL_LIST_WIDTH)."\n";
			}
			
			imagettfmultilinecheckboxtext($im, 11, 0, UPSELL_LIST_X + UPSELL_LIST_WIDTH + UPSELL_LIST_GUTTER, UPSELL_Y + 60, $list_color, MISO_BOLD, $upsellList, 2.0);
		
		}else{		
			
			foreach($this->upsellList as $item){
				$upsellList .= '[]'.$this->makeTextBlock($item, MISO_BOLD, 11, UPSELL_LIST_WIDTH)."\n";
			}
			
			imagettfmultilinecheckboxtext($im, 11, 0, UPSELL_LIST_X, UPSELL_Y + 60, $list_color, MISO_BOLD, $upsellList, 1.4);

		}

		//add other services title
		$other_size = imagettfbbox(18, 0, MISO_BOLD, $this->otherServicesTitle);
		imagettftext($im, 18, 0, $this->center($other_size[2] - $other_size[0]), SERVICES_Y, $title_color, MISO_BOLD, $this->otherServicesTitle);

		//add other services list
		$servicesList = '';
		foreach($this->otherServicesList as $item){
			$servicesList .= '[]'.$this->makeTextBlock($item, MISO_BOLD, 11, SERVICES_LIST_WIDTH)."\n";
		}
		imagettfmultilinecheckboxtext($im, 11, 0, SERVICES_LIST_X, SERVICES_Y + 22, $list_color, MISO_BOLD,  $servicesList, 1.4);		

	if($this->returnMode == 'FILE') {
		$path = $this->outputPath.$this->fileName;

		imagejpeg($im,$path,$this->jpegQuality);

		return array($path,$this->fileName);
	
	} else{
		// Output to browser as JPEG
		header('Content-Type: image/jpeg');
		imagejpeg($im,null,$this->jpegQuality);
		
	}

	imagedestroy($im);

}

}

<?php
namespace BZ_RCP\Questions;
class BzQuestionshtml {
    
	/**
	 * Render Question's HTML
	 *
	 * @return Void
	 */
	public function render_html($qid,$type,$options,$title) {
		
		$quesfield = '';
		
		if( $type == 'mcq') {
			
			if($options != '') {
					$cs_choices = unserialize($options);
					
					$quesfield .=  '<div class="form-group">';
					$quesfield .=  '<label for="bzrcpRadio_'.$qid.'">'.stripslashes($title).'</label>';
					$cnt=0;
					if(isset($cs_choices) && !empty($cs_choices)) {
						foreach($cs_choices  as $choicekey => $choiceval) {
								$quesfield .= '<input type="radio" name="bzrcpRadio_'.$qid.'" class="form-control" data-url="'.$choiceval['url'].'" data-relqid="'.$choiceval['rel_ques'].'" value="'.stripslashes($choicekey).'"> <span>'.stripslashes($choicekey).'</span><br>';
						}
						$cnt++;
						}
					$quesfield .= '<br><span class="requiredField">*Required</span></div>';
				}
		
		}  else if ($type == 'true_false') {	
			
				if($options != '') {
					$cs_choices = unserialize($options);
						if(isset($cs_choices) && !empty($cs_choices)) {
							foreach($cs_choices  as $choicekey => $choiceval) {
								$realtedQid = $choiceval['rel_ques'];
								$redirectUrl = $choiceval['url'];
							}
						}
				}
				if(isset($realtedQid) ) { $relID= $realtedQid; } else { $relID = ''; } 
				if(isset($redirectUrl) ) { $rUrl= $redirectUrl; } else { $rUrl = ''; } 
				
				$quesfield .=  '<div class="form-group">';
				$quesfield .=  '<label for="bzrcptruefalse_'.$qid.'">'.stripslashes($title).'</label>';
				$quesfield .= '<input type="checkbox" data-url="'.$rUrl.'" data-relqid="'.$relID.'" name="bzrcptruefalse_'.$qid.'" class="form-control" value="1">';
				$quesfield .= '<span class="requiredField">*Required</span></div>';
				
		}  else if ($type == 'message_box') {	
				
				if($options != '') {
					$cs_choices = unserialize($options);
						if(isset($cs_choices) && !empty($cs_choices)) {
							foreach($cs_choices  as $choicekey => $choiceval) {
								$realtedQid = $choiceval['rel_ques'];
								$redirectUrl = $choiceval['url'];
							}
						}
				}
				if(isset($realtedQid) ) { $relID= $realtedQid; } else { $relID = ''; } 
				if(isset($redirectUrl) ) { $rUrl= $redirectUrl; } else { $rUrl = ''; } 
				
				$quesfield .=  '<div class="form-group">';
				$quesfield .=  '<label for="bzrcpmsgbox_'.$qid.'">'.stripslashes($title).'</label>';
				$quesfield .= '<textarea name="bzrcpmsgbox_'.$qid.'" data-url="'.$rUrl.'" data-relqid="'.$relID.'" class="form-control" ></textarea>';
				$quesfield .= '<span class="requiredField">*Required</span></div>';
				
		} else if ($type == 'star_rating') {	
	
			if($options != '') {
				$cs_choices = unserialize($options);
					if(isset($cs_choices) && !empty($cs_choices)) {
						foreach($cs_choices  as $choicekey => $choiceval) {
							$realtedQid = $choiceval['rel_ques'];
							$redirectUrl = $choiceval['url'];
						}
					}
			}
			if(isset($realtedQid) ) { $relID= $realtedQid; } else { $relID = ''; } 
			if(isset($redirectUrl) ) { $rUrl= $redirectUrl; } else { $rUrl = ''; } 
					
			$quesfield .=  '<div class="form-group">';
			$quesfield .=  '<label for="bzrcptruefalse">'.stripslashes($title).'</label>';	
			$quesfield .=  	'<div class="rating"><span id="selectedRating" style="display:none;"></span>
				  <label>
					<input type="radio" name="stars_'.$qid.'" data-url="'.$rUrl.'" data-relqid="'.$relID.'" value="1" />
					<span class="icon">★</span>
				  </label>
				  <label>
					<input type="radio" name="stars_'.$qid.'"  data-url="'.$rUrl.'" data-relqid="'.$relID.'" value="2" />
					<span class="icon">★</span>
					<span class="icon">★</span>
				  </label>
				  <label>
					<input type="radio" name="stars_'.$qid.'" data-url="'.$rUrl.'" data-relqid="'.$relID.'" value="3" />
					<span class="icon">★</span>
					<span class="icon">★</span>
					<span class="icon">★</span>   
				  </label>
				  <label>
					<input type="radio" name="stars_'.$qid.'" data-url="'.$rUrl.'" data-relqid="'.$relID.'" value="4" />
					<span class="icon">★</span>
					<span class="icon">★</span>
					<span class="icon">★</span>
					<span class="icon">★</span>
				  </label>
				  <label>
					<input type="radio" name="stars_'.$qid.'" data-url="'.$rUrl.'" data-relqid="'.$relID.'" value="5" />
					<span class="icon">★</span>
					<span class="icon">★</span>
					<span class="icon">★</span>
					<span class="icon">★</span>
					<span class="icon">★</span>
				  </label>
				</div>';
			 $quesfield .= '<span class="requiredField">*Required</span></div>';	
		}
		return $quesfield;
	}
}
?>

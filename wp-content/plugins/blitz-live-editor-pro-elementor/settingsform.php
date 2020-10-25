<?php 
if(isset($_POST['floating_submit'])){
	update_option('floatingSetting',$_POST['floating']);
}
$floating = get_option('floatingSetting');
$backgroundColorPT = '';
$backgroundColor = '';
$color = '';
$hidemenu = '';
if(isset($floating['backgroundColorPT'])){
	$backgroundColorPT = $floating['backgroundColorPT'];
}
if(isset($floating['backgroundColor'])){
	$backgroundColor = $floating['backgroundColor'];
}
if(isset($floating['color'])){
	$color = $floating['color'];
}
if(isset($floating['btnColor'])){
	$color2 = $floating['btnColor'];
}
if(isset($floating['btnColorBg'])){
	$color3 = $floating['btnColorBg'];
}
if(isset($floating['bgimg'])){
	$bgimg = $floating['bgimg'];
}
if(isset($floating['iconChange'])){
	$iconChange = $floating['iconChange'];
}
if(isset($floating['hidemenu'])){
	$hidemenu = $floating['hidemenu'];
}
?>
<div class="wrap-content">
	<h2>Live Editor PRO Configuration</h2>
	<div id="floatingBarSettings" class="tab">
		<ul>
			<li rel="colorSetting" class="active">Settings</li>
			<li rel="wrap" >License</li>
		</ul>
	</div>
	<div id="colorSetting" class="tabBox active">
		<form action="" method="post">
			<table>
				<tr>
					<th><label for="floating_backgroundColor">Background Color</label></th>
					<td class="bgcolor">
						<div id="colorSelector1" class="gradientSelector vertical" rel="<?php echo $backgroundColorPT; ?>"></div>
						<input type="hidden" name="floating[backgroundColor]" id="floating_backgroundColor" value="<?php echo $backgroundColor; ?>" />
						<div class="preview-gradientSelector" style="background:<?php echo $backgroundColor; ?> " ></div>
						<input type="hidden" name="floating[backgroundColorPT]" class="crtlpt" value="<?php echo $backgroundColorPT; ?>" />
					</td>
				</tr>
				<tr>
					<th><label for="floating_fontColor">Text Color</label></th>
					<td class="bgcolor">
						<div id="color1" class="colorSelector" rel="<?php echo $color; ?>" >
							<div style="background-color: <?php echo $color; ?>"></div>
						</div>
						<input type="hidden" name="floating[color]" id="floating_fontColor"  value="<?php echo $color; ?>" /> 
					</td>
				</tr>
				<tr>
					<th><label for="floating_btnColor">Button Text Color</label></th>
					<td class="bgcolor">
						<div id="color2" class="colorSelector" rel="<?php echo $color2; ?>" >
							<div style="background-color: <?php echo $color2; ?>"></div>
						</div>
						<input type="hidden" name="floating[btnColor]" id="floating_btnColor"  value="<?php echo $color2; ?>" /> 
					</td>
				</tr>
				<tr>
					<th><label for="floating_btnColorBg">Button Background Color</label></th>
					<td class="bgcolor">
						<div id="color3" class="colorSelector" rel="<?php echo $color3; ?>" >
							<div style="background-color: <?php echo $color3; ?>"></div>
						</div>
						<input type="hidden" name="floating[btnColorBg]" id="floating_btnColorBg"  value="<?php echo $color3; ?>" /> 
					</td>
				</tr>
				<tr>
					<th><label for="floating_bgimg">Background Icon</label></th>
					<td class="bgcolor">
						<select name="floating[bgimg]" id="floating_bgimg" class="basic-multiple-select">
						<?php 
							$iconslist = ElementorDashIconList::get_icons();
							if(is_array($iconslist)){
								foreach($iconslist as $key=>$value){
									$selected = '';
									if($value == $bgimg){$selected = 'selected';}
									echo '<option value="'.$value.'"  data-id="selectIcons" '.$selected.'>'.$value.'</option>';
								}
							}
						?>
						</select> 
					</td>
				</tr>
				<tr>
					<th><label for="floating_hidemenu">Hide Elementor Editor</label></th>
					<td class="bgcolor">
						<select name="floating[hidemenu]" id="floating_hidemenu" ><option <?php if($hidemenu =='0') {echo 'selected ';}?> value="0">No</option><option <?php if($hidemenu =='1') {echo 'selected';}?> value="1">Yes</option></select> 
					</td>
				</tr>
				<tr style="display:none;">
					<th><label for="floating_iconChange">Icon Selector</label></th>
					<td class="bgcolor">
						<select name="floating[iconChange]" id="floating_iconChange" class="basic-multiple-select">
						<?php 
							$iconslist = ElementorDashIconList::get_icons();
							if(is_array($iconslist)){
								foreach($iconslist as $key=>$value){
									$selected = '';
									if($value == $iconChange){$selected = 'selected';}
									echo '<option value="'.$value.'"   data-id="selectIcons" '.$selected.'>'.$value.'</option>';
								}
							}
						?>
						</select> 
					</td>
				</tr>
				<tr>
					<th>
					</th>
					<td class="bgcolor"> 
						<p class="submit">
							<input type="submit" name="floating_submit" value="Save" class="button">
						</p>
					</td>
				</tr>
				
			</table>
		</form>

	</div>
</div>

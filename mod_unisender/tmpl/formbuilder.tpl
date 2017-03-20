<div class="wrap">
	<input type="hidden" id="id-formaction" value="<?php echo $formAction;?>"/>

	<h3><?php _e("Form fields","unisender");?></h3>
	<small><?php _e("You can drag and drop every field to achieve needed order","unisender");?></small>
	<fieldset id="id-fieldsinfocont" style="display: none;">
		<?php
            for ( $i = 0, $len = count($fields); $i < $len; $i++ ) { ?>
		<input type="hidden" name="jform[params][field_<?php echo $i;?>_name]" value="<?php echo $fields[$i]['name'];?>"
		       rel="<?php echo $fields[$i]['name'];?>"/>
		<input type="hidden" name="jform[params][field_<?php echo $i;?>_title]"
		       value="<?php echo $fields[$i]['title'];?>" rel="<?php echo $fields[$i]['name'];?>"/>
		<input type="hidden" name="jform[params][field_<?php echo $i;?>_mand]" value="1"
		       rel="<?php echo $fields[$i]['name'];?>" class="mand"/>
		<?php
            }
        ?>
	</fieldset>

	<ul class="fieldsortable" id="id-forfields">
		<?php
        for ( $i = 0, $len = count($fields); $i < $len; $i++ ) {
                $isMail = $fields[$i]['name'] == "email";
        ?>
		<li class="item" rel="<?php echo $fields[$i]['name'];?>">
			<?php if ( !$isMail ) { ?>
			<span class="rmbtm"><button rel="<?php echo $fields[$i]['name'];?>">х</button></span>
			<?php } ?>
			        <span class="mandchk"><input type="checkbox" <?php
		            if ( $isMail ) { echo ' disabled="disabled"'; }
		            ?> rel="<?php echo $fields[$i]['name'];?>
				        " <?php if($fields[$i]['mand']){echo 'checked="checked"';}?>/><sup>*</sup></span>
			<span class="titlename"><span class="title"><?php echo $fields[$i]['title'];?></span><br/><span
						class="name">(<?php echo $fields[$i]['name'];?>)</span></span>
		</li>
		<?php
            }
        ?>
	</ul>
	<p>* - <?php _e("Mark field as mandatory","unisender");?></p>

	<p>
		<button class="button-secondary action" id="id-addnewfield"><?php _e('Add field',"unisender");?></button>
	</p>
</div>

<div id="id-addfielddlg">
	<div class="wr1">
		<dl>
			<dt><?php _e('Field title',"unisender");?></dt>
			<dd><input type="text" id="id-newfieldtitle" class="regular-text"/></dd>
			<dt><?php _e('Field name',"unisender");?></dt>
			<dd><input type="text" id="id-newfieldname" class="regular-text"/></dd>
			<p id="id-dlgnote"></p>
		</dl>
	</div>
</div>

<?php
	function pgv_admin_dashboard(){
?>
		<div class="pgv-dashboard-box">
			<p>افزونه حرفه ای عضویت ویژه</p>
			<p>طراحی  و برنامه نویسی <a href="http://www.pgsavis.com">سید جمال قاسمی</a></p>
            <p><a href="http://www.pgsavis.com">پدیده گستر ساویس</a></p>
		</div>
<?php
}
function pgv_vipusers_page(){
	$userlist=GetVipUserList();
?>
	<div class="wrap">
    	<h3><?php echo __('VIP Users','PG-AdsManager') ?></h3>
        <div class="pga_panel">
          <table class="widefat">
            <tr>
                <th><?php echo __('User Id','PG-VIP') ?></th>
                <th><?php echo __('User Name','PG-VIP') ?></th>
                <th><?php echo __('Start Date','PG-VIP') ?></th>
                <th><?php echo __('Expire Date','PG-VIP') ?></th>
            </tr>
            <?php if(count($userlist)): ?>
                <?php foreach ($userlist as $vipuser): ?>
            <tr>
                <td><?php echo $vipuser->user_ID; ?></td>
                <td><?php echo GetUserNameByID($vipuser->user_ID); ?></td>
                <td><?php echo $vipuser->start_date; ?></td>
                <?php $Rdays=Remainingdays(PGVSendTimeStamp($vipuser->start_date),$vipuser->expire_date) ?>
                <td><?php echo $Rdays['days']; ?></td>
            </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
        </div>
<?php    	
}
function pgv_protectedfiles_page(){
	$filelist=GetVipFileList();
?>
<div class="wrap">
    <h3><?php echo __('VIP Files','PG-AdsManager') ?></h3>
    <div class="pga_panel">
    	<div id="pga_loader">لطفا صبر کنید ...</div>
          	<table class="widefat">
            	<tr>
                	<th><?php echo __('ID','PG-VIP') ?></th>
                	<th><?php echo __('File Name','PG-VIP') ?></th>
                	<th><?php echo __('Encrypted Name','PG-VIP') ?></th>
                	<th><?php echo __('Upload Date','PG-VIP') ?></th>
                    <th><?php echo __('Short Code','PG-VIP') ?></th>
            	</tr>
            <?php if(count($filelist)): ?>
                <?php foreach ($filelist as $file): ?>
            <tr>
                <td><?php echo $file->ID; ?></td>
                <td><?php echo $file->file_name; ?></td>
                <td><?php echo $file->encypted_name; ?></td>
                <td><?php echo $file->upload_date; ?></td>
                <td><?php echo '[pgvdlink id="'.$file->ID. '"]'; ?></td>

            </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
        </div>
    <div class="pgv-dashboard-files">
		<form action="" method="post" enctype="multipart/form-data">
			<label for="file"><?php echo __('Choose File:','PG-VIP') ?></label>
			<input type="file" name="file" id="file"><br>
			<input type="submit" name="submit" value="<?php echo __('Upload File','PG-VIP') ?>">
		</form>
	</div>
<?php

if (isset($_POST["submit"])){
		$allowedExts = array("pdf");
		$extension =strtolower(end(explode(".", $_FILES["file"]["name"])));
		if (($_FILES["file"]["type"] == "application/pdf")
		&& ($_FILES["file"]["size"] < 5242880)
		&& in_array($extension, $allowedExts)){
  			if ($_FILES["file"]["error"] > 0){
    			echo __('Return Code','PG-VIP') . $_FILES["file"]["error"] . "<br>";
    		}
  			else{
    			/*echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    			echo "Type: " . $_FILES["file"]["type"] . "<br>";
    			echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
    			echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";*/
    			if (file_exists(pgv_upload_DIR . $_FILES["file"]["name"])){
      				Alert( $_FILES["file"]["name"] . __(' already exists.','PG-VIP'));
      			}
    			else{
					if (!is_dir(pgv_upload_DIR)) {
    					mkdir(pgv_upload_DIR);         
					}
					$pgv_encrypt = new av_ecrypt;
      				move_uploaded_file($_FILES["file"]["tmp_name"],pgv_upload_DIR . $_FILES["file"]["name"]);
					$FilesData=array("file_name"=>$_FILES["file"]["name"],
   			 		"encypted_name"=>$pgv_encrypt->en($_FILES["file"]["name"]),
			 		"file_size"=>$_FILES["file"]["size"],
			 		"file_type"=>$extension,
			 		"upload_date"=>PGVgetcurrentdate(),
			 		);
					AddFilesToDbase($FilesData);
      				Alert( __('Stored in:','PG-VIP') .pgv_upload_DIR . $_FILES["file"]["name"]);
      			}
    		}
	}
	else{
  		echo "Invalid file";
  	}
	
}

}
function pgv_paymentslist(){
	?>
			<div class="meta-box-sortables">
				<div class="postbox">
					<div class="handlediv">
						<br />
					</div>
					<h3 class="hndle"><span> تراکنش های تمام شده </span></h3>
					<div class="inside">
						<?php jahanpay_get_payments(1)
						?>
					</div>
				</div>
			</div>
            <div class="meta-box-sortables">
				<div class="postbox">
					<div class="handlediv">
						<br />
					</div>
					<h3 class="hndle"><span> تراکنش های ناموفق </span></h3>
					<div class="inside">
						<?php jahanpay_get_payments(0)
						?>
					</div>
				</div>
			</div>
            <?php

}
function pgv_Settings_page(){
		global $pgv_plugin_options;
	global $pgv_shortname;
	if (isset($_POST["Savesubmit"])){
		foreach ($pgv_plugin_options as $optins):
			update_option($optins["id"],$_POST[$optins["id"].'_'.$pgv_shortname]);
		endforeach;
	}
	if (isset($_POST["Resetsubmit"])){
		foreach ($pgv_plugin_options as $optins):
			update_option($optins["id"],$optins["std"]);
		endforeach;
	}
	foreach ($pgv_plugin_options as $optins):
		$sval[$optins["id"]]=get_option($optins["id"],$optins["std"]);
	endforeach;

	?>
	<div class="pga_panel">
    	<form action="" method="post">
        	<table class="widefat">
            	<tr>
                	<th colspan="2">تنظیمات</th>
           		</tr>
                <?php foreach ($pgv_plugin_options as $optins): ?>
            		<tr>
                		<td style="width:200px;"><label for="<?php echo $optins["id"] ?>"><?php echo $optins["desc"] ?></label>
                        </td>
                        <td>
        					<input name="<?php echo $optins["id"].'_'.$pgv_shortname ?>" 
                            id="<?php echo $optins["id"] ?>" value="<?php echo $sval[$optins["id"]] ?>" type="text" />
                        </td>
    				 </tr>
                <?php endforeach; ?>
                <tr>
                	<td colspan="2">
                    	<input name="Savesubmit" value='<?php echo __("Save","PG-VIP") ?>' type="submit" />
                       	<input name="Resetsubmit" value='<?php echo __("Reset","PG-VIP") ?>' type="submit" />

                    </td>
                </tr>
        	</table>
        </form>
     </div>		
<?php
}
function pgv_About_page(){
?>
	<div class="pgv-dashboard-box">
		<p>افزونه حرفه ای عضویت ویژه</p>
		<p>طراحی  و برنامه نویسی <a href="http://www.pgsavis.com">سید جمال قاسمی</a></p>
        <p><a href="http://www.pgsavis.com">پدیده گستر ساویس</a></p>
	</div>
    <div class="pgv-dashboard-help">
    	<p>
            راهنما
            <br />
            روش افزودن متن ویژه اعضای با عضویت ویژه:
            <br />
            شما با استفاده از کد کوتاهی که برای این امر 
            اختصاص یافته میتوانید متون خود را در پستها و
             صفحات فقط به اعضای ویژه نمایش دهید.
             <br />
             <code dir="ltr">[pgvmember]
این یک نوشته تست جهت اعضای ویژه میباشد
[/pgvmember]</code>
		روش افزودن فایلهای محافظت شده:
        <br />
        ابتدا از قسمت فایلهای محافظت شده فایل مورد نظر خود را بارگزاری نمایید سپس از طریق کد کوتاه و آی دی فایل بارگزای شده 
        لینک فایل را فقط برای کاربران ویژه در پستها و صفحات نمایش دهید.
        <code dir="ltr">
        	[pgvdlink id="10"]
        </code>
        تنظیمات:
        <br />
        در قسمت تنظیمات میتوانید هزینه مورد نظر خود برای هر دوره زمانی عضویت ویژه تعیین نمایید.
		</p>
	</div>
<?php
}
function pgv_ads_page(){
}

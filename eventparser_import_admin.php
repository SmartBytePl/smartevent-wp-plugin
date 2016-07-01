<?php
if($_POST['eventparser_hidden'] == 'Y') {
	//Form data sent
	$shost = $_POST['eventparser_shost'];
	update_option('eventparser_shost', $shost);
	?>
	<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
	<?php
} else {
	//Normal page display
	$shost = get_option('eventparser_shost');
}
?>

<div class="wrap">
	<?php    echo "<h2>" . __( 'Event parser Options', 'eventparser' ) . "</h2>"; ?>
	<form name="eventparser_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="eventparser_hidden" value="Y">
		<p><?php _e("Source host: " ); ?><input type="text" name="eventparser_shost" value="<?php echo $shost; ?>" size="20"><?php _e(" ex: http://localhost:8000" ); ?></p>
		<input type="submit" name="Submit" value="<?php _e('Update Options', 'eventparser' ) ?>" class="button" />
	</form>
</div>

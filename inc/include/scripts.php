<script>
 var lalang;
 var dirabs;
 lalang = '<?=$__lang__?>';
 dirabs = '<?=$_DIR_SITEABS?>';
 var addtocartconfirmation = '<?=$CLang->m_Words['ADDTOCARTCONFIRMATION']?>';
 var mustselectatype = '<?=$CLang->m_Messages['MUST_SELECT_A_TYPE']?>';
 var multipleactivationwarning = '<?=$CLang->m_Messages['MULTIPLE_ACTIVATION_WARNING']?>'; 
 var multipledeactivationwarning = '<?=$CLang->m_Messages['MULTIPLE_DEACTIVATION_WARNING']?>'; 
 var multipledeletionwarning = '<?=$CLang->m_Messages['MULTIPLE_DELETION_WARNING']?>';
 var norecordselected = '<?=$CLang->m_Messages['NO_RECORD_SELECTED']?>';
 var musacceptconditions = '<?=$CLang->m_Words['MUSTACCEPTCONDITIONS']?>';
 var deletionwarning = '<?=$CLang->m_Messages['CONFIRMATION']?>';
</script>
<script type="text/javascript" src="<?=$_DIR_SITEABS?>/inc/js/lightbox.js"></script>
<script type="text/javascript" src="<?=$_DIR_SITEABS?>/inc/js/objectSwap.js"></script>
<script type="text/javascript" src="<?=$_DIR_SITEABS?>/inc/js/CSScriptLib.js"></script>
<script type="text/javascript" src="<?=$_DIR_SITEABS?>/inc/js/datetimepicker.js"></script>
<script type="text/javascript" src="<?=$_DIR_SITEABS?>/inc/js/tree.js"></script>
<script type="text/javascript" src="<?=$_DIR_SITEABS?>/inc/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?=$_DIR_SITEABS?>/inc/js/wiwe.js"></script>
<script type="text/javascript" src="<?=$_DIR_SITEABS?>/inc/js/flowplayer-3.2.2.min.js"></script>
<script type="text/javascript" src="<?=$_DIR_SITEABS?>/inc/js/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="<?=$_DIR_SITEABS?>/inc/js/jquery-ui-1.8.23.custom.min.js"></script>


<script type="text/javascript" src="<?=$_DIR_SITEABS?>/inc/js/jquery.nivo.slider.js"></script>
<script type="text/javascript" src="<?=$_DIR_SITEABS?>/inc/js/bday-picker.min_.js"></script>
<script type="text/javascript" src="<?=$_DIR_SITEABS?>/inc/js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="<?=$_DIR_SITEABS?>/inc/js/jquery.treeview.js"></script>

<?
if ($__modulo__=="admin") {
?>
<script>
$(function() {
//		$("#draggable").draggable();
	$( ".consulta" ).draggable({
		start: function(event, ui) { },
		drag: function(event, ui) { },
		stop: function(event, ui) { 
			
		}
	});

	$(".consulta").droppable({
		tolerance: 'pointer',
		drop: function(event, ui) {
			$(ui.draggable).css("display", "none");
			$(ui.draggable).remove();
			$(ui.helper).remove(); //IE7
			before  = true;
			ordenarcontenido( $(this).attr("id"), $(ui.draggable).attr("id"), before );

		}		
	});
});
</script>
<?
}
?>
<? require '../../inc/include/customscripts.php'; ?>
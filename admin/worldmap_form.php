<?php
session_start();
if(empty($_SESSION['user_id'])){
	header('Location: index.php');
	die();
}

require_once('../connection.php');
require_once('../countryList.php');
?>
<style>
.source-group {
    position: relative;
}
.source-group button.remove-source-btn, .source-group button.add-source-btn {
    position: absolute;
    right: -80px;
    background: #000;
    color: #fff;
    font-size: 12px;
    border-radius: 4px;
    padding: 5px 10px;
    line-height: 12px;
    top: 10px;
    border: none;
    cursor: pointer;
}
.source-group{
	margin-bottom:15px;
}
button.add-source-btn{
	 background: #000;
    color: #fff;
    font-size: 12px;
    border-radius: 4px;
    padding: 10px 15px;
    line-height: 12px;
    top: 10px;
    border: none;
    cursor: pointer;
	margin:5px 0;
}
</style>
<link href="/asset/css/backend_style.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script>


<div class="page_wrapper">
	<div class="eidt_top">
<h2>World Map Details</h2>
<a class="btn_edit" href="/admin/worldmap_list.php">World Map Lists</a>
	</div>
<form method="post" name="worldmap" enctype="multipart/form-data">
    <div class="map_form_wrap">
        <div class="form-control">
            <label>Select Country <span style="color:red;">*</span></label>
            <select class="country" name="country" required>
                <option value="">Select Country</option>
                <?php 
                foreach($countryList as $key => $countryName){ 
                    $query = "SELECT country FROM wp_world_map WHERE country='".mysqli_real_escape_string($conn, $countryName)."'";
                    $results = mysqli_query($conn, $query);
                    if($results && mysqli_num_rows($results) > 0){ ?>
                        <option value="<?php echo $countryName; ?>" disabled><?php echo $countryName; ?> (Already Added)</option>
                    <?php } else { ?>
                        <option value="<?php echo $countryName; ?>"><?php echo $countryName; ?></option>
                    <?php } 
                } ?>
            </select>
        </div>

        <div class="form-control">
            <label>Sanctions </label>
            <select name="sanctions[]" class="js-example-placeholder-single js-states form-control sanctions" id="sanctions" multiple>
                <option value="usa_sanctions">USA only Sanctions</option>
                <option value="eu_sanctions">EU only Sanctions</option>
                <option value="uk_sanctions">UK only Sanctions</option>
                <option value="un_sanctions">UN only Sanctions</option>
                <option value="canada_sanctions">Canada only Sanctions</option>
                <option value="switzerland_sanctions">Switzerland only Sanctions</option>
            </select>
        </div>

    <?php
    $sanctionTypes = [
        'usa_sanctions' => 'USA',
        'eu_sanctions' => 'EU',
        'uk_sanctions' => 'UK',
        'un_sanctions' => 'UN',
        'canada_sanctions' => 'Canada',
        'switzerland_sanctions' => 'Switzerland'
    ];

    foreach ($sanctionTypes as $slug => $label) {
        ?>
        <div class="<?php echo $slug; ?> world_map_wrapper" style="display:none;">
			<div class="form-control">
				<label class="label"><?php echo $label; ?> Sanctions Full Content</label>
				<textarea name="<?php echo $slug; ?>" rows="5" cols="50" id="<?php echo $slug; ?>" class="form-control"></textarea>
			</div>			
			<div class="source-wrapper form-control" data-sanction="<?php echo $slug; ?>">
				<div class="source-group">
					<input type="text" name="<?php echo $slug; ?>_source[]" class="source" placeholder="Enter source URL">
				</div>
				<button type="button" class="add-source-btn">Add More Source</button>
			</div>
		</div>
        <?php
    }
    ?>
    <div class="mapform_bottom">
		<div class="form-control">
				<label>Sanctions PDF</label>
				<input type="file" name="pdf" class="pdf" accept="application/pdf">
		</div>
        <div class="form-control">
            <input class="btn_form submit" type="submit" name="submit">
        </div>
    </div>
	 </div>
</form>
</div>
<script>

	// Handle "Add More Source" button	
	$(document).on('click', '.add-source-btn', function () {
    const wrapper = $(this).closest('.source-wrapper');
    const sanctionKey = wrapper.data('sanction');
    const newInput = `
        <div class="source-group">
            <input type="text" name="${sanctionKey}_source[]" class="source" placeholder="Enter source URL">
            <button type="button" class="remove-source-btn">Remove</button>
        </div>`;
    
    // Insert the new input above the Add More button
    $(this).before(newInput);
});

	// Handle "Remove" source
	$(document).on('click', '.remove-source-btn', function(){
		$(this).closest('.source-group').remove();
	});
	
        // Global object to store editor instances
        const editorInstances = {};

        // Initialize CKEditor instances
        const initEditors = () => {
            return Promise.all([
                ClassicEditor.create(document.querySelector('#usa_sanctions'), {
                    removePlugins: [
                        'MediaEmbed',
                        'MediaEmbedToolbar',
                        'MediaEmbedUpload',
                        'MediaEmbedEditing'
                    ],
                }).then(editor => {
                    editorInstances.usa_sanctions = editor;
                }),
                ClassicEditor.create(document.querySelector('#eu_sanctions'), {
                    removePlugins: [
                        'MediaEmbed',
                        'MediaEmbedToolbar',
                        'MediaEmbedUpload',
                        'MediaEmbedEditing'
                    ],
                }).then(editor => {
                    editorInstances.eu_sanctions = editor;
                }),
                ClassicEditor.create(document.querySelector('#uk_sanctions'), {
                    removePlugins: [
                        'MediaEmbed',
                        'MediaEmbedToolbar',
                        'MediaEmbedUpload',
                        'MediaEmbedEditing'
                    ],
                }).then(editor => {
                    editorInstances.uk_sanctions = editor;
                }),
                ClassicEditor.create(document.querySelector('#un_sanctions'), {
                    removePlugins: [
                        'MediaEmbed',
                        'MediaEmbedToolbar',
                        'MediaEmbedUpload',
                        'MediaEmbedEditing'
                    ],
                }).then(editor => {
                    editorInstances.un_sanctions = editor;
                }),
                ClassicEditor.create(document.querySelector('#canada_sanctions'), {
                    removePlugins: [
                        'MediaEmbed',
                        'MediaEmbedToolbar',
                        'MediaEmbedUpload',
                        'MediaEmbedEditing'
                    ],
                }).then(editor => {
                    editorInstances.canada_sanctions = editor;
                }),
                ClassicEditor.create(document.querySelector('#switzerland_sanctions'), {
                    removePlugins: [
                        'MediaEmbed',
                        'MediaEmbedToolbar',
                        'MediaEmbedUpload',
                        'MediaEmbedEditing'
                    ],
                }).then(editor => {
                    editorInstances.switzerland_sanctions = editor;
                })
            ]);
        };

        // Initialize all editors
        initEditors().then(() => {
            console.log('All editors are ready to use!');
        }).catch(error => {
            console.error('There was a problem initializing the editors.', error);
        });


jQuery(document).ready(function($){
    $("#sanctions").select2({
        multiple: true,
        placeholder: "Select Sanctions"
    });

    $('.sanctions').change(function(){
        $('.usa_sanctions, .eu_sanctions, .uk_sanctions, .canada_sanctions, .switzerland_sanctions, .un_sanctions').hide();
        var sanctions = $(this).val();
        $.each(sanctions, function(index, value){
            $('.' + value).show();
        });
    });

    $('.submit').click(function(event){
        event.preventDefault();
        var country = $('.country').val();
        var sanctions = $('#sanctions').val();
		
		// Get data from CKEditor instances
                var usa_sanctions = editorInstances.usa_sanctions.getData();
                var eu_sanctions = editorInstances.eu_sanctions.getData();
                var uk_sanctions = editorInstances.uk_sanctions.getData();
                var un_sanctions = editorInstances.un_sanctions.getData();
                var canada_sanctions = editorInstances.canada_sanctions.getData();
                var switzerland_sanctions = editorInstances.switzerland_sanctions.getData();


        //if (!country || sanctions.length === 0) {
        if (!country) {
            if (!country) alert('Please Select Country');
            //if (sanctions.length === 0) alert('Please Select Sanctions');
            return;
        }

        var form = $('form')[0];
        var formData = new FormData(form);
		 formData.append('usa_sanctions', usa_sanctions);
		 formData.append('eu_sanctions', eu_sanctions);
		 formData.append('uk_sanctions', uk_sanctions);
		 formData.append('un_sanctions', un_sanctions);
		 formData.append('canada_sanctions', canada_sanctions);
		 formData.append('switzerland_sanctions', switzerland_sanctions);

        $.ajax({
            url: 'https://map.sanctionsassociation.org/admin/sanctions_ajax.php',
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data){
				console.log(data);
                if(data.trim() === "1"){
                    alert('Successfully added data');
                    location.reload();
                } else {
                    alert(data);
                }
            },
            error: function(xhr, status, error){
                alert('AJAX Error: ' + error);
            }
        });
    });
});
</script>

<?php
session_start();
$id = $_GET['id'] ?? 0;
if(empty($_SESSION['user_id'])){
	header('Location: index.php');
	die();
}

if(empty($id)){
	header('Location: /admin/worldmap_list.php');
	die();
}

require_once('../connection.php');
require_once('../countryList.php');


$query = "SELECT * FROM wp_world_map WHERE id = '".intval($id)."'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) !== 1) {
   header('Location: /admin/worldmap_list.php');
   die('Access denied. Record not found.');
}
$row = mysqli_fetch_assoc($result);

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

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script>

<link rel='stylesheet' href='../asset/css/backend_style.css' media='all' />
<div class="page_wrapper">
<div class="eidt_top">
<h2>Edit World Map Details</h2>
<a class="btn_edit" href="/admin/worldmap_list.php">World Map Lists</a>
	</div>
<form method="post" name="worldmap" enctype="multipart/form-data">
    <div class="map_form_wrap">
        <div class="form-control">
            <label>Select Country <span style="color:red;">*</span></label>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <select class="country" name="country">
                <option value="">Select Country </option>
                <?php foreach ($countryList as $country): ?>
                    <option value="<?php echo $country; ?>" <?php echo ($row['country'] == $country) ? 'selected' : ''; ?>>
                        <?php echo $country; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-control">
            <label>Sanctions</label>
            <select name="sanctions[]" class="js-example-placeholder-single js-states form-control sanctions" multiple id="sanctions">
                <?php
                //$selectedSanctions = explode(',', $row['sanctions']);
				$selectedSanctions = explode(',', $row['sanctions'] ?? '');
                $sanctionsOptions = [
                    "usa_sanctions" => "USA only Sanctions",
                    "eu_sanctions" => "EU only Sanctions",
                    "uk_sanctions" => "UK only Sanctions",
                    "un_sanctions" => "UN only Sanctions",
                    "canada_sanctions" => "Canada only Sanctions",
                    "switzerland_sanctions" => "Switzerland only Sanctions"
                ];

                foreach ($sanctionsOptions as $value => $label) {
                    $selected = in_array($value, $selectedSanctions, true) ? 'selected' : '';
                    echo "<option value=\"$value\" $selected>$label</option>";
                }
                ?>
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
		?>
        <?php foreach ($sanctionTypes as $key => $label): ?>
		<?php $pdf_file = strtolower($label).'_pdf'; ?>
        <div class="<?php echo $key; ?> world_map_wrapper" style="display:none;">
            <div class="form-control">
                <label><?php echo $label; ?> Full Content</label>
                <textarea name="<?php echo $key; ?>" rows="5" cols="70" id="<?php echo $key; ?>"><?php echo htmlspecialchars($row[$key] ?? ''); ?></textarea>
            </div>
            <?php
			$sources = array_filter(explode("\n", $row[$key . '_source'] ?? ''));
			?>
			<div class="form-control source-wrapper" data-sanction="<?php echo $key; ?>">
				<label><?php echo $label; ?> Source(s)</label>
				<?php if (count($sources)): ?>
					<?php foreach ($sources as $i => $source): ?>
						<div class="source-group">
							<input type="text" name="<?php echo $key; ?>_source[]" value="<?php echo htmlspecialchars(trim($source)); ?>" class="source" placeholder="Enter source URL">
							<?php if ($i > 0): ?>
								<button type="button" class="remove-source-btn">Remove</button>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<div class="source-group">
						<input type="text" name="<?php echo $key; ?>_source[]" class="source" placeholder="Enter source URL">
					</div>
				<?php endif; ?>
				<button type="button" class="add-source-btn">Add More Source</button>
			</div>
        </div>
        <?php endforeach; ?>

        <div class="mapform_bottom">
			<div class="form-control">
				<label>Sanctions PDF</label>
				<input type="file" name="pdf" class="pdf" accept="application/pdf">
				<input type="hidden" name="pdf_already" value="<?php echo $row['pdf']; ?>">
				<?php if (!empty($row['pdf'])): ?>
					<div class="form-control">
						<label>Existing PDF:</label>
						<div class="pdf">
							<a href="../uploads/worldmap/<?php echo $row['pdf']; ?>" target="_blank"><img src="../asset/images/icon-pdf.png" alt="#">View PDF</a>
						</div>
					</div>
				<?php endif; ?>
			</div>
            <div class="form-control">
                <input class="btn_form submit" type="submit" name="submit" value="Update">
            </div>
        </div>
    </div>
</form>
</div>
<script>
// Handle dynamic add/remove for source fields
/* $(document).on('click', '.add-source-btn', function () {
    const wrapper = $(this).closest('.source-wrapper');
    const sanctionKey = wrapper.data('sanction');
    const newInput = `
        <div class="source-group">
            <input type="text" name="${sanctionKey}_source[]" class="source" placeholder="Enter source URL">
            <button type="button" class="remove-source-btn">Remove</button>
        </div>`;
    wrapper.append(newInput);
}); */

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

$(document).on('click', '.remove-source-btn', function () {
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
		
jQuery(document).ready(function($) {
	
	$('.pdf').click(function(){
		$('.pdf_view').remove();
	});
	
    const selectedSanctions = $('#sanctions').val() || [];
    selectedSanctions.forEach(function(value) {
        $('.' + value).show();
    });

    $('#sanctions').select2({
        multiple: true,
        placeholder: "No Sanctions"
    });

    $('.sanctions').on('change', function() {
        $('.world_map_wrapper').hide();
        const selected = $(this).val();
        selected.forEach(function(value) {
            $('.' + value).show();
        });
    });

    $('.submit').click(function(event) {
        event.preventDefault();

        const country = $('.country').val();
        const sanctions = $('#sanctions').val();
		
		// Get data from CKEditor instances
                var usa_sanctions = editorInstances.usa_sanctions.getData();
                var eu_sanctions = editorInstances.eu_sanctions.getData();
                var uk_sanctions = editorInstances.uk_sanctions.getData();
                var un_sanctions = editorInstances.un_sanctions.getData();
                var canada_sanctions = editorInstances.canada_sanctions.getData();
                var switzerland_sanctions = editorInstances.switzerland_sanctions.getData();

        if (!country) {
            if (!country) alert('Please Select Country');
            return;
        }

        const form = $('form')[0];
        const formData = new FormData(form);
		
		 formData.append('usa_sanctions', usa_sanctions);
		 formData.append('eu_sanctions', eu_sanctions);
		 formData.append('uk_sanctions', uk_sanctions);
		 formData.append('un_sanctions', un_sanctions);
		 formData.append('canada_sanctions', canada_sanctions);
		 formData.append('switzerland_sanctions', switzerland_sanctions);

        $.ajax({
			url: 'https://map.sanctionsassociation.org/admin/edit_sanctions_ajax.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                console.log(data);
                if (data == 1) {
                    alert('Successfully Updated');
                    location.reload();
                } else {
                    console.log(data);
                    alert('Update failed');
                }
            }
        });
    });
});
</script>

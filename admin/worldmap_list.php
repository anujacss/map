<?php
session_start();
if(empty($_SESSION['user_id'])){
	header('Location: index.php');
	die();
}
require_once('../connection.php');
$query = "SELECT * FROM wp_world_map";
$results = mysqli_query($conn,$query);
?>
<style>
#wpbody-content table {
    width: 97%;
    background: #dbdbdb;
    padding: 11px;
    border-radius: 10px;
    margin: 20px;
}
.addBtn a:hover {
    background: #9f9e9e;
}
.addBtn a {
    background: #858585;
    color: #fff;
    text-decoration: none;
    text-transform: uppercase;
    font-weight: 600;
    padding: 9px 33px;
    display: inline-block;
    border-radius: 5px;
    margin-left: 20px;
    margin-top: 50px;
}
#wpbody-content table thead tr th {
    text-align: left;
    padding-bottom: 6px;
}
#wpbody-content table tbody tr td {
    padding: 10px 0;
}
#wpbody-content table tbody tr td a:hover {
    background: #727272;
    color: #fff;
}

#wpbody-content table tbody tr td a {
    border: 1px solid #727272;
    border-radius: 4px;
    padding: 3px 14px;
    text-decoration: none;
    color: #000;
    font-weight: 600;
    font-size: 12px;
}
	</style>
<link href="https://map.sanctionsassociation.org/asset/css/backend_style.css" rel="stylesheet" />
<div class="page_wrapper">
<div class="page_top"><a href="https://map.sanctionsassociation.org/admin/worldmap_form.php">Add</a></div>
<table class="table_grid" width="100%;">
	<thead>
		<tr>
			<th>Country</th>
			<th>Sanctions</th>
			<th style="text-align: center;">Actions</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($results as $result){ 
		$sanctions = preg_replace('/(,)(?=[^\s])/', ', ', str_replace('_', ' ', ucwords($result['sanctions'])));
	?>
		<tr>
			<td><?php echo $result['country']; ?></td>
			<td><?php echo ucwords(strtolower($sanctions), '\',. ');?></td>
			<td style="text-align: center;"><a href="/admin/edit_world_map.php?id=<?php echo $result['id'];?>">Edit</a> <a class="delete" data-id="<?php echo $result['id'];?>" href="javascript:void(0);">Delete</a></td>
		</tr>
	<?php } ?>
	</tbody>
</table>
	</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
	$('.delete').on('click', function () {
    const id = $(this).data('id');

    if (confirm('Are you sure you want to delete this record?')) {
		$.ajax({
			url: 'https://map.sanctionsassociation.org/admin/delete_sanctions_ajax.php',
			type: 'POST',
			data: { id: id },
			dataType: 'text',
			success: function (response) {
				if (response == 'Record deleted successfully') {
					location.reload();
				} else {
					console.error('Server response:', response);
					alert('Delete failed');
				}
			},
			error: function (xhr, status, error) {
				console.error('AJAX Error:', status, error);
				alert('An error occurred while deleting.');
			}
		});
	}
});

});
</script>
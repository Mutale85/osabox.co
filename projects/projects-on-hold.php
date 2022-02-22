<?php 
  	require ("includes/db.php");
  	require ("includes/tip.php");  
?>
<!DOCTYPE html>
<html>
<head>
	<title>Projects On Hold</title>
	<?php include("links.php") ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
</head>
<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("nav_side.php"); ?>
		<div class="content-wrapper">
			<?php include ("dash_data.php"); ?>
      		<section class="content">
      			<div class="container-fluid">
      				<div class="row">
      					<div class="col-md-12">
			      			<div class="card card-secondary">
						        <div class="card-header">
						          	<h3 class="card-title">Projects On Hold</h3>

						          	<div class="card-tools">
						           		<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
						              		<i class="fas fa-minus"></i>
						            	</button>
						            	<button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
						              		<i class="fas fa-times"></i>
						            	</button>
						          	</div>
						        </div>
						        <div class="card-body">
						          	<table class="table table-striped projects" id="projectsTable">
						              	<thead>
						                  	<tr>
						                      	<th style="width: 1%">
						                        	#
						                      	</th>
						                      	<th style="width: 20%">
						                          	Project Name
						                      	</th>
						                      	<th style="width: 30%">
						                          	Team Members
						                      	</th>
						                      	<th style="width: 8%" class="text-center">
						                          	Status
						                      	</th>
						                      	<th style="width: 20%">
						                      	</th>
						                  	</tr>
						              	</thead>
						              	<tbody>
						              		<?php
						              			$one = 1;
						              			$parent_id = $_SESSION['parent_id'];
							              		$query = $connect->prepare("SELECT * FROM projects WHERE parent_id = ? AND status = 'On Hold' ");
												$query->execute(array($parent_id));
												if ($query->rowCount() > 0) {
													foreach ($query->fetchAll() as $row) {
														extract($row);
														if($status == 'Starting'){
															$status_ = '<span class="badge badge-primary">'.$status.'</span>';
														}elseif ($status == 'On Going') {
															$status_ = '<span class="badge badge-info">'.$status.'</span>';
														}elseif ($status == 'On Hold') {
															$status_ = '<span class="badge badge-warning">'.$status.'</span>';
														}elseif ($status == 'Canceled') {
															$status_ = '<span class="badge badge-danger">'.$status.'</span>';
														}elseif ($status =='Success') {
															$status_ = '<span class="badge badge-success">Completed</span>';
														}
													
											?>
											<tr>
						                      	<td><?php echo $one++ ?></td>
						                      	<td>
						                          	<a href="project-details" data-id="<?php echo $id?>" class="createCookieView"><?php echo $project_name?></a>
						                          	<br/>
						                          	<small>
						                              Created <?php echo $date_added?>
						                          	</small>
						                      	</td>
						                      	<td>
						                          	<ul class="list-inline">
						                          		<?php 
						                          			$query = $connect->prepare("SELECT * FROM projects_team_members WHERE parent_id = ? ");
															$query->execute(array($parent_id));
															$results = $query->fetchAll();
															foreach ($results as $rows) {
										
															$member_id 	= $rows['team_member'];
															$p_id 		= $rows['parent_id'];
						                          		?>
														<li class="list-inline-item">
															<img alt="<?php echo getStaffMemberImage($connect, $member_id, $p_id)?>" class="table-avatar img-circle" src="<?php echo getStaffMemberImage($connect, $member_id, $p_id)?>" style="width: 50px; height: 50px;">
														</li>
														<?php }

														?>
														
						                          	</ul>
	                      						</td>
												
						                      	<td class="project-state">
						                          	<?php echo $status_?>
						                      	</td>
												<td class="project-actions text-right">
													<a href="project-view" data-id="<?php echo $id?>" class="btn btn-primary btn-sm createCookieView">
														<i class="fas fa-folder"></i>
														View
													</a>
													<?php if($leader == $_SESSION['user_id']):?>
													<a href="project-edit" data-id="<?php echo $id?>" class="btn btn-info btn-sm createCookieEdit" href="#">
													<i class="fas fa-pencil-alt"></i>
														Edit
													</a>
													<a class="btn btn-danger btn-sm" href="#">
													<i class="fas fa-trash"></i>
														Delete
													</a>
													<?php else:?>
													<?php endif;?>
												</td>
											</tr>	

											<?php			
													}
												}else{

												}
						              		?>
						              		
						              	</tbody>
						            </table>
						        </div>
						    </div>
						</div>
					</div>
				</div>
    		</section>
      	</div>
      	<aside class="control-sidebar control-sidebar-dark"></aside>
    </div>
    <?php include("footer_links.php")?>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
	<script>
		//========== GET CURRENCY
		
		
		$(function(){
			$("#projectsTable").DataTable();
			$("#start_date").datepicker({
				format: 'yyyy-mm-dd',
				autoclose:true,
				startDate: '-1d',
				defaultViewDate: 'today',
			});
			// =================== SUBMITE THE data =================================

			$("#projectForm").submit(function(e){
				e.preventDefault();
				var projectForm = document.getElementById('projectForm');
				
				var data = new FormData(projectForm);
				var url = 'project-submit';
				$.ajax({
					url:url+'?<?php echo time()?>',
					method:"post",
					data:data,
					cache : false,
					processData: false,
					contentType: false,
					beforeSend:function(){
						$("#submit_project").html("<i class='fa fa-spinner fa-spin'></i>");
					},
					success:function(data){
						if (data === 'done') {
							successNow("New project added");
							setTimeout(function(){
								location.reload();
							}, 1500);
						}else if(data == 'updated'){
							successNow("Project data updated");
							setTimeout(function(){
								location.reload();
							}, 1500);
						}else{
							errorNow(data);
						}
					}
				})
			})
		})

		$(document).on("click", ".createCookieView", function(e){
	      	e.preventDefault();
	      	var cvalue = $(this).data('id');
	      	var cname = "projectID";
	      	projectID(cname, cvalue);
	        window.location = "project-details";
      	})
      	function projectID(cname, cvalue) {
        	event.preventDefault();    
        	const d = new Date();
        	d.setTime(d.getTime() + (30*24*60*60*1000));
        	let expires = "expires="+ d.toUTCString();
        	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
      	}
		
		$(document).on("click", ".createCookieEdit", function(e){
	      	e.preventDefault();
	      	var cvalue = $(this).data('id');
	      	var cname = "projectID";
	      	projectID(cname, cvalue);
	        window.location = "project-edit";
      	})
      	// function projectID(cname, cvalue) {
       //  	event.preventDefault();    
       //  	const d = new Date();
       //  	d.setTime(d.getTime() + (30*24*60*60*1000));
       //  	let expires = "expires="+ d.toUTCString();
       //  	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
      	// }

		$(document).on("click", ".editData", function(e){
			e.preventDefault();
			var clients_in_need_id = $(this).attr("href");
			$.ajax({
				url:"editData",
				method:"post",
				data:{clients_in_need_id:clients_in_need_id},
				dataType:"JSON",
				success:function(data){
					$("#ID").val(data.id);
					$("#firstname").val(data.firstname);
					$("#lastname").val(data.lastname);
					$("#city").val(data.city);
					$("#business_type").val(data.business_type);
					$("#required_amount").val(data.required_amount);
					$("#id_type").val(data.id_type);
					$("#id_number").val(data.id_number);
					$("#email").val(data.email);
					$("#phone").val(data.mobile_number);
					$("#result").html(data.mobile_number);
					$("#details").val(data.details);
				}
			})
		})

		$(document).on("click", ".deleteData", function(e){
			e.preventDefault();
			var delete_clients_in_need_id = $(this).attr("href");
			if(confirm("You wish to remove the client")){
				$.ajax({
					url:"editData",
					method:"post",
					data:{delete_clients_in_need_id:delete_clients_in_need_id},
					success:function(data){
						if (data === 'done') {
							successNow("Client Deleted");
							setTimeout(function(){
								location.reload();
							}, 2000);
						}else{
							errorNow(Data);
						}
					}
				})
			}else{
				return false;
			}
		})

	</script>
</body>
</html>
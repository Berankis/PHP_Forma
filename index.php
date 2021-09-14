<!DOCTYPE html>
<?php include('mysqli_con.php'); ?>
<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	</head>
	<body>
		<!-- Komentaro rašymo forma -->
		<div class='container w-75 mt-4'>
			<form>
				<div class="form-group row">
					<label for="email" class="col-sm-1 col-form-label">Email</label>
					<div class="col-sm-5">
						<input maxlength="100" type="email" class="form-control mb-2" id="email" aria-describeby='email_validation' placeholder="Enter email"/>
						<div id='email_validation' class='invalid-feedback'>Email address is not valid</div>
					</div>
					<label for="name" class="col-sm-1 col-form-label">Name</label>
					<div class="col-sm-5">
						<input maxlength='50' type="name" class="form-control mb-2" id="name" aria-describeby='name_validation' placeholder="Enter name"/>
						<div id='name_validation' class='invalid-feedback'>Invalid name</div>
					</div>
				</div>
				<div class="form-group">
					<label for="comment">Comment</label>
					<textarea type="text" class="form-control" id="comment" aria-describeby='comment_validation' placeholder="Enter comment"></textarea>
					<div id='comment_validation' class='invalid-feedback'>This field cannot be blank</div>
				</div>
				<button type="button" id='new_comment' class="btn btn-primary">Submit</button>
			</form>
		</div>
		<!-- Komentarų skiltis -->
		<div class='container w-75 mt-4'>
			<?php
				$rows=0;
				
			?>
			<p class='h3' id='comments_number_text'><?php echo $rows; ?> comments</p>
			<div id='all_comments'>
			
			</div>
			<div class='container' id='comment_container' style='display: none'>
				<div style='background-color: #ddeded' class='container border p-3 mt-2 text-grey'>
					<div class='row'>
						<div class='font-weight-bold col-md-auto' id='comment_name'>Lukas Juozas Čiuplys</div>
						<div class='col-3' id='comment_date'>13 Sep 2021 22:21</div>
						<div class='text-right col'><a onclick='hide_other_collapses()' id='reply_link' data-toggle='collapse' href='#reply' aria-expanded='false' aria-controls='reply'><u>Reply</u></a></div>
					</div>
					<div class='row pl-3 pr-3 text-break' id='comment_text'>
						asd
					</div>
				</div>
				<!-- Komentaro atsakymo forma -->
				<div class='collapse' id='reply'>
					<div class='container w-75 mt-4'>
						<form>
							<div class="form-group row">
								<label for="email" class="col-sm-1 col-form-label">Email</label>
								<div class="col-sm-5">
									<input oninput='email_reply_changed(1)' type="email" class="form-control mb-2" id="email_reply" aria-describeby='email_validation_reply' placeholder="Enter email"/>
									<div id='email_validation_reply' class='invalid-feedback'>Email address is not valid</div>
								</div>
								<label for="name" class="col-sm-1 col-form-label">Name</label>
								<div class="col-sm-5">
									<input oninput='name_reply_changed(1)' type="name" class="form-control mb-2" id="name_reply" aria-describeby='name_validation_reply' placeholder="Enter name"/>
									<div id='name_validation_reply' class='invalid-feedback'>Invalid name</div>
								</div>
							</div>
							<div class="form-group">
								<label for="comment">Comment</label>
								<textarea oninput='com_reply_changed(1)' type="text" class="form-control" id="comment_reply" aria-describeby='comment_validation_reply' placeholder="Enter comment"></textarea>
								<div id='comment_validation_reply' class='invalid-feedback'>This field cannot be blank</div>
							</div>
							<button type='button' onclick='comment_selected()' class="btn btn-primary" id='comment_reply_button'>Submit</button>
						</form>
					</div>
				</div>
				<!-- Komentarų atsakymų div'as -->
				<div id='atsakymai'>
					
				</div>
			</div>
			<!-- Komentaro atsakymo konteineris, kuris kopijuojamas į kitų komentarų atsakymų div'us -->
			<div style='display: none' class='container' id='response'>
				<div class='row'>
					<div class='col-1'></div>
					<div style='background-color: #d5e0e0' class='container border p-3 mt-2 text-grey col-11'>
						<div class='row'>
							<div class='font-weight-bold col-md-auto' id='response_name'></div>
							<div class='col-4' id='response_date'></div>
						</div>
						<div class='row pl-3 pr-3 text-break' id='response_text'>
							asd
						</div>
					</div>
				</div>
			</div>
			
		</div>
		<script>
			var comment_number=0;
			var email_invalid = false;
			var name_invalid = false;
			var reply_email_invalid = false;
			var reply_name_invalid = false;
			var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
			function comment_selected(id){
				var ok = true;
				if(reply_email_invalid==true || reply_name_invalid==true)ok=false;
				if($('#comment_reply_'+id).val().length<5){$('#comment_validation_reply_'+id).text('This field must contain at least 5 characters'); $('#comment_reply_'+id).addClass('is-invalid');ok=false;}
				if($('#email_reply_'+id).val().length==0){$('#email_validation_reply_'+id).text('Email address field cannot be blank'); $('#email_reply_'+id).addClass('is-invalid'); reply_email_invalid=true; ok=false;}
				if($('#name_reply_'+id).val().length==0){$('#name_validation_reply_'+id).text('Name field cannot be blank'); $('#name_reply_'+id).addClass('is-invalid'); reply_name_invalid = true; ok=false;}
				if(ok==true){
					$('#reply_'+id).collapse('hide');
					var date = new Date();
					var date_format = date.getDate()+' '+monthNames[date.getMonth()]+' '+date.getFullYear()+' '+String(date.getHours()).padStart(2,'0')+':'+String(date.getMinutes()).padStart(2,'0')+':'+String(date.getSeconds()).padStart(2,'0');
					CreateReply(id,$('#name_reply_'+id).val(),$('#comment_reply_'+id).val(),date_format,$('#email_reply_'+id).val(),0);
					$('#comment').val('');
				}
			}
			function CreateReply(comment_id,name,text,date,email,load){
				var clone = $('#response').clone().prependTo('#atsakymai_'+comment_id);
				$('#response_text').text(text);
				$('#response_name').text(name);
				$('#response_name').removeAttr('id');
				$('#comment_reply_'+comment_id).val('');
				$('#response_date').text(date);
				$('#response').css('display','block');
				$('#response').removeAttr('id');
				$('#response_text').removeAttr('id');
				$('#response_date').removeAttr('id');
				if(load == 0){
					$.ajax({
						method: "POST",
						url: "save_reply.php",
						data: {
							c_id : comment_id,
							name : name,
							email : email,
							text : text,
							date : date
						},
						success: function(result){
							console.log(result);
							if(result!="ok"){alert("Error: cannot save comment, please check logs."); location.reload();}
						}
					});
				}
				
			}
			$('#new_comment').on('click', function(event){
				var ok = true;
				if(email_invalid==true || name_invalid==true)ok=false;
				if($('#comment').val().length<5){$('#comment_validation').text('This field must contain at least 5 characters'); $('#comment').addClass('is-invalid');ok=false;}
				if($('#email').val().length==0){$('#email_validation').text('Email address field cannot be blank'); $('#email').addClass('is-invalid'); email_invalid=true; ok=false;}
				if($('#name').val().length==0){$('#name_validation').text('Name field cannot be blank'); $('#name').addClass('is-invalid'); name_invalid = true; ok=false;}
				if(ok==true){
					var date = new Date();
					var date_format = date.getDate()+' '+monthNames[date.getMonth()]+' '+date.getFullYear()+' '+String(date.getHours()).padStart(2,'0')+':'+String(date.getMinutes()).padStart(2,'0')+':'+String(date.getSeconds()).padStart(2,'0');
					CreateComment($('#name').val(),$('#email').val(),$('#comment').val(),date_format,0);
				}
			});
			function CreateComment(name,email,text,date,load){
				if(load==0){comment_number++;}else{comment_number=load;}
				var clone = $('#comment_container').clone().prependTo('#all_comments');
				$('#comment_text').text(text);
				$('#reply_link').attr('href','#reply_'+comment_number);
				$('#reply_link').attr('aria-controls','reply_'+comment_number);
				$('#reply_link').attr('onclick','hide_other_collapses('+comment_number+')');
					
				$('#name_reply').attr('id','name_reply_'+comment_number);
				$('#email_reply').attr('id','email_reply_'+comment_number);
				$('#comment_reply').attr('id','comment_reply_'+comment_number);

				$('#atsakymai').attr('id','atsakymai_'+comment_number);
				$('#comment_reply_'+comment_number).attr('oninput','com_reply_changed('+comment_number+')');
				$('#comment_reply_'+comment_number).attr('aria-describeby','comment_validation_reply_'+comment_number);
				$('#name_reply_'+comment_number).attr('oninput','name_reply_changed('+comment_number+')');
				$('#name_reply_'+comment_number).attr('aria-describeby','name_validation_reply_'+comment_number);
				$('#email_reply_'+comment_number).attr('oninput','email_reply_changed('+comment_number+')');
				$('#email_reply_'+comment_number).attr('aria-describeby','email_validation_reply_'+comment_number);
				$('#name_validation_reply').attr('id','name_validation_reply_'+comment_number);
				$('#email_validation_reply').attr('id','email_validation_reply_'+comment_number);
				$('#comment_validation_reply').attr('id','comment_validation_reply_'+comment_number);
				$('#comment_container').css('display','block');
				$('#comment_name').text(name);
				
				$('#comment_date').text(date);
				$('#comment_reply_button').attr('onclick','comment_selected('+comment_number+')');
				clone.removeAttr('id');
				$('#reply').attr('id','reply_'+comment_number);
				if(load==0){
					for(var i=1; i <= comment_number; i++){
						$('#reply_'+i).collapse('hide');
					}
					$.ajax({
						method: "POST",
						url: "save_comment.php",
						data: {
							name : name,
							email : email,
							text : text,
							date : date
						},
						success: function(result){
							console.log(result);
							if(result!="ok"){alert("Error: cannot save comment, please check logs."); location.reload();}
							
						}
					});
				}
				$('#comments_number_text').text(comment_number+' comments');
			}
			function hide_other_collapses(id){
				for(var i=1; i <= comment_number; i++){
					if(i==id)continue;
					$('#reply_'+i).collapse('hide');
				}
			}
			function name_reply_changed(id){
				if(validateName($('#name_reply_'+id).val())==false && reply_name_invalid==false){
					reply_name_invalid = true;
					$('#name_reply_'+id).addClass('is-invalid');
					$('#name_validation_reply_'+id).text('Invalid name');
				}else if(validateName($('#name_reply_'+id).val())==true && reply_name_invalid==true){
					reply_name_invalid = false;
					$('#name_reply_'+id).removeClass('is-invalid');
				}
			}
			function email_reply_changed(id){
				if(validateEmail($('#email_reply_'+id).val())==false && reply_email_invalid==false){
					reply_email_invalid = true;
					$('#email_reply_'+id).addClass('is-invalid');
					$('#email_validation_reply_'+id).text('Email address is not valid');
				}else if(validateEmail($('#email_reply_'+id).val())==true && reply_email_invalid==true){
					reply_email_invalid = false;
					$('#email_reply_'+id).removeClass('is-invalid');
				}
			}
			function com_reply_changed(id){
				$('#comment_reply_'+id).removeClass('is-invalid');
			}
			$('#email').on('input', function(event){
				if(validateEmail($('#email').val())==false && email_invalid==false){
					email_invalid = true;
					$('#email').addClass('is-invalid');
					$('#email_validation').text('Email address is not valid');
				}else if(validateEmail($('#email').val())==true && email_invalid==true){
					email_invalid = false;
					$('#email').removeClass('is-invalid');
				}
			});
			$('#name').on('input', function(event){
				if(validateName($('#name').val())==false && name_invalid==false){
					name_invalid = true;
					$('#name').addClass('is-invalid');
					$('#name_validation').text('Invalid name');
				}else if(validateName($('#name').val())==true && name_invalid==true){
					name_invalid = false;
					$('#name').removeClass('is-invalid');
				}
			});
			$('#comment').on('input', function(event){
				$('#comment').removeClass('is-invalid');
			});
			function validateEmail($email) {
				var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
				return emailReg.test( $email );
			}
			function validateName($name){
				var res = /^[a-zA-Zą-žĄ-Ž ]+$/;
				return res.test($name);
			}
		</script>
		<?php
			$query = mysqli_query($handle,"SELECT * FROM comments");
			$rows = mysqli_num_rows($query);
			while($data = mysqli_fetch_row($query)){
				$id = $data[0];
				$name = $data[2];
				$email = $data[1];
				$text = $data[3];
				$date = $data[4];
				echo "
				<script>
					CreateComment('$name','$email','$text','$date','$id');
					comment_number = $rows;
				</script>";
			}
			mysqli_free_result($query);
			$query = mysqli_query($handle,"SELECT * FROM replies");
			while($data = mysqli_fetch_row($query)){
				$comment_id = $data[1];
				$name = $data[2];
				$email = $data[3];
				$text = $data[4];
				$date = $data[5];
				echo "
				<script>
					CreateReply('$comment_id','$name','$text','$date','$email',1);
				</script>";
			}
			mysqli_free_result($query);
		?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	</body>
	<?php mysqli_close($handle); ?>
</html>
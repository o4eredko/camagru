<section id="add_photo">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-9">
				<form action = "ajax?action=addPhoto" method="POST" class="add_photo__form" enctype="multipart/form-data">
					<h3 class="add_photo__title">Add Photo</h3>
					<div class="form__content">
						<input type="hidden" name="action" value="addPhoto">
						<div class="add_photo__area">
							<i class="fas fa-cloud-upload-alt"></i>
							<input type="file" name="img" class ="add_photo__file" required>
						</div>
						<input type="text" name="title" placeholder="Post Title" required>
						<textarea name="description" placeholder="Post description"></textarea>
						<button class="button">Add Photo</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>

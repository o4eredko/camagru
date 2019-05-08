<section id="add_photo">
	<div class="container">
		<div class="row justify-content-center">
            <div class="col-6">
                <h3 class="add_photo__title">Grab a snapshot</h3>
                <canvas id="snapshot"></canvas>
                <video id="cam" autoplay></video>
                <button class="button button-transparent" id="snap-button">Take a photo</button>
            </div>
			<div class="col-6">
				<form method="POST" class="add_photo__form" enctype="multipart/form-data">
					<h3 class="add_photo__title">Add Photo</h3>
					<div class="form__content">
						<input type="hidden" name="action" value="addPhoto">
						<div class="add_photo__area">
							<i class="fas fa-cloud-upload-alt"></i>
							<input type="file" name="img" class ="add_photo__file" accept="image/*" onchange="addPhoto(this.files[0])">
                            <label for="img">
                                <strong>Choose a file</strong> or drag it here.
                            </label>
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

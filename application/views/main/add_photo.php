<section id="add_photo">
	<div class="container">
		<div class="row justify-content-center">
            <div class="col-12 col-lg-7">
                <div class="row flex-column">
                    <div class="col">
                        <h3 class="add_photo__title">Grab a snapshot</h3>
                        <div class="snapshot__area">
                            <canvas id="snapshot"></canvas>
                        </div>
                        <video id="cam" autoplay></video>
                        <div class="info__block sticker__container">
                            <img src="img/leopard_sunglasses.png" alt="Camagru" class="sticker">
                            <img src="img/black.png" alt="Camagru" alt="Camagru"  class="sticker">
                            <img src="img/sigarette.png" alt="Camagru" alt="Camagru"  class="sticker">
                            <img src="img/dinosaur.png" alt="Camagru" alt="Camagru"  class="sticker">
                        </div>
                        <button class="button button-transparent" id="snap-button">Take a photo</button>
                    </div>
                    <div class="col">
                        <form class="add_photo__form" enctype="multipart/form-data">
                            <h3 class="add_photo__title">Add Photo</h3>
                            <div class="form__content">
                                <input type="hidden" name="csrf" value="<?= $_SESSION["csrf"] ?>">
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
            <div class="col-12 col-md-10 col-lg-5">
                <div class="thumbnails info__block">
                    <h3 class="add_photo__title">Previous snapshots</h3>
                    <div class="snapshots d-flex flex-column-reverse"></div>
                </div>
            </div>

		</div>
	</div>
</section>

<section id="main">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-3">
                <form class="user" id="userSettingsForm" enctype="multipart/form-data">
					<h4>Your profile</h4>
                    <label class="user__avatar" style="background: url('<?= $avatar ?>') no-repeat center center /cover;">
						<input type="file" name="avatar" accept="image/*">
					</label>
					<label>
						<input type="text" name="username" value="<?= $username ?>" placeholder="Username" required>
						<span class="label__error" hidden>User with such name already exists</span>
					</label>
					<input type="text" name="info" value="<?= $info ?>" placeholder="Status">
                    <textarea name="about" class="user__about" placeholder="About me"><?= $about ?></textarea>
					<input type="hidden" name="csrf" value="<?= $_SESSION["csrf"] ?>">
					<button class="button">Change settings</button>
                </form>
            </div>
            <div class="col col-lg-9">
                <div class="posts slider">
					<?php foreach ($posts as $post): ?>
                        <div class="col-md-6">
                            <div class="post" data-id="<?= $post["id"] ?>">
                                <a href="post?id=<?= $post["id"] ?>" class="post__img">
                                    <img src="<?= $post["img"] ?>" alt="">
                                </a>
                                <div class="post__content">
                                    <h4 class="post__title"><?= $post["title"] ?></h4>
                                    <hr>
                                    <p class="post__owner">by <a href="#"> <?= $post["owner"] ?></a></p>
                                </div>
                                <div class="post__stat">
								<?php if (!isset($_SESSION["user"])): ?>
									<a href="#" class="post__like" data-toggle-id="login">
								<?php elseif (in_array($post["id"], $likedPosts)): ?>
									<a href="#" class="post__like active" data-post-id="<?= $post["id"] ?>">
								<?php else: ?>
									<a href="#" class="post__like" data-post-id="<?= $post["id"] ?>">
								<?php endif; ?>
										<i class="fa fa-thumbs-up"></i>
										<?= $post["likes"] ?>
									</a>
									<a href="post?id= <?= $post["id"] ?>" class="post__comment"
										<?php if (!isset($_SESSION["user"])): ?>
											data-toggle-id="login"
										<?php endif; ?>>
										<i class="fas fa-comment comment"></i><?= $post["comments"] ?>
									</a>
								<?php if (isset($_SESSION["user"]) && $post["owner"] == $_SESSION["user"]): ?>
									<span class="post__del">
										<i class="fas fa-trash-alt" data-post-id="<?= $post["id"] ?>"></i>
									</span>
								<?php endif; ?>
								</div>
                            </div>
                        </div>
					<?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

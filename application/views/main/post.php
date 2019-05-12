<section id="post">
	<div class="container">
		<div class="row">
			<div class="col-5"></div>
			<div class="col-7">
				<div class="info__block post-page">
					<h3 class="post-page__title"><?= $post["title"] ?></h3>
					<div class="post-page__content">
						<p class="post-page__text"><?= $post["description"] ?></p>
						<img class="post-page__image" src="<?= $post["img"] ?>" alt="Camagru Image">
					</div>
					<div class="post__stat">
                        <?php if (in_array($post["id"], $likedPosts)): ?>
                            <a href="#" class="post__like active" data-post-id="<?= $post["id"] ?>">
                        <?php else: ?>
                            <a href="#" class="post__like" data-post-id="<?= $post["id"] ?>">
                        <?php endif; ?>
                            <i class="fa fa-thumbs-up like active"></i>
                            <?= $post["likes"] ?>
                        </a>
                        <span class="post__comment"><i class="fas fa-comment comment"></i><?= $post["comments"] ?></span>
					</div>
				</div>
                <div class="info__block post-comment">
                    <h3 class="post-page__title">Comments</h3>
                    <div class="post-comment__list"></div>
                    <form class="post-comment__form">
                        <input type="hidden" name="csrf" value="<?= $_SESSION["csrf"] ?>">
                        <textarea name="comment" placeholder="Comment this post" required></textarea>
                        <button class="button button-transparent">Comment</button>
                    </form>
                </div>
            </div>
		</div>
	</div>
</section>